<?php
	$user = JFactory::getUser();
	//$array = json_decode($_SESSION['__vm']['vmcart']); 
	//echo "<pre>"; var_dump($array->cartProductsData); die;
?>
<style type="text/css">
	.order_page .page_title 
	{
		color:#333333;
		text-transform: uppercase;
		font-size: 14pt;
		padding: 0;
		margin: 0 0 10px;
		font-weight: normal;
		line-height: 15px;
	}
	.order_page .page_title_block
	{
		position: relative;
	}
	.order_page .page_title::after
	{
		width:30px;
		display: block;
		height:3px;
		background: #333333;
		position: absolute;
		top:27px;
		left:1px;
		content: "";
	}
	.myorder_grid
	{
		width: 100%;
		border-collapse: collapse;
		margin-top: 30px;
	}
	.myorder_grid_header td
	{
		text-align: center;
		padding-bottom: 7px;
		border-width: 3px;
		border-bottom-style:double;
		border-color:#dadada;
	}
	.myorder_grid_row td{
		padding:25px;
		text-align: center;
	}
	.myorder_grid_row_delete_item
	{
		text-decoration: none;
		height: 14px;
		width: 14px;
		display: inline-block;
		text-align: center;
	}
	.myorder_grid_row_delete_item:hover{
		text-decoration: none;
	}
	#system-message-container
	{
		display: none;
	}
	.myorder_grid_last_row{
		border-top: 1px solid #dadada;
	}
	.total_price {
		font-size:30px;
		font-weight: bold;
	}
	.quantity_container {
		border-bottom: 1px solid #dadada;
		width:150px;
		padding:5px;
		margin:0 auto;
	}
	.quantity_container span{
		color:#333333;
		font-size:20px;
	}
	.quantity_container input{
		border:0;
		width:100px;
		text-align: center;
		color:#bcbcbc;
	}
	.additional_block {
		color:#666666;
		font-style: italic;
		margin-top: 34px;
		line-height: 22px;
	}
	.user_info_block
	{
		margin-top: 40px;
	}
	.avatar_block {
		margin-top: 32px;
		float:left;
		clear:both;
		margin-bottom: 32px;
	}
	.user_basic_info_block {
		clear:both;
	}
	.basic_info_title {
		color:#666666;
		text-transform: uppercase;
		padding:0 0 15px;
		margin:0;
	}
	.basic_info_row{
		padding:0;
		margin:0;
		line-height: 22px;
		padding-bottom: 23px;
		color:#333333;
	}
	.delivery_address_block {
		padding-top:10px;
	}
	.mycart_sys_buttons 
	{
		margin-top: 73px;	
	}
	.continue_shopping {
		background: #fff;
		height:50px;
		line-height: 30px;
		border:1px solid #333333;
		color: #333333;
	    width: 170px;
	    border-radius: 25px;
		font-size: 16px;
	}
	.preview_order 
	{
		background: #333333;
		height:50px;
		line-height: 30px;
		border:0;
		color: #dadada;
		border: 0;
	    width: 170px;
	    border-radius: 25px;
		font-size: 16px;
	}
	.continue_shopping, .preview_order{
		float:left;
	}
	.continue_shopping 
	{
		margin-right:30px;
	}
