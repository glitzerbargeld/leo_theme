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



function customise_product_page() {
  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
  add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_excerpt', 30);
}
add_action( 'woocommerce_before_single_product', 'customise_product_page' );



function wpb_product_menu() {
	register_nav_menu('product-menu',__( 'Product Menu' ));
  }
  add_action( 'init', 'wpb_product_menu' );


  function display_product_menu(){
	echo '<div class = "product-dropdown"><img id="lionhead" class="alignnone size-full wp-image-11" src="https://sanaleo-cbd.de/wp-content/uploads/2021/04/CBD-Oele-CBD-Blueten-CBD-Vape-Produtke-Sanaleo-CBD-loewenkopf.png" alt="" width="50px" /><button id="product-menu-btn">Produkte</button>';

	wp_nav_menu( array( 
		'theme_location' => 'product-menu', 
		'container_class' => 'custom-menu-class',
		'container_id' => 'product-menu' ));

	echo '</div>';
}


function range_slider(){
	echo '<div class="range-values"><ul><li>5%</li><li>15%</li><li>25%</li></ul></div>';
	echo '<div class="slidecontainer">
	
	<input type="range" min="5" max="25" step="10" value="25" class="slider" id="myRange" list="steps"> 
	
	<datalist id="steps" ><output>5</output><output>15</output><output>25</output></datalist> CBD Gehalt
  </div>';

}
add_action( 'astra_header_after', 'display_product_menu' );



/**ACF Woocommerce Product Variations */


// Render fields at the bottom of variations - does not account for field group order or placement.
add_action( 'woocommerce_product_after_variable_attributes', function( $loop, $variation_data, $variation ) {
    global $abcdefgh_i; // Custom global variable to monitor index
    $abcdefgh_i = $loop;
    // Add filter to update field name
    add_filter( 'acf/prepare_field', 'acf_prepare_field_update_field_name' );
    
    // Loop through all field groups
    $acf_field_groups = acf_get_field_groups();
    foreach( $acf_field_groups as $acf_field_group ) {
        foreach( $acf_field_group['location'] as $group_locations ) {
            foreach( $group_locations as $rule ) {
                // See if field Group has at least one post_type = Variations rule - does not validate other rules
                if( $rule['param'] == 'post_type' && $rule['operator'] == '==' && $rule['value'] == 'product_variation' ) {
                    // Render field Group
                    acf_render_fields( $variation->ID, acf_get_fields( $acf_field_group ) );
                    break 2;
                }
            }
        }
    }
    
    // Remove filter
    remove_filter( 'acf/prepare_field', 'acf_prepare_field_update_field_name' );
}, 10, 3 );

// Filter function to update field names
function  acf_prepare_field_update_field_name( $field ) {
    global $abcdefgh_i;
    $field['name'] = preg_replace( '/^acf\[/', "acf[$abcdefgh_i][", $field['name'] );
    return $field;
}
    
// Save variation data
add_action( 'woocommerce_save_product_variation', function( $variation_id, $i = -1 ) {
    // Update all fields for the current variation
    if ( ! empty( $_POST['acf'] ) && is_array( $_POST['acf'] ) && array_key_exists( $i, $_POST['acf'] ) && is_array( ( $fields = $_POST['acf'][ $i ] ) ) ) {
        foreach ( $fields as $key => $val ) {
            update_field( $key, $val, $variation_id );
        }
    }
}, 10, 2 );