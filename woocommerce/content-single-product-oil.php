<style>
	.woocommerce div.product form.cart .variations {
	display: none;
	}
</style>


<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	


	<div class="ast-row">
		<div class="ast-col-md-5 ast-col-xs-12 ast-col-md-push-7 image-wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );

			?>
		</div>

		<div class="ast-col-md-4 ast-col-xs-12 ast-col-md-pull-4">

			

			<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				add_action( 'woocommerce_before_add_to_cart_button', 'range_slider' );
				add_action('woocommerce_product_thumbnails', 'woocommerce_template_single_excerp');
				do_action( 'woocommerce_single_product_summary' );
				
				
				
		

add_action( 'woocommerce_single_product_summary', 'woocommerce_total_product_price', 25 );
function woocommerce_total_product_price() {
    global $woocommerce, $product;
    // let's setup our divs
    echo sprintf('<div id="product_total_price" style="font-size: 16px; font-weight: 200;">%s %s</div>',__('Total Price (incl Tax):','woocommerce'),'<span class="price">'. get_woocommerce_currency_symbol() .' ' .$product->get_price().'</span>');
    ?>
        <script>
            jQuery(function($){
                var price = <?php echo $product->get_price(); ?>,
                    currency = '<?php echo get_woocommerce_currency_symbol(); ?>';

                $('[name=quantity]').change(function(){
                    if (!(this.value < 1)) {

                        var product_total = parseFloat(price * this.value);

                        $('#product_total_price .price').html( currency + product_total.toFixed(0));

                    }
                });
            });
        </script>
    <?php
} ?>
		


		</div>
		


	</div>




	

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>



<?php

/*

  $( function() {
    var select = $( "#anteil-cbd" );
    var slider = $( "#variation-slider" ).slider({
      min: 5,
      max: 15,
	  step: 5,
      range: "min",
      value: select[ 0 ].selectedIndex + 1,
      slide: function( event, ui ) {
        select[ 0 ].selectedIndex = ui.value - 1;
      }
    });
    $( "#anteil-cbd" ).on( "change", function() {
      slider.slider( "value", this.selectedIndex + 1 );
    });
  } );

  */
?>



<script>



	// EventListener hinzufügen
window.addEventListener("load", function(){

// Range-Slider in Variable speichern 
var slider = document.querySelector("input[type='range']");

// EventListener für das Verändern des Sliders hinzufügen
slider.addEventListener("change", function(){

console.log("change");
let element = document.getElementById("anteil-cbd");
element.value = this.value + "%";
element.dispatchEvent(new Event('change'))
console.log(element.value);


});
});

</script> 
