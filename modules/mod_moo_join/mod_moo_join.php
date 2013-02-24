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
$input = $app->input;

$form_submit_success = (bool) $app->getUserState('moo.join.form.success');
$thank_you_link = JRoute::_('index.php?option=com_content&view=article&id=8&Itemid=136');

$is_thank_you_article = ($input->get('Itemid', 0, 'int') === 136);


if ($is_thank_you_article && !$form_submit_success) {
    header('Location: ' . JURI::base());
    exit(0);
}

if ($is_post) {
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

    header('Location: http://' . $_SERVER['HTTP_HOST'] . html_entity_decode($_SERVER['REQUEST_URI']));
    exit(0);
} elseif ($form_submit_success && !$is_thank_you_article) {
    header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . html_entity_decode($thank_you_link));
    exit(0);
} elseif ($form_submit_success && $is_thank_you_article) {
    $app->setUserState('moo.join.form.success', null);
    $app->setUserState('moo.join.form.field.errors', null);
    $app->setUserState('moo.join.form.field.values', null);
} else {
    require JModuleHelper::getLayoutPath('mod_moo_join');
}