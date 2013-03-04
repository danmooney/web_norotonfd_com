<?php

namespace Moo\Calendar;

defined('_JEXEC') or die('Restricted Access');


class ViewSingle extends \JView
{
    /**
     * @var \MooViewHelper
     */
    public $helper;

    /**
     * @var \stdClass
     */
    public $row;

    /**
     * @var bool
     */
    public $is_tmpl_component;

    public function __construct(\MooViewHelper $helper, \stdClass $row, $is_tmpl_component)
    {
        $this->helper = $helper;
        $this->row = $row;
        $this->is_tmpl_component = $is_tmpl_component;

        parent::__construct();
    }
}