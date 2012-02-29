<?php

/*
 *
 *	Plugin Name: Did you know?
 *	Plugin URI: http://www.svetnauke.org/did-you-know/
 *	Description: Adds a sidebar widget that display interesting quotes from posts with link to the post.
 *	Version: 0.3.0
 *	Author: Milan Milosevic
 *	Author URI: http://www.svetnauke.org/
 *
 *	Copyright (c) 2009 Milan Milosevic. All Rights Reserved.
 *
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation; either version 2 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 *	If you are unable to comply with the terms of this license,
 *	contact the copyright holder for a commercial license.
 *
 *	We kindly ask that you keep links to Joe's Web Tools so
 *	other people can find out about this plugin.
 *
 */

/*
 *	mm-did-you-know Add Edit post Options
 */

// Add custom CSS style for Widget
function mm_dyk_css() {

	echo '<style type="text/css">';
	echo '.mmdyk_txt {';
// To change a color of text uncomment next line and set a color 
	echo '	color: #FFF;'; //echo '	color: #5F5F5F;';
	echo '	padding-bottom: 1.2em; }';
	echo '</style>';
}

add_action('wp_head', 'mm_dyk_css');


/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'mmdyk_add_custom_box');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'mmdyk_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function mmdyk_add_custom_box() {

  if( function_exists( 'add_meta_box' )) {
    add_meta_box( 'mmdyk_sectionid', __( 'Did You Know?', 'mmdyk_textdomain' ), 
                'mmdyk_inner_custom_box', 'post', 'advanced', 'high' );
    add_meta_box( 'mmdyk_sectionid', __( 'Did You Know?', 'mmdyk_textdomain' ), 
                'mmdyk_inner_custom_box', 'page', 'advanced', 'high' );
   }
}
   
/* Prints the inner fields for the custom post/page section */
function mmdyk_inner_custom_box() {

  global $wpdb;
  global $post;
  $post_id = $post->ID;;

  // Create the table name
  $table_name = $wpdb->prefix . 'mmdyk_quote';

  // Use nonce for verification

  echo '<input type="hidden" name="mmdyk_noncename" id="mmdyk_noncename" value="' . 
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

  // The actual fields for data entry

  echo 'Only one quote per line (no HTML code).';
  echo	'<textarea name="mmdyk_txtarea" rows="5" cols="80" wrap="off" style="overflow: auto;">';
		if ($post_id != 0) {
			$mmdyk_rec = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id=$post_id");
			foreach($mmdyk_rec as $mmdyk_act) {
				echo $mmdyk_act->quotes . "\r\n";
			}
		}
  echo	'</textarea><br />';
}

/* When the post is saved, saves our custom data */
function mmdyk_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['mmdyk_noncename'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
  // to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    return $post_id;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data

  global $wpdb;
  $mydata = $_POST['mmdyk_txtarea'];
  $table_name = $wpdb->prefix . 'mmdyk_quote';
  $wpdb->query("DELETE FROM $table_name WHERE post_id = $post_id");

  // Do something with $mydata 
  
  if($mmdyk_list = strip_tags(stripslashes($mydata))) {

	$mmdyk_array = explode("\n", $mmdyk_list);
	sort($mmdyk_array);

	foreach($mmdyk_array as $mmdyk_current) {
		$mmdyk_current = trim($mmdyk_current);
		if(!empty($mmdyk_current)) {
			$wpdb->insert( $table_name, array( 'quotes' => $mmdyk_current, 'post_id' => $post_id ));
		}
	}
  }

  // Delete quotes for post revisions
  $table_posts = $wpdb->prefix . 'posts';
  $mmdyk_rec = $wpdb->get_results("SELECT ID FROM $table_posts WHERE post_parent=$post_id AND post_type='revision'");
  foreach($mmdyk_rec as $mmdyk_current) {
	$wpdb->query("DELETE FROM $table_name WHERE post_id=$mmdyk_current->ID");
  }
  
  return $mydata;
}


