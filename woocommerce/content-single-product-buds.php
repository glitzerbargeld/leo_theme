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
		<div class="ast-col-md-5 ast-col-xs-12 ast-col-md-push-7">
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
				add_action( 'woocommerce_before_add_to_cart_button', 'container_size_buds' );
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
				add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt' , 1);
				do_action( 'woocommerce_single_product_summary' );
				
				
				
			?>
		


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


<script>


const buds = document.querySelectorAll('.buds');
const select = document.getElementById("pa_menge");
const dropdownselect = jQuery("#pa_menge");
const selectoptions = select.options;
var selectoptionsarray = [];

for(var i=0; i<select.options.length; i++){
    selectoptionsarray.push(select.options[i].value);
}

buds.forEach(el => {
    if(selectoptionsarray.includes(el.getAttribute("data-el"))){
        el.parentElement.style.display = "block";
    }
    if(el.getAttribute("data-el") == select.value){el.style.backgroundColor = "rgb(136, 175, 136)"}
})




buds.forEach(el => el.addEventListener('click', event => {
    event.preventDefault();
    select.value = event.target.getAttribute("data-el");
	dropdownselect.change();
    buds.forEach(el => el.style.backgroundColor ="gray");
    event.target.style.backgroundColor = "rgb(136, 175, 136)";


}));

buds.forEach(el => el.addEventListener('touchstart', event => {
    event.preventDefault();
    select.value = event.target.getAttribute("data-el");
	dropdownselect.change();
    buds.forEach(el => el.style.backgroundColor ="gray");
    event.target.style.backgroundColor = "rgb(136, 175, 136)";


}));


</script> 
