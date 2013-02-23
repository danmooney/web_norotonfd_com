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

<div id="operation-single">
<?= $helper->output($row->title, '<h1 class="bold">{str}</h1>') ?>
<div class="float-l <?= $image_exists_str ?>">
    <?= $helper->output($row->text, '<div class="description">{str}</div>') ?>
    <?= $helper->output($row->features, function ($feature_str) {
            $features = explode("\n", $feature_str);

            $html = '<ul id="features">';
            foreach ($features as $feature) {
                $html .= '<li>' . $feature . '</li>';
            }
            $html .= '</ul>';

            return $html;
        })
    ?>
</div>
<?= $helper->outputImage($row->image, '<div class="float-r">{str}</div>') ?>
<div class="clr"></div>
</div>