</style>
<div class="container order_page">
	<div class="row">
		
		<div class="page_title_block">
			<h2 class="page_title">My cart</h2>	
		</div>
	
		<table class="myorder_grid" id="myorder_grid">
			<tr class="myorder_grid_header">
				<td class="col_1"><?php echo JText::_('No') ?></td>
				<td class="col_2"></td>
				<td class="col_3"><?php echo JText::_('Name') ?></td>
				<td class="col_4"><?php echo JText::_('Color') ?></td>
				<td class="col_5"><?php echo JText::_('Price') ?></td>
				<td class="col_6"><?php echo JText::_('Quantity') ?></td>
				<td class="col_7"><?php echo JText::_('Total') ?></td>
				<td class="col_8"><?php echo JText::_('Delete') ?></td>
			</tr>
			
			<?php
				$items = array(
					array(
						'id' => 1, 
						'img' => 1, 
						'name' => 'Amore 1', 
						'color' => 'Model 1', 
						'price' => 1, 
						'quanlity' => 1, 
						'total' => 1, 
					), 
					array(
						'id' => 2, 
						'img' => 1, 
						'name' => 'Amore 2', 
						'color' => 'Model 2', 
						'price' => 1, 
						'quanlity' => 1, 
						'total' => 1, 
					), 
					array(
						'id' => 3, 
						'img' => 1, 
						'name' => 'Amore 3', 
						'color' => 'Model 3', 
						'price' => 1, 
						'quanlity' => 1, 
						'total' => 1, 
					), 
				);
				$app    = JFactory::getApplication();
				$path   = JURI::base(true).'/templates/'.$app->getTemplate().'/images/thumb/ad-1.jpg';
			?>	

			<?php foreach ( $items as $item ): ?>

			<tr class="myorder_grid_first_row myorder_grid_row">
				<td class="col_1"><?php echo $item['id'] ?></td>
				<td class="col_2"><img src="<?php echo $path ?>"/></td>
				<td class="col_3"><?php echo $item['name'] ?></td>
				<td class="col_4"><?php echo $item['color'] ?></td>
				<td class="col_5"><?php echo '$' . $item['price'] ?></td>
				<td class="col_6">
					<div class="quantity_container">
						<span class="quantity_container_subtract"></span>
						<input type="text" value="<?php echo $item['quanlity'] ?>"/> 
						<span class="quantity_container_plus"></span>
					</div>
				</td>
				<td class="col_7"><?php echo '$' . $item['total'] ?></td>
				<td class="col_8"><a href="" class="myorder_grid_row_delete_item">&nbsp;</a></td>
			</tr>

			<?php endforeach; ?>

			<tr class="myorder_grid_last_row myorder_grid_row">
				<td colspan="6"></td>	
				<td><strong class="total_price">$900</strong></td>	
				<td></td>	
			</tr>

		</table>

	</div>

	<div class="row">
		<div class="additional_block">
			<p><?php echo JText::_('Additional Information') ?></p>
			<textarea style="border:none;border-bottom:1px solid #dadada;width:568px;height:40px;"></textarea>
		</div>
	</div>
	<div class="row">
		<div class="user_info_block">
			<div class="page_title_block">
			<h2 class="page_title">User info</h2>	

			<div class="avatar_block">
				<img src="<?php echo JURI::base(true).'/templates/'.$app->getTemplate().'/images/thumb/user_avatar_default.png' ?> "/>		

			</div>
		</div>
	</div>

	<div class="row user_basic_info_block">
		<div class="col-md-3">
			<p class="basic_info_title">BASIC INFO</p>
			<p class="basic_info_row"><?php echo $user->name ?>Harry Tran</p>
			<p class="basic_info_row"><?php echo $user->email ?>Abc</p>
			<p class="basic_info_row"><?php echo $user->username ?>abc@gmail.com</p>
			<p class="basic_info_row"><?php echo $user->phone_number ?>0123456789</p>
		</div>
		<div class="col-md-3">
			<p class="basic_info_title">INVOICE ADDRESS</p>
			<p class="basic_info_row"><?php echo $user->name ?>Do Duc Duc, Ha Noi, 1008, Vietnam</p>

			<p class="basic_info_title delivery_address_block">Delivery  address</p>
			<p class="basic_info_row"><?php echo $user->name ?>Do Duc Duc, Ha Noi, 1008, Vietnam</p>
		</div>
		<div class="col-md-3">
			<p class="basic_info_title">Additional info</p>
			<p class="basic_info_row">lorem ipsum</p>
		</div>

		<div class="col-md-3">
			<div class="col-md-3"></div>	
		</div>

	</div>

	<div class="row mycart_sys_buttons">
		<div class="col-md-4 pull-right" style="text-align:right;">
			<button class="continue_shopping"><?php echo JText::_('Continue shopping') ?></button>
			<button class="preview_order"><?php echo JText::_('Preview Order') ?></button>
		</div>
	</div>

</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.quantity_container_subtract').click(function(e)
		{
			var element = jQuery(this).next();
			var current_qty = parseInt( jQuery(element).val() );
			if ( current_qty >= 1 ) current_qty -= 1;
			else current_qty = 0;
			jQuery(element).val(current_qty);
		});
		jQuery('.quantity_container_plus').click(function(e)
		{
			var element = jQuery(this).prev();
			var current_qty = parseInt(jQuery(element).val());
			current_qty += 1;
			jQuery(element).val(current_qty);
		});
	})

</script>