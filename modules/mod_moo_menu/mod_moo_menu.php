<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

if (!function_exists('debug')) {
    function debug($string, $dieBool = true) {
        if ($_SERVER['REMOTE_ADDR'] !== '71.9.158.10' &&
            $_SERVER['REMOTE_ADDR'] !== '108.200.220.35'
        ) {
            return;
        }

        print_r($string);
        if (true === $dieBool) {
            die;
        }
    }
}

// Include the syndicate functions only once
require_once dirname(__FILE__) . DS . 'helper.php';

$list	         = modMooMenuHelper::getList($params);
$visual_equipment_categories_arr = modMooMenuHelper::getVisualEquipmentCategories();
$visual_equipment_menu_id = 114;
$resellers_menu_id = 119;

$app	         = JFactory::getApplication();
$menu	         = $app->getMenu();
$active	         = $menu->getActive();

$menutype_str = $params->get('useActiveMenu')
    ? $active->menutype
    : $params->get('menutype');

$visual_equipment_bool                = false;
$visual_equipment_is_active_page_bool = false;
$is_main_menu_bool                    = false;
$is_main_submenu_bool                 = false;
$resellers_is_active_page_bool        = false;

$config = JFactory::getConfig();
$sef_rewrite_bool = (bool) $config->get('sef_rewrite');

$input = JFactory::getApplication()->input;
$category_id    = $input->get('category_id', null, 'int');
$subcategory_id = $input->get('subcategory_id', null, 'int');

// TODO - disgusting hack!  But might have to do to achieve true sef/non-sef interoperability
if (true === $sef_rewrite_bool) {
    $request = str_replace(JURI::base(true), '', $_SERVER['REQUEST_URI']);
    if (substr_count($request, '/') === 1) {
        $category_id = 0;
        $input->set('category_id', 0);
    }
}


if ($menutype_str === 'bottom-main-menu') {
    $visual_equipment_bool = true;
    // check if active page is visual equipment
    if ((int) $active->id === $visual_equipment_menu_id ||
        (int) $active->id === $resellers_menu_id
    ) {
        if ('main-menu-container' === $params->get('menuContainerClass')) {
            $is_main_menu_bool = true;
            if ((int) $active->id === $resellers_menu_id) {
                $resellers_is_active_page_bool = true;
            } else {
                $visual_equipment_is_active_page_bool = true;
            }

        } elseif ('main-submenu-container' === $params->get('menuContainerClass')) {
            $is_main_submenu_bool = true;
            if ((int) $active->id === $resellers_menu_id) {
                $resellers_is_active_page_bool = true;
                $visual_equipment_subcategories_arr = $visual_equipment_categories_arr;
            } else { // visual equipment page
                $visual_equipment_is_active_page_bool = true;
                $visual_equipment_subcategories_arr = modMooMenuHelper::getVisualEquipmentSubcategories();
            }
        }
    }
}


$active_id       = isset($active) ? $active->id : $menu->getDefault()->id;
$path	         = isset($active) ? $active->tree : array();
$showAll	     = $params->get('showAllChildren');
$class_sfx	     = htmlspecialchars($params->get('class_sfx'));
$container_class = htmlspecialchars($params->get('menuContainerClass'));

if (true === $resellers_is_active_page_bool) {
    require_once(JPATH_SITE . DS . 'components' . DS . 'com_mooresellers' . DS . 'helpers' . DS . 'url.php');
} elseif (true === $visual_equipment_bool) {
    require_once(JPATH_SITE . DS . 'components' . DS . 'com_moovisualequipment' . DS . 'helpers' . DS . 'url.php');
}

if (count($list) || true === $visual_equipment_is_active_page_bool) {
	require JModuleHelper::getLayoutPath('mod_moo_menu', $params->get('layout', 'default'));
}
