<?php



/*
* Initializing the Testimonials custom post type
*/
 
function testimonials_post_type() {
 
// Set UI labels for Optin Bars post type
    $labels = array(
        'name'                => _x( 'Testimonials', 'Post Type General Name' ),
        'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Testimonials' ),
        'parent_item_colon'   => __( 'Parent Testimonial' ),
        'all_items'           => __( 'Testimonials' ),
        'view_item'           => __( 'View Testimonial' ),
        'add_new_item'        => __( 'Add New Testimonial' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Testimonial' ),
        'update_item'         => __( 'Update Testimonial' ),
        'search_items'        => __( 'Search Testimonial' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Optin Bars post type
     
    $args = array(
        'label'               => __( 'Testimonials' ),
        'description'         => __( 'HMCA Testimonials' ),
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
    register_post_type( 'hmcatestimonials', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'testimonials_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_testimonials_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_testimonials_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'hmcatestimonials_';

	/**
	 * Initiate the metabox
	 */
	$hmcatestimonials = new_cmb2_box( array(
		'id'            => 'hmcatestimonials_metabox',
		'title'         => __( 'Testimonials Details', 'cmb2' ),
		'object_types'  => array( 'hmcatestimonials', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

    $hmcatestimonials->add_field( array(
            'name'    => 'Quote',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'quote',
            'type'    => 'textarea',
    ) );

/*    $hmcatestimonials->add_field( array(
            'name'    => 'Display Name',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'name',
            'type'    => 'text',
    ) );*/

}





// Add new Testimonials Product taxonomy
add_action( 'init', 'create_testimonials_product_taxonomy', 0 );
 
function create_testimonials_product_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Testimonial Product', 'taxonomy general name' ),
    'singular_name' => _x( 'Testimonials Product', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Products' ),
    'all_items' => __( 'All Products' ),
    'parent_item' => __( 'Parent Product' ),
    'parent_item_colon' => __( 'Parent Product:' ),
    'edit_item' => __( 'Edit Product' ), 
    'update_item' => __( 'Update Product' ),
    'add_new_item' => __( 'Add New Product' ),
    'new_item_name' => __( 'New Product' ),
    'menu_name' => __( 'Products' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('testimonialproducts',array('hmcatestimonials'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'testimonialcat' ),
  ));
 
}