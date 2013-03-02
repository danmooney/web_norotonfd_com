<?php

namespace Moo\Gallery;

class ModelSingle extends ModelAbstract
{
    /**
     * @var int
     */
    private $_id;

    public function __construct($id)
    {
        $this->_id = (int) $id;
        parent::__construct();
    }

    public function getData()
    {
        if (!isset($this->_data)) {
            $query = $this->_db->getQuery(true);

            $query
                ->select('*')
                ->from($this->_table)
                ->where('gallery_id = ' . $this->_id);

            $this->_db->setQuery($query);

            $this->_data = $this->_db->loadObject();

            $query
                ->clear()
                ->select('*')
                ->from($this->_table . '_image')
                ->where('gallery_id = ' . $this->_id)
                ->order('ordering');

            $this->_db->setQuery($query);

            $this->_data->images = $this->_db->loadObjectList();
        }

        return $this->_data;
    }
}