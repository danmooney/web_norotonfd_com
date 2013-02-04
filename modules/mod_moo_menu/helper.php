<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @since		1.5
 */
class modMooMenuHelper
{
    /**
     * @var array
     */
    private static $_equipment_categories;

    /**
     * @var array
     */
    private static $_equipment_subcategories;

    public static function getVisualEquipmentCategories()
    {
        if (!isset(self::$_equipment_categories)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('#__moo_visual_equipment_category')
                ->where('published = 1')
                ->order('ordering ASC');

            $db->setQuery($query);

            self::$_equipment_categories = $db->loadObjectList();
        }

        return self::$_equipment_categories;
    }

    public static function getVisualEquipmentSubcategories()
    {
        if (!isset(self::$_equipment_subcategories)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('#__moo_visual_equipment_subcategory')
                ->where('published = 1')
                ->order('ordering ASC');

            $db->setQuery($query);

            $results = $db->loadObjectList();

            $input = JFactory::getApplication()->input;
            $category_id = (int) $input->get('category_id', null, 'int');
            if (is_numeric($category_id)) {
                $query = $db->getQuery(true);
                $query->select('*')
                    ->from('#__moo_visual_equipment')
                    ->where('category_id = '  . $category_id)
                    ->where('published = 1');

                $db->setQuery($query);

                $items = $db->loadObjectList();

                $subcategory_ids_arr = array ();
                foreach ($items as $item) {
                    $subcategory_id = (int) $item->subcategory_id;
                    if (!in_array($subcategory_id, $subcategory_ids_arr)) {
                        $subcategory_ids_arr[] = $subcategory_id;
                    }
                }

                foreach ($results as $num => $result) {
                    if (!in_array($result->subcategory_id, $subcategory_ids_arr)) {
                        unset($results[$num]);
                    }
                }
            }

            self::$_equipment_subcategories = $results;
        }

        return self::$_equipment_subcategories;
    }



	/**
	 * Get a list of the menu items.
	 *
	 * @param	JRegistry	$params	The module options.
	 *
	 * @return	array
	 * @since	1.5
	 */
	static function getList(&$params)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		// If no active menu, use default
		$active = ($menu->getActive())
            ? $menu->getActive()
            : $menu->getDefault();

		$user = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();
		asort($levels);
        $key = 'menu_items' . $params . implode(',', $levels) . '.' . $active->id;
		$cache = JFactory::getCache('mod_moo_menu', '');
		if (!($items = $cache->get($key)))
		{
			// Initialise variables.
			$path		= $active->tree;
			$start		= (int) $params->get('startLevel');
			$end		= (int) $params->get('endLevel');
			$showAll	= $params->get('showAllChildren');
			$items 		= $menu->getItems('menutype', $params->get('useActiveMenu')
                ? $active->menutype
                : $params->get('menutype')
            );

			$lastitem	= 0;

			if ($items) {
				foreach($items as $i => $item)
				{
					if (($start && $start > $item->level)
						|| ($end && $item->level > $end)
						|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
						|| ($start > 1 && !in_array($item->tree[$start-2], $path))
					) {
						unset($items[$i]);
						continue;
					}

					$item->deeper = false;
					$item->shallower = false;
					$item->level_diff = 0;

					if (isset($items[$lastitem])) {
						$items[$lastitem]->deeper		= ($item->level > $items[$lastitem]->level);
						$items[$lastitem]->shallower	= ($item->level < $items[$lastitem]->level);
						$items[$lastitem]->level_diff	= ($items[$lastitem]->level - $item->level);
					}

					$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);

					$lastitem			= $i;
					$item->active		= false;
					$item->flink = $item->link;

					// Reverted back for CMS version 2.5.6
					switch ($item->type)
					{
						case 'separator':
							// No further action needed.
							continue;

						case 'url':
							if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
								// If this is an internal Joomla link, ensure the Itemid is set.
								$item->flink = $item->link.'&Itemid='.$item->id;
							}
							break;

						case 'alias':
							// If this is an alias use the item id stored in the parameters to make the link.
							$item->flink = 'index.php?Itemid='.$item->params->get('aliasoptions');
							break;

						default:
							$router = JSite::getRouter();
							if ($router->getMode() == JROUTER_MODE_SEF) {
								$item->flink = 'index.php?Itemid='.$item->id;
							}
							else {
								$item->flink .= '&Itemid='.$item->id;
							}
							break;
					}

					if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {
						$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
					}
					else {
						$item->flink = JRoute::_($item->flink);
					}

					$item->title        = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
					$item->anchor_css   = htmlspecialchars($item->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
					$item->menu_image   = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
				}

				if (isset($items[$lastitem])) {
					$items[$lastitem]->deeper		= (($start?$start:1) > $items[$lastitem]->level);
					$items[$lastitem]->shallower	= (($start?$start:1) < $items[$lastitem]->level);
					$items[$lastitem]->level_diff	= ($items[$lastitem]->level - ($start?$start:1));
				}
			}

			$cache->store($items, $key);
		}
		return $items;
	}
}
