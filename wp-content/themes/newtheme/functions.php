<?php
// function themename_custom_logo_setup() {
// 	$defaults = array(
// 		'height'               => 100,
// 		'width'                => 400,
// 		'flex-height'          => true,
// 		'flex-width'           => true,
// 		'header-text'          => array( 'site-title', 'site-description' ),
// 		'unlink-homepage-logo' => true, 
// 	);
// 	add_theme_support( 'custom-logo', $defaults );
// }
// add_action( 'after_setup_theme', 'themename_custom_logo_setup' );

add_action( 'wp_enqueue_scripts', 'theme_slug_enqueue_styles' );

function theme_slug_enqueue_styles() {
	$asset = include get_theme_file_path( 'public/css/screen.asset.php' );
	wp_enqueue_style(
		'screen-style',
		get_theme_file_uri( 'public/css/screen.css' ),
		$asset['dependencies'],
		$asset['version']
	);
	wp_enqueue_script('main-index-js',get_theme_file_uri('/public/js/index.js'));
	// wp_enqueue_style( 
	// 	'theme-slug-style', 
	// 	get_stylesheet_uri()
	// );
};

// Load editor stylesheets.
// add_action( 'after_setup_theme', 'themeslug_editor_styles' );

// function themeslug_editor_styles() {
// 	add_editor_style( [
// 		get_theme_file_uri( 'public/css/screen.css' )
// 	] );
// }