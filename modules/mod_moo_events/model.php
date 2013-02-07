<?php

class ModMooEvents
{
    /**
     * Reel type
     * @var string
     */
    private $_type;

    /**
     * Header
     * @var string
     */
    private $_title;

    /**
     * @var string
     */
    private $_table = '#__moo_event';

    /**
     * @var array
     */
    private $_events;

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->_table = $table;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @var JRegistry
     */
    private $_params;

    public function __construct(JRegistry $params)
    {
        $this->_params = $params;
    }

    public function getEvents()
    {
        if (!isset($this->_events)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query
                ->select('*')
                ->from($this->getTable());

            $db->setQuery($query);
            $this->_events = $db->loadObjectList();
        }

        return $this->_events;
    }

    public function getType()
    {
        if (!isset($this->_type)) {
            $this->_type = $this->_params->get('reel-type');
        }

        return $this->_type;
    }

    public function getTitle()
    {
        if (!isset($this->_title)) {
            $this->_title = $this->_params->get('header');
        }

        return $this->_title;
    }
}