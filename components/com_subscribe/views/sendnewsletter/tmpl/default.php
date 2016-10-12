<?php
/**
 * @version     1.0.0
 * @package     com_subscribe
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Phong Tranh <phongtranh68@gmail.com> - http://
 */
// no direct access
defined('_JEXEC') or die;


?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            
        </table>
    </div>
    
    <?php
else:
    echo JText::_('COM_SUBSCRIBE_ITEM_NOT_LOADED');
endif;
?>
