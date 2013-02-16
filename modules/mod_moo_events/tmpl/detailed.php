<?php

defined('_JEXEC') or die('Restricted Access')
?>

<ul class="event-carousel event-carousel-detailed">
    <?php
        foreach ($model->getEvents() as $event_cluster): ?>
            <li>
        <?php
            $i = 0;
            foreach ($event_cluster as $idx => $event):
                if ($i === 0) {
                    $idx_str = 'first';
                } else if ($i === count($event_cluster) - 1) {
                    $idx_str = 'last';
                } else {
                    $idx_str = '';
                }
?>
                <div class="event event-detailed <?= $idx_str ?>">
                <?= $helper->output($event->title, '<h2 class="semibold">{str}</h2>') ?>
                <?= $helper->outputImage($event->image, '<div class="image-container float-l">{str}</div>') ?>
                <?= $helper->output(
                        $event->summary,
                        $helper->outputWasSuccess()
                            ? '<div class="summary-container float-r"><p class="summary">{str}</p></div>'
                            : '<p class="summary">{str}</p>'
                    )
                ?>
                </div>
                <?php
                    if (intval($idx) === 0): ?>
                        <span class="separator"></span>
                <?php
                    endif;

?>
<?php
            $i += 1;
            endforeach; ?>
            </li>
    <?php
        endforeach
?>
</ul>