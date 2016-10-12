<?php
/**
 *
 * Main product information
 *
 * @package	VirtueMart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 8310 2014-09-21 17:51:47Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$i = 0;
?>

<fieldset>
		<legend>
		<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_CHILD_PARENT'); ?></legend>
<table class="adminform">
	<tr class="row<?php echo $i?>">
		<td width="50%">
			<?php
			if ($this->product->virtuemart_product_id) {
				$link=JROUTE::_('index.php?option=com_virtuemart&view=product&task=createChild&virtuemart_product_id='.$this->product->virtuemart_product_id.'&'.JSession::getFormToken().'=1' );
				$add_child_button="";
			} else {
				$link="";
				$add_child_button=" not-active";
			}
			?>
			<div class="button2-left <?php echo $add_child_button ?> btn-wrapper">
				<div class="blank">
					<?php if ($link) { ?>
					<a href="<?php echo $link ?>" class="btn btn-small">
						<?php } else { ?>
						<span class="hasTip" title="<?php echo vmText::_ ('COM_VIRTUEMART_PRODUCT_ADD_CHILD_TIP'); ?>">
							<?php } ?>
							<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ADD_CHILD');?>
							<?php if ($link) { ?>
					</a>
				<?php } else{ ?>
					</span>
				<?php } ?>
				</div>
			</div>
		</td>
		<th>
			<?php //echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PARENT') ?>
		</th>
		<legend><?php
			$parentRel = '';
			if ($this->product->product_parent_id) {
				$parentRel = vmText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_PARENT',JHtml::_('link', JRoute::_('index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$this->product->product_parent_id),
						$this->product_parent->product_name, array('title' => vmText::_('COM_VIRTUEMART_EDIT').' '.htmlentities($this->product_parent->product_name))).' =&gt; ');
			}
			echo vmText::sprintf('COM_VIRTUEMART_PRODUCT_INFORMATION',$parentRel);
			echo ' id: '.$this->product->virtuemart_product_id ?>
		</legend>
	</tr>
	<?php $i = 1 - $i; ?>
	<tr class="row<?php echo $i?>" >
		<td width="79%" colspan = "3">
			<?php if (count($this->product_childs)>0 ) {

				$customs = array();
				if(!empty($this->product->customfields)){
					foreach($this->product->customfields as $custom){
						//vmdebug('my custom',$custom);
						if($custom->field_type=='A'){
							$customs[] = $custom;
						}
					}
				}
				// vmdebug('ma $customs',$customs);
				?>

				<table class="adminform">
					<tr>
						<th style="text-align: left !important;" width="20%"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_CHILD') ?></th>
						<th style="text-align: left !important;" width="25%"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_CHILD_NAME')?></th>
						<!-- <th style="text-align: left !important;"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_GTIN')?></th>
						<th style="text-align: left !important;" width="5%"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST')?></th>
						<th style="text-align: left !important;"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_IN_STOCK')?></th>
						<th style="text-align: left !important;" width="5%"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_ORDERED_STOCK')?></th> -->
						<?php foreach($customs as $custom){ ?>
							<th style="text-align: left !important;">
								<?php echo vmText::sprintf('COM_VIRTUEMART_PRODUCT_CUSTOM_FIELD_N',vmText::_('COM_VIRTUEMART_'.strtoupper($custom->customfield_value)))?>
							</th>
						<?php } ?>
						<th style="text-align: left !important;" width="10%"><?php echo vmText::_('COM_VIRTUEMART_ORDERING')?></th>
						<th style="text-align: left !important;"><?php echo vmText::_('COM_VIRTUEMART_PUBLISHED')?></th>
					</tr>
					<?php foreach ($this->product_childs as $child  ) {
						$i = 1 - $i; ?>
						<tr class="row<?php echo $i ?>">
							<td>
								<?php echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$child->virtuemart_product_id), $child->slug, array('title' => vmText::_('COM_VIRTUEMART_EDIT').' '.htmlentities($child->product_name),'target' => '_blank')) ?>
								<!--input type="hidden" name="childs[<?php echo $child->virtuemart_product_id ?>][slug]" id="child<?php echo $child->virtuemart_product_id ?>slug" value="<?php echo $child->slug ?>" /-->
							</td>
							<td><input type="text" class="inputbox" name="childs[<?php echo $child->virtuemart_product_id ?>][product_name]" id="child<?php echo $child->virtuemart_product_id ?>product_name" size="32" value="<?php echo $child->product_name ?>" /></td>
							<!-- <td><input type="text" class="inputbox" name="childs[<?php echo $child->virtuemart_product_id ?>][product_gtin]" id="child<?php echo $child->virtuemart_product_id ?>product_gtin" size="32" maxlength="64"value="<?php echo $child->product_gtin ?>" /></td>

							<td><input type="text" class="inputbox" name="childs[<?php echo $child->virtuemart_product_id ?>][mprices][product_price][]" size="10" value="<?php echo $child->allPrices[$child->selectedPrice]['product_price'] ?>" /><input type="hidden" name="childs[<?php echo $child->virtuemart_product_id ?>][mprices][virtuemart_product_price_id][]" value="<?php echo $child->allPrices[$child->selectedPrice]['virtuemart_product_price_id'] ?>"  ></td> -->
							<!-- <td><?php echo $child->product_in_stock ?></td>
							<td><?php echo $child->product_ordered ?></td> -->
							<?php foreach($customs as $custom){
								$attrib = $custom->customfield_value;
								if(isset($child->$attrib)){
									$childAttrib = $child->$attrib;
								} else {
									//vmdebug('unset? use Fallback product_name instead $attrib '.$attrib,$child);
									$childAttrib = $child->product_name;
								}
								//vmdebug(' $attrib '.$attrib,$child,$childAttrib);
								?>
								<td><input type="text" class="inputbox" name="childs[<?php echo $child->virtuemart_product_id ?>][<?php echo $attrib ?>]" size="20" value="<?php echo $childAttrib ?>" /></td>
							<?php
							}
							?>
							<td>
								<input type="text" class="inputbox" name="childs[<?php echo $child->virtuemart_product_id ?>][pordering]" size="2" value="<?php echo $child->pordering ?>" /></td>
							</td>
							<td>
								<?php echo VmHTML::checkbox('childs['.$child->virtuemart_product_id.'][published]', $child->published) ?>
							</td>
						</tr>
					<?php } ?>
				</table>
			<?php } ?>
		</td>
	</tr>
</table>
</fieldset>