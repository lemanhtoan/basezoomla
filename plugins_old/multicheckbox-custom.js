(function($){
	jQuery.fn.multicheckboxCustom = function() {
		$(this).each(function() {
            // the container that contains all radio boxes
            var self = $(this);

            // define some variable to use later
            // the custom radio when user want to pick custom value
            var customElement = $('input[value="custom"]', self);
            // clear it value and checked state, we define it below
            customElement.val('').removeAttr('checked');

            // "value" this is the current value of element
            // if the value does not exists in the list of radio boxes
            // so it will be the custom value and need to be set to the additional text input
            var value = jQuery.parseJSON($(this).data('value'));
            if ($('input[value="' + value + '"]', self).length != 0) {
                value = '';
            }

            // create additional text input
            customElement.val(value);
            self.append(
                '<div class="custom-container" style="margin-top:7px; display:none">' +
                    '<input type="text" value="' + value + '" />' +
                    '</div>'
            );
            var customContainer = $('.custom-container', self);

            // register the event to show/hide the addtitional text input
            // when user choose or drop the custom radio box
            $('input[type="radio"]', self).change(function() {
                if (customElement.is(':checked')) {
                    customContainer.show();
                } else {
                    customContainer.hide();
                }
            });

            // everything is entered to additional text input will be push to custom radio box value
            $('input', customContainer).keyup(function() {
                customElement.val($(this).val());
            });

            // finally, if the custom value is not empty, trigger click event
            // to open the addtitional text input
            if (value != '') {
                customElement.click();
            }
        });
	};

    $('.multicheckbox-custom').multicheckboxCustom();
})(jQuery);