function createCookie(name,value,days)
{
	var expire;
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expire = "; expires="+date.toGMTString();
	}
	else
	{
		expire = "";
	}
	document.cookie = name+"="+value+expire+"; path=/";
}

function readCookie(name)
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return '';
}

function eraseCookie(name)
{
	createCookie(name,"",-1);
}

