<?php
// Code for the widget
class socialConnect_widget extends WP_Widget {
	function socialConnect_widget(){
		$widget_ops = array('classname' => 'widget_hello_world', 'description' => __( "Add icons and links to your social network pages. Configure from the WordPress Settings menu.") );
		$control_ops = array('width' => 200, 'height' => 200);
		$this->WP_Widget('helloworld', __('Social Connect Widget'), $widget_ops, $control_ops);
	}	
	function widget( $args, $instance ) {
	        global $wpdb;
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		// Before widget (defined by themes)
		echo $before_widget;
		// Title of widget (before and after defined by themes)
		if ( $title )
			echo $before_title . $title . $after_title;
					
   	        echo '<div>' . socialConnect_shortcodeHandler() . '</div>';
		echo $after_widget;
	}
	function form($instance){	
		?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			<p>Configure options from the WordPress settings menu.</p>
		<?php
	}
}
?>