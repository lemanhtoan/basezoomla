<?php

/**
 * Controller for the cart
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 8847 2015-05-06 12:22:37Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * Controller for the cart view
 *
 * @package VirtueMart
 * @subpackage Cart
 */

class VirtueMartControllerCart extends JControllerLegacy {

	/**
	 * Construct the cart
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct();
		if (VmConfig::get('use_as_catalog', 0)) {
			$app = JFactory::getApplication();
			$app->redirect('index.php');
		} else {
			if (!class_exists('VirtueMartCart'))
			require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
			if (!class_exists('calculationHelper'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
		}
		$this->useSSL = VmConfig::get('useSSL', 0);
		$this->useXHTML = false;
	}

	public function checkUserRights()
	{
		//check user role
		$user = JFactory::getUser();
		if ( !$user || !$user->id ) 
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('Please login to use cart function!'), '');
			$app->redirect('index.php');
			exit();
		}
		
	}

	/**
	 * Override of display
	 *
	 * @return  JController  A JController object to support chaining.
	 * @since   11.1
	 */
	public function display($cachable = false, $urlparams = false){
		$this->checkUserRights();

		if(VmConfig::get('use_as_catalog', 0)){
			// Get a continue link
			$virtuemart_category_id = shopFunctionsF::getLastVisitedCategoryId();
			$categoryLink = '';
			if ($virtuemart_category_id) {
				$categoryLink = '&virtuemart_category_id=' . $virtuemart_category_id;
			}
			$ItemId = shopFunctionsF::getLastVisitedItemId();
			$ItemIdLink = '';
			if ($ItemId) {
				$ItemIdLink = '&Itemid=' . $ItemId;
			}

			$continue_link = JRoute::_('index.php?option=com_virtuemart&view=category' . $categoryLink . $ItemIdLink, FALSE);
			$app = JFactory::getApplication();
			$app ->redirect($continue_link,'This is a catalogue, you cannot acccess the cart');
		}

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = vRequest::getCmd('view', $this->default_view);
		$viewLayout = vRequest::getCmd('layout', 'default');

		$view = $this->getView($viewName, $viewType, '', array('layout' => $viewLayout));

		$view->assignRef('document', $document);

		$cart = VirtueMartCart::getCart();

		$cart->order_language = vRequest::getString('order_language', $cart->order_language);

		$cart->prepareCartData();
		$request = vRequest::getRequest();
		$task = vRequest::getCmd('task');
		if(($task == 'confirm' or isset($request['confirm'])) and !$cart->getInCheckOut()){

			$cart->confirmDone();
			$view = $this->getView('cart', 'html');
			$view->setLayout('order_done');
			$cart->_fromCart = false;
			$view->display();
			return true;
		} else {
			//$cart->_inCheckOut = false;
			$redirect = (isset($request['checkout']) or $task=='checkout');
			$cart->_inConfirm = false;
			$cart->checkoutData($redirect);
		}

		$cart->_fromCart = false;
		$view->display();

		return $this;
	}

	public function updatecart($html=true,$force = null){
		$this->checkUserRights();

		$cart = VirtueMartCart::getCart();
		$cart->_fromCart = true;
		$cart->_redirected = false;
		if(vRequest::get('cancel',0)){
			$cart->_inConfirm = false;
		}
		if($cart->getInCheckOut()){
			vRequest::setVar('checkout',true);
		}
		$cart->saveCartFieldsInCart();

		if($cart->updateProductCart()){
			vmInfo('COM_VIRTUEMART_PRODUCT_UPDATED_SUCCESSFULLY');
		}

		$STsameAsBT = vRequest::getInt('STsameAsBT', vRequest::getInt('STsameAsBTjs',false));
		if($STsameAsBT){
			$cart->STsameAsBT = $STsameAsBT;
		}

		$addionalInput = vRequest::getVar('additional_information', null);
		$session = JFactory::getSession();
		$session->set('additional_information', $addionalInput);

		$currentUser = JFactory::getUser();
		if(!$currentUser->guest){
			$cart->selected_shipto = vRequest::getVar('shipto', $cart->selected_shipto);
			if(!empty($cart->selected_shipto)){
				$userModel = VmModel::getModel('user');
				$stData = $userModel->getUserAddressList($currentUser->id, 'ST', $cart->selected_shipto);

				if(isset($stData[0]) and is_object($stData[0])){
					$stData = get_object_vars($stData[0]);
					$cart->ST = $stData;
					$cart->STsameAsBT = 0;
				} else {
					$cart->selected_shipto = 0;
				}
			}
			if(empty($cart->selected_shipto)){
				$cart->STsameAsBT = 1;
				$cart->selected_shipto = 0;
				//$cart->ST = $cart->BT;
			}
		} else {
			$cart->selected_shipto = 0;
			if(!empty($cart->STsameAsBT)){
				//$cart->ST = $cart->BT;
			}
		}

		if(!isset($force))$force = VmConfig::get('oncheckout_opc',true);
		$cart->setShipmentMethod($force, !$html);
		$cart->setPaymentMethod($force, !$html);

		$cart->prepareCartData();

		$coupon_code = trim(vRequest::getString('coupon_code', ''));
		if(!empty($coupon_code)){
			$msg = $cart->setCouponCode($coupon_code);
			if($msg) vmInfo($msg);
		}


		if ($html) {
			$this->display();
		} else {
			$json = new stdClass();
			ob_start();
			$this->display ();
			$json->msg = ob_get_clean();
			echo json_encode($json);
			jExit();
		}

	}


