<?php
/**
 * @var MooViewSingle $this
 */

MooHelper::restrictAccess();

?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <fieldset class="adminform">
        <legend><?= MooHelper::makeReadable(MooHelper::makeSingular(MooConfig::get('current_page'))) ?> Details</legend>
        <table class="admintable" style="width:100%;">
            <?= $this->outputRows() ?>
        </table>
    </fieldset>
  <?= $this->outputHiddenInput() ?>
  <?= JHTML::_( 'form.token' ); ?>
</form>