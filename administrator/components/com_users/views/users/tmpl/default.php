<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$loggeduser = JFactory::getUser();
?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=users');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="userList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th class="left">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_NAME', 'user_list.name', $listDirn, $listOrder); ?>
						</th>

						<th width="15%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_ENABLED', 'user_list.block', $listDirn, $listOrder); ?>
						</th>
						
                        <th width="10%" class="nowrap center">
							<?php echo "Language"; ?>
						</th>
                        
                        <th width="10%" class="nowrap center">
							<?php echo "Retail Group"; ?>
						</th>

                        <th width="10%" class="nowrap center">
                            <?php echo "Membership Number"; ?>
                        </th>
                        
						<th width="10%" class="nowrap center">
							<?php echo JText::_('COM_USERS_HEADING_GROUPS'); ?>
						</th>
						<th width="25%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_EMAIL', 'user_list.email', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_LAST_VISIT_DATE', 'user_list.lastvisitDate', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_REGISTRATION_DATE', 'user_list.registerDate', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'user_list.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="14">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php 
                    foreach ($this->items as $i => $item) : 
					$canEdit   = $this->canDo->get('core.edit');
					$canChange = $loggeduser->authorise('core.edit.state',	'com_users');

					// If this group is super admin and this user is not super admin, $canEdit is false
					if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin'))
					{
						$canEdit   = false;
						$canChange = false;
					}
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php if ($canEdit) : ?>
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							<?php endif; ?>
						</td>
						<td>
							<div class="name">
							<?php if($item->block == 1) { ?>
								<?php if ($canEdit) : ?>
									<a style="color:#008040" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>">
										<?php echo $this->escape($item->name); ?></a>
								<?php else : ?>
									<?php echo $this->escape($item->name); ?>
								<?php endif; ?>
							<?php } else {?>
								<?php if ($canEdit) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>">
										<?php echo $this->escape($item->name); ?></a>
								<?php else : ?>
									<?php echo $this->escape($item->name); ?>
								<?php endif; ?>
							<?php } ?>
							</div>
							<div class="btn-group">
								<?php echo JHtml::_('users.filterNotes', $item->note_count, $item->id); ?>
								<?php echo JHtml::_('users.notes', $item->note_count, $item->id); ?>
								<?php echo JHtml::_('users.addNote', $item->id); ?>
							</div>
							<?php echo JHtml::_('users.notesModal', $item->note_count, $item->id); ?>
							<?php if ($item->requireReset == '1') : ?>
								<span class="label label-warning"><?php echo JText::_('COM_USERS_PASSWORD_RESET_REQUIRED'); ?></span>
							<?php endif; ?>
							<?php if (JDEBUG) : ?>
								<div class="small"><a href="<?php echo JRoute::_('index.php?option=com_users&view=debuguser&user_id=' . (int) $item->id);?>">
								<?php echo JText::_('COM_USERS_DEBUG_USER');?></a></div>
							<?php endif; ?>
						</td>
						
						<td class="center">
							<?php if ($canChange) : ?>
								<?php
								$self = $loggeduser->id == $item->id;
								echo JHtml::_('jgrid.state', JHtmlUsers::blockStates($self), $item->block, $i, 'users.', !$self);
								?>
							<?php else : ?>
								<?php echo JText::_($item->block ? 'JNO' : 'JYES'); ?>
							<?php endif; ?>
						</td>
						
                        <td class="center">
							<?php echo $item->language_user;?>
						</td>
                        
                        <td class="center">
							<?php 
                                if ($item->user_retail_group == 'user_opticoach') {
                                    echo "Opticoach";
                                } elseif ($item->user_retail_group == 'user_independent') {
                                    echo "Independent";
                                } else {
                                    echo "Independent";
                                }
                            ?>
						</td>

                        <td class="center">
                            <?php echo $item->user_code;?>
                        </td>
                        
						<td class="center">
							<?php if (substr_count($item->group_names[0]->title, "\n") > 1) : ?>
								<span class="hasTooltip" title="<?php echo JHtml::tooltipText(JText::_('COM_USERS_HEADING_GROUPS'), nl2br($item->group_names[0]->title), 0); ?>"><?php echo JText::_('COM_USERS_USERS_MULTIPLE_GROUPS'); ?></span>
							<?php else : ?>
								<?php echo nl2br($item->group_names[0]->title); ?>
							<?php endif; ?>
						</td>
                        
                        
                        
						<td class="center hidden-phone">
							<?php echo JStringPunycode::emailToUTF8($this->escape($item->email)); ?>
						</td>
						<td class="center hidden-phone">
							<?php if ($item->lastvisitDate != '0000-00-00 00:00:00'):?>
								<?php echo JHtml::_('date', $item->lastvisitDate, 'Y-m-d H:i:s'); ?>
							<?php else:?>
								<?php echo JText::_('JNEVER'); ?>
							<?php endif;?>
						</td>
						<td class="center hidden-phone">
							<?php echo JHtml::_('date', $item->registerDate, 'Y-m-d H:i:s'); ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php //Load the batch processing form. ?>
		<?php echo $this->loadTemplate('batch'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
