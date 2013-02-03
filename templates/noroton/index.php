<?php
defined('_JEXEC') or die('Restricted Access.'); ?>
<!DOCTYPE html>
<html>
<head>
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Share' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="templates/<?= $this->template ?>/css/template.css" />
</head>
<body>
    <div id="header">
        <div id="header-bg"></div>
        <div class="content-container">
            <div class="logo">
                <a href="#"></a>
            </div>
            <jdoc:include type="modules" name="top-menu" />
            <jdoc:include type="modules" name="main-menu" />
            <div class="clr"></div>
            <div id="hero-image-container">
                <div id="hero-image"></div>
            </div>
        </div>
    </div>
    <div id="content">
        <div id="content-bg">
            <div id="content-bg-top"></div>
            <div class="content-container">
                The Noroton Fire Department
            </div>
        </div>
    </div>
    <div id="footer">
        <div id="footer-bg-top"></div>
        <div class="content-container center">
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
            </ul>
            <div class="clr"></div>
            <jdoc:include type="modules" name="external-links-menu" />
            <p>Designed by <a class="bold" target="_blank" href="http://scsdesigninc.com">ScSDesignInc.com</a> and developed by <a target="_blank" class="bold" href="http://www.danronmoon.com/">DanRonMoon.com</a></p>
        </div>
    </div>
</body>
