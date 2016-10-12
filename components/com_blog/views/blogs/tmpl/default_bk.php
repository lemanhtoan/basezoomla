<?php
/**
 * @version     1.0.0
 * @package     com_blog
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
// JHtml::_('bootstrap.tooltip');
// JHtml::_('behavior.multiselect');
// JHtml::_('formbehavior.chosen', 'select');
 
 //echo "<pre>"; var_dump($this->items);  die;
 
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_blog');
$canEdit = $user->authorise('core.edit', 'com_blog');
$canCheckin = $user->authorise('core.manage', 'com_blog');
$canChange = $user->authorise('core.edit.state', 'com_blog');
$canDelete = $user->authorise('core.delete', 'com_blog');
?>

<?php
$orginalData = array();
$list_ids = array();
foreach($this->items as $item) 
{
    $mode[$item->id] = array
    (
        'mode' => $item->mode,
        'image' => $item->image,
        'created_date' => $item->created_date        
    );
    array_push($list_ids, $item->id);
}

JLoader::import('helpers.blog',JPATH_BASE . '/components/com_blog');
$data = BlogFrontendHelper::getTranslationForCurrentLanguage('blog','', implode(',', $list_ids ), array()); //$orginalData


$blog_translate = $data;  

$blogs = array_merge_recursive_replace($blog_translate, $mode);

$blogs = array_reverse($blogs, true); // sort

function array_merge_recursive_replace() {
    $arrays = func_get_args();
    $base = array_shift($arrays);
    foreach ($arrays as $array) {
        reset($base);
        while (list($key, $value) = @each($array)) {
            if (is_array($value) && @is_array($base[$key])) {
                $base[$key] = array_merge_recursive_replace($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }
    }
    return $base;
}


?>

<form action="<?php echo JRoute::_('index.php?option=com_blog&view=blogs'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="container">
        <div class="row">
        	<div class="blog-views accordion-views">
        		<div class="views-content">
                    <?php 
                        $i = 0;   
                        $len = count($blogs);
                        foreach ($blogs as $item) {     
                        if(sizeof($item) > 3) :     
                            if ( $i == 0 ) { // first                            
                            if(isset($item['name'])) { 
                    ?>
                    <article>
                        <?php if(strlen($item['image']) > 0) : ?>
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        <?php endif; ?>
                        <div class="post-time col-md-1 col-md-push-2">
                            <?php 
                                $month = date("F",strtotime($item['created_date']));
                                $day = date("d",strtotime($item['created_date']));
                            ?>
                            <p><?php echo $month; ?><br/><?php echo $day; ?></p>
                        </div>
                        <div class="col-md-7 col-md-push-2">
                            <h2 class="article-title"><a href="#"><?php echo $item['name']; ?></a></h2>
                            <div class="blog-add">
                                <?php echo $item['description']; ?>
                                <?php echo $item['content']; ?>
                            </div>
                        </div>
                    </article>                            
                    <?php
                        }  } else {  if(isset($item['name'])) { // last ?>
                    <article>
                            <?php if(strlen($item['image']) > 0) : ?>
                              <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                            <?php endif; ?>
                            <div class="post-time col-md-1 col-md-push-2">
                                <?php 
                                    $month = date("F",strtotime($item['created_date']));
                                    $day = date("d",strtotime($item['created_date']));
                                ?>
                                <p><?php echo $month; ?><br/><?php echo $day; ?></p>
                            </div>
                            <div class="col-md-7 col-md-push-2">
                                <h2 class="article-title"><a href="#"><?php echo $item['name']; ?></a></h2>
                                <div class="teaser">
                                <?php if(strlen($item['description']) > 0 ) { ?>
                                    <?php echo $item['description']; ?></div>
                                <?php } else {?>
                                    <?php echo substr($item['content'],0, 300); ?></div>
                                <?php } ?>
                                <div class="article-body">
                                    <?php echo $item['content']; ?>
                                </div>
                                <a href="javascript:void(0)" class="read-more"><?php echo JText::_('READ_MORE');?></a>
                            </div>
                        </article>                            
                        <?php 
                            } }
                            $i++;
                       endif; } 
                    ?>
                </div>
                  
        		<div class="pagination">
                    <?php echo $this->pagination->getPagesLinks(); ?>
                    <?php //echo $this->pagination->getListFooter(); ?>
        		</div>
            </div>  
        </div>
    </div>
</form>