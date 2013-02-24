<?php
defined('_JEXEC') or die('Restricted Access');

if (!empty($links)): ?>
    <ul class="menu external-links-menu">
<?php
    foreach ($links as $num => $link):
        $is_last = ($num === count($links) - 1);
        if (empty($link->url) || empty($link->title))
            continue ?>
        <li>
            <?= $helper->output($link->title, '<a target="_blank" href="' . $link->url . '">{str}</a>') ?>
        </li>
        <?php
        if (!$is_last) : ?>

            <li><span class="separator">|</span></li>

<?php
        endif; ?>

<?php
    endforeach; ?>
    </ul>
    <?php
endif;