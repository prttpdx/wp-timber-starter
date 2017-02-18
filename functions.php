<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		load_theme_textdomain( 'wp-dead-simple-timber', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'menus' );
		add_image_size( 'big', 1920, 1080 );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		parent::__construct();
	}

	function add_to_context( $context ) {
		$context['menu'] = new TimberMenu('menu');
		$context['footer_menu_1'] = new TimberMenu('footer-menu-1');
		$context['footer_menu_2'] = new TimberMenu('footer-menu-2');
		$context['footer_menu_3'] = new TimberMenu('footer-menu-3');
		$context['site'] = $this;
		$context['options'] = get_fields('options');
		return $context;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		return $twig;
	}

}

new StarterSite();

function init_scripts() {
	
	// css
	wp_enqueue_style( 'wp-dead-simple-timber-vendor', get_template_directory_uri() . '/public/css/vendor.css' );
	wp_enqueue_style( 'wp-dead-simple-timber-app', get_template_directory_uri() . '/public/css/app.css' );

	// google maps
	wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBu5stYQshVeasrOVdUAyPDST8cL7jMTcQ');
	
	// js
	wp_enqueue_script( 'wp-dead-simple-timber-vendor-js', get_template_directory_uri() . '/public/js/vendor.js' );
	wp_enqueue_script( 'wp-dead-simple-timber-app-js', get_template_directory_uri() . '/public/js/app.js' );

	wp_localize_script( 'wp-dead-simple-timber-app-js', 'theme_url_data', array( 'themeurl' => get_template_directory_uri() ) );
	wp_localize_script( 'wp-dead-simple-timber-app-js', 'admin_ajax_data', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	// load theme style
	wp_enqueue_style( 'wp-dead-simple-timber-style', get_stylesheet_uri() );

}
add_action( 'wp_enqueue_scripts', 'init_scripts' );

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

function my_acf_init() {
	
	acf_update_setting('google_api_key', 'AIzaSyBu5stYQshVeasrOVdUAyPDST8cL7jMTcQ');
}
if( function_exists('acf_update_setting') ) {
	add_action('acf/init', 'my_acf_init');
}

function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function sanitize_field_key($key) {
	return ucfirst(str_replace('_', ' ', $key));
}

function renderToVar($file, $context){
    ob_start();
    Timber::render( $file, $context );
    return ob_get_clean();
}

function use_straight_join( $distinct_clause ) {
    $distinct_clause = 'STRAIGHT_JOIN' . $distinct_clause;
    return $distinct_clause;
}

// select
require_once('includes/select.php');
// ricerca
require_once('includes/search.php');
// paginazione
require_once('includes/pagination.php');
