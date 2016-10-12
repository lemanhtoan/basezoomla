<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
JHtml::_('behavior.caption');

?>

<?php
$item = JTable::getInstance ("content"); //var_dump($item); die;
$item->load ( array ( 'alias' => 'about-us')); // Get Article
$orginalData = array(
    $item->id => array(
        'title' => $item->title,
        'introtext' => $item->introtext,
        'fulltext' => $item->fulltext,
        'articletext' => $item->fulltext,
    )
);
$translate_ids = array($item->id);
JLoader::import('helpers.blog',JPATH_BASE . '/components/com_blog');
$data = BlogFrontendHelper::getTranslationForCurrentLanguage('content','', implode(',', $translate_ids ), array() ); //$orginalData

//echo "<pre>"; var_dump($data); die; //articletext

if ( isset($data[$item->id]) && isset($data[$item->id]['title']) ) $item->title = $data[$item->id]['title'];
if ( isset($data[$item->id]) && isset($data[$item->id]['introtext']) ) $item->introtext = $data[$item->id]['introtext'];
?>

<div class="container">
	<div class="row">
		<div class="content-details">
			<img src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" />
			<div class="col-md-8 col-md-push-2">
	            <?php 
                    $value_datas = array_values($data);
                    $value_datas = $value_datas[0];
                    if (strlen($value_datas['articletext']) > 0 ) {
    	               echo  $value_datas['articletext']; 
    	            } else echo $item->introtext;                   
	            ?>
            </div>
		</div>
	</div>
</div>
 