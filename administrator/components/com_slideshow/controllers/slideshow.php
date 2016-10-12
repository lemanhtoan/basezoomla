<?php
/**
 * @version     1.0.0
 * @package     com_slideshow
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Slideshow controller class.
 */
class SlideshowControllerSlideshow extends JControllerForm
{

    function __construct() {
        $this->view_list = 'slideshows';
        parent::__construct();
    }

}