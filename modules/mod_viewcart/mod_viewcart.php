<?php
/**
 * @copyright	Copyright © 2014 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	hhp://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

// $doc = JFactory::getDocument();
// $width 			= $params->get("width");

/**
	$db = JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__mod_viewcart where del=0 and module_id=".$module->id);
	$objects = $db->loadAssocList();
*/
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

function getUserCurrency( $user_id = false )
{
	return false;
	//Switzerland - CHE - CH
	//Germany - DEU - DE
	//Austria - AUT - AT
	//France - FRA - FR
	//Italy - ITA - IT
	/*Only Switzercald has swiss franc
	other countries use Euro*/
	$country_currencies = array(
		'47' => array( //virtuemart_currency_id Euro
			'DEU', 'AUT', 'FRA', 'ITA'
		),
		'27' => array( 'CHE' ) //swiss
	);


	$db = JFactory::getDBO();
	$db->setQuery("SELECT v.*,vc.currency_name,vc.currency_symbol FROM #__virtuemart_vendors AS v INNER JOIN #__virtuemart_currencies AS vc ON v.vendor_currency = vc.virtuemart_currency_id");
	$vendor = $db->loadObject();	
	$default_currency_id = $vendor->vendor_currency;

	if ( $user_id === false ) 
	{
		$user = JFactory::getUser();
		$user_id = intval($user->id);
	}
	
	$db->setQuery("SELECT * FROM #__users WHERE block = 0 AND id = ". $user_id );
	$user = $db->loadObject();		

	if ( !$user ) return false;

	$currency_id = $default_currency_id;
	if ( is_object($user) && property_exists($user, 'country') )
	{
		$country_code = $user->country_code;
		foreach ( $country_currencies as $current_tmp_id => $country_codes )
		{
			if ( in_array($country_code, $country_codes) )	
			{
				$currency_id = $current_tmp_id;
				break;
			}
		}
	}

	$currency = getCurrencyRow($currency_id);	
	//echo "<pre>"; var_dump($currency);die;
	return $currency;
}

function getCurrencyRow( $currency_id = 0 )
{
	$db = JFactory::getDBO();
	$db->setQuery("SELECT vc.* FROM #__virtuemart_currencies vc WHERE virtuemart_currency_id=" . intval($currency_id));
	$currency = $db->loadObject();	//currency_code_3
	return $currency;
}
function convertCurrency($price, $from='EUR', $to='CHF')
{
	$currency_data = file_get_contents('http://www.getexchangerates.com/api/latest.json?base=' . $from . '&currencies=' . $from . ',' . $to);
	$items = json_decode($currency_data);
	$rate = false;
	if ( $items !== false )
	{
		if ( is_object($items) && property_exists($items, $to) ) $rate = $items->{$to};
	}
	if ( $rate == false )
	{
		//get from database
		/*
		$db->setQuery("SELECT vc.* FROM #__virtuemart_currencies vc WHERE virtuemart_currency_id=" . intval($currency_id));
		$currency = $db->loadObject();	
		return $currency;
		*/
	}
	return floatval($rate)*$price;
}
//getUserCurrency();
//convertCurrency();

echo "<div class='modViewCartContainer'>";
require JModuleHelper::getLayoutPath('mod_viewcart', $params->get('layout', 'default'));
echo "</div>";