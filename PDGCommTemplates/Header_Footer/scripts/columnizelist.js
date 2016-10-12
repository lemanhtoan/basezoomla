/*
Copyright (c) 2007 Christian yates
christianyates.com
chris [at] christianyates [dot] com
Licensed under the MIT License: 
http://www.opensource.org/licenses/mit-license.php

Inspired by work of Ingo Schommer
http://chillu.com/2007/9/30/jquery-columnizelist-plugin
*/
jQuery.fn.columnizeList = function(settings){
  settings = jQuery.extend({
    cols: 3,
    constrainWidth: 0
  }, settings);
    // var type=this.getNodeType();
    var prevColNum = 10000; // Start high to avoid appending to the wrong column
    var size = $('li',this).size();
    var percol = Math.ceil(size/settings.cols);
    var container = this;
    var tag = container[0].tagName.toLowerCase();
    var classN = container[0].className;
    var colwidth = Math.floor($(container).width()/settings.cols);
    var widthstring = '';
    var maxheight = 0;
    if (settings.constrainWidth) {
      widthstring = 'width:'+colwidth+'px;';
    };
    // Prevent stomping on existing ids with pseudo-random string
    var rand = Math.random().toPrecision(6)*10e6;
    $('<ul id="container'+rand+'" class="'+classN+'"></ul>').css({width:$(container).width()+'px'}).insertBefore(container);
    $('li',this).each(function(i) {
      var currentColNum = Math.floor(i/percol);
      if(prevColNum != currentColNum) {
        if ($("#col"+rand+"-"+prevColNum).height() > maxheight) { maxheight = $("#col"+rand+"-"+prevColNum).height()};
        $("#container"+rand).append('<li style="float:left;list-style:none;margin:0;padding:0;'+widthstring+' "><'+tag+' id="col'+rand+'-'+currentColNum+'"></'+tag+'></li>');
      }
      $(this).attr("value",i+1).appendTo("#col"+rand+'-'+currentColNum);
      prevColNum = currentColNum;
    });
    $("#container"+rand).after('<div style="clear: both;"></div>');
    $("#container"+rand+" "+tag).height(maxheight);
    this.remove();
return this;
}