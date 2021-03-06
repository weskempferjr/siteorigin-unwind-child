<?php

define ('UNWIND_CHILD_TEXT_DOMAIN', 'unwind-child');
define ('UNWIND_LOGGED_IN_MENU', 'Menu Logged In');
define ('UNWIND_CHILD_LOGGED_OUT_MENU', 'Menu Logged Out');

function my_theme_enqueue_styles() {

    $parent_style = 'siteorigin-unwind-style'; // This is for the parent theme styles

    wp_enqueue_style( $parent_style,
	                get_template_directory_uri() . '/style.css',
	                array('child-theme-mdb') );

    wp_enqueue_style( 'siteorigin-unwind-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function load_vendor_styles() {


	wp_enqueue_style( 'child-theme-bootstrap',
		get_stylesheet_directory_uri() . '/css/bootstrap.min.css',
		wp_get_theme()->get('Version'),
		false
	);

	wp_enqueue_style( 'child-theme-bootstrap-theme',
		get_stylesheet_directory_uri() . '/css/bootstrap-theme.min.css',
		array('child-theme-bootstrap'),
		wp_get_theme()->get('Version'),
		false
	);


	wp_enqueue_style( 'child-theme-mdb',
		get_stylesheet_directory_uri() . '/css/mdb.min.css',
		array('child-theme-bootstrap'),
		wp_get_theme()->get('Version'),
		false
	);





}

function load_vendor_scripts() {

	wp_enqueue_script( 'child-theme-js',
		get_stylesheet_directory_uri() . '/js/child-theme.js',
		array('jquery'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script( 'child-theme-bootstrap-js',
		get_stylesheet_directory_uri() . '/js/bootstrap.min.js',
		array('jquery'),
		wp_get_theme()->get('Version'),
		true
	);


	wp_enqueue_script( 'child-theme-mdb-js',
		get_stylesheet_directory_uri() . '/js/mdb.min.js',
		array('jquery', 'child-theme-bootstrap-js'),
		wp_get_theme()->get('Version'),
		true
	);



}

add_action( 'wp_enqueue_scripts', 'load_vendor_scripts' );
add_action( 'wp_enqueue_scripts', 'load_vendor_styles' );


/**
 * Setup My Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function my_child_theme_setup() {
    load_child_theme_textdomain( 'siteorigin-unwind-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'my_child_theme_setup' );


/*
 * Re-register the parent theme sidebars to remove the "heading-strike" class from
 * the header elements.
 */
function siteorigin_unwind_child_widgets_init() {


	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'siteorigin-unwind' ),
		'id'            => 'main-sidebar',
		'description'   => esc_html__( 'Visible on posts and pages that use the Default or Full Width, With Sidebar layout.', 'siteorigin-unwind' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'siteorigin-unwind' ),
		'id'            => 'footer-sidebar',
		'description'   => esc_html__( 'A column will be automatically assigned to each widget inserted', 'siteorigin-unwind' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	if ( function_exists( 'is_woocommerce' ) ) {
		register_sidebar( array(
			'name' 			=> esc_html__( 'Shop', 'siteorigin-unwind' ),
			'id' 			=> 'shop-sidebar',
			'description' 	=> esc_html__( 'Displays on WooCommerce pages.', 'siteorigin-unwind' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</aside>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
		) );
	}

	register_sidebar( array(
		'name'          => esc_html__( 'Masthead', 'siteorigin-unwind' ),
		'id'            => 'masthead-sidebar',
		'description'   => esc_html__( 'Replaces the logo and description.', 'siteorigin-unwind' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

remove_action('widgets_init', 'siteorigin_unwind_widgets_init' );
add_action( 'widgets_init', 'siteorigin_unwind_child_widgets_init', 20 );

require get_stylesheet_directory() . '/inc/class-child-theme-shortcodes.php';

$ct_shortcodes = new Child_Theme_Shortcodes();
$ct_shortcodes->register();



if ( !function_exists( 'insert_user_icons' ) ):

	function insert_user_icons() {
		echo '<div class="gpc-user-icons"><i class="fa fa-user"></i><i class="fa fa-search"></i></div>';
	}

endif;

// add_action('generate_before_header_content', 'insert_user_icons');

function my_wp_nav_menu_args( $args = '' ) {


	if( is_user_logged_in() ) {
		$args['menu'] = UNWIND_CHILD_LOGGED_IN_MENU;
	} else {
		$args['menu'] = UNWIND_CHILD_LOGGED_OUT_MENU ;
	}


	/*
	if (is_user_logged_in() ) {
		if ( current_user_can('mepr-active','rule:1109') ) {
			$args['menu'] = 'AAPAS Licensed Members';
		}
		elseif ( current_user_can('mepr-active','rule:1110')) {
			$args['menu'] = 'AAPAS Non-licensed Members';
		}
		else {
			$args['menu'] = 'AAPAS Menu Logged In';
		}

	}
	else {
		$args['menu'] = 'AAPAS Menu Logged Out';
	}
	*/

	return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

function auto_redirect_after_logout(){
	wp_redirect( home_url() );
	exit();
}
add_action('wp_logout','auto_redirect_after_logout');



function mb_post_sign_up( $user ) {
	$wp_user = get_user_by('ID', $user->rec->ID);
	$wp_user->remove_role('subscriber');
	$wp_user->add_role('No role for this site');
}

// add_action( 'mepr_user_account_saved','mb_post_sign_up');


