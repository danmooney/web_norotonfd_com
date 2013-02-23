<?php
namespace Moo\Operations;

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
                ->order('ordering ASC');

            $this->_db->setQuery($query);

            $this->_data = $this->_db->loadObjectList();
        }

        return $this->_data;
    }
}