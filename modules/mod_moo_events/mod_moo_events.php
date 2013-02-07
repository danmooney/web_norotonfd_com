<?php
/**
 * @var $params JRegistry
 */

defined('_JEXEC') or die('Restricted Access');

require_once('model.php');

$model = new ModMooEvents($params);

$model->getEvents();

require JModuleHelper::getLayoutPath('mod_moo_events');