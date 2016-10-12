<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams('com_media');
jimport('joomla.html.html.bootstrap');
$app = JFactory::getApplication();

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
$this->form->reset( true ); // to reset the form xml loaded by the view
$this->form->loadFile( dirname(__FILE__) . DS . "forms" . DS . "contact.xml"); // to load in our own version of login.xml
$user_name = $user_address = $user_phone = $user_email = '';

$user_infomation = $this->user_infomation;
if(isset($user_infomation)) {
	$user_name = $user_infomation->name;
	$user_address = $user_infomation->address;
	$user_phone = $user_infomation->phone;
	$user_email = $user_infomation->email;
}
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
         jQuery("#contact-submit").click(function(e){
            
            //jform_contact_name
            if(jQuery("#jform_contact_name").val() == "") {
                jQuery("#jform_contact_name").addClass('red'); return false;
            }else{jQuery("#jform_contact_name").removeClass('red');}
            
            //jform_contact_address
            if(jQuery("#jform_contact_address").val() == "") {
                jQuery("#jform_contact_address").addClass('red'); return false;
            }else{jQuery("#jform_contact_address").removeClass('red');}
            
            //jform_contact_telephone
            if(jQuery("#jform_contact_telephone").val() == "") {
                jQuery("#jform_contact_telephone").addClass('red'); return false;
            }else{jQuery("#jform_contact_telephone").removeClass('red');}
            
            //jform_contact_email
            if(jQuery("#jform_contact_email").val() == "") {
                jQuery("#jform_contact_email").addClass('red'); return false;
            }else{jQuery("#jform_contact_email").removeClass('red');}
            
            var emailRegExp = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([a-zA-Z]){2,4})$/;
	        if(!emailRegExp.test(jQuery("#jform_contact_email").val())){	
                jQuery("#jform_contact_email").addClass('red'); return false;
            }else{jQuery("#jform_contact_email").removeClass('red');}
            
            //jform_contact_message
            if(jQuery("#jform_contact_message").val() == "") {
                jQuery("#jform_contact_message").addClass('red'); return false;
            }else{jQuery("#jform_contact_message").removeClass('red');}
            
            //jform_contact_name
            if(jQuery("#jform_contact_captcha").val() == "") {
                jQuery("#jform_contact_captcha").addClass('red'); return false;
            }else{jQuery("#jform_contact_captcha").removeClass('red');}
            
            e.preventDefault();

            jQuery.ajax({
                type: 'POST',
                url: '../components/com_ajax/captcha.php',
                data: {'captcha_value': jQuery('#jform_contact_captcha').val(), 'encrypt_value': jQuery('#hdn_captcha').val()},
                success: function(result) {
                    if(result == 1) {
                        jQuery("#jform_contact_captcha").removeClass('red');
                        var act = jQuery('#act').val();
                        // If is claim
                        if(act == 'claim') {
                            var has_claim = jQuery('#has_claim').val();
                            if(parseInt(has_claim) > 0) {
                                jQuery('#contact-form').submit();
                                return false;
                            } else if(jQuery('#act').val() == 'claim') {
                                alert(MESSAGE_BEFORE_SUBMIT);
                                return false;
                            }
                        }
                        // If is contact
                        if(act == 'contact') {
                            jQuery('#contact-form').submit();
                        }
                    } else {
                        jQuery("#jform_contact_captcha").addClass('red');
                    }
                }
            });
            
         });
        

	});
