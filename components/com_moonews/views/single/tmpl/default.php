<?php
/**
 * @var $this \Moo\News\ViewSingle
 */
$row    = $this->row;
$helper = $this->helper;

defined('_JEXEC') or die('Restricted Access.');

//$image_exists = $helper->imageExists($row->image);
//
//$image_exists_str = !$image_exists
//    ? 'no-image'
//    : '';


echo $helper->output($row->title, '<h1 class="bold">' . $row->title . '</h1>');
echo $helper->output($row->text);