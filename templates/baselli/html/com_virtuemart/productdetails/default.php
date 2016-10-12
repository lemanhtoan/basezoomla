<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz, Max Galt
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8842 2015-05-04 20:34:47Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/* Let's see if we found the product */
//echo '<pre>'; print_r($_SESSION); die;
$DS  = '/';
 if (!class_exists( 'VmConfig' )) require_once(JPATH_ROOT.$DS.'administrator'.$DS.'components'.$DS.'com_virtuemart'.$DS.'helpers'.$DS.'config.php');
 VmConfig::loadConfig();

 VmConfig::loadJLang('mod_virtuemart_cart', true);
 VmConfig::loadJLang('com_virtuemart', true);
 vmJsApi::jQuery();
 if(!class_exists('VirtueMartCart')) require_once(VMPATH_SITE.$DS.'helpers'.$DS.'cart.php');

 $cart = VirtueMartCart::getCart(false);
 $products = $cart->products;

 $productModel = VmModel::getModel('product');
 $productDetails = array();

 if (!class_exists('CurrencyDisplay')) require_once(VMPATH_ADMIN . $DS. 'helpers' . $DS . 'currencydisplay.php');

$currencyDisplay = CurrencyDisplay::getInstance();

$currencySymbol = $currencyDisplay->getFormattedCurrency(1,0);
$currencySymbol = str_replace('1', '', $currencySymbol);//var_dump($this->product);die("TVX");
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}

echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));

if(vRequest::getInt('print',false)){ ?>
<body onload="javascript:print();">
<?php } ?>

<div class="productdetails-view productdetails">

	<?php // Back To Category Button
	if ($this->product->virtuemart_category_id) {
		$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
		$categoryName = vmText::_($this->product->category_name) ;
	} else {
		$catURL =  JRoute::_('index.php?option=com_virtuemart');
		$categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME') ;
	}
	?>

    <?php // afterDisplayTitle Event
    echo $this->product->event->afterDisplayTitle ?>
	<?php

	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop'));
    ?>
<?php
	$count_images = count ($this->product->images);
	if ($count_images > 1) {
		//echo $this->loadTemplate('images_additional');
	}

	// event onContentBeforeDisplay
	echo $this->product->event->beforeDisplayContent; ?>

	<?php

	//echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'normal'));

    // Product Packaging
    $product_packaging = '';
    if ($this->product->product_box) {
	?>
        <div class="product-box">
	    <?php

	        //echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
	    ?>
        </div>
    <?php } // Product Packaging END ?>

    <?php 
	//echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'onbot'));

    //echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_products','class'=> 'product-related-products','customTitle' => true ));

	//echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_categories','class'=> 'product-related-categories'));

	?>

<?php // onContentAfterDisplay event
echo $this->product->event->afterDisplayContent;

//echo $this->loadTemplate('reviews');

// Show child categories
/*if (VmConfig::get('showCategory', 1)) {
	echo $this->loadTemplate('showcategory');
}*/

$j = 'jQuery(document).ready(function($) {
	Virtuemart.product(jQuery("form.product"));

	$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
			Virtuemart.setproducttype($(this),id);

		}
	});
});';
//vmJsApi::addJScript('recalcReady',$j);

/** GALT
	 * Notice for Template Developers!
	 * Templates must set a Virtuemart.container variable as it takes part in
	 * dynamic content update.
	 * This variable points to a topmost element that holds other content.
	 */
$j = "Virtuemart.container = jQuery('.productdetails-view');
Virtuemart.containerSelector = '.productdetails-view';";

vmJsApi::addJScript('ajaxContent',$j);
//echo '<pre>'; print_r($this->product); die;
echo vmJsApi::writeJS();
$i = 1;
$current_product_id = JRequest::getVar('virtuemart_product_id');
if(is_array($this->product->child)) {
	$product_length = $this->product->product_parent_info['product_length'];
	$product_width = $this->product->product_parent_info['product_width'];
	$product_height = $this->product->product_parent_info['product_height'];
	$product_prices = $this->product->product_parent_info['product_price'];
} else {
	$product_length = $this->product->product_length;
	$product_width = $this->product->product_width;
	$product_height = $this->product->product_width;
	$product_prices = $this->product->prices['product_price'];
}
?> </div>
<div class="details-product">
	<div class="container">
		<div class="row">
			<?php echo $this->loadTemplate('images') ?>
			<div class="parameter">
				<p>
				<?php
					echo (float)$product_length . ' - '.
						(float)$product_width . ' - '.
						(float)$product_height;
				?>
				</p>
			</div>
			<?php
				$user = JFactory::getUser();
				if(!$user->guest):
			?>
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="containter">
							<div class="row">
								<div class="col-xs-6 col-sm-4 col-md-4 prices">
									<span class="product-price" id="v-product-price"><span><?php echo number_format($product_prices, 2) ?></span><?php echo ' ', $currencySymbol ?></span>
									<input type="hidden" name="product_price" id="h-product-price" value="<?php echo number_format($product_prices, 2) ?>" />
								</div>
								<?php echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product)); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
			<div class="models">
			<?php if ( is_object($this->product) && property_exists($this->product, 'child') && is_array($this->product->child) && $this->product->child ): ?>
			<?php foreach($this->product->child as $child): ?>
                            <?php //echo "<pre>"; var_dump($child->virtuemart_product_id);  var_dump($product->categoryItem['virtuemart_category_id']); die; ?>
				<a href="<?php echo 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $child->virtuemart_product_id  . '   ' ?>" class="<?php 
				if(in_array($current_product_id, $this->product->child_id)) {
					if($current_product_id == $child->virtuemart_product_id) {
						echo 'active';
					}
				} else {
					if($i == 1) {
						echo 'active';
					}
				}
				?>" data-id="rgba">
					<img src="<?php echo $child->images[0]->file_url ?>" alt="product">
					<p>
					<?php
						if(in_array($current_product_id, $this->product->child_id)) {
							echo trim(str_replace($this->product->product_parent_name, '', $child->product_name));
						} else {
							echo trim(str_replace($this->product->product_name, '', $child->product_name));
						}
					?>
					</p>
				</a>
			<?php $i++; endforeach; ?>
			<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="current_page" value="product_page" id="current_page" />


