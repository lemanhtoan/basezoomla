//////////////////////////////////////////////////////////////////////
/*add tooltips to namespace
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips={};
PDG.tooltips.mgr=null;
PDG.tooltips.container=null;
PDG.tooltips.mouse_x=0;
PDG.tooltips.mouse_y=0;
PDG.tooltips.current=null;
PDG.tooltips.mo_timers=[];

//////////////////////////////////////////////////////////////////////
/*init
*/
//////////////////////////////////////////////////////////////////////
(function() {
	YAHOO.util.Event.addListener(window, 'load',function(ev){PDG.tooltips.init();}); 
})();

//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.init 
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.init= function()
{
	var Event=YAHOO.util.Event;
	PDG.tooltips.container = new PDG.widget.TooltipContainer(false);
	PDG.tooltips.attachHandlers();
	Event.addListener(window.document,'mousemove',PDG.tooltips.docMouseMove,window,true);
	Event.addListener(window.document,'mouseout',PDG.tooltips.docMouseOut,window,true);
}
//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.getContent 
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.getContent= function(tipID)
{
	var Dom = YAHOO.util.Dom;
	var tar=Dom.get(tipID);
	if(tar != undefined)return tar.innerHTML;
	return 'undefined tooltip content';
}
//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.attachHandlers
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.attachHandlers= function()
{
	var elements,i;
	var Event=YAHOO.util.Event;
	
	//pdgtip0 types
	elements=$('.pdgtip0');
	for(i=0;i<elements.length;i++)
	{
		//set the tip-type
		elements[i].pdg_tiptype=0;
		//save its text..
		elements[i].pdg_tiptext=elements[i].getAttribute("title");
		//add mouse variable...
		elements[i].pdg_tip_hasmouse=false;		
		//remove title attribute
		elements[i].removeAttribute("title");		
		//hookup callbacks
		Event.addListener(elements[i], 'mouseover',PDG.tooltips.onMouseOver,window,true);
		Event.addListener(elements[i], 'mouseout',PDG.tooltips.onMouseOut,window,true);		
	}
	//pdgtip1 types
	elements=$('.pdgtip1');
	for(i=0;i<elements.length;i++)
	{
		//set the tip-type
		elements[i].pdg_tiptype=1;
		//add mouse variable...
		elements[i].pdg_tip_hasmouse=false;		
		//remove title attribute
		elements[i].removeAttribute("title");
		//hookup callbacks
		Event.addListener(elements[i], 'mouseover',PDG.tooltips.onMouseOver,window,true);
		Event.addListener(elements[i], 'mouseout',PDG.tooltips.onMouseOut,window,true);
	}
}
//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.containerHidden
	called by class when hidden
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.containerHidden= function(vContainer)
{
		PDG.tooltips.current=null;
}
//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.docMouseMove
	save the x and y
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.docMouseMove= function(e, obj)
{
	PDG.tooltips.mouse_x=YAHOO.util.Event.getPageX(e);
	PDG.tooltips.mouse_y=YAHOO.util.Event.getPageY(e);
}
//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.docMouseMove
	save the x and y
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.docMouseOut= function(e, obj)
{
	while(PDG.tooltips.mo_timers.length>0)
		clearTimeout(PDG.tooltips.mo_timers.pop());
	PDG.tooltips.current=null;
	PDG.tooltips.container.hide();	
}

