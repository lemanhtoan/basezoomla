<?php

/**
 * @version     1.0.0
 * @package     com_product
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
defined('_JEXEC') or die;

class ProductFrontendHelper {
    
	/**
	* Get category name using category ID
	* @param integer $category_id Category ID
	* @return mixed category name if the category was found, null otherwise
	*/
	public static function getCategoryNameByCategoryId($category_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . intval($category_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
	public static function getCategories($parent_id) {
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		$query
		->select('id, title, path, alias, description')
		->from('#__categories')
		->where('parent_id = ' . intval($parent_id) . ' AND published = 1')
		->order('lft')
		->setLimit('10');
		
		//echo $query->__toString();die;
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public static function getVirtueMartCategories() {
		$lang = JFactory::getLanguage();
		$current_lang = strtolower(str_replace('-', '_', $lang->getTag()));
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('l.virtuemart_category_id, l.category_name, l.slug')
			->from('#__virtuemart_categories_'.$current_lang.' l')
			->join('INNER', '#__virtuemart_categories c ON l.virtuemart_category_id = c.virtuemart_category_id')
			->where('published = 1')
			->order('c.ordering ASC')
			->setLimit('10');
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
}
