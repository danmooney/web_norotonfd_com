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
            'moojoin' => array (
                'title' => 'Join Our Crew Submissions',
                'table' => 'moo_join_submission',
                'singular' => 'Join Submission',
                'submenu_title' => 'Join Submissions',
                'default_empty_msg' => 'Sorry, no join our crew submissions could be found!  Please try again.',
                'model' => array (
                    'selects'  => array (
                        '*'
                    ),
                    'joins' => array (
                    ),
                    'where_fields' => array (
                        'first_name',
                        'last_name',
                        'address',
                        'state',
                        'zip',
                        'phone_number',
                        'email',
                        'age',
//                        'occupation',
//                        'birthplace'
                    ),
                ),
                'view' => array (
                    'all' => array (
                        'timestamp' => array (
                            'heading' => 'Submitted On',
                            'link' => true,
                            'sort' => true,
                            'width' => '5%',
                        ),
                        'first_name' => array (
                            'width' => '5%',
                            'sort' => true,
                            'link' => true,
                        ),
                        'last_name' => array (
                            'width' => '5%',
                            'sort' => true,
                            'link' => true
                        ),
                        'address' => array (
                            'width' => '5%',
                            'sort' => true,
//                            'link' => true
                        ),
                        'state' => array (
                            'width' => '5%',
                            'sort' => true
                        ),
                        'zip' => array (
                            'width' => '5%',
                            'sort' => true
                        ),
                        'phone_number' => array (
                            'width' => '5%',
                            'sort' => true
                        ),
                        'email' => array (
                            'sort' => true,
                            'width' => '7%'
                        ),
                        'age' => array (
                            'width' => '2%',
                            'sort' => true
                        ),
//                        'occupation' => array (
//                            'sort' => true
//                        ),
//                        'dob' => array (
//                            'heading' => 'Date of Birth',
//                            'sort' => true,
//                            'width' => '8%'
//                        ),
//                        'birthplace' => array (
//                            'sort' => true
//                        ),
//                        'citizen' => array (
//                            'sort' => true,
//                            'width' => '2%'
//                        ),
                        'published' => array (
                            'width' => '3%',
                            'sort' => true,
                            'heading' => 'Contacted'
                        )
                    ),
                    'single' => array (
                        'timestamp' => array (
                            'heading' => 'Submitted On',
                            'readonly' => true
                        ),
                        'first_name' => array (
                            'readonly' => true
                        ),
                        'last_name' => array (
                            'readonly' => true
                        ),
                        'address' => array (
                            'readonly' => true
                        ),
                        'state' => array (
                            'readonly' => true
                        ),
                        'zip' => array (
                            'readonly' => true
                        ),
                        'phone_number' => array (
                            'readonly' => true
                        ),
                        'email' => array (
                            'readonly' => true
                        ),
                        'age' => array (
                            'readonly' => true
                        ),
//                        'occupation' => array (
//                            'readonly' => true
//                        ),
//                        'dob' => array (
//                            'readonly' => true
//                        ),
//                        'birthplace' => array (
//                            'readonly' => true
//                        ),
//                        'citizen' => array (
//                            'readonly' => true
//                        ),
                        'published' => array (
                            'heading' => 'Contacted',
                            'formatter' => 'boolean'
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