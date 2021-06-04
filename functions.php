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
    wp_enqueue_style('jquery-ui-css', get_stylesheet_directory_uri() . '/inc/jquery-ui.min.css', array('astra-theme-css'), CHILD_THEME_SANALEO_VERSION, 'all' );


}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 1000 );


/**
 * Enqueue scripts
 */

function child_enqueue_scripts(){
	wp_enqueue_script('sanaleo-animations-js', get_stylesheet_directory_uri() . '/js/animations.js', array(), false, true);
	wp_enqueue_script('rellax-js', get_stylesheet_directory_uri() . '/js/rellax-master/rellax.min.js', array(), false, true);
    wp_enqueue_script('my-rellax', get_stylesheet_directory_uri() . '/js/my-rellax.js', array(), false, true);
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_script('jquery-ui-touchpunch', get_stylesheet_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array(), false, true);

    
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

function range_slider(){
	echo '<div class="range-values"><ul><li>5%</li><li>15%</li><li>25%</li></ul></div>';
	echo '<div id="variations-slider"><h3 id="cbdanteil-title"style="color: white;">CBD ANTEIL</h3></div>';

}

function container_size_buds(){
  echo '
    <div class="content">
      <div class="glass-wrapper">
          <span class="glass"><div class="buds" data-el="2g">2g</div></span>
          <span class="glass"><div class="buds" data-el="5g">5g</div></span>
          <span class="glass"><div class="buds" data-el="10g"">10g</div></span>
          <span class="glass"><div class="buds" data-el="20g"">20g</div></span>
      </div>
    </div>
  ';
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'filter_dropdown_option_html', 12, 2 );
function filter_dropdown_option_html( $html, $args ) {
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' );
    $show_option_none_html = '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

    $html = str_replace($show_option_none_html, '', $html);

    return $html;
}


// Removes specific product cats from single product pages

add_filter( 'get_the_terms', 'custom_product_cat_terms', 20, 3 );
function custom_product_cat_terms( $terms, $post_id, $taxonomy ){
    // HERE below define your excluded product categories Term IDs in this array
    $category_ids = array( 174,168,176,175,177,178 );

    if( ! is_product() ) // Only single product pages
        return $terms;

    if( $taxonomy != 'product_cat' ) // Only product categories custom taxonomy
        return $terms;

    foreach( $terms as $key => $term ){
        if( in_array( $term->term_id, $category_ids ) ){
            unset($terms[$key]); // If term is found we remove it
        }
    }
    return $terms;
}


// Add Product Attributes to Shop Page

add_action('woocommerce_after_shop_loop_item_title', 'display_shop_loop_product_attributes');
function display_shop_loop_product_attributes() {
    global $product;

    // Define you product attribute taxonomies in the array
    $product_attribute_taxonomies = array( 'pa_country', 'pa_class', 'pa_faction', 'pa_gender' );
    $attr_output = array(); // Initializing

    // Loop through your defined product attribute taxonomies
    foreach( $product_attribute_taxonomies as $taxonomy ){
        if( taxonomy_exists($taxonomy) ){
            $label_name = wc_attribute_label( $taxonomy, $product );

            $term_names = $product->get_attribute( $taxonomy );

            if( ! empty($term_names) ){
                $attr_output[] = '<span class="'.$taxonomy.'">'.$label_name.': '.$term_names.'</span>';
            }
        }
    }

    // Output
    echo '<div class="product-attributes">'.implode( '<br>', $attr_output ).'</div>';
}



// FULL PRICE including Quantity
function woocommerce_total_product_price() {
    global $woocommerce, $product;
    echo sprintf('<div id="product_total_price" style="font-size: 16px; font-weight: 200;">%s %s</div>',__('Total Price (incl Tax):','woocommerce'),'<span class="price">'. get_woocommerce_currency_symbol() .' ' .$product->get_price().'</span>');}



if(is_product_category( 'buds' )){
    echo '
    <script>
        alert("BUDS");
    </script>
    
    ';

}

add_action('woocommerce_before_single_product', 'entferne_single_excerpt');
function entferne_single_excerpt(){
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
}