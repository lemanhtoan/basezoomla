<?php
/**
 * @version     1.0.0
 * @package     com_product
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// no direct access
defined('_JEXEC') or die;
$items = $this->items;
$i = 1;
?> 
<div class="container">
	<div class="row">
		<div class="col-md-12 slide-product">
			<div class="main-slide">
			<?php foreach($items as $item): ?>
				<?php if($i <= 10): ?>
				<a href="<?php echo JRoute::_('index.php?option=com_product&view=product&id='.$item->id) ?>" title="<?php echo $item->name ?>">
					<h2><?php echo $item->category_id_title ?></h2>
					<p><?php echo $item->name ?></p>
					<img src="<?php echo JUri::base().$item->image ?>" alt="<?php echo $item->name ?>">
				</a>
			<?php endif; ?>
			<?php $i++; endforeach; $i = 1; ?>
			</div>
			<div class="pagination-category-slide"></div>
		</div>
		<div class="list-product">
			<div class="views-content">
				<?php foreach($items as $item): ?>
				<?php if($i <= 10): ?>
				<div class="col-md-4 product">
					<a href="<?php echo JRoute::_('index.php?option=com_product&view=product&id='.$item->id) ?>" title="<?php echo $item->name ?>" class="thumb product-item">
						<img src="<?php echo JUri::base().$item->image ?>" alt="<?php echo $item->name ?>" class="lazy-img">
						<img src="<?php echo JUri::base().$item->image_hover ?>" alt="<?php echo $item->name ?>" class="hover-img">
					</a>
					<h2 class="product-name">
						<a href="<?php echo JRoute::_('index.php?option=com_product&view=product&id='.$item->id) ?>" title="<?php echo $item->name ?>"><?php echo $item->name ?></a>
					</h2>
				</div> 
				<?php endif; ?>
				<?php $i++; endforeach; $i = 1; ?>
			</div>
			<!--<div class="pagination">
				<a href="#" class="prev-page"><i class="fa fa-angle-left"></i></a>
				<div class="list-page">
					<a href="#">1</a>
					<a href="#" class="active">2</a>
					<a href="#">3</a>
					<a href="#">4</a>
					<a href="#">5</a>
				</div>
				<a href="#" class="next-page"><i class="fa fa-angle-right"></i></a>
			</div>-->
		</div>
	</div>
</div>