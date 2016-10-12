<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_footer
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$module = JModuleHelper::getModule('mod_subscribe');
// Load setting config
$module = JModuleHelper::getModule('mod_setting');
$config_setting = new JRegistry($module->params);

?>
<div class="subscribe">
	<span><?php echo JText::_('SUBSCRIBE');?></span>
	<form action="#" id="subscribe">
		<input type="text" placeholder="<?php echo JText::_('YOURMAIL'); ?>" name="email" class="email" >
		<input type="submit" class="btn_subscribe">
		<img class="fa" src="<?php echo $config_setting->get('subscribe_image'); ?>" alt="">
		<img class="fa hover" src="<?php echo $config_setting->get('subscribe_image_hover'); ?>" alt="">	                
		<?php $lang = JFactory::getLanguage(); ?>
		<input type="hidden" name="current-lang" id="current-lang" value="<?php echo $lang->getTag() ?>" />
	</form>
</div>


