<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive') ?>
<form action="<?= JRoute::_('index.php', true, $params->get('usesecure')) ?>" method="post" id="login-form">
	<?php if ($params->get('pretext')): ?>
		<div class="pretext">
		<p><?= $params->get('pretext') ?></p>
		</div>
	<?php endif ?>
	<fieldset class="userdata">
	<p id="form-login-username">
		<?php /*<label for="modlgn-username"><?= JText::_('MOD_MOO_LOGIN_VALUE_USERNAME') ?></label>*/ ?>
		<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" placeholder="Username" autocomplete="off" />
	</p>
	<p id="form-login-password">
		<?php /*<label for="modlgn-passwd"><?= JText::_('JGLOBAL_PASSWORD') ?></label>*/ ?>
		<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18" placeholder="Password" autocomplete="off" />
	</p>
    <div class="clr"></div>
	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p id="form-login-remember">
		<label for="modlgn-remember"><?= JText::_('MOD_MOO_LOGIN_REMEMBER_ME') ?></label>
		<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox checkbox-input" value="yes"/>
	</p>
	<?php endif ?>
	<input type="submit" name="Submit" class="button" value="<?= JText::_('JLOGIN') ?>" />
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?= $return ?>" />
    <div class="clr"></div>
	<?= JHtml::_('form.token') ?>
	</fieldset>
    <?php /*
	<ul>
		<li>
			<a href="<?= JRoute::_('index.php?option=com_users&view=reset') ?>">
			<?= JText::_('MOD_MOO_LOGIN_FORGOT_YOUR_PASSWORD') ?></a>
		</li>
		<li>
			<a href="<?= JRoute::_('index.php?option=com_users&view=remind') ?>">
			<?= JText::_('MOD_MOO_LOGIN_FORGOT_YOUR_USERNAME') ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?= JRoute::_('index.php?option=com_users&view=registration') ?>">
				<?= JText::_('MOD_MOO_LOGIN_REGISTER') ?></a>
		</li>
		<?php endif ?>
	</ul>*/ ?>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?= $params->get('posttext') ?></p>
		</div>
	<?php endif ?>
</form>