<?php
/**
 * @var $this \Moo\Calendar\ViewSingle
 */
$row    = $this->row;
$helper = $this->helper;
$is_tmpl_component = $this->is_tmpl_component;

defined('_JEXEC') or die('Restricted Access.');


if ($is_tmpl_component): ?>
<div id="calendar-event-popup-container">
<?php
endif ?>
    <?= $helper->output($row->title, '<h1 class="bold">' . $row->title . '</h1>') ?>
    <?= $helper->output($row->date, '<p><span class="bold">Date:</span> ' .  date('F j, Y', strtotime($row->date)) . '</p>') ?>
    <?= $helper->output($row->address, '<p><span class="bold">Address:</span> {str}</p>') ?>
    <?= $helper->output($row->email, '<p><span class="bold">Email:</span> {str}</p>') ?>
    <?= $helper->output($row->phone, '<p><span class="bold">Phone:</span> {str}</p>') ?>
    <?= $helper->output($row->text, '<div class="event-text">{str}</div>') ?>
<?php
if ($is_tmpl_component): ?>
</div>
<?php
endif ?>

