<?php
defined('_JEXEC') or die('Restricted Access');

$fillOutValue = call_user_func(function () use ($app) {
    $errors = $app->getUserState('moo.join.form.field.errors');

    $field_values = $app->getUserState('moo.join.form.field.values');

    return function ($field) use ($errors, $field_values) {

        if ('errors' === $field) {

            if (empty($errors)) {
                return '';
            }

            $html = '<div class="box-errors">';

            $html .= '<ul>';

            foreach ($errors as $message) {
                $html .= '<li>- ' . $message . '</li>';
            }

            $html .= '</ul>';

            $html .= '</div>';

            return $html;
        }

        if (empty($field_values[$field])) {
            return '';
        }

        return $field_values[$field];
    };
});

echo $fillOutValue('errors');

?>

<form action="" method="POST" id="form-join">
    <div class="formline">
        <input type="text" id="first_name" class="required" name="first_name" placeholder="First Name" data-rule-alpha="1" tabindex="1" value="<?= $fillOutValue('first_name') ?>" />
        <input type="text" id="last_name" class="last required"  name="last_name" placeholder="Last Name" data-rule-alpha="1" tabindex="2" value="<?= $fillOutValue('last_name') ?>" />
    </div>
    <div class="formline">
        <input type="text" class="required" id="address" name="address" placeholder="Street Address" tabindex="3" value="<?= $fillOutValue('address') ?>" />
        <?php
            $options = array();
            $options[] = JHTML::_('select.option', '', 'State');
            foreach ($helper->states as $state) {
                $options[] = JHTML::_('select.option', $state, $state);
            }
            echo JHtml::_('select.genericList', $options, 'state', 'class="inputbox required" tabindex="4" style=" font-size:14px;"', 'value', 'text', $fillOutValue('state'));
        ?>

        <?php // <input type="text" id="state" name="state" placeholder="State" /> ?>
        <input class="last required" type="text" id="zip" name="zip" placeholder="Zip Code" tabindex="5" data-rule-number="1" data-rule-zip="1" value="<?= $fillOutValue('zip') ?>" />
    </div>
    <div class="formline">
        <input type="text" id="phone" class="required" name="phone_number" placeholder="Phone Number" tabindex="6" data-rule-phone="1" value="<?= $fillOutValue('phone_number') ?>" />
    </div>
    <div class="formline">
        <input type="text" id="email" class="required" name="email" placeholder="Email Address" tabindex="7" data-rule-email="1" value="<?= $fillOutValue('email') ?>" />
    </div>
    <div class="formline">
        <input type="text" id="confirm_email" class="required" name="confirm_email" placeholder="Confirm Email Address" tabindex="8" data-rule-email="1" data-rule-equalto="#email" />
    </div>
    <div class="formline">
        <input type="text" class="required" id="age" name="age" placeholder="Age" tabindex="9" data-rule-digits="1" value="<?= $fillOutValue('age') ?>" />
    </div>
    <div class="formline">
        <input type="text" class="required" id="occupation" name="occupation" placeholder="Occupation" tabindex="10" value="<?= $fillOutValue('occupation') ?>" />
    </div>
    <div class="formline">
        <?php /* <input type="text" class="required" id="dob" name="dob" placeholder="Date of Birth (mm/dd/yy)" tabindex="11" data-rule-date="1" value="<?= $fillOutValue('dob') ?>" /> */ ?>
        <?= JHTML::_('calendar', $fillOutValue('dob'), 'dob', 'dob', '%m/%d/%Y', 'class="required" placeholder="Date of Birth (mm/dd/yy)" data-rule-moodate="1"') ?>
    </div>
    <div class="formline">
        <input type="text" class="required" id="birthplace" name="birthplace" placeholder="Place of Birth" tabindex="12" value="<?= $fillOutValue('birthplace') ?>" />
    </div>
    <div class="formline">
        <input type="text" class="required" id="hp" name="hp" placeholder="Leave blank for verification purposes" value="<?= $fillOutValue('hp') ?>" />
    </div>
    <div class="formline">
        <span>Are you a U.S. citizen?</span>
        <label for="citizen_yes">Yes</label>
        <input id="citizen_yes" type="radio" name="citizen" value="yes" tabindex="13" class="required" />
        <label for="citizen_no">No</label>
        <input id="citizen_no" type="radio" name="citizen" value="no" tabindex="14" class="required" />
    </div>
    <button id="submit-join" type="submit">Submit</button>
    <div class="clr"></div>
</form>

<?php
$app->setUserState('moo.join.form.field.errors', null);
$app->setUserState('moo.join.form.field.values', null);