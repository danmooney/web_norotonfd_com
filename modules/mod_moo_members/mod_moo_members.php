<?php
/**
 * @var $params JRegistry
 */

defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');
require_once('model.php');

$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'templates/noroton/js/fullcalendar.min.js');
$document->addStyleSheet(JURI::base() . 'templates/noroton/css/fullcalendar.css');

$helper = new MooViewHelper();
$model = new MooMembersModel($helper);

$calendar_events = $model->getCalendarEvents();
$notices = $model->getNotices();
$documents = $model->getDocuments();

require JModuleHelper::getLayoutPath('mod_moo_members');