<?php
/**
 * @var $this \Moo\Operations\ViewList
 */

$rows   = $this->rows;
$helper = $this->helper;

$number_per_row = 3;

defined('_JEXEC') or die('Restricted Access.') ?>

<?php
    if (!empty($rows)):
        $i = 0;
?>
        <div class="box-light-gray">
            <h2 class="center semibold">The Noroton Fire Department fleet consists of</h2>
            <div id="operations-container">
            <?php
                foreach ($rows as $row):
                    $thumbnail_image_exists = is_file(JPATH_SITE . DS . 'images' . DS . $helper->getImgDir() . DS . $row->thumbnail_image);

                    $placeholder_str = !$thumbnail_image_exists
                        ? 'placeholder'
                        : '';

                    if ($i % $number_per_row === 0) {
                        $idx_str = 'first';
                    } else if ($i % $number_per_row === ($number_per_row - 1)) {
                        $idx_str = 'last';
                    } else {
                        $idx_str = '';
                    }

                    $can_read_more = !empty($row->text);

?>
                    <div class="operation <?= $idx_str ?>">
                        <?php
                            if ($can_read_more): ?>
                                <a href="<?= JRoute::_('index.php?option=com_moooperations&view=single&oid=' . $row->operation_id) ?>">
                        <?php
                            endif ?>
                                <div class="image-container <?= $placeholder_str ?>">
                                    <?php
                                        if ($thumbnail_image_exists): ?>
                                            <?= $helper->outputImage($row->thumbnail_image) ?>
                                    <?php
                                        endif
?>
                                    <?= $helper->output($row->category, '<div class="overlay"><h4 class="semibold">{str}</h4></div>') ?>
                                </div>


                        <?= $helper->output($row->title, '<h3 class="semibold">{str}</h3>') ?>
                        <?= $helper->output($helper->truncate($row->text, 80), '<div class="description">{str}</div>') ?>
                        <?php
                            if ($can_read_more):
?>
                                    <div class="read-more">
                                            Read More
                                    </div>
                                </a>
                        <?php
                            endif
?>

                    </div>
                    <?php

                    if ('last' === $idx_str): ?>
                        <div class="clr"></div>
            <?php
                    endif;
                    $i += 1;
                endforeach ?>
            </div>
        </div>
<?php
    endif
?>