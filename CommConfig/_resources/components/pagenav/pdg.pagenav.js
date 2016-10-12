//////////////////////////////////////////////////////////////////////
/*If PDGJS namespace does not exist-create it
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav={};
PDG.page_nav={};
PDG.page_nav.tabDispatch=null;

(function() {
/*
    var button = document.createElement('button');
    button.innerHTML = 'add tab';

    YAHOO.util.Event.on(button, 'click', function(ev){PDG.page_nav.addTab();});
    PDG.page_nav.tabView.appendChild(button);
    YAHOO.log("The example has finished loading; as you interact with it, you'll see log messages appearing here.", "info", "example");
	//YAHOO.util.Event.onContentReady(function(ev){PDG.section_nav.init();}); 
	*/
	//YAHOO.util.Event.onDOMReady(function(ev){PDG.page_nav.init();});
	//need full window loaded before we start...
	YAHOO.util.Event.addListener(window, 'load',function(ev){PDG.page_nav.init();}); 
	//top.pdggarbageframe.document.write("");
})();

//////////////////////////////////////////////////////////////////////
/*PDG.page_nav.init 
*/
//////////////////////////////////////////////////////////////////////
PDG.page_nav.init= function()
{
	if(!PDG.isObject(PDG.mainframe))
		return;//no nav possible
	
	PDG.page_nav.tabDispatch=PDG.page_nav.findTabDispatch();
	if(PDG.mainFrameLevel==1)
	{			
		PDG.page_nav.iframecounter=0;
		var pgTabs=YAHOO.util.Dom.get('page-container-tabs');
		if(pgTabs==null || pgTabs==undefined)
			return;
		PDG.page_nav.tabView = new YAHOO.widget.TabView('page-container');
		PDG.page_nav.tabView.on('activeTabChange', function(ev) {
			PDG.section_nav.ontabchange();
		});
	}
	
	PDG.section_nav.init();
	PDG.page_nav.initTopLinks();
}
//////////////////////////////////////////////////////////////////////
/*initTopLinks
	the return value is the frame id that should be used as a target
*/
//////////////////////////////////////////////////////////////////////
PDG.page_nav.initTopLinks= function()
{
	var tags=$("#pdg-pagetoplinks a");
	if(!PDG.isObject(tags))return;
	if(tags.length <=0)return;
	
	var vContainer=YAHOO.util.Dom.get('page-sections-cntainer');	
	var i;
	var vCurrent=null;
	if(vContainer.childNodes.length > 0)
		vCurrent=vContainer.childNodes[0];
		
	for(i=0;i<tags.length;i++)
	{
		if(vCurrent)
		{
			vContainer.insertBefore(tags[i],vCurrent);
			$(tags[i]).addClass("pdg-pagetoplink");
		}
	}
}
//////////////////////////////////////////////////////////////////////
/*page_nav.addDynaTab 
	the return value is the frame id that should be used as a target
*/
//////////////////////////////////////////////////////////////////////
PDG.page_nav.addDynaTab= function()
{
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event;
	
	//We create it ourselves....
	++PDG.page_nav.iframecounter;
	//iframetest3.htm
	var labelText = '<span class="tblabel71">Loading...&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="loading" title="This tabs content is loading"></span>';
	var frameID='pdginlineframe'+PDG.page_nav.iframecounter;
	var cntText = '<div class="page-section-frame"><iframe name="'+frameID+'" id="'+frameID+'" width="100%" style="width:100%;height:400px" frameborder="0" scrolling="no"></iframe></div>';
	var content = cntText;
	//note 
	var vTab=new YAHOO.widget.Tab({ label: labelText, content: content,active:true });
	//add the frame id to it...
	vTab.frameID=frameID;
	PDG.page_nav.fixTab(vTab);
	PDG.page_nav.tabView.addTab(vTab);
	
	$(Dom.get(frameID)).load(function()
		{
			PDG.page_nav.frameLoaded(vTab);
			return true;
		}
	);	
	
	return frameID;
}
//////////////////////////////////////////////////////////////////////
/*page_nav.addTab : page tab has been changed
*/
//////////////////////////////////////////////////////////////////////
PDG.page_nav.frameLoaded=function(tab)
{
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event;
	var vEM=tab.getElementsByClassName('loading')[0];
	var vLbl=tab.getElementsByClassName('tblabel71')[0];

	if(PDG.isObject(vEM))
	{
		vEM.className='close';
		vEM.title='Click to close this tab';
		Event.on(vEM, 'click',function(ev){PDG.page_nav.tabClose(ev,tab);});
	}
	
	if(PDG.isObject(vLbl))
	{
		var sTitle=String(Dom.get(tab.frameID).contentWindow.document.title);
		$(vLbl).attr("title",sTitle);
		if(sTitle.length > 32)
		{			
			//chop it..
			sTitle=sTitle.substring(0,40)+'...';			
		}
		$(vLbl).html(sTitle);
	}

	return true;
}

