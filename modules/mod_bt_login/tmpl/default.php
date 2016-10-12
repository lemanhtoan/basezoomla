<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
//echo "<pre>"; var_dump($countryTranslationData); die;
$lang = JFactory::getLanguage();
$current_language = $lang->getTag();

?>
<div id="btl">
	<!-- Panel top -->	
	<div class="btl-panel">
		<?php if($type == 'logout') : ?>
		<!-- Profile button -->
		<span id="btl-panel-profile" class="btl-dropdown">			
			<?php  echo JText::_("MY_ACCOUT"); ?>
		</span> 
		<?php else : ?>
			<!-- Login button -->
			<?php
			if($params->get('enabled_login_tab', 1)){
			?>
			<span id="btl-panel-login" class="<?php echo $effect;?>"><?php echo JText::_('JLOGIN');?></span>
			<?php }?>			
		<?php endif; ?>
	</div>
	<!-- content dropdown/modal box -->
	<div id="btl-content">
		<?php if($type == 'logout') { ?>
		<!-- Profile module -->
		<div id="btl-content-profile" class="btl-content-block">
			<ul class="nav-user">
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile&layout=edit'); ?>"><?php echo JText::_('EDIT_ACCOUNT'); ?></a>
				</li>
				<li>
					<?php if($showLogout == 1):?>
						<div class="btl-buttonsubmit-logout">
							<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="logoutForm">
								<button name="Submit" class="btl-buttonsubmit logout-btn" onclick="document.logoutForm.submit();"><?php echo JText::_('JLOGOUT'); ?></button>
								<input type="hidden" name="option" value="com_users" />
								<input type="hidden" name="task" value="user.logout" />
								<input type="hidden" name="return" value="<?php echo $return; ?>" />
								<?php echo JHtml::_('form.token'); ?>				
							</form>
						</div>
					<?php endif;?>
				</li>				
			</ul>
			
		</div>
		<?php }else{ ?>	

		<!-- Form login -->	
		<div id="btl-content-login" class="btl-content-block">
			<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
				<?php JHTML::_('script', 'openid.js'); ?>
			<?php endif; ?>
			
			<!-- if not integrated any component -->
			<?php if($integrated_com==''|| $moduleRender == ''){?>
			<form name="btl-formlogin" class="btl-formlogin" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post">
				<div id="btl-login-in-process"></div>	
				<h3><?php echo JText::_('LOGIN_TO_YOUR_ACCOUNT') ?></h3>
				<div class="btl-error" id="btl-login-error"></div>
                
                <input type="text" id="username_focus" name="username_focus" /> 
                
				<div class="btl-field">
					<div class="btl-input">					
                        
						<input id="btl-input-username" placeholder="<?php echo JText::_('MOD_BT_LOGIN_USERNAME') ?>" type="text" name="username"/> 
					</div>
				</div>
				<div class="btl-field">
					<div class="btl-input">
						<input id="btl-input-password" placeholder="<?php echo JText::_('MOD_BT_LOGIN_PASSWORD') ?>" type="password" name="password"/>
					</div>
				</div>
				<div class="clear"></div>
				<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<div class="btl-field">		
					<div class="btl-input" id="btl-input-remember">
						<label class="myCheckbox">
						    <input id="btl-checkbox-remember"  type="checkbox" name="remember" value="yes" />
						    <span></span>
						</label>
						<label id="remember-lbl" for="remember-lbl"><?php echo JText::_('BT_REMEMBER_ME'); ?></label>	
					</div>	
				</div>
				<div class="clear"></div>
				<?php endif; ?>
				<div class="btl-buttonsubmit">
					<input type="submit" name="Submit" class="btl-buttonsubmit" onclick="return loginAjax()" value="<?php echo JText::_('JLOGIN') ?>" /> 
					<input type="hidden" name="bttask" value="login" /> 
					<input type="hidden" name="return" id="btl-return"	value="<?php echo $return; ?>" />
					<?php echo JHtml::_('form.token');?>
				</div>
			</form>	
			<ul id ="bt_ul">
				<li>
                    <div id="forgot-link">
						<a  href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					       <?php echo JText::_('BT_FORGOT_YOUR_PASSWORD'); ?>
                        </a>
					</div>
				</li>
				<li>
					<?php if ($enabledRegistration) : ?>
						<div id="register-link">
							<?php echo sprintf(JText::_('DONT_HAVE_AN_ACCOUNT_YET'),'<a href="'.JRoute::_('index.php?option=com_users&view=registration').'">','</a>');?>
							<p><i><?php echo JText::_('FOR_RETAILERS_ONLY'); ?></i></p>
						</div>
					<?php else: ?>
						<div class="spacer"></div>
					<?php endif; ?>
				</li>				
			</ul>
			
            <!-- if integrated with one component -->
			<?php }else{ ?>
				<h3><?php echo JText::_('JLOGIN') ?></h3>
				<div id="btl-wrap-module"><?php  echo $moduleRender; ?></div>
				<?php }?>			
		</div>
		<!--end login site-->
		
        <!--forgot form-->		
		<div id="btl-content-forgot" class="btl-content-block btl-formforgot">
            <form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="btl-formlogin">
                <div id="btl-forgot-in-process"></div>	
				<h3><?php echo JText::_('FORGOT_PASSWORD_HEADER') ?></h3>
				<div id="btl-success-2"></div>				
                <p><?php echo JText::_('RESET_PASSWORD_MESSAGE_MAIN');?></p>
                <div id="btl-forgot-error" class="btl-error" style="margin-left: 20px !important;"></div>
                <input type="text" id="username_focus_forgot" name="username_focus_forgot" /> 
                <input type="text" name="jform[email]" id="jform_email" value="" placeholder="<?php echo JText::_('COM_MAILTO_YOUR_EMAIL'); ?>" />
                <button type="submit" class="btl-buttonsubmit-forgot" onclick="return forgotAjax()" ><?php echo JText::_('RESET_PASSWORD_BUTTON');?></button>
                <input type="hidden" name="bttask" value="forgot" /> 
				<input type="hidden" name="return" id="btl-return"	value="<?php echo $return; ?>" />
        		<?php echo JHtml::_('form.token'); ?>
        	</form>
        </div>
        <!-- end forgot form-->
        
		<!--Register form-->
		<?php if($enabledRegistration ){ ?>			
		<div id="btl-content-registration" class="btl-content-block">			
			<!-- if not integrated any component -->
			<?php if($integrated_com==''){?>	
						
				<form name="btl-formregistration" class="btl-formregistration"  autocomplete="off">
					<div id="btl-register-in-process"></div>	
					<h3 id="register-header"><?php echo JText::_('CREATE_AN_ACCOUNT') ?></h3>
                    <h3 id="register-success"><?php echo JText::_('THANK_YOU') ?></h3>
					<div id="btl-success"></div>
					
                                        <div id="btl-registration-error-fields" class="btl-error"></div>
                                        <div id="btl-registration-error" class="btl-error"></div>

					<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_INFO"); ?></span></div>	
                    
                    <input type="text" id="username_focus_register" name="username_focus_register" /> 		
					<!--name-->
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-name" placeholder="<?php echo JText::_( 'BUSINESS_NAME' ); ?>" type="text" name="jform[name]" />
						</div>
					</div>			
					<div class="clear"></div>

					<!--contact-->
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-contact" placeholder="<?php echo JText::_( 'CONTACT_NOT_REQUIRED' ); ?>" type="text" name="jform[contact]" />
						</div>
					</div>			
					<div class="clear"></div>
					
					<!--email-->
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-email1" placeholder="<?php echo JText::_( 'EMAIL' ); ?>" type="text" name="jform[email1]" onchange="checkAutofill()" />
						</div>
					</div>
					<div class="clear"></div>

					<!--phone-->
					<div class="btl-field" id="phone_custom">
						<div class="btl-input">
							<input id="btl-input-phone" placeholder="<?php echo JText::_( 'PHONE_NUMBER' ); ?>" type="text" name="jform[phone]" />
						</div>
					</div>
					<div class="clear"></div>

					<!--add other phone-->
					<div class="btl-field"  >
						<div class="btl-input">
							<p id="addPhone"></p>
						</div>
						<input type="button" value="<?php echo JText::_('ADD_PHONE');?>" class="add" id="add" />
					</div>
					

					<!--INVOICE ADDRESS-->
					<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_INVOICE_ADDRESS"); ?></span></div>
					
					<!--street-->
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-street" placeholder="<?php echo JText::_( 'STREET' ); ?>" type="text" name="jform[street]" />
						</div>
					</div>
					<div class="clear"></div>

					<!--city-->
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-city" placeholder="<?php echo JText::_( 'CITY' ); ?>" type="text" name="jform[city]" />
						</div>
					</div>
					<div class="clear"></div>

					<!--zipcode + country-->
					<div class="btl-field">
						<div class="btl-input">
							<div style="width:50%; float:left">
								<input id="btl-input-zipcode" placeholder="<?php echo JText::_( 'ZIPCODE' ); ?>" type="text" name="jform[zipcode]" />
							</div>
							<div style="width:50%; float:right">
								<select id="btl-input-country" name="jform[country]">
                                    <?php                                     
                                        foreach ($countries as $key => $country) :
                                            if($country['country_3_code'] == 'CHE') { 
                                                unset($countries[$key]);
                                            }                                            
                                        endforeach; 
                                        $counttry = $countries;   
                                    ?>
                                    <option value="CHE"> Switzerland </option>
                                    <?php foreach ( $counttry as $country ): ?>
										<?php $country_id = $country['virtuemart_country_id']; ?>
										<?php $country_item = isset( $country[$country_id] ) ? $country[$country_id] : array(); ?>
										<option value="<?php echo $country['country_3_code'] ?>" >
											<?php echo ( isset($countryTranslationData[$country_id]) && isset($countryTranslationData[$country_id]['country_name']) ) ? $countryTranslationData[$country_id]['country_name'] :  $country['country_name']  ?>
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
						<div class="btl-input">
							<input id="btl-input-street-delevery" placeholder="<?php echo JText::_( 'STREET' ); ?>" type="text" name="jform[street_delevery]" />
						</div>
					</div>
					<div class="clear"></div>

					<!--city-->
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-city-delevery" placeholder="<?php echo JText::_( 'CITY' ); ?>" type="text" name="jform[city_delevery]" />
						</div>
					</div>
					<div class="clear"></div>

					<!--zipcode + country-->
					<div class="btl-field">
						<div class="btl-input">
							<div style="width:50%; float:left">
								<input id="btl-input-zipcode-delevery" placeholder="<?php echo JText::_( 'ZIPCODE' ); ?>" type="text" name="jform[zipcode_delevery]" />
							</div>
							<div style="width:50%; float:right" >
                                <select id="btl-input-country_delevery" name="jform[country_delevery]">
                                    <?php                                     
                                        foreach ($countries as $key => $country) :
                                            if($country['country_3_code'] == 'CHE') { 
                                                unset($countries[$key]);
                                            }                                            
                                        endforeach; 
                                        $counttry = $countries;   
                                    ?>
                                    <option value="CHE"> Switzerland </option>
                                    <?php foreach ( $counttry as $country ): ?>
										<?php $country_id = $country['virtuemart_country_id']; ?>
										<?php $country_item = isset( $country[$country_id] ) ? $country[$country_id] : array(); ?>
										<option value="<?php echo $country['country_3_code'] ?>" >
											<?php echo ( isset($countryTranslationData[$country_id]) && isset($countryTranslationData[$country_id]['country_name']) ) ? $countryTranslationData[$country_id]['country_name'] :  $country['country_name']  ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>																	
						</div>
					</div>
					<div class="clear"></div>

					<!--ADDITIONAL INFO-->
					<div class="btl-note"><span><?php echo JText::_("ADDITIONAL_INFO"); ?></span></div>
                    
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-additonal" placeholder="<?php echo JText::_( 'ADDITIONAL_INFO' ); ?>" type="text" name="jform[additonal]" />
						</div>
					</div>
					<div class="clear"></div>

                    <div class="btl-field">		
						<div class="btl-input" id="btl-input-subcribe" style="margin-top: -3px">
							<label class="myCheckbox">
							    <input id="btl-checkbox-subcribe"  type="checkbox" name="jform[subcribe]"/>
							    <span id="subscribe-span" style="margin-top: 15px; margin-left: 5px;"></span>
							</label>
							<label id="subcribe-lbl"  style="position: absolute; margin-left: 10px;"for="subcribe-lbl"><?php echo JText::_('GET_NEWSLETTER'); ?></label>	
						</div>	
					</div>
                    
                    
					<!--PASSWORD-->
					<div class="btl-note"><span><?php echo JText::_("BTL_BASIC_PASSWORD"); ?></span></div>
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-password1" placeholder="<?php echo JText::_( 'PASSWORD' ); ?>" type="password" name="jform[password1]"  />
						</div>
					</div>		
					<div class="clear"></div>					
					<div class="btl-field">
						<div class="btl-input">
							<input id="btl-input-password2" placeholder="<?php echo JText::_( 'REPEATE_PASSWORD' ); ?>" type="password" name="jform[password2]"  />
						</div>
					</div>
					<div class="clear"></div>

					<!-- add captcha-->
					<?php if($enabledRecaptcha){?>
					<div class="btl-field">
						<div id="recaptcha"><?php echo $reCaptcha;?></div>
					</div>
					<div id="btl-registration-captcha-error" class="btl-error-detail"></div>
					<div class="clear"></div>
					<!--  end add captcha -->
					<?php }?>
				
					<div class="btl-buttonsubmit">						
						<button type="submit" class="btl-buttonsubmit register-btn" onclick="return registerAjax()" >
							<?php echo JText::_('JREGISTER');?>							
						</button>
						 
						<input type="hidden" name="bttask" value="register" /> 
						<?php echo JHtml::_('form.token');?>
					</div>

					<!--input hidden current_language - subscribe-->
					<input type="hidden" name="jform[current_language]" value="<?php echo $current_language; ?>" />
			</form>
			<!-- if  integrated any component -->
			<?php }else{ ?>
				<input type="hidden" name="integrated" value="<?php echo $linkOption?>" value="no" id="btl-integrated"/>		
			<?php }?>
		</div>
						
		<?php } ?>

	<?php } ?>
	
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	    //checkAutofill()
        function checkAutofill_begin()
        {
            var email_del = document.getElementById("btl-input-email1").value;
            var street_del = document.getElementById("btl-input-street").value;
            var city_del = document.getElementById("btl-input-city").value;
            var zipcode_del = document.getElementById("btl-input-zipcode").value;
            var country_del = document.getElementById("btl-input-country").value;
            if(email_del !="" || street_del !="" || city_del !="" || zipcode_del !="" || country_del !=""){
            	jQuery("#btl-checkbox-copy-delevery").removeAttr("disabled");
            }
        }
        
        function checkAutofill()
        {            
            setTimeout(function(){ checkAutofill_begin(); }, 1000);
        }
        
