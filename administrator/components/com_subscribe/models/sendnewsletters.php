<?php

/**
 * @version     1.0.0
 * @package     com_send_newsletter
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Phong Tranh <phongtranh68@gmail.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport( 'joomla.application.module.helper' );
jimport( 'joomla.html.parameter' ); 

/**
 * Methods supporting a list of Send_newsletter records.
 */
class SubscribeModelSendnewsletters extends JModelList {

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
                'blog_articles', 'a.blog_articles',
                'send_time', 'a.send_time',
                'is_sent', 'a.is_sent',

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

        
		//Filtering blog_articles
		$this->setState('filter.blog_articles', $app->getUserStateFromRequest($this->context.'.filter.blog_articles', 'filter_blog_articles', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_subscribe');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.blog_articles', 'asc');
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
        $query->from('`#__send_newsletter` AS a');

        
		// Join over the foreign key 'blog_articles'
		$query->select('a.id,
        a.send_time,
        GROUP_CONCAT(
            concat(
                \'<a target="_blank" href="index.php?option=com_blog&task=blog.edit&id=\',
                b.id,
                \'" title="\',
                b.name,
                \'">\',
                b.name,
                \'</a>\'
            ) SEPARATOR \'d*$$$p\') blog_articles');
		$query->from('#__blog AS b')
                ->where('FIND_IN_SET(b.id, a.blog_articles)')
                ->group('a.blog_articles');

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.blog_articles LIKE '.$search. ' )');
            }
        }

        

		//Filtering blog_articles
		$filter_blog_articles = $this->state->get("filter.blog_articles");
		if ($filter_blog_articles) {
			$query->where("a.blog_articles = '".$db->escape($filter_blog_articles)."'");
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

			if (isset($oneItem->blog_articles)) {
				$values = explode(',', $oneItem->blog_articles);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('name'))
							->from('`#__blog`')
							->where($db->quoteName('id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->title;
					}
				}

			$oneItem->blog_articles = !empty($textValue) ? implode(', ', $textValue) : $oneItem->blog_articles;

			}
		}

        return $items;
    }

    public function delete() {
        $ids = JRequest::getVar('cid');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // delete all custom keys for user 1001.
        $conditions = array(
            $db->quoteName('id') . ' IN ('. implode(',', $ids) . ')'
        );
        
        $query->delete($db->quoteName('#__send_newsletter'));
        $query->where($conditions);

        $db->setQuery($query);
         
        $result = $db->execute();
    }

}