/*
 *	mm-did-you-know WP Widget
 */

class WP_Widget_mmdyk extends WP_Widget {

	function WP_Widget_mmdyk() {

		parent::WP_Widget(false, $name = 'Did You Know');
	}

	function widget($args, $instance) {

		global $wpdb;

		// Create the table name
		$table_name = $wpdb->prefix . 'mmdyk_quote';

		// Get a fun fact
		$mmdyk_no = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
		$mmdyk_num = $instance['mmdyk_num'];

		if ((!isset($mmdyk_num)) or ($mmdyk_num < 1)) $mmdyk_num = 1;
		for ($i = 1; $i <= $mmdyk_num; $i++) {	
			$mmdyk_rnd = rand(1, $mmdyk_no) - 1;
			$mmdyk_rec = $wpdb->get_results("SELECT * FROM $table_name LIMIT $mmdyk_rnd, 1");
			foreach($mmdyk_rec as $mmdyk_act) {
				$mmdyk_text[$i] = $mmdyk_act->quotes;
				$mmdyk_post[$i] = $mmdyk_act->post_id;
				$mmdyk_link[$i] = $mmdyk_act->link;
			}
		}

		extract($args);

		$option_title = apply_filters('widget_title', empty($instance['title']) ? 'Did You Know?' : $instance['title']);

		// Create the widget
		echo $before_widget;
		echo $before_title . $option_title . $after_title;

		if ($instance['mmdyk_blank'] == "on")
			$mmdyk_blank = 'target="_blank"';
		else $mmdyk_blank = '';

		for ($i = 1; $i <= $mmdyk_num; $i++) {	
			if ($mmdyk_post[$i] == 0) $mmdyk_more = $mmdyk_link[$i];
				else $mmdyk_more = get_permalink($mmdyk_post[$i]);
		
			echo '<p class="mmdyk_txt">' . $mmdyk_text[$i];
			if (strlen($mmdyk_more) > 0) echo '<a '.$mmdyk_blank.'href="' . $mmdyk_more . '"> Source...</a>';
			echo '</p>';
		}	

		if ($instance['mmdyk_credits'] != "on")
			echo '<p style="text-align: right;"><font face="arial" size="-4">Plugin by <a href="http://www.svetnauke.org/" title="Did You Know? - plugin for Wordpress">mmilan</a></font></p>';

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {

		return $new_instance;
	}

	function form($instance) {

		$instance = wp_parse_args((array)$instance, array('title' => 'Did You Know?'));
		$option_title = strip_tags($instance['title']);
 		$option_num = strip_tags($instance['mmdyk_num']);

		echo '<p>';
		echo 	'<label for="' . $this->get_field_id('title') . '">Title:</label>';
		echo 	'<input class="widefat" type="text" value="' . $option_title . '" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" /><br />';
		echo 	'<label for="' . $this->get_field_id('mmdyk_num') . '">Number of facts do display:</label>';
		echo 	'<input class="widefat" type="text" value="' . $option_num . '" id="' . $this->get_field_id('mmdyk_num') . '" name="' . $this->get_field_name('mmdyk_num') . '" />';
		echo '</p>';
?>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool)  $instance['mmdyk_blank'], true ); ?> id="<?php echo $this->get_field_id( 'mmdyk_blank' ); ?>" name="<?php echo $this->get_field_name( 'mmdyk_blank' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'mmdyk_blank' ); ?>">Open link in new window</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool)  $instance['mmdyk_credits'], true ); ?> id="<?php echo $this->get_field_id( 'mmdyk_credits' ); ?>" name="<?php echo $this->get_field_name( 'mmdyk_credits' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'mmdyk_credits' ); ?>">Don't show credits</label>
		</p>
<?php	}
}

add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_mmdyk");'));

/*
 *	mm-did-you-know Add Menu
 */

function mmdyk_add_menu() {

	// Add the menu page
	add_submenu_page('options-general.php', 'Did You Know?', 'Did You Know?', 10, __FILE__, 'mmdyk_page');
}

