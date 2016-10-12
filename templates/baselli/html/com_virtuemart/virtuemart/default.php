<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage
* @author
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 8847 2015-05-06 12:22:37Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>

<?php # Vendor Store Description
//echo $this->add_product_link;
if (!empty($this->vendor->vendor_store_desc) and VmConfig::get('show_store_desc', 1)) { ?>
<div class="vendor-store-desc">
	<?php echo $this->vendor->vendor_store_desc; ?>
</div>
<?php } ?>

<?php
# load categories from front_categories if exist
if ($this->categories and VmConfig::get('show_categories', 1)) echo $this->renderVmSubLayout('categories',array('categories'=>$this->categories));

# Show template for : topten,Featured, Latest Products if selected in config BE
if (!empty($this->products) ) {
	$products_per_row = VmConfig::get ( 'homepage_products_per_row', 3 ) ;
	echo $this->renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$products_per_row,'showRating'=>$this->showRating)); //$this->loadTemplate('products');
}

?>

<?php 
	$status = JRequest::getVar('status');
	$is_contact = JRequest::getVar('contact');
	if(isset($_SESSION['success']) && $_SESSION['success'] === true && isset($status) && $status == 'success'):
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
         jQuery('#close, #btn-email').click(function() {
         	window.location.href = BASE_URL;
         });
	});
</script>
<div id="hide-popup"></div>
<div id="popup-wrapper">
	<div id="show-popup" class="modalDialog" style="margin-top: 20px;">
		<a href="#close" title="Close" class="modalCloseImg simplemodal-close" id="close"></a>
		<form action="" method="post">
			<h3><?php echo JText::_('THANK_YOU') ?></h3>
	        <p style="text-align: center"><?php echo JText::_('ORDER_SUCCESS_THANKS'); ?></p>
	        <div id="reset_step_email" class="reset_step_email" style="width: 100%; padding: 3px;">
	            <button type="submit" id="btn-email" style="width: 195px; font-size: 17px; display: block; padding: 2px 0; text-align: center; margin: 10px auto 0"><?php echo JText::_('JCLOSE'); ?></button>
	        </div>
		</form>
	</div>
</div>
<?php unset($_SESSION['success']);endif; ?>