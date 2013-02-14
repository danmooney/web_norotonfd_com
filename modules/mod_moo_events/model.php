<?php

class ModMooEvents
{
    /**
     * Reel type
     * @var string
     */
    private $_type;

    /**
     * Reel subject
     * @var string
     */
    private $_subject;

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
                ->from($this->getTable())
                ->where('event_type = ' . $db->quote($this->getSubject()))
                ->where('published = 1');

            $db->setQuery($query);
            $this->_events = $db->loadObjectList();
        }

        return $this->_events;
    }

    public function clusterEventsByNum($num)
    {
        $events = $this->getEvents();
        if (count($events) !== count($events, COUNT_RECURSIVE)) {
            return;
        }

        $this->_events = array_chunk($events, $num);
    }

    // news or events
    public function getSubject()
    {
        if (!isset($this->_subject)) {
            $this->_subject = $this->_params->get('reel-subject', 'news');
        }

        return $this->_subject;
    }

    // detailed or general
    public function getType()
    {
        if (!isset($this->_type)) {
            $this->_type = $this->_params->get('reel-type', 'detailed');
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