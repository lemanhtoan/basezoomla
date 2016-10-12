<?php

/**
 * @version     1.0.0
 * @package     com_send_newsletter
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Phong Tranh <phongtranh68@gmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Send_newsletter.
 */
class SubscribeViewSendnewsletters extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        SubscribeHelper::addSubmenu('sendnewsletters');

        $this->addToolbar();

        $this->sidebar = '';
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/subscribe.php';

        $state = $this->get('State');
        $canDo = SubscribeHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_SEND_NEWSLETTER_TITLE_NEWSLETTERS'), 'sendnewsletters.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/sendnewsletter';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('sendnewsletter.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('sendnewsletter.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('sendnewsletters.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('sendnewsletters.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'sendnewsletters.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('sendnewsletters.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('sendnewsletters.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'sendnewsletters.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('sendnewsletters.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_subscribe');
        }

        //Set sidebar action - New in 3.0
        //JHtmlSidebar::setAction('index.php?option=com_send_newsletter&view=newsletters');

        $this->extra_sidebar = '';
                                                        
        //Filter for the field blog_articles;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_subscribe.sendnewsletter', 'sendnewsletter');

        $field = $form->getField('blog_articles');

        $query = $form->getFieldAttribute('filter_blog_articles','query');
        $translate = $form->getFieldAttribute('filter_blog_articles','translate');
        $key = $form->getFieldAttribute('filter_blog_articles','key_field');
        $value = $form->getFieldAttribute('filter_blog_articles','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            '$Blog Articles',
            'filter_blog_articles',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.blog_articles')),
            true
        );
    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.blog_articles' => JText::_('COM_SEND_NEWSLETTER_NEWSLETTERS_BLOG_ARTICLES'),
		'a.send_time' => JText::_('COM_SEND_NEWSLETTER_NEWSLETTERS_SEND_TIME'),
		'a.is_sent' => JText::_('COM_SEND_NEWSLETTER_NEWSLETTERS_IS_SENT'),
		);
	}

}
