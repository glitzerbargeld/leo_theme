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
    wp_enqueue_style( 'bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css", array('astra-theme-css'), CHILD_THEME_SANALEO_VERSION, 'all' );



}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 1000 );


/**
 * Enqueue scripts
 */

function child_enqueue_scripts(){
	wp_enqueue_script('sanaleo-animations-js', get_stylesheet_directory_uri() . '/js/animations.js', array(), false, true);
	wp_enqueue_script('rellax-js', get_stylesheet_directory_uri() . '/js/rellax-master/rellax.min.js', array(), false, true);
    wp_enqueue_script('my-rellax', get_stylesheet_directory_uri() . '/js/my-rellax.js', array(), false, true);
}

add_action( 'wp_enqueue_scripts', 'child_enqueue_scripts');



include_once( get_stylesheet_directory() .'/woocommerce/product_hooks.php');



function customise_product_page() {
  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
  add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_excerpt', 30);
}
add_action( 'woocommerce_before_single_product', 'customise_product_page' );


add_action( 'init', 'wpb_product_menu' );


function wpb_product_menu() {
	register_nav_menu('product-menu',__( 'Product Menu' ));
  }





  add_action( 'astra_main_header_bar_top', 'sanaleo_display_product_menu' );

  function sanaleo_display_product_menu(){
	echo '<div class = "product-dropdown"><img id="lionhead" class="alignnone size-full wp-image-11" src="https://sanaleo-cbd.de/wp-content/uploads/2021/04/CBD-Oele-CBD-Blueten-CBD-Vape-Produtke-Sanaleo-CBD-loewenkopf.png" alt="" width="50px" /><button id="product-menu-btn">Produkte</button>';

	wp_nav_menu( array( 
		'theme_location' => 'product-menu', 
		'container_class' => 'custom-menu-class',
		'container_id' => 'product-menu' ));

	echo '</div>';
}

/*

add_action( 'astra_main_header_bar_top', 'sanaleo_main_menu_overlay' );


function sanaleo_main_menu_overlay(){
    echo '<div id="myNav" class="overlay">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div class="overlay-content">';
    wp_nav_menu( array( 'main-menu', 'container_class' => 'my_extra_menu_class' ) );
    echo '</div>
  </div>';
    
}

*/


function range_slider(){
	echo '<div class="range-values"><ul><li>5%</li><li>15%</li><li>25%</li></ul></div>';
	echo '<div class="slidecontainer">
	
	<input type="range" min="5" max="25" step="10" value="25" class="slider" id="myRange" list="steps"> 
	
	<datalist id="steps" ><output>5</output><output>15</output><output>25</output></datalist> CBD Gehalt
  </div>';

}