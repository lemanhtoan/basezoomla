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
        if (task == 'sendnewsletter.cancel') {
            Joomla.submitform(task, document.getElementById('sendnewsletter-form'));
        }
        else {
            
            if (task != 'sendnewsletter.cancel' && document.formvalidator.isValid(document.id('sendnewsletter-form'))) {
                
                Joomla.submitform(task, document.getElementById('sendnewsletter-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_subscribe&view=sendnewsletter&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="sendnewsletter-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_SUBSCRIBE_TITLE_SUBSCRIBE', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">
                     
                    
                    <div class="control-group">
						<div class="control-label">Time hosting current:</div>
						<div class="controls"><?php 
                        //echo "<b>" . file_get_contents("http://localhost/zfg513/en/component/subscribe/sendnewsletters/sendmail.html") . "</b>";
                        echo "<b>" . file_get_contents("http://baselli.ch/test.php") . "</b>"; ?></div>
                        <!-- http://localhost/zfg513/en/component/subscribe/sendnewsletters/sendmail.html -->
					</div>
                    
                    
                  	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('blog_articles'); ?></div>
						<div class="controls list-blog"><?php echo $this->form->getInput('blog_articles'); ?></div>
					</div>
                    <?php
                        foreach((array)$this->item->blog_articles as $value): 
                            if(!is_array($value)):
                                echo '<input type="hidden" class="blog_articles" name="jform[blog_articleshidden]['.$value.']" value="'.$value.'" />';
                            endif;
                        endforeach;
                    ?>
					<div class="control-group" style="display: none;">
						<div class="control-label"><?php echo $this->form->getLabel('send_time'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('send_time'); ?></div>
					</div>
                    <div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('send_time'); ?></div>
						<div class="controls"><input type="text" name="time" value="" id="datetimepicker" /></div>
					</div>
                    <input type="hidden" name="jform[is_sent]" value="<?php echo $this->item->is_sent; ?>" />
					<script type="text/javascript">
			            jQuery(function($) {
			            	$(function () {			            	    
                                var convertDateLoad = function(usDate) {
                                  var dateParts = usDate.split(/(\d{4})\-(\d{1,2})\-(\d{1,2})/);
                                  return dateParts[2] + "/" + dateParts[3] + "/" + dateParts[1];
                                }
                                var inDateLoad =  $('#jform_datetimepicker').val().slice(0,10);
                                var inHoursLoad = $('#jform_datetimepicker').val().slice(11,19);                                
                                var outDateLoad = convertDateLoad(inDateLoad);
                                var dateSetLoad = outDateLoad + " " + inHoursLoad;
			            	    $(window).load(function() {
			            	        if(dateSetLoad != "undefined/undefined/undefined ") {
			            	          $('#datetimepicker').attr('value',dateSetLoad);  
			            	        } else {
			            	            $('#datetimepicker').attr('value',"");
			            	        }                                   
			            	    });
				                $('#datetimepicker').datetimepicker({
				                    format:'m/d/Y H:i',
									step: 10 
				                });
				            });
                            var convertDate = function(usDate) {
                              var dateParts = usDate.split(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);
                              return dateParts[3] + "-" + dateParts[1] + "-" + dateParts[2];
                            }
                            
                            $('#datetimepicker').change(function() {
                                var inDate = $(this).val().slice(0,10);
                                var inHours = $(this).val().slice(11,19);
                                var outDate = convertDate(inDate);
                                var dateSet = outDate + " " + inHours;
                                $('#jform_datetimepicker').attr('value', dateSet);
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