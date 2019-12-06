<?php



/*
* Initializing the Optin Bars custom post type
*/
 
function optinbar_post_type() {
 
// Set UI labels for Optin Bars post type
    $labels = array(
        'name'                => _x( 'Optin Bars', 'Post Type General Name' ),
        'singular_name'       => _x( 'Optin Bar', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Optin Bars' ),
        'parent_item_colon'   => __( 'Parent Optin Bar' ),
        'all_items'           => __( 'Optin Bars' ),
        'view_item'           => __( 'View Optin Bar' ),
        'add_new_item'        => __( 'Add New Optin Bar' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Optin Bar' ),
        'update_item'         => __( 'Update Optin Bar' ),
        'search_items'        => __( 'Search Optin Bars' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Optin Bars post type
     
    $args = array(
        'label'               => __( 'optinbar' ),
        'description'         => __( 'HMCA Optin Bars' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'author', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => 'hmcasettings',
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
    register_post_type( 'hmcaoptinbar', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'optinbar_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_optinbar_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_optinbar_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'hmcaoptinbar_';

	/**
	 * Initiate the metabox
	 */
	$hmcaoptinbar = new_cmb2_box( array(
		'id'            => 'hmcaoptinbar_metabox',
		'title'         => __( 'Optin Bar Details', 'cmb2' ),
		'object_types'  => array( 'hmcaoptinbar', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


}





// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'hmca-optin-bar', 'hmca_optin_bar_shortcode' );
function hmca_optin_bar_shortcode( $atts ) {
    ob_start();
    
    // define query parameters based on attributes
        $obargs = array(
            'post_type' => 'hmcaoptinbar',
            'order' => 'DESC',
            'orderby' => 'date',
            'posts' => 1,
            'post_status' => 'publish'
        );

        $obquery = new WP_Query( $obargs );
        if ( $obquery->have_posts() ) {
            while( $obquery->have_posts() ) : $obquery->the_post();
                $id = get_the_ID();
                echo the_content($id);
            endwhile;
        }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}





?>
