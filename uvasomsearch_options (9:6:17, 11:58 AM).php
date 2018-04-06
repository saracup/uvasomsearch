<?php

/**
 *
 * This file registers all of this plugin's 
 * specific Theme Settings, accessible from
 * Genesis > Site Contact Info.
 *
 * @package      WPS_Starter_Genesis_Child
 * @author       Travis Smith <travis@wpsmith.net>
 * @copyright    Copyright (c) 2012, Travis Smith
 * @license      <a href="http://opensource.org/licenses/gpl-2.0.php" onclick="javascript:_gaq.push(['_trackEvent','outbound-article','http://opensource.org']);" rel="nofollow">http://opensource.org/licenses/gpl-2.0.php</a> GNU Public License
 * @since        1.0
 * @alter        1.1.2012
 *
 */
 
 
/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Child Theme Settings page.
 *
 * @package      WPS_Starter_Genesis_Child
 * @subpackage   Admin
 *
 * @since 1.0.0
 */
class UVASOMSEARCH_Settings extends Genesis_Admin_Boxes {
	/**
	 * Create an admin menu item and settings page.
	 * 
	 * @since 1.0.0
	 */
	function __construct() {
		
		// Specify a unique page ID. 
		$page_id = 'uvasomsearch';
		
		// Set it as a child to genesis, and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => 'Search Settings ',
				'menu_title'  => 'Search Settings',
				'capability' => 'manage_options',
			)
		);
		
		// Set up page options. These are optional, so only uncomment if you want to change the defaults
		$page_ops = array(
		//	'screen_icon'       => array( 'custom' => WPS_ADMIN_IMAGES . '/staff_32x32.png' ),
			'screen_icon'       => 'options-general',
		//	'save_button_text'  => 'Save Settings',
		//	'reset_button_text' => 'Reset Settings',
		//	'save_notice_text'  => 'Settings saved.',
		//	'reset_notice_text' => 'Settings reset.',
		);		
		
		// Give it a unique settings field. 
		// You'll access them from genesis_get_option( 'option_name', CHILD_SETTINGS_FIELD );
		$settings_field = 'UVASOMSEARCH_SETTINGS_FIELD';
		
		// Set the default values
		$default_settings = array(
			'uvasomsearch_option' => 'google'
		);
		
		// Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		// Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );
			
	}

	/** 
	 * Set up Sanitization Filters
	 *
	 * See /lib/classes/sanitization.php for all available filters.
	 *
	 * @since 1.0.0
	 */	
	function sanitization_filters() {
		genesis_add_option_filter( 'no_html', $this->settings_field, array(
			'uvasomsearch_option'
		) );
	}
	
	/**
	 * Register metaboxes on Child Theme Settings page
	 *
	 * @since 1.0.0
	 *
	 * @see Child_Theme_Settings::contact_information() Callback for contact information
	 */
	function metaboxes() {
		
		add_meta_box('uvasomsearch-settings', 'UVA SOM Site Search Options', array( $this, 'uvasomsearch_meta_box' ), $this->pagehook, 'main', 'high');
		
	}
	
	/**
	 * Register contextual help on Child Theme Settings page
	 *
	 * @since 1.0.0
	 *
	 */
	function help( ) {	
		global $my_admin_page;
		$screen = get_current_screen();
		
		if ( $screen->id != $this->pagehook )
			return;
		
		$tab1_help = 
			'<h3>' . __( 'Site Search Options' , '' ) . '</h3>' .
			'<p>' . __( 'Select the type of search this site will have. If this is a private site protected by NetBadge then select WordPress search. Public sites should use Google search (the default).' , '' ) . '</p>';
		
		
		$screen->add_help_tab( 
			array(
				'id'	=> $this->pagehook . '-searchoptions',
				'title'	=> __( 'Search Options' , '' ),
				'content'	=> $tab1_help,
			) );
		
		
		
		
	}
	
	/**
	 * Callback for Contact Information metabox
	 *
	 * @since 1.0.0
	 *
	 * @see Child_Theme_Settings::metaboxes()
	 */
	function uvasomsearch_meta_box() {
		
/*set default ranges for quantities of articles in */
	$uvasomsearch_options = array(
				'google' => 'Google Search (site is on the public web)',
				'wordpress' => 'WordPress Search (site is private -- protected by UVA authentication)'
				);
//Display the form
?>

	<p><strong>Site Search Option:</strong><br />
		<select name="<?php echo 'UVASOMSEARCH_SETTINGS_FIELD' ?>[uvasomsearch_option]">
			<?php foreach ( $uvasomsearch_options as $type => $label ) : ?>
			<option value="<?php echo $type; ?>" <?php selected($type, $this->get_field_value( 'uvasomsearch_option')); ?>><?php _e($label, 'genesis'); ?></option>	
			<?php endforeach; ?>
		</select>
	</p>
    
<?php
	}
}
add_action( 'genesis_admin_menu', 'uvasomsearch_settings_menu' );
/**
 * Instantiate the class to create the menu.
 *
 * @since 1.8.0
 */
function uvasomsearch_settings_menu() {

	new UVASOMSEARCH_Settings;

}
