<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<?php
if (!empty($container_class)) :
?>
<div class="<?php echo $container_class ?>">
<?php
endif
?>
<ul class="menu<?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id') != NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php

// get number of parent items
$parent_count = 0;

$child_parent_map = array();
$child_parent_name_map = array();

$debug_bool = (isset($list[0]) && $list[0]->menutype === 'bottom-main-menu');

foreach ($list as $i => $item) {

//    if (true === $debug_bool) {
//        echo '<pre>';
//        debug($list);
//    }

    if ($item->parent && (int) $item->level === 1) {   // item is parent
        $child_parent_name_map[$item->id] = $item->title;
        $parent_count += 1;
    } else {
        if (!isset($child_parent_map[$item->parent_id])) {
            $child_parent_map[$item->parent_id] = 0;
        }
        $child_parent_map[$item->parent_id] += 1;
    }
}

if (true === $debug_bool) {
//    debug($parent_count);
//    echo '<pre>';
//    debug($child_parent_name_map, false);
//    debug($child_parent_map, false);
}



$largest_child_item_count = count($child_parent_map) > 0
    ? max($child_parent_map)
    : 0;

if (count($visual_equipment_categories_arr) > $largest_child_item_count) {
    $largest_child_item_count = count($visual_equipment_categories_arr);
}

//if (true === $debug_bool) {
//    echo $largest_child_item_count;
//    die;
//}

$parents_output = 0;

$current_parent_id = 0;
$current_children_count = 0;

$iterator_count = 0;

