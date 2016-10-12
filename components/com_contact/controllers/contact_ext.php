<?php

class ContactControllerContactExt extends JControllerForm
{
 
	public function basesubmit()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app    = JFactory::getApplication();
		$model  = $this->getModel('contact');
		$params = JComponentHelper::getParams('com_contact');
		$stub   = $this->input->getString('id');
		$id     = (int) $stub;
		$submit_task = $this->input->getString('act');
		$submit_claim_url = '';
		
		if(isset($submit_task) && $submit_task == 'claim') {
			$submit_claim_url = '&task=claim';
		}
		$captcha_flag = true;

		// Get the data from POST
		$data    = $this->input->post->get('jform', array(), 'array');
        
		// Check captcha
		$global_config = new JConfig();
		$captcha_encrypt = $data['captcha'];
		$captcha_input = md5($global_config->secret.$data['contact_captcha']);
		if($captcha_input != $captcha_encrypt) {
			$msg = '<div id="contact-message" class="contact-message"><span style="color: #CC547F; background: #EFE7B8; padding: 15px; display: block">' . JText::_('CAPTCHA_WRONG') . '</span></div>';
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id=' . $stub . $submit_claim_url, false), $msg);
			return false;
		}

		$contact = $model->getItem($id);

		$params->merge($contact->params);

		// Check for a valid session cookie
		if ($params->get('validate_session', 0))
		{
			if (JFactory::getSession()->getState() != 'active')
			{
				JError::raiseWarning(403, JText::_('COM_CONTACT_SESSION_INVALID'));

				// Save the data in the session.
				$app->setUserState('com_contact.contact.data', $data);

				// Redirect back to the contact form.
				$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id=' . $stub, false));

				return false;
			}
		}

