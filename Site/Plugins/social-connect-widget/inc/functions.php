<?php
//Load css sheet for the main WordPress pages
function addHeaderCode() {
	echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/social-connect-widget/css/socialConnect-style.css" />' . "\n";
}

//Function to do the work of the plugin and return shortcode text
function socialConnect_shortcodeHandler() {
  $socialConnect_output = socialConnect_outputFunction();
  return $socialConnect_output;
}

// Runs on plugin install
function socialConnect_install() {
	//Populate new option fields with default values
	global $wpdb;
        $sc_twitter = 'scrybes';
        $sc_facebook= 'scrybes';
        $sc_googleplus= 'gplus.to/scryb.es';
        $sc_youtube= '';
        $sc_tumblr= '';
        $sc_linkedin= 'www.linkedin.com/company/scrybes';
        $sc_rss= 'feeds.feedburner.com/scrybes';

	// Creates new database fields
	add_option("sc_twitter", $sc_twitter, '', 'yes');
	add_option("sc_facebook", $sc_facebook, '', 'yes');
	add_option("sc_googleplus", $sc_googleplus, '', 'yes');
	add_option("sc_youtube", $sc_youtube, '', 'yes');
	add_option("sc_tumblr", $sc_tumblr, '', 'yes');
	add_option("sc_linkedin", $sc_linkedin, '', 'yes');
	add_option("sc_rss", $sc_rss, '', 'yes');
}

// Display admin notice
function socialConnect_adminNotice() {
	global $current_user ;
	$user_id = $current_user->ID;
	// Check that the user hasn't already clicked to ignore the message
	if ( ! get_user_meta($user_id, 'example_ignore_notice') ) {
		echo '<div class="updated"><p>';
		printf(__('Important! Please configure the new version of Social Connect on the <a href="/options-general.php?page=social-connect-settings">settings</a> page. | <a href="%1$s">Hide Notice</a>'), '?example_nag_ignore=0');
		echo "</p></div>";
	}
}

// Ignore the admin notice
function socialConnect_adminNotice_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	// If user clicks to ignore the notice, add that to their user meta
	if ( isset($_GET['example_nag_ignore']) && '0' == $_GET['example_nag_ignore'] ) {
		add_user_meta($user_id, 'example_ignore_notice', 'true', true);
	}
}

//Runs on deactivation and deletes the database fields
function socialConnect_remove() {
	delete_option('sc_twitter');
	delete_option('sc_facebook');
	delete_option('sc_googleplus');
	delete_option('sc_youtube');
	delete_option('sc_tumblr');
	delete_option('sc_linkedin');
	delete_option('sc_rss');
}

//Register the widget
function socialConnect_registerWidget() {
	register_widget('socialConnect_widget');
}

// Function to register and then enqueue CSS and fonts on the admin settings page
function socialConnect_loadCSS() {
	wp_register_style('socialConnect_mainCSS', plugins_url().'/social-connect-widget/css/socialConnect-style.css', array(), '1.0.0' , 'all');
	wp_register_style('socialConnect_settingsCSS', plugins_url().'/social-connect-widget/css/socialConnect-settings.css', array(), '1.0.0' , 'all');
	wp_enqueue_style('socialConnect_mainCSS');
	wp_enqueue_style('socialConnect_settingsCSS');
?>
<link href='http://fonts.googleapis.com/css?family=Goudy+Bookletter+1911' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css' />
<?php
}
?>