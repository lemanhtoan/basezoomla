var file_browser_callback;
var $elfinder;
var $fileManagerModal;

$(function() {
    // disable ajax cache in all browsers
    $.ajaxSetup({ cache: false });

    $fileManagerModal = $('#file-manager-modal').remodal({hashTracking: false});

    // resolve conflicting between bootstrap modal and tinymce dialog
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });

    $(document).on('opened', '#file-manager-modal', function (e) {
        var $container = $('#elfinder');
        if (! $elfinder) {
            var height = $container.parent().height() - 50;

            $elfinder = $('#elfinder').elfinder({
                // lang: 'ru',             // language (OPTIONAL)
                url: QWeb.basePath + '/elfinder/connector',  // connector URL (REQUIRED)
                getFileCallback: function(file) {
                    window.file_browser_getfile_callback(file);
                },
                resizable: false,
                height: height,
                commandsOptions: {
                    getfile: {
                        multiple: true
                    }
                }
            }).elfinder('instance');

            $(window).resize(function () {
                var height = $container.parent().height() - 50;
                if ($container.height() != height) {
                    $container.height(height).resize();
                }
            });
        }
    });

    window.file_browser_callback = function (field_name, url, type, win) {
        $fileManagerModal.open();
        window.file_browser_getfile_callback = function(file) {
            $('#' + field_name).val(file[0].url);
            $fileManagerModal.close();
            $elfinder.disable();
        }

        return false;
    }

    QWeb.onAfterAjaxLoad = function($element) {
        $('select', $element).chosen();

        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };

        $(".sortable", $element).sortable({
            connectWith: ".connected-sortable",
            placeholder: "ui-state-highlight",
            helper: fixHelper
        });


        if (typeof tinymce !== 'undefined') {
            if (! $element.attr('id')) {
                $element.attr('id', QWeb.randomString());
            }

            tinymce.init({
                selector: "#" + $element.attr('id') + " .tinymce",
                theme: "modern",
                skin: 'light',
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste colorpicker"
                ],
                image_advtab: true,
                toolbar1: "insertfile undo redo | styleselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview media | colorpicker | code fullscreen",
                height: 350,
                relative_urls: false,
                remove_script_host: false,
                statusbar: true,
                file_browser_callback: (typeof(window.file_browser_callback) == 'undefined' ? null : window.file_browser_callback)
            });

            tinymce.init({
                selector: "#" + $element.attr('id') + " .tinymce-minimal",
                theme: "modern",
                skin: 'light',
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste colorpicker"
                ],
                image_advtab: true,
                menubar: false,
                toolbar1: "fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | link image | colorpicker | code fullscreen",
                height: 120,
                relative_urls: false,
                remove_script_host: false,
                statusbar: false,
                file_browser_callback: (typeof(window.file_browser_callback) == 'undefined' ? null : window.file_browser_callback)
            });
        }

        $('.where-element-container', $element).each(function() {
            var $container = $(this);
            var targetElement = $container.attr('data-target');
            if (! targetElement) {
                return;
            }

            var $targetElement = $('[name="'+targetElement+'"]');
            var $addButton = $container.find('.add-condition');

            $targetElement.change(function() {
                $container.find('.condition-row').remove();
                $addButton.includeAjaxLoading();

                $.ajax({
                    url: QWeb.basePath + '/app/where-condition-template',
                    data: {targetClass: $(this).val(), name: $container.data('name')},
                    type: 'get',
                    dataType: 'html',
                    success: function(response) {
                        $container.attr('data-template', response);
                        $addButton.removeAjaxLoading();
                    }
                });
            });
        });

        $('.order-by-element-container', $element).each(function() {
            var $container = $(this);
            var targetElement = $container.attr('data-target');
            if (! targetElement) {
                return;
            }

            var $targetElement = $('[name="'+targetElement+'"]');
            var $addButton = $container.find('.add-condition');

            $targetElement.change(function() {
                $container.find('.condition-row').remove();
                $addButton.includeAjaxLoading();

                $.ajax({
                    url: QWeb.basePath + '/app/order-by-condition-template',
                    data: {targetClass: $(this).val(), name: $container.data('name')},
                    type: 'get',
                    dataType: 'html',
                    success: function(response) {
                        $container.attr('data-template', response);
                        $addButton.removeAjaxLoading();
                    }
                });
            });
        });
    }

    QWeb.onBeforeAjaxSend = function($element) {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }
    }

    QWeb.onAfterAjaxLoad($('body'));

    /**
     * Bootstrap datetime picker
     *
     * @link: http://eonasdan.github.io/bootstrap-datetimepicker
     */
    var datetimePickerOptions = {
        format: "YYYY-MM-DD HH:mm",
        pickDate: true,                 //en/disables the date picker
        pickTime: true,                 //en/disables the time picker
        useMinutes: true,               //en/disables the minutes picker
        useSeconds: true,               //en/disables the seconds picker
        useCurrent: true,               //when true, picker will set the value to the current date/time
        minuteStepping:1,               //set the minute stepping
        minDate: '1/1/1900',            //set a minimum date
        maxDate: null,                  //set a maximum date (defaults to today +100 years)
        showToday: true,                //shows the today indicator
        language:'en',                  //sets language locale
        defaultDate:"",                 //sets a default date, accepts js dates, strings and moment objects
        disabledDates:[],               //an array of dates that cannot be selected
        enabledDates:[],                //an array of dates that can be selected
        icons : {
            time: 'glyphicon glyphicon-time',
            date: 'glyphicon glyphicon-calendar',
            up:   'glyphicon glyphicon-chevron-up',
            down: 'glyphicon glyphicon-chevron-down'
        },
        useStrict: false,               //use "strict" when validating dates
        sideBySide: false,              //show the date and time picker side by side
        daysOfWeekDisabled:[]          //for example use daysOfWeekDisabled: [0,6] to disable weekends
    };

    $('input[type="datetime"],input[type="date"],input[type="time"]').each(function() {
        var ipdt_parent = $(this).parent();
        if (ipdt_parent.attr('data-name')=='datetime-picker') {
            switch($(this).attr('data-type')){
                case "date":
                    datetimePickerOptions.pickTime = false;
                    datetimePickerOptions.useMinutes = false;
                    datetimePickerOptions.useSeconds = false;
                    datetimePickerOptions.format = "YYYY-MM-DD";
                    break;
                case "time":
                    datetimePickerOptions.pickDate = false;
                    datetimePickerOptions.useSeconds = false;
                    datetimePickerOptions.format = "HH:mm";
                    break;
                default:
                    break;
            }
            ipdt_parent.datetimepicker(datetimePickerOptions);
        }
    });

    /**
     * Enable bootstrap tooltip plugin.
     *
     * @link: http://getbootstrap.com/2.3.2/javascript.html#tooltips
     */
    $('body').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });

    /**
     * Enable bootstrap popover plugin.
     *
     * @link: http://getbootstrap.com/2.3.2/javascript.html#popovers
     */
    $('[data-toggle="popover"]').popover();

    /**
     * File selector
     */
    $(document).on('click', '.file-select-select', function () {
        var $container = $(this).parents('.file-select-container');

        $fileManagerModal.open();

        window.file_browser_getfile_callback = function(file) {
            $('.img-thumbnail', $container).attr('src', file[0].url).show();
            $('.file-select-hidden', $container).val(file[0].object_id);
            $fileManagerModal.close();
            $elfinder.disable();
        }
    });

    $(document).on('click', '.file-select-remove', function () {
        var container = $(this).parents('.file-select-container');
        $('.img-thumbnail', container).hide();
        $('.file-select-hidden', container).val('');
    });

    /**
     * Live update element
     */
    $(document).on('change', '.live-update-element', function (e) {
        QWeb.update(
            $(this).attr('data-id'),
            $(this).attr('data-target-class'),
            $(this).attr('data-field'),
            ($(this).is(':checked') ? 1 : 0)
        );
    });

    /**
     * File list
     */
    $(document).on('click', '.file-list-select', function () {
        var $container = $(this).parents('.file-list-container');

        $fileManagerModal.open();

        window.file_browser_getfile_callback = function(file) {
            var $thumbsContainer = $('.file-list-thumbs', $container);
            var $name = $container.attr('data-name');
            var max = $container.attr('data-maximum');

            if (max) {
                var currentCount = $('.file-list-img', $thumbsContainer).length;
                if ((currentCount + file.length) > max) {
                    bootbox.alert("You can not add more than "+max+" items");
                    var canAdd = max - currentCount;
                }
            }

            $.each(file, function(index, value) {
                if (max && canAdd < 1) {
                    return false;
                }

                if ($('.file-list-img[data-id="'+value.object_id+'"]', $thumbsContainer).length < 1) {
                    if (max) {
                        canAdd--;
                    }
                    $thumbsContainer.append(
                        '<li class="file-list-img" data-id="'+value.object_id+'">' +
                            '<img src="' + value.tmb + '" />' +
                            '<input type="hidden" name="'+$name+'[]" value="'+value.object_id+'" />' +
                            '<i class="fa fa-lg fa-close btn-close"></i>' +
                        '</li>'
                    );
                }
            });
            $fileManagerModal.close();
            $elfinder.disable();
        }
    });

    $(document).on('click', '.file-list-img .btn-close', function () {
        $(this).parents('.file-list-img').remove();
    });

    /**
     * Collection fieldset
     */
    $(document).on('click', '.collection-addmore', function () {
        var $container = $(this).parent();
        var max = $(this).attr('data-maximum');
        if (max) {
            var count = $('.collection-container', $container).length;
            if (count >= max) {
                bootbox.alert("You can not add more item");
                return false;
            }
        }

        var template = $('.collection-template', $container).data('template');
        template = template.replace(/__index__/g, QWeb.randomString());

        $('.collection-list', $container).append(template);
        var $added = $('.collection-list .collection-container:last', $container);
        QWeb.onAfterAjaxLoad($added);
    });

    $(document).on('click', '.collection-close', function () {
        $(this).parent().remove();
    });

    /**
     * Where & order by element
     */
    $(document).on('click', '.where-element-container .add-condition', function () {
        if ($(this).find('.ajax-load').length) {
            // current loading template
            return;
        }

        var $container = $(this).parents('.where-element-container:first');
        var template = $container.attr('data-template');
        template = template.replace(/__index__/g, QWeb.randomString());

        $(this).before(template);
    });

    $(document).on('click', '.order-by-element-container .add-condition', function () {
        if ($(this).find('.ajax-load').length) {
            // current loading template
            return;
        }

        var $container = $(this).parents('.order-by-element-container:first');
        var template = $container.attr('data-template');
        template = template.replace(/__index__/g, QWeb.randomString());

        $(this).before(template);
    });

    $(document).on('click', '.condition-remove', function () {
        $(this).parent().remove();
    });

    /**
     * Multiple input
     */
    $(document).on('click', '.multiple-input-add', function () {
        var container = $(this).parents('.multiple-input-container');
        var template = container.data('template');

        $(this).before(template);
    });

    $(document).on('click', '.multiple-input-remove', function () {
        $(this).parents('.multiple-input-input').remove();
    });

    /**
     * Check box check-all check-one
     */
    $(document).on('click', '.check-all', function () {
        var checked = $(this).is(":checked");
        $('.check-one', $(this).parents('table')).prop("checked", checked);
    });

    $(document).on('click', '.check-one', function () {
        var checked = $(this).is(":checked");
        if ( ! checked) {
            $('.check-all', $(this).parents('table')).prop("checked", false);
        }
    });

    /**
     * Checkbox shift-click
     */
    $(".check-one").shiftClick({'cache': false});

    /**
     * Load-on-click
     */
    $(document).on('click', '.load-on-click', function () {
        $(this).includeAjaxLoading();
    });

    /**
     * Confirm an action before execute, dependency on bootstrap-bootbox.
     *
     * @link: http://bootboxjs.com/
     */
    if (typeof(bootbox) != 'undefined') {
        $(document).on('click', '[data-confirm]', function() {
            var self = $(this);
            if (self.hasClass('disabled')) {
                return false;
            }

            if (self.hasClass('confirm-ok')) {
                self.removeClass('confirm-ok');
                return true;
            }

            bootbox.confirm($(this).data('confirm'), function(result) {
                if (result == true) {
                    self.addClass('confirm-ok');
                    self.context.click();
                }
            });

            return false;
        });
    }
});
