<?php




function hmca_header_scripts(){ 

    $id = get_the_ID();

    $hide_header_optin = get_post_meta( $id, 'hmcaoptin_hide_header_optin', true ); 
    if ( $hide_header_optin ) { ?>
        <style>
            .pum.pum-overlay {display: none !important;}
        </style>
    <?php } ?>

    <!-- Facebook Pixel Code -->

    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','//connect.facebook.net/en_US/fbevents.js');
    fbq('init', '241753902680051');
    fbq('track', 'PageView');
    </script>

    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=241753902680051&ev=PageView&noscript=1" /></noscript>

    <!-- End Facebook Pixel Code -->

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
	//    var x = document.getElementByClassName("merciful-heading");
	//    x.innerHTML = x.innerHTML.replace(/’/g, "'");
	</script>

	<?php 
	$id = get_the_ID();

	if ( get_post_meta( $id, 'hmca_zopim', true ) ) { ?>
	<!--Start of Zendesk Chat Script-->
	<script type="text/javascript">
		window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
		d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
		_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
		$.src="https://v2.zopim.com/?3hXmNSOsocmBvv6vZAwxCcFf4t2b0sEI";z.t=+new Date;$.
		type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
	</script>
	<!--End of Zendesk Chat Script-->
	<?php 
	}
}
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

    $newhereitems = cmb2_get_option( 'hmcaoptions', 'hmca_newhere_posts' ); ?>

    <ul class="hmca-popular-posts">

        <?php foreach ( $newhereitems as $newhereitem ) {  ?>
            
            <li><a href="<?php echo get_the_permalink($newhereitem); ?>"><?php echo get_the_title($newhereitem); ?></a></li>

        <?php } ?>

    </ul>
        
    <?php wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}




add_action( 'after_setup_theme', 'new_image_sizes' );
function new_image_sizes() {
    add_image_size( 'home-thumb', 375 ); // 375 pixels wide (and unlimited height)
}






/**
 * Shortcode for single blog post related articles
 */
add_shortcode( 'hmca_recent_posts', 'hmca_recent_posts_shortcode' );
function hmca_recent_posts_shortcode( $atts ) {
    ob_start();
    
    // define query parameters based on attributes
        $rpargs = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',
            'posts_per_page' => 3,
            'post_status' => 'publish',
        );

        $rpquery = new WP_Query( $rpargs );
        if ( $rpquery->have_posts() ) { ?>
            <div class="homerecentposts">
            <?php while( $rpquery->have_posts() ) : $rpquery->the_post();
                $id = get_the_ID(); ?>
            
                <div class="single-recent-post">
                    <a href="<?php echo the_permalink(); ?>"><?php echo get_the_post_thumbnail($id, 'home-thumb'); ?></a>
                    <div class="recent-post-bottom">
                        <a href="<?php echo the_permalink(); ?>"><h3><?php echo the_title(); ?></h3></a>
                    </div>
                </div>

            <?php endwhile; ?>
            </div>
        <?php }
        
    wp_reset_postdata();
    $myvariable = ob_get_clean();
    return $myvariable;
}




/**
 * btc_wplogin_styles
 * Styles for wp-login.php
 * 
 * @return string css
 * @since  0.1.1 similar to /login-page
 */
