<?php

namespace Moo\Gallery;

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

    public function __construct(\MooViewHelper $helper, \stdClass $row)
    {
        $this->helper = $helper;
        $this->row = $row;

        parent::__construct();
    }
}