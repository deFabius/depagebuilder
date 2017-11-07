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
require_once(plugin_dir_path( __FILE__ ) . '/bin/editor-setup.php');
require_once(plugin_dir_path( __FILE__ ) . '/bin/data-helper.php');

/**
 * Initialize page editor hooks
 */
add_action( 'admin_enqueue_scripts', 'depb_admin_scripts' );
add_action( 'edit_form_after_editor', 'depb_editor_callback' );
add_action( 'edit_form_after_title', 'depb_before_editor' );

/**
 * Edit data hooks
 */
add_action( 'save_post', 'depb_pbbase_save_post', 10, 2 );
