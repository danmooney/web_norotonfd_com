<?php
defined('_JEXEC') or die('Restricted Access');
?>
<ul class="event-carousel event-carousel-general">
    <?php
        foreach ($model->getEvents() as $event_cluster): ?>
            <li>
        <?php
            $i = 0;
            foreach ($event_cluster as $idx => $event):
                $image_exists = is_file(JPATH_SITE . DS . 'images' . DS . $helper->getImgDir() . DS . $event->image);

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
                    <div class="image-container <?= $placeholder_str ?>">
                <?php
                    if ($image_exists): ?>
                        <?= $helper->outputImage($event->image) ?>
                <?php
                    endif ?>
                        <?= $helper->output($event->title, '<div class="overlay"><p>{str}</p></div>') ?>
                    </div>
                </div>
<?php
            $i += 1;
            endforeach; ?>
            </li>
    <?php
        endforeach
?>
</ul>