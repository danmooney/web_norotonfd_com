<?php
/**
 * @var $this \Moo\Gallery\ViewList
 */
defined('_JEXEC') or die('Restricted Access.');
$rows   = $this->rows;
$helper = $this->helper;

?>

<h1 class="bold news-heading">What's Going On at Noroton</h1>

<?php if (!count($rows)) : ?>
        <p>There is currently nothing happening at Noroton.</p>
<?php else: ?>
        <ul id="news-list">
            <?php foreach ($rows as $row) : ?>
                    <?= $helper->output($row->title,
                            '<li><a href="'
                                . JRoute::_('index.php?option=com_moogallery&Itemid=144&cid='
                                . $row->gallery_id)
                                . '">{str}</a></li>'
                    ) ?>

            <?php endforeach ?>
        </ul>
<?php endif ?>