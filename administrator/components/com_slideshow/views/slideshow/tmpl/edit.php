<?php
/**
 * @version     1.0.0
 * @package     com_slideshow
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_slideshow/assets/css/slideshow.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'slideshow.cancel') {
            Joomla.submitform(task, document.getElementById('slideshow-form'));
        }
        else {
            
            if (task != 'slideshow.cancel' && document.formvalidator.isValid(document.id('slideshow-form'))) {
                
                Joomla.submitform(task, document.getElementById('slideshow-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_slideshow&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="slideshow-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_SLIDESHOW_TITLE_SLIDESHOW', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m_image'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m2_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m2_image'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m3_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m3_image'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m4_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m4_image'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m5_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m5_image'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m6_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m6_image'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m7_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m7_image'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('m8_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('m8_image'); ?></div>
			</div>

            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('m9_image'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('m9_image'); ?></div>
            </div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('image'); ?></div>
			</div>
            
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('alt'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('alt'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
			</div>
            
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>

                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>