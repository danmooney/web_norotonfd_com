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
            'moomembers' => array (
                'title' => 'Members Area - Calendar',
                'file_folder' => 'calendar',
                'table' => 'moo_calendar_event',
                'singular' => 'event',
                'submenu_title' => 'Calendar Events',
                'alias' => 'o',
                'default_empty_msg' => 'Sorry, no calendar events could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        '*'
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'location',
                        'email',
                        'phone',
                        'title',
                        'text',
                        'published',
                    ),
                    /**
                     * Store url alias
                     */
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
                            'width' => '2%'
                        ),
                        'time' => array (
                            'width' => '2%'
                        ),
                        'title' => array (
                            'sort' => true,
                            'width' => '3%'
                        ),
                        'location' => array (
                            'sort' => true,
                            'width' => '3%'
                        ),
                        'address' => array (
                            'link' => true,
                            'sort' => true,
                            'width' => '12%',
                        ),
                        'email' => array (
                            'sort' => 'true',
                            'width' => '6%'
                        ),
                        'phone' => array (
                            'sort' => true,
                            'width' => '5%'
                        ),
                        'text' => array (
                            'sort' => true,
                            'width' => '3%'
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
                        'time' => array (
                            'additional_style' => 'width: 70px'
                        ),
                        'title' => array (

                        ),
                        'location' => array (

                        ),
                        'address' => array (

                        ),
                        'email' => array (

                        ),
                        'phone' => array (

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
            'notice' => array (
                'title' => 'Members Area - Notices',
                'file_folder' => 'notices',
                'table' => 'moo_notice',
                'singular' => 'notice',
                'submenu_title' => 'Notices',
                'alias' => 'n',
                'default_empty_msg' => 'Sorry, no notices could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        '*'
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'date',
                        'title',
                        'text',
                    ),
                    /**
                     * Store url alias
                     */
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
                            'sort' => true,
                            'width' => '3%'
                        ),
                        'text' => array (
                            'sort' => true,
                            'width' => '3%'
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
            'documents' => array (
                'title' => 'Members Area - Documents',
                'file_folder' => '../files', // TODO - see if this'll work
                'table' => 'moo_document',
                'singular' => 'document',
                'submenu_title' => 'Documents',
                'alias' => 'd',
                'default_empty_msg' => 'Sorry, no documents could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        '*'
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'date',
                        'title',
                        'filename',
                    ),
                    /**
                     * Store url alias
                     */
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
                            'sort' => true,
                            'width' => '3%'
                        ),
                        'filename' => array (
                            'heading' => 'File',
                            'sort' => true,
                            'link' => true,
                            'width' => '3%'
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
                        'filename' => array (
                            'heading' => 'File',
                            'formatter' => 'file'
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