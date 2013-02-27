<?php

// Make this the only file that needs to be edited

class MooConfig
{
    private static   $initialized;
    private static   $task;
    private static   $type;
    private static   $option;
    private static   $pages;
    private static   $default_toolbar;
    private static   $default_page;
    private static   $default_delete_msg;
    private static   $current_page;
    private static   $arr_current_page;
    private static   $wideimage_path;
    
    public static function initialize()
    {
        if (isset(self::$initialized)) {
            return;
        }
        self::$task   = JRequest::getCmd('task') ? JRequest::getCmd('task') : 'default';
        self::$type   = JRequest::getCmd('type');
        self::$option = JRequest::getCmd('option');
        self::setupDefaults();
        self::setupCurrents();
        self::setupPages();
        self::$arr_current_page = self::$pages[self::$current_page];
        self::$wideimage_path   = JPATH_SITE . DS . 'libraries' . DS . 'wideimage' . DS . 'WideImage.php'; 
    }
    
    public static function get($thing)
    {
        return self::$$thing;
    }

    public static function set($thing, $value)
    {
        self::$$thing = $value;
    }

    private static function setupPages()
    {
        /* THIS SHOULD BE THE ONLY THING YOU NEED TO EDIT */
        self::$pages = array (
            'moofundraiser' => array (
                'title' => 'Fundraiser Events',
                'file_folder' => 'fundraiser',
                'table' => 'moo_fundraiser',
                'singular' => 'Fundraiser Event',
                'default_ordering_field' => 'date',
                'default_order' => 'DESC',
                'alias' => 'n',
                'default_empty_msg' => 'Sorry, no fundraiser events could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        '*'
                    ),
                    'where_fields' => array (
                        'title',
                        'text',
                        'date'
                    ),
                    'pre_hook' => function (&$row) {
                        if (trim($row->title) !== '') {
                            $row->alias = MooHelper::makeUrlFriendly($row->title);
                        }
                    }
                ),
                'view' => array (
                    'all' => array (
                        'date' => array (
                            'date_format' => 'F j, Y',
                            'link' => true,
                            'sort' => true,
                            'width' => '6%'
                        ),
                        'title' => array (
                            'link' => true,
                            'sort' => true,
                            'width' => '12%',
                        ),
                        'text' => array (
                            'sort' => true,
                            'width' => '30%'
                        ),
                        'published' => array (
                            'width' => '5%',
                            'sort' => true
                        )
                    ),
                    'single' => array (
                        'date' => array (
                            'formatter' => 'date'
                        ),
                        'title' => array (

                        ),
                        'text' => array (
                            'formatter' => 'textarea',
                            'allow_html' => true
                        ),
                        'published' => array (
                            'formatter' => 'boolean'
                        )
                    )
                ),
                'controller' => array ()
            ),
        );
    }
    
    private static function setupDefaults()
    {
        self::$default_page = substr(self::$option, 4, strlen(JRequest::getCmd('option')) - 1);
        self::$default_toolbar = array (
            'add' => array (
                'save',
                'apply',
                'cancel'
            ),
            'edit' => array (
                'save',
                'apply',
                'cancel'
            ),
            'default' => array (
                'addNew',
                'editList',
                'deleteList' => 'Are you sure you would like to delete these records?  This operation cannot be undone.',
            )
        );
    }
    
    private static function setupCurrents()
    {
        self::$current_page = self::$type ? self::$type : self::$default_page;
    }
}