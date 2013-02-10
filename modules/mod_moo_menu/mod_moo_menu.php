<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$list	    = modMooMenuHelper::getList($params);
$app	    = JFactory::getApplication();
$menu	    = $app->getMenu();
$active	    = $menu->getActive();
$active_id  = isset($active) ? $active->id : $menu->getDefault()->id;
$path	    = isset($active) ? $active->tree : array();
$showAll	= $params->get('showAllChildren');
$class_sfx	= htmlspecialchars($params->get('class_sfx'));

$wrap_interval_num = intval($params->get('wrap-items-interval'));
$using_wrap_interval = (bool) $wrap_interval_num;

if (false === $using_wrap_interval) { // avoid division by 0 error
    $wrap_interval_num = 1;
}

if (count($list)) {
	require JModuleHelper::getLayoutPath('mod_moo_menu', $params->get('layout', 'default'));
}
