<?php
/**
 * jDiction library entry point
 *
 * @package jDiction
 * @link http://joomla.itronic.at
 * @copyright	Copyright (C) 2013 ITronic Harald Leithner. All rights reserved.
 * @license GNU General Public License v3
 *
 * This file is part of jDiction.
 *
 * jDiction is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3 of the License.
 *
 * jDiction is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with jDiction.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
defined('_JEXEC') or die;


jimport('jdiction.finder.adapter');
/**
 * Finder adapter for com_content.
 *
 * @package     Joomla.Plugin
 * @subpackage  Finder.Content
 * @since       2.5
 */
class plgFinderJdContent extends JdFinderIndexerAdapter
{
	/**
	 * The plugin identifier.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $context = 'JdContent';

	/**
	 * The extension name.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $extension = 'com_content';

	/**
	 * The sublayout to use when rendering the results.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $layout = 'article';

	/**
	 * The type of content that the adapter indexes.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $type_title = 'Article';

	/**
	 * The table name.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $table = '#__content';

	/**
	 * Method to update the item link information when the item category is
	 * changed. This is fired when the item category is published or unpublished
	 * from the list view.
	 *
	 * @param   string   $extension  The extension whose category has been updated.
	 * @param   array    $pks        A list of primary key ids of the content that has changed state.
	 * @param   integer  $value      The value of the state that the content has been changed to.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function onFinderCategoryChangeState($extension, $pks, $value)
	{
		// Make sure we're handling com_content categories
		if ($extension == 'com_content')
		{
			$this->categoryStateChange($pks, $value);
		}
	}

	 
	/**
	 * Method to reindex an item.
	 *
	 * @param   integer  $id  The ID of the item to reindex.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	protected function reindex($id)
	{
		// Run the setup method.
		$this->setup();
		
		$db = JFactory::getDbo();
		// Get the item.
		$items = $this->getItems(0,1, "a.id = ".$db->q((int)$id));

		foreach ($items as $item) {
			// Index the item.
			$this->index($item);
		}
	}	

	/**
	 * Method to remove the link information for items that have been deleted.
	 *
	 * @param   string  $context  The context of the action being performed.
	 * @param   JTable  $table    A JTable object containing the record to be deleted
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	public function onFinderAfterDelete($context, $table)
	{
		if ($context == 'com_content.article')
		{
			$id = $table->id;
		}
		elseif ($context == 'com_finder.index')
		{
			$id = $table->link_id;
		}
		else
		{
			return true;
		}
		// Remove the items.
		return $this->remove($id);
	}

	/**
	 * Method to determine if the access level of an item changed.
	 *
	 * @param   string   $context  The context of the content passed to the plugin.
	 * @param   JTable   $row      A JTable object
	 * @param   boolean  $isNew    If the content has just been created
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	public function onFinderAfterSave($context, $row, $isNew)
	{
		// We only want to handle articles here
		if ($context == 'com_content.article' || $context == 'com_content.form')
		{
			// Check if the access levels are different
			if (!$isNew && $this->old_access != $row->access)
			{
				// Process the change.
				$this->itemAccessChange($row);
			}

			// Reindex the item
			$this->reindex($row->id);
		}

		// Check for access changes in the category
		if ($context == 'com_categories.category')
		{
			// Check if the access levels are different
			if (!$isNew && $this->old_cataccess != $row->access)
			{
				$this->categoryAccessChange($row);
			}
		}

		return true;
	}

	/**
	 * Method to reindex the link information for an item that has been saved.
	 * This event is fired before the data is actually saved so we are going
	 * to queue the item to be indexed later.
	 *
	 * @param   string   $context  The context of the content passed to the plugin.
	 * @param   JTable   $row     A JTable object
	 * @param   boolean  $isNew    If the content is just about to be created
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	public function onFinderBeforeSave($context, $row, $isNew)
	{
		// We only want to handle articles here
		if ($context == 'com_content.article' || $context == 'com_content.form')
		{
			// Query the database for the old access level if the item isn't new
			if (!$isNew)
			{
				$this->checkItemAccess($row);
			}
		}

		// Check for access levels from the category
		if ($context == 'com_categories.category')
		{
			// Query the database for the old access level if the item isn't new
			if (!$isNew)
			{
				$this->checkCategoryAccess($row);
			}
		}

		return true;
	}

	/**
	 * Method to update the link information for items that have been changed
	 * from outside the edit screen. This is fired when the item is published,
	 * unpublished, archived, or unarchived from the list view.
	 *
	 * @param   string   $context  The context for the content passed to the plugin.
	 * @param   array    $pks      A list of primary key ids of the content that has changed state.
	 * @param   integer  $value    The value of the state that the content has been changed to.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function onFinderChangeState($context, $pks, $value)
	{
		// We only want to handle articles here
		if ($context == 'com_content.article' || $context == 'com_content.form')
		{
			$this->itemStateChange($pks, $value);
		}
		// Handle when the plugin is disabled
		if ($context == 'com_plugins.plugin' && $value === 0)
		{
			$this->pluginDisable($pks);
		}
	}

	protected function getItemMenuTitle($url)
	{

		$return = null;

		// Set variables
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		// Build a query to get the menu params.
		$sql = $this->db->getQuery(true);
		$sql->select($this->db->quoteName('params'), $this->db->quoteName('id'));
		$sql->from($this->db->quoteName('#__menu'));
		$sql->where($this->db->quoteName('link') . ' = ' . $this->db->quote($url));
		$sql->where($this->db->quoteName('published') . ' = 1');
		$sql->where($this->db->quoteName('access') . ' IN (' . $groups . ')');

		// Get the menu params from the database.
		$this->db->setQuery($sql);
		$obj = $this->db->loadObject();

		// Check for a database error.
		if ($this->db->getErrorNum())
		{
			// Throw database error exception.
			throw new Exception($this->db->getErrorMsg(), 500);
		}

    if ($obj) {
      $params = $obj->params;
    }

		// Check the results.
		if (empty($params))
		{
			return $return;
		}

		// Instantiate the params.
		$params = json_decode($params);

		// Get the page title if it is set.
		if ($params->page_title)
		{
			$return = $params->page_title;
		}

		return $return;
	}
	

	/**
	 * Method to index an item. The item must be a FinderIndexerResult object.
	 *
	 * @param   FinderIndexerResult  $item    The item to index as an FinderIndexerResult object.
	 * @param   string               $format  The item format
	 *
	 * @return  void
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	protected function index(FinderIndexerResult $item, $format = 'html')
	{
    // @deprecated useding in Joomla 2.5 replaced by $this->indexer
    if (version_compare(JVERSION, '3.0.0', '<')) {
      if ($item->language == '*' || $item->language == '')
      {
        $item->language = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');;
      }
    } else {
      $item->setLanguage();
    }

		// Check if the extension is enabled
		if (JComponentHelper::isEnabled($this->extension) == false)
		{
			return;
		}
		
		// Initialize the item parameters.
		$registry = new JRegistry;
		$registry->loadString($item->params);
		$item->params = JComponentHelper::getParams('com_content', true);
		$item->params->merge($registry);

		$registry = new JRegistry;
		$registry->loadString($item->metadata);
		$item->metadata = $registry;

		$registry = new JRegistry;
		$registry->loadString($item->cat_metadata);
		$item->cat_metadata = $registry;
		
		if ((strpos($item->metadata->get('robots'), 'noindex') !== false) or (strpos($item->cat_metadata->get('robots'), 'noindex') !== false)) {
			return;
		}

		// Trigger the onContentPrepare event.
		$item->summary = FinderIndexerHelper::prepareContent($item->summary, $item->params);
		$item->body = FinderIndexerHelper::prepareContent($item->body, $item->params);

		// Build the necessary route and path information.
		$item->url = $this->getURL($item->id, $this->extension, $this->layout, $item->language);
		$item->route = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);
		$item->path = FinderIndexerHelper::getContentPath($item->route);
		
		// Get the menu title if it exists.
		$title = $this->getItemMenuTitle($item->url);

		// Adjust the title if necessary.
		if (!empty($title) && $this->params->get('use_menu_title', true))
		{
			$item->title = $title;
		}

		// Add the meta-author.
		$item->metaauthor = $item->metadata->get('author');

		// Add the meta-data processing instructions.
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metakey');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metadesc');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metaauthor');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'author');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'created_by_alias');

		// Translate the state. Articles should only be published if the category is published.
		$item->state = $this->translateState($item->state, $item->cat_state);

		// Add the type taxonomy data.
		$item->addTaxonomy('Type', 'Article');

		// Add the author taxonomy data.
		if (!empty($item->author) || !empty($item->created_by_alias))
		{
			$item->addTaxonomy('Author', !empty($item->created_by_alias) ? $item->created_by_alias : $item->author);
		}

		// Add the category taxonomy data.
		$item->addTaxonomy('Category', $item->category, $item->cat_state, $item->cat_access);
		
		// Add the language taxonomy data.
		$item->addTaxonomy('Language', $item->language);

		// Get content extras.
		FinderIndexerHelper::getContentExtras($item);

		// Index the item.
    // @deprecated useding in Joomla 2.5 replaced by $this->indexer
    if (version_compare(JVERSION, '3.0.0', '<')) {
      FinderIndexer::index($item);
    } else {
      $this->indexer->index($item);
    }
	}

	/**
	 * Method to setup the indexer to be run.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 */
	protected function setup()
	{
		// Load dependent classes.
		include_once JPATH_SITE . '/components/com_content/helpers/route.php';

		return true;
	}

