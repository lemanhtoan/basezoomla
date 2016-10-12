<?php
/*---------------------------------------------------------------
# Package - Joomla Template based on SD3 Framework   
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com
-----------------------------------------------------------------*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

function pagination_list_render($list) {
	// Initialize variables
	$html = '<ul>';
	if ($list['start']['active']==1)   $html .= $list['start']['data'];
	if ($list['previous']['active']==1) $html .= $list['previous']['data'];

	foreach ($list['pages'] as $page) {
		$html .= $page['data'];
	}

	if ($list['next']['active']==1) $html .= $list['next']['data'];
	if ($list['end']['active']==1)  $html .= $list['end']['data'];

	$html .= "</ul>";
	
	return $html;
}

function pagination_item_active(&$item) {
	
	$cls = '';
	
    //en
    if ($item->text == JText::_('Next')) { $item->text = "<i class='fa fa-angle-right'></i>"; $cls = "next-page";} 
    if ($item->text == JText::_('Prev')) { $item->text = "<i class='fa fa-angle-left'></i>"; $cls = "prev-page";}    
	if ($item->text == JText::_('Start')) { $item->text = "<i class='fa fa-angle-double-left'></i>"; $cls = "prev-page_start";}
    if ($item->text == JText::_('End'))   { $item->text = "<i class='fa fa-angle-double-right'></i>"; $cls = "next-page_end";}
    
    //fr
    if ($item->text == JText::_('Suivant')) { $item->text = "<i class='fa fa-angle-right'></i>"; $cls = "next-page";} 
    if ($item->text == JText::_('Prev')) { $item->text = "<i class='fa fa-angle-left'></i>"; $cls = "prev-page";}    
	if ($item->text == JText::_('Start')) { $item->text = "<i class='fa fa-angle-double-left'></i>"; $cls = "prev-page_start";}
    if ($item->text == JText::_('Fin'))   { $item->text = "<i class='fa fa-angle-double-right'></i>"; $cls = "next-page_end";}
    
    //ge
    if ($item->text == JText::_('Weiter')) { $item->text = "<i class='fa fa-angle-right'></i>"; $cls = "next-page";} 
    if ($item->text == JText::_('Prev')) { $item->text = "<i class='fa fa-angle-left'></i>"; $cls = "prev-page";}    
	if ($item->text == JText::_('Start')) { $item->text = "<i class='fa fa-angle-double-left'></i>"; $cls = "prev-page_start";}
    if ($item->text == JText::_('Ende'))   { $item->text = "<i class='fa fa-angle-double-right'></i>"; $cls = "next-page_end";}
    
    //it
    if ($item->text == JText::_('Avanti')) { $item->text = "<i class='fa fa-angle-right'></i>"; $cls = "next-page";} 
    if ($item->text == JText::_('Indietro')) { $item->text = "<i class='fa fa-angle-left'></i>"; $cls = "prev-page";}    
	if ($item->text == JText::_('Inizio')) { $item->text = "<i class='fa fa-angle-double-left'></i>"; $cls = "prev-page_start";}
    if ($item->text == JText::_('Fine'))   { $item->text = "<i class='fa fa-angle-double-right'></i>"; $cls = "next-page_end";}
	
    return "<li><a class=\"".$cls."\" href=\"".$item->link."\" title=\"".$item->text."\">".$item->text."</a></li>";
}

function pagination_item_inactive(&$item) {
	return "<li class=\"pagination-active\"><a>".$item->text."</a></li>";
}
?>