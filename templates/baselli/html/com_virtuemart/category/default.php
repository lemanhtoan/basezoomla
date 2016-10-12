<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8811 2015-03-30 23:11:08Z Milbo $
 */

defined ('_JEXEC') or die('Restricted access');
// Get currency symbol
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
$currencySymbol = str_replace('1', '', $currencySymbol);

if (empty($this->products)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}

$i = 1;

//echo count($this->products) . '<hr>';

//echo '<pre>'; print_r($this->products); die;

// Load setting config
$module = JModuleHelper::getModule('mod_setting');
$config_setting = new JRegistry($module->params);

//echo $config_setting->get('productshow_setting_num_slider') . ' / '.$config_setting->get('productshow_setting_num_product') ;
?>
<?php
if($this->showproducts){
?>
		<?php
		if (!empty($this->products)) {
			$products = array();
			$products[0] = $this->products;
		?>
		<div class="container">
			<div class="row">
				<div class="col-md-12 slide-product">
					<div class="main-slide">
					<?php foreach($this->products as $product): ?>  
						<?php if($i <= $config_setting->get('productshow_setting_num_slider')): ?>
						<a href="<?php echo $product->link; // $product->link.$ItemidStr?>" title="<?php echo $product->product_name ?>">
							<h2><?php echo vmText::_($this->category->category_name); ?></h2>
							<p><?php echo $product->product_name ?></p>
                                                        
							<img src="<?php echo $product->images[0]->file_url; ?>" alt="<?php echo $product->product_name ?>" />
						</a>
					<?php endif; ?>
					<?php $i++; endforeach; $i = 1; ?>
					</div>
					<div class="pagination-category-slide"></div>
				</div>
				<div class="list-product">
					<div class="views-content">
						<?php foreach($this->products as $product): ?>
							<?php $user = JFactory::getUser(); ?>
						<?php if($i <= $config_setting->get('productshow_setting_num_product')): ?>
							<?php
								//$product_price = $currencySymbol.number_format($product->allPrices[0]['product_price'], 0);
							?>
						<div class="col-xs-6 col-md-4 product">
							<a href="<?php echo $product->link; ?>" title="<?php echo $product->product_name ?>" class="thumb product-item">
								<img src="<?php echo $product->images_str[0]; ?>" alt="<?php echo $product->product_name ?>" class="lazy-img">
								<img src="<?php echo $product->images_str[1]; ?>" alt="<?php echo $product->product_name ?>" class="hover-img">
							</a>
							<h2 class="product-name">
								<a href="<?php echo $product->link; ?>" title="<?php echo $product->product_name ?>"><?php echo $product->product_name ?></a>
								<?php if(!$user->guest): ?>
								<span class="product-price"><?php echo number_format($product->allPrices[0]['product_price'], 2)  . ' ', $currencySymbol; //$product_price ?></span>
								<?php endif; ?>
							</h2>
						</div> 
						<?php endif; ?>
						<?php $i++; endforeach; $i = 1; ?>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="current_page" value="category_page" id="current_page" />
	<?php
		} elseif (!empty($this->keyword)) {
			echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
		}
	?>

<?php } ?>
<!-- end browse-view -->