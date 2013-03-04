<?php

function debug($string, $dieBool = true) {
    if ($_SERVER['REMOTE_ADDR'] !== '71.9.158.10' &&
        $_SERVER['REMOTE_ADDR'] !== '108.200.220.35'
    ) {
        return;
    }

    print_r($string);
    if (true === $dieBool) {
        die;
    }
}

$input = JFactory::getApplication()->input;

$id     = (int) $input->get('id', 0, 'int');
$itemId = (int) $input->get('Itemid', 0, 'int');

$option = $input->get('option', null, 'string');

$menu = JMenu::getInstance('site');

$isFrontpage = ($menu->getActive() === $menu->getDefault());

$isArticle   = ($input->get('view') === 'article');

$active_menu = null;

if ($itemId > 0 && !$isFrontpage) {
    $article = JTable::getInstance('content');
    $article->load($id);
    $active_menu = $menu->getActive();
    $parent_id = (int) $active_menu->parent_id;

    if ('com_content' === $option) {
        $body_class = $article->get('alias') . ' article-' . $article->get('id');
    } else {
        $body_class = preg_replace('/(com_)(moo)?/', '', $option);
    }
} else {
    $body_class = '';
    $menu_title = '';
}

if ($isFrontpage) {
    $body_class = 'home';
    $content_class = 'frontpage';

    $document  = JFactory::getDocument();
    $doc_title = $document->getTitle();
    $site_title = JFactory::getApplication()->getCfg('sitename');
    $document->setTitle($doc_title . ' - ' . $site_title);

} else {
    $content_class = 'article';
}

// check session success message
$session = JFactory::getSession();
$success_message = $session->get('success_message');
$error_message   = $session->get('error_message');

if ($success_message || $error_message) {
    $message_type = $success_message
        ? 'success'
        : 'error';

    $message_var_str = $message_type . '_message';
    $message = $$message_var_str;

    $has_messages_bool = true;
} else {
    $has_messages_bool = false;
}

$is_logged_in = !JFactory::getUser()->guest;

$session->clear('success_message');
$session->clear('error_message');