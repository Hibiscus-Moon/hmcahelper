<?php

function hmca_header_scripts(){ 

    $id = get_the_ID();

    $hide_header_optin = get_post_meta( $id, 'hmcaoptin_hide_header_optin', true ); 
    if ( $hide_header_optin ) { ?>
        <style>
            .pum.pum-overlay {display: none !important;}
        </style>
    <?php } ?>

    <!-- Deadline Funnel -->
    <script type="text/javascript" data-cfasync="false">function SendUrlToDeadlineFunnel(e){var r,t,c,a,h,n,o,A,i = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",d=0,l=0,s="",u=[];if(!e)return e;do r=e.charCodeAt(d++),t=e.charCodeAt(d++),c=e.charCodeAt(d++),A=r<<16|t<<8|c,a=A>>18&63,h=A>>12&63,n=A>>6&63,o=63&A,u[l++]=i.charAt(a)+i.charAt(h)+i.charAt(n)+i.charAt(o);while(d<e.length);s=u.join("");var C=e.length%3;var decoded = (C?s.slice(0,C-3):s)+"===".slice(C||3);decoded = decoded.replace("+", "-");decoded = decoded.replace("/", "_");return decoded;} var url = SendUrlToDeadlineFunnel(location.href);var parentUrl = (parent !== window) ? ("/" + SendUrlToDeadlineFunnel(document.referrer)) : "";(function() {var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.setAttribute("data-scriptid", "dfunifiedcode");s.src ="https://a.deadlinefunnel.com/unified/reactunified.bundle.js?userIdHash=eyJpdiI6IkRcL0Y5bHdhNnBZUU5NUUNiTWV6M29BPT0iLCJ2YWx1ZSI6IjZEZUhneXJXclwvb0ZrU1dRdFZ5Nk1nPT0iLCJtYWMiOiJjMWUyZTMyZjNhN2NmYmQ0OTA3ZWY5Y2ZlMmU4YTczMjRhYTVlNDlkN2Y2NzEwMzZjNDA2NDVmMTFlOTIxMjhmIn0=&pageFromUrl="+url+"&parentPageFromUrl="+parentUrl;var s2 = document.getElementsByTagName("script")[0];s2.parentNode.insertBefore(s, s2);})();</script><!-- End Deadline Funnel -->

    <script src="https://kit.fontawesome.com/6e0c322d24.js"></script>

    <!-- <script type="text/javascript" src="https://my.hellobar.com/b38b7e9e36c8ad5482cd32aa93cd5a03181ab1b0.js" async="async"></script> -->

<?php }
add_action( 'wp_head', 'hmca_header_scripts', 10 );




function hmca_footer_scripts() { ?>
    <script type="text/javascript">
        // Set to false if opt-in required
        var trackByDefault = true;

        function acEnableTracking() {
            var expiration = new Date(new Date().getTime() + 1000 * 60 * 60 * 24 * 30);
            document.cookie = "ac_enable_tracking=1; expires= " + expiration + "; path=/";
            acTrackVisit();
        }

        function acTrackVisit() {
            var trackcmp_email = '';
            var trackcmp = document.createElement("script");
            trackcmp.async = true;
            trackcmp.type = 'text/javascript';
            trackcmp.src = '//trackcmp.net/visit?actid=799050921&e='+encodeURIComponent(trackcmp_email)+'&r='+encodeURIComponent(document.referrer)+'&u='+encodeURIComponent(window.location.href);
            var trackcmp_s = document.getElementsByTagName("script");
            if (trackcmp_s.length) {
                trackcmp_s[0].parentNode.appendChild(trackcmp);
            } else {
                var trackcmp_h = document.getElementsByTagName("head");
                trackcmp_h.length && trackcmp_h[0].appendChild(trackcmp);
            }
        }

        if (trackByDefault || /(^|; )ac_enable_tracking=([^;]+)/.test(document.cookie)) {
            acEnableTracking();
        }
    </script>

    <script type="text/javascript">
        // This code fixes the apostrophes in this section's title.
        var x = document.getElementByClassName("merciful-heading");
        x.innerHTML = x.innerHTML.replace(/’/g, "'");
    </script>
<?php }
add_action( 'wp_footer', 'hmca_footer_scripts', 10);




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

    $hmcaoptin->add_field( array(
            'name' => 'Display Optin Bar',
            'desc' => 'Check box to show full width optin bar',
            'id'   => $prefix . 'show_optin_bar',
            'type' => 'checkbox',
    ) );


}






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
            'posts_per_page' => 3,
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
            <?php while( $rpquery->have_posts() ) : $rpquery->the_post();
                $id = get_the_ID(); ?>
            
                <div class="single-related-post">
                    <a href="<?php echo the_permalink(); ?>"><?php echo get_the_post_thumbnail($id, 'medium'); ?></a>
                    <a href="<?php echo the_permalink(); ?>"><h4><?php echo the_title(); ?></h4></a>
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
            'posts_per_page' => 10,
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
            <?php while( $ppquery->have_posts() ) : $ppquery->the_post();
                $id = get_the_ID(); ?>
            
                <li><a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a></li>

            <?php endwhile; ?>
            </ul>
        <?php }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}