//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.onMouseOver
	mouse have moved over a tip area
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.onMouseOver= function(e, obj)
{
	var elm=YAHOO.util.Event.getTarget(e);
	elm.pdg_tip_hasmouse=true;	
	if(PDG.tooltips.current==elm)
		return;//already the current
		
	while(PDG.tooltips.mo_timers.length>0)
		clearTimeout(PDG.tooltips.mo_timers.pop());
	var x=PDG.tooltips.mouse_x;
	var y=PDG.tooltips.mouse_y;
	PDG.tooltips.mo_timers.push(setTimeout(function(){
													
		if(PDG.tooltips.isMouseOverElement(elm,0))
		{			
			PDG.tooltips.current=elm;
			PDG.tooltips.container.currentElm=elm;
			
			if(elm.pdg_tiptype==0)			
				PDG.tooltips.container.setContent(elm.pdg_tiptext);
			else
			{
				if($(elm).attr("tipsrc")=='ttip-notip')
					return;
					
				PDG.tooltips.container.setContent(PDG.tooltips.getContent($(elm).attr("tipsrc")));
			}
				
			PDG.tooltips.container.setPosition(PDG.tooltips.mouse_x+5,PDG.tooltips.mouse_y+5);
			PDG.tooltips.container.show();
		}
		
	},1000));

}
//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.isMouseOverElement
	check to see if mouse is over something...padding allows a bit of slop
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.isMouseOverElement=function(elm,padding)
{
	var region = YAHOO.util.Dom.getRegion(elm);
	
	var x=PDG.tooltips.mouse_x;
	var y=PDG.tooltips.mouse_y;
	
	region.left-=padding;
	region.right+=padding;
	region.top-=padding;
	region.bottom+=padding;
	
	if(x<region.left)return false;
	if(y<region.top)return false;
	if(x>region.right)return false;
	if(y>region.bottom)return false;
	
	return true;
}
//////////////////////////////////////////////////////////////////////
/*PDG.tooltips.onMouseOout
	mouse has moved away from a tip area
*/
//////////////////////////////////////////////////////////////////////
PDG.tooltips.onMouseOut= function(e, obj)
{
	var elm=YAHOO.util.Event.getTarget(e);
	elm.pdg_tip_hasmouse=false;	
	setTimeout(function(){
			
		if(PDG.tooltips.container.mouseOver)
			return;
			
		PDG.tooltips.current=null;
		PDG.tooltips.container.hide();			
	},200);
}
//////////////////////////////////////////////////////////////////////	
/*TooltipContainer class def
*/
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer=function (bRight)
{	
	this.container=null;
	this.bodyContent=null;
	this._construct(bRight);
	this.mouseOver=false;
	this.currentElm=null;
}
//////////////////////////////////////////////////////////////////////	
/*TooltipContainer _construct
*/
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype._construct=function(bRight)
{
	var Event=YAHOO.util.Event;
	//Create the container
	this.container=document.createElement("DIV");
	this.container.style.position="absolute";
	this.container.style.display='none';
	this.container.className=(bRight)?'pdg-tooltip-right':'pdg-tooltip';
	var sTC=[];
	sTC.push('<div class="pdg-tooltip-top"></div>');
	sTC.push('<div class="pdg-tooltip-bottom"></div>');

	$(this.container).html(sTC.join(""));
	
	document.body.appendChild(this.container);	

	this.bodyContent=$('div.pdg-tooltip-top',this.container);
	
	Event.addListener(this.container, 'mouseover',this.mouseOver,this,true);
	Event.addListener(this.container, 'mouseout',this.mouseOut,this,true);
	Event.addListener(this.container,'mousemove',this.mouseMove,this,true);
}
//mouseOver
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype.mouseMove=function(e)
{	
	if(this.currentElm==null)
		return;
		
	if(!PDG.tooltips.isMouseOverElement(this.currentElm,4))
		this.hide();
}
//mouseOver
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype.mouseOver=function(e)
{	
	this.mouseOver=true;
}
//mouseOut
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype.mouseOut=function(e)
{	
	this.mouseOver=false;
	var self=this;
	setTimeout(function(){

		if(self.currentElm==null)
			self.hide();
		else if(!self.currentElm.pdg_tip_hasmouse)
			self.hide();
	},100);
}
//TooltipContainer class...
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype.setPosition=function(x,y)
{	
	this.container.style.left=x+'px';
	this.container.style.top=y+'px';
}
//TooltipContainer class...
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype.setContent=function(sTxt)
{	
	$(this.bodyContent).html(sTxt);
}
//TooltipContainer class...
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype.hide=function()
{	
	this.currentElm=null;
	this.container.style.display='none';
	this.mouseOver=false;
	PDG.tooltips.containerHidden(this);
}
//TooltipContainer class...
//////////////////////////////////////////////////////////////////////	
PDG.widget.TooltipContainer.prototype.show=function()
{	
	this.container.style.display='block';
}