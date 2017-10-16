<?php
/*
Plugin Name: De Page Builder
Plugin URI: 
Description: Some text
Version: 0.1
Author: Fabio Valle
Author URI: 
License: GPL3
*/
define( 'MY_PLUGIN_PATH', plugins_url() . '/depagebuilder' );

require_once(plugin_dir_path( __FILE__ ) . '/views/loader/res-loader.php');

add_action( 'admin_enqueue_scripts', 'dpb_admin_scripts' );

function fx_pbbase_editor_callback($post)
{
    if ('page' !== $post->post_type) {
        return;
    }
?>
<div id="de-page-builder">
    <?php wp_nonce_field( "depb_nonce_action", "depb_nonce" ) ?>
    <h1>Page Builder Placeholder.</h1>
    <div data-bind="foreach: {data: rows, as: 'row'}">
        <div class="row-container">
            <header class="page-builder-header">
                <button type="button" class="button fa fa-trash" data-bind="click: $parent.removeRow"></button>
            </header>
            <div class="hero-content" data-bind="style: { 'background-image': $parent.getBgImageCss(row) }">
                <div class="hero-text-container">
                    <div class="hero-text-content" contenteditable="true" data-bind="text: text">Text here</div>
                </div>
            </div>
            <input type='hidden' data-bind="value: text, attr: { 'name': '_depb[' + $index() + '][text]' }" />
            <input type='hidden' data-bind="value: bg, attr: { 'name': '_depb[' + $index() + '][bg]' }" />
            <input type='button' class="button-primary" value="<?php esc_attr_e( 'Select a image', 'mytextdomain' ); ?>" data-bind="click: $parent.pickImage" />
        </div>
    </div>
    <input type="button" class="button button-primary button-large" value="Add Row" data-bind="click: addRow" />
</div><!-- #fx-page-builder -->

<script>
jQuery(document).ready(function () {
    var data = <?php echo json_encode(get_post_meta(get_the_ID(), '_depb', true)) ?>;
    // ko.applyBindings(pageEditorApp(data), document.getElementById('de-page-builder'));
});
</script>
<?php
}

add_action( 'edit_form_after_editor', 'fx_pbbase_editor_callback', 10, 2 );

function dpb_before_editor()
{
    ?>
    <div class="row depb_interface">
        <input class="toggle_button" type="checkbox" name="use_depagebuilder" id="depagebuilder_switch" />
        <label for="depagebuilder_switch"><span class="switch"><span class="handle"></span></span>Use builder</label>
    </div>
    <?php
}


add_action( 'edit_form_after_title', 'dpb_before_editor', 10, 2 );

