<script type="text/html" id="dpb-slideshow">
    <header class="page-builder-header">
        <div class="page-builder-interface">
            <button class="button fa fa-picture-o" data-bind="click: pickImage"></button>
        </div>
        <button type="button" class="button fa fa-trash" data-bind="click: $parent.removeRow"></button>
    </header>
    <div class="slideshow-content">
        <div class="tabs" data-bind="foreach: pictures">
            <img data-bind="attr: { src: $data.thumbnail.url }, click: $parent.showDetails" />
        </div>
        <!-- ko with: selectedPic -->
        <div class="hero-content" data-bind="style: { 'background-image': 'url(' + large.url + ')' }">
            <div class="hero-text-container">
                <div class="hero-text-content" data-bind="css: align, style: { color: fontColor() }">
                    <div data-bind="html: text">Text here</div>
                    <!-- ko if: link -->
                    <a class="btn" data-bind="text: linkLabel, attr: { href: link }, style: { 'border-color': fontColor() }"></a>
                    <!-- /ko -->
                </div>
            </div>
        </div>
        <button class="button fa fa-align-left" data-bind="click: $parent.align.bind($data, 'left')"></button>
        <button class="button fa fa-align-center" data-bind="click: $parent.align.bind($data, 'center')"></button>
        <button class="button fa fa-align-right" data-bind="click: $parent.align.bind($data, 'right')"></button>
        <button class="button fa fa-font" data-bind="colorPicker: fontColor"></button>
        <label class="slideshow-interface" for="slide-text">Slide text</label>
        <textarea class="slideshow-interface" id="slide-text" name="text" data-bind="value: text" />
        <label class="slideshow-interface" for="link-label">Link label</label>
        <input class="slideshow-interface" id="link-label" type="text" name="linkLabel" data-bind="value: linkLabel">
        <label class="slideshow-interface" for="link-url">Link url</label>
        <input class="slideshow-interface" id="link-url" type="text" name="link" data-bind="value: link">
        <!-- /ko -->
        <input type="hidden" data-bind="attr: { name: '_depb[' + $index() + '][pictures]' }, value: ko.toJSON($data.pictures)" />
        <input type="hidden" data-bind="attr: { name: '_depb[' + $index() + '][type]' }, value: 'dpb-slideshow'" />
    </div>
</script>