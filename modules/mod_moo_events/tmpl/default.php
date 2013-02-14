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

<?php
include $model->getType() . '.php'; ?>
<div class="clr"></div>
