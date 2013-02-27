<?php

defined('_JEXEC') or die('Restricted Access.');

function MooOperationsBuildRoute(&$query)
{
    $segments = array();
    if (isset($query['cid'])) {

        if (is_numeric($query['cid'])) {
            $segments[] = getOperationAliasById($query['cid']);
        }

        unset($query['cid']);
    }

    unset($query['view']);
    return $segments;
}

function MooOperationsParseRoute(&$segments)
{
    $vars = array();

    if (count($segments) > 0) {
        if (is_numeric($segments[0])) {
            $vars['cid'] = $segments[0];
        } else {
            $name = str_replace(':', '-', $segments[0]);
            $id = getOperationIdByName($name);
            if (null === $id) {
                return JError::raiseError(404, JText::_('Page not found'));
            }
            $vars['cid'] = $id;
        }
    }

    $vars['view'] = 'single';

    return $vars;
}

function getOperationIdByName($name)
{
    $db = \JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select('operation_id')
          ->from('#__moo_operation')
          ->where('alias = ' . $db->quote($name));

    $db->setQuery($query);
    $result = $db->loadResult();
    return $result;
}

function getOperationAliasById($id)
{
    $db = \JFactory::getDBO();
    $query = $db->getQuery(true);
    $query
        ->select('alias')
        ->from('#__moo_operation')
        ->where('operation_id = ' . (int) $id);

    $db->setQuery($query);
    $result = $db->loadResult();
    return $result;
}