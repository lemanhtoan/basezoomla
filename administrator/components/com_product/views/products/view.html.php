<?php

/**
 * @version     1.0.0
 * @package     com_product
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Product.
 */
class ProductViewProducts extends JViewLegacy {

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

        ProductHelper::addSubmenu('products');

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
        require_once JPATH_COMPONENT . '/helpers/product.php';

        $state = $this->get('State');
        $canDo = ProductHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_PRODUCT_TITLE_PRODUCTS'), 'products.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/product';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('product.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('product.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('products.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('products.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'products.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('products.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('products.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'products.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('products.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_product');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_product&view=products');

        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_CATEGORY"),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_product'), "value", "text", $this->state->get('filter.category_id'))

		);
                                                
        //Filter for the field colours;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_product.product', 'product');

        $field = $form->getField('colours');

        $query = $form->getFieldAttribute('filter_colours','query');
        $translate = $form->getFieldAttribute('filter_colours','translate');
        $key = $form->getFieldAttribute('filter_colours','key_field');
        $value = $form->getFieldAttribute('filter_colours','value_field');

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
            '$Colours',
            'filter_colours',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.colours')),
            true
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

			//Filter for the field updated_date
		$this->extra_sidebar .= '<div class="other-filters">';
			$this->extra_sidebar .= '<small><label for="filter_from_updated_date">'. JText::sprintf('COM_PRODUCT_FROM_FILTER', 'Updated Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.updated_date.from'), 'filter_from_updated_date', 'filter_from_updated_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_updated_date">'. JText::sprintf('COM_PRODUCT_TO_FILTER', 'Updated Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.updated_date.to'), 'filter_to_updated_date', 'filter_to_updated_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
		$this->extra_sidebar .= '</div>';
			$this->extra_sidebar .= '<hr class="hr-condensed">';

			//Filter for the field created_date
		$this->extra_sidebar .= '<div class="other-filters">';
			$this->extra_sidebar .= '<small><label for="filter_from_created_date">'. JText::sprintf('COM_PRODUCT_FROM_FILTER', 'Created Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.created_date.from'), 'filter_from_created_date', 'filter_from_created_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_created_date">'. JText::sprintf('COM_PRODUCT_TO_FILTER', 'Created Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.created_date.to'), 'filter_to_created_date', 'filter_to_created_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
		$this->extra_sidebar .= '</div>';
			$this->extra_sidebar .= '<hr class="hr-condensed">';

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.name' => JText::_('COM_PRODUCT_PRODUCTS_NAME'),
		'a.image' => JText::_('COM_PRODUCT_PRODUCTS_IMAGE'),
		'a.image_hover' => JText::_('COM_PRODUCT_PRODUCTS_IMAGE_HOVER'),
		'a.category_id' => JText::_('COM_PRODUCT_PRODUCTS_CATEGORY_ID'),
		'a.frame_width' => JText::_('COM_PRODUCT_PRODUCTS_FRAME_WIDTH'),
		'a.frame_height' => JText::_('COM_PRODUCT_PRODUCTS_FRAME_HEIGHT'),
		'a.lens_width' => JText::_('COM_PRODUCT_PRODUCTS_LENS_WIDTH'),
		'a.temple_arms' => JText::_('COM_PRODUCT_PRODUCTS_TEMPLE_ARMS'),
		'a.bridge' => JText::_('COM_PRODUCT_PRODUCTS_BRIDGE'),
		'a.colours' => JText::_('COM_PRODUCT_PRODUCTS_COLOURS'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
		);
	}

}
