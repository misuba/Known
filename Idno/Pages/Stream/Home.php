<?php

    namespace Idno\Pages\Stream {

        use Idno\Entities\Reader\FeedItem;

        class Home extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper();
                $this->setAsset('reader','/js/reader.js','javascript');

                $items = FeedItem::get(array(
                    'owner' => \Idno\Core\site()->session()->currentUserUUID()
                ));
                if (count($items)) {

                    $t = \Idno\Core\site()->template();
                    $t->__(array(
                        'title' => 'Stream',
                        'body'  => $t->__(array(
                            'items' => $items
                        ))->draw('stream/home')
                    ))->drawPage();

                }
                // else {
                //     // show error message
                //     $t = \Idno\Core\site()->template();
                //     $t->__(array(
                //         'title' => 'Stream',
                //         'messages' => array(
                //             'Nothing found. Perhaps you\'d like to follow someone? or have a better uuid'
                //         ),
                //         'body' => ''
                //     ))->drawPage();
                // }

            }

        }

    }
