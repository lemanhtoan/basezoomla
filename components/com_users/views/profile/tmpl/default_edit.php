<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
//JHtml::_('formbehavior.chosen', 'select');

// Load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from( $db->quoteName('#__virtuemart_countries') );
$query->where( $db->quoteName('published' ) . ' = 1');
$query->order('ordering ASC');
$db->setQuery($query);
$countries = $db->loadObjectList();

$orginalData = array();
JLoader::import('helpers.blog',rtrim(str_replace('administrator', '', JPATH_BASE), '/') . '/components/com_blog');
$cids = array();
$tmp = array();
foreach ( $countries as $country )
{
    $orginalData[$country->virtuemart_country_id] = array(
        'country_name' => $country->country_name
    );
    array_push($cids, $country->virtuemart_country_id);
    array_push($tmp, array(
        'country_name' => $country->country_name, 
        'virtuemart_country_id' => $country->virtuemart_country_id, 
        'country_3_code' => $country->country_3_code, 
    ));
}
$countries = $tmp;
$countryTranslationData = BlogFrontendHelper::getTranslationForCurrentLanguage('languages','', implode(',', $cids ), $orginalData); 
?>
<style>

</style>
<div class="container">
	<div class="row">
		<div class="profile-edit<?php echo $this->pageclass_sfx?>">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<div class="page-header">
					<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
				</div>
			<?php endif; ?>

			<script type="text/javascript">
				Joomla.twoFactorMethodChange = function(e)
				{
					var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

					jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function(i, el) {
						if (el.id != selectedPane)
						{
							jQuery('#' + el.id).hide(0);
						}
						else
						{
							jQuery('#' + el.id).show(0);
						}
					});
				}
			</script>

			<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post"  enctype="multipart/form-data"><!--class="form-validate form-horizontal"-->

			<h2><?php echo JText::_('COM_USERS_PROFILE_DEFAULT_LABEL'); ?></h2>
            <!--tab content-->            
        	<ul class="tabs">
        	    <li><a href="#profile"><?php echo JText::_('PROFILE'); ?></a></li>
        	    <li><a href="#password"><?php echo JText::_('PASSWORD'); ?></a></li>
        	</ul>
        	<div class="tabcontents">
            
                <!--error if exist -->
                <div id="profile-error" class="profile-error"></div>
                
        	    <div id="profile">
        	        <div class="btl-note"><span><?php echo JText::_("MY_AVATAR"); ?></span></div>
    				<div class="btl-field">
    					<div style="float: left; width: 15;">
    						<?php 
    							if (strlen($this->form->getValue('image')) > 0 ) : 
    						 ?>
    							<div class="btnuser-image">
    								<img id="img-profile" src="<?php echo $this->form->getValue('image'); ?>" width="100" height="100" style="border-radius:50%">
    							</div>
                            <?php else : ?>
                            	<div class="btnuser-image">
    								<img id="img-profile" src="images/user/noavatar.png" width="100" height="100" style="border-radius:50%">
    							</div>
    						<?php endif; ?>
    					</div>
    					<div style="float: right; width: 85%; margin-top: 15px;">
    						<div style="float: left; ">
    							<label class="custom-upload">
                                    <input type="file" name="jform[image]" id="jform_image" onchange="readURL(this);"  accept="image/*" /><?php echo JText::_('CHANGE_AVATAR');?></label>
    							<br /><input type="button" value="<?php echo JText::_('DELETE');?>" class="add" id="delete" />
    						</div>
    					</div>
    				</div>	
    
    				<div class="clear"></div>
    
    				<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_INFO"); ?></span></div>	
    				
    				<!--name-->
    				<div class="btl-field">
                        <span id="val_name" class="error"><?php echo JText::_('REQUIRED_PROFILE_NAME');?></span>
    					<div class="btl-input">
    						<input id="btl-input-name" type="text" value="<?php echo $this->form->getValue('name');?>" placeholder="<?php echo JText::_('BUSINESS_NAME');?>" name="jform[name]" />
    					</div>
                        
    				</div>	
    				<div class="clear"></div>
    
    				<!--contact-->
    				<div class="btl-field">
    					<div class="btl-input">
    						<input id="btl-input-contact" value="<?php echo $this->form->getValue('contact');?>" type="text"  placeholder="<?php echo JText::_('CONTACT_NOT_REQUIRED');?>" name="jform[contact]" />
    					</div>
    				</div>			
    				<div class="clear"></div>
                    
    				<!--email-->
    				<div class="btl-field">
    					<div class="btl-input">
    						<input id="btl-input-email1" value="<?php echo $this->form->getValue('email1');?>" type="text" placeholder="<?php echo JText::_('EMAIL');?>" name="jform[email1]" readonly />
    					</div>
    				</div>
    				<div class="clear"></div>
                    
    				<!--phone-->
    				<div class="btl-field">
                        <span id="val_phone" class="error"><?php echo JText::_('REQUIRED_PROFILE_PHONE');?></span>
    					<div class="btl-input">
    						<input id="btl-input-phone" value="<?php echo $this->form->getValue('phone');?>" type="text" placeholder="<?php echo JText::_('PHONE_NUMBER');?>" name="jform[phone]" />
    					</div>
    				</div>
    				<div class="clear"></div>
    
                    <?php if(strlen($this->form->getValue('phone1')) > 0 ) : ?>
                        <!--phone other exist-->
        				<div class="btl-field">
        					<div class="btl-input">
        						<input id="btl-input-phone" value="<?php echo $this->form->getValue('phone1');?>" type="text" placeholder="<?php echo JText::_('PHONE_NUMBER');?>" name="jform[phone1]" />
        					</div>
        				</div>
        				<div class="clear"></div>
                    <?php else : ?>
                        <!--add other phone-->
        				<div class="btl-field">
        					<div class="btl-input">
        						<p id="addPhone"></p>
        					</div>
        					<input type="button" value="<?php echo JText::_('ADD_PHONE');?>" class="add" id="add" />
        				</div>
                    <?php endif; ?>    				
    				
    
    				<!--INVOICE ADDRESS-->
    				<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_INVOICE_ADDRESS"); ?></span></div>
    				
    				<!--street-->
    				<div class="btl-field">
                        <span id="val_street" class="error"><?php echo JText::_('REQUIRED_PROFILE_STREET');?></span>
    					<div class="btl-input">
    						<input id="btl-input-street" value="<?php echo $this->form->getValue('street');?>" type="text" placeholder="<?php echo JText::_('STREET');?>" name="jform[street]" />
    					</div>
    				</div>
    				<div class="clear"></div>
    
    				<!--city-->
    				<div class="btl-field">
                        <span id="val_city" class="error"><?php echo JText::_('REQUIRED_PROFILE_CITY');?></span>
    					<div class="btl-input">
    						<input id="btl-input-city" value="<?php echo $this->form->getValue('city');?>"  type="text" placeholder="<?php echo JText::_('CITY');?>" name="jform[city]" />
    					</div>
    				</div>
    				<div class="clear"></div>
    
    				<!--zipcode + country-->
    				<div class="btl-field">
    					<div class="btl-input">
    						<div style="width:20%; float:left">
                                <span id="val_zipcode" class="error"><?php echo JText::_('REQUIRED_PROFILE_ZIPCODE');?></span>
                                <span id="val_zipcode_number" class="error"><?php echo JText::_('REQUIRED_PROFILE_ZIPCODE_NUMBER');?></span>
                                
    							<input id="btl-input-zipcode" value="<?php echo $this->form->getValue('zipcode');?>" type="text" placeholder="<?php echo JText::_('ZIPCODE');?>" name="jform[zipcode]" />
    						</div>
    						<div style="width:80%; float:right">
                                <select id="btl-input-country" name="jform[country]">
                                    <?php foreach ( $countries as $country ): ?>
                                        <?php $country_id = $country['virtuemart_country_id']; ?>
                                        <?php $country_item = isset( $country[$country_id] ) ? $country[$country_id] : array(); ?>
                                        <option value="<?php echo $country['country_3_code'] ?>" <?php if ( $this->form->getValue('country') == $country['country_3_code'] ): ?>selected="selected"<?php endif; ?>>
                                            <?php echo ( isset($countryTranslationData[$country_id]) && isset($countryTranslationData[$country_id]['country_name']) ) ? trim($countryTranslationData[$country_id]['country_name']) :  trim($country['country_name'])  ?>
                                        </option>
                                    <?php endforeach; ?>
								</select>
    						</div>
    					</div>
    				</div>
    				<div class="clear"></div>
    
    				<!--DELEVERY ADDRESS-->
    				<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_DELEVERY_ADDRESS"); ?></span></div>
    				<div class="btl-field">		
    					<div class="btl-input" id="btl-input-remember">
    						<label class="myCheckbox">
    						    <input id="btl-checkbox-copy-delevery"  type="checkbox" name="btl-checkbox-copy-delevery" value="yes" />
    						    <span></span>
    						</label>
    						<label id="remember-lbl" for="remember-lbl"><?php echo JText::_('COPY_DELEVERY'); ?></label> 
    					</div>	
    				</div>
    				<!--street-->
    				<div class="btl-field">
                        <span id="val_street_delevery" class="error"><?php echo JText::_('REQUIRED_PROFILE_STREET_DELIVERY');?></span>
    					<div class="btl-input">
    						<input id="btl-input-street-delevery" value="<?php echo $this->form->getValue('street_delevery');?>" type="text" placeholder="<?php echo JText::_('STREET');?>" name="jform[street_delevery]" />
    					</div>
    				</div>
    				<div class="clear"></div>
    
    				<!--city-->
    				<div class="btl-field">
                        <span id="val_city_delevery" class="error"><?php echo JText::_('REQUIRED_PROFILE_CITY_DELIVERY');?></span>
    					<div class="btl-input">
    						<input id="btl-input-city-delevery" value="<?php echo $this->form->getValue('city_delevery');?>" type="text" placeholder="<?php echo JText::_('CITY');?>" name="jform[city_delevery]" />
    					</div>
    				</div>
    				<div class="clear"></div>

    				<!--zipcode + country-->
    				<div class="btl-field">
    					<div class="btl-input">
    						<div style="width:20%; float:left">
                                <span id="val_zipcode_delevery" class="error"><?php echo JText::_('REQUIRED_PROFILE_ZIPCODE_DELIVERY');?></span>
                                <span id="val_zipcode_number_delevery" class="error"><?php echo JText::_('REQUIRED_PROFILE_ZIPCODE_NUMBER_DELEVERY');?></span>
                                
    							<input id="btl-input-zipcode-delevery" value="<?php echo $this->form->getValue('zipcode_delevery');?>" type="text" placeholder="<?php echo JText::_('ZIPCODE');?>" name="jform[zipcode_delevery]" />
    						</div>
                            
    						<select id="btl-input-country_delevery" name="jform[country_delevery]">
                                <?php foreach ( $countries as $country ): ?>
                                    <?php $country_id = $country['virtuemart_country_id']; ?>
                                    <?php $country_item = isset( $country[$country_id] ) ? $country[$country_id] : array(); ?>
                                    <option value="<?php echo $country['country_3_code'] ?>" <?php if ( $this->form->getValue('country_delevery') == $country['country_3_code'] ): ?>selected="selected"<?php endif; ?>>
                                        <?php echo ( isset($countryTranslationData[$country_id]) && isset($countryTranslationData[$country_id]['country_name']) ) ? trim($countryTranslationData[$country_id]['country_name']) :  trim($country['country_name'])  ?>
                                    </option>
                                <?php endforeach; ?>
							</select>																
    					</div>
    				</div>
    				<div class="clear"></div>
    
    				<!--ADDITIONAL INFO-->
    				<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_ADDITIONAL_INFO"); ?></span></div>
    				<div class="btl-field">
    					<div class="btl-input">
    						<input id="btl-input-additonal" value="<?php echo $this->form->getValue('additonal');?>" type="text" placeholder="<?php echo JText::_('ADDITIONAL_INFO');?>" name="jform[additonal]" />
    					</div>
    				</div>
    				<div class="clear"></div>
    
    
    				<div class="btl-field">
    					<div class="btl-input">
    						<input type="text" name="jform[add_more_description]" 
    							id="jform_add_more_description" placeholder="<?php echo JText::_('OTHER_DESCROPTION');?>" value="<?php echo $this->form->getValue('add_more_description');?>"	
    						/>
    					</div>
    				</div>
    				<div class="clear"></div>
        	    </div>
        	    <div id="password">        	        
    				<!--PASSWORD-->
    				<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_PASSWORD"); ?></span></div>
                    <span id="val_password_same" class="error"><?php echo JText::_('REQUIRED_PROFILE_CONFIRM_PASSWORD');?></span>
    				<div class="btl-field">
    					<div class="btl-input">
    						<input id="btl-input-password1"  type="password" name="jform[password1]" placeholder="<?php echo JText::_('PASSWORD');?>" />
    					</div>
    				</div>		
    				<div class="clear"></div>					
    				<div class="btl-field">
    					<div class="btl-input">
    						<input id="btl-input-password2"  type="password" name="jform[password2]" placeholder="<?php echo JText::_('REPEATE_PASSWORD');?>" />
    					</div>
    				</div>
    				<div class="clear"></div> 
        	    </div>
        	</div>
            <!--end tab content-->  
            
            <!--control-->
            <div class="control-group">
				<div class="controls" id="profile-button">
					<button type="submit" class="btn btl-buttonsubmit validate"><span><?php echo JText::_('UPDATE'); ?></span></button>
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="profile.save" />
				</div>
			</div>
            <!--end control-->

			<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	</div>
