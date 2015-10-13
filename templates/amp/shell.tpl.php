<!doctype html>
<html amp>
    <head>
        <meta charset="utf-8">
        <title><?= htmlspecialchars($vars['title']); ?></title>
        <link rel="canonical" href="<?php

            /* @var \Idno\Core\Template $this */
            echo $this->getCurrentURLWithoutVar('_t');

        ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
        <script src="https://cdn.ampproject.org/v0.js" async></script>
        <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
        <style>body {opacity: 0}</style><noscript><style>body {opacity: 1}</style></noscript>

        <!-- To silo is human, to syndicate divine -->
        <link rel="alternate" type="application/rss+xml" title="<?= htmlspecialchars($vars['title']) ?>"
              href="<?= $this->getURLWithVar('_t', 'rss'); ?>"/>
        <link rel="feed" type="application/rss+xml" title="<?= htmlspecialchars($vars['title']) ?>"
              href="<?= $this->getURLWithVar('_t', 'rss'); ?>"/>
        <link rel="alternate feed" type="application/rss+xml"
              title="<?= htmlspecialchars(\Idno\Core\site()->config()->title) ?>: all content"
              href="<?= \Idno\Core\site()->config()->getDisplayURL() ?>content/all?_t=rss"/>
        <link rel="feed" type="text/html" title="<?= htmlspecialchars(\Idno\Core\site()->config()->title) ?>"
              href="<?= \Idno\Core\site()->config()->getDisplayURL() ?>content/all"/>

        <!-- Fonts -->
        <link rel="stylesheet"
              href="<?= \Idno\Core\site()->config()->getStaticURL() ?>external/font-awesome/css/font-awesome.css">

        <!-- Webmention endpoint -->
        <link href="<?= \Idno\Core\site()->config()->getURL() ?>webmention/" rel="http://webmention.org/"/>
        <link href="<?= \Idno\Core\site()->config()->getURL() ?>webmention/" rel="webmention"/>

        <?=$this->draw('shell/css');?>

        <?php

            $opengraph = array(
                'og:type'      => 'website',
                'og:title'     => htmlspecialchars(strip_tags($vars['title'])),
                'og:site_name' => htmlspecialchars(strip_tags(\Idno\Core\site()->config()->title)),
                'og:image'     => Idno\Core\site()->currentPage()->getIcon()
            );

            if (\Idno\Core\site()->currentPage() && \Idno\Core\site()->currentPage()->isPermalink()) {

                $opengraph['og:url'] = \Idno\Core\site()->currentPage()->currentUrl();

                if (!empty($vars['object'])) {
                    $owner  = $vars['object']->getOwner();
                    $object = $vars['object'];

                    $opengraph['og:title']       = htmlspecialchars(strip_tags($vars['object']->getTitle()));
                    $opengraph['og:description'] = htmlspecialchars($vars['object']->getShortDescription());
                    $opengraph['og:type']        = 'article'; //htmlspecialchars($vars['object']->getActivityStreamsObjectType());
                    $opengraph['og:image']       = $vars['object']->getIcon(); //$owner->getIcon(); //Icon, for now set to being the author profile pic

                    if ($icon = $vars['object']->getIcon()) {
                        if ($icon_file = \Idno\Entities\File::getByURL($icon)) {
                            if (!empty($icon_file->metadata['width'])) {
                                $opengraph['og:image:width'] = $icon_file->metadata['width'];
                            }
                            if (!empty($icon_file->metadata['height'])) {
                                $opengraph['og:image:height'] = $icon_file->metadata['height'];
                            }
                        }
                    }

                    if ($url = $vars['object']->getDisplayURL()) {
                        $opengraph['og:url'] = $vars['object']->getDisplayURL();
                    }
                }

            }

            foreach ($opengraph as $key => $value)
                echo "<meta property=\"$key\" content=\"$value\" />\n";

        ?>


        <!-- Dublin Core -->
        <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/">
        <meta name="DC.title" content="<?= htmlspecialchars($vars['title']) ?>">
        <meta name="DC.description" content="<?= htmlspecialchars($vars['description']) ?>"><?php

            if (\Idno\Core\site()->currentPage() && \Idno\Core\site()->currentPage()->isPermalink()) {
                /* @var \Idno\Common\Entity $object */
                if ($object instanceof \Idno\Common\Entity) {

                    if ($creator = $object->getOwner()) {
                        ?>
                        <meta name="DC.creator" content="<?= htmlentities($creator->getTitle()) ?>"><?php
                    }
                    if ($created = $object->created) {
                        ?>
                        <meta name="DC.date" content="<?= date('c', $created) ?>"><?php
                    }
                    if ($url = $object->getDisplayURL()) {
                        ?>
                        <meta name="DC.identifier" content="<?= htmlspecialchars($url) ?>"><?php
                    }

                }
            }

        ?>

        <style>
            body {
                font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
            }
        </style>

    </head>
    <body>
        <?= $this->draw('shell/beforecontent') ?>
        <?php

            $body = $vars['body'];
            $body = str_replace('<img','<amp-img',$body);
            $body = str_replace('<iframe','<amp-iframe',$body);
            echo $body;

        ?>
        <?= $this->draw('shell/aftercontent') ?>
    </body>
</html>