if (true === $visual_equipment_is_active_page_bool || (true === $resellers_is_active_page_bool && false === $is_main_menu_bool)) {
    if (true === $is_main_menu_bool) {
        $html  = '';

        foreach ($visual_equipment_categories_arr as $category) {
            $href = MooEquipmentHelperUrl::formatUrl(array (
                'category_id' => $category->category_id,
                'view' => 'list'
            ), array (
                'item_id',
                'subcategory_id'
            ));

//            $active_class_str = (0 === $equipment_iterator)
//                ? 'class="active"'
//                : '';

            $active_class_str = ((int) $category->category_id === $category_id)
                ? 'class="active"'
                : '';

            $html .= '<li ' . $active_class_str . '>'
                  .  '<a href="' . $href . '">';


            $html .=  $category->title
                  .  '</a>'
                  .  '</li>';

        }

        $all_href = MooEquipmentHelperUrl::formatUrl(array (
            'view' => 'list',
//            'category_id' => 1000
        ), array (
            'category_id',
            'subcategory_id',
            'item_id'
        ));

        $active_class_str = (0 === (int) $category_id)
            ? 'class="active"'
            : '';

        $html .= '<li ' . $active_class_str . '>'
            . '<a href="' . $all_href . '">'
            . 'All'
            . '</a>'
            . '</li>';

        echo $html;
    } else if (true === $is_main_submenu_bool) {
        $html = '';

        $input = JFactory::getApplication()->input;
        $equipment_category_id = $input->get('category_id', null, 'int');

        if (false === $resellers_is_active_page_bool) {
            $active_class_str = (null === $subcategory_id)
                ? 'class="active"'
                : '';

            $url_arr = array ();

            if ($equipment_category_id) {
                $url_arr['category_id'] = $equipment_category_id;
            }

            $href = MooEquipmentHelperUrl::formatUrl($url_arr, array (
                'subcategory_id',
                'item_id'
            ));

            $html .=  '<li ' . $active_class_str . '>'
                  .   '<a href="' . $href . '">'
                  .   'Full Collection'
                  .   '</a>'
                  .   '</li>';
        }



        foreach ($visual_equipment_subcategories_arr as $subcategory) {
            $active_class_str = '';
            if (true === $resellers_is_active_page_bool) {
                $href = MooResellersHelperUrl::formatUrl(array (
                    'category_id' => $subcategory->category_id
                ));

                if ((int) $subcategory->category_id === $category_id) {
                    $active_class_str = 'class="active"';
                }

            } else {
                $url_arr = array (
                    'subcategory_id' => $subcategory->subcategory_id,
                    'view' => 'list'
                );

                if ($equipment_category_id > 0) {
                    $url_arr['category_id'] = $equipment_category_id;
                }

                $href = MooEquipmentHelperUrl::formatUrl($url_arr, array (
                    'item_id'
                ));

                $active_class_str = ((int) $subcategory->subcategory_id === $subcategory_id)
                    ? 'class="active"'
                    : '';
            }

            $html .= '<li ' . $active_class_str . '>'
                  .  '<a href="' . $href . '">'
                  .  $subcategory->title
                  .  '</a>'
                  .  '</li>';
        }
        echo $html;
    }
} else {
    foreach ($list as $i => &$item) :
        if ($visual_equipment_menu_id === (int) $item->id &&
            false === $is_main_menu_bool &&
            true === $sef_rewrite_bool
        ) {
            $category_id = (int) $item->query['category_id'];
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('alias')
               ->from('#__moo_visual_equipment_category')
               ->where('category_id = ' . $category_id);

            $db->setQuery($query);

            $result = $db->loadResult();

            $item->flink .= '/' . $result;
        }

        $item_next = isset($list[$iterator_count + 1])
            ? $list[$iterator_count + 1]
            : null;

        $class = 'item-' . $item->id;
        if ($item->id == $active_id) {
            $class .= ' current';
        }

        if (in_array($item->id, $path)) {
            $class .= ' active ';
        }
        elseif ($item->type == 'alias') {
            $aliasToId = $item->params->get('aliasoptions');
            if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
                $class .= ' active';
            }
            elseif (in_array($aliasToId, $path)) {
                $class .= ' alias-parent-active';
            }
        }

        if ($item->deeper) {
            $class .= ' deeper';
        }

        if ($item->parent) {
            $class .= ' parent';
            if ((int) $item->level === 1) {
                $parents_output += 1;
                if ($parent_count === $parents_output) {    // if last parent, add 'last' class
                    $class .= ' last';
                }
            }
        }

        if (!empty($class)) {
            $class = ' class="' . trim($class) . '"';
        }

        if (empty($item->parent)  || (!empty($item_next) || empty($item_next->parent)) && !$item->deeper) {
            $current_children_count += 1;
        }

        echo '<li' . $class . '>';


        // Render the menu item.
        switch ($item->type) :
            case 'separator':
            case 'url':
            case 'component':
                require JModuleHelper::getLayoutPath('mod_moo_menu', 'default_' . $item->type);
                break;

            default:
                require JModuleHelper::getLayoutPath('mod_moo_menu', 'default_url');
                break;
        endswitch;

        // VISUAL EQUIPMENT OUTPUT
        if (true === $visual_equipment_bool && (int) $item->id === $visual_equipment_menu_id) {
            if (!empty($visual_equipment_categories_arr)) {
                echo '<ul>';
                $current_children_count = 0;
                foreach ($visual_equipment_categories_arr as $category) {
                    echo '<li>'
                        .    '<a href="' . MooEquipmentHelperUrl::formatUrl(array (
                        'category_id' => $category->category_id,
                        'view' => 'list',
                        'Itemid' => $visual_equipment_menu_id
                    ), array (
                        'carousel_id'
                    ))  . '">'
                        .        $category->title
                        .    '</a>'
                        .    '</li>';
                    $current_children_count += 1;
                }
                while ($current_children_count < $largest_child_item_count) {
                    echo '<li class="invisible"><a href="#">*</a></li>';
                    $current_children_count += 1;
                }
                $current_children_count = 0;
                echo '</ul>';
            }
        }

        // The next item is deeper.
        if ($item->deeper) {
            echo '<ul>';
        }

        // The next item is shallower.
        elseif ($item->shallower) {
            if (122 == (int) $item->id) { // Warranty, the culprit in this debug session
//                echo '<pre>' . $current_children_count;
//
//                debug($item, false);
//                debug($item_next);

            }
            echo '</li>';
            if (/*is_null($item_next) || $item_next->parent*/ true === true) {
                while ($current_children_count < $largest_child_item_count) {
                    echo '<li class="invisible"><a href="#">*</a></li>';
                    $current_children_count += 1;
                }

                $current_children_count = 0;
            }
            echo str_repeat('</ul></li>', $item->level_diff);
        }
        // The next item is on the same level.
        else {
            echo '</li>';
        }

        if (!is_null($item_next) && $params->get('useSeparators')) {
            echo '<span class="separator">|</span>';
        }

        $iterator_count += 1;
    endforeach;
}

?></ul>
<?php
if (!empty($container_class)) :
    ?>
</div>
<?php
endif
?>