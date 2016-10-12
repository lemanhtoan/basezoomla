function addEvent(obj,evType,fn,useCapture){
 var r = false;
 if (obj.addEventListener){
    obj.addEventListener(evType, fn, useCapture);
    r = true;
  }
  else if (obj.attachEvent) {
    var id = obj.sourceIndex || -1;

    if (!fn[evType + id]) {
      var f = fn[evType + id] = function(e) {
        var o = document.all[id] || document;
        o._f = fn;
        var s = o._f(e);
        o._f = null;
        return s;
      };

      r = obj.attachEvent("on" + evType, f);
      obj = null;
    }
  }
  return r;
};

function removeEvent(obj, evType, fn){
  var r = false
  if (obj.removeEventListener){
    obj.removeEventListener(evType, fn, false);
    r = true;
  } else if (obj.detachEvent) {
    r = obj.detachEvent("on" + evType, fn[evType + (obj.sourceIndex || -1)]);
  }
  return r;
};


/*
AddEvent Manager (c) 2005 Angus Turnbull http://www.twinhelix.com
Free usage permitted as long as this credit notice remains intact.
*/
// REST OF SCRIPT NOT USED HERE
// Optional cancelEvent() function you can call within your event handlers to
// stop them performing the normal browser action or kill the event entirely.
// Pass an event object, and the second "c" parameter cancels event bubbling.
function cancelEvent(e, c)
{
 e.returnValue = false;
 if (e.preventDefault) e.preventDefault();
 if (c)
 {
  e.cancelBubble = true;
  if (e.stopPropagation) e.stopPropagation();
 }
};
