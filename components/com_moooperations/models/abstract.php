<?php

namespace Moo\Operations;

abstract class ModelAbstract implements ModelInterface
{
    protected $_table = '#__moo_operation';
    protected $_db;

    public function __construct()
    {
        $this->_db = \JFactory::getDBO();
    }
}