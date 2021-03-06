<?php
function depb_admin_scripts($hook_suffix)
{
    global $post_type;

    if ("page" == $post_type && in_array( $hook_suffix, array( "post.php", "post-new.php" ) )) {
        wp_enqueue_script("knockout-3.4.2.js", "//cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js");
        wp_enqueue_script("page-editor-app.js", MY_PLUGIN_PATH . "/js/page-editor-app.js", array( "jquery", "knockout-3.4.2.js"));
        wp_enqueue_script("ko-custom-bindings.js", MY_PLUGIN_PATH . "/js/ko-custom-bindings.js", array( "jquery", "knockout-3.4.2.js", "page-editor-app.js"));
        wp_enqueue_script("jcolor.js", "https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.0.4/jscolor.js", array("jquery", "knockout-3.4.2.js"));
        wp_enqueue_script("font-awesome", "//use.fontawesome.com/dedf3b6161.js");
        wp_enqueue_media();

        wp_enqueue_style( "page-editor-admin", MY_PLUGIN_PATH . "/css/admin-interface.css");
    }
}

?>