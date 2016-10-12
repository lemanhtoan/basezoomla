<?php

/**
 * @version     1.0.0
 * @package     com_product
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Product records.
 */
class ProductModelProducts extends JModelList
{
 
	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				                'id', 'a.id',
                'name', 'a.name',
                'image', 'a.image',
                'image_hover', 'a.image_hover',
                'category_id', 'a.category_id',
                'frame_width', 'a.frame_width',
                'frame_height', 'a.frame_height',
                'lens_width', 'a.lens_width',
                'temple_arms', 'a.temple_arms',
                'bridge', 'a.bridge',
                'colours', 'a.colours',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'language', 'a.language',
                'updated_date', 'a.updated_date',
                'created_date', 'a.created_date',

			);
		}
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{


		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');
		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');
		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
		{
			$list['ordering'] = 'ordering';
		}

		if (empty($list['direction']))
		{
			$list['direction'] = 'asc';
		}

		$this->setState('list.ordering', $list['ordering']);
		$this->setState('list.direction', $list['direction']);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__product` AS a');

		
    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the category 'category_id'
		$query->select('category_id.title AS category_id_title');
		$query->join('LEFT', '#__categories AS category_id ON category_id.id = a.category_id');
		// Join over the foreign key 'colours'
		$query->select('#__product_1866407.name AS products_name_1866407');
		$query->join('LEFT', '#__product AS #__product_1866407 ON #__product_1866407.id = a.colours');
		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		
		if (!JFactory::getUser()->authorise('core.edit.state', 'com_product'))
		{
			$query->where('a.state = 1');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.name LIKE '.$search.'  OR  a.frame_width LIKE '.$search.'  OR  a.frame_height LIKE '.$search.'  OR  a.lens_width LIKE '.$search.'  OR  a.temple_arms LIKE '.$search.'  OR  a.bridge LIKE '.$search.' )');
			}
		}

		

		//Filtering category_id
		$filter_category_id = $this->state->get("filter.category_id");
		if ($filter_category_id) {
			$query->where("a.category_id = '".$db->escape($filter_category_id)."'");
		}

		//Filtering bridge

		//Filtering colours
		$filter_colours = $this->state->get("filter.colours");
		if ($filter_colours) {
			$query->where("FIND_IN_SET('" . $db->escape($filter_colours) . "',a.colours)");
		}

		//Filtering language
		$filter_language = $this->state->get("filter.language");
		if ($filter_language) {
			$query->where("a.language = '".$db->escape($filter_language)."'");
		}

		//Filtering updated_date

		//Checking "_dateformat"
		$filter_updated_date_from = $this->state->get("filter.updated_date_from_dateformat");
		if ($filter_updated_date_from && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_updated_date_from) && date_create($filter_updated_date_from) ) {
			$query->where("a.updated_date >= '".$db->escape($filter_updated_date_from)."'");
		}
		$filter_updated_date_to = $this->state->get("filter.updated_date_to_dateformat");
		if ($filter_updated_date_to && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_updated_date_to) && date_create($filter_updated_date_to) ) {
			$query->where("a.updated_date <= '".$db->escape($filter_updated_date_to)."'");
		}

		//Filtering created_date

		//Checking "_dateformat"
		$filter_created_date_from = $this->state->get("filter.created_date_from_dateformat");
		if ($filter_created_date_from && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_created_date_from) && date_create($filter_created_date_from) ) {
			$query->where("a.created_date >= '".$db->escape($filter_created_date_from)."'");
		}
		$filter_created_date_to = $this->state->get("filter.created_date_to_dateformat");
		if ($filter_created_date_to && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_created_date_to) && date_create($filter_created_date_to) ) {
			$query->where("a.created_date <= '".$db->escape($filter_created_date_to)."'");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	public function getItems() 
	{
		$items = parent::getItems();
		foreach($items as $item){
	

			if ( isset($item->category_id) ) {

				// Get the title of that particular template
					$title = ProductFrontendHelper::getCategoryNameByCategoryId($item->category_id);

					// Finally replace the data object with proper information
					$item->category_id = !empty($title) ? $title : $item->category_id;
				}

			if (isset($item->colours) && $item->colours != '') {
				if(is_object($item->colours)){
					$item->colours = JArrayHelper::fromObject($item->colours);
				}
				$values = (is_array($item->colours)) ? $item->colours : explode(',',$item->colours);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('name'))
							->from('`#__product`')
							->where($db->quoteName('id') . ' = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$item->colours = !empty($textValue) ? implode(', ', $textValue) : $item->colours;

			}
		}
		return $items;
	}

	public function getProducts() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$cat_id = JRequest::getVar('id');
		$where_category = isset($cat_id) ? 'p.category_id = '.intval($cat_id) : '';
		$query
			->select('p.id, p.name, p.image, p.image_hover, c.title category_id_title')
			->from('#__product p')
			->join('INNER', '#__categories c ON p.category_id = c.id')
			->where('p.state = 1')
			->where($where_category)
			->order('id DESC, ordering ASC')
			->setLimit('10');
		$db->setQuery($query);
		$results = $db->loadObjectlist();
		return $results;
	}

}
