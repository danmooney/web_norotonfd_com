<?php
defined('_JEXEC') or die('Restricted Access') ?>

<form action="" method="POST" id="form-join">
    <div class="formline">
        <input type="text" id="first_name" name="first_name" placeholder="First Name" />
        <input class="last" type="text" id="last_name" name="last_name" placeholder="Last Name" />
    </div>
    <div class="formline">
        <input type="text" id="address" name="address" placeholder="Street Address" />
        <input type="text" id="state" name="state" placeholder="State" />
        <input class="last" type="text" id="zip" name="zip" placeholder="Zip Code" />
    </div>
    <div class="formline">
        <input type="text" id="phone" name="phone" placeholder="Phone Number" />
    </div>
    <div class="formline">
        <input type="text" id="email" name="email" placeholder="Email Address" />
    </div>
    <div class="formline">
        <input type="text" id="confirm_email" name="confirm_email" placeholder="Confirm Email Address" />
    </div>
    <div class="formline">
        <input type="text" id="age" name="age" placeholder="Age" />
    </div>
    <div class="formline">
        <input type="text" id="occupation" name="occupation" placeholder="Occupation" />
    </div>
    <div class="formline">
        <input type="text" id="dob" name="dob" placeholder="Date of Birth (mm/dd/yy)" />
    </div>
    <div class="formline">
        <input type="text" id="birthplace" name="birthplace" placeholder="Place of Birth" />
    </div>
    <div class="formline">
        <span>Are you a U.S. citizen?</span>
        <label for="citizen_yes">Yes</label>
        <input id="citizen_yes" type="radio" name="citizen" value="yes" />
        <label for="citizen_no">No</label>
        <input id="citizen_no" type="radio" name="citizen" value="no" />
    </div>
    <button id="submit-join" type="submit">Submit</button>
    <div class="clr"></div>
</form>