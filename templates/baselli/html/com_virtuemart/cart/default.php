<?php 
	$user = JFactory::getUser(); 
	$db = JFactory::getDbo();
	//reload data not using cache
	$query = $db->getQuery(true);

	$query = 'SELECT u.*, CONCAT(u.`street`, "<br />", u.`zipcode`, " ", u.`city`) infos, CONCAT(u.`street_delevery`, "<br />", u.`zipcode_delevery`, " ", u.`city_delevery`) delivery_infos, (SELECT `virtuemart_country_id` FROM `#__virtuemart_countries` c where c.`country_3_code` = u.`country`) country_id, (SELECT `virtuemart_country_id` FROM `#__virtuemart_countries` c where c.`country_3_code` = u.`country_delevery`) country_delivery_id FROM `#__users` u where u.`id` = '.intval($user->id);
	
	$db->setQuery($query);
	$user = $db->loadObject();

	$app    = JFactory::getApplication();
	$path   = JURI::base(true).'/templates/'.$app->getTemplate().'/images/thumb/ad-1.jpg';
	$items = $this->cart->products;
	$i = 1;
	$currencySymbol = $this->currencyDisplay->getFormattedCurrency(1,0);
	$currencySymbol = str_replace('1', '', $currencySymbol);
	$totalPrice = 0;
	$productModel = VmModel::getModel('product');

	// Country
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('*');
	$query->from( $db->quoteName('#__virtuemart_countries') );
	$query->where( $db->quoteName('published' ) . ' = 1');
	$query->order('ordering ASC');
	$db->setQuery($query);
	$countries = $db->loadObjectList();

	$orginalData = array();
	JLoader::import('helpers.blog',rtrim(str_replace('administrator', '', JPATH_BASE), '/') . '/components/com_blog');
	$cids = array();
	$tmp = array();
	foreach ( $countries as $country )
	{
	    $orginalData[$country->virtuemart_country_id] = array(
	        'country_name' => $country->country_name
	    );
	    array_push($cids, $country->virtuemart_country_id);
	    array_push($tmp, array(
	        'country_name' => $country->country_name, 
	        'virtuemart_country_id' => $country->virtuemart_country_id, 
	        'country_3_code' => $country->country_3_code, 
	    ));
	}
	$countries = $tmp;
	$countryTranslationData = BlogFrontendHelper::getTranslationForCurrentLanguage('languages','', implode(',', $cids ), $orginalData);

	function getUserCountryTax($user_country_id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select( 'vc.*' )
			->from($db->quoteName('#__virtuemart_calcs', 'vc'))
			->join('INNER', $db->quoteName('#__virtuemart_calc_countries', 'vcc') . ' ON (' . $db->quoteName('vcc.virtuemart_calc_id') . ' = ' . $db->quoteName('vc.virtuemart_calc_id') . ')')
			->where( $db->quoteName('vcc.virtuemart_country_id') . ' = ' . intval($user_country_id) );
		$db->setQuery($query);
		$countryRow = $db->loadObject();

		if ( is_object($countryRow)  && property_exists($countryRow, 'virtuemart_calc_id') )  
		{
			return $countryRow->calc_value;
		}
		return false;
	}
	$virtuemart_country_id = 0;
	$query = $db->getQuery(true);
	$query->select('virtuemart_country_id');
	$query->from( $db->quoteName('#__virtuemart_countries') );
	$query->where( $db->quoteName('country_3_code') . ' = '. $db->quote($user->country) );
	$db->setQuery($query);
	$countryRow = $db->loadObject();
	if ( is_object($countryRow)  && property_exists($countryRow, 'virtuemart_country_id') )  $virtuemart_country_id = intval($countryRow->virtuemart_country_id);

	//get user tax
	$tax = getUserCountryTax($virtuemart_country_id);
	$session = JFactory::getSession();
