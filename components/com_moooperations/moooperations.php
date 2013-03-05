<?php

namespace Moo\Operations;

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport('joomla.application.component.view');
require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');

spl_autoload_extensions('.php,.html.php');
spl_autoload_register(function ($class) {
    if (stripos($class, __NAMESPACE__) === false) {
        return;
    }

    $is_view = (stripos($class, 'View') !== false);


    $class = ltrim(str_replace(__NAMESPACE__, '', $class), '\\');

    $class_letters = str_split($class);

    $slice_idxs = array();

    foreach ($class_letters as $key => $letter) {
        if (strtoupper($letter) !== $letter) { // is lowercase
            continue;
        }

        $slice_idxs[] = $key;
    }

    $path = array();

    if (count($slice_idxs) <= 1) {
        $path[] = strtolower($class);
    } else {
        foreach ($slice_idxs as $key => $idx) {
            $has_next_idx = isset($slice_idxs[$key + 1]);

            $next_idx = $has_next_idx
                ? $slice_idxs[$key + 1]
                : strlen($class);

            $plural_appendage = $has_next_idx
                ? 's'
                : '';

            $path[] = (string) implode('', array_slice($class_letters, $idx, $next_idx - $idx)) . $plural_appendage;
        }
    }

    if ($is_view) {
        $path[] = 'view';
    }

    $include_path_basename = JPATH_COMPONENT . DS . strtolower(implode(DS, $path));

    $extensions = explode(',', spl_autoload_extensions());

    foreach ($extensions as $extension) {
        $include_path = $include_path_basename . $extension;

        if (!is_readable($include_path)) {
            continue;
        }

        return include($include_path);
    }
});

//$available_model_names = array_slice(scandir(JPATH_COMPONENT . DS . 'models'), 2);

$params = \JComponentHelper::getParams(basename(dirname(__FILE__)));

$input = \JFactory::getApplication()->input;

$controller = new Controller($input, $params);

$available_view_names = array_slice(scandir(JPATH_COMPONENT . DS . 'views'), 2);

$view_name = $controller->correctViewName(
    $available_view_names,
    'list'
);

$cid = $input->get('cid', 0, 'int');

//$controller->redirect();

$model_class_name = '\\' . __NAMESPACE__ . '\\Model' . ucfirst($view_name);

$model = new $model_class_name($cid);

$view_class_name = '\\' . __NAMESPACE__ . '\\View' . ucfirst($view_name);

$view = new $view_class_name(new \MooViewHelper('operations'), $model->getData());

$view->display();

$document = \JFactory::getDocument();
$app = \JFactory::getApplication();
$title = $app->getCfg('sitename');
$doc_title = $document->getTitle();
$document->setTitle($doc_title . ' - ' . $title);
