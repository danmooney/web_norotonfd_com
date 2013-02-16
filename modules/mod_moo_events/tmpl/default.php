<?php

defined('_JEXEC') or die('Restricted Access');

?>

<div class="events-header-container">
    <div class="events-header">
        <?= $model->getTitle() ?>
    </div>
    <div class="more-button">
        <a href="#">More</a>
    </div>
</div>

<div class="event-carousel-container event-carousel-container-<?= $model->getType() ?>">
    <div class="circle circle-left">
        <div class="arrow arrow-left"></div>
    </div>
    <div class="overflow-buffer overflow-buffer-left"></div>

    <?php
        include $model->getType() . '.php';
    ?>
    <div class="circle circle-right">
        <div class="arrow arrow-right"></div>
    </div>
    <div class="clr"></div>
    <?php
        if (count($model->getEvents()) > 1):
        for ($i = 0; $i < count($model->getEvents()); $i += 1): ?>
            <div class="circle-small"></div>
    <?php
        endfor;
        endif ?>
    <div class="overflow-buffer overflow-buffer-right"></div>
</div>
<div class="clr"></div>