?>
<style type="text/css">
	.order_page .page_title 
	{
		color:#333333;
		text-transform: uppercase;
		font-size: 14pt;
		padding: 0;
		margin: 0 0 10px;
		font-weight: normal;
		line-height: 15px;
	}
	.order_page .page_title_block
	{
		position: relative;
	}
	/*.order_page .page_title::after
	{
		width:30px;
		display: block;
		height:3px;
		background: #333333;
		position: absolute;
		top:27px;
		left:1px;
		content: "";
	}*/
	.myorder_grid
	{
		width: 100%;
		border-collapse: collapse;
		margin-top: 30px;
	}
	.myorder_grid_header td
	{
		text-align: center;
		padding-bottom: 7px;
		border-width: 3px;
		border-bottom-style:double;
		border-color:#dadada;
	}
	.myorder_grid_row td{
		padding:25px;
		text-align: center;
	}
	.myorder_grid_row_delete_item
	{
		text-decoration: none;
		height: 14px;
		width: 14px;
		display: inline-block;
		text-align: center;
	}
	.myorder_grid_row_delete_item:hover{
		text-decoration: none;
	}
	#system-message-container
	{
		display: none;
	}
	.myorder_grid_last_row{
		border-top: 1px solid #dadada;
	}
	.total_price {
		font-size:30px;
		font-weight: bold;
	}
	.quantity_container {
		border-bottom: 1px solid #dadada;
		width:150px;
		padding:5px;
		margin:0 auto;
	}
	.quantity_container span{
		color:#333333;
		font-size:20px;
	}
	.quantity_container input{
		border:0;
		width:100px;
		text-align: center;
		color:#bcbcbc;
	}
	.additional_block {
		color:#666666;
		font-style: italic;
		margin-top: 34px;
		line-height: 22px;
	}
	.user_info_block
	{
		margin-top: 40px;
	}
	.avatar_block {
		margin-top: 32px;
		float:left;
		clear:both;
		margin-bottom: 32px;
	}
	.user_basic_info_block {
		clear:both;
	}
	.basic_info_title {
		color:#666666;
		text-transform: uppercase;
		padding:0 0 15px;
		margin:0;
	}
	.basic_info_row{
		padding:0;
		margin:0;
		line-height: 22px;
		padding-bottom: 23px;
		color:#333333;
	}
	.delivery_address_block {
		padding-top:10px;
	}
	.mycart_sys_buttons 
	{
		margin-top: 73px;	
	}
	.continue_shopping {
		background: #fff;
		height:50px;
		line-height: 30px;
		border:1px solid #333333;
		color: #333333;
	    width: auto;
	    border-radius: 25px;
		font-size: 16px;
	}
	.preview_order 
	{
		background: #333333;
		height:50px;
		line-height: 30px;
		border:0;
		color: #dadada;
		border: 0;
	    width: auto;
	    border-radius: 25px;
		font-size: 16px;
	}
	.continue_shopping, .preview_order{
		float:left;
		padding: 0 15px;
	}
	.continue_shopping 
	{
		margin-right:30px;
	}
	.product_link {
		color:#333;
	}
	.myorder_grid .col_1 {
		width:74px;
	}
	.myorder_grid .col_2 {
		width:161px;
	}
	.myorder_grid .col_3 {
		width:187px;
	}
	.myorder_grid .col_4 {
		width:163px;
	}
	.myorder_grid .col_5 {
		width:149px;
	}
	.myorder_grid .col_6 {
		width:218px;
	}
	.myorder_grid .col_7 {
		width:149px;
	}
	.myorder_grid .col_8 {
		width:100px;
	}