add_action('admin_menu', 'mmdyk_add_menu');


/*
 *
 *	mm-did-you-know Option Page
 *
 */

function mmdyk_page() {
	
	// Page wrapper start
	echo '<div class="wrap">';

	// Title
	screen_icon();
	echo '<h2>Did You Know?</h2>';

	global $wpdb;
	$table_name = $wpdb->prefix . 'mmdyk_quote';
	$hidden_field_name = 'mm_dyk_submit';
	
	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		$mydata = $_POST['mmdyk_txtarea'];
		$wpdb->query("DELETE FROM $table_name WHERE post_id = 0");
  
	// Do something with $mydata 
  
		if($mmdyk_list = strip_tags(stripslashes($mydata))) {
			$mmdyk_array = explode("\n", $mmdyk_list);
			sort($mmdyk_array);

			foreach($mmdyk_array as $mmdyk_current) {
				$mmdyk_current = trim($mmdyk_current);
				if(!empty($mmdyk_current)) {
					$mmdyk_str = explode(" http://", $mmdyk_current);
					if (strlen($mmdyk_str[1]) > 0) $mmdyk_str[1] = "http://".$mmdyk_str[1];
					$wpdb->insert( $table_name, array( 'quotes' => $mmdyk_str[0], 'post_id' => $post_id, 'link' => $mmdyk_str[1] ));
				}
			}
		}
	}
	
	// Options
?>
	<p>Only one quote per line (no HTML code). To add a link put it at the end of line and start it with http://</p>
	<form name="mmdyk_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

		<textarea name="mmdyk_txtarea" rows="30" cols="120" wrap="off" style="overflow: auto;">
<?php 			$mmdyk_rec = $wpdb->get_results("SELECT * FROM $table_name  WHERE post_id = 0");
			foreach($mmdyk_rec as $mmdyk_act) {
				echo $mmdyk_act->quotes . " " . $mmdyk_act->link . "\r\n";
			}
?>
		</textarea>
	
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
<?php }


/*
 *	mm-did-you-know Activate plugin
 */

function mmdyk_activate() {

	global $wpdb;

	// Create the table name
	$table_name = $wpdb->prefix . 'mmdyk_quote';

	// Create the table if it doesn't already exist
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$results = $wpdb->query("CREATE TABLE IF NOT EXISTS $table_name(id INT(11) NOT NULL AUTO_INCREMENT, quotes VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL, post_id INT(11) NOT NULL, link VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARSET=utf8;");
		$wpdb->insert( $table_name, array( 'quotes' => 'Did You Know? Wordpress plugin by Milan Milosevic.', 'post_id' => 0, 'link' => 'http://www.mmilan.com/'));
		$wpdb->insert( $table_name, array( 'quotes' => 'A rat can last longer without water than a camel.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'A female ferret will die if it goes into heat and cannot find a mate.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'A duck`s quack doesn`t echo. No one knows why.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'Donald Duck comics were banned from Finland because he doesn`t wear pants.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'Because metal was scarce, the Oscars given out during World War II were made of wood.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'The number of possible ways of playing the first four moves per side in a game of chess is 318,979,564,000.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'There are no words in the dictionary that rhyme with month, orange, purple, and silver.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'The first CD pressed in the US was Bruce Springsteen`s "Born in the USA."', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'Charlie Chaplin once won third prize in a Charlie Chaplin look-alike contest.', 'post_id' => 0, 'link' => ''));
		$wpdb->insert( $table_name, array( 'quotes' => 'The Guinness Book of Records holds the record for being the book most often stolen from public libraries.', 'post_id' => 0, 'link' => ''));
	} else {
		$results = $wpdb->query("alter table $table_name' default collate utf8_unicode_ci;");
		$results = $wpdb->query("ALTER TABLE $table_name CHANGE quotes quotes VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
		$results = $wpdb->query("ALTER TABLE $table_name CHANGE link link VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
	}
	
}

register_activation_hook(__FILE__, 'mmdyk_activate');

?>