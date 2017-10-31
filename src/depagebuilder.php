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

function depb_editor_callback($post)
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
                <div class="page-builder-interface">
                    <button class="button fa fa-picture-o" data-bind="click: $parent.pickImage"></button>
                    <button class="button fa fa-align-left" data-bind="click: $parent.align.bind($data, 'left')"></button>
                    <button class="button fa fa-align-right" data-bind="click: $parent.align.bind($data, 'right')"></button>
                    <button class="button fa fa-font" data-bind="colorPicker: fontColor"></button>
                </div>
                <button type="button" class="button fa fa-trash" data-bind="click: $parent.removeRow"></button>
            </header>
            <div class="hero-content" data-bind="style: { 'background-image': $parent.getBgImageCss(row) }">
                <div class="hero-text-container">
                    <div class="hero-text-content" contenteditable="true" data-bind="contentEditable: text, css: align, style: { color: fontColor() }">Text here</div>
                </div>
            </div>
            <input type="hidden" data-bind="value: text, attr: { 'name': '_depb[' + $index() + '][text]' }" />
            <input type="hidden" data-bind="value: bg, attr: { 'name': '_depb[' + $index() + '][bg]' }" />
            <input type="hidden" data-bind="value: align, attr: { 'name': '_depb[' + $index() + '][align]' }" />
            <input type="hidden" data-bind="value: fontColor, attr: { 'name': '_depb[' + $index() + '][fontColor]' }" />
            
        </div>
    </div>
    <input type="button" class="button button-primary button-large" value="Add Row" data-bind="click: addRow.bind(this, null)" />
</div><!-- #fx-page-builder -->

<script>
jQuery(document).ready(function () {
    var data = <?php echo json_encode(get_post_meta(get_the_ID(), '_depb', true)) ?>;
    ko.applyBindings(pageEditorApp(data), document.getElementById('de-page-builder'));
});
</script>
<?php
}

function depb_before_editor()
{
    $depSwitch = get_post_meta(get_the_ID(), 'use_depagebuilder', true);
    echo $depSwitch;
    ?>
    <div class="row depb_interface">
        <input type="hidden" name="use_depagebuilder" value="false" />
        <input class="toggle_button" type="checkbox" name="use_depagebuilder" id="depagebuilder_switch" value="true" <?php if (get_post_meta(get_the_ID(), 'use_depagebuilder', true) == "true") { echo "checked=\"checked\""; } ?>/>
        <label for="depagebuilder_switch"><span class="switch"><span class="handle"></span></span>Use builder</label>
    </div>
    <?php
}

/**
* Save Page Builder Data When Saving Page
* @since 1.0.0
*/
function depb_pbbase_save_post($post_id, $post)
{

   /* Stripslashes Submitted Data */
    $request = stripslashes_deep( $_POST );

   /* Verify/validate */
    if (! isset( $request['depb_nonce'] ) || ! wp_verify_nonce( $request['depb_nonce'], 'depb_nonce_action' )) {
        return $post_id;
    }
   /* Do not save on autosave */
    if (defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return $post_id;
    }
   /* Check post type and user caps. */
    $post_type = get_post_type_object( $post->post_type );
    if ('page' != $post->post_type || !current_user_can( $post_type->cap->edit_post, $post_id )) {
        return $post_id;
    }

    save_or_update($post_id, "_depb", $request);
    save_or_update($post_id, "use_depagebuilder", $request);

    $post_content = generate_content($request);

    /* Post Data To Save */
    $this_post = array(
        'ID'           => $post_id,
        'post_content' => sanitize_post_field( 'post_content', $post_content, $post_id, 'db' ),
    );
    
    /**
     * Prevent infinite loop.
     * @link https://developer.wordpress.org/reference/functions/wp_update_post/
     */
    remove_action( 'save_post', 'depb_pbbase_save_post' );
    wp_update_post( $this_post );
    add_action( 'save_post', 'depb_pbbase_save_post' );
}

function save_or_update($post_id, $meta_key, $request)
{
    /* Get (old) saved page builder data */
    $saved_data = get_post_meta( $post_id, $meta_key, true );
    /* Get new submitted data and sanitize it. */
    $submitted_data = isset( $request[$meta_key] ) ? $request[$meta_key] : null;
    /* New data submitted, No previous data, create it  */
    if ($submitted_data && '' == $saved_data) {
        add_post_meta( $post_id, $meta_key, $submitted_data, true );
    } /* New data submitted, but it's different data than previously stored data, update it */
    elseif ($submitted_data && ( $submitted_data != $saved_data )) {
        update_post_meta( $post_id, $meta_key, $submitted_data );
    } /* New data submitted is empty, but there's old data available, delete it. */
    elseif (empty( $submitted_data ) && $saved_data) {
        delete_post_meta( $post_id, $meta_key );
    }
}

add_action( 'admin_enqueue_scripts', 'depb_admin_scripts' );
add_action( 'edit_form_after_editor', 'depb_editor_callback' );
add_action( 'edit_form_after_title', 'depb_before_editor' );
add_action( 'save_post', 'depb_pbbase_save_post', 10, 2 );

function generate_content($request) {
    $submitted_data = isset( $request["_depb"] ) ? $request["_depb"] : null;
    $generated = "";

    foreach($submitted_data as $row) {
        ob_start(); ?>
        <div class="hero-content" style="background-image: url(<?=$row["bg"]?>)">
            <div class="hero-text-container">
                <div class="hero-text-content" style="text-align: <?=$row["align"]?>; color: <?=$row["fontColor"]?>"><?=$row["text"]?></div>
            </div>
        </div>
        <?php $generated .= ob_get_clean();
    }

    return $generated;
}