<?php
/**
 * @var $params JRegistry
 */

defined('_JEXEC') or die('Restricted Access');

require_once('model.php');
require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');

$model  = new ModMooNews($params);
$helper = new MooViewHelper('news');

$model->getNews();

if ($model->getType() === 'detailed') {
    $model->clusterEventsByNum(2);
} else {
    $model->clusterEventsByNum(4);
}

require JModuleHelper::getLayoutPath('mod_moo_news');