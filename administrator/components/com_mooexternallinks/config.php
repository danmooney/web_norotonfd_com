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
            'mooexternallinks' => array (
                'title' => 'External Links (for the bottom of the page)',
                'file_folder' => 'operations',
                'table' => 'moo_external_link',
                'singular' => 'External link',
                'submenu_title' => 'External link',
                'default_empty_msg' => 'Sorry, no external links could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        '*'
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'title',
                        'url',
                    ),
                    /**
                     * TODO
                     * Fix url
                     */
                    'pre_hook' => function (&$row) {

                    }
                ),
                'view' => array (
                    'all' => array (
                        'title' => array (
                            'link' => true,
                            'sort' => true,
                            'width' => '5%',
                            'align' => 'left'
                        ),
                        'url' => array (
                            'heading' => 'Links To',
                            'sort' => true,
                            'external_link' => true,
                            'align' => 'left'
                        ),
                        'ordering' => array (
                            'width' => '5%',
                            'sort' => true
                        ),
                        'published' => array (
                            'width' => '5%',
                            'sort' => true
                        )
                    ),
                    'single' => array (
                        'title' => array (
                            
                        ),
                        'url' => array (
                            'heading' => 'Links To'
                        ),
                        'ordering' => array (
                            'additional_style' => 'width:25px;'
                        ),
                        'published' => array (
                            'formatter' => 'boolean'
                        ),
                    )
                ),
                'controller' => array (
                )
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