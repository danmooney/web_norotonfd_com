<?php

defined('_JEXEC') or die('Restricted Access')
?>
<ul class="event-carousel">
    <?php
        foreach ($model->getEvents() as $event_cluster): ?>
            <li>
        <?php
            foreach ($event_cluster as $idx => $event):
?>
                <div class="event">
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
                    endif
?>
<?php
            endforeach; ?>
            </li>
    <?php
        endforeach
?>
</ul>