  /**
   * Method to get the SQL query used to retrieve the list of content items.
   *
   * @param   mixed  $query  A JDatabaseQuery object or null.
   *
   * @return  JDatabaseQuery  A database object.
   *
   * @since   2.5
   */
  protected function getListQuery($query = null)
  {
    $db = JFactory::getDbo();
    // Check if we can use the supplied SQL query.
    $query = $query instanceof JDatabaseQuery ? $query : $db->getQuery(true)
      ->select('a.id, a.title, a.alias, a.introtext AS summary, a.fulltext AS body')
      ->select('a.state, a.catid, a.created AS start_date, a.created_by')
      ->select('a.created_by_alias, a.modified, a.modified_by, a.attribs AS params')
      ->select('a.metakey, a.metadesc, a.metadata, a.language, a.access, a.version, a.ordering')
      ->select('a.publish_up AS publish_start_date, a.publish_down AS publish_end_date')
      ->select('c.title AS category, c.published AS cat_state, c.access AS cat_access, c.metadata AS cat_metadata');

    // Handle the alias CASE WHEN portion of the query
    $case_when_item_alias = ' CASE WHEN ';
    $case_when_item_alias .= $query->charLength('a.alias', '!=', '0');
    $case_when_item_alias .= ' THEN ';
    $a_id = $query->castAsChar('a.id');
    $case_when_item_alias .= $query->concatenate(array($a_id, 'a.alias'), ':');
    $case_when_item_alias .= ' ELSE ';
    $case_when_item_alias .= $a_id.' END as slug';
    $query->select($case_when_item_alias);

    $case_when_category_alias = ' CASE WHEN ';
    $case_when_category_alias .= $query->charLength('c.alias', '!=', '0');
    $case_when_category_alias .= ' THEN ';
    $c_id = $query->castAsChar('c.id');
    $case_when_category_alias .= $query->concatenate(array($c_id, 'c.alias'), ':');
    $case_when_category_alias .= ' ELSE ';
    $case_when_category_alias .= $c_id.' END as catslug';
    $query->select($case_when_category_alias)

      ->select('u.name AS author')
      ->from('#__content AS a')
      ->join('LEFT', '#__categories AS c ON c.id = a.catid')
      ->join('LEFT', '#__users AS u ON u.id = a.created_by');

    return $query;
  }
}
