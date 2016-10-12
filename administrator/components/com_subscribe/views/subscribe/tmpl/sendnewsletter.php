<?php
/**
 * @version     1.0.0
 * @package     com_subscribe
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_subscribe/assets/css/jquery.datetimepicker.css');
$document->addStyleSheet('components/com_subscribe/assets/css/subscribe.css');

// JS
$document->addScript('components/com_subscribe/assets/js/jquery.datetimepicker.js');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'subscribe.cancel') {
            Joomla.submitform(task, document.getElementById('subscribe-form'));
        }
        else {
            
            if (task != 'subscribe.cancel' && document.formvalidator.isValid(document.id('subscribe-form'))) {
                
                Joomla.submitform(task, document.getElementById('subscribe-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_subscribe&task=subscribe.saveconfigsend&layout=sendnewsletter&view=subscribe'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="subscribe-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_SUBSCRIBE_TITLE_SUBSCRIBE', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                  	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
					<div class="control-group">
						<div class="control-label"><?php echo $this->send_config_form->getLabel('blog_article'); ?></div>
						<div class="controls"><?php echo $this->send_config_form->getInput('blog_article'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->send_config_form->getLabel('send_time'); ?></div>
						<div class="controls">
			                    <?php echo $this->send_config_form->getInput('send_time'); ?>
						</div>
					</div>
					<script type="text/javascript">
			            jQuery(function($) {
			            	$(function () {
				                $('#datetimepicker').datetimepicker({
				                	formatDate:'Y-m-d',
				                	formatTime:'H:i'
									
				                });
				            });
			            });
			        </script>

                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>