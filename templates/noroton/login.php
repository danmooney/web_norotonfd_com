<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <script src="templates/<?= $this->template ?>/js/jquery-1.9.0-src.js"></script>
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700' rel='stylesheet' type='text/css' />
<?php if ($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template_rtl.css" type="text/css" />
<?php endif; ?>
</head>
<body id="site" class="contentpane" style="background:#000">
	<jdoc:include type="message" />
    <div id="content">
        <h3 style="text-align:center">Members Log In</h3>
	    <jdoc:include type="component" />
    </div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/',
                data: {
                    ajax: 1,
                    username: $('#modlgn-username').val(),
                    password: $('#modlgn-passwd').val()
                },
                success: function (data) {
                    console.log(data);
                },
                error: function (data) {
                    console.log(data);
//                    alert('An error has occurred.  Please try again later.');
                }
            });

        });
    });
</script>
</body>
</html>
