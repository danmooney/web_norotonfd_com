<?php
defined('_JEXEC') or die('Restricted Access');
?>
<ul class="event-carousel event-carousel-general">
    <?php
        foreach ($model->getEvents() as $event_cluster): ?>
            <li>
                <div class="event-container">
        <?php
            $i = 0;
            foreach ($event_cluster as $idx => $event):
                $image_exists = is_file(JPATH_SITE . DS . 'images' . DS . $helper->getImgDir() . DS . 'thumbs' . DS . $event->filename);

                $placeholder_str = !$image_exists
                    ? 'placeholder'
                    : '';

                if ($i === 0) {
                    $idx_str = 'first';
                } else if ($i === count($event_cluster) - 1) {
                    $idx_str = 'last';
                } else {
                    $idx_str = '';
                }
?>
                <div class="event event-general <?= $idx_str ?>">
                    <a href="<?= JRoute::_('index.php?option=com_moogallery&Itemid=144&cid=' . $event->gallery_id) ?>">
                        <div class="image-container <?= $placeholder_str ?>">
                    <?php
                        if ($image_exists): ?>
                            <?= $helper->outputImage($event->filename, null, $helper->getImgDir() . '/thumbs') ?>
                    <?php
                        endif ?>
                            <?= $helper->output($helper->truncate($event->title), '<div class="overlay"><p>{str}</p></div>') ?>
                        </div>
                    </a>
                </div>
<?php
            $i += 1;
            endforeach; ?>
                </div>
            </li>
    <?php
        endforeach
?>
</ul>