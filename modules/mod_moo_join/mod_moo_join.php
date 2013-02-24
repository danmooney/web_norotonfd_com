<?php
/**
 * @var $params JRegistry
 */

defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');
require_once('model.php');
require_once('validator.php');
require_once('processor.php');

$helper = new MooViewHelper();

$is_post = ($_SERVER['REQUEST_METHOD'] === 'POST');

if ($is_post) {
    $input = JFactory::getApplication()->input;
    $model = new MooJoinModel($input);
    $validator = new MooJoinValidator();

    $processor = new MooJoinProcessor($model, $validator);

    if (!$processor->validate()) {

    } else {

    }
} else {
    require JModuleHelper::getLayoutPath('mod_moo_join');
}