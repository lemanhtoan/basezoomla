<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.framework', true);

$language = JFactory::getLanguage();
$extension = 'custom';
$base_dir = dirname(__FILE__);
$language_tag = $language->getTag(); // loads the current language-tag
$language->load($extension, $base_dir, $language_tag, true);


$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$user            = JFactory::getUser();
$user            = JFactory::getUser($user->id);
$template_path   = $this->baseurl . '/templates/' . $this->template;

$jinput = JFactory::getApplication()->input;
if ( 'com_virtuemart' != $jinput->get('option') ) JHtml::_('jquery.framework');

$doc->addScript($template_path . '/scripts/jquery.touchSwipe.min.js');
$doc->addScript($template_path . '/scripts/jquery.carouFredSel-6.2.1-packed.js');
$doc->addScript($template_path . '/scripts/main.js');
$doc->addScript($template_path . '/scripts/tab_profile.js');
$doc->addScript($template_path . '/scripts/jquery.flexslider-min.js');
$doc->addScript($template_path . '/scripts/imagesloaded.pkgd.min.js');

$doc->addScript($template_path . '/scripts/jquery.lazyload.js');

// Add Stylesheets
$doc->addStyleSheet('http://fonts.googleapis.com/css?family=Lato:300,400');

//change header + footer font
//$doc->addStyleSheet('http://fonts.googleapis.com/css?family=Montserrat:400,700');


$doc->addStyleSheet($template_path . '/styles/bootstrap.min.css');
$doc->addStyleSheet($template_path . '/styles/font-awesome.min.css');
$doc->addStyleSheet($template_path . '/styles/site.css');
$doc->addStyleSheet($template_path . '/styles/tab_profile.css');
$doc->addStyleSheet($template_path . '/styles/flexslider.css');

//internet explore fix
$doc->addStyleSheet($template_path . '/styles/ieFix.css');

// Load setting config
$module = JModuleHelper::getModule('mod_setting');
$config_setting = new JRegistry($module->params);
$lang = JFactory::getLanguage();
?>

<!DOCTYPE html>
<html xml:lang="<?php print $doc->language; ?>" lang="<?php print $doc->language; ?>" dir="<?php print $doc->direction; ?>">
<head>
    <meta http-equiv="refresh" content="600">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3">
    <title>BASELLI</title>
<!--
	<script type="text/javascript" src="<?php echo JURI::root(true) . '/media/jui/js/jquery.min.js' ?>"></script>
	<script type="text/javascript" src="<?php echo JURI::root(true) . '/media/jui/js/jquery-noconflict.js' ?>"></script>
-->

	
	<?php // Template color ?>


	<jdoc:include type="head" />
    <script type="text/javascript"> 
    	var BASE_URL = '<?php echo JUri::base() ?>';
    	var USER_LANG = '<?php echo $user->guest == 1 ? "NULL" : $user->country ?>';
    	var CURRENT_LANG = '<?php echo $lang->getTag() ?>';
    	<?php require_once JPATH_THEMES . '/' . $this->template . '/scripts/translater_jscontent.php'; ?>
    </script>

    <style>
        #main-menu li a {
            color: <?php echo $config_setting->get('header_menu_inactive'); ?>;            
        }
        
        #main-menu li.active a {
            color: <?php echo $config_setting->get('header_menu_active'); ?>;
            border-bottom: 2px solid <?php echo $config_setting->get('header_menu_active'); ?>;
        }
        
        #main-menu li:hover a {
            color: <?php echo $config_setting->get('header_menu_hover'); ?>;
        }
        
        .sub-menu {
            background: <?php echo $config_setting->get('header_menu_sub_bground'); ?>;
        }
        
        #main-menu .sub-menu li a {
            color: <?php echo $config_setting->get('header_menu_sub_color');?>;
        }
            
        #main-menu .sub-menu li:hover a {
            background: <?php echo $config_setting->get('header_menu_sub_color_link_hover');?>;
            color: <?php echo $config_setting->get('header_menu_sub_color_hover');?>;
        }
        @media (max-width: 1259px) {
        	#main-menu .sub-menu li a {
        		color: <?php echo $config_setting->get('mobile_submenu_color');?>;
        	}
        }

        footer p {
            color: <?php echo $config_setting->get('footer_color_text');?>;
        }
    </style>  

    <link href="<?php print JURI::base( true ) ?>/<?php echo $config_setting->get('icon_image'); ?>" rel="shortcut icon" type="image/vnd.microsoft.icon" /> 

</head>
<body background="<?php echo '/zfg513/' . $config_setting->get('bground_image');?>"  style="background-color: <?php echo $config_setting->get('bground_color');?> ;">

	<?php    
		$app = JFactory::getApplication();
        
		$menu = $app->getMenu();
		$lang = JFactory::getLanguage();
		$current_component = JRequest::getVar('option');
        
        //echo "<pre>"; var_dump($current_component); die;
		$component_class= substr($current_component, 4);
		$is_homepage = ($menu->getActive() == $menu->getDefault($lang->getTag())) ? true : false;
	?>
	<div id="page" class="<?php echo $is_homepage === true ? 'home-page' : 'body-'.$component_class.'-component' ?>">
		<header style="background: <?php echo $config_setting->get('header_color');?>" >
			<a href="<?php print $this->baseurl ?>" id="logo">
                <img src="<?php print JURI::base( true ) ?>/<?php echo $config_setting->get('logo_image'); ?>" alt="<?php echo $config_setting->get('logo_title'); ?>" title="<?php echo $config_setting->get('logo_alt'); ?>" width="380" height="100">                
            </a>
			<?php if ($this->countModules('main-menu')) : ?>
				<div class="container main-menu">
					<div class="row"><jdoc:include type="modules" name="main-menu" /></div>
				</div>
			<?php endif; ?>
            
            <jdoc:include type="modules" name="user-menu" style="xhtml" />
                        
			<?php if ($this->countModules('languages')) : ?>
			<div class="topright_container">
				<jdoc:include type="modules" name="languages" style="xhtml" />
			</div>
			<?php endif; ?>
		</header>
		<!-- end header -->
		<section id="content">
			<?php if ($this->countModules('slideshow')) : ?>
				<div class="banner">
					<jdoc:include type="modules" name="slideshow" style="xhtml" />
				</div>				
			<?php else: ?> 
				<!-- Begin Content -->
				<div class="container order_page">
					<div class="row">
						<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
							<jdoc:include type="message" />
						</div>
					</div>
				</div>

				<jdoc:include type="component" />
				<!-- End Content -->
			<?php endif; ?>	

		</section>
		<!-- end content -->
		<footer style="background: <?php echo $config_setting->get('footer_color');?>">
			<?php if ($this->countModules('footer')) : ?>
				<jdoc:include type="modules" name="footer" style="xhtml" />
			<?php endif; ?>			
			<jdoc:include type="modules" name="newsletter" style="xhtml" />
			<?php if ($this->countModules('social')) : ?>
				<p class="social">
					<jdoc:include type="modules" name="social" style="xhtml" />
				</p>
			<?php endif; ?>
			
		</footer>
		<!-- end footer -->
	</div>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
