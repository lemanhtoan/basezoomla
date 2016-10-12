<?php

/**
 * @version     1.0.0
 * @package     com_home
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.modellist' );
 
/**
 * Methods supporting a list of Home records.
 */
class ProductModelCollection extends JModelList {
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	array An optional associative array of configuration settings.
	 *        	
	 * @see JController
	 * @since 1.6
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
	}

	public function getSubCategories() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('id, title, alias, params')
			->from('#__categories')
			->where('level > 1')
			->where('extension = "com_product"')
			->where('published = 1')
			->order('lft ASC')
			;
		$db->setQuery($query);
		return $db->loadObjectList();

	}

	public function checkHasSubCategory($id) {

	}
}
