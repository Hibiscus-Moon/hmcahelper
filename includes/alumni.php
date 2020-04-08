<?php



/*
* Initializing the Alumni custom post type
*/
 
function alumni_post_type() {
 
// Set UI labels for Optin Bars post type
    $labels = array(
        'name'                => _x( 'Alumni', 'Post Type General Name' ),
        'singular_name'       => _x( 'Alumni', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Alumni' ),
        'parent_item_colon'   => __( 'Parent Alumni' ),
        'all_items'           => __( 'Alumni' ),
        'view_item'           => __( 'View Alumni' ),
        'add_new_item'        => __( 'Add New Alumni' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Alumni' ),
        'update_item'         => __( 'Update Alumni' ),
        'search_items'        => __( 'Search Alumni' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Optin Bars post type
     
    $args = array(
        'label'               => __( 'Alumni' ),
        'description'         => __( 'HMCA Alumni' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'hmcaalumni', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'alumni_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_alumni_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_alumni_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'hmcaalumni_';

	/**
	 * Initiate the metabox
	 */
	$hmcaalumni = new_cmb2_box( array(
		'id'            => 'hmcaalumni_metabox',
		'title'         => __( 'Alumni Details', 'cmb2' ),
		'object_types'  => array( 'hmcaalumni', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

    $hmcaalumni->add_field( array(
            'name'    => 'Description',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'description',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name'    => 'Business',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'business',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name'    => 'Location',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'location',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name'    => 'Email',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'email',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name'    => 'Phone',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'phone',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name'    => 'Website',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'website',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name'    => 'Facebook',
            'desc'    => 'Enter the username of the Facebook profile address only',
            'default' => '',
            'id'      => $prefix . 'facebook',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name'    => 'Twitter',
            'desc'    => 'Enter the username of the Twitter profile only',
            'default' => '',
            'id'      => $prefix . 'twitter',
            'type'    => 'text',
    ) );

    $hmcaalumni->add_field( array(
            'name' => 'Is this student an Advanced Crystal Master?',
            'desc' => 'Check for yes; leave unchecked to indicate that the student is a Certified Crystal Healer.',
            'id'   => $prefix . 'featured',
            'type' => 'checkbox',
    ) );

}





// Add new Alumni Location taxonomy
add_action( 'init', 'create_alumni_location_taxonomy', 0 );
 
function create_alumni_location_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Alumni Location', 'taxonomy general name' ),
    'singular_name' => _x( 'Alumni Location', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Locations' ),
    'all_items' => __( 'All Locations' ),
    'parent_item' => __( 'Parent Location' ),
    'parent_item_colon' => __( 'Parent Location:' ),
    'edit_item' => __( 'Edit Location' ), 
    'update_item' => __( 'Update Location' ),
    'add_new_item' => __( 'Add New Location' ),
    'new_item_name' => __( 'New Location' ),
    'menu_name' => __( 'Locations' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('alumnilocations',array('hmcaalumni'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'location' ),
  ));
 
}