<?php
/**
 * @var $params JRegistry
 */

defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');

$helper = new MooViewHelper();

require JModuleHelper::getLayoutPath('mod_moo_social');