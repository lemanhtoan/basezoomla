<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>


<?php
$orginalData = array();
$list_ids = array();

//echo "<pre>"; var_dump($list); die;
foreach($list as $item) 
{
    $orginalData[$item->id] = array
    (
        'title' => $item->title,   
    );
    array_push($list_ids, $item->id);
}

JLoader::import('helpers.blog',JPATH_BASE . '/components/com_blog');
$data = BlogFrontendHelper::getTranslationForCurrentLanguage('menu','com_menus', implode(',', $list_ids ), $orginalData); //$orginalData
?>


<div class="col-md-12">
	<ul id="main-menu">
		<li class="parent" id="language-list"></li>
		<?php foreach ($list as $i => &$item):
			$categories = false;
			$collection_id = $item->params->get('menu-anchor_css');
			if (is_numeric($collection_id)):
				$current_component = JRequest::getVar('option');
				if($current_component != 'com_product')
					require JPATH_SITE . '/components/com_product/helpers/product.php';
				$categories = ProductFrontendHelper::getVirtueMartCategories();
				//var_dump($categories);die;
			endif;
            
            if ( isset($data[$item->id]) && isset($data[$item->id]['title']) ) $item->title = $data[$item->id]['title'];
            
		?>
			<li class="<?php print $categories ? 'parent collect-menu' : ''; ?> <?php print (in_array($item->id, $path)) ? 'active' : ''; ?> menu-item">
		
                <a href="<?php print JFilterOutput::ampReplace(htmlspecialchars($item->alias)); ?>.html"><?php print $item->title; ?></a>
                
				<?php if ($categories): ?>
					<ul class="sub-menu">
						<?php foreach ($categories AS $el): ?>
							<li><a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$el->virtuemart_category_id) ?>" title="<?php print $el->category_name; ?>"><?php print $el->category_name; ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif;?>
			</li>
		<?php endforeach; ?>
		<?php $user = JFactory::getUser(); if(!$user->guest): ?>
			<li class="parent" style="display: none" id="account-menu">
				<a href="javascript:;" title="<?php echo JText::_('MY_ACCOUNT') ?>"><?php echo JText::_('MY_ACCOUNT') ?></a>
				<ul class="sub-menu">
					<li><a href="<?php echo JRoute::_('index.php?option=com_users&view=profile&layout=edit'); ?>" title="<?php echo JText::_('EDIT_ACCOUNT'); ?>"><?php echo JText::_('EDIT_ACCOUNT'); ?></a></li>
					<li>
						<?php
							$userToken = JSession::getFormToken();
							$uri = JFactory::getURI();
							$absolute_url = $uri->toString();
							$logout_link = JRoute::_('index.php?option=com_users&task=user.logout&' . $userToken . '=1&return='.base64_encode($absolute_url));
    						echo '<a title="'.JText::_('JLOGOUT').'" href="'.$logout_link.'">'.JText::_('JLOGOUT').'</a>';
						?>
					</li>
				</ul>
				<i class="fa fa-angle-right"></i>
			</li>
		<?php endif; ?>
	</ul>
</div>
<button id="toggle-menu">
	<span></span>
	<span></span>
	<span></span>
</button>