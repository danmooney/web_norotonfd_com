<?php
/**
 *  @package MOO ABSTRACT COMPONENT MAKER
 *  @author Dan Mooney
 *  @version 3/4/13
 *  - Add upload_image_thumb width/height calculation based on missing width/height
 *  - Add alias support
 *  - Add support for multiple primary keys in table.php
 *  - Add upload_max_width for image upload
 */

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

defined('_JEXEC') or die('Restricted Access');

define('MOO_COMPONENT_PATH', dirname(__FILE__));

JTable::addIncludePath(JPATH_COMPONENT . DS . 'tables');
jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport( 'joomla.application.component.view');
jimport('joomla.html.pagination');

// view
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

JHTML::_('behavior.calendar'); 
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');
require_once('table.php');