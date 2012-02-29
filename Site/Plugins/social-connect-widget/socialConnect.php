<?php
/*
 * Plugin Name: Social Connect Widget
 * Version: 1.3
 * Plugin URI: http://scryb.es/
 * Description: A widget designed to easily add icons with links to your social pages on all the major networks.
 * Author: Scrybes WordPress Hosting
 * Author URI: http://scryb.es/
 */
?>
<?php
//Add necessary includes
require_once(dirname(__FILE__) . '/inc/output.php');
require_once(dirname(__FILE__) . '/inc/functions.php');
require_once(dirname(__FILE__) . '/inc/settings.php');
require_once(dirname(__FILE__) . '/inc/widget.php');

// Runs when plugin is activated
register_activation_hook(__FILE__,'socialConnect_install'); 

// Runs on plugin deactivation
register_deactivation_hook( __FILE__, 'socialConnect_remove' );

//Determine if the plugin has been being updated from version 1.2 and remove depreciated table
global $wpdb;
if($wpdb->get_var("SHOW TABLES LIKE 'SCW_Stats';") == 'SCW_Stats') {
	$wpdb->query("DROP TABLE SCW_Stats");
}

//Display admin messages
add_action('admin_notices', 'socialConnect_adminNotice');
add_action('admin_init', 'socialConnect_adminNotice_ignore');

// Add the widget	
add_action('widgets_init', 'socialConnect_registerWidget');

// Add the header code (css) to WordPress page
add_action('wp_head', 'addHeaderCode');

//Register the shortcode
add_shortcode("dnh32pw75j", "socialConnect_shortcodeHandler");
?>