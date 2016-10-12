//////////////////////////////////////////////////////////////////////
/*Establish PDG.iframe namespace items
*/
//////////////////////////////////////////////////////////////////////
PDG.iframe={};
PDG.iframe.main_view=false;
PDG.iframe.child=false;
PDG.iframe.iframeElm=null;
PDG.iframe.iframeAnchorElm=null;
PDG.iframe.ignore=false;

//////////////////////////////////////////////////////////////////////
/*init
*/
//////////////////////////////////////////////////////////////////////
(function() {
	//YAHOO.util.Event.onDOMReady(function(ev){PDG.iframe.init();});
	YAHOO.util.Event.addListener(window, 'load',function(ev){PDG.iframe.init();}); 
})();

//////////////////////////////////////////////////////////////////////
/*PDG.iframe init
*/
//////////////////////////////////////////////////////////////////////
PDG.iframe.init= function()
{
	var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event;
	PDG.iframe.iframeElm=PDG.iframe.findFrameElement(window.document);	
	//alert(PDG.iframe.iframeElm);
	var dv = document.createElement('DIV');
	dv.style.height='1px';
	dv.style.width='1px';
	window.document.body.appendChild(dv);
	PDG.iframe.iframeAnchorElm=dv;
	
	if(PDG.iframe.iframeElm ==null)
		return;
	
	//we watch for resize events....
//	Event.addListener(window.document.body,'resize',function(){PDG.iframe.updateFrame();});	
	//$(window).wresize(PDG.iframe.updateFrame); 
	$(window).resize(function(){PDG.iframe.updateFrame(false);});
	/*
	YAHOO.util.Event.addListener(document.body, "Resize", function(e) {
		alert('size change');
	});
	*/
	//Go ahead and call it 
	PDG.iframe.updateFrame(true);	
//	PDG.iframe.ignore=false;

}
//////////////////////////////////////////////////////////////////////
/*PDG.iframe.updateFrame 
	We use the ignore flag because by changing our height will fire
	the resize event...
*/
//////////////////////////////////////////////////////////////////////
PDG.iframe.updateFrame=function(bForce)
{
	if(PDG.iframe.ignore && !bForce)
	{
		PDG.iframe.ignore=false;	
		return;
	}
	if(!PDG.isObject(PDG.iframe.iframeElm))
		return;

	//cheat : 
	var curxy = YAHOO.util.Dom.getXY(PDG.iframe.iframeAnchorElm); 
	var height=Math.round(curxy[1])+1;
	
	//alert('document height: '+height); 63921?
	//height=900000;
	//set our height in the parent....
	PDG.iframe.ignore=true;
	
	if(height > 32700)
	 	height=32700;
		
	//PDG.iframe.iframeElm.setAttribute("height",null);//height=
	PDG.iframe.iframeElm.style.height =height+'px';
	
	//are we a child frame?
	if(window.parent.PDG.iframe != undefined)
	{
		// let page render first...
//		setTimeout(function(){		  
//		  window.parent.PDG.iframe.updateFrame(true);
//		}, 5000);
		//yes... make them update as well
		window.parent.PDG.iframe.updateFrame(true);
	}
}
//////////////////////////////////////////////////////////////////////
/*PDG.iframe.findFrameElement : find the frame that references the given document element
*/
//////////////////////////////////////////////////////////////////////
PDG.iframe.findFrameElement=function(elmDocument)
{
	try
	{
		//assumes we have a parent...
		var i;
		var vSrcWindow=window.parent;
		
		if(vSrcWindow == null || vSrcWindow==undefined)
			return null;
			
		var iFrames = vSrcWindow.document.getElementsByTagName('iframe');
		for(i=0;i<iFrames.length;i++)
		{
			if(iFrames[i].contentWindow.document==elmDocument)
			{
				if(iFrames[i].id != "pdggarbageframe")
					return iFrames[i];
			}
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
/*PDG.iframe.measureHeight : dbody = document.body
*/
//////////////////////////////////////////////////////////////////////
PDG.iframe.measureHeight= function(dbody)
{
	var x,y;
	var test1 = dbody.scrollHeight;
	var test2 = dbody.offsetHeight
	if (test1 > test2) // all but Explorer Mac
	{
		x = dbody.scrollWidth;
		y = dbody.scrollHeight;
	}
	else // Explorer Mac;
		 //would also work in Explorer 6 Strict, Mozilla and Safari
	{
		x =dbody.offsetWidth;
		y = dbody.offsetHeight;
	}
	return y;
}