		// Contact plugins
		JPluginHelper::importPlugin('contact');
		$dispatcher = JEventDispatcher::getInstance();

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form)
		{
			JError::raiseError(500, $model->getError());

			return false;
		}

		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_contact.contact.data', $data);

			// Redirect back to the contact form.
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id=' . $stub, false));

			return false;
		}

		// Validation succeeded, continue with custom handlers
		$results = $dispatcher->trigger('onValidateContact', array(&$contact, &$data));

		foreach ($results as $result)
		{
			if ($result instanceof Exception)
			{
				return false;
			}
		}

		// Passed Validation: Process the contact plugins to integrate with other applications
		$dispatcher->trigger('onSubmitContact', array(&$contact, &$data));

		// Send the email
		$sent = false;

		if (!$params->get('custom_reply'))
		{
			if($captcha_flag) {
				$sent = $this->_sendEmail($data, $contact, $params->get('show_email_copy'));
			}
		}
		$success_param = '';
		$submit_task = $_POST['act'];
		$is_contact = '';
		if($submit_task == 'contact') {
			$is_contact = '&contact=1';
		}
		// Set the success message if it was a success
		if (!($sent instanceof Exception))
		{
			$_SESSION['sent'] = true;
			$success_param = '&status=success';

		}
		else
		{
			$msg = '';
		}

		// Flush the data from the session
		$app->setUserState('com_contact.contact.data', null);

		// Redirect if it is set in the parameters, otherwise redirect back to where we came from
		if ($contact->params->get('redirect'))
		{
			//$this->setRedirect($contact->params->get('redirect'), $msg);
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact'.$success_param.$is_contact, false), '');
		}
		else
		{
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id=' . $stub.$success_param.$is_contact, false), $msg);
		}

		return true;
	}

	private function _sendEmail($data, $contact, $copy_email_activated)
	{
			$app = JFactory::getApplication();
			$config = JFactory::getConfig();
			$sender_config = $config->get('smtpuser');
			if ($contact->email_to == '' && $contact->user_id != 0)
			{
				$contact_user      = JUser::getInstance($contact->user_id);
				$contact->email_to = $contact_user->get('email');
			}
			$mailfrom = $app->get('mailfrom');
			$fromname = $app->get('fromname');
			$sitename = $app->get('sitename');
			$name    = $data['contact_name'];

			$email   = JStringPunycode::emailToPunycode($data['contact_email']);
			$subject = JText::_('SEND_EMAIL_SUBJECT');

			// Mail content
			$body .= '<p><b>'.JText::_('COM_CONTACT_CONTACT_EMAIL_NAME_LABEL').'</b>: '.$data['contact_name'].'</p>';

			$body .= '<p><b>'.JText::_('COM_CONTACT_CONTACT_ADDRESS_NAME_LABEL').'</b>: '.$data['contact_address'].'</p>';

			$body .= '<p><b>'.JText::_('COM_CONTACT_CONTACT_TELEPHONE_NAME_LABEL').'</b>: '.$data['contact_telephone'].'</p>';

			$body .= '<p><b>'.JText::_('COM_CONTACT_EMAIL_LABEL').'</b>: '.$data['contact_email'].'</p>';

			$body .= '<p><b>'.JText::_('COM_CONTACT_CONTACT_ENTER_MESSAGE_LABEL').'</b>:<br /> '.$data['contact_message'].'</p>';
			
			// Prepare email body
			$prefix = JText::sprintf('COM_CONTACT_ENQUIRY_TEXT', JUri::base());
			$body	= $prefix . "\n" . "\r\n\r\n" . stripslashes($body);

			// Claim form data
			$submit_task = $_POST['act'];
			if(isset($submit_task) && $submit_task == 'claim') {
				// Get user contry
				$user = JFactory::getUser();
				//echo '<pre>'; print_r($user); die;
				$claim_data = $_POST['hdn_claim_data'];

				//Get setting param
		        $params = $this->getModuleParams(); 
		        $params_setting = $params[0]; 
		        $params_setting_arr = json_decode($params_setting, true);
		        $country_prefix = '';
		        $lang = JFactory::getLanguage();
		        switch ($lang->getTag()) {
		        	case 'de-DE': $country_prefix = '_de_DE'; break;
		        	case 'fr-FR': $country_prefix = '_fr_FR'; break;
		        	case 'it-IT': $country_prefix = '_it_IT'; break;
		        	default: $country_prefix = '_en_GB';

		        }
		        $email_template_content = $params_setting_arr['claim_template_content'.$country_prefix];
		        //echo '<pre>'; var_dump($email_template_content); die;
		        $post_datas = JRequest::get('jform');
		        $contact_name = $post_datas['jform']['contact_name'];
		        $contact_address = $post_datas['jform']['contact_address'];
		        $contact_phone = $post_datas['jform']['contact_telephone'];
		        $contact_message = $post_datas['jform']['contact_message'];
		        $contact_email = $post_datas['jform']['contact_email'];
		        // Switch language
		        switch($lang->getTag()) {
		        	case 'de-DE':
		        		{
							$claim_pre_text     = 'Ihr Beanstandungsformular ist eingegangen und wird schnellstmöglich bearbeitet.';
							$claim_number_title = 'Beanstandungsnummer';
							$claim_infor_title  = 'Benutzerinfo';
							$claim_product      = 'Anspruch Produkt';
							$copyright          = 'Copyright';
							$reseved            = 'Alle Rechte vorbehalten';
							$number             = 'Nr.';
							$name               = 'Produktname';
							$color              = 'Farbe';
							$invoice            = 'Rechnung Nr.';
							$type               = 'Art der Beanstandung';
							$desc               = 'Beschreibung';
							$basic_info			= 'Allgemeine Infos';
							$message 			= 'Nachricht';
							$subject            = 'Beanstandung';

		        		} break;
		        	case 'fr-FR':
		        		{
							$claim_pre_text     = 'Votre formulaire de demande a été reçue et est en cours de traitement. Votre demande de formulaire de détails sont présentés ci-dessous pour votre référence:';
							$claim_number_title = 'numéro de réclamation';
							$claim_infor_title  = 'Infos utilisateurs';
							$claim_product      = 'Produit de revendication';
							$copyright          = 'droit d\'auteur';
							$reseved            = 'Tous droits réservés';
							$number             = 'No.';
							$name               = 'Désignation';
							$color              = 'Couleur';
							$invoice            = 'Facture';
							$type               = 'Type d\'émission';
							$desc               = 'Description';
							$basic_info			= 'informations de base';
							$message 			= 'message';
							$subject            = 'Revendication';
		        		} break;
		        	case 'it-IT':
		        		{
							$claim_pre_text     = 'Il vostro modulo di domanda è stato ricevuto ed è ora in fase di elaborazione. Il vostro reclamo dettagli del modulo sono riportati di seguito per il vostro riferimento:';
							$claim_number_title = 'numero di richiesta';
							$claim_infor_title  = 'Info utenti';
							$claim_product      = 'prodotto Claim';
							$copyright          = 'diritto d\'autore';
							$reseved            = 'Tutti i diritti riservati';
							$number             = 'N.';
							$name               = 'Designazione';
							$color              = 'Colore';
							$invoice            = 'Fattura';
							$type               = 'Tipo di problema';
							$desc               = 'Descrizione';
							$basic_info			= 'informazioni di base';
							$message 			= 'messaggio';
							$subject            = 'Richiesta';
		        		} break;
		        	default:
		        		{
							$claim_pre_text     = 'Your claim form has been received and is now being processed. Your claim form details are shown below for your reference:';
							$claim_number_title = 'claim number';
							$claim_infor_title  = 'User info';
							$claim_product      = 'Claim product';
							$copyright          = 'Copyright';
							$reseved            = 'All rights reserved';
							$number             = 'No';
							$name               = 'Name';
							$color              = 'Color';
							$invoice            = 'Invoice';
							$type               = 'Type of issue';
							$desc               = 'Description';
							$basic_info			= 'Basic info';
							$message 			= 'message';
							$subject            = 'Claim';
		        		}
		        }

		        $table_header = '<table style="width: 100%; font-size: 16px; color: rgb(51, 51, 51); border-collapse: collapse; border-bottom: 1px solid rgb(218, 218, 218);">
						        	<tbody>
						        		<tr>
						        			<th style="font-weight: 500; text-align: center; width: 10%">'.$number.'</th>
						        			<th style="font-weight: 500; text-align: center; text-align: center; width: 15%">'.$name.'</th>
						        			<th style="font-weight: 500; text-align: center" style="font-weight: 500; width: 15%">'.$color.'</th>
						        			<th style="font-weight: 500; text-align: center; width: 10%">'.$invoice.'</th>
						        			<th style="font-weight: 500; text-align: center; width: 20%">'.$type.'</th>
						        			<th style="font-weight: 500; text-align: center; width: 30%">'.$desc.'</th>
						        		</tr>
						        	</tbody>
						        </table>';
				$user_info = '<div style="clear: both;">
								<div>
									<div style="float:left;width:50%;">
										<p style="color:#333333;">'.$contact_name.'</p><p style="color:#333333;">' . $contact_address . '</p><p style="color:#333333;">'.$contact_phone.'</p><p style="color:#333333;">'.$contact_email.'</p></div></div><div style="float:left;width:50%;">
									</div>
								</div>';
		        $replaces = array(
					'[claim_pre_text]'      =>	$claim_pre_text,
					'[claim_number_title]'  =>	$claim_number_title,
					'[claim_number]'        =>	'#'.rand(0,1000),
					'[claim_detail]'        =>	$table_header.$claim_data,
					'[claim_infor_title]'   =>	$claim_infor_title,
					'[claim_infor]'         =>	$user_info,
					'[CLAIM_PRODUCT]'       =>  $claim_product,
					'[COPYRIGHT]'           =>  $copyright,
					'[ALL_RIGHTS_RESERVED]' =>  $reseved,
					'[base_url]'			=>	JUri::base(),
					'[base_urls]'			=>	JUri::base()
		        );
				$body = str_replace(array_keys($replaces), array_values($replaces), $email_template_content);
				
				$other_emails = $params_setting_arr['claim_other_email_receive'];
				$other_emails = explode(',', $other_emails);
				
				$email = $params_setting_arr['claim_reply_to_email'];
				$name = $params_setting_arr['claim_reply_to_name'];
				$mailfrom = $contact->email_to;
				$fromname = $params_setting_arr['claim_email_sendor_name'];
				//$subject = $params_setting_arr['claim_email_subject'];
				$email_receive = array($contact->email_to, $contact_email);
				
				foreach($other_emails as $other_email) {
					$email_receive[] = trim($other_email);
				}

				// Send mail
				foreach($email_receive as $email) {
					$mail = JFactory::getMailer();
					$mail->IsHTML(true);
					$mail->addRecipient($email);
					$mail->addReplyTo($email, $name);
					$mail->setSender(array($sender_config, strtoupper($fromname)));
					$mail->setSubject($subject . ': ' . strtoupper($sitename));
					$mail->setBody($body);
					$sent = $mail->Send();
				}
				return $sent;
			}
			$mail = JFactory::getMailer();
			$mail->IsHTML(true);
			$mail->addRecipient($contact->email_to);
			$mail->addReplyTo($email, $name);
			$mail->setSender(array($sender_config, strtoupper($fromname)));
			$mail->setSubject($subject . ': ' . strtoupper($sitename));
			$mail->setBody($body);
			$sent = $mail->Send();

			// If we are supposed to copy the sender, do so.

			// Check whether email copy function activated
			if ($copy_email_activated == true && !empty($data['contact_email_copy']))
			{
				$copytext    = JText::sprintf('COM_CONTACT_COPYTEXT_OF', $contact->name, $sitename);
				$copytext    .= "\r\n\r\n" . $body;
				$copysubject = JText::sprintf('COM_CONTACT_COPYSUBJECT_OF', $subject);

				$mail = JFactory::getMailer();
				$mail->addRecipient($email);
				$mail->addReplyTo(array($email, $name));
				$mail->setSender(array($sender_config, $fromname));
				$mail->setSubject($copysubject);
				$mail->setBody($copytext);
				$sent = $mail->Send();
			}

			return $sent;
	}

	public function getModuleParams() {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('params')
            ->from('#__modules')
            ->where('module = "mod_setting" AND published = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList(); 
        $result = array();
        foreach($results as $r) {
            $result[] = $r->params;
        }

        return $result;
    }
    
}