<?php defined('_JEXEC') or die; ?>
<?php
	$userCurrency = getUserCurrency();
	$userCurrencyCode = @$userCurrency->currency_code_3;
	$userCurrencySymbol = @$userCurrency->currency_symbol;

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

	if ( isset($_SESSION['__vm']) && isset($_SESSION['__vm']['vmcart']) ) 
	{
		$vmcart = json_decode($_SESSION['__vm']['vmcart']); 
		$cartProductsData = $vmcart->cartProductsData;
	}
	else {
		$vmcart = array();
		$cartProductsData = array();
	}
	
	$number_of_items = 0;
	$total = 0;
	$db = JFactory::getDbo();

	$realProducts = array();
	$currencyDisplay = CurrencyDisplay::getInstance( );
	//echo strlen('Annette 123');die;//11
	foreach ( $cartProductsData as $item )
	{
		$product = $productModel->getProduct($item->virtuemart_product_id, true, false,true,$item->quantity);
		//$realProducts[$item->virtuemart_product_id] = array();
		//echo "<pre>";var_dump($product);die;
		$number_of_items += intval($item->quantity);
		$virtuemart_media_id = $product->virtuemart_media_id;
		$allPrices = $product->allPrices;
		$product_price = isset($allPrices[0]) && isset($allPrices[0]['product_price']) ? number_format($allPrices[0]['product_price'],2) : 0;

		$image_url = null;
		$currencySymbol = null;
		$toCurrencyCode = null;
		if ( $product_price )
		{
			$product_currency = $allPrices[0]['product_currency'];
			$currencyDisplay = CurrencyDisplay::getInstance( $product_currency);
			$currencySymbol = $currencyDisplay->getSymbol();
			$toCurrency = getCurrencyRow($product_currency);
			$toCurrencyCode = @$toCurrency->currency_code_3;
		}
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
		//var_dump($toCurrencyCode);die;
		if ( strlen($toCurrencyCode) && strlen($userCurrencyCode) ) 
		{
			//$product_price = convertCurrency($product_price, $toCurrencyCode, $userCurrencyCode);	
		}
		$short_name = $product->product_name;
		if ( mb_strlen($product->product_name) >= 7 )
		{
			$short_name = mb_substr($short_name, 0, 7) . '...';
		}
		$productDetails[$item->virtuemart_product_id] = array(
			'name' => $product->product_name,
			'short_name' => $short_name,
			'product_url' => JRoute::_($product->link),
			'price' => $product_price,
			'image_url' => $image_url,
			'quantity' => $item->quantity, 
			'currencySymbol' => $currencySymbol

		);
		$total += $product_price*$item->quantity;
	}
//	var_dump($currencyDisplay->_vendorCurrency);die;

	$currencySymbol = null;
	if ( $currencyDisplay->_vendorCurrency )
	{
		$siteCurrencyDisplay = CurrencyDisplay::getInstance( $currencyDisplay->_vendorCurrency );
		$currencySymbol = $currencyDisplay->getSymbol();	
	}
	$countItems = count($productDetails);
	/*$app    = JFactory::getApplication();
	$path   = JURI::base(true).'/templates/'.$app->getTemplate().'/images/thumb/ad-1.jpg';*/
?>
<a id="cart" class="cart_icon"></a>
<span class="cart_icon_items"><?php echo $number_of_items ?></span>
<?php //if ( $number_of_items ): ?> 
<div class="cart_dropdown <?php if ( $countItems > 5 ): ?>cart_dropdown_over_five_items<?php endif; ?>" style="display:none;">
	<div class="cart_icon_items_grid">
		<?php foreach ( $productDetails as $item ): ?>
		<div class="cart_icon_item_row">
			<img class="dropdown_item_image" src="<?php echo $item['image_url'] ?>" style="width:61px;height:auto;float:left; margin-top: 6px; max-height: 31px"/>
			<div class="dropdown_item_name">
				<p class="dropdown_item_name_first">
					<a href="<?php echo $item['product_url'] ?>" title="<?php echo $item['name'] ?>" alt="<?php echo $item['name'] ?>"><?php echo $item['short_name'] ?></a>
					<span class="num">x<?php echo $item['quantity'] ?>&nbsp;&nbsp;<?php echo number_format($item['price']*$item['quantity'], 2) ?><?php echo ' ', $currencySymbol; ?>
					</span>
				</p>
			</div>
		</div>
		<?php endforeach; ?>

		<div class="dropdown_total_block">
			<label class="dropdown_total_label"><?php echo JText::_('TOTAL') ?></label>
			<span class="dropdown_total_price"><?php echo number_format($total, 2) ?><?php echo ' ', $currencySymbol; ?></span>
			<p>
			<button class="view_cart_but" data-url="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE) ?>"><?php echo JText::_('VIEW_CART') ?></button>
			</p>
		</div>
	</div>
</div>

<?php //endif; ?>