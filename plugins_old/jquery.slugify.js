$(function(){
	jQuery.fn.slugify = function(target){
        var self = $(this);
		$(target).keyup(function() {
            self.val(slugify($(this).val()));
		});
	};

    $('.slugify').each(function() {
        $(this).slugify($(this).attr('data-target'));
    });
});

/**
 * Transform text into a URL slug: spaces turned into dashes, remove non alnum
 * @param string str
 */
var slugify = function(str) {
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();

  // remove accents, swap ñ for n, etc
  var from = "đãàáäâảạăắằẳẵặâấầẫậẩẽèéëêếềễểệẻẹìíïîỉịĩoòóỏõọơờớởỡợôồốổỗộuùúủũụưừứửữựỳýỷỹỵ·/_,:;";
  var to   = "daaaaaaaaaaaaaaaaaaaeeeeeeeeeeeeiiiiiiioooooooooooooooooouuuuuuuuuuuuyyyyy------";
  for (var i=0, l=from.length ; i<l ; i++) {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-'); // collapse dashes

  return str;
};
