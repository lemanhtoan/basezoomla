//////////////////////////////////////////////////////////////////////
/*Establish page namespace ( page-level script)
*/
//////////////////////////////////////////////////////////////////////
PDG.page={};
PDG.page.appid=null;//unique page app id
PDG.page.responseFrame=null;
PDG.page.responseInputElm=null;
PDG.page.responseItem=null;
PDG.page.responseTrigger=null;
PDG.page.responseOTarget=null;
PDG.page.responseOHref=null;
PDG.page.responseItemType=0;
PDG.page.Tracker=null;

//////////////////////////////////////////////////////////////////////
/*login form init
*/
//////////////////////////////////////////////////////////////////////
$(document).ready(function(){
	PDG.page.init();
});

//////////////////////////////////////////////////////////////////////
/*PDG.page.init
*/
//////////////////////////////////////////////////////////////////////
PDG.page.pre_initialize= function()
{
	if(!PDG.isObject(PDG.mainframe))
		return;//nothing to do
	PDG.page.generate_appid();
	PDG.page.Tracker=PDG.mainframe.pageTracker.addPage(PDG.page.appid);
	window.history.forward(1);
	//alert(PDG.page.Tracker);
}
//////////////////////////////////////////////////////////////////////
/*PDG.page.init
*/
//////////////////////////////////////////////////////////////////////
PDG.page.init= function()
{
	if(!PDG.isObject(PDG.mainframe))
		return;//nothing to do
		
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event;
	PDG.formEffects.init(true);
	PDG.updateRunTimeData();
	
	//pdg-cshelp
	if(PDG.isObject($("#pdg-cshelp")[0]))
		Event.addListener(Dom.get('pdg-cshelp'),'click',function(){
			PDG.mainframe.doAlert('Context sensitive help placeholder','This is a placeholder.  It can be hidden.');
		},window,true);
		
	PDG.page.initFirstCheck();//has to come before initDynaTabs
	PDG.page.initDynaTabs();
	PDG.page.initBinaryUpdates();
	PDG.page.initUpdateReloads();
	//testing for IE:
	PDG.page.initResponseFrame();
	PDG.page.initEditors();
}
//////////////////////////////////////////////////////////////////////
/*PDG.page.generate_appid
	figures out this pages unique id for section-mnu tracking and display response callbacks
	note: the ids generated need to be compliant with node-names..
*/
//////////////////////////////////////////////////////////////////////
PDG.page.generate_appid= function()
{
	var sID=new String();
	if(PDG.mainFrameLevel>1)
	{
		//we are a child page embedded somewhere...
		var vFrame=PDG.iframe.findFrameElement(window.document);
		if(PDG.isObject(vFrame))
			sID='clvl'+PDG.mainFrameLevel+'_'+vFrame.name;
		else
			sID='clvl_'+PDG.mainFrameLevel+document.location;//this should never happen...			
	}
	else
	{		
		sID='tlvl_'+PDG.mainFrameLevel;//we are a top level container page...
	}
	//replace anything that could cause problems with using the id in the dom
	sID =sID.replace(/[-' '+=\/\\:?.]/g,'ic');	
	//sID	
	PDG.page.appid=sID;
}

//////////////////////////////////////////////////////////////////////
/*page_nav.initBinaryUpdates
	they just update and report if it was successful or not...
*/
//////////////////////////////////////////////////////////////////////
PDG.page.initBinaryUpdates=function()
{
	var Event = YAHOO.util.Event,i,checkTriggers;
	checkTriggers=$(".pdgbinupdate");
	for(i=0;i<checkTriggers.length;i++)
	{
		Event.on(checkTriggers[i], 'click',PDG.page.onCheckTrigger,window,true);	
		checkTriggers[i].pdgCheckState=10;
		checkTriggers[i].pdgLaunchTab=false;
		checkTriggers[i].triggerType=1;
	}
}
//////////////////////////////////////////////////////////////////////
/*page_nav.initUpdateReloads
	they just update and report if it was successful or not...
*/
//////////////////////////////////////////////////////////////////////
PDG.page.initUpdateReloads=function()
{
	var Event = YAHOO.util.Event,i,checkTriggers;
	checkTriggers=$(".pdgupdaterl");
	for(i=0;i<checkTriggers.length;i++)
	{
		Event.on(checkTriggers[i], 'click',PDG.page.onCheckTrigger,window,true);	
		checkTriggers[i].pdgCheckState=10;
		checkTriggers[i].pdgLaunchTab=false;
		checkTriggers[i].triggerType=2;
	}
}
//////////////////////////////////////////////////////////////////////
/*page_nav.initFirstCheck
	Checks are binary (usually for searches or report type things )
*/
//////////////////////////////////////////////////////////////////////
PDG.page.initFirstCheck=function()
{
	var Event = YAHOO.util.Event,i,checkTriggers;
	checkTriggers=$(".pdgcheckfirst");
	for(i=0;i<checkTriggers.length;i++)
	{
		Event.on(checkTriggers[i], 'click',PDG.page.onCheckTrigger,window,true);	
		checkTriggers[i].pdgCheckState=10;
		checkTriggers[i].pdgLaunchTab=false;
		checkTriggers[i].triggerType=0;
	}
}

