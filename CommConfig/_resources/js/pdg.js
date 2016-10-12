//////////////////////////////////////////////////////////////////////
/*Establish PDG namespace items
*/
//////////////////////////////////////////////////////////////////////
var PDG ={};
PDG.mainFrameObj=null;
PDG.mainFrameLevel=0;
PDG.widget={};//widgets
PDG.formEffects={};
PDG.dialogs={};
PDG.resourcepath='';

//////////////////////////////////////////////////////////////////////
/*responders
*/
//////////////////////////////////////////////////////////////////////
PDG.onResponseError=null;
PDG.onResponseSuccess=null;

//////////////////////////////////////////////////////////////////////
/*login form init
*/
//////////////////////////////////////////////////////////////////////
$(document).ready(function(){
	PDG.domInit();
});
//////////////////////////////////////////////////////////////////////
/*application_data : not persisted
*/
//////////////////////////////////////////////////////////////////////
PDG.application_data =
{		
	user_logged_in: false,
	initialized: false
};

//////////////////////////////////////////////////////////////////////
/*PDG.callOnPageLoad

	add an event to be triggered once a page is loaded...
*/
//////////////////////////////////////////////////////////////////////
PDG.callOnPageLoad= function(fPTR)
{
	YAHOO.util.Event.addListener(window,'load',fPTR,window); 
}
//////////////////////////////////////////////////////////////////////
/*PDG.callOnDomReady
	add an event to be triggered when the dom is ready
*/
//////////////////////////////////////////////////////////////////////
PDG.callOnDomReady= function(fPTR)
{
	//init, data, scope
	YAHOO.util.Event.onDOMReady(fPTR,null,window);
}
//////////////////////////////////////////////////////////////////////
/*PDG.callWhenEmAvailable
	
*/
//////////////////////////////////////////////////////////////////////
PDG.callWhenEmAvailable= function(elmID,fPTR)
{
	YAHOO.util.Event.onAvailable(elmID,fPTR);
}


PDG.getCookieValue= function(cookieName) {
  var exp = new RegExp (escape(cookieName) + "=([^;]+)");
  if (exp.test (document.cookie + ";")) {
    exp.exec (document.cookie + ";");
    return unescape(RegExp.$1);
  }
  else return false;
}

PDG.writeSessionCookie= function(cookieName, cookieValue) {
    document.cookie = escape(cookieName) + "=" + escape(cookieValue) + "; path=/";
}

PDG.PDGDisplayTimeOutAlert= function(){
	if(confirm('YOUR PDG COMMERCE LOGIN SESSION IS ABOUT TO EXPIRE.' +
	' PLEASE CLICK [OK] TO KEEP YOUR SESSION ACTIVE.  CLICK [CANCEL] TO CLOSE THIS WINDOW.' +
	' SAVE YOUR CHANGES NOW! YOU WILL NOT BE ABLE TO SAVE YOUR CHANGES' +
	' IN 2 MINUTES. IF YOU DO NOT SAVE NOW, YOU WILL LOSE YOUR CHANGES.' )){
		$.post($('#pdg-scriptname').text(),'updatelogin=action&displayresponse=yes',function(data){alert('Session was updated!');},"html");	
		
	}
}


PDG.PDGTimeOutAlert= function(){
	var currenttime=Number(new Date());
	var cookietime=PDG.getCookieValue('pdgtimer');
	
	if(!cookietime){
		PDG.PDGDisplayTimeOutAlert();
	}
	else{
	   var icookietime=Number(cookietime);
	   if(icookietime<=(currenttime-720000)){
		// update cookie
		PDG.writeSessionCookie('pdgtimer',currenttime);
		PDG.PDGDisplayTimeOutAlert();
	   }
	   else{
		var checkagain=(icookietime+720000)-currenttime;
		setTimeout('PDG.PDGTimeOutAlert();', checkagain );
	   }
	}
}

