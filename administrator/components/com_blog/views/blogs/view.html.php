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

jimport('joomla.application.component.view');

/**
 * View class for a list of Blog.
 */
class BlogViewBlogs extends JViewLegacy {

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

        BlogHelper::addSubmenu('blogs');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/blog.php';

        $state = $this->get('State');
        $canDo = BlogHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_BLOG_TITLE_BLOGS'), 'blogs.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/blog';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('blog.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('blog.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('blogs.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('blogs.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'blogs.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('blogs.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('blogs.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'blogs.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                //JToolBarHelper::trash('blogs.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::deleteList('', 'blogs.delete', 'JTOOLBAR_TRASH'); //JTOOLBAR_EMPTY_TRASH
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_blog');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_blog&view=blogs');

        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_CATEGORY"),
			'filter_categories',
			JHtml::_('select.options', JHtml::_('category.options', 'com_blog'), "value", "text", $this->state->get('filter.categories'))

		);

		//Filter for the field mode
		$select_label = JText::sprintf('COM_BLOG_FILTER_SELECT_LABEL', 'Mode');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "1";
		$options[0]->text = "Short mode";
		$options[1] = new stdClass();
		$options[1]->value = "2";
		$options[1]->text = "Full mode ";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_mode',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.mode'), true)
		);

		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_LANGUAGE"),
			'filter_language',
			JHtml::_('select.options', JHtml::_("contentlanguage.existing", true, true), "value", "text", $this->state->get('filter.language'), true)
		);

			//Filter for the field created_date
		$this->extra_sidebar .= '<div class="other-filters">';
			$this->extra_sidebar .= '<small><label for="filter_from_created_date">'. JText::sprintf('COM_BLOG_FROM_FILTER', 'Created Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.created_date.from'), 'filter_from_created_date', 'filter_from_created_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_created_date">'. JText::sprintf('COM_BLOG_TO_FILTER', 'Created Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.created_date.to'), 'filter_to_created_date', 'filter_to_created_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
		$this->extra_sidebar .= '</div>';
			$this->extra_sidebar .= '<hr class="hr-condensed">';

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.categories' => JText::_('COM_BLOG_BLOGS_CATEGORIES'),
		'a.mode' => JText::_('COM_BLOG_BLOGS_MODE'),
		'a.name' => JText::_('COM_BLOG_BLOGS_NAME'),
		'a.image' => JText::_('COM_BLOG_BLOGS_IMAGE'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
		'a.created_date' => JText::_('COM_BLOG_BLOGS_CREATED_DATE'),
		);
	}

}
