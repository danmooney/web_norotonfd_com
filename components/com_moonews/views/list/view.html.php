<?php

namespace Moo\Operations;

defined('_JEXEC') or die('Restricted Access');


class ViewList extends \JView
{
    /**
     * @var \MooViewHelper
     */
    public $helper;

    /**
     * @var array
     */
    public $rows;

    public function __construct(\MooViewHelper $helper, array $rows)
    {
        $this->helper = $helper;
        $this->rows = $rows;

        parent::__construct();
    }

}