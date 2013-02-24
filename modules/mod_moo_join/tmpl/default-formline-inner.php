<?php
defined('_JEXEC') or die('Restricted Access') ?>

<form action="" method="POST" id="form-join">
    <div class="formline">
        <div class="formline-inner">
            <input type="text" id="first_name" class="required" name="first_name" placeholder="First Name" data-rule-alpha="1" tabindex="1" />
        </div>
        <div class="formline-inner">
            <input type="text" id="last_name" class="last required"  name="last_name" placeholder="Last Name" data-rule-alpha="1" tabindex="2" />
        </div>
    </div>
    <div class="formline">
        <div class="formline-inner">
            <input type="text" class="required" id="address" name="address" placeholder="Street Address" tabindex="3" />
        </div>
        <div class="formline-inner">
        <?php
            $options = array();
            $options[] = JHTML::_('select.option', '', 'State');
            foreach ($helper->states as $state) {
                $options[] = JHTML::_('select.option', $state, $state);
            }
            echo JHtml::_('select.genericList', $options, 'state', 'class="inputbox required" tabindex="4" style=" font-size:14px;"', 'value', 'text', '');
        ?>
        </div>
        <div class="formline-inner">
            <?php // <input type="text" id="state" name="state" placeholder="State" /> ?>
            <input class="last required" type="text" id="zip" name="zip" placeholder="Zip Code" tabindex="5" data-rule-number="1" data-rule-rangelength="[5, 5]" />
        </div>
    </div>
    <div class="formline">
        <input type="text" id="phone" name="phone" placeholder="Phone Number" tabindex="6" />
    </div>
    <div class="formline">
        <div class="formline-inner">
            <input type="text" id="email" class="required" name="email" placeholder="Email Address" tabindex="7" data-rule-email="1" />
        </div>
    </div>
    <div class="formline">
        <div class="formline-inner">
            <input type="text" id="confirm_email" class="required" name="confirm_email" placeholder="Confirm Email Address" tabindex="8" data-rule-email="1" data-rule-equalto="#email" />
        </div>
    </div>
    <div class="formline">
        <div class="formline-inner">
            <input type="text" class="required" id="age" name="age" placeholder="Age" tabindex="9" data-rule-number="1" />
        </div>
    </div>
    <div class="formline">
        <div class="formline-inner">
            <input type="text" class="required" id="occupation" name="occupation" placeholder="Occupation" tabindex="10" />
        </div>
    </div>
    <div class="formline">
        <div class="formline-inner">
            <input type="text" class="required" id="dob" name="dob" placeholder="Date of Birth (mm/dd/yy)" tabindex="11" data-rule-date="1" />
        </div>
    </div>
    <div class="formline">
        <div class="formline-inner">
            <input type="text" class="required" id="birthplace" name="birthplace" placeholder="Place of Birth" tabindex="12" />
        </div>
    </div>
    <div class="formline">
        <span>Are you a U.S. citizen?</span>
        <div class="formline-inner">
            <label for="citizen_yes">Yes</label>
            <input id="citizen_yes" type="radio" name="citizen" value="yes" tabindex="13" />
            <label for="citizen_no">No</label>
            <input id="citizen_no" type="radio" name="citizen" value="no" tabindex="14" />
        </div>
    </div>
    <button id="submit-join" type="submit">Submit</button>
    <div class="clr"></div>
</form>