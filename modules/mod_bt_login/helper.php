<?php
/**
 * @package 	mod_bt_login - BT Login Module
 * @version		2.6.0
 * @created		April 2012

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.user.helper' );

//require_once JPATH_SITE.DS.'components'.DS.'com_users'.DS.'models'.DS.'reset.php';
require_once JPATH_SITE.'/components/com_users/models/reset.php';
//require_once JPATH_SITE.'\components\com_users\models\reset.php';
class modbt_loginHelper
{
	public static function loadModule($name,$title){
		$module=JModuleHelper::getModule($name,$title);
		return JModuleHelper::renderModule($module);
	}
	public static function loadModuleById($id){
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
			$query->select('module,title' );
			$query->from('#__modules');
			$query->where('#__modules.id='.$id);
			$db->setQuery((string)$query);
			$module = $db->loadObject();
			
			$module = JModuleHelper::getModule( $module->module,$module->title );
			
			$contents = JModuleHelper::renderModule ( $module);
			return $contents;
	}
	public static function getReturnURL($params, $type)
	{
		$app	= JFactory::getApplication();
		$router = $app->getRouter();
		$url = null;
		if ($itemid =  $params->get($type))
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);

			$query->select($db->quoteName('link'));
			$query->from($db->quoteName('#__menu'));
			$query->where($db->quoteName('published') . '=1');
			$query->where($db->quoteName('id') . '=' . $db->quote($itemid));

			$db->setQuery($query);
			if ($link = $db->loadResult()) {
				if ($router->getMode() == JROUTER_MODE_SEF) {
					$url = 'index.php?Itemid='.$itemid;
				}
				else {
					$url = $link.'&Itemid='.$itemid;
				}
			}
		}
		if (!$url)
		{
			// stay on the same page
			$uri = clone JFactory::getURI();
			$vars = $router->parse($uri);
			unset($vars['lang']);
			if ($router->getMode() == JROUTER_MODE_SEF)
			{
				if (isset($vars['Itemid']))
				{
					$itemid = $vars['Itemid'];
					$menu = $app->getMenu();
					$item = $menu->getItem($itemid);
					unset($vars['Itemid']);
					if (isset($item) && $vars == $item->query) {
						$url = 'index.php?Itemid='.$itemid;
					}
					else {
						$url = 'index.php?'.JURI::buildQuery($vars).'&Itemid='.$itemid;
					}
				}
				else
				{
					$url = 'index.php?'.JURI::buildQuery($vars);
				}
			}
			else
			{
				$url = 'index.php?'.JURI::buildQuery($vars);
			}
		}

		return base64_encode($url);
	}

	public static function getType()
	{
		$user =  JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
	
	public static function getModules($params) {
		$user =  JFactory::getUser();
		if ($user->get('guest')) return '';
		
		$document = JFactory::getDocument();
		$moduleRender = $document->loadRenderer('module');
		$positionRender = $document->loadRenderer('modules');
		
		$html = '';
		
		$db = JFactory::getDbo();
		$i=0;
		$module_id = $params->get('module_id', array());
		if (count($module_id) > 0) {
			$sql = "SELECT * FROM #__modules WHERE id IN (".implode(',', $module_id).") ORDER BY ordering";
			$db->setQuery($sql);
			$modules = $db->loadObjectList();
			foreach ($modules as $module) {
				
				if ($module->module != 'mod_bt_login') {
					$i++;
					$html = $html . $moduleRender->render($module->module, array('title' => $module->title, 'style' => 'xhtml'));
					//$html = $html .$moduleRender->render($module->module, array('title' => $module->title, 'style' => 'rounded'));
					//if($i%2==0) $html.="<br clear='both'>";
				}
			}
		}	
		$module_position = $params->get('module_position', array());
		if (count($module_position) > 0) {
			foreach ($module_position as $position) {
				$modules = JModuleHelper::getModules($position);
				foreach ($modules as $module) {
					if ($module->module != 'mod_bt_login') {
						$i++;
						$html = $html . $moduleRender->render($module, array('style' => 'xhtml'));
						//if($i%2==0) $html.="<br clear='both'>";
					}
				}
			}
		}
		if ($html==''){
			$html= $moduleRender->render('mod_menu',array('title'=>'User Menu','style'=>'xhtml'));
		}
		return $html;
	}
	public static function fetchHead($params){
		$document	= JFactory::getDocument();
		$header = $document->getHeadData();
		$mainframe = JFactory::getApplication();
		$template = $mainframe->getTemplate();

		$loadJquery = true;
		switch($params->get('loadJquery',"auto")){
			case "0":
				$loadJquery = false;
				break;
			case "1":
				$loadJquery = true;
				break;
			case "auto":
				
				foreach($header['scripts'] as $scriptName => $scriptData)
				{
					if(substr_count($scriptName,'/jquery'))
					{
						$loadJquery = false;
						break;
					}
				}
			break;		
		}
		
		// load js
		if(file_exists(JPATH_BASE.'/templates/'.$template.'/html/mod_bt_login/js/default.js'))
		{	
			if($loadJquery)
			{ 
				$document->addScript(JURI::root(true).'/templates/'.$template.'/html/mod_bt_login/js/jquery.min.js');
			}
			$document->addScript(JURI::root(true).'/templates/'.$template.'/html/mod_bt_login/js/jquery.simplemodal.js');
			$document->addScript(JURI::root(true).'/templates/'.$template.'/html/mod_bt_login/js/default.js');	
		}
		else{
			if($loadJquery)
			{ 
				$document->addScript(JURI::root(true).'/modules/mod_bt_login/tmpl/js/jquery.min.js');
			}
			$document->addScript(JURI::root(true).'/modules/mod_bt_login/tmpl/js/jquery.simplemodal.js');	
			$document->addScript(JURI::root(true).'/modules/mod_bt_login/tmpl/js/default.js');	
		}
		
		// load css
		if(file_exists(JPATH_BASE.'/templates/'.$template.'/html/mod_bt_login/css/style2.0.css'))
		{
			$document->addStyleSheet(  JURI::root(true).'/templates/'.$template.'/html/mod_bt_login/css/style2.0.css');
		}
		else{
			$document->addStyleSheet(JURI::root(true).'/modules/mod_bt_login/tmpl/css/style2.0.css');
		}

	}
    /**
	 * 
	 * function register()
	 * @param array() $temp
	 */	
	public static function register($temp)
	{
		$config = JFactory::getConfig();
		$db		= JFactory::getDbo();
		$params = JComponentHelper::getParams('com_users');
		
		// Initialise the table with JUser.
		$user = new JUser;
		
		// Merge in the registration data.
		foreach ($temp as $k => $v) {
			$data[$k] = $v;
		}

		// Prepare the data for the user object.
		$data['email']		= $data['email1'];
		$data['password']	= $data['password1'];
		$useractivation = $params->get ( 'useractivation' );
		
		// Check if the user needs to activate their account.
		if (($useractivation == 1) || ($useractivation == 2)) {
			$data ['activation'] = JApplication::getHash ( JUserHelper::genRandomPassword () );
			$data ['block'] = 1;
		}
		$system	= $params->get('new_usertype', 2);
		$data['groups'] = array($system);
		
		// Bind the data.
		if (! $user->bind ( $data )) {
			self::ajaxResponse('$error$'.JText::sprintf ( 'COM_USERS_REGISTRATION_BIND_FAILED', $user->getError () ));
		}
		
		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Store the data.
		if (!$user->save()) {
			self::ajaxResponse('$error$'.JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
		}
        
        
        return $user->id;

		
	}		
	
	public static function ajax($bttask, $params){
		$mainframe =& JFactory::getApplication('site');
		
		/**
		 * check task is login to do
		 */
		
		if($bttask=='login'){
			JRequest::checkToken() or self::ajaxResponse('$error$'.JText::_('JINVALID_TOKEN'));
	
			if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
				$return = base64_decode($return);
				if (!JURI::isInternal($return)) {
					$return = '';
				}
			}		
			$options = array();
			
			$options['remember'] = JRequest::getBool('remember', false);
			
			$options['return'] = $return;
	
			$credentials = array();
			
			$credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
			
			$credentials['password'] = JRequest::getString('passwd', '', 'post', JREQUEST_ALLOWRAW);
			
            //echo "<pre>"; var_dump($options); die("stop");
			//preform the login action
			$error = $mainframe->login($credentials, $options);
			self::ajaxResponse($error);
		}elseif(($bttask=='register')) {
			JRequest::checkToken() or self::ajaxResponse('$error$'.JText::_('JINVALID_TOKEN'));	
			/**
			 * check task is registration to do
			 */
			// If registration is disabled - Redirect to login page.
			if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0){
				// set message in here : Registration is disable
				self::ajaxResponse("Registration is not allow!");
			}
			
			//check captcha 
			if($params->get('use_captcha', 1)){
				if($params->get('use_captcha', 1) != 2){
					$captcha = JFactory::getConfig ()->get ( 'captcha' );
					if($captcha){
						$reCaptcha = JCaptcha::getInstance ($captcha);
						$checkCaptcha = $reCaptcha->checkAnswer('');
						if($checkCaptcha==false){
							self::ajaxResponse('$error$'.JText::_('INCORRECT_CAPTCHA'));
						}
					}					
				}else{
					$session = JFactory::getSession();
					if(JRequest::getString('btl_captcha') != $session->get('btl_captcha')){
						self::ajaxResponse('$error$'.JText::_('INCORRECT_CAPTCHA'));
					}
				}			
			}
		
			// Get the user data.
			// reset params form name in getVar function (not yet)
			$jform = JRequest::getVar('jform');
            
			$requestData ['name']      = $jform['name'];
			$requestData ['email1']    = $jform['email1'];
			$requestData ['password1'] = $jform['password1'];
			$requestData ['password2'] = $jform['password2'];
            
            $requestData ['contact']   = $jform['contact'];
			$requestData ['phone']     = $jform['phone'];
			$requestData ['street']    = $jform['street'];
			$requestData ['city']      = $jform['city'];
			$requestData ['zipcode']   = $jform['zipcode'];
			$requestData ['country']   = $jform['country'];

			//copy delevery data
			$requestData ['street_delevery']  = $jform['street_delevery'];
			$requestData ['city_delevery']    = $jform['city_delevery'];
			$requestData ['zipcode_delevery'] = $jform['zipcode_delevery'];
            
            //check $jform['country_delevery']
            if(isset($jform['country_delevery']) || (strlen($jform['country_delevery']) > 0)){
               $requestData ['country_delevery'] = $jform['country_delevery']; 
            }else{
               $requestData ['country_delevery'] = $jform['country']; 
            }		
			$requestData['additonal']         = $jform['additonal'];
			$requestData['phone1']            = $jform['phone1'];
            $requestData['username']          ="Baselli_" . time(); //set not null check
            
            //echo "<pre>"; var_dump($requestData); die;
            // subcribe
            if( (isset($jform['subcribe'])) && ($jform['subcribe'] == 'on') ) { 
                //email
                $email_add = $jform['email1'];
                
                $db1 = JFactory::getDbo();
                $query1 = $db1->getQuery(true);
                $values = array('"' . $email_add . '"', '"' . date("Y-m-d") . '"', '"' . 1 . '"', '"' . $jform['current_language'] . '"');
                $query1 = "INSERT INTO #__subscribe (email, register, state, language) VALUES ( " . implode(',', $values) . " )";                
                $db1->setQuery($query1);                               
                $db1->execute();                
            }

            $requestData['language_user'] =  $jform['current_language'];
            
            $requestData['user_retail_group'] =  'user_independent';
            
			$return	=self::register($requestData);	
            
        
			if ($return === 'adminactivate'){  
				self::ajaxResponse(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
			} elseif ($return === 'useractivate') { 
				self::ajaxResponse(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));		
			} else { 
			    //sent email to admin
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
				$query->select('*');
				$query->from( $db->quoteName('#__users') );
				$query->where( $db->quoteName('id') . ' = 289' );
				$db->setQuery($query);
				$adminRow = $db->loadObject();
                //email admin: $adminRow->email;
                
                //CHE Switzerland / DEU Germany / AUT Austria / FRA France / ITA Italy
                switch ($requestData['country']) {
        			case "CHE": 
        				$emailSubject = JText::sprintf("BASELLI New User Request");
                        $emailBody = JText::sprintf(  "Dear Admin, You have a new user registration request to Baselli site. User Name: " . $requestData['name'] . ", Email: " . $requestData['email1'] . ", Street - City - Country: " . $requestData['street'] . " - " . $requestData['city'] . " - " . $requestData['country'] . ', Phone Number: ' . $requestData['phone'] . ', Language: ' . $jform['current_language'] . '.');
                        $ttile = "BASELLI New User Request";
        				break;
                    case "DEU":
        				$emailSubject = JText::sprintf("BASELLI New User Request");
                        $emailBody = JText::sprintf(  "Dear Admin, You have a new user registration request to Baselli site. User Name: " . $requestData['name'] . ", Email: " . $requestData['email1'] . ", Street - City - Country: " . $requestData['street'] . " - " . $requestData['city'] . " - " . $requestData['country'] . ', Phone Number: ' . $requestData['phone'] . ', Language: ' . $jform['current_language'] . '.');
        				$ttile = "BASELLI New User Request";
        				break;
        			case "AUT":
        				$emailSubject = JText::sprintf("New User Details at Baselli[Austria]");
                        $emailBody = JText::sprintf(  "Dear Admin, You have a new user registration request to Baselli site. User Name: " . $requestData['name'] . ", Email: " . $requestData['email1'] . ", Street - City - Country: " . $requestData['street'] . " - " . $requestData['city'] . " - " . $requestData['country'] . ', Phone Number: ' . $requestData['phone'] . ', Language: ' . $jform['current_language'] . '.');
        				$ttile = "BASELLI New User Request";
        				break;
                    case "FRA":
        				$emailSubject = JText::sprintf("BASELLI New User Request");
                        $emailBody = JText::sprintf(  "Dear Admin, You have a new user registration request to Baselli site. User Name: " . $requestData['name'] . ", Email: " . $requestData['email1'] . ", Street - City - Country: " . $requestData['street'] . " - " . $requestData['city'] . " - " . $requestData['country'] . ', Phone Number: ' . $requestData['phone'] . ', Language: ' . $jform['current_language'] . '.');
        				$ttile = "BASELLI New User Request";
        				break;
                    case "ITA":
        				$emailSubject = JText::sprintf("BASELLI New User Request");
                        $emailBody = JText::sprintf(  "Dear Admin, You have a new user registration request to Baselli site. User Name: " . $requestData['name'] . ", Email: " . $requestData['email1'] . ", Street - City - Country: " . $requestData['street'] . " - " . $requestData['city'] . " - " . $requestData['country'] . ', Phone Number: ' . $requestData['phone'] . ', Language: ' . $jform['current_language'] . '.');
        				$ttile = "BASELLI New User Request";
        				break; 
                        
                    /*
        			case "DEU":
        				$emailSubject = JText::sprintf("BASELLI New User Request");
                        $emailBody = JText::sprintf(  "Lieber Administrator, es hat sich ein neuer Benutzer auf der Baselli-Seite registriert. Benutzer Mail: " . $requestData['email1'] . ', Client-Telefon: ' . $requestData['phone'] . '.');
        				$ttile = "BASELLI New User Request";
        				break;
        			case "AUT":
        				$emailSubject = JText::sprintf("New User Details at Baselli[Austria]");
                        $emailBody = JText::sprintf(  "Dear Admin, Have new user register to Baselli page. Client email: " . $requestData['email1'] . ', Client phone: ' . $requestData['phone'] . '.');
        				$ttile = "BASELLI New User Request";
        				break;
                    case "FRA":
        				$emailSubject = JText::sprintf("BASELLI New User Request");
                        $emailBody = JText::sprintf(  "Cher administrateur, il ya un nouvel utilisateur est enregistré sur la page Baselli. Email de l'utilisateur: " . $requestData['email1'] . ', Téléphone du client: ' . $requestData['phone'] . '.');
        				$ttile = "BASELLI New User Request";
        				break;
                    case "ITA":
        				$emailSubject = JText::sprintf("BASELLI New User Request");
                        $emailBody = JText::sprintf(  "Caro amministratore, c'è un nuovo utente si è registrato sulla pagina Baselli. Utente e-mail: " . $requestData['email1'] . ', Telefono client: ' . $requestData['phone'] . '.');
        				$ttile = "BASELLI New User Request";
        				break; */
        		}
        
        		// Get the Mailer
                $mailer = JFactory::getMailer();
                $mailer->isHTML(true);
                $mailer->addRecipient($adminRow->email);
                
        		// Build email message format.
        		$mailer->setSender(array("info@gmail.com", $ttile));
        		$mailer->setSubject($emailSubject);
        		$mailer->setBody($emailBody);
                //echo "<pre>"; var_dump($mailer); die;
        		// Send the Mail
        		$mailer->Send();
          
                // end custom sent email to user         
                
				self::ajaxResponse(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS').'<p class="button-task"><a href="'.JUri::base().'" title="OK" class="ok-button">OK</a></p>');                      
        	    
			}
		}elseif(($bttask=='forgot')) {
			JRequest::checkToken() or self::ajaxResponse('$error$'.JText::_('JINVALID_TOKEN'));			
			$jform = JRequest::getVar('jform');            
			$requestData ['email'] = $jform['email'];
            // Submit the password reset request.
            $reset = new UsersModelReset(); 
			$return	= $reset->AjaxProcessResetRequest($requestData);
            
            if($return == 1) {
                self::ajaxResponse(JText::_('PLEASE_CHECK_YOUR_EMAIL'));
            } else {
                self::ajaxResponse(JText::_('EMAIL_NOT_EXIST'));
            }
		}else{
			self::ajaxResponse(self::createCaptcha());
		}
	}
    
    
    
	public static function ajaxResponse($message){
		$obLevel = ob_get_level();
		if($obLevel){
			while ($obLevel > 0 ) {
				ob_end_clean();
				$obLevel --;
			}
		}else{
			ob_clean();
		}
		echo $message;
		die;
	}

	/**
	 * Create image captcha
	 * @since 2.6.0
	 */
	public static function createCaptcha(){
		$session = JFactory::getSession();
		$oldImages = glob(JPATH_ROOT . '/modules/mod_bt_login/captcha_images/*.png');
		if($oldImages){
			foreach($oldImages as $oldImage){
				if(file_exists($oldImage)){
					unlink($oldImage);
				}
			}
		}	

		
		
		$imagePath = base64_encode($session->getId() . time()). '.png';
		$session->set('btl_captcha_image_path', $imagePath);
		
		$image = imagecreatetruecolor(200, 50) or die("Cannot Initialize new GD image stream");
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 0, 255, 255);
		$line_color = imagecolorallocate($image, 64, 64, 64);
		$pixel_color = imagecolorallocate($image, 0, 0, 255);
		imagefilledrectangle($image, 0, 0, 200, 50, $background_color);
		
		//random dots
		for ($i = 0; $i < 1000; $i++) {
		imagesetpixel($image, rand() % 200, rand() % 50, $pixel_color);
		}
 
		$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$len = strlen($letters);
		
 
		$text_color = imagecolorallocate($image, 0, 0, 0);
		$word = "";
		for ($i = 0; $i < 6; $i++) {
			$letter = $letters[rand(0, $len - 1)];
			imagestring($image, 7, 5 + ($i * 30), 20, $letter, $text_color);
			$word .= $letter;
		}
		$session->set('btl_captcha', $word);
 
		
		
		if(!file_exists(JPATH_ROOT . "/modules/mod_bt_login/captcha_images")){
			mkdir(JPATH_ROOT . "/modules/mod_bt_login/captcha_images");
		}
		
		imagepng($image, JPATH_ROOT . '/modules/mod_bt_login/captcha_images/' . $imagePath);
		return JURI::root(). 'modules/mod_bt_login/captcha_images/' . $imagePath;
	}
	/**
	 * Return builtin captcha html
	 * @since 2.6.0
	 */
	public static function getBuiltinCaptcha(){
		$html = '<img src="' . self::createCaptcha() .'" alt=""/>
				<div style="clear:both"></div>
				<input type="text" name="btl_captcha" id="btl-captcha" size="10"/>
				<span id="btl-captcha-reload" title="' . JText::_('RELOAD_CAPTCHA') . '">' . JText::_('RELOAD_CAPTCHA') . '</span>
				';
		return $html;
	}
}
