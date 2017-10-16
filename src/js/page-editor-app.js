function pageEditorApp(data) {
    var self = this;

    self.rows = ko.observableArray();

    self.addRow = function (data) {
        var obj = {
            text: ko.observable(),
            bg: ko.observable()
        };

        if (data) {
            obj.text(data.text);
            obj.bg(data.bg);
        }

        self.rows.push(obj);
    }

    for (var i = 0; i < data.length; i++) {
        self.addRow(data[i]);
    }

    self.removeRow = function (row) {
        self.rows.remove(row);
    }

    self.getBgImageCss = function (rowData) {
        return 'url(' + rowData.bg() + ')';
    }

    self.pickImage = function (imageVM) {
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: false,
            library: {
                type: 'image',
            }
        });

        image_frame.on('close', function () {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
            if (selection.length === 0) return;
            var image = selection.first().attributes.url;
            imageVM.bg(image);
        });

        image_frame.on('open', function () {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manage

        });

        image_frame.open();
    };


    return self;
}

jQuery(document).ready(function ($) {

    // Ajax request to refresh the image preview
    function Refresh_Image(the_id) {
        var data = {
            action: 'myprefix_get_image',
            id: the_id
        };

        jQuery.get(ajaxurl, data, function (response) {

            if (response.success === true) {
                jQuery('#myprefix-preview-image').replaceWith(response.data.image);
            }
        });
    }
});

jQuery(document).ready(function ($) {

    /* Editor Toggle Function */
    function fxPb_Editor_Toggle() {
        if ($('#depagebuilder_switch').is(":checked")) {
            $('#postdivrich').hide();
            $('#de-page-builder').show();
        }
        else {
            $('#postdivrich').show();
            $('#de-page-builder').hide();
        }
    }

    /* Toggle On Page Load */
    fxPb_Editor_Toggle();

    /* If user change page template drop down */
    $("#depagebuilder_switch").change(function (e) {
        fxPb_Editor_Toggle();
    });

});