<?php

/**
 * @version     1.0.0
 * @package     com_blog
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Blog helper.
 */
class BlogHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_BLOG_TITLE_BLOGS'),
			'index.php?option=com_blog&view=blogs',
			$vName == 'blogs'
		);
		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES'),
			"index.php?option=com_categories&extension=com_blog",
			$vName == 'categories'
		);
		if ($vName=='categories') {
			JToolBarHelper::title('Blog: JCATEGORIES');
		}

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

        $assetName = 'com_blog';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
