<?php

class MooHelper
{
    public static function renderPage()
    {
        $task = MooConfig::get('task');
        $arr_current_page = MooConfig::get('arr_current_page');
        if ($task == 'default' || 
            $task == 'cancel' ||
            isset($arr_current_page['always_show_submenu'])
        ) {
            self::renderSubmenu();
        }
        self::renderToolbar();
        self::requireController();
    }
    
    public static function restrictAccess()
    {
        defined('_JEXEC') or die('Restricted Access');
    }
    
    public static function makeReadable($string)
    {
        $string = ucwords(str_replace('_', ' ', $string));
        return $string;
    }

    public static function makeUrlFriendly($string) {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $string), '-'));
    }
    
    public static function getUserState($thing, $default, $type)
    {
        $app = JFactory::getApplication();
//        debug(MooConfig::get('option') . MooConfig::get('current_page') . '_' . $thing, false);
//        debug($default, false);
//        debug($type);

        return $app->getUserStateFromRequest(MooConfig::get('option') . MooConfig::get('current_page') . '_' . $thing, $thing, $default, $type);
    }
    
    public static function populateWhereFields()
    {
        $where_fields = array();
        $arr_page = MooConfig::get('arr_current_page');
        if (!empty($arr_page) && isset($arr_page['model']['where_fields'])) {
            $table = self::getCurrentTable();
            foreach ($arr_page['model']['where_fields'] as $key => $value) {
                if (!stristr($value, '#__')) {
                    if ($alias = self::getAliasByName($value)) {
                        $where_fields[] = $alias . '.' . $value;
                    } elseif (@MooHelper::getCurrentTable()->$value) {
                        $where_fields[] = $table . '.' . $value;
                    } else {
                        $where_fields[] = $value;
                    }
                } else {
                    $where_fields[] = $value;
                }
            }
        }
        return $where_fields;
    }

    public static function getCurrentTable()
    {
        $arr_page = MooConfig::get('arr_current_page');
        if (isset($arr_page['table'])) {
            $table = str_replace('#__#__', '#__', '#__' . $arr_page['table']);
        } elseif (MooConfig::get('default_page') == MooConfig::get('current_page')) {
            $table = '#__' . MooConfig::get('default_page');
        } else {
            $table = '#__' . MooConfig::get('default_page') . '_' . MooConfig::get('current_page');
        }    
        return $table;
    }
    
    /**
     * Get primary key of table
     * 
     * Straying away from using 'id' for every primary key in every table
     */
    public static function getPrimaryKey($table_str = null)
    {
        if (is_null($table_str)) {
            $table_str = MooHelper::getCurrentTable();
        }
        
        self::fixTableNameIfNecessary($table_str);
        
        $table = new MooTable($table_str);
        
        return $table->primary_key;
    }
    
    public static function getDefault($type, $page = null, $default_if_all_else_fails = '')
    {
        $pages = MooConfig::get('pages');
        if (is_null($page)) {
            $page = MooConfig::get('current_page');
        } else {
            $page = @$pages[$page];
            if (!@$page) {
                throw new Exception('Page not found in ' . get_called_class() . ' called with arguments ' . var_dump(func_get_args()));
            }
        }
        
        $str_key = 'default_' . $type;
        
        if (isset($pages[$page][$str_key])) {
            return $pages[$page][$str_key];                
        } else {
            return $default_if_all_else_fails;
        }
    }
    
    public static function makeSingular($string)
    {
        $pages = MooConfig::get('pages');
        if (isset($pages[$string]['singular'])) {
            return $pages[$string]['singular'];
        } elseif (substr($string, strlen($string) - 1, strlen($string)) == 's') {
            return substr($string, 0, strlen($string) - 1);
        } else {
            return $string;
        }
    }
    
    public static function checkImage(&$image_src, $use_thumb = false, $width = '', $height = '')
    {
        if (!$image_src) {
            return false;
        }

        $image_src = trim($image_src);

        $arr_current_page = MooConfig::get('arr_current_page');
        $file_folder_name = isset($arr_current_page['file_folder'])
            ? $arr_current_page['file_folder']
            : MooConfig::get('current_page');

        $path = JPATH_SITE . DS . 'images' . DS . $file_folder_name . DS .  $image_src;

        if (file_exists($path)) {
            if ($use_thumb === true) {
                $thumb_path = 'thumbs' . '/';
                $path = JPATH_SITE . DS . 'images' . DS . $file_folder_name . DS . str_replace('/', DS, $thumb_path) . $image_src;
                if (!file_exists($path)) {
                    $image_src = '';
                    return;
                }
            } else {
                $thumb_path = '';
            }

            // TODO - allow dynamic resizing based on single param passed
            list($img_width, $img_height) = getimagesize($path);

            if (empty($width) && !empty($height)) {

            }

            if (!empty($width) && empty($height)) {

            }

            if ($width) {
                $width = 'width="' . $width . '"';
            }
            
            if ($height) {
                $height = 'height="' . $height . '"';
            }

            $image_src = "<img " . $width . " " . $height . " src='" . JURI::root() . 'images' . '/' . $file_folder_name . '/' . $thumb_path . $image_src .  "' />";
            return true;
        } else {
            $image_src = NULL;
            return false;
        }
    }
    
    /**
     * Prepend '#__' if necessary
     */
    public static function fixTableNameIfNecessary(&$table)
    {
        if (substr($table, 0, 3) !== '#__') {
            $table = '#__' . $table;
        }
    }
    
    public static function getRows($table = NULL, $field = NULL, $where = NULL, $order = NULL, $key = NULL)
    {
        if (is_null($table)) {
            $table = self::getCurrentTable();
        }
        
        self::fixTableNameIfNecessary($table);
        
        $db =& JFactory::getDBO();
        $query = 'SELECT * FROM ' . $table . ' ' . $where . ' ' . $order;
        $db->setQuery($query);

        if (is_null($field)) {
            return $db->loadObjectList($key);
        } else {
            if (is_null($key)) {
                $key = self::getPrimaryKey($table);
            }
            $results   = $db->loadObjectList($key);
            $field_arr = array();
            if (!empty($results)) {
                foreach ($results as $result) {
                    if (isset($result->$key)) {
                        $field_arr[$result->$key] = $result->$field;
                    }
                }
            }
            return $field_arr;                   
        }
    }
    
    public static function createSelects()
    {
        $arr_page = MooConfig::get('arr_current_page');
        if (isset($arr_page['model']['selects'])) {
            $selects = '';
            $selects_count = count($arr_page['model']['selects']);
            $count = 0;
            foreach ($arr_page['model']['selects'] as $key => $value) {
                $count += 1;
                if (is_numeric($key)) {
                    $selects .= $value;
                } else {
                    $selects .= $key . ' AS ' . $value;
                }
                
                if ($count != $selects_count) {
                    $selects .= ', ';
                }
            }
            return $selects;
        } else {
            return NULL;
        }
    }
    
    public static function createJoins()
    {
        $arr_page = MooConfig::get('arr_current_page');
        if (isset($arr_page['model']['joins'])) {
            return implode(' ', $arr_page['model']['joins']);
        }
    }
    
    public static function createGroupBy()
    {
        $arr_page = MooConfig::get('arr_current_page');
        if (isset($arr_page['model']['group_by'])) {
            return ' GROUP BY ' . $arr_page['model']['group_by'];
        }    
    }
    
    public static function getExtraQueries()
    {
        $arr_page = MooConfig::get('arr_current_page');
        if (isset($arr_page['model']['extra_queries'])) {
            return $arr_page['model']['extra_queries'];
        }
    }
    
    public static function getTableNameForOrdering($name)
    {
        $arr_page =  MooConfig::get('arr_current_page');
        $all_view =& $arr_page['view']['all'];

        if ($alias = self::getAliasByName($name)) {
            return $alias . '.' . $name;
        } elseif (isset($all_view[$name]['table'])) {
            return $all_view[$name]['table'] . '.' . $name;
        } else if (@MooHelper::getCurrentTable()->$name) {
            return self::getCurrentTable()   . '.' . $name;
        } else {
            return $name;
        }
    }

    /**
     * Return main table alias if exists
     * @return string 'AS {alias}' if exists, otherwise, ''
     */
    public static function getAlias()
    {
        $arr_page = MooConfig::get('arr_current_page');
        if (isset($arr_page['alias'])) {
            return 'AS ' . $arr_page['alias'];
        }
        
        return '';
    }
    
    /**
     * Return alias by name from select list
     */
    public static function getAliasByName($name)
    {
        $arr_page = MooConfig::get('arr_current_page');

        foreach ($arr_page['model']['selects'] as $select) {
            if (!stristr($select, '.')) {
                continue;
            }
            
            $split_select = explode('.', $select);
            if (@$split_select[1] === $name) {
                return $split_select[0];
            }
        }
    }
    
    public static function getColumnName($field)
    {
        if (stristr($field, '.')) {
            $field = explode('.', $field);
            return $field[1]; 
        }
    }
    
    public static function loadModel($name = 'single')
    {
        require_once(dirname(__FILE__) . DS . 'models' . DS . $name . '.php');
        $class_string = 'MooModel' . ucfirst($name);
        return new $class_string;
    }
    
    public static function checkIfImage($file)
    {
        $arr_current_page = MooConfig::get('arr_current_page');
        if (isset($arr_current_page['view']['single'][$file]['image'])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Takes possibly malformed URL and turns it into appropriate external URL
     * 
     * @param  string $url to make well formed
     * @param  string $scheme
     * @return string Well-formed url
     */
    public static function linkify($url, $scheme = 'http')
    {
        if (preg_match('/^([\w]+:\/\/)/', $url)) {     //if any scheme matches in the beginning (i.e. http://, you://, eeewie://, it will assume it is properly formed and return $url)
            return $url;
        }
        
        return $scheme . '://' . $url;
    }
    
    private static function renderSubmenu()
    {
        $pages = MooConfig::get('pages');
        if (count($pages) > 1) {
            foreach ($pages as $key => $page) {
                if (!isset($page['submenu_title'])) {
                    $key_readable = self::makeReadable($key);    
                } else {
                    $key_readable = $page['submenu_title'];
                }
                $bool_current = ($key == MooConfig::get('current_page')) ? true : false;
                JSubMenuHelper::addEntry(JText::_($key_readable), 'index.php?option=' . MooConfig::get('option') . '&type=' . $key, $bool_current);
            }
        }
    } 
     
    private static function renderToolbar($title_image = 'generic.png')
    {
        $arr_page        = MooConfig::get('arr_current_page');
        $current_page    = MooConfig::get('current_page');
        $default_toolbar = MooConfig::get('default_toolbar');
        $task            = MooConfig::get('task');
        
        if (isset($arr_page['toolbar'])) {
            $toolbar_config = $arr_page['toolbar'];
        } else {
            $toolbar_config = $default_toolbar;
        }
        
        if (empty($toolbar_config[$task])) {
            $task = 'default';
        }
        
        foreach ($toolbar_config[$task] as $key => $config) {
            if (is_numeric($key)) {
                JToolbarHelper::$config();
            } else {

                if ($key === 'apply') {
                    JToolbarHelper::$key($key, $config);
                } else {
                    JToolbarHelper::$key($config);
                }
            }
        }
        
        if ($task == 'default' && 
            isset($arr_page['view']['all']['published'])
        ) {
            JToolbarHelper::publishList();
            JToolbarHelper::unpublishList();
        }
        
        $title = isset($arr_page['title']) 
            ? $arr_page['title']
            : self::makeReadable(self::makeSingular($current_page)) . ' Manager';
            
        JToolBarHelper::title( JText::_($title, $title_image));
    }
    
    private static function requireController()
    {
        require_once(dirname(__FILE__) . DS . 'controllers' . DS . 'controller.php');
    }
}