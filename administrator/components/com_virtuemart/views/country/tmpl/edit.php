<style type="text/css">
.language_block{
	float:left;
	width: 100%;
	overflow: hidden;
	background: #e6e4e4;
}
.language_block h4 {
	padding:1%;
	margin:0px;
	background: gray;
	clear: both;
	float: left;
	width: 98%;
	display: inline-block;
	color: #fff;
}
.language_block h4::after{
	padding-bottom: 10px;
}
</style>
<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Country
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 8802 2015-03-18 17:12:44Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start','COM_VIRTUEMART_COUNTRY_DETAILS');

$db = JFactory::getDbo();
$lang = JFactory::getLanguage();
//get default language
$query = $db->getQuery(true);
$query->select( '*' );
$query->from( $db->quoteName('#__extensions') );
$query->where( $db->quoteName('name') . ' = "com_languages"' );
$db->setQuery($query);
$language = $db->loadObject();
$params = false;
$defaultLanguage = $lang->getTag();

if ( is_object($language)  && property_exists($language, 'params') )
{
	$params = json_decode($language->params);
}
if ( is_object($language)  && property_exists($language, 'site') )
{
	$defaultLanguage = $language->site;
}

$query = $db->getQuery(true);
$query->select('*');
$query->from( $db->quoteName('#__languages') );
$query->where( $db->quoteName('lang_code' ) . ' <> "'. $defaultLanguage . '"');
$query->order('ordering ASC');
$db->setQuery($query);
$languages = $db->loadObjectList();


$jinput = JFactory::getApplication()->input;
$inputs = $jinput->get->getArray( array() );
$cids = isset($inputs['cid']) ? $inputs['cid'] : array();
$orginalData = array();
$edit_id = false;
$data_languages = array();
if ( $cids && $this->country )
{
	$current_language = JFactory::getLanguage ()->getTag ();
	$lang = &JFactory::getLanguage();
	$edit_id = $cids[0];
	$orginalData[$cids[0]] = array
	(
	    'country_name' => $this->country->country_name,
	);
	
	JLoader::import('helpers.blog',rtrim(str_replace('administrator', '', JPATH_BASE), '/') . '/components/com_blog');
	//echo "<pre>"; var_dump($languages);die;
	foreach ( $languages as $language )
	{
		$lang->setLanguage($language->lang_code);
		//echo $language->lang_code, "<hr/>";
		$data = BlogFrontendHelper::getTranslationForCurrentLanguage('languages','', implode(',', $cids ), $orginalData, 1);	
		// /echo 1, "<hr/>";
		$data_languages[$language->lang_id] = $data;
	}
	$lang->setLanguage($current_language);
	//echo "<pre>"; var_dump(JFactory::getLanguage ()->getTag (), $data_languages); die;
	//echo "<pre>"; var_dump($data); die;
}
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">


<div class="col50">
	<fieldset>
	<legend><?php echo vmText::_('COM_VIRTUEMART_COUNTRY_DETAILS'); ?></legend>
	<table class="admintable">
		<?php
		$lang = JFactory::getLanguage();
		$prefix="COM_VIRTUEMART_COUNTRY_";
		$country_string = $lang->hasKey($prefix.$this->country->country_3_code) ? ' (' . vmText::_($prefix.$this->country->country_3_code) . ')' : ' ';
        ?>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_COUNTRY_REFERENCE_NAME','country_name',$this->country->country_name,'class="required"', '', 50, 50, $country_string); ?>

		<?php echo VmHTML::row('booleanlist','COM_VIRTUEMART_PUBLISHED','published',$this->country->published); ?>
<?php /* TODO not implemented		<tr>
			<td width="110" class="key">
				<label for="title">
					<?php echo vmText::_('COM_VIRTUEMART_WORLDZONE'); ?>:
				</label>
			</td>
			<td>
				<?php echo JHtml::_('Select.genericlist', $this->worldZones, 'virtuemart_worldzone_id', '', 'virtuemart_worldzone_id', 'zone_name', $this->country->virtuemart_worldzone_id); ?>
			</td>
		</tr>*/ ?>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_COUNTRY_3_CODE','country_3_code',$this->country->country_3_code); ?>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_COUNTRY_2_CODE','country_2_code',$this->country->country_2_code); ?>
	</table>

	<table style="border: 1px solid gray; border-collapse:collapse;width:100%;">

		<tr>
			<td><h3>Translate in other language</h3></td>
		</tr>
		<tr>
			<td>
				<?php foreach ( $languages as $language ): ?>
				<div class="language_block">

					<h4><?php echo $language->title, ' / (', $language->lang_code, ')' ?></h4>
					<?php
						$value = ( $edit_id !== false && isset($data_languages[$language->lang_id]) && isset($data_languages[$language->lang_id][$edit_id]) ) ? $data_languages[$language->lang_id][$edit_id]['country_name'] : "";

					?>
					<div class="language_block_content">
						<label><?php echo JText::_('Country Reference Name') ?></label><input type="text" name="country_name_<?php echo $language->lang_id ?>" id="country_name_<?php echo $language->lang_id ?>" value="<?php echo $value ?>" size="50" maxlength="50"/>	
					</div>

				</div>
				<?php endforeach; ?>
			</td>
		</tr>


	</table>
	</fieldset>
</div>

	<input type="hidden" name="virtuemart_country_id" value="<?php echo $this->country->virtuemart_country_id; ?>" />

	<?php echo $this->addStandardHiddenToForm(); ?>
</form>

<?php 
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>