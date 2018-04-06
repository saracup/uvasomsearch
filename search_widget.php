<?php
class uvasomsearch_widget extends WP_Widget {

	// constructor
	function __construct() {
		parent::__construct(
			'uvasomsearch_widget', // Base ID
			'UVA SOM Search Widget', // Name
			array( 'description' => 'UVA SOM Search Widget' ) // Args
		);
	//function uvasomsearch_widget() {
		//parent::WP_Widget(false, $name = __('UVA SOM Search Widget', 'uvasomsearch_widget') );
	}

	// widget form creation
function form($instance) {

// Check values
if( $instance) {
     $title = esc_attr($instance['title']);
} else {
     $title = 'Search';
}
?>

<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('UVA SOM Search Widget', 'uvasomsearch_widget'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
     return $instance;
}

	// display widget
	function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   //$text = $instance['text'];
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="widget-text uvasomsearch_widget_box">';

	   // Check if title is set
	   if ( $title ) {
		  echo $before_title . $title . $after_title;
	   }
	   //output the search form
	   if ((genesis_get_option('uvasomsearch_option','UVASOMSEARCH_SETTINGS_FIELD') == 'google')|| (genesis_get_option('uvasomsearch_option','UVASOMSEARCH_SETTINGS_FIELD') == '')){
		uvasom_sitesearch_form('local');
		}
		if (genesis_get_option('uvasomsearch_option','UVASOMSEARCH_SETTINGS_FIELD') == 'wordpress'){
		get_search_form();
		}
	   //uvasom_sitesearch_form('local');
	   echo '</div>';
	   echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("uvasomsearch_widget");'));
?>
