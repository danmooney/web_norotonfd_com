<?php
namespace Moo\Gallery;

defined('_JEXEC') or die('Restricted Access');

abstract class ModelAbstract extends \JModel implements ModelInterface
{
    protected $_table = '#__moo_gallery';
    protected $_db;
    protected $_data;

    public function __construct()
    {
        $this->_db = \JFactory::getDBO();
    }
}