<?php
/**
 * @var $params JRegistry
 */

defined('_JEXEC') or die('Restricted Access');

require_once('model.php');
require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');

$model  = new ModMooCarousel($params);
$helper = new MooViewHelper('carousel');

$images = $model->getImages();
$carousel_delay = $model->getDelay();

$document = JFactory::getDocument();
$document->addScriptDeclaration('com_noroton.carouselDelay = ' . $carousel_delay * 1000 . ';');

require JModuleHelper::getLayoutPath('mod_moo_carousel');