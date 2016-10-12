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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_product', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_product/assets/js/form.js');


?>
</style>
<script type="text/javascript">
    if (jQuery === 'undefined') {
        document.addEventListener("DOMContentLoaded", function(event) { 
            jQuery('#form-product').submit(function(event) {
                
            });

            
			jQuery('input:hidden.colours').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('colourshidden')){
					jQuery('#jform_colours option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
	jQuery('#jform_colours').change(function(){
		if(jQuery('#jform_colours option:selected').length == 0){
		jQuery("#jform_colours option[value=0]").attr('selected', 'selected');
		}
	});
					jQuery("#jform_colours").trigger("liszt:updated");
        });
    } else {
        jQuery(document).ready(function() {
            jQuery('#form-product').submit(function(event) {
                
            });

            
			jQuery('input:hidden.colours').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('colourshidden')){
					jQuery('#jform_colours option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
	jQuery('#jform_colours').change(function(){
		if(jQuery('#jform_colours option:selected').length == 0){
		jQuery("#jform_colours option[value=0]").attr('selected', 'selected');
		}
	});
					jQuery("#jform_colours").trigger("liszt:updated");
        });
    }
</script>

<div class="product-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-product" action="<?php echo JRoute::_('index.php?option=com_product&task=product.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
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
	<?php foreach((array)$this->item->colours as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="colours" name="jform[colourshidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
	</div>
				<?php echo $this->form->getInput('updated_date'); ?>
				<?php echo $this->form->getInput('created_date'); ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_product&task=productform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_product" />
        <input type="hidden" name="task" value="productform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
