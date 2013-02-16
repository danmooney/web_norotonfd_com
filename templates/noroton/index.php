<?php
defined('_JEXEC') or die('Restricted Access.');

require_once('setup.php');

?>
<!DOCTYPE html>
<html>
<head>
    <script src="templates/<?= $this->template ?>/js/jquery-1.9.0-src.js"></script>
    <jdoc:include type="head" />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Share' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="templates/<?= $this->template ?>/css/template.css" />
</head>
<body class="<?= $body_class ?>">
    <div id="header">
        <div id="header-bg-container">
            <div id="header-bg"></div>
        </div>
        <div class="content-container">
            <div class="logo">
                <a href="<?= JURI::base() ?>"></a>
            </div>
            <ul class="menu top-menu">
                <li><a href="#">Members Section</a></li>
                <li>
                    <span class="separator"></span>
                </li>
                <?php // TODO - obviously use form tokens and stuff ?>
                <li><a href="#">Log In</a></li>
                <li>
                    <span class="separator"></span>
                </li>
                <li>
                    <div id="fb" class="social">
                        <a href="#"></a>
                    </div>
                </li>
                <li>
                    <div id="tw" class="social">
                        <a href="#"></a>
                    </div>
                </li>
            </ul>
            <jdoc:include type="modules" name="top-menu" />
            <jdoc:include type="modules" name="main-menu" />
            <div class="clr"></div>
            <div id="hero-image-container-bg"></div>
            <div id="hero-image-container">
                <div id="hero-image"></div>
            </div>
        </div>
    </div>
    <div id="content" class="<?= $content_class ?>">
        <div id="content-bg">
            <div id="content-bg-top"></div>
            <div class="content-container">
                <jdoc:include type="component" />
            </div>
        </div>
    </div>
    <div id="footer">
        <div id="footer-bg-top"></div>
        <div class="content-container center">
            <jdoc:include type="modules" name="bottom-menu" />
            <?php /*
            <ul class="menu">
                <li>
                    <a href="#">Home</a>
                </li>
                <span class="separator">|</span>
                <li>
                    <a href="#">History</a>
                </li>
                <span class="separator">|</span>
                <li>
                    <a href="#">Operations</a>
                </li>
                <span class="separator">|</span>
                <li>
                    <a href="#">Fire Safety</a>
                </li>
                <span class="separator">|</span>
                <li>
                    <a href="#">Fundraising</a>
                </li>
                <span class="separator">|</span>
                <li>
                    <a href="#">Join Our Crew</a>
                </li>
            </ul>*/ ?>
            <div class="clr"></div>
            <jdoc:include type="modules" name="external-links-menu" />
            <p>Designed by <a class="bold" target="_blank" href="http://scsdesigninc.com">ScSDesignInc.com</a> and developed by <a target="_blank" class="bold" href="http://www.danronmoon.com/">DanRonMoon.com</a></p>
        </div>
    </div>
    <div id="image-cache">
        <img src="templates/<?= $this->template ?>/img/header_fb_hover.png" />
        <img src="templates/<?= $this->template ?>/img/header_tw_hover.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_right_large_hover.png" />
        <img src="templates/<?= $this->template ?>/img/circle_large_hover.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_left_red.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_left_orange.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_right_red.png" />
        <img src="templates/<?= $this->template ?>/img/arrow_right_orange.png" />
    </div>
</body>
