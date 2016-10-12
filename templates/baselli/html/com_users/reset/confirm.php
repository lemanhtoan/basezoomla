<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

$show = JRequest::getVar('show');

?>

<?php if($show == 1): ?>
<div id="hide-popup">    
</div>
<div id="show-popup" class="modalDialog">
<a href="#close" title="Close" class="modalCloseImg simplemodal-close"></a>
    	<form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.confirm'); ?>" method="post">
    		<h3><?php echo JText::_('VERIFI_EMAIL') ?></h3>
            <p><?php echo JText::_('TEXT_VERIFICATION_EMAIL_MESSAGE'); ?></p>
            <span id="val_email" class="error"><?php echo JText::_('REQUIRED_RESET_EMAIL');?></span>
            <span id="val_email_format" class="error"><?php echo JText::_('FORMAT_RESET_EMAIL');?></span>
    		<input type="text" name="jform[email]" placeholder="<?php echo JText::_('COM_MAILTO_YOUR_EMAIL'); ?>" id="jform_email"/>
            <span id="val_code" class="error"><?php echo JText::_('REQUIRED_RESET_CODE');?></span>							
    		<input type="text" name="jform[token]" placeholder="<?php echo JText::_('VERIFICATION_CODE'); ?>" id="jform_token"/>	
            <div id="reset_step_email" class="reset_step_email">
                <button type="submit" id="btn-email"><?php echo JText::_('JSUBMIT'); ?></button>
            </div>
    		<?php echo JHtml::_('form.token'); ?>
    	</form>
    </div>
</div>
<?php endif ?>
