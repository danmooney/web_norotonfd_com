<?php
namespace Moo\News;

defined('_JEXEC') or die('Restricted Access');

abstract class ModelAbstract extends \JModel implements ModelInterface
{
    protected $_table = '#__moo_news';
    protected $_db;
    protected $_data;

    public function __construct()
    {
        $this->_db = \JFactory::getDBO();
    }
}