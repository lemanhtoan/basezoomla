<?php
	define( '_JEXEC', 1 );
	// defining the base path.
	if (stristr( $_SERVER['SERVER_SOFTWARE'], 'win32' )) {
	    define( 'JPATH_BASE', realpath(dirname(__FILE__).'\..\..' ));
	} else define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../..' ));
	define( 'DS', DIRECTORY_SEPARATOR );		
	// including the main joomla files
	require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );

	$captcha_value = JRequest::getVar('captcha_value');
	$encrypt_value = JRequest::getVar('encrypt_value');
	if(isset($captcha_value) && isset($encrypt_value)) {
		if(is_numeric($captcha_value)) {
			$global_config = new JConfig();
			$total_encrypt = md5($global_config->secret.$captcha_value);
			if($total_encrypt == $encrypt_value) {
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	} else {
		echo 0;
	}