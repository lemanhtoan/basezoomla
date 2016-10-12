<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_footer
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$module = JModuleHelper::getModule('mod_social');
$social = new JRegistry($module->params);
//echo "<pre>"; var_dump($social);die;
?>
<div class="social">
    <a href="<?php echo $social->get('link_facebook'); ?>" target="_blank">
        <img src="<?php print JURI::base( true ) ?>/<?php echo $social->get('image_facebook'); ?>" alt="facebook"> 
        <img class="hover" src="<?php print JURI::base( true ) ?>/<?php echo $social->get('hover_image_facebook'); ?>" alt="facebook">
    </a>
    
    <a href="<?php echo $social->get('link_twitter'); ?>" target="_blank">
        <img src="<?php print JURI::base( true ) ?>/<?php echo $social->get('image_twitter');?>" alt="twitter">
        <img class="hover" src="<?php print JURI::base( true ) ?>/<?php echo $social->get('hover_image_twitter');?>" alt="twitter">
    </a>
</div>
