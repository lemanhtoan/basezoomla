<?php

/**
 * @version     1.0.0
 * @package     com_subscribe
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Subscribe helper.
 */
class SubscribeHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        JHtmlSidebar::addEntry(
			JText::_('COM_SUBSCRIBE_TITLE_SUBSCRIBES'),
			'index.php?option=com_subscribe&view=subscribes',
			$vName == 'subscribes'
		);

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_subscribe';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
