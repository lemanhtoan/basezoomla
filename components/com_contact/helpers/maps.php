<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Contact Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_contact
 * @since       1.5
 */
abstract class ContactHelperMaps
{
	public static function getMaps()
	{
		$db = JFactory::getDbo();
	    $query = $db->getQuery(true);
	    $query
	        ->select('*')
	        ->from('#__contact_details')
	        ->where('id = 1');
	    $db->setQuery($query);
	    $result = $db->loadObject();
	    return $result;
	}
}
