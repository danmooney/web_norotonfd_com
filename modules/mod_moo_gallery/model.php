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
    private $_table = '#__moo_gallery';

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
                ->select(array(
                    '*'
                ))
                ->from($this->getTable())
//                ->join('INNER', $this->getTable() . ' AS g USING (gallery_id)')
                ->where('published = 1')
                ->order('date DESC');

            $db->setQuery($query);
            $events = $db->loadObjectList();

            $query
                ->clear()
                ->select('*')
                ->from($this->getTable() . '_image')
                ->where('placeholder = 1');

            $db->setQuery($query);
            $images = $db->loadObjectList('gallery_id');

            foreach ($events as $event) {
                $gallery_id = $event->gallery_id;

                $event->filename = isset($images[$gallery_id])
                    ? $images[$gallery_id]->filename
                    : null;
            }

            $this->_events = $events;
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
            $this->_type = $this->_params->get('reel-type', 'general');
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