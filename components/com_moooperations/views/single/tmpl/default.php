<?php
/**
 * @var $this \Moo\Operations\ViewSingle
 */
$row    = $this->row;
$helper = $this->helper;

defined('_JEXEC') or die('Restricted Access.');

$image_exists = $helper->imageExists($row->image);

$image_exists_str = !$image_exists
    ? 'no-image'
    : '';
?>

<?= $helper->output($row->title, '<h1 class="bold">{str}</h1>') ?>
<div class="float-l <?= $image_exists_str ?>">
    <?= $helper->output($row->text, '<div class="description">{str}</div>') ?>
    <?= $helper->output($row->features) ?>
</div>
<?= $helper->outputImage($row->image, '<div class="float-r">{str}</div>') ?>
<div class="clr"></div>