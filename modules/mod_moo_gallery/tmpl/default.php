<?php

defined('_JEXEC') or die('Restricted Access');

$use_carousel = (count($model->getEvents()) > 1);

?>

<div class="events-header-container">
    <div class="events-header">
        <?= $model->getTitle() ?>
    </div>
    <div class="more-button">
        <a href="<?= JRoute::_('index.php?option=com_moogallery&Itemid=144') ?>">More</a>
    </div>
</div>

<div class="event-carousel-container event-carousel-container-<?= $model->getType() ?>">
    <?php
        if ($use_carousel): ?>
            <div class="circle circle-left" style="display:none">
                <div class="arrow arrow-left"></div>
            </div>
    <?php
        endif
?>
    <div class="overflow-buffer overflow-buffer-left"></div>

    <?php
        include $model->getType() . '.php';
        if ($use_carousel):
    ?>
            <div class="circle circle-right" style="display:none">
                <div class="arrow arrow-right"></div>
            </div>
    <?php
        endif
?>
    <div class="clr"></div>
    <div class="circle-small-container">
    <?php
        if ($use_carousel): ?>

        <?php
            for ($i = 0; $i < count($model->getEvents()); $i += 1): ?>
                <div class="circle-small-outer">
                    <div class="circle-small-inner"></div>
                </div>
        <?php
            endfor; ?>

    <?php
        endif ?>
    </div>
    <div class="overflow-buffer overflow-buffer-right"></div>
</div>
<div class="clr"></div>
