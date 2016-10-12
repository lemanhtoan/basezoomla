function imageupload(target,imgaction){
  var h=100
  var w=350
  var winl = (screen.width - w) / 2;
  var wint = (screen.height - h) / 2;
  var myimgdir = document.getElementById('imgdirpath');
    if(myimgdir){
     childwindow=window.open('','popuppage','scrollbars=no,'+'width='+w+',height='+h+',top='+wint+',left='+winl);
     if (childwindow.opener == null) childWindow.opener = self;
     childwindow.document.write("<html>");
     childwindow.document.write("<head>");
     childwindow.document.write("<title>PDG Image Uploader</title>");
     childwindow.document.write("</head>");
     childwindow.document.write("<body>");
     childwindow.document.write("<center>");
     childwindow.document.write('<FORM ENCTYPE="multipart/form-data" ACTION="'+ imgaction +'" ');
     childwindow.document.write('onsubmit=\'javascript:var len=this.uploadfile.value.length;'); 
     childwindow.document.write('var sl1=this.uploadfile.value.lastIndexOf("/");');
     childwindow.document.write('var sl2=this.uploadfile.value.lastIndexOf("\\\\");');
     childwindow.document.write('var mystr=this.uploadfile.value;');
     childwindow.document.write('if(sl1>=0){mystr=this.uploadfile.value.substr(sl1+1,len);}'); 
     childwindow.document.write('else if(sl2>=0){mystr=this.uploadfile.value.substr(sl2+1,len);}'); 
     childwindow.document.write('opener.' +target + '.value="/'+myimgdir.value+'/"+mystr;');
     childwindow.document.write('opener.' +target + '.focus();'); 
     childwindow.document.write('\' METHOD=POST>');
     childwindow.document.write('Upload Image File: <INPUT NAME="uploadfile" TYPE="file">');
     childwindow.document.write('<INPUT type="submit" class="pdgbutton" name=displaydataxfer VALUE="Send File">');
     childwindow.document.write('</FORM>');
     childwindow.document.write("</body>");
     childwindow.document.write("</html>");
   }
    else{
     alert('You have the wrong product template!');
    }
};

function imageuploadprod(target,thumbtarget,imgaction){
  var h=100;
  var w=350;
  var winl = (screen.width - w) / 2;
  var wint = (screen.height - h) / 2;
  var myimgdir = document.getElementById('imgdirpath');
    if(myimgdir){
     var childwindow=window.open('','popuppage','scrollbars=no,'+'width='+w+',height='+h+',top='+wint+',left='+winl);
     if (childwindow.opener == null) childWindow.opener = self;
     childwindow.document.write('<html>');
     childwindow.document.write('<head>');
     childwindow.document.write('<title>PDG Image Uploader</title>');
     childwindow.document.write('<script language="javaScript">');
     childwindow.document.write('function Set_Cookie( name, value){');
     childwindow.document.write('	var expires =  1000 * 60 * 60 ;');
     childwindow.document.write('	var today = new Date();');
     childwindow.document.write('	today.setTime( today.getTime() );');
     childwindow.document.write('	var expires_date = new Date( today.getTime() + (expires) );');
     childwindow.document.write('	document.cookie = name + "=" +escape( value ) + ";path=/;expires=" + expires_date.toGMTString()+ ";";');
     childwindow.document.write('}');

     childwindow.document.write('function Delete_Cookie( name ) {');
     childwindow.document.write('	if ( Get_Cookie( name ) ) {');
     childwindow.document.write('		document.cookie = name + "=;path=/;expires=Thu, 01-Jan-1970 00:00:01 GMT";');
     childwindow.document.write('	}');
     childwindow.document.write('}');
     if(thumbtarget.length>0){
	     childwindow.document.write('Set_Cookie(\'enablethumb\',\'yes\');');
     }
     else{
	     childwindow.document.write('Delete_Cookie(\'enablethumb\');');
     }
     childwindow.document.write('</script>');
     childwindow.document.write('</head>');
     childwindow.document.write('<body>');
     childwindow.document.write('<center>');
     childwindow.document.write('<FORM ENCTYPE="multipart/form-data" ACTION="'+ imgaction +'" ');
     childwindow.document.write('onsubmit=\'javascript:var len=this.uploadfile.value.length;'); 
     childwindow.document.write('var sl1=this.uploadfile.value.lastIndexOf("/");');
     childwindow.document.write('var sl2=this.uploadfile.value.lastIndexOf("\\\\");');
     childwindow.document.write('var mystr=this.uploadfile.value;');
     childwindow.document.write('if(sl1>=0){mystr=this.uploadfile.value.substr(sl1+1,len);}'); 
     childwindow.document.write('else if(sl2>=0){mystr=this.uploadfile.value.substr(sl2+1,len);}'); 
     childwindow.document.write('opener.' +target + '.value="/'+myimgdir.value+'/"+mystr;');
     childwindow.document.write('opener.' +target + '.focus();'); 
     if(thumbtarget.length>0){
	     childwindow.document.write('if(this.makethumb.checked){');
	     childwindow.document.write('var mythumbstr=this.uploadfile.value;');
	     childwindow.document.write('var mythumbstr2=this.uploadfile.value;');
	     childwindow.document.write('var sl3=mystr.lastIndexOf(".");');
	     childwindow.document.write('if(sl3>=0){'); 
	     childwindow.document.write('mythumbstr=mystr.substr(0,sl3);'); 
	     childwindow.document.write('mythumbstr2=mystr.substr(sl3,len);}'); 
	     childwindow.document.write('opener.' +thumbtarget + '.value="/'+myimgdir.value+'/"+mythumbstr+"-thumb"+mythumbstr2;');
	     childwindow.document.write('}');
     }
     childwindow.document.write('\' METHOD=POST>');
     childwindow.document.write('Upload Image File: <INPUT NAME="uploadfile" TYPE="file">');
     childwindow.document.write('<INPUT type="submit" class="pdgbutton" name="displaydataxfer" VALUE="Send File"><br>');
     if(thumbtarget.length>0){
	     childwindow.document.write('<INPUT type="checkbox" name="makethumb" VALUE="yes" checked onClick="javascript:if(this.checked){');
	     childwindow.document.write('Set_Cookie(\'enablethumb\',\'yes\');}else{Delete_Cookie(\'enablethumb\');"> make thumbnail<br>');
     }
     childwindow.document.write('</FORM>');
     childwindow.document.write('</body>');
     childwindow.document.write('</html>');
   }
    else{
     alert('You have the wrong product template!');
    }
}

var oldimgid="";
function displayeditimage(imgid){
var imgele=document.getElementById(imgid);
if(oldimgid.length>0){
	imgele=document.getElementById(oldimgid);
	if(oldimgid==imgid){
		if(imgele.style.visibility=='hidden'){
			imgele.style.visibility='visible';
			imgele.style.display='block';
			imgele.style.zindex='9999';
			return;
		}
		else{
			alert('You must click on [Update] to save your changes!');
			imgele.style.visibility='hidden';
			imgele.style.display='none';
			imgele.style.zindex='-1';
			return;
		}
	}
	else{
		imgele.style.visibility='hidden';
		imgele.style.display='none';
		imgele.style.zindex='-1';
		imgele=document.getElementById(imgid);
	}
}
oldimgid=imgid;
imgele.style.visibility='visible';
imgele.style.display='block';
imgele.style.zindex='9999';
}

