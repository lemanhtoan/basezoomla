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
	$email = JRequest::getVar('email');
	$current_lang = JRequest::getVar('current_lang');
	// select all email system current and check exist email or not
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('id')
		->from('#__subscribe')
		->where('email = "'.$email.'"');
	$db->setQuery($query);
	$results = $db->loadObject();

	if(is_object($results) && count($results) > 0) {
		// exist email => response = 1
		$response = array('error_code' => 1, 'message' => 'Exist');
	} elseif($results == null) {
		// not exist email => insert new record
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$date = date('Y-m-d H:i:s');
		$query = "INSERT INTO `#__subscribe`(`email`, `register`, `language`) VALUES ('$email', '$date', '$current_lang')";
		$db->setQuery($query);
		$db->execute();
		$response = array('error_code' => 0, 'message' => 'Ok');
	}
    echo json_encode($response);
?>
