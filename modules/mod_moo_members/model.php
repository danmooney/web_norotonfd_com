<?php

class MooMembersModel
{
    /**
     * @var MooViewHelper
     */
    private $_helper;

    public function __construct(MooViewHelper $helper)
    {
        $this->_helper = $helper;
    }

    public function getCalendarEvents()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from('#__moo_calendar_event')
            ->where('published = 1')
            ->order('date DESC');

        $db->setQuery($query);
        $results = (array) $db->loadObjectList();

        foreach ($results as $result) {
            $result->title = $this->_helper->truncate($result->title, 30);
            $result->url = JRoute::_('index.php?option=com_moocalendar&Itemid=146&cid=' . $result->event_id);
        }

        return $results;
    }

    public function getNotices()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from('#__moo_notice')
            ->where('published = 1')
            ->order('date DESC');

        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public function getDocuments()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from('#__moo_document')
            ->where('published = 1')
            ->order('date DESC');

        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }
}