<?php

    $item = $vars['object'];
    /* @var \Idno\Entities\Reader\FeedItem $item */


    if (!empty($item)) {

        ?>

        <div class="permalink">
            <p>
                <a href="<?= $item->getAuthorURL() ?>"><?= $item->getAuthorName() ?></a>published this
                <a class="u-url url" href="<?= $item->getDisplayURL() ?>" rel="permalink">
                    <time class="dt-published"
                          datetime="<?= date('c', $item->created) ?>"><?= date('c', $item->created) ?></time>
                </a>
            </p>
        </div>
        <div class="interactions">
            <?php
            if ($posse = $item->getPosseLinks()) {

                ?>
                <div class="posse">
                    <a name="posse"></a>

                    <p>
                        Also on:
                        <?php

                            foreach ($posse as $service => $url) {
                                echo '<a href="' . $url . '" rel="syndication" class="u-syndication ' . $service . '">' . $service . '</a> ';
                            }

                        ?>
                    </p>
                </div>
            <?php

            }

            if (!$has_liked) {
                $heart_only = '<i class="icon-star-empty"></i>';
            } else {
                $heart_only = '<i class="icon-star"></i>';
            }
            if ($likes == 1) {
                $heart_text = '1 star';
            } else {
                $heart_text = $likes . ' stars';
            }
            $heart = $heart_only . ' ' . $heart_text;
            if (\Idno\Core\site()->session()->isLoggedOn()) {
                echo \Idno\Core\site()->actions()->createLink(\Idno\Core\site()->config()->getDisplayURL() . 'annotation/post', $heart_only, array('type' => 'like', 'object' => $item->getUUID()), array('method' => 'POST', 'class' => 'stars'));
                ?>
                <a class="stars" href="<?= $item->getDisplayURL() ?>#comments"><?= $heart_text ?></a>
            <?php
            } else {
                ?>
                <a class="stars" href="<?= $item->getDisplayURL() ?>#comments"><?= $heart ?></a>
            <?php
            }
            ?>

            <a class="comments" href="<?= $item->getDisplayURL() ?>#comments"><i class="icon-comments"></i> <?php

                    //echo $replies;
                    if ($replies == 1) {
                        echo '1 comment';
                    } else {
                        echo $replies . ' comments';
                    }

                ?></a>
            <a class="shares" href="<?= $item->getDisplayURL() ?>#comments"><?php if ($shares = $item->countAnnotations('share')) {
                    echo '<i class="icon-arrows-cw"></i> ' . $shares;
                } ?></a>
            <a class="shares" href="<?= $item->getDisplayURL() ?>#comments"><?php if ($rsvps = $item->countAnnotations('rsvp')) {
                    echo '<i class="icon-calendar-empty"></i> ' . $rsvps;
                } ?></a>
        </div>
        <br clear="all"/>
        <?php

        if (\Idno\Core\site()->currentPage()->isPermalink()) {

            if (!empty($likes) || !empty($replies) || !empty($shares) || !empty($rsvps)) {

                ?>

                <div class="annotations">

                    <a name="comments"></a>
                    <?= $this->draw('content/end/annotations') ?>
                    <?php

                        if ($replies = $item->getAnnotations('reply')) {
                            echo $this->__(array('annotations' => $replies))->draw('entity/annotations/replies');
                        }
                        if ($likes = $item->getAnnotations('like')) {
                            echo $this->__(array('annotations' => $likes))->draw('entity/annotations/likes');
                        }
                        if ($shares = $item->getAnnotations('share')) {
                            echo $this->__(array('annotations' => $shares))->draw('entity/annotations/shares');
                        }
                        if ($rsvps = $item->getAnnotations('rsvp')) {
                            echo $this->__(array('annotations' => $rsvps))->draw('entity/annotations/rsvps');
                        }

                    ?>

                </div>

            <?php

            }

            echo $this->draw('entity/annotations/comment/main');

        } else {

            if (\Idno\Core\site()->session()->isLoggedOn()) {
                echo $this->draw('entity/annotations/comment/mini');
            }

        }
    }

?>
