<?php

/**
 * Get the bootstrap! If using as a plugin, REMOVE THIS!
 */
require_once WPMU_PLUGIN_DIR . '/cmb2-attached-posts/cmb2-attached-posts-field.php';


/**
 * Hibiscus Moon Crystal Academy
 */
add_action( 'cmb2_admin_init', 'hmcasettings_register_theme_options_metabox' );
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function hmcasettings_register_theme_options_metabox() {
	/**
	 * Registers options page menu item and form.
	 */
	$hmca_options = new_cmb2_box( array(
		'id'           => 'hmcasettings_option_metabox',
		'title'        => esc_html__( 'Hibiscus Moon', 'hmcasettings' ),
		'object_types' => array( 'options-page' ),
		'option_key'      => 'hmcasettings', // The option key and admin menu page slug.
		'menu_title'      => esc_html__( 'Hibiscus Moon', 'hmcasettings' ), // Falls back to 'title' (above).
		'position'        => 500, // Menu position. Only applicable if 'parent_slug' is left empty.
	) );
	/*
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */
}





/*add_action( 'admin_menu', 'hmcasettings_move_taxonomy_menu' );
function hmcasettings_move_taxonomy_menu() {
	add_submenu_page( 'hmcasettings', esc_html__( 'Webinars - Cats', 'hmca-webinars' ), esc_html__( 'Webinars - Cats', 'hmca-webinars' ), 'manage_categories', 'edit-tags.php?taxonomy=webinarcategories' );
}*/





/**
 * Hibiscus Moon
 */
add_action( 'cmb2_admin_init', 'hmcasite_register_settings_metabox' );
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function hmcasite_register_settings_metabox() {
	/**
	 * Registers options page menu item and form.
	 */
	$hmca_settings = new_cmb2_box( array(
		'id'           => 'hmcasite_settings_metabox',
		'title'        => esc_html__( 'Hibiscus Moon Settings', 'hmcaoptions' ),
		'object_types' => array( 'options-page' ),
		/*
		 * The following parameters are specific to the options-page box
		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
		 */
		'option_key'      => 'hmcaoptions', // The option key and admin menu page slug.
		// 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
		'menu_title'      => esc_html__( 'Settings', 'hmcaoptions' ), // Falls back to 'title' (above).
		'parent_slug'     => 'hmcasettings', // Make options page a submenu item of the themes menu.
		// 'capability'      => 'manage_options', // Cap required to view options-page.
		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
	) );
	/*
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */

	$hmca_settings->add_field( array(
            'name'        => __( 'Optin Bar Pages' ),
            'id'          => 'hmca_optinbar_pages',
            'type'    => 'custom_attached_posts',
            'options' => array(
			'show_thumbnails' => true, // Show thumbnails on the left
			'filter_boxes'    => true, // Show a text box for filtering the results
			'query_args'      => array(
				'posts_per_page' => 500,
				'post_type'      => 'page',
				'order' => 'ASC',
            	'orderby' => 'title',
			), // override the get_posts args
		),
	) );

}



/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function hmcasettings_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( 'hmcaoptions', $key, $default );
	}
	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'hmcaoptions', $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}









?>