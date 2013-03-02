<?php

defined('_JEXEC') or die('Restricted Access.');

function MooGalleryBuildRoute(&$query)
{
    $segments = array();
    if (isset($query['cid'])) {

        if (is_numeric($query['cid'])) {
            $segments[] = getGalleryById($query['cid']);
        }

        unset($query['cid']);
    }

    unset($query['view']);
    return $segments;
}

function MooGalleryParseRoute(&$segments)
{
    $vars = array();

    if (count($segments) > 0) {
        if (is_numeric($segments[0])) {
            $vars['cid'] = $segments[0];
        } else {
            $name = str_replace(':', '-', $segments[0]);

            $id = getGalleryIdByName($name);
            if (null === $id) {
                return JError::raiseError(404, JText::_('Page not found'));
            }
            $vars['cid'] = $id;
            $vars['view'] = 'single';
        }
    }

    return $vars;
}

function getGalleryIdByName($name)
{
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select('gallery_id')
          ->from('#__moo_gallery')
          ->where('alias = ' . $db->quote($name));

    $db->setQuery($query);
    $result = $db->loadResult();
    return $result;
}

function getGalleryById($id)
{
    $db = \JFactory::getDBO();
    $query = $db->getQuery(true);
    $query
        ->select('alias')
        ->from('#__moo_gallery')
        ->where('gallery_id = ' . (int) $id);

    $db->setQuery($query);
    $result = $db->loadResult();
    return $result;
}