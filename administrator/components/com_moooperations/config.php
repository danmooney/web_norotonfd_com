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
                'submenu_title' => 'Operations',
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
                        'o.features',
                        'o.ordering',
                        'o.published',
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'title',
                        'text',
                        'category',
                        'features'
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
                        'features' => array (
                            'sort' => true,
                            'formatter' => 'nl2br',
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
                        'features' => array (
                            'formatter' => 'textarea'
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
            'text' => array (
                'link' => 'index.php?option=com_moooperations&type=text&task=edit&cid[]=1',
                'always_show_submenu' => true,
                'toolbar' => array (
                    'edit' => array (
                        'apply'
                    ),
                    'default' => array (
                        'apply',
                    )
                ),
                'title' => 'Text',
                'table' => 'moo_operation_text',
                'alias' => 't',
                'model' => array (
                    'selects'  => array (
                        '*',
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'before_text',
                        'after_text',
                    ),
                ),
                'view' => array (
                    'single' => array (
                        'before_text' => array (
                            'formatter' => 'textarea',
                            'allow_html' => true,
                            'heading' => 'Text Before Fleet Operations List'
                        ),
                        'after_text' => array (
                            'formatter' => 'textarea',
                            'allow_html' => true,
                            'heading' => 'Text After Fleet Operations List'
                        ),
                        'operation_text_id' => array (
                            'formatter' => 'hidden',
                            'value' => 1
                        )
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