</style>
<?php
/**
 *
 * Layout for the shopping cart
 *
 * @package    VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

JHtml::_ ('behavior.formvalidation');
/*vmJsApi::addJScript('vm.STisBT',"
	jQuery(document).ready(function($) {

		if ( $('#STsameAsBTjs').is(':checked') ) {
			$('#output-shipto-display').hide();
		} else {
			$('#output-shipto-display').show();
		}
		$('#STsameAsBTjs').click(function(event) {
			if($(this).is(':checked')){
				$('#STsameAsBT').val('1') ;
				$('#output-shipto-display').hide();
			} else {
				$('#STsameAsBT').val('0') ;
				$('#output-shipto-display').show();
			}
			var form = jQuery('#checkoutFormSubmit');
			document.checkoutForm.submit();
		});
	});
");*
/*
vmJsApi::addJScript('vm.checkoutFormSubmit','
	jQuery(document).ready(function($) {
		jQuery(this).vm2front("stopVmLoading");
		jQuery("#checkoutFormSubmit").bind("click dblclick", function(e){
			jQuery(this).vm2front("startVmLoading");
			e.preventDefault();
			jQuery(this).attr("disabled", "true");
			jQuery(this).removeClass( "vm-button-correct" );
			jQuery(this).addClass( "vm-button" );
			jQuery(this).fadeIn( 400 );
			var name = jQuery(this).attr("name");
			$("#checkoutForm").append("<input name=\""+name+"\" value=\"1\" type=\"hidden\">");
			$("#checkoutForm").submit();
		});
	});
');*/

$this->addCheckRequiredJs();
$taskRoute = '';
$check_user_group = $user->user_retail_group;
 ?>
