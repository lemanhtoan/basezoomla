//////////////////////////////////////////////////////////////////////
/*Establish loginform namespace
*/
//////////////////////////////////////////////////////////////////////
PDG.loginform={};

//////////////////////////////////////////////////////////////////////
/*login form init
*/
//////////////////////////////////////////////////////////////////////
$(document).ready(function(){
	PDG.loginform.init();
});

//////////////////////////////////////////////////////////////////////
/*PDG.loginform.init
*/
//////////////////////////////////////////////////////////////////////
PDG.loginform.init= function()
{
	var Event=YAHOO.util.Event;
	var Dom = YAHOO.util.Dom;
	
	PDG.formEffects.init();
	
	if(!jQuery.browser.msie)
	{
	  $("#inner2").fadeIn("slow");
	}
	else
	{
	  $("#inner2").slideDown("slow");
	}
	//hook up hover
	$('#pdg-login-btn').hover(function() {
	  $(this).addClass('pdg-login-btn-hover');
	}, function() {
	  $(this).removeClass('pdg-login-btn-hover');
	});
	
	//If there is no secure login then hide the controls.
	if($('#securemodulename').val()==$('#modulename').val())
	{
		$("#lgn-secure-lbl").hide();
		$("#lgn-secure-input").hide();
		$("#loginsecurely").val(false);
		$("#loginsecurely")[0].checked=false;
	}
	
	//handle the sumbit action....
	Event.addListener(Dom.get('loginform'),'submit',function(ev){
			var bAttempt=true;
			var sAlert="You must enter a ";		
			
			if($('#user').val().length <0)
			{
				sAlert+="valid Username";
				bAttempt=false;
			}
			
			if($('#pw').val().length <3)
			{
				if(!bAttempt)
					sAlert+=" and Password.";
				else
					sAlert+="valid Password.";
					
				bAttempt=false;
			}			
			if(!bAttempt)
			{
				PDG.doAlert('Unable to continue',sAlert);
				Event.preventDefault(ev);
			}
			else
			{
				if($("#loginsecurely")[0].checked)
					$("#loginform")[0].action=$('#securemodulename').val();
				else
					$("#loginform")[0].action=$('#modulename').val();
			}
	});
	
	PDG.initDialogs();
	
	//we have a very simple responders...
	PDG.onResponseSuccess=PDG.loginform.onLoginSuccess;
	PDG.onResponseError=PDG.loginform.onLoginError;
}
//////////////////////////////////////////////////////////////////////
/*PDG.loginform.onLoginSuccess
*/
//////////////////////////////////////////////////////////////////////
PDG.loginform.onLoginSuccess= function(elmdocument,sURL,smodule)
{
	elmdocument=null;//we do not want it
	$("#loggedinform")[0].action=sURL;
	$("#li-modulename").val(smodule);
	$("#loggedinform")[0].submit();
}
//////////////////////////////////////////////////////////////////////
/*PDG.loginform.onLoginError
*/
//////////////////////////////////////////////////////////////////////
PDG.loginform.onLoginError= function(elmdocument,sErrorCode)
{
	elmdocument=null;//we do not want it
	if(String(sErrorCode).search(/password/i) > 0)
		PDG.doAlert('Unable to log in',"You have entered an invalid username or password",PDG.dialogs.ICON_BLOCK);
	else if(String(sErrorCode).search(/time/i) > 0)
	{
		PDG.doAlert('Refresh Required',"You have exceeded the amount of time allowed to login.  You must refresh your browser before trying again.",PDG.dialogs.ICON_BLOCK);		
	}
	else
		PDG.doAlert('Unable to log in',sErrorCode,PDG.dialogs.ICON_BLOCK);
	
}
//////////////////////////////////////////////////////////////////////
/*PDG.loginform.setupWallpaper
*/
//////////////////////////////////////////////////////////////////////
PDG.loginform.setupWallpaper= function()
{
	//setup wallpaper						   
	var flashvars = {};
	var params = {};
	var sSWF='pdg-wallpaper.swf';
	var sContentID='pdg-wallpaper';
	params.play = "true";
	params.wmode="transparent";
	params.menu = "true";
	params.scale = "scale";
	params.salign = "tl";
	params.base=".";
	flashvars.wmode="transparent";
	params.bgcolor = "#0A478A";
	params.allowfullscreen = "true";
	params.allowscriptaccess = "always";
	params.allownetworking = "all";
	var attributes = {};
	attributes.align = "left";
	var swfPath=PDG.resourcepath+"/wallpaper/";
	swfobject.embedSWF(swfPath+sSWF,sContentID, "100%", "100%", "9.0.28",swfPath+"expressInstall.swf", flashvars, params, attributes);
}

//Setup the wallpaper immediately..
PDG.loginform.setupWallpaper();