//Title: Custom DropDown plugin by PC
//Documentation: http://designwithpc.com/Plugins/ddslick
//Author: PC 
//Website: http://designwithpc.com
//Twitter: http://twitter.com/chaudharyp

(function ($) {

    $.fn.ddslick = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exists.');
        }
    };

    var methods = {},

    //Set defauls for the control
    defaults = {
        data: [],
        keepJSONItemsOnTop: false,
        width: 260,
        height: null,
        background: "#eee",
        selectText: "",
        defaultSelectedIndex: null,
        truncateDescription: true,
        imagePosition: "left",
        showSelectedHTML: true,
        clickOffToClose: true,
        embedCSS: true,
        onSelected: function () { }
    },

    ddSelectHtml = '<div class="geotdd-select"><input class="geotdd-selected-value" type="hidden" /><a class="geotdd-selected"></a><span class="geotdd-pointer geotdd-pointer-down"></span></div>',
    ddOptionsHtml = '<ul class="geotdd-options"></ul>';


    //Public methods 
    methods.init = function (userOptions) {
        //Preserve the original defaults by passing an empty object as the target
        //The object is used to get global flags like embedCSS.
        var options = $.extend({}, defaults, userOptions);
        
        //Apply on all selected elements
        return this.each(function () {
            //Preserve the original defaults by passing an empty object as the target 
            //The object is used to save drop-down's corresponding settings and data.
            var options = $.extend({}, defaults, userOptions);
            
            var obj = $(this),
                data = obj.data('ddslick');
            //If the plugin has not been initialized yet
            if (!data) {

                var ddSelect = [], ddJson = options.data;

                //Get data from HTML select options
                obj.find('option').each(function () {
                    var $this = $(this), thisData = $this.data();
                    ddSelect.push({
                        text: $.trim($this.text()),
                        value: $this.val(),
                        selected: $this.is(':selected'),
                        f: $this.is(':selected'),
                        description: thisData.description,
                        imageSrc: thisData.imagesrc //keep it lowercase for HTML5 data-attributes
                    });
                });

                //Update Plugin data merging both HTML select data and JSON data for the dropdown
                if (options.keepJSONItemsOnTop)
                    $.merge(options.data, ddSelect);
                else options.data = $.merge(ddSelect, options.data);

                //Replace HTML select with empty placeholder, keep the original
                var original = obj, placeholder =$('<div id="' + obj.attr('id') +'-geotdd-placeholder"></div>');
                obj.replaceWith(placeholder);
                obj = placeholder;

                //Add classes and append ddSelectHtml & ddOptionsHtml to the container
                obj.addClass('geotdd-container').append(ddSelectHtml).append(ddOptionsHtml);

                // Inherit name attribute from original element
                obj.find("input.geotdd-selected-value")
                    .attr("id", $(original).attr("id"))
                    .attr("name", $(original).attr("name"));

                //Get newly created ddOptions and ddSelect to manipulate
                var ddSelect = obj.find('.geotdd-select'),
                    ddOptions = obj.find('.geotdd-options');

                //Set widths
                obj.css({ "max-width": options.width });

                //Set height
                if (options.height != null)
                    ddOptions.css({ height: options.height, overflow: 'auto' });

                //Add ddOptions to the container. Replace with template engine later.
                $.each(options.data, function (index, item) {
                    if (item.f) options.defaultSelectedIndex = index;
                    ddOptions.append('<li>' +
                        '<a class="geotdd-option">' +
                            (item.value ? ' <input class="geotdd-option-value" type="hidden" value="' + item.value + '" />' : '') +
                            (item.imageSrc ? ' <span class="' + item.imageSrc + ' geotdd-option-image' + (options.imagePosition == "right" ? ' geotdd-image-right' : '') + '"></span>' : '') +
                            (item.text ? ' <label class="geotdd-option-text">' + item.text + '</label>' : '') +
                            (item.description ? ' <small class="geotdd-option-description geotdd-desc">' + item.description + '</small>' : '') +
                        '</a>' +
                    '</li>');
                });

                //Save plugin data.
                var pluginData = {
                    settings: options,
                    original: original,
                    selectedIndex: -1,
                    selectedItem: null,
                    selectedData: null
                }
                obj.data('ddslick', pluginData);

                //Check if needs to show the select text, otherwise show selected or default selection
                if (options.selectText.length > 0 && options.defaultSelectedIndex == null) {
                    obj.find('.geotdd-selected').html(options.selectText);
                }
                else {
                    var index = (options.defaultSelectedIndex != null && options.defaultSelectedIndex >= 0 && options.defaultSelectedIndex < options.data.length)
                                ? options.defaultSelectedIndex
                                : 0;
                    selectIndex(obj, index, false);
                }

                //EVENTS
                //Displaying options
                obj.find('.geotdd-select').on('click.ddslick', function () {
                    open(obj);
                });

                //Selecting an option
                obj.find('.geotdd-option').on('click.ddslick', function () {
                    selectIndex(obj, $(this).closest('li').index());
                });

                //Click anywhere to close
                if (options.clickOffToClose) {
                    ddOptions.addClass('geotdd-click-off-close');
                    obj.on('click.ddslick', function (e) { e.stopPropagation(); });
                    $('body').on('click', function () {
                    $('.geotdd-open').removeClass('geotdd-open');
                        $('.geotdd-click-off-close').slideUp(50).siblings('.geotdd-select').find('.geotdd-pointer').removeClass('geotdd-pointer-up');
                    });
                }
            }
        });
    };

    //Public method to select an option by its index
    methods.select = function (options) {
        return this.each(function () {
            if (options.index!==undefined)
                selectIndex($(this), options.index);
            if (options.id)
                selectId($(this), options.id);
        });
    }

    //Public method to open drop down
    methods.open = function () {
        return this.each(function () {
            var $this = $(this),
                pluginData = $this.data('ddslick');

            //Check if plugin is initialized
            if (pluginData)
                open($this);
        });
    };

    //Public method to close drop down
    methods.close = function () {
        return this.each(function () {
            var $this = $(this),
                pluginData = $this.data('ddslick');

            //Check if plugin is initialized
            if (pluginData)
                close($this);
        });
    };

    //Public method to destroy. Unbind all events and restore the original Html select/options
    methods.destroy = function () {
        return this.each(function () {
            var $this = $(this),
                pluginData = $this.data('ddslick');

            //Check if already destroyed
            if (pluginData) {
                var originalElement = pluginData.original;
                $this.removeData('ddslick').unbind('.ddslick').replaceWith(originalElement);
            }
        });
    }
    
     //Private: Select id
    function selectId(obj, id) {
    
       var index = obj.find(".geotdd-option-value[value= '" + id + "']").parents("li").prevAll().length;
       selectIndex(obj, index);
       
    }

    //Private: Select index
    function selectIndex(obj, index, doCallback) {
       // If true, fire the onSelected callback; true by if not specified
       if (typeof doCallback === 'undefined') {
            doCallback = true;
        }
        //Get plugin data
        var pluginData = obj.data('ddslick');

        //Get required elements
        var ddSelected = obj.find('.geotdd-selected'),
            ddSelectedValue = ddSelected.siblings('.geotdd-selected-value'),
            ddOptions = obj.find('.geotdd-options'),
            ddPointer = ddSelected.siblings('.geotdd-pointer'),
            selectedOption = obj.find('.geotdd-option').eq(index),
            selectedLiItem = selectedOption.closest('li'),
            settings = pluginData.settings,
            selectedData = pluginData.settings.data[index];

        //Highlight selected option
        obj.find('.geotdd-option').removeClass('geotdd-option-selected');
        selectedOption.addClass('geotdd-option-selected');

        //Update or Set plugin data with new selection
        pluginData.selectedIndex = index;
        pluginData.selectedItem = selectedLiItem;
        pluginData.selectedData = selectedData;

        //If set to display to full html, add html
        if (settings.showSelectedHTML) {
            ddSelected.html(
                    (selectedData.imageSrc ? '<span class="' + selectedData.imageSrc + ' geotdd-selected-image' + (settings.imagePosition == "right" ? ' geotdd-image-right' : '') + '"></span>' : '') +
                    (selectedData.text ? '<label class="geotdd-selected-text">' + selectedData.text + '</label>' : '') +
                    (selectedData.description ? '<small class="geotdd-selected-description geotdd-desc' + (settings.truncateDescription ? ' geotdd-selected-description-truncated' : '') + '" >' + selectedData.description + '</small>' : '')
                );

        }
            //Else only display text as selection
        else ddSelected.html(selectedData.text);

        //Updating selected option value
        ddSelectedValue.val(selectedData.value);

        //BONUS! Update the original element attribute with the new selection
        pluginData.original.val(selectedData.value);
        obj.data('ddslick', pluginData);

        //Close options on selection
        close(obj);

        //Adjust appearence for selected option
        adjustSelectedHeight(obj);

        //Callback function on selection
        if (doCallback && typeof settings.onSelected == 'function') {
            settings.onSelected.call(this, pluginData);
        }
    }

    //Private: Close the drop down options
    function open(obj) {

        var $this = obj.find('.geotdd-select'),
            ddOptions = $this.siblings('.geotdd-options'),
            ddPointer = $this.find('.geotdd-pointer'),
            wasOpen = ddOptions.is(':visible');

        //Close all open options (multiple plugins) on the page
        $('.geotdd-click-off-close').not(ddOptions).slideUp(50);
        $('.geotdd-pointer').removeClass('geotdd-pointer-up');
        $this.removeClass('geotdd-open');

        if (wasOpen) {
            ddOptions.slideUp('fast');
            ddPointer.removeClass('geotdd-pointer-up');
            $this.removeClass('geotdd-open');
        }
        else {
            $this.addClass('geotdd-open');
            ddOptions.slideDown('fast');
            ddPointer.addClass('geotdd-pointer-up');
        }

        //Fix text height (i.e. display title in center), if there is no description
        adjustOptionsHeight(obj);
    }

    //Private: Close the drop down options
    function close(obj) {
        //Close drop down and adjust pointer direction
        obj.find('.geotdd-select').removeClass('geotdd-open');
        obj.find('.geotdd-options').slideUp(50);
        obj.find('.geotdd-pointer').removeClass('geotdd-pointer-up').removeClass('geotdd-pointer-up');
    }

    //Private: Adjust appearence for selected option (move title to middle), when no desripction
    function adjustSelectedHeight(obj) {

        //Get height of geotdd-selected
        var lSHeight = obj.find('.geotdd-select').css('height');

        //Check if there is selected description
        var descriptionSelected = obj.find('.geotdd-selected-description');
        var imgSelected = obj.find('.geotdd-selected-image');

    }

    //Private: Adjust appearence for drop down options (move title to middle), when no desripction
    function adjustOptionsHeight(obj) {
        obj.find('.geotdd-option').each(function () {
            var $this = $(this);
            var lOHeight = $this.css('height');
            var descriptionOption = $this.find('.geotdd-option-description');
            var imgOption = obj.find('.geotdd-option-image');
  
        });
    }

})(jQuery);