//////////////////////////////////////////////////////////////////////
/*PDG.init

	initialize base data for rest of scripts to use...
*/
//////////////////////////////////////////////////////////////////////
PDG.init= function()
{
	PDG.mainFrameObj=PDG.findMainFrame();
	PDG.initApplicationData();

	//allow all pages to reference mainframe in a normal fashion.
	if(PDG.mainFrameLevel>0)	
		PDG.mainframe=PDG.mainFrameObj;
		
	PDG.writeSessionCookie('pdgtimer',Number(new Date()));
	setTimeout('PDG.PDGTimeOutAlert();', 720000 );

}
//////////////////////////////////////////////////////////////////////
/*PDG.domReadInit

	initialize base data for rest of scripts to use...
*/
//////////////////////////////////////////////////////////////////////
PDG.domInit= function()
{
	if(PDG.mainFrameLevel==1)
		$(document.body).removeClass('pdg-childpage');
	//is there an error tag?
	if(PDG.isObject($("#pdg-error")[0]))
	{
		//we need the error responder...
		var vResponse=PDG.findResponder(false);
		if(PDG.isObject(vResponse))
			vResponse(window.document,$("#pdg-error").html());
	}
}
//////////////////////////////////////////////////////////////////////
/*PDG.updateRunTimeData
*/
//////////////////////////////////////////////////////////////////////
PDG.updateRunTimeData=function()
{
	PDG.updateLiveStatus();
	PDG.updateDBStage();
}
//////////////////////////////////////////////////////////////////////
/*PDG.updateLiveStatus
	looks up div and attempts to call mainframe
	called by pdg.page.js and mainframe itself
*/
//////////////////////////////////////////////////////////////////////
PDG.updateLiveStatus=function()
{
	//update live status
	if(PDG.isObject($("#pdg-status")[0]) && PDG.isObject(PDG.mainFrameObj))
		PDG.mainFrameObj.updateLiveStatus($("#pdg-status").html());
}
//////////////////////////////////////////////////////////////////////
/*PDG.updateDBStage
	looks up div and attempts to call mainframe
	called by pdg.page.js and mainframe itself
*/
//////////////////////////////////////////////////////////////////////
PDG.updateDBStage=function()
{
	if(PDG.isObject($("#pdg-stage")[0]) && PDG.isObject(PDG.mainFrameObj))
		PDG.mainFrameObj.updateDBStage($("#pdg-stage").html());
}

//////////////////////////////////////////////////////////////////////
/*PDG.isObject helper function : is it something we can look at?
*/
//////////////////////////////////////////////////////////////////////
PDG.isObject=function(obj)
{
	if(obj===undefined)return false;
	if(obj===null)return false;
	return true;
}
//////////////////////////////////////////////////////////////////////
/*PDG.getPreviousNode gets previous node...
*/
//////////////////////////////////////////////////////////////////////
PDG.getPreviousNode=function(el)
{
	function isIgnorable(node) {
		// is a comment or contains only whitespace
		return (node.nodeType == 8 || /^[\t\n\r ]+$/.test(node.data));
	}

	var prev = el;
	while (prev = prev.previousSibling) {
		if (!isIgnorable(prev)) break;
	}

	return prev;
}

