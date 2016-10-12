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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_product');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_product')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_IMAGE'); ?></th>
			<td><?php echo $this->item->image; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_IMAGE_HOVER'); ?></th>
			<td><?php echo $this->item->image_hover; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_CATEGORY_ID'); ?></th>
			<td><?php echo $this->item->category_id_title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_FRAME_WIDTH'); ?></th>
			<td><?php echo $this->item->frame_width; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_FRAME_HEIGHT'); ?></th>
			<td><?php echo $this->item->frame_height; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_LENS_WIDTH'); ?></th>
			<td><?php echo $this->item->lens_width; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_TEMPLE_ARMS'); ?></th>
			<td><?php echo $this->item->temple_arms; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_BRIDGE'); ?></th>
			<td><?php echo $this->item->bridge; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_COLOURS'); ?></th>
			<td><?php echo $this->item->colours; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_LANGUAGE'); ?></th>
			<td><?php echo $this->item->language; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_UPDATED_DATE'); ?></th>
			<td><?php echo $this->item->updated_date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_PRODUCT_CREATED_DATE'); ?></th>
			<td><?php echo $this->item->created_date; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_product&task=product.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_PRODUCT_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_product')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_product&task=product.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_PRODUCT_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_PRODUCT_ITEM_NOT_LOADED');
endif;
?>
