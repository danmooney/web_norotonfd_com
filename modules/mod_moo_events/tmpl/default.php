<?php

defined('_JEXEC') or die('Restricted Access');

?>

<div class="events-header">
    <?= $model->getTitle() ?>
</div>

<?php
require_once($model->getType() . '.php');