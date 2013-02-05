<?php

class ShoppWishlist_Widget extends WP_Widget
{
	function ShoppWishlist_Widget()
	{
		// widget actual processes
		$widget_ops = array('description' => __('Shopp wishlist widget','shoppwishlist'));
		$this->WP_Widget('shoppwishlist_widget', __('Shopp Wishlist','shoppwishlist'), $widget_ops);
	}
	
	function form($instance)
	{
		$title = esc_attr($instance['title']);
?> 
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title','shoppwishlist'); ?>:
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
<?php
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
 
	function widget($args, $instance)
	{
		global $Shopp, $user_ID;
		extract($args);
		
		if ( shopp('customer','loggedin') )
		{		
			$title	= ( $instance['title'] != '' ) ? $before_title . apply_filters('widget_title', esc_attr($instance['title'])) . $after_title : __('My wishlist','shoppwishlist');
			
			// output title & $before_widget
			echo $before_widget . $before_title . $title . $after_title;
			
			if ( file_exists(get_query_template('shopp/sidewishlist')) == true )
			{
				$template = get_query_template('shopp/sidewishlist');
			}
			elseif ( file_exists(get_query_template('sidewishlist')) == true )
			{
				$template = get_query_template('sidewishlist');
			}
			else
			{
				$template = SHOPP_WISHLIST_DIR.'/sidewishlist.php';
			}
			include($template);
			
			// output $after_widget
			echo $after_widget;
		} // end if
	}
} // end class ShoppWishlist_Widget
 
?>