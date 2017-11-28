<?php
/**
 * Add page editor toggle button to admin page, unless it's home page in which case it enables the page editor by default
 */
function depb_before_editor()
{
    if (get_page_by_title("Home Page")->ID == get_the_ID()) {
        ?>
        <input type="hidden" name="use_depagebuilder" value="true" />
        <?php
    } else {
        $depSwitch = get_post_meta(get_the_ID(), 'use_depagebuilder', true);
        ?>
        <div class="row depb_interface">
            <input type="hidden" name="use_depagebuilder" value="false" />
            <input class="toggle_button" type="checkbox" name="use_depagebuilder" id="depagebuilder_switch" value="true" <?php if ($depSwitch == "true") { echo "checked=\"checked\""; } ?>/>
            <label for="depagebuilder_switch"><span class="switch"><span class="handle"></span></span>Use builder</label>
        </div>
    <?php
    }
}

/**
 * Creates page editor interface
 */
function depb_editor_callback($post)
{
    if ('page' !== $post->post_type) {
        return;
    }
?>
<?php echo file_get_contents(MY_PLUGIN_PATH . '/views/widgets/admin/dpb-hero.php'); ?>
<?php echo file_get_contents(MY_PLUGIN_PATH . '/views/widgets/admin/dpb-slideshow.php'); ?>
<div id="de-page-builder" data>
    <?php wp_nonce_field( "depb_nonce_action", "depb_nonce" ) ?>
    <h1>Page Builder Placeholder.</h1>
    <div data-bind="template: {name: getTemplate, foreach: rows, as: 'row'}">
    </div>
    <input type="button" class="button button-primary button-large" value="Add Hero" data-bind="click: addRow.bind(this, {type: 'dpb-hero'})" />
    <input type="button" class="button button-primary button-large" value="Add Slideshow" data-bind="click: addRow.bind(this, {type: 'dpb-slideshow'})" />
</div><!-- #fx-page-builder -->

<script>
jQuery(document).ready(function () {
    var data = <?php echo json_encode(get_post_meta(get_the_ID(), '_depb', true)) ?>;
    ko.applyBindings(pageEditorApp(data), document.getElementById('de-page-builder'));
});
</script>
<?php
}


?>