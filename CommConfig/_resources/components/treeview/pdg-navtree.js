//////////////////////////////////////////////////////////////////////
/*add tree nav to namespace
*/
//////////////////////////////////////////////////////////////////////
PDG.tree_nav={};

//////////////////////////////////////////////////////////////////////
/*PDG.tree_nav.init 
*/
//////////////////////////////////////////////////////////////////////
PDG.tree_nav.init= function()
{
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event,i,links;
	PDG.tree_nav.currentItem=null;
	
	$("#pdg-navtree").treeview();
	
	links=$("ul#pdg-navtree A");
	for(i=0;i<links.length;i++)
		Event.on(links[i], 'click',function(ev){PDG.tree_nav.itemClick(Event.getTarget(ev));});
}
//Add init routine to dom event 
PDG.callOnDomReady(PDG.tree_nav.init);

//////////////////////////////////////////////////////////////////////
/*PDG.tree_nav.init 
*/
//////////////////////////////////////////////////////////////////////
PDG.tree_nav.itemClick= function(elm)
{
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event,i,links;
	if(PDG.tree_nav.currentItem != null)
	{
		Dom.removeClass(PDG.tree_nav.currentItem,'nitem-selected');
		PDG.tree_nav.currentItem=null;
	}
	PDG.tree_nav.currentItem=elm;
	Dom.addClass(elm,'nitem-selected');
	//reset the main-frame tracker...
	PDG.mainframe.pageTracker.resetContents();
}