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
class ProductModelProducts extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
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
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        
		//Filtering category_id
		$this->setState('filter.category_id', $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '', 'string'));

		//Filtering bridge
		$this->setState('filter.bridge', $app->getUserStateFromRequest($this->context.'.filter.bridge', 'filter_bridge', '', 'string'));

		//Filtering colours
		$this->setState('filter.colours', $app->getUserStateFromRequest($this->context.'.filter.colours', 'filter_colours', '', 'string'));

		//Filtering language
		//Language filters for all languages is a * make it empty
		if (JFactory::getApplication()->input->getVar('filter_language') == '*') {
			JFactory::getApplication()->input->set('filter_language', '');
		}
		$this->setState('filter.language', $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '', 'string'));

		//Filtering updated_date
		$this->setState('filter.updated_date.from', $app->getUserStateFromRequest($this->context.'.filter.updated_date.from', 'filter_from_updated_date', '', 'string'));
		$this->setState('filter.updated_date.to', $app->getUserStateFromRequest($this->context.'.filter.updated_date.to', 'filter_to_updated_date', '', 'string'));

		//Filtering created_date
		$this->setState('filter.created_date.from', $app->getUserStateFromRequest($this->context.'.filter.created_date.from', 'filter_from_created_date', '', 'string'));
		$this->setState('filter.created_date.to', $app->getUserStateFromRequest($this->context.'.filter.created_date.to', 'filter_to_created_date', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_product');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.name', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__product` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the category 'category_id'
		$query->select('category_id.title AS category_id');
		$query->join('LEFT', '#__categories AS category_id ON category_id.id = a.category_id');
		// Join over the foreign key 'colours'
		$query->select('#__product_1866407.name AS products_name_1866407');
		$query->join('LEFT', '#__product AS #__product_1866407 ON #__product_1866407.id = a.colours');
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

        

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.name LIKE '.$search.'  OR  a.image LIKE '.$search.'  OR  a.image_hover LIKE '.$search.'  OR  a.category_id LIKE '.$search.'  OR  a.frame_width LIKE '.$search.'  OR  a.frame_height LIKE '.$search.'  OR  a.lens_width LIKE '.$search.'  OR  a.temple_arms LIKE '.$search.'  OR  a.bridge LIKE '.$search.'  OR  a.colours LIKE '.$search.'  OR  a.language LIKE '.$search.'  OR  a.updated_date LIKE '.$search.'  OR  a.created_date LIKE '.$search.' )');
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
		$filter_updated_date_from = $this->state->get("filter.updated_date.from");
		if ($filter_updated_date_from) {
			$query->where("a.updated_date >= '".$db->escape($filter_updated_date_from)."'");
		}
		$filter_updated_date_to = $this->state->get("filter.updated_date.to");
		if ($filter_updated_date_to) {
			$query->where("a.updated_date <= '".$db->escape($filter_updated_date_to)."'");
		}

		//Filtering created_date
		$filter_created_date_from = $this->state->get("filter.created_date.from");
		if ($filter_created_date_from) {
			$query->where("a.created_date >= '".$db->escape($filter_created_date_from)."'");
		}
		$filter_created_date_to = $this->state->get("filter.created_date.to");
		if ($filter_created_date_to) {
			$query->where("a.created_date <= '".$db->escape($filter_created_date_to)."'");
		}


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
		foreach ($items as $oneItem) {

			if (isset($oneItem->colours)) {
				$values = explode(',', $oneItem->colours);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('name'))
							->from('`#__product`')
							->where($db->quoteName('id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$oneItem->colours = !empty($textValue) ? implode(', ', $textValue) : $oneItem->colours;

			}
		}
        return $items;
    }

}
