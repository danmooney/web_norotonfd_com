<?php

class MooMembersModel
{
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

        // TODO - add url to each
        foreach ($results as $result) {
            $result->url = 'http://www.google.com/';
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