//////////////////////////////////////////////////////////////////////
/*PDG.clampValue helper function : is it something we can look at?
	if the vlu is not an object vDefault is returned
	if vlu is less than min then min is used
	if vlu is greater than max then max is used
*/
//////////////////////////////////////////////////////////////////////
PDG.clampValue=function(vlu,vMin,vMax,vDefault)
{
	if(!PDG.isObject(vlu))return vDefault;
	if(vlu < vMin)return vMin;
	if(vlu > vMax)return vMax;
	return vlu;
}
//////////////////////////////////////////////////////////////////////
/*PDG.findMainFrame : find the main frame
*/
//////////////////////////////////////////////////////////////////////
PDG.findMainFrame=function()
{
	PDG.mainFrameLevel=0;
	
	//is it us?
	if(PDG.isObject(PDG.mainframe))
		return PDG.mainframe;
		
	try
	{
		//assumes we have a parent...
		var nLookup=0;
		var vPos=window.parent;	
		while(PDG.isObject(vPos) && nLookup <15)
		{
			++PDG.mainFrameLevel;
			if(PDG.isObject(vPos.PDG))
			{
				if(PDG.isObject(vPos.PDG.mainframe))
				{
					if(!PDG.isObject(vPos.PDG.realmainframe))
						++PDG.mainFrameLevel;
					return vPos.PDG.mainframe;			
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
/*PDG.findResponder : find the appropriate responder
	when bSuccess is true it will look for the success responder otherwise
	it will look for the error responder...
*/
//////////////////////////////////////////////////////////////////////
PDG.findResponder=function(bSuccess)
{
	if(bSuccess)
	{
		try
		{
			//assumes we have a parent...
			var nLookup=0;
			var vPos=window.parent;	
			while(PDG.isObject(vPos) && nLookup <15)
			{
				if(PDG.isObject(vPos.PDG))
				{
					if(PDG.isObject(vPos.PDG.onResponseSuccess))
						return vPos.PDG.onResponseSuccess;			
						
					return null;//only 1 chain up
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
	
	try
	{
		//assumes we have a parent...
		var nLookup=0;
		var vPos=window.parent;	
		while(PDG.isObject(vPos) && nLookup <15)
		{
			if(PDG.isObject(vPos.PDG))
			{
				if(PDG.isObject(vPos.PDG.onResponseError))
					return vPos.PDG.onResponseError;			
				return null;//only 1 chain up
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
/*PDG.findLoginForm : find the login form
*/
//////////////////////////////////////////////////////////////////////
/*
PDG.findLoginForm=function()
{
	//is it us?
	if(PDG.isObject(PDG.loginform))
		return null;//we are it.
		
	try
	{
		//assumes we have a parent...
		var nLookup=0;
		var vPos=window.parent;	
		while(PDG.isObject(vPos) && nLookup <15)
		{
			if(PDG.isObject(vPos.PDG))
			{
				if(PDG.isObject(vPos.PDG.loginform))
					return vPos.PDG.loginform;			
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
*/
//////////////////////////////////////////////////////////////////////
/*PDG.initApplicationData
	
*/
//////////////////////////////////////////////////////////////////////
PDG.initApplicationData=function()
{
	/*
	var Cookie=YAHOO.util.Cookie;
	
	if(PDG.application_data.initialized)
		return;
	
	PDG.application_data.user_logged_in=false;
	var pw=Cookie.get('pw',false);
	if(pw != null)
	{
		if(String(pw).length >2)
			PDG.application_data.user_logged_in=true;
	}	
	
	PDG.application_data.initialized=true;
	*/
}
//////////////////////////////////////////////////////////////////////
/*PDG.initDialogs
	
*/
//////////////////////////////////////////////////////////////////////
PDG.dialogs.onOk=function()
{
	this.hide();
}
//////////////////////////////////////////////////////////////////////
/*PDG.initDialogs
	
*/
//////////////////////////////////////////////////////////////////////
PDG.initDialogs=function()
{
	//copy icons...
	PDG.dialogs.ICON_ALARM=YAHOO.widget.SimpleDialog.ICON_ALARM;
	PDG.dialogs.ICON_BLOCK=YAHOO.widget.SimpleDialog.ICON_BLOCK;
	PDG.dialogs.ICON_HELP=YAHOO.widget.SimpleDialog.ICON_HELP;
	PDG.dialogs.ICON_INFO=YAHOO.widget.SimpleDialog.ICON_INFO;
	PDG.dialogs.ICON_TIP=YAHOO.widget.SimpleDialog.ICON_TIP;
	PDG.dialogs.ICON_WARN=YAHOO.widget.SimpleDialog.ICON_WARN;
	PDG.dialogs.ICON_NOICON='';

	PDG.dialogs.dlgAlert=new YAHOO.widget.SimpleDialog(	"dlgAlert", 
														 { width: "400px",
														   fixedcenter: true,
														   visible: false,
														   draggable: false,
														   zindex:100, 
														   modal:true, 
														   close: false,
														   icon: YAHOO.widget.SimpleDialog.ICON_WARN,
														   constraintoviewport: true,
														   buttons: [ { text:"Ok", handler:PDG.dialogs.onOk,
																	 isDefault:true }]
														 } );
}
//////////////////////////////////////////////////////////////////////
/*PDG.doAlert
	
*/
//////////////////////////////////////////////////////////////////////
PDG.doAlert=function(sTitle,sContent,icn)
{
	if(icn != undefined)
		PDG.dialogs.dlgAlert.cfg.queueProperty('icon',icn);
	else
		PDG.dialogs.dlgAlert.cfg.queueProperty('icon',PDG.dialogs.ICON_WARN);
	PDG.dialogs.dlgAlert.setHeader(sTitle);
	PDG.dialogs.dlgAlert.setBody(sContent);
	PDG.dialogs.dlgAlert.render(document.body);
	if (PDG.dialogs.dlgAlert.bringToTop) {
		PDG.dialogs.dlgAlert.bringToTop();
	}
	PDG.dialogs.dlgAlert.show();
}
//////////////////////////////////////////////////////////////////////
/*PDG.doConfirmation : confirmation dialog with custom buttons
	
*/
//////////////////////////////////////////////////////////////////////
PDG.doConfirmation=function(sID,sTitle,sContent,icn,buttondef,bHasClose,nWidth)
{
	if(!PDG.isObject(bHasClose))bHasClose=false;
	if(!PDG.isObject(nWidth))nWidth=400;
	if(!PDG.isObject(buttondef))
	{
		alert('script failure: required parameter (buttondef) for PDG.doConfirmation is not defined');
		return;
	}	
	var dlgAttributes={ width: nWidth+"px",
					   fixedcenter: true,
					   visible: false,
					   draggable: false,
					   zindex:100, 
					   modal:true, 
					   close: bHasClose,
					   icon: icn,
					   constraintoviewport: true,
					   buttons :buttondef
					 };
				 
	var sWidth=nWidth+"px";
	PDG.dialogs.dlgConfirm=new YAHOO.widget.SimpleDialog(sID,dlgAttributes);
	
	PDG.dialogs.dlgConfirm.setHeader(sTitle);
	PDG.dialogs.dlgConfirm.setBody(sContent);
	PDG.dialogs.dlgConfirm.render(document.body);
	PDG.dialogs.dlgConfirm.show();

}
//////////////////////////////////////////////////////////////////////
/*PDG.formEffects.init
	
*/
//////////////////////////////////////////////////////////////////////
PDG.formEffects.init=function(bAutomatic)
{
	var Event = YAHOO.util.Event,i,links;
	var Dom = YAHOO.util.Dom;
	var bAllText=false;
	var links=null;
	if(PDG.isObject(bAutomatic))
		bAllText=bAutomatic;
	
	if(bAllText)
	{
		links=$("input[type=text],input[type=password],textarea,select");
	}
	else
		links=$(".pdg-flat-input");

	for(i=0;i<links.length;i++)
	{
		Dom.addClass(links[i],'pdg-flat-input');
		Event.on(links[i], 'focus',function(ev){PDG.formEffects.flatInputFocus(Event.getTarget(ev));});
		Event.on(links[i], 'blur',function(ev){PDG.formEffects.flatInputBlur(Event.getTarget(ev));});
	}
}
//////////////////////////////////////////////////////////////////////
/*PDG.formEffects.flatInputFocus
	
*/
//////////////////////////////////////////////////////////////////////
PDG.formEffects.flatInputFocus=function(elm)
{
	var Dom = YAHOO.util.Dom;
	Dom.addClass(elm,'pdg-flat-input-focus');
	Dom.removeClass(elm,'pdg-flat-input');	
}
//////////////////////////////////////////////////////////////////////
/*PDG.formEffects.flatInputBlur
	
*/
//////////////////////////////////////////////////////////////////////
PDG.formEffects.flatInputBlur=function(elm)
{
	var Dom = YAHOO.util.Dom;
	Dom.addClass(elm,'pdg-flat-input');
	Dom.removeClass(elm,'pdg-flat-input-focus');
}

//////////////////////////////////////////////////////////////////////
/*PDG init - important it happens here..
	a couple items need setup for the other scripts to function properly...
*/
//////////////////////////////////////////////////////////////////////
PDG.init();