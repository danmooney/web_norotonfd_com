<?php

namespace Moo\Notice;

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
                ->where('notice_id = ' . $this->_id);

            $this->_db->setQuery($query);

            $this->_data = $this->_db->loadObject();
        }

        return $this->_data;
    }
}