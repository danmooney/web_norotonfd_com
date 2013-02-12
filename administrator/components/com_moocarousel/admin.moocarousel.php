<?php

$moo_component_path = JPATH_SITE . DS . 'administrator' . DS . '_moo_component_maker' . DS;

require_once($moo_component_path . 'setup.php');
require_once('config.php');
require_once($moo_component_path . 'helper.php');

MooConfig::initialize();
MooHelper::renderPage();