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

$comp_show = JRequest::getVar('comp-show');
$comp_change = JRequest::getVar('comp-change');

?>

<?php if($comp_show == 1) : ?>
<div id="hide-popup">    
</div>

<div id="show-popup" class="modalDialog">
<a href="#close" title="Close" class="modalCloseImg simplemodal-close"></a>
    <div class="reset-complete<?php echo $this->pageclass_sfx?>">
    	<?php if ($this->params->get('show_page_heading')) : ?>
    		<div class="page-header">
    			<h1>
    				<?php echo $this->escape($this->params->get('page_heading')); ?>
    			</h1>
    		</div>
    	<?php endif; ?>
    
    	<form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post">
            <h3><?php echo JText::_('ENTER_PASSWORD') ?></h3>
    		<p><?php echo JText::_('RESET_PASSWORD_MESSAGE'); ?></p>
            
            <span id="val_pass1" class="error"><?php echo JText::_('REQUIRED_RESET_PASS1');?></span>
            <span id="val_pass_similar" class="error"><br /><?php echo JText::_('FORMAT_RESET_PASS_SIMILAR');?></span>
            
            <input type="password" name="jform[password1]" id="jform_password1" value="" placeholder="<?php echo JText::_('PASSWORD');?>" />
            <p><?php //echo JText::_('RESET_PASSWORD_MESSAGE'); ?></p>
            
            <span id="val_pass2" class="error"><?php echo JText::_('REQUIRED_RESET_PASS2');?></span>	
            
            <input type="password" name="jform[password2]" id="jform_password2" value="" placeholder="<?php echo JText::_('REPEATE_PASSWORD');?>"/>
            <div id="reset_step_password" class="reset_step_password">
    		  <button type="submit" id="btn-email"><?php echo JText::_('JSUBMIT'); ?></button>
            </div>
    		<?php echo JHtml::_('form.token'); ?>
    	</form>
    </div>
</div>

<?php endif; ?>


<?php if($comp_change == 1) : ?>
    <div id="hide-popup">    
    </div>
    
    <div id="show-popup" class="modalDialog">
        <a href="" title="Close" class="modalCloseImg simplemodal-close"></a>
    	
		<div class="page-headera">
			<h3>
				<?php echo JText::_('FORGOT_PASSWORD_HEADER'); ?>
			</h3>
		</div>
    
        <p><?php echo JText::_('MESSAGE_CHANGE_PASS_DONE');?></p>
    </div>
<?php endif; ?>
