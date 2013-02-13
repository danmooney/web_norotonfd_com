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

    private static function setupPages()
    {
        /* THIS SHOULD BE THE ONLY THING YOU NEED TO EDIT */
        self::$pages = array (
            'mooevent' => array (
                'title' => 'News & Events',
                'file_folder' => 'events',
                'table' => 'moo_event',
//                'singular' => 'carousel',
//                'submenu_title' => 'Main Carousel',
                'alias' => 'e',
                'default_empty_msg' => 'Sorry, no news or events could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        '*'
//                        'group_concat(cast(concat(i.image_id, ":", i.filename) AS char) ORDER BY r.ordering, i.image_id ASC SEPARATOR ", ") AS images'
                    ),
                    'joins' => array (
//                        'LEFT JOIN #__moo_carousel_image_ref as r USING (carousel_id)',
//                        'LEFT JOIN #__moo_carousel AS c USING (carousel_id)'
                    ),
//                    'where' => 'WHERE carousel_id = 1',
//                    'group_by' => 'carousel_id',
                    'where_fields' => array (
                        'event_type',
                        'title',
                        'summary',
                        'text',
                        'date'
                    ),
                ),
                'view' => array (
                    'all' => array (
                        'date' => array (
//                            'formatter' => 'date',
                            'date_format' => 'F j, Y',
                            'link' => true,
                            'sort' => true,
                            'width' => '6%'
                        ),
                        'event_type' => array (
                            'heading' => 'type',
                            'sort' => true,
                            'width' => '3%'
                        ),
                        'title' => array (
                            'link' => true,
                            'sort' => true,
                            'width' => '12%',
                        ),
                        'summary' => array (
                            'sort' => 'true',
                            'width' => '20%'
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
                        'event_type' => array (
                            'formatter' => 'select',
                            'options' => array (
                                'news' => 'News',
                                'event' => 'Event',
                            ),
                            'use_id_as_value' => true
                        ),
                        'title' => array (
                            
                        ),
                        'summary' => array (
                            'formatter' => 'textarea'
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