//////////////////////////////////////////////////////////////////////
/*page_nav.initDynaTabs : 
*/
//////////////////////////////////////////////////////////////////////
PDG.page.initDynaTabs=function()
{
	var Event = YAHOO.util.Event,i,tabTriggers;
	tabTriggers=$(".pdgoitab");
	
	for(i=0;i<tabTriggers.length;i++)
	{
		if(PDG.isObject(tabTriggers[i].pdgLaunchTab))
		{
			//the check state functions will do it...
			tabTriggers[i].pdgLaunchTab=true;			
		}
		else
			Event.on(tabTriggers[i], 'click',PDG.page.onTabTrigger,window,true);	
	}
}
//////////////////////////////////////////////////////////////////////
/*page_nav.initEditors : 
*/
//////////////////////////////////////////////////////////////////////
PDG.page.initEditors=function()
{
	var Event = YAHOO.util.Event,i,tabTriggers;
	editors=$("textarea.pdg-wysiwyg");
	
	for(i=0;i<editors.length;i++)
		PDG.page.wrapEditor(editors[i]);
}
//////////////////////////////////////////////////////////////////////
/*page_nav.wrapEditor
*/
//////////////////////////////////////////////////////////////////////
PDG.page.wrapEditor=function(elmArea)
{	
	var Dom = YAHOO.util.Dom;
	var tbl = document.createElement("table");
	var tblBody=document.createElement("tbody");
	//var lnk= document.createElement("A");
	var tmpRow=document.createElement("tr");
	var edtCell=document.createElement("td");
	var lnkCell=document.createElement("td");	
	var vDiv= document.createElement("DIV");
	var sTitle=null;
	var pParent=elmArea.parentNode;	
	
	tbl.setAttribute("border","0");
	tbl.setAttribute("cellpadding","0");
	tbl.setAttribute("cellspacing","0");
	$(lnkCell).addClass("pdg-edtlnktd");
	
	//alert($(pParent).height());
	//lnkCell.style.height="100%";
	//alert($(elmArea).height());
	//lnk.style.height=$(elmArea).height()+'px';
	//lnk.style.height = "100%";

//	lnk.style.minHeight="32px";
//	lnkCell.style.minHeight="100px";
	vDiv.style.width="22px";
	vDiv.style.height="1px";
	vDiv.style.overflow="hidden";
	edtCell.style.padding="0px";
	edtCell.style.margin="0px";
	
	
	//elmArea.style.margin="0px";
	edtCell.setAttribute("align","left");	
	
	if($(elmArea).hasClass("pdg-fullwidth"))
	{
		tbl.setAttribute("width","100%");
		edtCell.setAttribute("width","100%");			
		//lnkCell.setAttribute("width","22");		
		//lnkCell.setAttribute("wrap","nowrap");		
	}		
	sTitle="Editing:&nbsp;"+String(elmArea.getAttribute("editortitle"));
	
	//$(edtCell).addClass("pdg-editorfixer");
	$(tbl).addClass("pdg-taeditor");	
	//$(lnk).addClass("pdg-htmeditlnk");	
	//$(lnkCell).addClass("pdg-tdlnkcell");	
	//lnkCell.setAttribute("width","22");
	lnkCell.setAttribute("title","Edit this field using WYSIWYG editor.");
	
	tbl.appendChild(tblBody);	
	tblBody.appendChild(tmpRow);	
	tmpRow.appendChild(lnkCell);
	tmpRow.appendChild(edtCell);	

	lnkCell.appendChild(vDiv);
	edtCell.appendChild(elmArea);
	//edtCell.appendChild(lnk);
	

	//alert($(jQuery.sibling(elmArea).prev));
	//alert(pParent);
	
	pParent.appendChild(tbl);
	
//	var region = Dom.getRegion(elmArea);
//	Dom.setStyle(lnk, 'height',((region.bottom - region.top)-2)+'px');
	$(lnkCell).click(function(){
		var region = YAHOO.util.Dom.getRegion(elmArea);
		PDG.mainframe.editTextArea(window,lnkCell,elmArea,sTitle,region);
		
	});


	$(lnkCell).hover(function() {
	  $(this).addClass('pdg-edtlnktd-hover');
	}, function() {
	  $(this).removeClass('pdg-edtlnktd-hover');
});
}

