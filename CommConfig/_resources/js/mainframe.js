//////////////////////////////////////////////////////////////////////
/*Establish mainframe namespace items
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe={};
PDG.mainframe.live_status=false;//default when loaded
PDG.mainframe.db_stage=-1971;//default when loaded
PDG.mainframe.pageTracker=new PDG.pageTracker();
PDG.realmainframe=true;

//////////////////////////////////////////////////////////////////////
/*mainframe form init
*/
//////////////////////////////////////////////////////////////////////
$(document).ready(function(){
      PDG.mainframe.init();
	  PDG.formEffects.init();
});
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.init 
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.init= function()
{
	var Event=YAHOO.util.Event;
	var Dom = YAHOO.util.Dom;
	
	//load frame state
	PDG.mainframe.serializeState(true);
	
	//init resizer
	PDG.mainframe.resize = new YAHOO.util.Resize('resize', {
			proxy: false,
			minWidth: 300,
			minHeight: 422		
		});
	
	PDG.mainframe.resize.on('beforeResize', function(ev,obj) {
													 
		var hHandle=PDG.mainframe.resize.getActiveHandleEl();
		if($(hHandle).hasClass("yui-resize-handle-r"))
			YAHOO.util.Dom.get('main-view-overlay').style.cursor='e-resize';
		else if($(hHandle).hasClass("yui-resize-handle-br"))
			YAHOO.util.Dom.get('main-view-overlay').style.cursor='se-resize';
		else if($(hHandle).hasClass("yui-resize-handle-b"))
			YAHOO.util.Dom.get('main-view-overlay').style.cursor='s-resize';
			
		PDG.mainframe.updateOverlay();
		$('#main-view-overlay').show();
		//YAHOO.util.Dom.get('main-view-overlay').style.display='block';		
	});	
	
	PDG.mainframe.resize.on('endResize', function(ev) {
		$('#main-view-overlay').hide();
		PDG.mainframe.serializeState(false);
	});	
	
	PDG.mainframe.resize.on('resize', function(ev) {
		PDG.mainframe.updateSizes();
		PDG.mainframe.updateOverlay();		
		//YAHOO.util.Dom.get('main-view-overlay').style.display='none';		
	});
	
	$("#nav-panel-hider").click(function() {
										 
		if($(this).hasClass("pnl-expanded"))
		{
			$(this).addClass("pnl-collapsed");
			$(this).removeClass("pnl-expanded");
			PDG.mainframe.expandcollapse(false);
		}
		else
		{
			$(this).addClass("pnl-expanded");
			$(this).removeClass("pnl-collapsed");	
			PDG.mainframe.expandcollapse(true);
		}
	});
	
	//create qsearch combo
	$('#qsearch').combobox( 
		{ 	comboboxContainerClass: "comboboxContainer", 
			comboboxValueContainerClass: "comboboxValueContainer", 
			comboboxValueContentClass: "comboboxValueContent", 
			comboboxDropDownClass: "comboboxDropDownContainer", 
			comboboxDropDownButtonClass: "comboboxDropDownButton", 
			comboboxDropDownItemClass: "comboboxItem", 
			comboboxDropDownItemHoverClass: "comboboxItemHover", 
			comboboxDropDownGroupItemHeaderClass: "comboboxGroupItemHeader", 
			comboboxDropDownGroupItemContainerClass: "comboboxGroupItemContainer", 
			animationType: "fade", 
			width: "100%" });
	
	PDG.mainframe.qsearch_combo=jQuery("#qsearch").combobox;
	
	//pdg-qsearch-submit
	Event.addListener(Dom.get('qsearch-form'),'submit',PDG.mainframe.onQSearch,window,true);

	//make live discard and logout...
	Event.addListener(Dom.get('pdg-discardchanges'),'click',PDG.mainframe.onDiscardChanges,window,true);
	Event.addListener(Dom.get('pdg-makelive'),'click',PDG.mainframe.onMakeLive,window,true);
	Event.addListener(Dom.get('pdg-logout'),'click',PDG.mainframe.onLogout,window,true);
	
	//assign label-over to any pre label elements
	$('label.pre').labelOver('over');
	
	PDG.mainframe.updateSizes();
	PDG.initDialogs();
	//make sure he has a reference to us
	PDG.mainFrameObj=PDG.mainframe;
	PDG.updateRunTimeData();
	
	//our responders...
	PDG.onResponseSuccess=PDG.mainframe.onResponseSuccess;
	PDG.onResponseError=PDG.mainframe.onResponseError;
	
	window.history.forward(1);
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.onResponseSuccess
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.onResponseSuccess=function(elmdocument,sURL,smodule)
{
	elmdocument=null;
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.onResponseSuccess
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.onResponseError=function(elmdocument,sErrorCode)
{
	elmdocument=null;
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.onQSearch
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.onQSearch= function(ev)
{
	var Event=YAHOO.util.Event;
	var qSrch=$('#qsearch')[0];
	var sSearchText=$('#pdg-qsearch-text').val();
	var nAction=parseInt(qSrch[qSrch.selectedIndex].value,10);
	//we never submit this form
	Event.preventDefault(ev);
	if(sSearchText=="" && nAction!=7)
	{
		PDG.doAlert("Can not continue","You have not entered any search criteria");
		return;	
	}
	
	if(nAction <=4)
		PDG.mainframe.QSearch_ResetProducts();
	else
		PDG.mainframe.QSearch_ResetOrders();
		
	switch(nAction)
	{
		//0-4 product oriented
		case 0:
			$('#pqs-productsku').val(sSearchText);
			$('#qsearch_products')[0].submit();
			break;
		case 1:
			$('#pqs-productcategory').val(sSearchText);
			$('#qsearch_products')[0].submit();
			break;
		case 2:
			$('#pqs-productdescription').val(sSearchText);
			$('#qsearch_products')[0].submit();
			break;
		case 3:
			$('#pqs-productskuinv').val(sSearchText);
			$('#qsearch_products')[0].submit();
			break;
		case 4:			
			$('#pqs-productcategoryinv').val(sSearchText);
			$('#qsearch_products')[0].submit();
			break;
		//5-7 invoice
		case 5:
			$('#oqs-tmInvoiceNum').val(sSearchText);
			$("#oqs-orderaction").val("invoice");
			$('#qsearch_orders')[0].submit();
			break;
		case 6:
			$('#oqs-username').val(sSearchText);
			$("#oqs-orderaction").val("custorder");
			$('#qsearch_orders')[0].submit();
			break;		
		case 7:
			//All orders...
			$("#oqs-orderaction").val("orders");
			$('#qsearch_orders')[0].submit();			
			break;
	}
}
//////////////////////////////////////////////////////////////////////
/*QSearch_ResetProducts
	clear all the product inputs...
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.QSearch_ResetProducts=function()
{
	$("#pqs-productdescription").val("");
	$("#pqs-productsku").val("");
	$("#pqs-productcategory").val("");
	$("#pqs-productskuinv").val("");
	$("#pqs-productcategoryinv").val("");
}
//////////////////////////////////////////////////////////////////////
/*QSearch_ResetOrders
	clear all the product inputs...
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.QSearch_ResetOrders=function()
{
	$("#oqs-orderaction").val("");
	$("#oqs-tmInvoiceNum").val("");
	$("#oqs-username").val("");
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.onMakeLive
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.onMakeLive= function()
{
	$("#pdgfrm-makelive")[0].submit();
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.onDiscardChanges
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.onDiscardChanges= function()
{
	PDG.mainframe.confirmDiscard();
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.onDiscardChanges
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.onLogout= function(ev)
{
	var Event=YAHOO.util.Event;	
	if(PDG.mainframe.db_stage==1)
	{
		Event.preventDefault(ev);
		PDG.mainframe.confirmStage1();
	}
}
//////////////////////////////////////////////////////////////////////
/*PDG doAlert delegate
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.doAlert=function(sTitle,sContent,icn)
{
	PDG.doAlert(sTitle,sContent,icn);
}
//////////////////////////////////////////////////////////////////////
/*PDG doInfo delegate
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.doInfo=function(sTitle,sContent)
{
	PDG.doAlert(sTitle,sContent,YAHOO.widget.SimpleDialog.ICON_INFO);
}

//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.updateSizes
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.updateSizes= function()
{
	var Dom = YAHOO.util.Dom;
	var region = YAHOO.util.Dom.getRegion('resize');
	var qsregion = YAHOO.util.Dom.getRegion('pdg-qsearch-container');
	var elmHeight = region.bottom - region.top -( qsregion.bottom-qsregion.top);
	var vTree=Dom.get('nav-tree-container');

	YAHOO.util.Dom.setStyle(vTree, 'height',(elmHeight-6)+ 'px');
	PDG.mainframe.qsearch_combo.updateWidth(region.right - region.left);
	
	PDG.mainframe.updateMainSize();
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.updateSizes
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.updateMainSize= function()
{
	var Dom = YAHOO.util.Dom;
	var region = YAHOO.util.Dom.getRegion('resize');
	
	var elmWidth = region.right - region.left;
	var vmain=Dom.get('left-view');
	var vFrame=Dom.get('frame-main');

	Dom.setStyle(vmain, 'width',(region.right+26)+ 'px');
	Dom.setStyle(vmain, 'height','');//we allow height to be fluid
	if(!jQuery.browser.msie)//firefox
		$("#frame-main").show();
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.serializeState
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.serializeState=function(bIsLoading)
{
	var Cookie=YAHOO.util.Cookie;
	var Dom = YAHOO.util.Dom;
	var sCName='pdgfstate';
	if(bIsLoading){
		
		var vnav=Dom.get('resize');
		var vw=PDG.clampValue(Cookie.getSub(sCName,'fw',Number),300,1500,300);
		var vh=PDG.clampValue(Cookie.getSub(sCName,'fh',Number),420,1500,420);
		Dom.setStyle(vnav, 'width',vw+'px');
		Dom.setStyle(vnav, 'height',vh+'px');
	}
	else
	{		
		var region = YAHOO.util.Dom.getRegion('resize');
		var vw=region.right - region.left;
		var vh=region.bottom - region.top;
		Cookie.setSub(sCName,'fw',vw);
		Cookie.setSub(sCName,'fh',vh);
	}
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.expandcollapse
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.updateOverlay=function()
{
	var Dom = YAHOO.util.Dom;
	var mView=Dom.get('main-view');
	var mOverlay=Dom.get('main-view-overlay');	
	var region = YAHOO.util.Dom.getRegion(window.document.body);
	Dom.setStyle(mOverlay, 'width',((region.right - region.left))+'px');
	Dom.setStyle(mOverlay, 'height',((region.bottom - region.top)-30)+'px');
//	Dom.setStyle(mOverlay, 'top',(region.top-22)+ 'px');
//	Dom.setStyle(mOverlay, 'left',(region.left-22)+ 'px');
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.expandcollapse
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.expandcollapse=function(bExpand)
{
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event;
	var vSizer=Dom.get('resize');
	var vPanel=Dom.get('navpanel');
	var xy = YAHOO.util.Dom.getXY(vPanel); 
	if(xy==false)
		return false;
	if(xy[0]==0 && xy[1]==0)
		return false;//ie
	var curxy = YAHOO.util.Dom.getXY(vPanel); 
	var region = YAHOO.util.Dom.getRegion(vSizer);
	var elmHeight = region.bottom - region.top;
	var elmWidth = region.right - region.left;
	var vDX=(curxy[0]-elmWidth);	
	if(bExpand)
	{
		//pre-expand the view....
		var vmain=Dom.get('left-view');
		Dom.setStyle(vmain, 'width',(elmWidth+26)+ 'px');
		vDX=0;
	}
	var attributes = {
		points: { to: [vDX, curxy[1]] }
	};		
	var anim = new YAHOO.util.Motion(vPanel, attributes,.2, YAHOO.util.Easing.easeOut);
	anim.onComplete.subscribe(PDG.mainframe.updateMainSize); 
	
	//if you do not hide it - firefox will flicker ( badly)
	if(!jQuery.browser.msie)//firefox
		$("#frame-main").hide();
	anim.animate();
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.editTextArea
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.editTextArea = function(vWindow,vTrigger,vTextElement,vTitle,region)
{
	PDG.editTextAreaEx(vWindow,vTrigger,vTextElement,vTitle,region);
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.updateDBStage
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.updateDBStage=function(sVal)
{
	if(!PDG.isObject(sVal))return;
	PDG.mainframe.db_stage=0;
	if(String(sVal).length > 0)
		PDG.mainframe.db_stage=parseInt(sVal,10);
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.updateLiveStatus
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.updateLiveStatus=function(sStatusText)
{
	if(!PDG.isObject(sStatusText))return;		
	PDG.mainframe.setLiveStatusIndicator(!(String(sStatusText).search(/not/i) > 0));
}

//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.setLiveStatusIndicator
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.setLiveStatusIndicator=function(bIsLive)
{
	if(PDG.mainframe.live_status==bIsLive)return;
	if(bIsLive)
	{
		$('#pdg-top-box-notlive').hide();
		$('#pdg-makelive').hide();		
		$('#pdg-discardchanges').hide();				
		$('#pdg-top-box-live').fadeIn("slow");
	}
	else
	{
		$('#pdg-top-box-live').hide();
		$('#pdg-makelive').show();
		$('#pdg-discardchanges').show();				
		$('#pdg-top-box-notlive').fadeIn("slow");		
	}
	PDG.mainframe.live_status=bIsLive;
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.confirmStage1
	do the stage 1 dialog
*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.confirmStage1=function()
{
	var sMSG='You have elected to Log out of the PDG Merchant Administrator with '+
	'changes that have not yet been <em>Made Live</em>.<br><br>Please choose from the following options:<br>';
	
	var buttons=[ { text:"Just Logout",isDefault:true,handler:function(){$("#pdgfrm-logout")[0].submit();} },
				  { text:"Make Changes Live",handler:function(){this.hide();$("#pdgfrm-makelive")[0].submit();}},
				  { text:"Discard Current Changes",handler:function(){this.hide();PDG.mainframe.confirmDiscard();}}
				  ];
	
	PDG.doConfirmation(	'pdg-dlg-stage1',//id
						'Changes Pending',//title
						sMSG,//content
						PDG.dialogs.ICON_WARN,//icon ( see pdg.js for full list )
						buttons,//button definition
						true,//has close button
						500);
}
//////////////////////////////////////////////////////////////////////
/*PDG.mainframe.confirmDiscard

*/
//////////////////////////////////////////////////////////////////////
PDG.mainframe.confirmDiscard=function()
{
	var sMSG='All changes which have not yet been <em>Made Live</em> will be lost.<br><br>Are you sure?<br>';
	
	var buttons=[ { text:"No (do not discard my changes)",handler:PDG.dialogs.onOk },
				  { text:"Yes (discard my changes)",handler:function(){this.hide();$("#pdgfrm-discard")[0].submit();}}
				  ];

	PDG.doConfirmation(	'pdg-dlg-discard',//id
						'Discard Changes',//title
						sMSG,//content
						PDG.dialogs.ICON_WARN,//icon ( see pdg.js for full list )
						buttons,//button definition
						true,//has close button
						500);
}