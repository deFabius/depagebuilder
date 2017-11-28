function WidgetHero() {
    var self = this;
    this.text = ko.observable();
    this.bg = ko.observable();
    this.align = ko.observable('left');
    this.fontColor = ko.observable('ffffff');
    this.type = 'dpb-hero';

    this.populate = function (data) {
        if (data) {
            this.text(data.text);
            this.bg(data.bg);
            this.align(data.align || 'left');
            this.fontColor(data.fontColor);
        }
    }

    this.pickImage = function (imageVM) {
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
            self.bg(image);
        });

        image_frame.on('open', function () {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manage

        });

        image_frame.open();
    };
}

function SlideShow() {
    var self = this;
    this.type = 'dpb-slideshow';
    this.pictures = ko.observableArray([]);
    this.selectedPic = ko.observable();

    this.populate = function(data) {
        var pictures = data.pictures ? JSON.parse(data.pictures).map(function(model) {
            return {
                full: model.full,
                large: model.large,
                medium: model.medium,
                thumbnail: model.thumbnail,
                text: ko.observable(model.text),
                align: ko.observable(model.align),
                link: ko.observable(model.link),
                linkLabel: ko.observable(model.linkLabel),
                fontColor: ko.observable(model.fontColor)
            }
        }) : [];
        if (data) {
            self.pictures(pictures);
            self.selectedPic(pictures[0] || null);
        }
    }

    this.showDetails = function(pic) {
        self.selectedPic(pic);
    }

    this.align = function (align, image) {
        image.align(align);
    };

    this.pickImage = function (imageVM) {
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: true,
            library: {
                type: 'image',
            }
        });

        image_frame.on('close', function () {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
            if (selection.length === 0) return;
            self.pictures(selection.map(function(model) { 
                var obj = model.attributes.sizes;
                obj.text = ko.observable();
                obj.align = ko.observable('left');
                obj.fontColor = ko.observable('#000000');
                obj.link = ko.observable('');
                obj.linkLabel = ko.observable('');
                return obj;
            }));
            self.selectedPic(self.pictures()[0]);
        });

        image_frame.on('open', function () {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manage

        });

        image_frame.open();
    };
}

function pageEditorApp(data) {
    var self = this;

    self.rows = ko.observableArray();

    self.addRow = function (data) {
        var obj;

        switch (data.type) {
            case 'dpb-hero':
                obj = new WidgetHero();
                break;
            case 'dpb-slideshow':
                obj = new SlideShow();
                break;
        }

        obj.populate(data);

        self.rows.push(obj);
    }

    self.getTemplate = function (item) {
        return item.type;
    }

    for (var i = 0; i < data.length; i++) {
        self.addRow(data[i]);
    }

    self.removeRow = function (row) {
        self.rows.remove(row);
    }

    self.pickImage = function (imageVM, isMultiple) {
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: isMultiple,
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

    self.align = function (align) {
        this.align(align);
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
    function fxPb_Editor_Toggle(ignoreCheckbox) {
        var isActive = ignoreCheckbox || $('#depagebuilder_switch').is(":checked");

        if (isActive) {
            $('#postdivrich').hide();
            $('#de-page-builder').show();
        }
        else {
            $('#postdivrich').show();
            $('#de-page-builder').hide();
        }
    }

    /* Toggle On Page Load */
    fxPb_Editor_Toggle($('input[type=hidden][name=use_depagebuilder]').val() == 'true');

    /* If user change page template drop down */
    $("#depagebuilder_switch").change(function (e) {
        fxPb_Editor_Toggle();
    });

});