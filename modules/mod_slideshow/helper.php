<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_slideshow
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_slideshow
 *
 * @package     Joomla.Site
 * @subpackage  mod_slideshow
 * @since       1.5
 */
class SlideshowMenuHelper
{
	public static function getList(&$params)
	{
        $db = JFactory::getDbo ();        
        $query = $db->getQuery ( true );
        $query
        ->select ( 'SQL_CACHE *' )
        ->from ( '#__slideshow' )
        ->where('state = 1')
        ->order ( 'ordering' );             
        $db->setQuery ($query);
        return $db->loadObjectList ();
	}
}