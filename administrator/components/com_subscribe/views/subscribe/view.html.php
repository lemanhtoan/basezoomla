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
 * View to edit
 */
class SubscribeViewSubscribe extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->send_config_form = $this->get('SendNewsletterForm');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();
        $isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
        $canDo = SubscribeHelper::getActions();
        $layout = JRequest::getVar('layout');
        JToolBarHelper::title(JText::_('COM_SUBSCRIBE_TITLE_SUBSCRIBE'), 'subscribe.png');
        
        if($layout == 'sendnewsletter') {
            $save_submit = 'subscribe.saveconfigsend';
            $save_submit_close = 'subscribe.saveconfigsend';
        } elseif($layout == 'edit') {
            $save_submit = 'subscribe.apply';
            $save_submit_close = 'subscribe.save';
        }

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {

            JToolBarHelper::apply($save_submit, 'JTOOLBAR_APPLY');
            JToolBarHelper::save($save_submit_close, 'JTOOLBAR_SAVE');
        }

        if($layout == 'subscribe') {
            if (!$checkedOut && ($canDo->get('core.create'))) {
                JToolBarHelper::custom('subscribe.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
        }
        // If an existing item, can save to a copy.
        if (!$isNew && $canDo->get('core.create')) {
            JToolBarHelper::custom('subscribe.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
        }
        if (empty($this->item->id)) {
            JToolBarHelper::cancel('subscribe.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('subscribe.cancel', 'JTOOLBAR_CLOSE');
        }
    }

}
