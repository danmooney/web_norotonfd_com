<?php
/**
 * @var $this \Moo\News\ViewList
 */
defined('_JEXEC') or die('Restricted Access.');
$rows   = $this->rows;
$helper = $this->helper;

?>

<h1 class="bold">NEWS</h1>

<?php if (!count($rows)) : ?>
        <p>There is currently no news.</p>
<?php else: ?>
        <ul class="news-list">
            <?php foreach ($rows as $row) : ?>
                    <?= $helper->output($row->title,
                            '<li><a href="'
                                . JRoute::_('index.php?option=com_moonews&view=single&Itemid=141&cid='
                                . $row->news_id)
                                . '">{str}</a></li>'
                    ) ?>

            <?php endforeach ?>
        </ul>
<?php endif ?>