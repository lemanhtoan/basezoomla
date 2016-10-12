<?php
/**
 * @version     1.0.0
 * @package     com_blog
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// no direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_blog');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_blog')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_CATEGORIES'); ?></th>
			<td><?php echo $this->item->categories_title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_MODE'); ?></th>
			<td><?php echo $this->item->mode; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_IMAGE'); ?></th>
			<td><?php echo $this->item->image; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_DESCRIPTION'); ?></th>
			<td><?php echo $this->item->description; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_CONTENT'); ?></th>
			<td><?php echo $this->item->content; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_LANGUAGE'); ?></th>
			<td><?php echo $this->item->language; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_BLOG_FORM_LBL_BLOG_CREATED_DATE'); ?></th>
			<td><?php echo $this->item->created_date; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_blog&task=blog.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_BLOG_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_blog')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_blog&task=blog.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_BLOG_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_BLOG_ITEM_NOT_LOADED');
endif;
?>