	public function updatecartJS(){
		$this->updatecart(false);
	}


	/**
	 * legacy
	 * @deprecated
	 */
	public function confirm(){
		$this->updatecart();
	}

	public function setshipment(){
		$this->updatecart(true,true);
	}

	public function setpayment(){
		$this->updatecart(true,true);
	}

	/**
	 * Add the product to the cart
	 * @access public
	 */
	public function add() {
		$mainframe = JFactory::getApplication();
		if (VmConfig::get('use_as_catalog', 0)) {
			$msg = vmText::_('COM_VIRTUEMART_PRODUCT_NOT_ADDED_SUCCESSFULLY');
			$type = 'error';
			$mainframe->redirect('index.php', $msg, $type);
		}
		$cart = VirtueMartCart::getCart();
		if ($cart) {
			$virtuemart_product_ids = vRequest::getInt('virtuemart_product_id');
			$error = false;
			$cart->add($virtuemart_product_ids,$error);
			if (!$error) {
				$msg = vmText::_('COM_VIRTUEMART_PRODUCT_ADDED_SUCCESSFULLY');
				$type = '';
			} else {
				$msg = vmText::_('COM_VIRTUEMART_PRODUCT_NOT_ADDED_SUCCESSFULLY');
				$type = 'error';
			}

			$mainframe->enqueueMessage($msg, $type);
			$mainframe->redirect(JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE));

		} else {
			$mainframe->enqueueMessage('Cart does not exist?', 'error');
		}
	}

	/**
	 * Add the product to the cart, with JS
	 * @access public
	 */
	public function addJS() {
		require_once JPATH_ROOT . '/modules/mod_viewcart/viewcart_lib.php';

		// VM code
		$this->json = new stdClass();
		$cart = VirtueMartCart::getCart(false);
		if ($cart) {
			$view = $this->getView ('cart', 'json');
			$virtuemart_category_id = shopFunctionsF::getLastVisitedCategoryId();
			$categoryLink='';
			if ($virtuemart_category_id) {
				$categoryLink = '&view=category&virtuemart_category_id=' . $virtuemart_category_id;
			}

			$continue_link = JRoute::_('index.php?option=com_virtuemart' . $categoryLink);

			$virtuemart_product_ids = vRequest::getInt('virtuemart_product_id');

			$view = $this->getView ('cart', 'json');
			$errorMsg = 0;

			$products = $cart->add($virtuemart_product_ids, $errorMsg );


			$view->setLayout('padded');
			$this->json->stat = '1';

			if(!$products or count($products) == 0){
				$product_name = vRequest::get('pname');
				$virtuemart_product_id = vRequest::getInt('pid');
				if($product_name && $virtuemart_product_id) {
					$view->product_name = $product_name;
					$view->virtuemart_product_id = $virtuemart_product_id;
				} else {
					$this->json->stat = '2';
				}
				$view->setLayout('perror');
			}

			$view->assignRef('products',$products);
			$view->assignRef('errorMsg',$errorMsg);

			ob_start();
			$view->display ();
			$this->json->msg = ob_get_clean();
		} else {
			$this->json->msg = '<a href="' . JRoute::_('index.php?option=com_virtuemart', FALSE) . '" >' . vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING') . '</a>';
			$this->json->msg .= '<p>' . vmText::_('COM_VIRTUEMART_MINICART_ERROR') . '</p>';
			$this->json->stat = '0';
		}

		// Return cart html
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
		$currencyDisplay = CurrencyDisplay::getInstance();
		//echo strlen('Annette 123');die;//11
		foreach ( $cartProductsData as $item )
		{
			$product = $productModel->getProduct($item->virtuemart_product_id, true, false,true,$item->quantity);
			//$realProducts[$item->virtuemart_product_id] = array();
			//echo "<pre>";var_dump($product);die;
			$number_of_items += intval($item->quantity);
			$virtuemart_media_id = $product->virtuemart_media_id;
			$allPrices = $product->allPrices;
			$product_price = isset($allPrices[0]) && isset($allPrices[0]['product_price']) ? number_format($allPrices[0]['product_price'],0) : 0;

			$image_url = null;
			$currencySymbol = null;
			$toCurrencyCode = null;
			if ( $product_price )
			{
				//echo "<pre>"; var_dump($allPrices[0]);die;
				$product_currency = $allPrices[0]['product_currency'];
				$currencyDisplay = CurrencyDisplay::getInstance( $product_currency);
				//echo "<pre>"; var_dump($currencyDisplay->getSymbol());die;
				//echo $currencyDisplay->getCurrencyForDisplay($product_currency);die;
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
		$total = number_format($total, 0);
		$drop_over = $countItems > 5 ? 'cart_dropdown_over_five_items' : '';
		$cart_html = '';
		$cart_html .= '<a id="cart" class="cart_icon"></a>'; 
		$cart_html .= '<span class="cart_icon_items">'.$number_of_items.'</span>';
		$cart_html .= '<div class="cart_dropdown '.$drop_over.'" style="display:none;">';
		$cart_html .= '<div class="cart_icon_items_grid">';
		foreach($productDetails as $item) {
			$prices = number_format($item['price']*$item['quantity'], 0);
			$cart_html .= '<div class="cart_icon_item_row">';
			$cart_html .= '<img class="dropdown_item_image" src="'.$item['image_url'].'" style="width:61px;height:auto;float:left; margin-top: 6px;"/>';
			$cart_html .= '<div class="dropdown_item_name"><p class="dropdown_item_name_first">';
			$cart_html .= '<a href="'.$item['product_url'].'" title="'.$item['name'].'" alt="'.$item['name'].'">'.$item['short_name'].'</a>';
			$cart_html .= '<span class="num">x'.$item['quantity'];
			$cart_html .= '&nbsp;&nbsp;'.$prices.' ' . $currencySymbol;
			$cart_html .= '</span>';
			$cart_html .= '</p></div>';
		}
		$cart_html .= '<div class="dropdown_total_block">';
		$cart_html .= '<label class="dropdown_total_label">'.JText::_('TOTAL').'</label>';
		$cart_html .= '<span class="dropdown_total_price">'.$total.' ' . $currencySymbol . '</span>';
		$cart_html .= '<p>';
		$cart_html .= '<button class="view_cart_but" data-url="'.JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE).'">'.JText::_('VIEW_CART').'</button>';
		$cart_html .= '</p></div></div></div>';
		
		$this->json->cart_html = $cart_html;
		echo json_encode($this->json);
		jExit();
	}

	/**
	 * Add the product to the cart, with JS
	 *
	 * @access public
	 */
	public function viewJS() {

		if (!class_exists('VirtueMartCart'))
		require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart(false);
		$cart -> prepareCartData();
		$data = $cart -> prepareAjaxData(true);

		echo json_encode($data);
		Jexit();
	}

	/**
	 * For selecting couponcode to use, opens a new layout
	 */
	public function edit_coupon() {

		$view = $this->getView('cart', 'html');
		$view->setLayout('edit_coupon');

		// Display it all
		$view->display();
	}

	/**
	 * Store the coupon code in the cart
	 * @author Max Milbers
	 */
	public function setcoupon() {

		/* Get the coupon_code of the cart */
		$coupon_code = vRequest::getString('coupon_code', '');

		$cart = VirtueMartCart::getCart();
		if ($cart) {
			$this->couponCode = '';

			if (!empty($coupon_code)) {
				$app = JFactory::getApplication();
				$msg = $cart->setCouponCode($coupon_code);
				$cart->setOutOfCheckout();
				$app->redirect(JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE),$msg);
			}
		}
	}


	/**
	 * For selecting shipment, opens a new layout
	 */
	public function edit_shipment() {


		$view = $this->getView('cart', 'html');
		$view->setLayout('select_shipment');

		// Display it all
		$view->display();
	}

	/**
	 * To select a payment method
	 */
	public function editpayment() {

		$view = $this->getView('cart', 'html');
		$view->setLayout('select_payment');

		// Display it all
		$view->display();
	}

	/**
	 * Delete a product from the cart
	 * @access public
	 */
	public function delete() {
		$mainframe = JFactory::getApplication();
		/* Load the cart helper */
		$cart = VirtueMartCart::getCart();
		if ($cart->removeProductCart())
		$mainframe->enqueueMessage(vmText::_('COM_VIRTUEMART_PRODUCT_REMOVED_SUCCESSFULLY'));
		else
		$mainframe->enqueueMessage(vmText::_('COM_VIRTUEMART_PRODUCT_NOT_REMOVED_SUCCESSFULLY'), 'error');

		$this->display();
	}

	public function getManager(){
		$adminID = JFactory::getSession()->get('vmAdminID',false);
		if($adminID) {
			if(!class_exists( 'vmCrypt' ))
				require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcrypt.php');
			$adminID = vmCrypt::decrypt( $adminID );
			return JFactory::getUser( $adminID );
		} else {
			return JFactory::getUser();
		}
	}

	/**
	 * Change the shopper
	 *
	 * @author Maik Künnemann
	 */
	public function changeShopper() {
		JSession::checkToken () or jexit ('Invalid Token');
		$current = $this->getManager();
		$manager = false;

		$redirect = vRequest::getString('redirect',false);
		if($redirect){
			$red = $redirect;
		} else {
			$red = JRoute::_('index.php?option=com_virtuemart&view=cart');
		}

		$app = JFactory::getApplication();
		if($current->authorise('core.admin', 'com_virtuemart')){
			$admin = true;
		} else if($current->authorise('vm.user', 'com_virtuemart')){
			$manager = true;
		} else {

			$app->enqueueMessage(vmText::sprintf('COM_VIRTUEMART_CART_CHANGE_SHOPPER_NO_PERMISSIONS', $current->name .' ('.$current->username.')'), 'error');
			$app->redirect($red);
			return false;
		}

		$userID = vRequest::getCmd('userID');
		$newUser = JFactory::getUser($userID);

		if($manager and !empty($userID) and $userID!=$current->id){
			if($newUser->authorise('core.admin', 'com_virtuemart') or $newUser->authorise('vm.user', 'com_virtuemart')){
				$app->enqueueMessage(vmText::sprintf('COM_VIRTUEMART_CART_CHANGE_SHOPPER_NO_PERMISSIONS', $current->name .' ('.$current->username.')'), 'error');
				$app->redirect($red);
			}
		}

		$searchShopper = vRequest::getString('searchShopper');

		if(!empty($searchShopper)){
			$this->display();
			return false;
		}

		//update session
		$session = JFactory::getSession();
		$adminID = $session->get('vmAdminID');
		if(!isset($adminID)) {
			if(!class_exists('vmCrypt'))
				require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcrypt.php');
			$session->set('vmAdminID', vmCrypt::encrypt($current->id));
		}
		$session->set('user', $newUser);

		//update cart data
		$cart = VirtueMartCart::getCart();
		$usermodel = VmModel::getModel('user');
		$data = $usermodel->getUserAddressList(vRequest::getCmd('userID'), 'BT');
		foreach($data[0] as $k => $v) {
			$data[$k] = $v;
		}
		$cart->BT['email'] = $newUser->email;

		$cart->ST = 0;
		$cart->STsameAsBT = 1;
		$cart->selected_shipto = 0;
		$cart->virtuemart_shipmentmethod_id = 0;
		$cart->saveAddressInCart($data, 'BT');

		$msg = vmText::sprintf('COM_VIRTUEMART_CART_CHANGED_SHOPPER_SUCCESSFULLY', $newUser->name .' ('.$newUser->username.')');

		if(empty($userID)){
			$red = JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT');
			$msg = vmText::sprintf('COM_VIRTUEMART_CART_CHANGED_SHOPPER_SUCCESSFULLY','');
		}

		$app->enqueueMessage($msg, 'info');
		$app->redirect($red);
	}


	function cancel() {

		$cart = VirtueMartCart::getCart();
		if ($cart) {
			$cart->setOutOfCheckout();
		}
		$this->display();
	}

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

	function updateOrder()
	{

		$lang = JFactory::getLanguage();
		$language_code = str_replace('-', '_', $lang->getTag());
		$field_template_content = 'order_email_template_content_' . $language_code;
		//echo 'Current language is: ' . $language_code;->{$language_code}

		$this->checkUserRights();

		$settings = JModuleHelper::getModule('mod_setting');
		$emailSettings = json_decode($settings->params); 

		$app = JFactory::getApplication();

		$cart = VirtueMartCart::getCart();
		$cart->order_language = vRequest::getString('order_language', $cart->order_language);
        	  
		$cart->prepareCartData();
		$DS  = '/';
		if (!class_exists('CurrencyDisplay')) require_once(VMPATH_ADMIN . $DS. 'helpers' . $DS . 'currencydisplay.php');
		$currencyDisplay = CurrencyDisplay::getInstance( $cart->pricesCurrency );
		if (!class_exists('VirtueMartCart'))
		require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');

		$user = JFactory::getUser(); 
		$db = JFactory::getDbo();
		//reload data not using cache
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from( $db->quoteName('#__users') );
		$query->where( $db->quoteName('id') . ' = '. intval($user->id) );
		$db->setQuery($query);
		$user = $db->loadObject();

		$app    = JFactory::getApplication();
		$path   = JURI::base(true).'/templates/'.$app->getTemplate().'/images/thumb/ad-1.jpg';
		$items 	= $cart->products;
        
       
        $check_user_group = $user->user_retail_group;

		$i = 1;
		//$currencySymbol = $currencyDisplay->getFormattedCurrency(1,0);
		$currencySymbol = $currencyDisplay->getSymbol();//str_replace('1', '', $currencySymbol);
		$totalPrice = 0;
		$productModel = VmModel::getModel('product');

		$query = $db->getQuery(true);
		$columns = array('virtuemart_user_id', 'virtuemart_vendor_id', 'order_total', 'order_salesPrice', 'order_billTaxAmount','ip_address', 'additional_infor', 'order_number'
			, 'created_on', 'order_status'
			, 'street_shipping', 'city_shipping', 'zipcode_shipping', 'country_shipping');   
		//$totalPrice = 0;echo "<pre>"; var_dump($items);die;
		foreach ( $items as $item )
		{
			$prices = $item->prices;
			$totalPrice += floatval($prices['product_price']*$item->quantity);
		}
		

  
		try {
			$order_detail = '<table class="myorder_grid" id="myorder_grid" style="width: 100%; border-collapse: collapse; margin-top: 30px;color:#333333;">';
				$order_detail .= '<tr class="myorder_grid_header">';
					$order_detail .= '<td class="col_1" style="color:#333333;text-align: center;padding-bottom: 7px;border-width: 3px;border-bottom-style:double;border-color:#dadada;width:57px;">' . JText::_('NOS'). '</td>';
					$order_detail .= '<td class="col_2" style="color:#333333;text-align: center;padding-bottom: 7px;border-width: 3px;border-bottom-style:double;border-color:#dadada;width:89px;"></td>';
					$order_detail .= '<td class="col_3" style="color:#333333;text-align: center;padding-bottom: 7px;border-width: 3px;border-bottom-style:double;border-color:#dadada;width:98;">' . JText::_('Name'). '</td>';
					$order_detail .= '<td class="col_4" style="color:#333333;text-align: center;padding-bottom: 7px;border-width: 3px;border-bottom-style:double;border-color:#dadada;width:101px;">' . JText::_('Color'). '</td>';
					$order_detail .= '<td class="col_5" style="color:#333333;text-align: center;padding-bottom: 7px;border-width: 3px;border-bottom-style:double;border-color:#dadada;width:89px;">' . JText::_('Price'). '</td>';
					$order_detail .= '<td class="col_6" style="color:#333333;text-align: center;padding-bottom: 7px;border-width: 3px;border-bottom-style:double;border-color:#dadada;width:86px;">' . JText::_('Quantity'). '</td>';
					$order_detail .= '<td class="col_7" style="color:#333333;text-align: right;padding-bottom: 7px;border-width: 3px;border-bottom-style:double;border-color:#dadada;width:90px;">' . JText::_('Total'). '</td>';
				$order_detail .= '</tr>';



			//get country id
			$virtuemart_country_id = 0;
			$query = $db->getQuery(true);
			$query->select('virtuemart_country_id');
			$query->from( $db->quoteName('#__virtuemart_countries') );
			$query->where( $db->quoteName('country_3_code') . ' = '. $db->quote($user->country_delevery) );
			$db->setQuery($query);
			$countryRow = $db->loadObject();
			if ( is_object($countryRow)  && property_exists($countryRow, 'virtuemart_country_id') )  $virtuemart_country_id = intval($countryRow->virtuemart_country_id);

			$session = JFactory::getSession();
           
            
            //get country id
			$virtuemart_country_id = 0;
			$query = $db->getQuery(true);
			$query->select('virtuemart_country_id');
			$query->from( $db->quoteName('#__virtuemart_countries') );
			$query->where( $db->quoteName('country_3_code') . ' = '. $db->quote($user->country) );
			$db->setQuery($query);
			$countryRow = $db->loadObject();
			if ( is_object($countryRow)  && property_exists($countryRow, 'virtuemart_country_id') )  $virtuemart_country_id = intval($countryRow->virtuemart_country_id);

			//get user tax
			$tax = $this->getUserCountryTax($virtuemart_country_id);
            $tax_value = $totalPrice*floatval($tax/100);
                        
            if ($check_user_group == "user_opticoach") {
                $total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                $total_final = $total_discount + $total_discount * ($tax/100);
                
                //$totalPrice_custom = number_format($total_final, 1);
                $totalPrice_custom = round($total_final, 1);
                $tax_value = number_format($total_discount*($tax/100), 2);  
                $totalPrice = round($totalPrice, 2);  
            } else {
                //$totalPrice_custom = number_format( ($totalPrice*floatval($tax/100) + $totalPrice), 1);
                $totalPrice_custom = round( ($totalPrice*floatval($tax/100) + $totalPrice), 1);
                $tax_value = number_format( ($totalPrice*floatval($tax/100)),2);
                $totalPrice = round($totalPrice, 2);
            }
            
            //echo $totalPrice . ' // ' . $tax_value . ' // ' . $totalPrice_custom; die;
            
			$values = array( 
                $db->quote($user->id), 
                0, 
                $totalPrice,  // sub total
                $tax_value,  // update = VAT
                $totalPrice_custom,  // grand total
                $db->quote($_SERVER['REMOTE_ADDR']),
                $db->quote($session->get('additional_information')), 
                time(), 
                $db->quote( date("Y-m-d H:i:s") ),
                $db->quote('P'),
                $db->quote($user->street_delevery),
                $db->quote($user->city_delevery),
                $db->quote($user->zipcode_delevery),
                $db->quote($virtuemart_country_id) 
            );
            
            
            
            //store user_infor
            // Country
        	$db = JFactory::getDbo();
        	//reload data not using cache
        	$query = $db->getQuery(true);
        
        	$query = 'SELECT u.*, CONCAT(u.`street`, ", ", u.`city`, ", ", u.`zipcode`) infos, CONCAT(u.`street_delevery`, ", ", u.`city_delevery`, ", ", u.`zipcode_delevery`) delivery_infos, (SELECT `virtuemart_country_id` FROM `#__virtuemart_countries` c where c.`country_3_code` = u.`country`) country_id, (SELECT `virtuemart_country_id` FROM `#__virtuemart_countries` c where c.`country_3_code` = u.`country_delevery`) country_delivery_id FROM `#__users` u where u.`id` = '.intval($user->id);
        	
        	$db->setQuery($query);
        	$user = $db->loadObject();
            
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
            
            $user_invoice_addrees = $user->infos.'{{COMMA}} '.$countryTranslationData[$user->country_id]['country_name'];
            $user_delivery_addrees = $user->delivery_infos.'{{COMMA}} '.$countryTranslationData[$user->country_delivery_id]['country_name'];
            
            $user_infor = json_encode(array(
                'user_id' => $user->id,
                'user_name' => $user->name, 
                'user_contact' => $user->contact,
                'user_email' => $user->email,
                'user_phone' => $user->phone,
                'user_invoice_addrees' => $user_invoice_addrees,
                'user_delivery_addrees' => $user_delivery_addrees,
            ));
        
            
            //var_dump($columns['user_infor']);die;
            
            //echo "<pre>";var_dump($columns, $values) . '<hr>'; die;
            
			$query->insert($db->quoteName('#__virtuemart_orders'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			$db->setQuery($query);
			
            //echo $query; die;
            $db->query();
			
            $orderId = $db->insertid();
            
            //set user info
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('user_infor') . ' = ' . $db->quote($user_infor),
            );
            $conditions = array(
                $db->quoteName('virtuemart_order_id') . ' = ' . $orderId, 
            );
            $query->update($db->quoteName('#__virtuemart_orders'))->set($fields)->where($conditions);
            $db->setQuery($query);
            
            

            
            $updateOrderRow = $db->execute();


			//insert user infor

			//get country id
			$virtuemart_country_id = 0;
			$query = $db->getQuery(true);
			$query->select('virtuemart_country_id');
			$query->from( $db->quoteName('#__virtuemart_countries') );
			$query->where( $db->quoteName('country_3_code') . ' = '. $db->quote($user->country) );
			$db->setQuery($query);
			$countryRow = $db->loadObject();
			if ( is_object($countryRow)  && property_exists($countryRow, 'virtuemart_country_id') )  $virtuemart_country_id = intval($countryRow->virtuemart_country_id);

			//get user tax
			$tax = $this->getUserCountryTax($virtuemart_country_id);

			$columns = array('virtuemart_order_id', 'virtuemart_user_id', 'company', 'first_name', 'middle_name','phone_1', 'address_1', 'city','email' ,'zip','address_type','virtuemart_country_id');  
			$values = array(
				$orderId, 
				$user->id, 
				$db->quote(''), 
				$db->quote($user->name),
				$db->quote(''), 
				$db->quote($user->phone),
				$db->quote($user->street),
				$db->quote($user->city),
				$db->quote($user->email),
				$db->quote($user->zipcode),
				$db->quote('BT'),
				$virtuemart_country_id,
			);
			$query = $db->getQuery(true);
			$query->insert($db->quoteName('#__virtuemart_order_userinfos'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			$db->setQuery($query);
			$db->query();
		
			

			//virtuemart_order_items
			$columns = array('virtuemart_order_id', 'virtuemart_product_id', 'order_item_sku', 'order_item_name', 'product_quantity','product_item_price', 'product_final_price', 'created_on');  
			$rowCount = 0;

			foreach ( $items as $item )
			{
				$rowCount++;
				//echo "<pre>";var_dump($item);die;
				$prices = $item->prices;
				$values = array(
					$orderId, 
					$item->virtuemart_product_id, 
					$db->quote($item->product_sku), 
					$db->quote($item->product_name),
					$item->quantity, 
					floatval($prices['product_price']), 
					floatval($prices['product_price']), 
					$db->quote( date("Y-m-d H:i:s") )
				);
				$query = $db->getQuery(true);
				$query->insert($db->quoteName('#__virtuemart_order_items'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));
				$db->setQuery($query);
				$db->query();

				//get data for email template
				$product_parent_id = $item->product_parent_id;
				$virtuemart_media_id = $item->virtuemart_media_id;
				$image_url = null;
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
						$image_url = JURI::base() . $image->file_url;	
						$exp = explode('/', $image->file_url);
						//fix file name contains spaces
						if ( is_array($exp) && count($exp) > 1)
						{
							$nCount = count($exp);
							$image_url = JURI::base();
							for ( $i = 0; $i < $nCount - 1; $i++) 
							{
								$image_url .= $exp[$i] . '/';
							}
							$image_url .= rawurlencode($exp[$nCount - 1]);
						}
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
				$product_price = $prices['product_price'];

				$order_detail .= '<tr class="myorder_grid_first_row myorder_grid_row">';
					$order_detail .= '<td class="col_1" style="color:#333333;padding:10px;text-align: center;">' .$rowCount. '</td>';
					$order_detail .= '<td class="col_2" style="color:#333333;padding:10px;text-align: center;"><img src="' . $image_url . '" style="width:114px;max-height:50px;"/></td>';
					$order_detail .= '<td class="col_3" style="color:#333333;padding:10px;text-align: center;">' . $product_name .'</td>';
					$order_detail .= '<td class="col_4" style="color:#333333;padding:10px;text-align: center;">' . $color_name. '</td>';
					$order_detail .= '<td class="col_5" style="color:#333333;padding:10px;text-align: center; width:140px">' . number_format($product_price, 2). ' ' . $currencySymbol . '</td>';
					$order_detail .= '<td class="col_6" style="color:#333333;padding:10px;text-align: center;">' . $item->quantity. '</td>';
					$order_detail .= '<td class="col_7" style="color:#333333;text-align: right; width:140px">' . number_format($product_price*$item->quantity, 2). ' ' . $currencySymbol . '</td>';
				$order_detail .= '</tr>';
			}

			$order_detail .= '<tr class="myorder_grid_last_row myorder_grid_row" style="border-top:1px solid #dadada;">';
				$order_detail .= '<td colspan="3"></td>';
				$order_detail .= '<td colspan="4" style="text-align:right;padding-top:20px;">';
					
					$order_detail .= '<div style="float:right;width:318px;border:0px solid;">';	
						$order_detail .= '<div style="margin-right:40px;float:left;text-align:right;color:#333333;width:135px;font-size:15px;">' . JText::_('Total') . '</div>';	
						$order_detail .= '<div style="text-align:right;width:110px;float:right;font-size:15px;"><strong style="text-align:right;">' . number_format($totalPrice, 2) . ' ' . $currencySymbol  .'</strong></div>';	
					$order_detail .= '</div>';			

					//add VAT in there $tax
                    if ($check_user_group == "user_opticoach") { 
                        
                        $discount_value = $totalPrice*floatval($tax/100);
						$order_detail .= '<div style="float:right;width:318px;border:0px solid;font-size:15px;margin-top:15px;">';	
							$order_detail .= '<div style="margin-right:40px;float:left;text-align:right;color:#333333;width:135px;font-size:16px;">' . JText::_('DISCOUNT') . ' '. ceil($tax) .'%' .'</div>';	
							$order_detail .= '<div style="text-align:right;width:110px;float:right;font-size:15px;"><strong style="text-align:right;">' . '- ' . number_format($totalPrice*($tax/100), 2) .' CHF</strong></div>';	
						$order_detail .= '</div>';
                    }
                    
                    
                    if ($check_user_group == "user_opticoach") {
                        $total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                        $tax_group =  number_format( ($total_discount * ($tax/100)), 2) . ' '. $currencySymbol;
                    } else {
                        $tax_group = number_format( ($totalPrice*floatval($tax/100)), 2). ' '. $currencySymbol; 
                    }
                    
					$tax_value = 0;
					if ( $tax !== false ) 
					{
						$tax_value = $totalPrice*floatval($tax/100);
						$order_detail .= '<div style="float:right;width:318px;border:0px solid;font-size:15px;margin-top:15px;">';	
							$order_detail .= '<div style="margin-right:40px;float:left;text-align:right;color:#333333;width:135px;font-size:15px;">' . JText::_('VAT') . ' ' . ceil($tax) .'%</div>';	
							$order_detail .= '<div style="text-align:right;width:110px;float:right;font-size:15px;"><strong style="text-align:right;">' . 
                               $tax_group 
                            .'</strong></div>';	
						$order_detail .= '</div>';	
					}

                    if ($check_user_group == "user_opticoach") {
                        $total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                        $total_final = $total_discount + $total_discount*($tax/100);
                        //$check_user_group_value =  sprintf("%.2f",number_format( $total_final, 1)) . ' '.$currencySymbol;
                        $check_user_group_value =  sprintf("%.2f",round( $total_final, 1)) . ' '.$currencySymbol;
                            
                        //$total_discount = $totalPrice - $totalPrice*floatval($tax/100);
                        //$total_final = $total_discount + ($total_discount * ($tax/100));                     
                        //$check_user_group_value = number_format($total_final, 2) . ' '. $currencySymbol;
                    } else {
                        $check_user_group_value =  sprintf("%.2f",round( $tax_value + $totalPrice, 1)) . ' '.$currencySymbol;
                        //$check_user_group_value =  sprintf("%.2f",number_format( $tax_value + $totalPrice, 1)) . ' '.$currencySymbol;//number_format( $totalPrice*floatval($tax/100) + $totalPrice , 2) . ' '. $currencySymbol;//number_format($tax_value + $totalPrice, 2, '.', '') .$currencySymbol;
                    }
                            
					$order_detail .= '<div style="float:right;width:328px;border:0px solid;margin-top:15px;border-top:1px solid #dadada;padding-top:10px;">';
                        $order_detail .= '<div style="margin-right:40px;float:left;text-align:right;color:#333333;width:145px;font-size:15px; text-transform: uppercase">' . JText::_('GRAND_TOTAL') .'</div>';	
						$order_detail .= '<div style="text-align:right;width:140px;float:right;font-size:16px;"><strong style="text-align:right;">' . 
                            $check_user_group_value                               
                        . '</strong></div>';	
						$order_detail .= '<div style="margin-right:40px;float:right;text-align:right;color:#333333;width:135px;text-transform:uppercase;font-size:18px;"></div>';	
					$order_detail .= '</div>';		


				$order_detail .= '</td>';		
			$order_detail .= '</tr>';	

			$order_detail .= '</table>';
            
			$user_infor = '<div style="float:left;width:50%;">';
				$user_infor .= '<div style="float:left;width:50%;">';
					$user_infor .= '<p style="color:#666666;text-transform:uppercase;">' . JText::_('BTL_BASIC_INFO') . '</p>';
					$user_infor .= '<p style="color:#333333;">' . $user->name . '</p>';
					$user_infor .= '<p style="color:#333333;">' . $user->contact . '</p>';
					$user_infor .= '<p style="color:#333333;">' . $user->email . '</p>';
					$user_infor .= '<p style="color:#333333;">' . $user->phone . '</p>';
				$user_infor .= '</div>';

			$user_infor .= '</div>';

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
			//echo "<pre>";var_dump($countryTranslationData);

			$query = "SELECT * FROM #__virtuemart_countries WHERE country_3_code='{$user->country}' OR country_3_code='{$user->country_delevery}'";
			$db->setQuery($query);
			$userCountryIds = $db->loadObjectList();
			$user_country_id = $user_delivery_country_id = 0;
			if ( $userCountryIds )
			{
				foreach ( $userCountryIds as $titem )
				{
					if ( $titem->country_3_code == $user->country ) $user_country_id = $titem->virtuemart_country_id;
					if ( $titem->country_3_code == $user->country_delevery ) $user_delivery_country_id = $titem->virtuemart_country_id;
				}
			}
			//echo $user_country_id, " ", $user_delivery_country_id; die;
			//echo "<pre>"; var_dump($userCountryIds); die;

			$user_infor .= '<div style="float:left;width:50%;">';
				$user_infor .= '<p style="color:#666666;text-transform:uppercase;">' . JText::_('BTL_BASIC_INVOICE_ADDRESS') . '</p>';
				$user_infor .= '<p style="color:#333333;">' . $user->street . ', ' . $user->zipcode . ' ' . $user->city . ', ' . $countryTranslationData[$user_country_id]['country_name']. '</p>';

				$user_infor .= '<p style="color:#666666;text-transform:uppercase;margin-top:35px;">' . JText::_('BTL_BASIC_DELEVERY_ADDRESSS') . '</p>';
				$user_infor .= '<p style="color:#333333;">' . $user->street_delevery . ', ' . $user->zipcode_delevery . ' ' . $user->city_delevery  . ', ' . $countryTranslationData[$user_delivery_country_id]['country_name'] . '</p>';
			$user_infor .= '</div>';

			//$user_infor .= '<div style="float:left;width:100%;clear:both;">';
				//$user_infor .= '<p style="color:#666666;text-transform:uppercase;">' . JText::_('Additional info') . '</p>';
				//$user_infor .= '<p style="color:#333333;">' . $user->additonal . '</p>';

			//$user_infor .= '</div>';
			

			//SEND EMAIL
			if ( $emailSettings !== false && property_exists($emailSettings, 'order_email_subject') && $emailSettings->order_email_subject )
			{

				$order_pre_text = 'Your order has been received and is now being processed. Your order details are show below for your reference:';
				$order_email_subject = JText::_($emailSettings->order_email_subject);
				$order_email_sendor_name = JText::_($emailSettings->order_email_sendor_name);
				$order_reply_to_name = JText::_($emailSettings->order_reply_to_name);
				$order_reply_to_email = JText::_($emailSettings->order_reply_to_email);
				$order_other_emails = $emailSettings->order_other_email_receive;
				$order_other_emails = explode(',', $order_other_emails);
				$order_email_template_content = $emailSettings->{$field_template_content};//order_email_template_content;
				$order_email_template_content = str_replace('[order_pre_text]', JText::_($order_pre_text), $order_email_template_content);

				$CONFIRM_ORDER = 'CONFIRM ORDER';
				$order_email_template_content = str_replace('[CONFIRM_ORDER]', JText::_($CONFIRM_ORDER), $order_email_template_content);

				$order_number_title = 'ORDER NUMBER';
				$order_email_template_content = str_replace('[order_number_title]', JText::_($order_number_title), $order_email_template_content);
				$order_email_template_content = str_replace('[order_number]', ' #' . JText::_($orderId), $order_email_template_content);

				
				$order_email_template_content = str_replace('[order_detail]', $order_detail, $order_email_template_content);

				$order_additional_title = 'ADDITIONAL INFORMATION';
				$order_email_template_content = str_replace('[order_additional_title]', JText::_($order_additional_title), $order_email_template_content);
				$order_additional = $session->get('additional_information');
				$order_email_template_content = str_replace('[order_additional]', $order_additional, $order_email_template_content);

				$user_infor_title = 'USER INFO';
				$order_email_template_content = str_replace('[user_infor_title]', JText::_($user_infor_title), $order_email_template_content);
				
				$order_email_template_content = str_replace('[user_infor]', $user_infor, $order_email_template_content);

				$order_email_template_content = str_replace('[base_url]', JURI::base(), $order_email_template_content);
				//echo "<pre>";var_dump($order_email_template_content);die;

				$mailer = JFactory::getMailer();
				//cc to admin
				$query = $db->getQuery(true);
				$query->select('*');
				$query->from( $db->quoteName('#__users') );
				$query->where( $db->quoteName('id') . ' = 289' );
				$db->setQuery($query);
				$adminRow = $db->loadObject();
				if ( is_object($adminRow) && property_exists($adminRow, 'email') ) 
				{
					$mailer->addCC($adminRow->email);
				}
				if(count($order_other_emails) > 0) {
					foreach($order_other_emails as $other_email) {
						$mailer->addCC(trim($other_email));
					}
				}
				$mailer->addReplyTo($emailSettings->order_reply_to_email, $emailSettings->order_email_sendor_name);
                $config = JFactory::getConfig();
                $sender_config = $config->get('smtpuser');
				$email_title = JText::_('ORDER_EMAIL_TITLE');

                //echo $order_email_template_content; die;
				$return = $mailer->sendMail($sender_config, strtoupper($emailSettings->order_email_sendor_name), $user->email, ucwords($email_title), $order_email_template_content, 1);

				if ($return !== true)
				{
					$app->enqueueMessage(JText::_('Send email confirmation unsuccessful!'), 'info');
				}
			}
		}
		catch(Exception $e)
		{
		  //var_dump($e->getMessage());die;
			$msg = JText::_('Place order unsuccessful! Please try again or contact to administrator!');
			$app->enqueueMessage($msg, 'info');
			$red = JRoute::_('index.php?option=com_virtuemart&view=cart');
			$app->redirect($red);	
			return false;
		}
			
		//clear data
		$session->set('additional_information', null);
		$cart->emptyCart();

		//redirect page
		/*$msg = JText::_('Your order have been placed. We have sent you confirmation email with all the order details.<div id="reset_step_email" class="reset_step_email" style="width: 100%; padding: 3px;">
            <button type="submit" id="btn-email" style="width: 195px; font-size: 17px; display: block; padding: 2px 0; text-align: center; margin: 10px auto 0">'.JText::_('JCLOSE').'</button>
        </div>');
		$app->enqueueMessage($msg, JText::_('THANK_YOU'));//, 'info'
		$red = JRoute::_('index.php');*/
		//$app->redirect($red);
		$_SESSION['success'] = true;
		$this->setRedirect(JRoute::_('index.php?option=com_virtuemart&view=virtuemart&status=success', false), '');
	}

}
//pure php no Tag
