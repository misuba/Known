function refreshFeeds() {
    // $.ajax is not helpin us out with non-JSON responses currently
    var oReq = new XMLHttpRequest();
    oReq.addEventListener("load", function() {
        if (this.status !== 200) {
            $('.alert-refresh').html('Failed to refresh.');
            console.debug(this);
        } else {
            // remove notice
            $('.alert-refresh').html('Redrawing from refresh...');
            // get /stream
            var nReq = new XMLHttpRequest();
            nReq.addEventListener("load", function() {
                if (this.status !== 200) {
                    $('.alert-refresh').html('Failed to redraw');
                } else {
                    var $innards = $(this.responseText).find('.container.page-body');
                    $('.container.page-body').html($innards.html());
                }
            });
            nReq.open("GET", wwwroot() + '/stream');
            nReq.send();
        }
    });
    oReq.open("GET", wwwroot() + '/following/refresh');
    oReq.send();
}

$('.container.page-body').prepend(
    $('<div class="alert alert-info col-md-10 col-md-offset-1 alert-refresh">Refreshing your feeds...</div>')
);

refreshFeeds();
