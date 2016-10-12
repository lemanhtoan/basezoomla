<?php
/**
 * @version     1.0.0
 * @package     com_subscribe
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Phong Tranh <phongtranh68@gmail.com> - http://
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Sendnewsletters list controller class.
 */
class SubscribeControllerSendnewsletters extends SubscribeController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Sendnewsletters', $prefix = 'SubscribeModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function sendMail() {
    	$model = $this->getModel();
    	$model->sendMail();
    }

    public function unSubscribe() {
    	$model = $this->getModel();
    	$email = JRequest::getVar('code');
    	if($model->unSubscribe($email)) {
    		$html = file_get_contents(JPATH_COMPONENT . '/assets/html/unsubscribe_success.html');
    		echo str_replace('[RESUBSCRIBE]', JRoute::_('index.php?option=com_subscribe&task=sendnewsletters.resubscribe&code='.$email), $html);
    	} else {
    		echo file_get_contents(JPATH_COMPONENT . '/assets/html/unsubscribe_fail.html');
    	}
    }

    public function reSubscribe() {
    	$model = $this->getModel();
    	$email = JRequest::getVar('code');
    	if($model->reSubscribe($email)) {
    		echo file_get_contents(JPATH_COMPONENT . '/assets/html/resubscribe_success.html');
    	} else {
    		echo file_get_contents(JPATH_COMPONENT . '/assets/html/resubscribe_fail.html');
    	}
    }
}