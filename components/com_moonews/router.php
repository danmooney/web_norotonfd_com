<?php

defined('_JEXEC') or die('Restricted Access.');

function MooNewsBuildRoute(&$query)
{
    $segments = array();
    if (isset($query['cid'])) {

        if (is_numeric($query['cid'])) {
            $segments[] = getNewsById($query['cid']);
        }

        unset($query['cid']);
    }

    unset($query['view']);
    return $segments;
}

function MooNewsParseRoute(&$segments)
{
    $vars = array();

    if (count($segments) > 0) {
        if (is_numeric($segments[0])) {
            $vars['cid'] = $segments[0];
        } else {
            $name = str_replace(':', '-', $segments[0]);

            $id = getNewsIdByName($name);
            if (null === $id) {
                return JError::raiseError(404, JText::_('Page not found'));
            }
            $vars['cid'] = $id;
            $vars['view'] = 'single';
        }
    }

    return $vars;
}

function getNewsIdByName($name)
{
    $db = \JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select('news_id')
          ->from('#__moo_news')
          ->where('alias = ' . $db->quote($name));

    $db->setQuery($query);
    $result = $db->loadResult();
    return $result;
}

function getNewsById($id)
{
    $db = \JFactory::getDBO();
    $query = $db->getQuery(true);
    $query
        ->select('alias')
        ->from('#__moo_news')
        ->where('news_id = ' . (int) $id);

    $db->setQuery($query);
    $result = $db->loadResult();
    return $result;
}