<form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=cart' . $taskRoute, $this->useXHTML, $this->useSSL); ?>">
<div class="container order_page">
	<div class="row">
		
		<div class="page_title_block">
			<h2 class="page_title"><?php echo JText::_('MYCART') ?></h2>	
		</div>
		
		
		<?php if ( $items !== false && $items ): ?>
		
		<div class="myorder_grid_container mobile_version">

			<?php foreach ( $items as $pkey => $item ): ?>

			<div class="myorder_grid_mobile_row" style="margin-top:20px;">
				<div class="myorder_grid_mobile_col1 myorder_grid_mobile_header"><?php echo JText::_('No') ?></div>
				<div class="myorder_grid_mobile_col2 myorder_grid_mobile_header"><?php echo $i ?></div>
			</div>
			
			<?php $prices = $item->prices; ?>
			<?php $product_price = $prices['product_price']; ?>
			<?php
				$product_parent_id = $item->product_parent_id;
				$virtuemart_media_id = $item->virtuemart_media_id;
				if ( $virtuemart_media_id )
				{
					//get image url
					$virtuemart_media_ids = implode(',', $virtuemart_media_id);
					
					$query = $db->getQuery(true);
					$query->select('*');
					$query->from($db->quoteName('#__virtuemart_medias'));
					$query->where($db->quoteName('virtuemart_media_id') . ' IN ('. $virtuemart_media_ids . ')');
					$db->setQuery($query);
					$image = $db->loadObject();

					if ( is_object($image) && property_exists($image, 'file_url') && $image->file_url )
					{
						$image_url = JURI::base() . '/' . $image->file_url;	
					}
				}
				$color_name = $product_name = $item->product_name;
				if ( intval($product_parent_id) ) 
				{
					$product = $productModel->getProduct(intval($product_parent_id), true, false,true,1);
					if ( is_object($product) && property_exists($product, 'virtuemart_product_id') )
					{
						$product_name = $product->product_name;	
						$color_name = ltrim($color_name, $product_name);
					}
				}
			?>
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_col1">&nbsp;</div>
				<div class="myorder_grid_mobile_col2"><img src="<?php echo $image_url ?>" style="width:114px;max-height:50px;margin-top:30px;text-align:center;"/></div>
			</div>
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_col1"><?php echo JText::_('CART_NAME') ?></div>
				<div class="myorder_grid_mobile_col2"><a class="product_link" href="<?php echo JRoute::_($item->link) ?>"><?php echo $product_name ?></a></div>
			</div>
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_col1"><?php echo JText::_('CART_COLOR') ?></div>
				<div class="myorder_grid_mobile_col2"><?php echo $color_name ?></div>
			</div>
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_col1"><?php echo JText::_('Price') ?></div>
				<div class="myorder_grid_mobile_col2"><?php echo number_format($product_price, 2), ' ', $currencySymbol ?></div>
			</div>
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_col1"><?php echo JText::_('Quantity') ?></div>
				<div class="myorder_grid_mobile_col2">

					<div class="quantity_container">
						<span class="quantity_container_subtract"></span>
						<input data-is-mobile="1" data-product-index="<?php echo $pkey; ?>" data-submit-id="updatecart_<?php echo $pkey; ?>" readonly="readonly" type="text" value="<?php echo $item->quantity ?>" name="quantity_mobile[<?php echo $pkey; ?>]" onblur="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" onclick="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" onchange="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" onsubmit="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" title="Update Quantity In Cart" class="" size="3" maxlength="4"/> 
						<span class="quantity_container_plus"></span>
					</div>

				</div>
			</div>
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_col1"><?php echo JText::_('Total') ?></div>
				<div class="myorder_grid_mobile_col2"><?php echo number_format($product_price*$item->quantity, 2), ' ', $currencySymbol ?></div>
			</div>

			<div class="myorder_grid_mobile_row myorder_grid_mobile_delete_row">
				<div class="myorder_grid_mobile_col1"><?php echo JText::_('Delete') ?></div>
				<div class="myorder_grid_mobile_col2"><button type="button" class="myorder_grid_row_delete_item vm2-remove_from_cart" data-id="delete_<?php echo $pkey; ?>" title="Delete Product From Cart"></div>
			</div>

			<?php $totalPrice += floatval($prices['product_price']*$item->quantity); $i++;?>

			<?php endforeach; ?>
			
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_total_col1"><?php echo JText::_('Sub Total') ?></div>
				<div class="myorder_grid_mobile_total_col2"><strong><?php echo number_format($totalPrice, 2), ' ', $currencySymbol ?></strong></div>
			</div>
                       
            <?php if ($check_user_group == "user_opticoach") : ?>
            <div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_total_col1"><?php echo JText::_('DISCOUNT'), ' ', ceil($tax), '%' ?></div>
				<div class="myorder_grid_mobile_total_col2"><strong><?php echo number_format($totalPrice*($tax/100), 2) . ' ', $currencySymbol; ?></strong></div>
			</div>
            <?php endif; ?>
            
			<?php if ( $tax !== false ): ?>
			<?php 
                $tax_value = $totalPrice*floatval($tax/100); 
                $discount_value = $totalPrice*floatval($tax/100);
            ?>
			<div class="myorder_grid_mobile_row">
				<div class="myorder_grid_mobile_total_col1"><?php echo JText::_('VAT'), ' ', ceil($tax), '%' ?></div>
				<div class="myorder_grid_mobile_total_col2"><strong>
                <?php 
                     if ($check_user_group == "user_opticoach") {
                        $total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                        echo sprintf("%.2f",number_format($total_discount*($tax/100), 2)) .  $currencySymbol;
                     } else {
                        echo sprintf("%.2f",number_format($tax_value, 2)) .$currencySymbol;
                     }                   
                ?>
                </strong></div>
			</div>
			<?php endif; ?>
			<div class="myorder_grid_mobile_row myorder_grid_mobile_grand_total_row">
				<div class="myorder_grid_mobile_total_col1"><?php echo JText::_('Grand Total') ?></div>
				<div class="myorder_grid_mobile_total_col2"><strong>
                   <?php 
                        if ($check_user_group == "user_opticoach") {
                            $total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                            $total_final = $total_discount + $total_discount*($tax/100);
                            //echo  sprintf("%.2f",number_format( $total_final, 1)) .$currencySymbol;
                            echo sprintf("%.2f",round($total_final,1)) .$currencySymbol;
                            
                        } else {
                            //echo  sprintf("%.2f",number_format( $tax_value + $totalPrice, 1)) .$currencySymbol;
                            echo sprintf("%.2f",round($tax_value + $totalPrice,1)) .$currencySymbol;
                        }
                    ?>
                </strong></div>
			</div>

		</div>
		
		<?php endif; ?>
		
		<?php

			//reset
			$i = 1;
			$totalPrice = 0;
		?>
		
		<table class="myorder_grid desktop_version" id="myorder_grid">
			<tr class="myorder_grid_header">
				<td class="col_1"><?php echo JText::_('NOS') ?></td>
				<td class="col_2"></td>
				<td class="col_3"><?php echo JText::_('NAME') ?></td>
				<td class="col_4"><?php echo JText::_('COLOR') ?></td>
				<td class="col_5"><?php echo JText::_('PRICE') ?></td>
				<td class="col_6"><?php echo JText::_('QUANTITY') ?></td>
				<td class="col_7"><?php echo JText::_('TOTAL') ?></td>
				<td class="col_8"><?php echo JText::_('DELETE') ?></td>
			</tr>
			<?php if ( $items !== false && $items ): ?>
			<?php foreach ( $items as $pkey => $item ): ?>
				<?php $prices = $item->prices; ?>
				<?php $product_price = $prices['product_price']; ?>
				<?php
					$product_parent_id = $item->product_parent_id;
					$virtuemart_media_id = $item->virtuemart_media_id;
					if ( $virtuemart_media_id )
					{
						//get image url
						$virtuemart_media_ids = implode(',', $virtuemart_media_id);
						
						$query = $db->getQuery(true);
						$query->select('*');
						$query->from($db->quoteName('#__virtuemart_medias'));
						$query->where($db->quoteName('virtuemart_media_id') . ' IN ('. $virtuemart_media_ids . ')');
						$db->setQuery($query);
						$image = $db->loadObject();

						if ( is_object($image) && property_exists($image, 'file_url') && $image->file_url )
						{
							$image_url = JURI::base() . '/' . $image->file_url;	
						}
					}
					$color_name = $product_name = $item->product_name;
					if ( intval($product_parent_id) ) 
					{
						$product = $productModel->getProduct(intval($product_parent_id), true, false,true,1);
						if ( is_object($product) && property_exists($product, 'virtuemart_product_id') )
						{
							$product_name = $product->product_name;	
							$color_name = ltrim($color_name, $product_name);
						}
					}
				?>

			<tr class="myorder_grid_first_row myorder_grid_row">
				<td class="col_1"><?php echo $i;?></td>
				<td class="col_2">
					<img src="<?php echo $image_url ?>" style="width:114px;max-height:50px;"/>
				</td>
				<td class="col_3"><a class="product_link" href="<?php echo JRoute::_($item->link) ?>"><?php echo $product_name ?></a></td>
				<td class="col_4"><?php echo $color_name ?></td>
				<td class="col_5">
					<?php echo number_format($product_price, 2) . ' ', $currencySymbol; ?>
				</td>
				<td class="col_6">
					<div class="quantity_container">
						<span class="quantity_container_subtract"></span>
						<input data-submit-id="updatecart_<?php echo $pkey; ?>" readonly="readonly" type="text" value="<?php echo $item->quantity ?>" id="quantity_input_<?php echo $pkey; ?>" name="quantity[<?php echo $pkey; ?>]" onblur="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" onclick="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" onchange="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" onsubmit="Virtuemart.checkQuantity(this,1,'You can buy this product only in multiples of %s pieces!');" title="Update Quantity In Cart" class="" size="3" maxlength="4"/> 
						<span class="quantity_container_plus"></span>

						<button type="submit" style="display:none" class="vmicon vm2-add_quantity_cart" name="updatecart.<?php echo $pkey; ?>" id="updatecart_<?php echo $pkey; ?>" title="Update Quantity In Cart">
					</div>
				</td>
				<td class="col_7"><?php echo number_format($product_price*$item->quantity, 2)  . ' ', $currencySymbol;  ?></td>
				<td class="col_8">
					<!--<a href="" class="myorder_grid_row_delete_item">&nbsp;</a>-->
					<button type="submit" class="myorder_grid_row_delete_item vm2-remove_from_cart" name="delete.<?php echo $pkey; ?>" id="delete_<?php echo $pkey; ?>" title="Delete Product From Cart"/>
				</td>
			</tr>
				<?php $totalPrice += floatval($prices['product_price']*$item->quantity); $i++;?>
			<?php endforeach; ?>

			<?php endif; ?>

			<tr class="">
				<td colspan="5" style="padding-top:25px;border-top:1px solid #DADADA;"></td>	
				<td style="font-size:16px;border-bottom:0px solid #DADADA;text-align:right;padding-right:65px;padding-bottom:15px;padding-top:25px;border-top:1px solid #DADADA;"><?php echo JText::_('SUB_TOTAL') ?></td>	
				<td style="font-size:16px;border-bottom:0px solid #DADADA;padding-bottom:15px;padding-top:25px;border-top:1px solid #DADADA;"><strong><?php echo number_format($totalPrice, 2) . ' ', $currencySymbol; ?></strong></td>	
				<td style="border-top:1px solid #DADADA;"></td>	
			</tr>
            
            <?php if ($check_user_group == "user_opticoach") : ?>
            <tr class="">
				<td colspan="5" style="padding-top:25px;border-top:1px solid #DADADA;"></td>	
				<td style="font-size:16px;border-bottom:0px solid #DADADA;text-align:right;padding-right:65px;padding-bottom:15px;padding-top:25px;border-top:1px solid #DADADA;"><?php echo JText::_('DISCOUNT'), ' ', ceil($tax), '%' ?></td>	
				<td style="font-size:16px;border-bottom:0px solid #DADADA;padding-bottom:15px;padding-top:25px;border-top:1px solid #DADADA;"><strong><?php echo number_format($totalPrice*($tax/100), 2) . ' ', $currencySymbol; ?></strong></td>	
				<td style="border-top:1px solid #DADADA;"></td>	
			</tr>
            <?php endif; ?>
            
			<?php if ( $tax !== false ): ?>
			<?php 
                $tax_value = $totalPrice*floatval($tax/100); 
                $discount_value = $totalPrice*floatval($tax/100);
            ?>
			<tr class="">
				<td colspan="5"></td>	
				<td style="font-size:16px;border-bottom:0px solid #DADADA;text-align:right;padding-right:65px;padding-bottom:15px;"><?php echo JText::_('VAT'), ' ', ceil($tax), '%' ?></td>	
				<td style="font-size:16px;border-bottom:0px solid #DADADA;padding-bottom:15px;"><strong>
                    <?php 
                         if ($check_user_group == "user_opticoach") {
                            $total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                            echo sprintf("%.2f",number_format($total_discount*($tax/100), 2)) .  $currencySymbol;
                         } else {
                            echo sprintf("%.2f",number_format($tax_value, 2)) .$currencySymbol;
                         }                    
                    ?></strong></td>	
				<td></td>	
			</tr>
			<?php endif; ?>
			<tr class="">
				<td colspan="5"></td>	
				<td style="font-size:18px;border-top:1px solid #DADADA;text-transform:uppercase;text-align:right;padding-right:65px;padding-top:15px;"><?php echo JText::_('GRAND_TOTAL') ?></td>	
				<td style="font-size:18px;border-top:1px solid #DADADA;padding-top:15px;"><strong>
                    <?php 
                        if ($check_user_group == "user_opticoach") {
                            $total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                            $total_final = $total_discount + $total_discount*($tax/100);
                            //echo  sprintf("%.2f",number_format( $total_final, 1)) .$currencySymbol;
                            echo sprintf("%.2f",round($total_final,1)) .$currencySymbol;
                            
                        } else {
                            //echo  sprintf("%.2f",number_format( $tax_value + $totalPrice, 1)) .$currencySymbol;
                            echo sprintf("%.2f",round($tax_value + $totalPrice,1)) .$currencySymbol;
                        }
                    ?>
				<td></td>	
			</tr>
		</table>
		<?php if($user): ?>
			<div class="row mycart_sys_buttons">
				<div class="col-sm-12 col-xs-12 col-md-12 col-lg-5 pull-right" style="text-align:right;">
					<button class="continue_shopping" onclick="window.location.href='<?php echo $session->get("lastest_category_request") ?>'; return false;"><?php echo JText::_('CONTINUE_SHOPPING') ?></button>
					<button class="preview_order"><?php echo JText::_('PREVIEW_ORDER') ?></button>
				</div>
			</div>
		<?php endif; ?>
	</div>
	

	<?php if ( $user ): ?>
	<?php $session = JFactory::getSession(); ?>
	<div class="row">
		<div class="additional_block">
			<p><?php echo JText::_('ADDITIONAL_INFOMATION') ?></p>
			<textarea style="font-style:normal;border:none;border-bottom:1px solid #dadada;width:568px;height:40px;" id="additional_information" name="additional_information"><?php echo $session->get('additional_information') ?></textarea>
		</div>
	</div>
	
	<div class="row">
		<div class="user_info_block">
			<div class="page_title_block">
			<h2 class="page_title"><?php echo JText::_('USER_INFO') ?></h2>	

			<div class="avatar_block">
				<?php if ( $user->image ) :?>
					<?php $image = JURI::base(true) . '/' . $user->image; ?>
				<?php else: ?>
					<?php $image = JURI::base(true).'/templates/'.$app->getTemplate().'/' .'images/thumb/user_avatar_default.png'; ?>
				<?php endif; ?>
				<img src="<?php echo $image  ?> " style="width:102px;height:102px;"/>		

			</div>
		</div>
	</div>

	<div class="row user_basic_info_block">
		<div class="col-sm-6 col-xs-12 col-md-4">
			<p class="basic_info_title"><?php echo JText::_('INFO_CLIENT'); ?></p>
			<p class="basic_info_row"><?php echo $user->name ?></p>
			<?php if ( $user->contact ): ?><p class="basic_info_row"><?php echo $user->contact ?></p><?php endif; ?>
			<p class="basic_info_row"><?php echo $user->email ?></p>
			<p class="basic_info_row"><?php echo $user->phone ?></p>
		</div>
		<div class="col-sm-6 col-xs-12 col-md-4">
			<p class="basic_info_title"><?php echo JText::_('INVOICE_ADDRESS') ?></p>
			<p class="basic_info_row"><?php echo $user->infos.'<br />'.$countryTranslationData[$user->country_id]['country_name'] ?></p>

			<p class="basic_info_title delivery_address_block"><?php echo JText::_('DELIVERY_ADDRESS') ?></p>
			<p class="basic_info_row"><?php echo $user->delivery_infos.'<br />'.$countryTranslationData[$user->country_delivery_id]['country_name'] ?></p>
		</div>

	</div>
	<?php endif; ?>
		

	<?php // Continue and Checkout Button END ?>
	<input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
	<input type='hidden' name='task' value='updatecart'/>
	<input type='hidden' name='option' value='com_virtuemart'/>
	<input type='hidden' name='view' value='cart'/>

