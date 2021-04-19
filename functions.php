<?php
/**
 * Sanaleo Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Sanaleo
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_SANALEO_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'sanaleo-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_SANALEO_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );


/**
 * Enqueue scripts
 */

function child_enqueue_scripts(){
	wp_enqueue_script('sanaleo-animations-js', get_stylesheet_directory_uri() . '/js/animations.js', array(), false, true);
	wp_enqueue_script('rellax-js', get_stylesheet_directory_uri() . '/js/rellax-master/rellax.min.js', array(), false, true);
}

add_action( 'wp_enqueue_scripts', 'child_enqueue_scripts');



include_once( get_stylesheet_directory() .'/woocommerce/product_hooks.php');


add_action( 'woocommerce_before_single_product', 'customise_product_page' );
function customise_product_page() {
  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
  // ... any other removes and adds here
}


function wpb_product_menu() {
	register_nav_menu('product-menu',__( 'Product Menu' ));
  }
  add_action( 'init', 'wpb_product_menu' );

  function display_product_menu(){
	wp_nav_menu( array( 
		'theme_location' => 'product-menu', 
		'container_class' => 'custom-menu-class',
		'container_id' => 'product-menu' )); 
  }

  add_action('astra_header', 'display_product_menu');