//////////////////////////////////////////////////////////////////////
/*page_nav.onTabTrigger : page tab trigger called
*/
//////////////////////////////////////////////////////////////////////
PDG.page.onTabTrigger=function(ev)
{
	PDG.page.launchTab(YAHOO.util.Event.getTarget(ev),ev);
}
//////////////////////////////////////////////////////////////////////
/*page_nav.launchTab
*/
//////////////////////////////////////////////////////////////////////
PDG.page.launchTab=function(elm,ev)
{
	var frameID='';
	
	//is it a submit button?
	if(PDG.isObject(elm.type) && elm.type=='submit')
	{
		var vFORM=PDG.page.findForm(elm);
		if(!PDG.isObject(vFORM))
		{
			YAHOO.util.Event.preventDefault(ev);
			return;
		}
		frameID=PDG.page_nav.tabDispatch.addDynaTab();
		vFORM.target=frameID;
		
	}
	else
	{
		frameID=PDG.page_nav.tabDispatch.addDynaTab();
		elm.target=frameID;	
	}
}
//////////////////////////////////////////////////////////////////////
/*page_nav.findForm : find an elements form
*/
//////////////////////////////////////////////////////////////////////
PDG.page.findForm=function(elm)
{
	var i,j;
	var allForms=$("form");
	
	for(i=0;i<allForms.length;i++)
	{
		for(j=0;j<allForms[i].elements.length;j++)
			if(allForms[i].elements[j]==elm)
				return allForms[i];
	}
	alert("unable to resolve form owner");
	return null;
	/*
	for (var i=0; i < form.elements.length; i++) {
	   var element = form.elements[i];
	
	}
	*/


}
//////////////////////////////////////////////////////////////////////
/*page_nav.onTabTrigger : page tab trigger called
*/
//////////////////////////////////////////////////////////////////////
PDG.page.onCheckTrigger=function(ev)
{
	var Dom = YAHOO.util.Dom,Event=YAHOO.util.Event;
	var elm=Event.getTarget(ev);
	++elm.pdgCheckState;	
	if(elm.pdgCheckState==3)
	{
		if(elm.pdgLaunchTab)
			PDG.page.launchTab(elm);		

		return;
	}
		
	PDG.page.initResponseFrame();//make sure frame is there	
	PDG.page.responseTrigger=elm;
	
	//is it a submit button?
	if(PDG.isObject(elm.type) && elm.type=='submit')
	{
		var vFORM=PDG.page.findForm(elm);
		if(!PDG.isObject(vFORM))
		{
			YAHOO.util.Event.preventDefault(ev);
			return;
		}
		PDG.page.chkResponseItemType=0;
		PDG.page.responseItem=vFORM;
		//Add the hidden input element
		PDG.page.responseItem.appendChild(PDG.page.responseInputElm);
		PDG.page.responseOHref='';//its not an anchor
	}
	else
	{
		//save the original link and target...
		PDG.page.responseItem=elm;
		PDG.page.chkResponseItemType=1;
		//preserve original href
		PDG.page.responseOHref=elm.getAttribute('href');
		elm.href+=(String(PDG.page.responseOHref).search(/&/) > 0)?'&':'?';
		elm.href+='displayresponse=yes';
	}
	
	//preserve original target	
	PDG.page.responseOTarget=PDG.page.responseItem.target;
	
	//replace target...
	PDG.page.responseItem.target=PDG.page.responseFrame.name;
	
	//reset callbacks...
	PDG.onResponseSuccess=PDG.page.onChkResponseSuccess;
	PDG.onResponseError=PDG.page.onChkResponseFail;
	elm.pdgCheckState=1;//we are checking...
}
//////////////////////////////////////////////////////////////////////
/*PDG.page.restoreReponseItem
	
*/
//////////////////////////////////////////////////////////////////////
PDG.page.restoreReponseItem= function()
{
	if(!PDG.isObject(PDG.page.responseItem))
		return;
		
	//restore target..
	PDG.page.responseItem.target=PDG.page.responseOTarget;		
	if(PDG.page.responseItem.getAttribute('target')=='')
		PDG.page.responseItem.removeAttribute('target');

	
	if(PDG.page.chkResponseItemType==0)
	{
		//remove hidden
		document.body.appendChild(PDG.page.responseInputElm);
		PDG.page.responseItem=null;
		return;
	}
	
	PDG.page.responseItem.href=PDG.page.responseOHref;
	PDG.page.responseItem=null;
	return;
}
//////////////////////////////////////////////////////////////////////
/*PDG.page.onChkResponseSuccess 
	I would highly suggest not messing with the checkstate things...
*/
//////////////////////////////////////////////////////////////////////
PDG.page.onChkResponseSuccess= function(elmdocument,sURL,smodule)
{
	elmdocument=null;
	//reset callbacks...
	PDG.onResponseSuccess=null;
	PDG.onResponseError=null;
	
	if(PDG.isObject(PDG.page.responseTrigger))
	{
		if(PDG.page.responseTrigger.pdgCheckState==2)
			return;//do nothing
	}
	var vItem=PDG.page.responseInputElm;
	PDG.page.restoreReponseItem();
	++PDG.page.responseTrigger.pdgCheckState;	
	
	switch(PDG.page.responseTrigger.triggerType) 
	{
		case  0: 
			PDG.page.responseTrigger.click();
			break; 
		case  1: 
			PDG.page.responseTrigger.pdgCheckState=10;//reset this
			PDG.mainframe.doInfo('Update Success','The requested changes have been applied.');
			break;
		case 2:
			document.location.reload();
			break;
	}		
}
//////////////////////////////////////////////////////////////////////
/*PDG.page.onChkResponseFail
*/
//////////////////////////////////////////////////////////////////////
PDG.page.onChkResponseFail= function(elmdocument,sErrorCode)
{
	elmdocument=null;
	PDG.page.restoreReponseItem();
	PDG.page.responseTrigger.pdgCheckState=10;		
	switch(PDG.page.responseTrigger.triggerType) 
	{
		case  0: 
			PDG.mainframe.doAlert('Can not continue',sErrorCode);
			break; 
		case  1:
		case  2: 
			PDG.mainframe.doAlert('Update Failed',sErrorCode);
			break;
	}		
	//reset callbacks...
	PDG.onResponseSuccess=null;
	PDG.onResponseError=null;
}

//////////////////////////////////////////////////////////////////////
/*PDG.page.pre_initialize - important it happens here..
	a couple items need setup for the other scripts to function properly...
*/
//////////////////////////////////////////////////////////////////////
PDG.page.initResponseFrame=function()
{
	if(PDG.page.responseFrame != null)
		return;
		
	//IE does not like iframes added via dom...so we have to do it this way
	var ifrmdv = document.createElement('div');
	var sFrmID='rframe'+PDG.page.appid;
	$(ifrmdv).html('<iframe id="'+sFrmID+'" name="'+sFrmID+'" style="width:1;height:1;display:none"></iframe>');
	document.body.appendChild(ifrmdv);
	PDG.page.responseFrame=$('#'+sFrmID)[0];
	
	var vInput=document.createElement('INPUT');
	vInput.setAttribute('type','hidden');
	vInput.setAttribute('name','displayresponse');
	vInput.setAttribute('value','yes');
	document.body.appendChild(vInput);	
	PDG.page.responseInputElm=vInput;
}

//////////////////////////////////////////////////////////////////////
/*PDG.page.pre_initialize - important it happens here..
	a couple items need setup for the other scripts to function properly...
*/
//////////////////////////////////////////////////////////////////////
PDG.page.pre_initialize();