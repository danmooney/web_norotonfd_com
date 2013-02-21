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
            'moooperations' => array (
                'title' => 'Operations',
                'file_folder' => 'operations',
                'table' => 'moo_operation',
                'singular' => 'operation',
//                'submenu_title' => 'Main Carousel',
                'alias' => 'o',
                'default_empty_msg' => 'Sorry, no operations could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        'o.operation_id',
                        'o.category',
                        'o.thumbnail_image',
                        'o.image',
                        'o.title',
                        'o.text',
                        'o.ordering',
                        'o.published',
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'title',
                        'text',
                        'category'
                    ),
                ),
                'view' => array (
                    'all' => array (
                        'title' => array (
                            'link' => true,
                            'sort' => true,
                            'width' => '5%',
                        ),
                        'category' => array (
                            'heading' => 'Type of Operation',
                            'sort' => true,
                            'width' => '4%'
                        ),
                        'thumbnail_image' => array (
                            'width' => '10%',
                            'heading' => 'Thumbnail Image',
                            'formatter' => 'image',
                            'link' => true,
//                            'use_thumb' => true,
                        ),
                        'image' => array (
                            'width' => '20%',
                            'heading' => 'Image',
                            'formatter' => 'image',
                            'link' => true,
                            'use_thumb' => true,
                        ),
                        'text' => array (
                            'sort' => true,
                            'heading' => 'Text Content'
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
                        'category' => array (
                            'heading' => 'Type of Operation'
                        ),
                        'thumbnail_image' => array (
                            'heading' => 'Thumbnail Image',
                            'formatter' => 'file',
                            'image' => 'true',
                            'upload_width' => 188,
                            'upload_height' => 127,
//                            'upload_thumb_width' => 300
                        ),
                        'image' => array (
                            'heading' => 'Image',
                            'formatter' => 'file',
                            'image' => 'true',
                            'upload_width' => 483,
                            'upload_thumb_width' => 300
                        ),
                        'text' => array (
                            'formatter' => 'textarea',
                            'allow_html' => true,
//                            'load_css' => array (
//                                '../templates/noroton/css/template.css'
//                            )
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
//                    'table_mapping' => array (
//                        '#__moo_carousel_image_ref' => array (
//                            'multivalue' => true,
//                            'ref' => 'carousel_id',
//                            'count' => 'image_id',
//                            'image_id' => array (
//                                'column' => 'image_id'
//                            ),
//                            'ordering' => array (
//                                'column' => 'ordering',
//                            ),
//                        )
//                    )
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