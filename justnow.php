<?php
/*
Plugin Name: Just Now - User Friendly Date Time
Plugin URI: orangemoon.design/JustNow
Description: Instantly convert your blog time/date to Time Ago Format such as 1 week ago or 17 minutes ago.
Version: 1.0.1
Tags: time ago,just now,weeks ago,months ago,minutes ago,days ago,time
Requires at least: 3.7
Tested up to: 5.8
Requires PHP: 5.6
Author: Orange Moon Design
Author URI: https://orangemoon.design/
License: GPL-2.0+
Stable tag: 1.0.1
*/

//Prevent file access directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Define info for menu and settings
class justNowPlugin {
	function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
	}

    function register() {
	add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

	add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

	add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
	}

	public function settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=justnow_settings">Settings</a>';
	array_push( $links, $settings_link );
	return $links;
	}

	public function add_admin_pages() {
	add_menu_page( 'Just Now', 'JustNow', 'manage_options', 'justnow_settings', array( $this, 'admin_index' ), 'dashicons-clock', 110 );
	}

	public function admin_index() {
	require_once plugin_dir_path( __FILE__ ) . 'template/admin.php';
	}

	function enqueue() {
		// enqueue all our scripts
		//wp_enqueue_style( 'mypluginstyle', plugins_url( '/assets/mystyle.css', __FILE__ ) );
		//wp_enqueue_script( 'mypluginscript', plugins_url( '/assets/myscript.js', __FILE__ ) );
	}
}

// Run it
if ( class_exists( 'justNowPlugin' ) ) {
	$justNowPlugin = new justNowPlugin();
	$justNowPlugin->register();
}

//Define Basename
if ( ! defined( 'justNow_BASENAME' ) ){
	define( 'justNow_BASENAME', plugins_url( '', __FILE__ ) );
}

//Check PHP and WP version will work with plugin
if ( is_admin() ) {
	if ( version_compare( PHP_VERSION, '5.0', '<' ) && version_compare( WP_VERSION, '3.7', '<' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
		wp_die( __( 'Just Now plugin requires WordPress 3.8 and PHP 5 or greater. The plugin will now disable itself', 'JustNow' ) );
	}
}

//Create the settings to be used on admin.php
function justNow_settings_page_options()
{
    add_settings_section("justNow_options", "", null, "justNow_options");
    add_settings_field("posts-checkbox", "Posts Date/time", "justNow_posts_checkbox_display", "justNow_options", "justNow_options");
    add_settings_field("comment-checkbox", "Comment Date/Time", "justNow_comment_checkbox_display", "justNow_options", "justNow_options");
    register_setting("justNow_options", "posts-checkbox");
    register_setting("justNow_options", "comment-checkbox");
}


function justNow_posts_checkbox_display()
{
   ?>
        <input type="checkbox" name="posts-checkbox" value="1" <?php checked(1, get_option('posts-checkbox'), true); ?> />
   <?php
}

function justNow_comment_checkbox_display()
{
   ?>
	<input type="checkbox" name="comment-checkbox" value="1" <?php checked(1, get_option('comment-checkbox'), true); ?> />
   <?php
}

//Trigger settings page
add_action("admin_init", "justNow_settings_page_options");

//JustNow time for Posts
if (get_option('posts-checkbox')){

function justNow_convert_post_time() {
return sprintf( esc_html__( '%s ago', 'textdomain' ), human_time_diff(get_the_time ( 'U' ), current_time( 'timestamp' ) ) );
}
add_filter( 'the_time', 'justNow_convert_post_time' );
add_filter( 'the_date', 'justNow_convert_post_time' );
}

//JustNow time for Comments
if (get_option('comment-checkbox')){

function justNow_convert_comment_time() {
return sprintf( esc_html__( '%s ago', 'textdomain' ), human_time_diff(get_comment_time ( 'U' ), current_time( 'timestamp' ) ) );
}
add_filter( 'get_comment_date', 'justNow_convert_comment_time' );

}