function hmca_wplogin_styles() { ?>
<!--	<link rel='stylesheet' id='montserrat-css'  href='https://fonts.googleapis.com/css?family=Montserrat%3A400&#038;subset=latin&#038;' type='text/css' media='all' /> -->
	<style type="text/css">
		#login {
			width: 100%;
			max-width: 450px;
		}
		#login p#nav {
			text-align: center;
		/*	font-family: montserrat; */
			font-weight: 300;
			margin-top: 10px;
			font-size: 12px;
		}
		#login p#backtoblog {
			text-align: center;
		/*	font-family: montserrat; */
			font-weight: 300;
			margin-top: 30px;
			font-size: 16px;
		}

		.login h1 a {
			background-image: url(https://hibiscusmooncrystalacademy.com/wp-content/uploads/2019/12/HM_Logo_Header-1.png) !important;
			width: 100% !important;
			background-size: 100%!important;
		/*	height: 90px !important; */
			margin: 0 0 40px !important;
			max-width: 450px;
		}
		body {
			/* background-image: url(<?php // echo $bkg ?>) !important; */
			background-position: left center !important;
			background-repeat: no-repeat !important;
			background-size:cover!important;
			background-attachment:fixed!important;
			display: table;
			width: 100%;
		}
		body.login form {
			background: transparent;
			/* color: #fff; */
			box-shadow: none;
			-webkit-box-shadow: none;
			padding-bottom: 0;
			padding-top: 0;
			max-width: 320px;
			margin: 20px auto 0;
			border: 0;
		}
		.login form .input, .login input[type=text] {
			padding: 4px 8px;
			background: #eaeaea !important;
			color: #555 !important;
			border-radius: 2px;
		}
		.login form input#rememberme {

		}

		.login form .forgetmenot {
			float: none;
			text-align: right;
			margin-bottom: 20px !important;
		}
		.login form label[for="user_login"], .login form label[for="user_pass"] {
		/*	font-family: montserrat; */
			font-weight: 300;
		}
		.login form label[for="rememberme"] {
		/*	font-family: montserrat; */
			font-size: 14px;
			font-weight: 300;
		}

		.login label,
		.login #backtoblog a,
		.login #nav a {
		/*	color:#fff!important; */
		}
		body.login .message {
			border: none;
			background: none;
		/*	color: #fff; */
		/*	font-family: montserrat; */
			text-align: center;
			font-size: 16px;
			font-weight: 300;
			padding: 0 10px;
			max-width: 320px;
			margin: 0 auto;
		}
		.wp-core-ui .button-primary {
			background: #5e1172!important;
			border: 2px solid #5e1172 !important;
			border-radius: 0;
			box-shadow: none!important;
			text-shadow: none!important;
			text-transform: uppercase;
		/*	font-family: montserrat; */
			float: none;
			width: 100%;
			padding: 7px !important;
			height: auto !important;
			font-size: 18px !important;
			line-height: 28px !important;
		}
		.wp-core-ui .button-primary:hover {
			background: #bbe9f6 !important;
			color: #5e1172 !important;
		}
		.description {
			color: #e7e7e7;
			text-align: center;
		}
		::-moz-selection { background: #5e1172; color: #fff; }
		::selection { background: #5e1172; color: #fff; }
	</style>
    <?php 
}
add_action( 'login_head', 'hmca_wplogin_styles' );

/**
 * Filter the wp-login.php logo link
 */
function hmca_login_logo_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'hmca_login_logo_url' );

// Auto login after reset password success
function hmca_password_reset_login( $user ) {
	wp_set_auth_cookie( $user->ID, true );
	do_action( 'wp_login', $user->user_login );
	wp_redirect( site_url( '/my-account/' ) );
	exit;
}
add_action( 'after_password_reset', 'hmca_password_reset_login', 10, 1 );

// Redirect wp-login.php for logged in users
function hmca_redirect_wp_login() {
	if ( ( $GLOBALS['pagenow'] === 'wp-login.php' ) && is_user_logged_in() ) {
		wp_redirect( site_url( '/my-account/' ) );
	}
}
add_action( 'init', 'hmca_redirect_wp_login' );

function hmca_login_errors(){
	return '<strong>Login Error:</strong> Please try again.';
}
add_filter( 'login_errors', 'hmca_login_errors' );

/**
 * Prevent empty password brute force attacks
 */
function hmca_wplogin_scripts() { ?>
<script type="text/javascript">
	document.forms["loginform"].onsubmit = function(){
		if( "" == document.forms["loginform"]["user_pass"].value ) {
			alert( "Please enter a password" );
			return false;
		}
	}
</script>
<?php 
}
add_action( 'login_footer', 'hmca_wplogin_scripts' );