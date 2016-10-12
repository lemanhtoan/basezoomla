var QWeb = {
    basePath: '', // please set this value on document loading complete
    baseUrl: '', // please set this value on document loading complete

    addPageHandle: function(event, triggerElement, targetElement, ajaxOptions) {
        $(document).on(event, triggerElement, function (e) {
            e.preventDefault();
            var url = $(this).attr('href');

            if (history.pushState) {
                history.pushState({}, "", url);
            }

            QWeb.loadPage(url, targetElement, ajaxOptions);
        });

        window.addEventListener("popstate", function(e) {
            var url = window.location.href;
            QWeb.loadPage(url, targetElement, ajaxOptions);
        });
    },

    loadPage: function(url, element, ajaxOptions) {
        var container = $(element);

        var defaultAjaxOptions = {
            type: 'get',
            dataType: 'html',
            url: url
        };

        var ajaxOptions = $.extend({}, defaultAjaxOptions, ajaxOptions);

        var success = ajaxOptions.success;
        ajaxOptions.success = function (content) {
            var html = $(content);
            container.html($(element, html));
            if (typeof success != 'undefined') {
                success(content);
            }
        }

        $.ajax(ajaxOptions);
    },

    randomString: function(length) {
        var chars = '0123456789abcdefghiklmnopqrstuvwxyz'.split('');

        if ( ! length) {
            length = 8;
        }

        var str = '';
        for (var i = 0; i < length; i++) {
            str += chars[Math.floor(Math.random() * chars.length)];
        }
        return str;
    },

    formInputs: function($element) {
        this.onBeforeAjaxSend($element);
        return $('input[type="text"], input[type="hidden"], input[type="email"], input[type="range"], input[type="number"], input[type="checkbox"]:checked, input[type="radio"]:checked, select, textarea', $element);
    },

    onAfterAjaxLoad: function($element) {

    },
    onBeforeAjaxSend: function($element) {

    },

    update: function(id, targetClass, data, value) {
        if (! $.isPlainObject(data)) {
            var field = data;
            data = {};
            data[field] = value;
        }

        $.ajax({
            url: this.basePath + '/app/update',
            type: 'post',
            data: {id: id, targetClass: targetClass, data: data}
        });
    }
};

// loading html can be overrided
$.ajaxLoadingHtml = '<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>';
$.ajaxLoadingLargeHtml = '<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>';

$.fn.includeAjaxLoading = function() {
    $('i', $(this)).hide();
    $(this).prepend('<span class="ajax-load">' + $.ajaxLoadingHtml + '</span>');
};

$.fn.appendAjaxLoading = function() {
    $(this).after('<span class="ajax-load">' + $.ajaxLoadingHtml + '</span>');
};

$.fn.prependAjaxLoading = function() {
    $(this).before('<span class="ajax-load">' + $.ajaxLoadingHtml + '</span>');
};

$.fn.removeAjaxLoading = function() {
    $('.ajax-load', $(this).parent()).remove();
    $('i', $(this)).show();
};