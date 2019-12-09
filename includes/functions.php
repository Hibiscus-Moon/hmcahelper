<?php


add_action( 'cmb2_admin_init', 'cmb2_hmcaoptin_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_hmcaoptin_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'hmcaoptin_';

	/**
	 * Initiate the metabox
	 */
	$hmcaoptin = new_cmb2_box( array(
		'id'            => 'hmcaoptin_metabox',
		'title'         => __( 'HMCA - Opt-In Options', 'cmb2' ),
		'object_types'  => array( 'page' ), // Post type
		'context'       => 'side',
		'priority'      => 'low',
		'show_names'    => false, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$hmcaoptin->add_field( array(
    		'name' => 'Remove Sticky Header Opt-In',
    		'desc' => 'Check box to remove promotional popups.',
    		'id'   => $prefix . 'hide_header_optin',
    		'type' => 'checkbox',
	) );


}


function hmca_header_scripts() { 
	$id = get_the_ID();

	$hide_header_optin = get_post_meta( $id, 'hmcaoptin_hide_header_optin', true ); 
	if ( $hide_header_optin ) { ?>
		<style>
		    .pum.pum-overlay {display: none !important;}
		</style>
	<?php } 	
}
add_action( 'wp_head', 'hmca_header_scripts', 10 );





/**
 * Taxonomy for admin organization of pages
 */
function hmca_create_page_type_taxonomy() {
	$labels = array(
		'name'              => 'Page Types',
		'singular_name'     => 'Page Type',
		'search_items'      => 'Search Page Types',
		'all_items'         => 'All Page Types',
		'parent_item'       => 'Parent Page Type',
		'parent_item_colon' => 'Parent Page Type:',
		'edit_item'         => 'Edit Page Type',
		'update_item'       => 'Update Page Type',
		'add_new_item'      => 'Add New Page Type',
		'new_item_name'     => 'New Page Type Name',
		'menu_name'         => 'Page Types',
		'not_found'         => 'No Page Types found',
	);

	register_taxonomy(
		'page_type',
		'page',
		array(
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'rewrite' => false,
			'with_front' => false,
			'show_admin_column' => true,
			'hierarchical' => true,
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'hmca_create_page_type_taxonomy' );






/**
 * Shortcode for single blog post related articles
 */
add_shortcode( 'hmca_related_posts', 'hmca_related_posts_shortcode' );
function hmca_related_posts_shortcode( $atts ) {
    ob_start();

    $currentpost = get_the_ID();
    $currentcategories = wp_get_post_categories($currentpost);
    
    // define query parameters based on attributes
        $rpargs = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',
            'posts' => 3,
            'post_status' => 'publish',
            'category__in' => array($currentcategories),
            'date_query' => array(
                array(
                    'after'     => 'January 1st, 2017',
                    'inclusive' => true,
                ),
            ),
        );

        $rpquery = new WP_Query( $rpargs );
        if ( $rpquery->have_posts() ) { ?>
            <div class="relatedposts">
            <?php while( $obquery->have_posts() ) : $obquery->the_post();
                $id = get_the_ID(); ?>
            
                <div class="single-related-post">
                    <a href="<?php echo the_permalink(); ?>"><img src="<?php echo get_the_post_thumbnail($id, 'medium'); ?>"></a>
                    <a href="<?php echo the_permalink(); ?>"><h4><?php echo the_title(); ?></a>
                </div>

            <?php endwhile; ?>
            </div>
        <?php }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}






/**
 * Shortcode for single blog post related articles
 */
add_shortcode( 'hmca_popular_posts', 'hmca_popular_posts_shortcode' );
function hmca_popular_posts_shortcode( $atts ) {
    ob_start();
    
    // define query parameters based on attributes
        $ppargs = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'comment_count',
            'posts' => 10,
            'post_status' => 'publish',
            'date_query' => array(
                array(
                    'after'     => 'January 1st, 2017',
                    'inclusive' => true,
                ),
            ),
        );

        $ppquery = new WP_Query( $ppargs );
        if ( $ppquery->have_posts() ) { ?>
            <ul class="hmca-popular-posts">
            <?php while( $obquery->have_posts() ) : $obquery->the_post();
                $id = get_the_ID(); ?>
            
                <li><a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a></li>

            <?php endwhile; ?>
            </ul>
        <?php }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}