<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage Category
 * @author RickG, jseros
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


$mainframe = JFactory::getApplication();
?>
<table class="adminform" style="width:80%">
	<tr>
		<td valign="top">
			<fieldset>
				<legend><?php echo vmText::_('COM_VIRTUEMART_FORM_GENERAL'); ?></legend>
				<table width="100%" border="0">
					<!-- Commented out for future use
				<tr>
					<td class="key">
						<label for="shared">
							<?php echo vmText::_('COM_VIRTUEMART_CATEGORY_FORM_SHARED'); ?>:
						</label>
					</td>
					<td>
						<?php
							$categoryShared = isset($this->relationInfo->category_shared) ? $this->relationInfo->category_shared : 1;
							echo JHtml::_('select.booleanlist', 'shared', $categoryShared, $categoryShared);
						?>
					</td>
				</tr>
				-->
					<?php echo VmHTML::row('input','COM_VIRTUEMART_CATEGORY_NAME','category_name',$this->category->category_name,'class="required"'); ?>
					<?php echo VmHTML::row('booleanlist','COM_VIRTUEMART_PUBLISHED','published',$this->category->published); ?>
					<?php echo VmHTML::row('input','COM_VIRTUEMART_SLUG','slug',$this->category->slug); ?>
					<?php echo VmHTML::row('editor','COM_VIRTUEMART_DESCRIPTION','category_description',$this->category->category_description); ?>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<style>table {border:none;  width: 100%;}</style>
			<fieldset>
				<legend><?php echo vmText::_('COM_VIRTUEMART_METAINFO'); ?></legend>
				<?php echo shopFunctions::renderMetaEdit($this->category); ?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<?php if($this->showVendors() ){
			echo VmHTML::row('raw','COM_VIRTUEMART_VENDOR', $this->vendorList );
		} ?>
	</tr>
</table>
