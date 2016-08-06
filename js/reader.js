function refreshFeeds() {
    $.ajax(wwwroot() + '/following/refresh', {
        success: function (data) {
            // remove notice
            $('.alert-refresh').html('Redrawing your stream...');
            // get /stream
            $.ajax(wwwroot() + '/stream', {
                success: function(data) {
                    var $innards = $(data).find('.container.page-body');
                    $('.container.page-body').html($innards.html());
                },
                error: function (error) {
                    $('.alert-refresh').html('Failed to redraw.');
                }
            });
        },
        error: function (error) {
            $('.alert-refresh').html('Failed to refresh.');
            console.debug(error);
        }
    });
}

$('.container.page-body').prepend(
    $('<div class="alert alert-info col-md-10 col-md-offset-1 alert-refresh">Refreshing your feeds...</div>')
);
refreshFeeds();
