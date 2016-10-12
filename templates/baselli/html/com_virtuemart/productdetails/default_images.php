<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 8657 2015-01-19 19:16:02Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if(VmConfig::get('usefancy',1)){
	vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack', false);
	vmJsApi::css('jquery.fancybox-1.3.4');
	$document = JFactory::getDocument ();
	$imageJS = '
	jQuery(document).ready(function() {
		Virtuemart.updateImageEventListeners()
	});
	Virtuemart.updateImageEventListeners = function() {
		jQuery("a[rel=vm-additional-images]").fancybox({
			"titlePosition" 	: "inside",
			"transitionIn"	:	"elastic",
			"transitionOut"	:	"elastic"
		});
		jQuery(".additional-images a.product-image.image-0").removeAttr("rel");
		jQuery(".additional-images img.product-image").click(function() {
			jQuery(".additional-images a.product-image").attr("rel","vm-additional-images" );
			jQuery(this).parent().children("a.product-image").removeAttr("rel");
			var src = jQuery(this).parent().children("a.product-image").attr("href");
			jQuery(".main-image img").attr("src",src);
			jQuery(".main-image img").attr("alt",this.alt );
			jQuery(".main-image a").attr("href",src );
			jQuery(".main-image a").attr("title",this.alt );
			jQuery(".main-image .vm-img-desc").html(this.alt);
		}); 
	}
	';
} else {
	vmJsApi::addJScript( 'facebox',false );
	vmJsApi::css( 'facebox' );
	$document = JFactory::getDocument ();
	$imageJS = '
	jQuery(document).ready(function() {
		Virtuemart.updateImageEventListeners()
	});
	Virtuemart.updateImageEventListeners = function() {
		jQuery("a[rel=vm-additional-images]").facebox();
		var imgtitle = jQuery("span.vm-img-desc").text();
		jQuery("#facebox span").html(imgtitle);
	}
	';
}
$app = JFactory::getApplication();
vmJsApi::addJScript('imagepopup',$imageJS);
if($this->product->product_parent_id == 0 && is_array($this->product->child)) {
	$product = $this->product->child[0];
	$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
	$app->redirect($url);
}
if (!empty($this->product->images)):
	$number_image = count($this->product->images);
	?>
	<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
		<div class="flexslider">
			<ul class="slides">
				<?php foreach($this->product->images as $image): ?>
				<li>
					<img src="<?php echo $image->file_url ?>" alt="<?php echo $this->product->product_name ?>">
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
	<h2 class="product-name"><?php echo $this->product->product_name ?></h2>
	<div class="fl-control-nav">
		<?php for($i = 0; $i < $number_image; $i++): ?>
		<a class="fl-item-nav"><?php echo $i ?></a>
		<?php endfor; ?>
	</div>
	<?php endif; ?>
