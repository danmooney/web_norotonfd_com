<?php
namespace Moo\Gallery;

defined('_JEXEC') or die('Restricted Access');

class ModelList extends ModelAbstract
{
    public function getData()
    {
        if (!isset($this->_data)) {
            $query = $this->_db->getQuery(true);
            $query
                ->select('*')
                ->from($this->_table)
                ->where('published = 1')
                ->order('date DESC');

            $this->_db->setQuery($query);

            $data = $this->_db->loadObjectList();

            $this->_data = $data;
        }

        return $this->_data;
    }
}