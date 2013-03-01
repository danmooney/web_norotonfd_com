<?php

class ModMooNews
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
    private $_table = '#__moo_news';

    /**
     * @var array
     */
    private $_news;

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

    public function getNews()
    {
        if (!isset($this->_news)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query
                ->select('*')
                ->from($this->getTable())
                ->where('published = 1')
                ->order('date DESC');

            $db->setQuery($query);
            $this->_news = $db->loadObjectList();
        }

        return $this->_news;
    }

    public function clusterEventsByNum($num)
    {
        $events = $this->getNews();
        if (count($events) !== count($events, COUNT_RECURSIVE)) {
            return;
        }

        $this->_news = array_chunk($events, $num);
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
            $this->_title = $this->_params->get('header', 'NEWS');
        }

        return $this->_title;
    }
}