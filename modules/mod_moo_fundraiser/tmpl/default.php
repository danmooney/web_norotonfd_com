<?php
defined('_JEXEC') or die('Restricted Access');


foreach ($events as $event): ?>
    <div class="fundraising-event">
        <?= $helper->output($event->title, '<h4 class="semibold">{str}' . $helper->output($event->date, ' - ' . date('F j, Y', strtotime($event->date))) . '</h4>') ?>
        <?= $helper->output($event->text, '<div class="event-description">{str}</div>') ?>
    </div>
    <div class="line-horizontal"></div>
<?php
endforeach ?>