/*<![CDATA[*/
var btlOpt = 
{
	BT_AJAX					:'<?php echo addslashes(JURI::getInstance()->toString()); ?>',
	BT_RETURN				:'<?php echo addslashes($return_decode); ?>',
	RECAPTCHA				:'<?php echo $enabledRecaptcha ;?>',
	LOGIN_TAGS				:'<?php echo $loginTag?>',
	REGISTER_TAGS			:'<?php echo $registerTag?>',
    
    FORGOT_TAGS		    	:'<?php echo $forgotTag?>',
    
    
	EFFECT					:'<?php echo $effect?>',
	ALIGN					:'<?php echo $align?>',
	BG_COLOR				:'<?php echo $bgColor ;?>',
	MOUSE_EVENT				:'<?php echo $params->get('mouse_event','click') ;?>',
	TEXT_COLOR				:'<?php echo $textColor;?>',
	MESSAGES 				: {
		E_LOGIN_AUTHENTICATE 		: '<?php echo JText::_('E_LOGIN_AUTHENTICATE')?>',
		REQUIRED_NAME				: '<?php echo JText::_('REQUIRED_NAME')?>',
		REQUIRED_USERNAME			: '<?php echo JText::_('REQUIRED_USERNAME')?>',
		REQUIRED_PASSWORD			: '<?php echo JText::_('REQUIRED_PASSWORD')?>',
		REQUIRED_VERIFY_PASSWORD	: '<?php echo JText::_('REQUIRED_VERIFY_PASSWORD')?>',
		PASSWORD_NOT_MATCH			: '<?php echo JText::_('PASSWORD_NOT_MATCH')?>',
		REQUIRED_EMAIL				: '<?php echo JText::_('REQUIRED_EMAIL')?>',
		EMAIL_INVALID				: '<?php echo JText::_('EMAIL_INVALID')?>',
		REQUIRED_VERIFY_EMAIL		: '<?php echo JText::_('REQUIRED_VERIFY_EMAIL')?>',
		EMAIL_NOT_MATCH				: '<?php echo JText::_('EMAIL_NOT_MATCH')?>',
		CAPTCHA_REQUIRED			: '<?php echo JText::_('CAPTCHA_REQUIRED')?>',
		//REQUIRED_CONTACT			: '<?php //echo JText::_('REQUIRED_CONTACT')?>',
		REQUIRED_PHONE				: '<?php echo JText::_('REQUIRED_PHONE')?>',
		REQUIRED_STREET				: '<?php echo JText::_('REQUIRED_STREET')?>',
		REQUIRED_CITY				: '<?php echo JText::_('REQUIRED_CITY')?>',
		REQUIRED_ZIPCODE			: '<?php echo JText::_('REQUIRED_ZIPCODE')?>',
                NUMBER_ONLY_ZIPCODE			: '<?php echo JText::_('NUMBER_ONLY_ZIPCODE')?>',
                NUMBER_ONLY_ZIPCODE_DELEVERY	: '<?php echo JText::_('NUMBER_ONLY_ZIPCODE_DELEVERY')?>',
                NUMBER_ONLY_PHONE			: '<?php echo JText::_('NUMBER_ONLY_PHONE')?>',
                LENGTH_PASSWORD : '<?php echo JText::_('LENGTH_PASSWORD') ?>',
                REQUIRED_STREET_DELEVERY				: '<?php echo JText::_('REQUIRED_STREET_DELEVERY')?>',
		REQUIRED_CITY_DELEVERY				: '<?php echo JText::_('REQUIRED_CITY_DELEVERY')?>',
		REQUIRED_ZIPCODE_DELEVERY			: '<?php echo JText::_('REQUIRED_ZIPCODE_DELEVERY')?>',
                NUMBER_ONLY_ZIPCODE_DELEVERY			: '<?php echo JText::_('NUMBER_ONLY_ZIPCODE_DELEVERY')?>',
                REQUIRED_FIELDS : '<?php echo JText::_('REQUIRED_FIELDS')?>',
	}
}
if(btlOpt.ALIGN == "center"){
	BTLJ(".btl-panel").css('textAlign','center');
}else{
	BTLJ(".btl-panel").css('float',btlOpt.ALIGN);
}
BTLJ("input.btl-buttonsubmit,button.btl-buttonsubmit").css({"color":btlOpt.TEXT_COLOR,"background":btlOpt.BG_COLOR});
BTLJ("#btl .btl-panel > span").css({"color":btlOpt.TEXT_COLOR,"background-color":btlOpt.BG_COLOR,"border":btlOpt.TEXT_COLOR});
/*]]>*/
</script>

