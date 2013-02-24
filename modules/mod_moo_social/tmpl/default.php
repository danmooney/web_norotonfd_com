<?php
defined('_JEXEC') or die('Restricted Access');

$facebook_link = $params->get('facebook');
if (!empty($facebook_link)) {
    $facebook_link = $helper->urlify($facebook_link, true);
}

$twitter_link = $params->get('twitter');
if (!empty($twitter_link)) {
    $twitter_link = $helper->urlify($twitter_link, true);
}

echo $helper->output($facebook_link, '<li><div id="fb" class="social"><a href="{str}"></a></div></li>');
echo $helper->output($twitter_link, '<li><div id="tw" class="social"><a href="{str}"></a></div></li>');