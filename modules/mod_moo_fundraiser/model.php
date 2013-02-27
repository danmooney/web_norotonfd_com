<?php

class MooFundraiserModel
{
    private $_table = '#__moo_fundraiser';

    /**
     * @var array
     */
    private $_data;

    public function getData()
    {
        if (!isset($this->_data)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query
                ->select('*')
                ->from($this->_table)
                ->where('published = 1')
                ->order('date DESC');

            $db->setQuery($query);
            $this->_data = $db->loadObjectList();
        }

        return $this->_data;
    }
}