</div>

<?php
    $status = JRequest::getVar('edit');
    if(isset($_SESSION['edit_profile']) && $_SESSION['edit_profile'] === true && isset($status) && $status == 'done'):
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
         jQuery('#close, #btn-email').click(function() {
         	jQuery('#show-popup, #hide-popup').remove();
            jQuery('#popup-wrapper').css('position', 'static');
         });
	});
</script>

<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    jQuery('#img-profile').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
    
<div id="hide-popup"></div>
<div id="popup-wrapper">
    <div id="show-popup" class="modalDialog" style="margin-top: 20px;">
    	<a href="#close" title="Close" class="modalCloseImg simplemodal-close" id="close"></a>
    	<form action="" method="post">
    		<h3><?php echo JText::_('COM_USERS_THANK'); ?></h3>
            <p style="text-align: center;"><?php echo JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'); ?></p>
            <div id="reset_step_email" class="reset_step_email" style="width: 100%; padding: 3px;">
                <button type="submit" id="btn-email" style="width: 195px; font-size: 17px; display: block; padding: 2px 0; text-align: center; margin: 10px auto 0"><?php echo JText::_('JCLOSE'); ?></button>
            </div>
    	</form>
    </div>
</div>
<?php unset($_SESSION['edit_profile']);endif; ?>