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
            'moogallery' => array (
                'title' => "Gallery (&quot;What&#8217;s Going On at Noroton&quot;)",
                'file_folder' => 'gallery',
                'table' => 'moo_gallery',
                'singular' => 'gallery',
                'submenu_title' => 'Gallery',
                'alias' => 'g',
                'default_ordering_field' => 'date',
                'default_order' => 'DESC',
                'default_empty_msg' => 'Sorry, no galleries could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        'g.gallery_id',
                        'g.date',
                        'g.title',
                        'g.text',
                        'g.ordering',
                        'g.published',
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
//                        'image' => array(
//                            'heading' => 'Thumbnail',
//                            'link' => true,
//                            'formatter' => 'image',
//                        ),
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
//                        'image' => array (
//                            'heading' => 'Thumbnail',
//                            'formatter' => 'file',
//                            'image' => true,
//                            'upload_thumb_width' => 175,
//                            'upload_thumb_height' => 131
//                        ),
                        'text' => array (
                            'heading' => 'Text to show before gallery',
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
            'images' => array (
                'title' => 'Gallery Images',
                'file_folder' => 'gallery',
                'table' => 'moo_gallery_image',
//                'default_ordering_field' => 'date',
//                'default_order' => 'DESC',
                'singular' => 'image',
                'alias' => 'i',
                'default_empty_msg' => 'Sorry, no images could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        'i.image_id',
                        'g.gallery_id',
                        'g.title AS gallery_title',
                        'i.ordering',
                        'i.published',
                        'i.filename',
                    ),
                    'joins' => array (
                        'LEFT JOIN #__moo_gallery AS g USING (gallery_id)'
                    ),
                    'where_fields' => array (
                        'gallery_title',
                    ),
                    // if is placeholder, set placeholder value to 0 for the existing gallery placeholder image
                    'prehook' => function (&$row) {
                        if (!$row->published)  {
                            return;
                        }

                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        $query
                            ->update('#__moo_gallery_image')
                            ->set('published = 0')
                            ->where('gallery_id = ' . (int) $row->gallery_id)
                            ->where('published = 1');

                        $db->setQuery($query);
                        $db->execute();
                    }
                ),
                'view' => array (
                    'all' => array (
                        'filename' => array(
                            'heading' => 'Image',
                            'link' => true,
                            'formatter' => 'image',
                            'use_thumb' => true
                        ),
                        'gallery_title' => array (
                            'heaidng' => 'Used In Gallery',
                            'sort' => true,
                            'width' => '12%',
                        ),
                        'ordering' => array (
                            'width' => '3%',
                            'sort' => true
                        ),
                        'published' => array (
                            'heading' => "Use for &quot;What's Going On At Noroton&quot;",
                            'width' => '5%',
                            'sort' => true
                        )
                    ),
                    'single' => array (
                        'gallery_id' => array (
                            'heading' => 'Belongs to gallery',
                            'formatter' => 'select',
                            'table'      => '#__moo_gallery',
                            'column'     => 'title',
                            'table_ref'  => '#__moo_gallery_image',
                            'column_ref' => 'gallery_id',
                            'order' => 'title',
                            'use_id_as_value' => true
                        ),
                        'filename' => array (
                            'heading' => 'Image',
                            'formatter' => 'file',
                            'image' => true,
                            'upload_max_width' => 600,
                            'upload_thumb_width' => 201,
                            'upload_thumb_height' => 140
                        ),
                        'ordering' => array (
                            'additional_style' => 'width:30px;'
                        ),
                        'published' => array (
                            'heading' => 'Make Placeholder Image (Will overwrite previouse placeholder for this gallery)',
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