</script>
<div class="container">
	<div class="row">
		<div class="content-details">
        	<script type="text/javascript" src="<?php print JURI::base() . 'templates/' . $app->getTemplate() . "/scripts/contact.js" ?>"></script>
			<?php if($this->maps->show_maps == 1) : ?>
                <div class="map">
                    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                    <script type="text/javascript">
                        var latLng = new google.maps.LatLng(<?php echo $this->maps->con_lat; ?>, <?php echo $this->maps->con_lng; ?>);
                        var _icon = '<?php echo JURI::root(); ?>images/gmap_icon.png';
                        var geocoder = new google.maps.Geocoder();
                        // Onload handler to fire off the app.
                        google.maps.event.addDomListener(window, 'load', initialize);
                    </script>
                    <style> #mapCanvas {width: 100%; height: 400px;}</style>
                    <div id="mapCanvas"></div>
                </div>
             <?php endif; ?>
			<!-- end map -->
			<div id="s-msg" style="margin-bottom: 30px"></div>
			<div class="col-md-4 information alpha">
				<h2><?php echo JText::_('CONTACT_INFORMATIONS');?></h2>
                <div id="c-i-1">
                    <p><?php echo $this->contact->address; ?></p>
                    <p><?php echo $this->contact->postcode; ?> <?php echo $this->contact->suburb; ?></p>
                    <p><?php echo $this->contact->state; ?></p>
                    <p><?php echo $this->contact->country; ?></p>
                </div>
                <div id="c-i-2">
                    <p><?php echo JText::_('CONTACT_PHONE') ?>: <?php echo $this->contact->telephone; ?></p>
                    <p><?php echo JText::_('CONTACT_FAX') ?>: <?php echo JText::_($this->contact->fax); ?></p>
                    <p><a href="mailto:<?php echo $this->contact->email_to; ?>"><?php echo $this->contact->email_to; ?></a></p>
                </div>
			</div>
			<div class="col-md-8 contact-form omega<?php echo $this->pageclass_sfx?>">
				<h2><?php echo JText::_('COM_CONTACT_FORM_LABEL'); ?></h2>
                <div class="form-row contact-tab">
                	<ul>
                      <li id="contact" class="active"><?php echo JText::_('CONTACT');?></li>
                      <?php
                      	$user = JFactory::getUser();
                      	if(!$user->guest):
                      ?>
                      <li id="claim"><?php echo JText::_('CLAIM');?></li>
                  	<?php endif ?>
                    </ul>
                </div>

                <?php //if(isset($_SESSION['contact-post'])) $value = ($_SESSION['contact-post']);?>
				<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate form-horizontal">
					<div class="form-row">
                		<input type="text" name="jform[contact_name]" id="jform_contact_name" value="<?php print isset($user_name) ? $user_name : '' ?>" placeholder="<?php echo JText::_('COM_CONTACT_CONTACT_EMAIL_NAME_LABEL');?>" >					
                	</div>
                	<div class="form-row">
                		<input type="text" name="jform[contact_address]" id="jform_contact_address" value="<?php print isset($user_address) ? $user_address : '' ?>" placeholder="<?php echo JText::_('COM_CONTACT_CONTACT_ADDRESS_NAME_LABEL');?>" >					
                	</div>
                	<div class="form-row">
                		<input type="text" name="jform[contact_telephone]" id="jform_contact_telephone" value="<?php print isset($user_phone) ? $user_phone : '' ?>"  placeholder="<?php echo JText::_('COM_CONTACT_CONTACT_TELEPHONE_NAME_LABEL');?>" >					
                	</div>
                	<div class="form-row">
                		<input type="text" name="jform[contact_email]" class="validate-email required" id="jform_contact_email" value="<?php print isset($user_email) ? $user_email : '' ?>" placeholder="<?php echo JText::_('COM_CONTACT_EMAIL_LABEL'); ?>" >					
                	</div>
                	<div class="form-row" id="message-row">
                		<div class="controls">
                			<textarea name="jform[contact_message]" cols="50" rows="10" placeholder="<?php echo JText::_('COM_CONTACT_CONTACT_ENTER_MESSAGE_LABEL');?>"></textarea>
                		</div>
                	</div>
					<?php if(!$user->guest): ?>
						<?php 
							$is_active_claim = JRequest::getVar('task'); 
							if(isset($is_active_claim) && $is_active_claim == 'claim') {
								$claim_flag = true;
							}
						?>
                    <div id="form-claim" <?php echo $claim_flag === true ? 'style="display: block"' : '' ?>>
                    <?php
                    	if($claim_flag === true) {
                    		print $this->loadTemplate("claim");
                    	}
                    ?>
                    </div>
                    <textarea style="display: none" name="hdn_claim" id="hdn-claim"><?php print htmlspecialchars($this->loadTemplate("claim")); ?></textarea>
                	<?php endif; ?>
					<div class="form-row captcha">
						<p><?php echo JText::_('COM_CONTACT_CAPTCHA_LABEL'); ?></p>
						<?php
							$number_1 = rand(1, 100);
							$number_2 = rand(1, 100);
							$total = $number_1 + $number_2;
							$global_config = new JConfig();
							$total_encrypt = md5($global_config->secret.$total);
						?>
						<label style="padding-top: 5px"><?php echo "$number_1 + $number_2 =" ?></label>
						<input type="text" name="jform[contact_captcha]" id="jform_contact_captcha" value="" class="captcha-input required">	
						<input type="hidden" name="jform[captcha]" id="hdn_captcha" value="<?php echo $total_encrypt ?>" />
					</div>
					<div class="form-row contact-action action">
						<input id="contact-submit" type="submit" class="button submit " value="<?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?>">
						<input id="contact-reset" style="width: auto; padding: 0 12px; min-width: 100px" type="reset" class="button reset" value="<?php echo JText::_('COM_CONTACT_CONTACT_RESET'); ?>">
					</div>
					<div class="form-actions">
                    	<textarea name="rs_claim" id="rs_claim"><div class="form-row"><?php echo JText::_('Claimed');?> <span id="claimed-products"></span> <?php echo JText::_('products');?></div></textarea>
                    	<textarea name="hdn_claim_data" id="hdn-claim-data" style="display:none"></textarea>
                    	<input type="hidden" name="act" id="act" value="<?php echo $claim_flag === true ? 'claim' : 'contact' ?>" />
						<input type="hidden" name="option" value="com_contact" />
						<input type="hidden" name="task" value="contact.basesubmit" />
						<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
						<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
                        <input type="hidden" id="has_claim" value="0" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php 
	$status = JRequest::getVar('status');
	$is_contact = JRequest::getVar('contact');
	if(isset($_SESSION['sent']) && $_SESSION['sent'] === true && isset($status) && $status == 'success'):
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
         jQuery('#close, #btn-email').click(function() {
         	jQuery('#show-popup, #hide-popup, #popup-wrapper').remove();
         });
	});
</script>
<div id="hide-popup"></div>
<div id="popup-wrapper">
	<div id="show-popup" class="modalDialog" style="margin-top: 20px;">
		<a href="#close" title="Close" class="modalCloseImg simplemodal-close" id="close"></a>
		<form action="" method="post">
			<h3><?php echo JText::_('THANK_YOU') ?></h3>
	        <p><?php echo $is_contact == 1 ? JText::_('COM_CONTACT_THANKS') : JText::_('COM_CONTACT_EMAIL_THANKS'); ?></p>
	        <div id="reset_step_email" class="reset_step_email" style="width: 100%; padding: 3px;">
	            <button type="submit" id="btn-email" style="width: 195px; font-size: 17px; display: block; padding: 2px 0; text-align: center; margin: 10px auto 0"><?php echo JText::_('JCLOSE'); ?></button>
	        </div>
		</form>
	</div>
</div>
<?php unset($_SESSION['sent']);endif; ?>