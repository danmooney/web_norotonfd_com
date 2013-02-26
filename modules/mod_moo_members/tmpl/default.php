<?php
defined('_JEXEC') or die('Restricted Access') ?>

<div id="calendar-container" class="box-light-gray">
    <?php include('calendar.php') ?>
</div>
<div class="half-box-container">
    <div id="notices-container" class="box-light-gray half-box float-l">
        <?php include('notices.php') ?>
    </div>
    <div id="documents-container" class="box-light-gray half-box float-r">
        <?php include('documents.php') ?>
    </div>
</div>
<div class="clr"></div>
<script>
    jQuery(document).ready(function ($) {
        var maxHeight = 0;
        $('.half-box').each(function (_, el) {
            el = $(el);
            if (el.height() > maxHeight) {
                maxHeight = el.height();
            }
        });

        $('.half-box').each(function (_, el) {
            el = $(el);
            el.height(maxHeight);
        });

    });
</script>