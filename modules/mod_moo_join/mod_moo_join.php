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

$app = JFactory::getApplication();

$form_submit_success = (bool) $app->getUserState('moo.join.form.success');

if ($is_post) {
    $input = $app->input;
    $model = new MooJoinModel($input, $params);
    $validator = new MooJoinValidator();

    $processor = new MooJoinProcessor($model, $validator);

    $app->setUserState('moo.join.form.field.values', $model->field_value_map);

    if (!$processor->validate()) {
        $app->setUserState('moo.join.form.field.errors', $validator->errors);
    } else {
        $app->setUserState('moo.join.form.success', true);
        $processor
            ->storeFields()
            ->sendEmail();
    }

    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
} elseif ($form_submit_success) {
    require JModuleHelper::getLayoutPath('mod_moo_join');
} else {
    require JModuleHelper::getLayoutPath('mod_moo_join', 'thank_you.php');
}