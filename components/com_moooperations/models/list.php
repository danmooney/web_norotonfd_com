<?php
namespace Moo\Operations;

defined('_JEXEC') or die('Restricted Access');

class ModelList extends ModelAbstract
{
    public function getData()
    {
        if (!isset($this->_data)) {
            $data = array();

            $query = $this->_db->getQuery(true);
            $query
                ->select('*')
                ->from($this->_table)
                ->where('published = 1')
                ->order('ordering ASC');

            $this->_db->setQuery($query);

            $data['rows'] = $this->_db->loadObjectList();

            $query = $this->_db->getQuery(true);

            $query
                ->select('*')
                ->from($this->_table . '_text');

            $this->_db->setQuery($query);

            $data['text'] = $this->_db->loadObject();

            $this->_data = $data;
        }

        return $this->_data;
    }
}