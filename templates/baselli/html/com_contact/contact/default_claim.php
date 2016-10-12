<script>
	var del_msg = "<?php print JText::_("CONFIRM_DELETE_CLAIM"); ?>";
	var add_msg = "<?php print JText::_("INPUT_CLAIM_FIELD_MESSAGE"); ?>";
</script>
<div id="dv-claim">
    <div class="form-row"><?php echo JText::_("CLAIMED");?> <span id="claimed-products">0</span> <?php echo JText::_("PRODUCT");?></div>
    <div class="form-row" id="tbody-claim"></div>
</div>
<div id="dv-tmp-claim" style="display:none"></div>
<div class="form-row"><?php echo JText::_("CLAIM_PRODUCT");?></div>
<div class="form-row">
	<div><?php echo JText::_("TYPE_OF_ISSUE");?></div>
</div>
<div class="form-row checkbox checkbox-1">
	<?php echo $this->form->getInput("type_of_issue_1"); ?>
	<span class="beauty-checkbox" id="box-1" onclick="checked_element('#jform_type_of_issue_1', '#box-1')"></span><?php echo $this->form->getLabel("type_of_issue_1"); ?>
</div>
<div class="form-row checkbox">
	<?php echo $this->form->getInput("type_of_issue_2"); ?>
	<span class="beauty-checkbox" id="box-2" onclick="checked_element('#jform_type_of_issue_2', '#box-2')"></span><?php //echo $this->form->getLabel("type_of_issue_2"); ?>
	<label id="jform_type_of_issue_2-lbl" for="jform_type_of_issue_2" class=""><?php echo stripcslashes(JText::_('COLOR_ISSUE')) ?></label>
</div>
<div class="form-row">
	<div class="col-xs-12 col-md-6" style="padding-left:0">
		<?php echo $this->form->getInput("name_of_product"); ?>
    </div>
    <div class="col-xs-12 col-md-6 color-input-field" style="padding-right:0">
		<?php echo $this->form->getInput("color_of_product"); ?>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<div class="form-row"><?php echo $this->form->getInput("invoice_number"); ?></div>
<div class="form-row"><?php echo $this->form->getInput("description"); ?></div>
<div id="hdn-claim-data-tmp" style="display:none"></div>
<div class="form-row action claim-action">
    <input style="width: auto; padding: 0 15px; min-width: 100px" type="button" class="button submit" value="<?php echo JText::_("CLAIM_BUTTON"); ?>" onClick="addClaim();"></div>
<div class="clearfix">&nbsp;</div>