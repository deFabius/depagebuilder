(function ($) {
    ko.bindingHandlers.colorPicker = {
        init: function (element, valueAccessor, allBindings, viewModel, bindingContext) {
            var value = valueAccessor();
            var valueUnwrapped = ko.unwrap(value);

            var btn = $(element);
            var colorInput = $('<input>');

            colorInput.addClass("color-sample jscolor {value:'ffffff'}");
            colorInput.change(function(color) {
                var value = valueAccessor();
                value('#' + jQuery(this).val());
                colorInput.css("color", "transparent");
            });
            btn.append(colorInput);
            //            btn.html('<input class="color-sample jscolor {valueElement:null,value:\'ffffff\'}" />');
            jscolor.installByClassName('jscolor');
            colorInput.css("color", "transparent");

            btn.click(function (e) {
                e.preventDefault();
                btn.children('.jscolor').get(0).jscolor.show();
            })
        },
        update: function (element, valueAccessor, allBindings, viewModel, bindingContext) {
            // This will be called once when the binding is first applied to an element,
            // and again whenever any observables/computeds that are accessed change
            // Update the DOM element based on the supplied values here.
        }
    };

    ko.bindingHandlers.contentEditable = {
        init: function(element, valueAccessor) {
            var value = valueAccessor();
            var valueUnwrapped = ko.unwrap(value);

            if (valueUnwrapped !== '') {
                $(element).html(valueUnwrapped);
            }

            $(element).keyup(function() {
                var value = valueAccessor();
                value($(element).html());
            });

            $(element).blur(function() {
                var value = valueAccessor();
                value($(element).html());
            });
        }
    }
}(jQuery));

function updateColor(newColor) {
    alert(newColor)
}