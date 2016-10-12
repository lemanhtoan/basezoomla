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
$categories = $this->categories;
?> 
<div class="container">
	<div class="row">
		<div class="collection-views">
		<?php if(count($categories) > 0): $i = 1; ?>
			<?php 
				foreach($categories as $category):
					$params = json_decode($category->params);
					if($i == 1):
			?>
			<div class="colection-item full-block">
				<a href="<?php echo JRoute::_('index.php?option=com_product&view=products&id='.$category->id) ?>" title="<?php echo $category->title ?>">
					<img class="lazy-image" src="<?php echo JUri::base().$params->image ?>" alt="<?php echo $category->title ?>">
					<p class="button"><?php echo $category->title ?></p>
				</a>
			</div>
			<div class="full-block"> 
			<?php else: ?>
				<div class="colection-item col-2-block">
					<a href="<?php echo JRoute::_('index.php?option=com_product&view=products&id='.$category->id) ?>" title="<?php echo $category->title ?>">
						<img class="lazy-image" src="<?php echo JUri::base().$params->image ?>" alt="<?php echo $category->title ?>">
						<p class="button"><?php echo $category->title ?></p>
					</a>
				</div>
			<?php endif; $i++; endforeach; ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>