</div>
</form>

<?php
	$preview_link = JRoute::_ ('index.php?option=com_virtuemart&view=cart&layout=preview_order' . $taskRoute, $this->useXHTML, $this->useSSL);
	if ( strpos($preview_link, '?') === false ) $preview_link = '?layout=preview_order';
	else $preview_link = '&layout=preview_order';
?>
<input type='hidden' name='preview_link' id="preview_link" value='<?php echo $preview_link ?>'/>


<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.myorder_grid_mobile_delete_row button').click(function(e){
			e.stopImmediatePropagation();
			var id = jQuery(this).attr('data-id');
			setTimeout(function(){ 
				jQuery('#' + id).trigger("click");
			}, 500);
		});
		jQuery('.quantity_container_subtract').click(function(e)
		{
			e.stopImmediatePropagation();
			var element = jQuery(this).next();
			var current_qty = parseInt( jQuery(element).val() );
			if ( current_qty <= 1 ) 
			{
				alert("You can not update quantity less than 1!");
				return false;
			}
			if ( current_qty >= 2 ) current_qty -= 1;
			else current_qty = 1;
			jQuery(element).val(current_qty);
			//jQuery(element).val(current_qty);//quantity_input_ data-is-mobile="1" data-product-index="<?php echo ($i  - 1); ?>"
			var check = jQuery(element).attr('data-is-mobile');
			if ( ( typeof check !== typeof undefined && check !== false ) && jQuery(element).attr('data-is-mobile') == 1 )
			{
				var data_index = jQuery(element).attr('data-product-index');
				jQuery('#quantity_input_' + data_index).val( jQuery(element).val() );
			}
			setTimeout(function(){ 
				jQuery('#' + jQuery(element).attr('data-submit-id')).trigger("click");
			}, 500);
		});
		jQuery('.quantity_container_plus').click(function(e)
		{
			e.stopImmediatePropagation();
			var element = jQuery(this).prev();
			var current_qty = parseInt(jQuery(element).val());
			current_qty += 1;
			jQuery(element).val(current_qty);
			var check = jQuery(element).attr('data-is-mobile');
			if ( ( typeof check !== typeof undefined && check !== false )  && jQuery(element).attr('data-is-mobile') == 1 )
			{
				var data_index = jQuery(element).attr('data-product-index');
				jQuery('#quantity_input_' + data_index).val( jQuery(element).val() );

			}
			setTimeout(function(){
				jQuery('#' + jQuery(element).attr('data-submit-id')).trigger("click");
			}, 500);
		});

		jQuery('.preview_order').click(function(e){
			e.stopImmediatePropagation();
			//alert(jQuery('#preview_link').val()); return false;
			jQuery('#checkoutForm').attr('action', jQuery('#preview_link').val());
			jQuery('#checkoutForm').submit();
			return false;
		});



	});


	jQuery(document).ready(function() {
		//jQuery(this).vm2front("stopVmLoading");
		jQuery("#checkoutFormSubmit").bind("click dblclick", function(e){
			jQuery(this).vm2front("startVmLoading");
			e.preventDefault();
			jQuery(this).attr("disabled", "true");
			jQuery(this).removeClass( "vm-button-correct" );
			jQuery(this).addClass( "vm-button" );
			jQuery(this).fadeIn( 400 );
			var name = jQuery(this).attr("name");
			jQuery("#checkoutForm").append("<input name=\""+name+"\" value=\"1\" type=\"hidden\">");
			jQuery("#checkoutForm").submit();
		});


		if ( jQuery('#STsameAsBTjs').is(':checked') ) {
			jQuery('#output-shipto-display').hide();
		} else {
			jQuery('#output-shipto-display').show();
		}
		jQuery('#STsameAsBTjs').click(function(event) {
			if(jQuery(this).is(':checked')){
				jQuery('#STsameAsBT').val('1') ;
				jQuery('#output-shipto-display').hide();
			} else {
				jQuery('#STsameAsBT').val('0') ;
				jQuery('#output-shipto-display').show();
			}
			var form = jQuery('#checkoutFormSubmit');
			document.checkoutForm.submit();
		});
	});

</script>