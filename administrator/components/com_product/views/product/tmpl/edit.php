<?php
/**
 * @version     1.0.0
 * @package     com_product
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
$document->addStyleSheet('components/com_product/assets/css/product.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
	js('input:hidden.colours').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('colourshidden')){
			js('#jform_colours option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_colours").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'product.cancel') {
            Joomla.submitform(task, document.getElementById('product-form'));
        }
        else {
            
            if (task != 'product.cancel' && document.formvalidator.isValid(document.id('product-form'))) {
                
	if(js('#jform_colours option:selected').length == 0){
		js("#jform_colours option[value=0]").attr('selected','selected');
	}
                Joomla.submitform(task, document.getElementById('product-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_product&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="product-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PRODUCT_TITLE_PRODUCT', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('image'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('image_hover'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('image_hover'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('category_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('category_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('frame_width'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('frame_width'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('frame_height'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('frame_height'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('lens_width'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('lens_width'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('temple_arms'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('temple_arms'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('bridge'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('bridge'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('colours'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('colours'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->colours as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="colours" name="jform[colourshidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
			</div>

				<?php echo $this->form->getInput('updated_date'); ?>
				<?php echo $this->form->getInput('created_date'); ?>

                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>