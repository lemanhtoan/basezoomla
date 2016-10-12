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
* @version $Id: product_edit.php 8578 2014-11-18 18:24:06Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
AdminUIHelper::startAdminArea($this);

$document = JFactory::getDocument();

vmJsApi::JvalideForm();
$this->editor = JFactory::getEditor();

?>

<?php
    if (count($_POST) == 0) { 
        echo '<script>';
        echo 'if(document.URL.indexOf("#")==-1){
            url = document.URL+"#";
            location = "#";
            location.reload(true);
        }';
        echo '</script>';
        //header( "refresh:10;url='" .JRequest::getURI()."'" ); 
    }
    
?> 

<form method="post" name="adminForm" action="index.php" enctype="multipart/form-data" id="adminForm">

<?php // Loading Templates in Tabs
	$tabarray = array();
	$tabarray['information'] = 'COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_INFO_LBL';
if($this->product->product_parent_id == 0) {
	$tabarray['description'] = 'COM_VIRTUEMART_PRODUCT_FORM_DESCRIPTION';
	$tabarray['dimensions'] = 'COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_DIM_WEIGHT_LBL';
}
	$tabarray['images'] = 'COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_IMAGES_LBL';
if(!empty($this->product_childs)){
	$tabarray['childs'] = 'COM_VIRTUEMART_PRODUCT_CHILD_LIST';
}

//$tabarray['custom'] = 'COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_CUSTOM_TAB';
//$tabarray['emails'] = 'COM_VIRTUEMART_PRODUCT_FORM_EMAILS_TAB';
// $tabarray['customer'] = 'COM_VIRTUEMART_PRODUCT_FORM_CUSTOMER_TAB';


AdminUIHelper::buildTabs ( $this,  $tabarray, $this->product->virtuemart_product_id );
// Loading Templates in Tabs END ?>


<!-- Hidden Fields -->

	<?php echo $this->addStandardHiddenToForm(); ?>
<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>" />
<input type="hidden" name="product_parent_id" value="<?php echo vRequest::getInt('product_parent_id', $this->product->product_parent_id); ?>" />
</form>
<?php 
    AdminUIHelper::endAdminArea(); 
?>
<?php //$document->addScriptDeclaration( 'jQuery(window).load(function(){ jQuery.ajaxSetup({ cache: false }); })'); ?>