//////////////////////////////////////////////////////////////////////
/*page_nav.addTab : page tab has been changed
*/
//////////////////////////////////////////////////////////////////////
PDG.page_nav.tabClose=function(ev,tab)
{
	YAHOO.util.Event.preventDefault(ev);
	PDG.page_nav.tabView.removeTab(tab); 
}
//////////////////////////////////////////////////////////////////////
/*page_nav.addTab : page tab has been changed
*/
//////////////////////////////////////////////////////////////////////
PDG.page_nav.fixTab=function(vTab)
{
	var i;
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event;

	var b = document.createElement('b');
	var dv = document.createElement('DIV');
	var em=vTab.get('element');

	if(em.childNodes.length <=0)return;
	while(em.childNodes.length>0)
		dv.appendChild(em.childNodes[0]);
		
	em.appendChild(b);
	em.appendChild(dv);
}
//////////////////////////////////////////////////////////////////////
/*PDG.findTabDispatch : find the top-level page who is hosting all the tabs....
*/
//////////////////////////////////////////////////////////////////////
PDG.page_nav.findTabDispatch=function()
{
	//is it us?
	if(PDG.mainFrameLevel==1)
		return PDG.page_nav;//yes
		
	try
	{
		//assumes we have a parent...
		var nLookup=0;
		var vPos=window.parent;	
		while(PDG.isObject(vPos) && nLookup <15)
		{
			if(PDG.isObject(vPos.PDG))
			{				
				if(PDG.isObject(vPos.PDG.page_nav))
				{
					if(PDG.isObject(vPos.PDG.page_nav.tabDispatch))
						return vPos.PDG.page_nav.tabDispatch;			
				}
			}	
			++nLookup;			
			vPos=vPos.parent;			
		}

	}
	catch(err)
	{
		//Handle errors here
		return null;
	}
	
	return null;
}
//////////////////////////////////////////////////////////////////////
/*section_nav.ontabchange : page tab has been changed
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav.ontabchange= function()
{
	PDG.iframe.updateFrame(true);//we are in an iframe
}
//////////////////////////////////////////////////////////////////////
/*section_nav.onclick : initialize page section navigation
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav.onclick= function(el)
{
	var Dom = YAHOO.util.Dom;
	var vSection=Dom.get(el.getAttribute('tsid'));
	var vSectionContainer=null;
	if(vSection != undefined && vSection.parentNode != undefined)
		vSectionContainer=vSection.parentNode;
	if(vSectionContainer==null)return;//bad

	//make it the current one...
	PDG.section_nav.setCurrent(vSectionContainer,vSection,el);
	PDG.section_nav.movePtrTo(vSectionContainer.navPointer,el,true);
	
	
}
//////////////////////////////////////////////////////////////////////
/*section_nav.movePtrTo : initialize page section navigation
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav.movePtrTo= function(pPtr,elm,bAnimate)
{
	var xy = YAHOO.util.Dom.getXY(elm); 
	if(xy==false)
		return false;
	if(xy[0]==0 && xy[1]==0)
		return false;//ie
	var curxy = YAHOO.util.Dom.getXY(pPtr); 
	var region = YAHOO.util.Dom.getRegion(elm);
	var elmHeight = region.bottom - region.top;
	var elmWidth = region.right - region.left;
	var vDX=xy[0]+((elmWidth/2)-8);
	
	if(bAnimate)
	{
		var attributes = {
			points: { to: [vDX, curxy[1]] }
		};	
		
		var anim = new YAHOO.util.Motion(pPtr, attributes,.3, YAHOO.util.Easing.easeOut);
		anim.animate();	
	}
	else
	{
		var attributes = {
			points: { to: [vDX, curxy[1]] }
		};	
		var anim = new YAHOO.util.Motion(pPtr, attributes,0);
		anim.animate();		
	}
	return true;
}
//////////////////////////////////////////////////////////////////////
/*section_nav.setCurrent : sets current nav
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav.setCurrent= function(vSectionContainer,vSection,vLnk)
{
	var Dom = YAHOO.util.Dom;
	
//	if(vSectionContainer.curNavSection==null)
//		vSectionContainer.curNavSection=PDG.section_nav.findCurrent(vSectionContainer);

	if(vSectionContainer.curNavSection != null)
	{
		Dom.replaceClass(vSectionContainer.curNavSection,'page-section-visible', 'page-section');
		vSectionContainer.curNavSection.style.display='none';
		vSectionContainer.curNavSection=null;
	}
	
	if(vSectionContainer.curNavLink!=null)
	{
		Dom.removeClass(vSectionContainer.curNavLink.parentNode,'selected');
		vSectionContainer.curNavLink=null;	
	}
	
	vSectionContainer.curNavSection=vSection;
	vSectionContainer.curNavLink=vLnk;
	
	Dom.replaceClass(vSection,'page-section','page-section-visible');
	Dom.addClass(vLnk.parentNode,'selected');
	vSection.style.display='block';
	
	//debugtext
	var curxy = YAHOO.util.Dom.getXY(PDG.iframe.iframeAnchorElm); 
	var height=Math.round(curxy[1])+1;	

	PDG.iframe.updateFrame(true);//we are in an iframe
	//PDG.iframe.iframeElm.parentNode.style.display='none';
	if(PDG.isObject(PDG.iframe.iframeElm))
	{
		//alert("test!");
		//trigger rendering
		PDG.iframe.iframeElm.parentNode.style.height=height+'px';		
		//This is changed back to fluidity in PDG.mainframe.updateMainSize
		//we set the height be variable after a timeout (this allows page to render before fixing height problems)
		setTimeout(function(){PDG.iframe.iframeElm.parentNode.style.height='';},500);
	}
	//update tracker position
	PDG.page.Tracker.sectionid=String(vSection.id);
}
//////////////////////////////////////////////////////////////////////
/*section_nav.init : initialize page section navigation
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav.init= function()
{
	var i,j;
	var vPtr;
	var vLnk=null;
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event;	

	var sections=YAHOO.util.Selector.query('div.page-sections');
	var section_links = YAHOO.util.Selector.query('div.page-sections-menu ul li a'); 
	
	PDG.section_nav.section_links=section_links;
	PDG.section_nav.sections=sections;

	for(i=0;i<sections.length;i++)
	{
		//create ptr for it...
		vPtr=document.createElement('DIV');
		
		if(PDG.mainFrameLevel==1)
			vPtr.className = 'page-section-pointer';
		else
			vPtr.className = 'page-section-pointer-cp';
			
		vPtr.innerHTML = '';
		vPtr.id = Dom.generateId();	
		Dom.insertBefore(vPtr,Dom.getFirstChild(sections[i]));
		sections[i].navPointer=vPtr;
		sections[i].curNavSection=null;
		sections[i].curNavLink=null;
		sections[i].navPointerInit=false;
		if(section_links.length==0)
			sections[i].navPointer.style.display='none';
	}	
	
	//hook up nav clicks	
	var bDefault=(PDG.page.Tracker.sectionid=='default');
	
	if(bDefault && section_links.length > 0)
		PDG.section_nav.switchto(section_links[0]);
	
	for(i=0;i<section_links.length;i++)
	{
		Event.on(section_links[i], 'click', function(ev) {
			PDG.section_nav.onclick(Event.getTarget(ev));
		});
		
		if(!bDefault && section_links[i].getAttribute('tsid')==PDG.page.Tracker.sectionid)
			PDG.section_nav.switchto(section_links[i]);
	}
}
//////////////////////////////////////////////////////////////////////
/*section_nav.switchto : copy of on-click minus the animation part...
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav.switchto= function(el)
{
	var Dom = YAHOO.util.Dom;
	var vSection=Dom.get(el.getAttribute('tsid'));
	var vSectionContainer=null;
	if(vSection != undefined && vSection.parentNode != undefined)
		vSectionContainer=vSection.parentNode;
	if(vSectionContainer==null)return;//bad

	//make it the current one...
	PDG.section_nav.setCurrent(vSectionContainer,vSection,el);
	PDG.section_nav.movePtrTo(vSectionContainer.navPointer,el,false);
}
//////////////////////////////////////////////////////////////////////
/*section_nav.findLinkSrc : find matching link for given section
*/
//////////////////////////////////////////////////////////////////////
PDG.section_nav.findLinkSrc= function(elmLinks,elSection)
{
	var i;
	var Dom = YAHOO.util.Dom;
	if(elmLinks.length<=0)return null;
	for(i=0;i<elmLinks.length;i++)
	{
		if(Dom.get(Dom.get(elmLinks[i].getAttribute('tsid')))==elSection)
			return elmLinks[i];
	}
	
	return null;
}