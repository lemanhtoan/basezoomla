<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_languages
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
list($current_lang, $a) = explode('(', JFactory::getLanguage()->getName());
$lang_tag = JFactory::getLanguage()->getTag();

?>
<div id="language">
	<span class="current-language"><?php echo JText::_(strtoupper(trim($current_lang))); ?> <i class="fa fa-angle-down"></i></span>
	<ul class="language">
		<?php foreach($languages as $language):
        ?>
        <?php 
        $lang = list($title, $a) = explode('(', $language->title_native);

        if($lang[0] != $current_lang) :   ?>
			<li><a href="<?php echo $language->link; ?>"><?php
				echo JText::_(strtoupper(trim($title)));
			?></a></li>
        <?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>