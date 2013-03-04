<?php
defined('_JEXEC') or die('Restricted Access.');

require_once('setup.php');

?>
<!DOCTYPE html>
<html>
<head>
    <script src="templates/<?= $this->template ?>/js/jquery-1.9.0-src.js"></script>
    <script>com_noroton = {};</script>
    <jdoc:include type="head" />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Share' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="templates/<?= $this->template ?>/css/template.css" />
    <link rel="stylesheet" type="text/css" href="templates/<?= $this->template ?>/css/fancybox/jquery.fancybox.css" />
    <noscript>
        <style>
            #content .event-carousel .event-container {
                margin: 0 auto;
            }

            #content .event-carousel li {
                width: auto;
            }
        </style>
    </noscript>
</head>
<body id="site" class="<?= $body_class ?>">
    <div id="header">
        <div id="header-bg-container">
            <div id="header-bg"></div>
        </div>
        <div class="content-container">
            <div class="logo">
                <a href="<?= JURI::base() ?>"></a>
            </div>
            <ul class="menu top-menu">
                <li><a href="<?= JRoute::_('index.php?option=com_content&view=article&id=9&Itemid=137') ?>">Members Section</a></li>
                <li>
                    <span class="separator"></span>
                </li>
                <?php // TODO - obviously use form tokens and stuff ?>
                <?php
                if (!$is_logged_in):
?>
                <li><a href="<?= JRoute::_('index.php?option=com_content&view=article&id=10&Itemid=138') ?>">Log In</a></li>
                <?php
                else:
                    $logout = JModuleHelper::getModules('logout');
                    $logout = $logout[0];
                    echo '<li>' . JModuleHelper::renderModule($logout) . '</li>';
?>
                <?php
                endif
?>
                <li>
                    <span class="separator"></span>
                </li>
                <jdoc:include type="modules" name="social-icons" />
            </ul>
            <jdoc:include type="modules" name="top-menu" />
            <jdoc:include type="modules" name="main-menu" />
            <div class="clr"></div>
            <div id="hero-image-container-bg"></div>
            <div id="hero-image-container">
                <div id="hero-image">
                    <jdoc:include type="modules" name="carousel" />
                </div>
            </div>
        </div>
    </div>
    <div id="content" class="<?= $content_class ?>">
        <div id="content-bg">
            <div id="content-bg-top"></div>
            <div class="content-container">
                <jdoc:include type="message" />
                <?php
                if (!$is_com_users):
?>

                <jdoc:include type="component" />
                <?php
                else:
                    $login = JModuleHelper::getModules('login-form');
                    $login = $login[0];
                    echo JModuleHelper::renderModule($login);
                endif
?>
            </div>
        </div>
    </div>
    <div id="footer">
        <div id="footer-bg-top"></div>
        <div class="content-container center">
            <jdoc:include type="modules" name="bottom-menu" />
            <div class="clr"></div>
            <jdoc:include type="modules" name="external-links-menu" />
            <div class="clr"></div>
            <p>Designed by <a class="bold" target="_blank" href="http://scsdesigninc.com">ScSDesignInc.com</a> and developed by <a target="_blank" class="bold" href="http://www.danronmoon.com/">DanRonMoon.com</a></p>
        </div>
    </div>
    <script src="templates/<?= $this->template ?>/js/jquery.anythingslider-1.8.6-min.js"></script>
    <script src="templates/<?= $this->template ?>/js/jquery.aviaslider.min.js"></script>
    <script src="templates/<?= $this->template ?>/js/jquery-validation-1.11.0.js"></script>
    <script src="templates/<?= $this->template ?>/js/carousel.js"></script>
    <script src="templates/<?= $this->template ?>/js/site.js"></script>
    <script src="templates/<?= $this->template ?>/js/jquery.fancybox.js"></script>
    <div id="image-cache">
        <img src="templates/<?= $this->template ?>/img/header_fb_hover.png" />
        <img src="templates/<?= $this->template ?>/img/header_tw_hover.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_right_large_hover.png" />
        <img src="templates/<?= $this->template ?>/img/circle_large_hover.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_left_red.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_left_orange.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_right_red.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_right_orange.png" />
        <img src="templates/<?= $this->template ?>/img/form_label_error_triangle.png" />
    </div>
</body>
