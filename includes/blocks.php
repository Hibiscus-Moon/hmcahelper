<?php

/*
* Initializing the Blocks custom post type
*/
 
function blocks_post_type() {
 
// Set UI labels for Blocks post type
    $labels = array(
        'name'                => _x( 'Blocks', 'Post Type General Name' ),
        'singular_name'       => _x( 'Block', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Blocks' ),
        'parent_item_colon'   => __( 'Parent Block' ),
        'all_items'           => __( 'Blocks' ),
        'view_item'           => __( 'View Block' ),
        'add_new_item'        => __( 'Add New Block' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Block' ),
        'update_item'         => __( 'Update Block' ),
        'search_items'        => __( 'Search Blocks' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Blocks post type
     
    $args = array(
        'label'               => __( 'blocks' ),
        'description'         => __( 'HMCA Blocks' ),
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
    register_post_type( 'hmcablocks', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'blocks_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_blocks_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_blocks_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'hmcablocks_';

	/**
	 * Initiate the metabox
	 */
	$hmcablocks = new_cmb2_box( array(
		'id'            => 'hmcablocks_metabox',
		'title'         => __( 'Block Details', 'cmb2' ),
		'object_types'  => array( 'hmcablocks', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$hmcablocks->add_field( array(
    		'name'    => 'Block Link',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'link',
    		'type'    => 'text',
	) );

}







?>
