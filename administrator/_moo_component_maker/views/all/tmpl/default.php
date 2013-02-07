<?php

/**
 * @var MooViewAll $this
 */

MooHelper::restrictAccess();
?>

<form action="index.php" method="post" name="adminForm">
    <table>
        <?= $this->outputSearch() ?>
    </table>
    <?php if ($this->view['rows']) : ?>
        <table class="adminlist">
            <?= $this->outputTableHeaders() ?>
            <?= $this->outputTableRows() ?>
            <tfoot>
                <tr>
                    <td colspan="100"><?= $this->view['pagination']->getListFooter(); ?></td>
                </tr>
            </tfoot>
        </table>
    <?php else : ?>
        <p><?= MooHelper::getDefault('empty_msg', null, 'Sorry, no ' . MooHelper::makeReadable(MooConfig::get('current_page')) . ' could be found!') ?></p>
    <?php endif ?>
    <?= $this->outputHiddenInput() ?>
    <?= JHTML::_( 'form.token' ); ?>
</form>