<?php

defined('_JEXEC') or die('Restricted Access')
?>

<ul class="event-carousel event-carousel-detailed">
    <?php
        foreach ($model->getNews() as $event_cluster): ?>
            <li>
                <div class="event-container">
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
                <?= $helper->output($event->title, '<h2 class="heading semibold">{str}</h2>') ?>
                <?= $helper->outputImage($event->image, '<div class="image-container float-l">{str}</div>') ?>
                <?php
                    $image_output_bool = $helper->outputWasSuccess() ?>
                <?= $helper->output(
                        $helper->truncate($event->summary, 150),
                        $image_output_bool
                            ? '<div class="summary-container float-r"><p class="summary">{str}</p></div>'
                            : '<p class="summary">{str}</p>'
                    )
                ?>
                <?php
                    if (/*!empty($event->text)*/ true === true): ?>
                        <div class="more-button">
                            <a href="<?= JRoute::_('index.php?option=com_moonews&nid=' . $event->news_id) ?>">
                                <span>Learn More</span>
                            </a>
                        </div>
                <?php
                    endif ?>
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
                </div>
            </li>
    <?php
        endforeach
?>
</ul>