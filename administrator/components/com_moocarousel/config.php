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
            'moocarousel' => array (
                'title' => 'Main Carousel',
                'file_folder' => 'carousel',
                'table' => 'moo_carousel_image',
                'singular' => 'carousel',
                'submenu_title' => 'Main Carousel',
                'alias' => 'i',
                'default_empty_msg' => 'Sorry, no main carousel images could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        'i.image_id',
                        'i.title',
                        'i.text',
                        'i.filename',
                        'c.carousel_id',
                        'c.title AS carousel_name'
//                        'group_concat(cast(concat(i.image_id, ":", i.filename) AS char) ORDER BY r.ordering, i.image_id ASC SEPARATOR ", ") AS images'
                    ),
                    'joins' => array (
//                        'LEFT JOIN #__moo_carousel_image_ref as r USING (carousel_id)',
                        'LEFT JOIN #__moo_carousel AS c USING (carousel_id)'
                    ),
                    'group_by' => 'carousel_id',
                    'where_fields' => array (
                        'title',
                    ),
                ),
                'view' => array (
                    'all' => array (
                        'title' => array (
                            'link' => true,
                            'sort' => true,
                            'width' => '5%',
                            'align' => 'left'
                        ),
                        'carousel_name' => array(

                        ),
                        'filename' => array (
                            'width' => '5%',
                            'heading' => 'Image',
                            'formatter' => 'image',
                            'link' => true,
                            'use_thumb' => true,
                        ),
                    ),
                    'single' => array (
                        'title' => array (
                            
                        ),
                    )
                ),
                'controller' => array (
                    'table_mapping' => array (
                        '#__moo_carousel_image_ref' => array (
                            'update' => true,
                            'multivalue' => true,
                            'ref' => 'carousel_id',
                            'count' => 'image_id',
                            'image_id' => array (
                                'column' => 'image_id'
                            ),
                            'ordering' => array (
                                'column' => 'ordering',
                            ),
                        )
                    )
                )
            ),

            'images' => array (
                'title' => 'Images',
                'table' => 'moo_carousel_image',
                'singular' => 'image',
                'file_folder' => 'carousel',
                'submenu_title' => 'Images',
                'alias' => 'i',
                'model' => array (
                    'selects'  => array (
                        'i.image_id',
                        'i.filename',
                        'i.use_for_visual_equipment',
                        'group_concat(c.title ORDER BY c.title ASC SEPARATOR "<br />") AS carousels'
                    ),
                    'joins' => array (
                        'LEFT JOIN #__moo_carousel_image_ref as r USING (image_id)',
                        'LEFT JOIN #__moo_carousel AS c USING (carousel_id) '
                    ),
                    'where' => 'WHERE file_type = "image"',
                    'group_by' => 'image_id',
                    'where_fields' => array (
                        'filename',
                        'carousels'
                    ),
                    'pre_hook' => function (&$row) {
                        if (is_null($row->image_id)) {
                            unset($row->ordering);
                        }
                    },
                    'post_hook' => function (&$row) {
                        $arr_current_page = MooConfig::get('arr_current_page');

                        $arr_image_dims = $arr_current_page['view']['single']['filename'];

                        if (is_null($row->size_x)) {
                            $row->size_x = $arr_image_dims['upload_width'];
                        }

                        if (is_null($row->size_y)) {
                            $row->size_y = $arr_image_dims['upload_height'];
                        }
                    }
                ),
                'view' => array (
                    'all' => array (
                        'filename' => array (
                            'width' => '5%',
                            'heading' => 'Image',
                            'formatter' => 'image',
                            'link' => true,
                            'use_thumb' => true,
                        ),
                        'carousels' => array (
                            'heading' => 'Used in the following carousels',
                            'sort' => true
                        ),
                        'use_for_visual_equipment' => array (
                            'heading' => 'Is Being Used For Visual Equipment',
                            'sort' => true,
                            'width' => '4%',
                            'formatter' => 'boolean'
                        ),
                    ),
                    'single' => array (
                        'filename' => array (
                            'formatter' => 'file',
                            'heading' => 'Image',
                            'image' => true,
                            'upload_width' => 1651,
                            'upload_height' => 664,
                            'upload_thumb_width' => 215,
                            'upload_thumb_height' => 122
                        ),
                        'use_for_visual_equipment' => array (
                            'formatter' => 'boolean',
                        ),
                        'carousel' => array (
                            'multivalue' => true,
                            'table' => '#__moo_carousel_image_ref', // used to count number of rows there are in single view
                            'order' => 'carousel_id',               // used for lining up the multifields in the appropriate row
                            'allow_html' => true,
                            'load_css' => array (
                                JURI::base() . 'components/com_moocarousel/assets/css/carousel.css',
                                str_replace('/administrator', '', JURI::base() . 'templates/pilla/css/template.css')
                            ),
                            'load_js' => array (
                                JURI::base() . 'components/com_moocarousel/assets/js/carousel.js'
                            ),
                            'fields' => array (
                                'carousel' => array (
                                    'formatter' => 'select',
                                    'options'   => array (
                                        'table'      => '#__moo_carousel',
                                        'column'     => 'title',
                                        'table_ref'  => '#__moo_carousel_image_ref',
                                        'column_ref' => 'carousel_id'
                                    ),
                                    'use_id_as_value'   => true
                                ),
                                'text' => array (
                                    'formatter' => 'textarea',
                                    'allow_html' => true,
                                    'width' => '50%',
                                    'options' => array (
                                        'table' => '#__moo_carousel_image_ref',
                                        'column' => 'text',
                                        'column_ref' => 'image_id'
                                    )
                                ),
                                'background' => array (
                                    'formatter' => 'select',
                                    'heading' => 'Background',
                                    'options'   => array (
                                        'table'      => '#__moo_carousel_image_ref',
                                        'column'     => 'background',
                                        'column_ref' => 'image_id',
                                    ),
                                    'rows' => array (
                                        'gray' => 'Gray Semi-opaque',
                                        'none' => 'None',
                                        'black' => 'Black Opaque'
                                    ),
                                    'use_id_as_value'   => true
                                ),
                                'text_pos_x' => array (
                                    'options' => array (
                                        'table' => '#__moo_carousel_image_ref',
                                        'column' => 'text_pos_x',
                                        'column_ref' => 'image_id'
                                    ),
                                    'additional_style' => 'width:30px'
                                ),
                                'text_pos_y' => array (
                                    'options' => array (
                                        'table' => '#__moo_carousel_image_ref',
                                        'column' => 'text_pos_y',
                                        'column_ref' => 'image_id'
                                    ),
                                    'additional_style' => 'width:30px'
                                ),
                                'text_width' => array (
                                    'options' => array (
                                        'table' => '#__moo_carousel_image_ref',
                                        'column' => 'text_width',
                                        'column_ref' => 'image_id'
                                    ),
                                    'additional_style' => 'width:30px'
                                ),
                                'text_height' => array (
                                    'options' => array (
                                        'table' => '#__moo_carousel_image_ref',
                                        'column' => 'text_height',
                                        'column_ref' => 'image_id'
                                    ),
                                    'additional_style' => 'width:30px'
                                ),
                                'ordering' => array (
                                    'options' => array (
                                        'table' => '#__moo_carousel_image_ref',
                                        'column' => 'ordering',
                                        'column_ref' => 'image_id'
                                    ),
                                    'additional_style' => 'width:30px;display:none;'
                                ),
                            )
                        ),
                        'file_type' => array (
                            'formatter' => 'hidden',
                            'value' => 'image'
                        )
                    )
                ),
                'controller' => array (
                    'table_mapping' => array (
                        '#__moo_carousel_image_ref' => array (
                            'multivalue' => true,
                            'ref' => 'image_id',
                            'count' => 'carousel_id',
                            'carousel_id' => array (
                                'column' => 'carousel_id',
                            ),
                            'text' => array (
                                'column' => 'text'
                            ),
                            'text_pos_x' => array (
                                'column' => 'text_pos_x'
                            ),
                            'text_pos_y' => array (
                                'column' => 'text_pos_y'
                            ),
                            'text_width' => array (
                                'column' => 'text_width'
                            ),
                            'text_height' => array (
                                'column' => 'text_height'
                            ),
                            'background' => array (
                                'column' => 'background'
                            ),
                            'ordering' => array (
                                'column' => 'ordering'
                            ),
                        )
                    )
                )
            ),
            'videos' => array (
                'title' => 'Videos',
                'table' => 'moo_carousel_image',
                'singular' => 'video',
//                'file_folder' => 'carousel',
                'submenu_title' => 'Videos',
                'alias' => 'v',
                'model' => array (
                    'selects'  => array (
                        'v.image_id',
                        'v.video_href',
                        'group_concat(c.title ORDER BY c.title ASC SEPARATOR "<br />") AS carousels'
                    ),
                    'joins' => array (
                        'LEFT JOIN #__moo_carousel_image_ref as r USING (image_id)',
                        'LEFT JOIN #__moo_carousel AS c USING (carousel_id) '
                    ),
                    'where' => 'WHERE file_type = "video"',
                    'group_by' => 'image_id',
                    'where_fields' => array (
                        'video_href',
                        'carousels'
                    ),
                    'pre_hook' => function (&$row) {
                        if (is_null($row->image_id)) {
                            unset($row->ordering);
                        }
                    },
                    'post_hook' => function (&$row) {
                        $parseYoutubeIdFromUrl = function ($url_str) use (&$row) {
                            $query_str = explode('?', $url_str);
                            if (count($query_str) === 1) {
                                return '';
                            }

                            $old_row = new MooTable();
                            if (is_int($row->image_id)) {
                                $old_row->load($row->image_id);
                            }

                            $query_str = $query_str[1];
                            $key_val_arr = array();

                            parse_str($query_str, $key_val_arr);
                            $youtube_id_str  = $key_val_arr['v'];
                            $row->youtube_id = $youtube_id_str;
                        };

                        if (empty($row->video_href)) {
                            return;
                        }

                        $parseYoutubeIdFromUrl($row->video_href);

                    }
                ),
                'view' => array (
                    'all' => array (
                        'video_href' => array (
                            'width' => '5%',
                            'heading' => 'YouTube Video Link',
                            'sort' => true,
                            'link' => true,
//                            'external_link' => true
                        ),
                        'carousels' => array (
                            'heading' => 'Used in the following carousels',
                            'sort' => true
                        ),
                    ),
                    'single' => array (
                        'video_href' => array (
                            'width' => '5%',
                            'heading' => 'YouTube Video Link',
                        ),
                        'carousel_id' => array (
                            'multivalue' => true,
                            'table' => '#__moo_carousel_image_ref', // used to count number of rows there are in single view
                            'order' => 'carousel_id',               // used for lining up the multifields in the appropriate row
                            'allow_html' => true,
                            'heading' => 'Carousel',
                            'use_id_as_value'   => true,
                            'load_js' => array (
                                JURI::base() . 'components/com_moocarousel/assets/js/carousel.js'
                            ),
                            'fields' => array (
                                'carousel' => array (
                                    'formatter' => 'select',
                                    'options'   => array (
                                        'table'      => '#__moo_carousel',
                                        'external_table_id' => 'image_id',
                                        'column'     => 'title',
                                        'table_ref'  => '#__moo_carousel_image_ref',
                                        'column_ref' => 'carousel_id'
                                    ),
                                    'use_id_as_value'   => true
                                ),
                                'ordering' => array (
                                    'options' => array (
                                        'table' => '#__moo_carousel_image_ref',
                                        'external_table_id' => 'image_id',
                                        'column' => 'ordering',
                                        'column_ref' => 'image_id'
                                    ),
                                    'additional_style' => 'width:30px;display:none;'
                                ),
                            )
                        ),

                        'file_type' => array (
                            'formatter' => 'hidden',
                            'value' => 'video'
                        )
                    )
                ),
                'controller' => array (
                    'table_mapping' => array (
                        '#__moo_carousel_image_ref' => array (
                            'multivalue' => true,
                            'ref' => 'image_id',
                            'count' => 'carousel_id',
                            'carousel_id' => array (
                                'column' => 'carousel_id',
                            ),
                            'ordering' => array (
                                'column' => 'ordering',
                            )
                        )
                    )
                )
            )
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