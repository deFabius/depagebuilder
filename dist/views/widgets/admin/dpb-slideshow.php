<script type="text/html" id="dpb-slideshow">
    <header class="page-builder-header">
        <div class="page-builder-interface">
            <button class="button fa fa-picture-o" data-bind="click: $parent.pickImage"></button>
            <button class="button fa fa-align-left" data-bind="click: $parent.align.bind($data, 'left')"></button>
            <button class="button fa fa-align-right" data-bind="click: $parent.align.bind($data, 'right')"></button>
            <button class="button fa fa-font" data-bind="colorPicker: fontColor"></button>
        </div>
        <button type="button" class="button fa fa-trash" data-bind="click: $parent.removeRow"></button>
    </header>
    <div class="hero-content" data-bind="style: { 'background-image': 'url(' + row.bg() + ')' }">
        <div class="hero-text-container">
        <h1>slideshow</h1>
            <div class="hero-text-content" contenteditable="true" data-bind="contentEditable: text, css: align, style: { color: fontColor() }">Text here</div>
        </div>
    </div>
    <input type="hidden" data-bind="value: text, attr: { 'name': '_depb[' + $index() + '][text]' }" />
    <input type="hidden" data-bind="value: bg, attr: { 'name': '_depb[' + $index() + '][bg]' }" />
    <input type="hidden" data-bind="value: align, attr: { 'name': '_depb[' + $index() + '][align]' }" />
    <input type="hidden" data-bind="value: fontColor, attr: { 'name': '_depb[' + $index() + '][fontColor]' }" />
</script>