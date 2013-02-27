<?php
/**
 * @var $params JRegistry
 */

defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');
require_once('model.php');

$model = new MooExternalLinksModel();
$links = $model->getData();
$helper = new MooViewHelper();

require JModuleHelper::getLayoutPath('mod_moo_externallinks');