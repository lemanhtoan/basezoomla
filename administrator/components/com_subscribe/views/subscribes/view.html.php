<?php

/**
 * @version     1.0.0
 * @package     com_subscribe
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Subscribe.
 */
class SubscribeViewSubscribes extends JViewLegacy {

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

        SubscribeHelper::addSubmenu('subscribes');
        SubscribeHelper::addSubmenu('sendnewsletter');

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
        require_once JPATH_COMPONENT . '/helpers/subscribe.php';

        $state = $this->get('State');
        $canDo = SubscribeHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_SUBSCRIBE_TITLE_SUBSCRIBES'), 'subscribes.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/subscribe';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('subscribe.add', 'JTOOLBAR_NEW');
            }

            // Send button
            if(isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('sendnewsletter.sendmailconfig', 'share', 'share', 'JTOOLBAR_SEND_NEWSLETTER', false);
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('subscribe.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('subscribes.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('subscribes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'subscribes.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('subscribes.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('subscribes.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'subscribes.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                //JToolBarHelper::trash('subscribes.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::deleteList('', 'subscribes.delete', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_subscribe');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_subscribe&view=subscribes');

        $this->extra_sidebar = '';
        
			//Filter for the field register
		$this->extra_sidebar .= '<div class="other-filters">';
			$this->extra_sidebar .= '<small><label for="filter_from_register">'. JText::sprintf('COM_SUBSCRIBE_FROM_FILTER', 'Register') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.register.from'), 'filter_from_register', 'filter_from_register', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_register">'. JText::sprintf('COM_SUBSCRIBE_TO_FILTER', 'Register') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.register.to'), 'filter_to_register', 'filter_to_register', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
		$this->extra_sidebar .= '</div>';
			$this->extra_sidebar .= '<hr class="hr-condensed">';

		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.email' => JText::_('COM_SUBSCRIBE_SUBSCRIBES_EMAIL'),
		'a.register' => JText::_('COM_SUBSCRIBE_SUBSCRIBES_REGISTER'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		);
	}

}
