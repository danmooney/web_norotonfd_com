<?php
defined('_JEXEC') or die('Restricted Access');
?>
<ul class="event-carousel">
    <?php
        foreach ($model->getEvents() as $event_cluster): ?>
            <li>
        <?php
            foreach ($event_cluster as $idx => $event):
?>
                <div class="event">
                <?= $helper->outputImage($event->image, '<div class="image-container float-l">{str}</div>') ?>
                <?= $helper->output($event->title, '<h2 class="semibold">{str}</h2>') ?>
                </div>
<?php
            endforeach; ?>
            </li>
    <?php
        endforeach
?>
</ul>