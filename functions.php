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

    // wp_enqueue_style( 'bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css", array('astra-theme-css'), CHILD_THEME_SANALEO_VERSION, 'all' );

    wp_enqueue_style( 'sanaleo-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_SANALEO_VERSION, 'all' );
    wp_enqueue_style('jquery-ui-css', get_stylesheet_directory_uri() . '/inc/jquery-ui.min.css', array('astra-theme-css'), CHILD_THEME_SANALEO_VERSION, 'all' );
	wp_enqueue_style('mailchimp', '//cdn-images.mailchimp.com/embedcode/classic-10_7.css', array('astra-theme-css'), CHILD_THEME_SANALEO_VERSION, 'all' );

	//cdn-images.mailchimp.com/embedcode/classic-10_7.css

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', PHP_INT_MAX );


/**
 * Enqueue scripts
 */

function child_enqueue_scripts(){
	wp_enqueue_script('sanaleo-animations-js', get_stylesheet_directory_uri() . '/js/animations.js', array(), false, true);
	wp_enqueue_script('rellax-js', get_stylesheet_directory_uri() . '/js/rellax-master/rellax.min.js', array(), false, true);
  wp_enqueue_script('my-rellax', get_stylesheet_directory_uri() . '/js/my-rellax.js', array(), false, true);
  wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array(), false, true);
  wp_enqueue_script( 'jquery-ui-slider' );
  wp_enqueue_script('jquery-ui-touchpunch', get_stylesheet_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array(), false, true); 

  if (is_front_page()) {
    wp_enqueue_script('replacement', get_stylesheet_directory_uri() . '/js/replacement.js', array(), false, true);
  }
}




add_action( 'wp_enqueue_scripts', 'child_enqueue_scripts');

include_once( get_stylesheet_directory() .'/woocommerce/product_hooks.php');

/* change links on category archive pages
add_action( 'woocommerce_before_shop_loop_item', 'customizing_loop_product_link_open', 9 );
function customizing_loop_product_link_open() {
    global $product;

    #// HERE BELOW, replace clothing' with your product category (can be an ID, a slug or a name)
    #if( has_term( array('clothing'), 'product_cat' )){
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    add_action( 'woocommerce_before_shop_loop_item', 'custom_link_for_product_category', 10 );
    #}
}
function custom_link_for_product_category() {
    global $product;

    // HERE BELOW, Define the Link to be replaced
    $link = $product->get_permalink();
	$title = $product->get_the_title();

    echo '<a href="' . $link . '" title="' . $title . '" class="woocommerce-LoopProduct-link">';
}
*/

/* MAILCHIMP NEWSLETTER COUPON CREATEN FOR SUBSCRIPTION*/

add_action('rest_api_init', function () {
    // here we are telling wordpress to use "webhook" namespace.
    // This could be anything, but since it's a custom webhook receiver,
    // it makes sense to call it webhook
    // next is our "newMailChimpSubscriber" endpoint or route name
    register_rest_route('webhook', '/newMailChimpSubscriber/', array(
        'methods' => ['POST','GET'],
        // and this is name of the function that will be called,
        // when our /wp-json/webhook/newMailChimpSubscriber/ endpoint is called
        'callback' => 'createDiscountCouponForNewSubscriber',
    ));
});

// this function creates the coupon, for the newly registered user
function createDiscountCouponForNewSubscriber()
{
 // create coupon   
	$email = $_POST['data']['email'];
	$coupon_code = 'welcome-'.$email; // Code
	$amount = '15'; // Amount
	$discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product

	$coupon = array(
		'post_title' => $coupon_code,
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => 1,
		'post_type' => 'shop_coupon'
	);

	$new_coupon_id = wp_insert_post($coupon);
	//$exclude_products = array('61707', '61688', '61684', '61679', '61399');

	// Add meta
	update_post_meta($new_coupon_id, 'discount_type', $discount_type);
	update_post_meta($new_coupon_id, 'coupon_amount', $amount);
	update_post_meta($new_coupon_id, 'individual_use', 'yes');
	update_post_meta($new_coupon_id, 'product_ids', '');
	update_post_meta($new_coupon_id, 'exclude_product_ids', '61707, 61688, 61684, 61679, 61399');
	update_post_meta($new_coupon_id, 'usage_limit', '1');
	update_post_meta($new_coupon_id, 'expiry_date', '');
	update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
	update_post_meta($new_coupon_id, 'free_shipping', 'no');

	return "ok";
}
/* SET TITLE AND ALT TAG ON PRODUCTLINKS ON CATEGORY PAGES 
add_filter('wp_get_attachment_image_attributes', 'change_attachement_image_attributes', 20, 2);
function change_attachement_image_attributes( $attr, $attachment ) {
// Get post parent
$parent = get_post_field( 'post_parent', $attachment);

// Get post type to check if it's product
$type = get_post_field( 'post_type', $parent);
if( $type != 'product' ){
    return $attr;
}

/// Get title
$title = get_post_field( 'post_title', $parent);

if( $attr['alt'] == ''){
    $attr['alt'] = $title;
}
if( $attr['title'] == ''){
    $attr['title'] = $title;
}

return $attr;
}*
/* 
 * Code-Snippets aus altem Shop
 * 
 * */
/*
 * Warenkorb checken und Käufe mit bestimmten Merkmalen innerhalb einer Kategorie nicht zulassen (CBD Blüten < 10g)  
 * */
add_action( 'woocommerce_check_cart_items', 'check_total' );
function check_total() {
    // Only run in the Cart or Checkout pages
    if( is_cart() || is_checkout() ) {

		global $woocommerce, $product;
		//$weight = $product->get_weight();
		$total_quantity = 0;
		$total_grams = 0;
		$display_notice = 1;
		$i = 0;
		//echo $weight.'<br><br>';
		//loop through all cart products
		foreach ( $woocommerce->cart->cart_contents as $product ) {

			// See if any product is from the cuvees category or not
			if ( has_term( 'cbd-blueten', 'product_cat', $product['product_id'] )) {
				//echo ($i+33);
				$total_quantity += $product['quantity'];
				//echo ;
				//echo $product->get_weight().'<br><br>';
				$total_grams += $product['data']->get_weight() * $product['quantity'];
				
			}
			//echo $total_grams;

		}
		// Set up the acceptable totals and loop through them so we don't have an ugly if statement below.
		$acceptable_totals = array(10);

		foreach($acceptable_totals as $total_check) {
			if ( $total_grams <= $total_check) { $display_notice = 0; } 
		}

		foreach ( $woocommerce->cart->cart_contents as $product ) {
			if ( has_term( 'cbd-blueten', 'product_cat', $product['product_id'] ) ) {
				if( $display_notice == 1 && $i == 0 ) {
					// Display our error message
					wc_add_notice( sprintf( '<p>Aufgrund der derzeitigen Gesetzeslage ist die Menge der bestellbaren CBD-Blüten pro Bestellung auf 10g begrenzt.</p>', $total_grams),
								  'error' );
				}
				$i++;
			}
		}
	}
}


/**
* Child theme stylesheet einbinden in Abhängigkeit vom Original-Stylesheet
*/
function customAdmin() {
    $url = get_settings('siteurl');
    $url = $url . '/wp-content/themes/CBD Shop/style-admin.css';
    echo '<!-- custom admin css -->
          <link rel="stylesheet" type="text/css" href="' . $url . '" />
          <!-- /end custom adming css -->';
}
add_action('admin_head', 'customAdmin');

/*
 * Remove unnecessery <h1> Tags
 */
add_filter( 'astra_advanced_header_title', 'remove_page_header_title' );
function remove_page_header_title() {
  return;
}

/*	custom hook for getting order_status 
 * 	used in PDF Invoices template file 'invoice.php'
 * */
function get_order_status_hook($order) {
	do_action('get_order_status_hook');
}
function return_order_status($order) {
	return $order->get_status();
}
add_filter('get_order_status_hook', 'return_order_status');

function get_order_payment_method_hook($order) {
	do_action('get_order_payment_method_hook');
}
function return_order_payment_method($order) {
	return $order->get_payment_method();
}
add_filter('get_order_payment_method_hook', 'return_order_payment_method');

//Insert Adcell Tracking into Footer
add_action('wp_footer', 'adcell_tracking_information');
function adcell_tracking_information() {
	echo '<script type="text/javascript" src="https://t.adcell.com/js/trad.js"></script>
<script>Adcell.Tracking.track();</script>';
}

//Remove Heading of Custom Product Tab
add_filter( 'yikes_woocommerce_custom_repeatable_product_tabs_heading', '__return_false' );
// Checkout Page Customization - $address_fields is passed via the filter!
function custom_override_default_address_fields( $address_fields ) {
     $address_fields['address_1']['label'] = "Straße, Hausnummer";
	 $address_fields['address_1']['placeholder'] = "Straße, Hausnummer";

     return $address_fields;
}
/* Enable upload for webp image files.*/
function webp_upload_mimes($existing_mimes) {
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'webp_upload_mimes');

/* Modify Email Header - Order Notification */
add_action('woocommerce_email_header','add_Address', 10, 2);
function add_Address($email_heading, $email) {
	echo "<table><tr><th>Sanaleo Web UG</th></tr><tr><td>Geschäftsführerin: Stefanie Barth</td></tr><tr><td>Lessingstraße 29</td></tr><tr><td>04109 Leipzig</td></tr><tr><td>Tel.:01638913184</td></tr><tr><td>E-Mail: info@sanaleo-cbd.de</td></tr></table>";
}

/* Change Product Titles from H2 to Span -> SEO 
remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'soChangeProductsTitle', 10 );
function soChangeProductsTitle() {
    echo '<span class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</span>';
}*/

//Add Custom Email to Invoice-Mail
add_filter( 'woocommerce_email_headers', 'woocommerce_completed_order_email_bcc_copy', 10, 3);
function woocommerce_completed_order_email_bcc_copy( $headers, $email, $order ) {
	$formattedEmail = utf8_decode('<support@sanaleo-cbd.de>, <franz.borchardt@yahoo.de>');
    if ($email == 'customer_completed_order') :
        //$headers .= 'Bcc: Your name <balling.julian@web.de>'; //just repeat this line again to insert another email address in BCC
		//$headers .= 'Bcc: Your name <support@sanaleo-cbd.de>'; //just repeat this line again to insert another email address in BCC
		//$headers .= 'Cc: Your name <balling.julian@web.de>'; //just repeat this line again to insert another email address in BCC
		//$headers .= 'Cc: Your name <support@sanaleo-cbd.de>'; //just repeat this line again to insert another email address in BCC
		$headers .= 'Cc: '.$formattedEmail;
		echo $headers;
    endif;
 	
    return $headers;
}


/* Insert Google Analytics Tracking Code and Tracking Event */

function insertGABasic() {
	//$url = get_site_url();
	//echo '<link rel="alternate" hreflang="de" href="'.$url.'"/>';
	?>
	<meta name="facebook-domain-verification" content="y4q2jy4l2dqwabg1w3yhhdyi6h0v1p" />
	<script async>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-157457301-1', 'auto');
	ga('set', 'anonymizeIp', true);
	ga('send', 'pageview');
	ga('require', 'ecommerce');
	<?php 
	global $wp;
		if ( is_checkout() && !empty( $wp->query_vars['order-received'] ) ) {
		// GET the WC_Order object instance from, the Order ID
		$order_id  = absint( $wp->query_vars['order-received'] );
		$order_key = isset( $_GET['key'] ) ? wc_clean( wp_unslash( $_GET['key'] ) ) : '';
		$order = wc_get_order( $order_id );

		$order_key = $order->get_order_key();

		$transaction_id = $order->get_transaction_id(); // Doesn't always exist

		$transaction_id = $order_id;
		
		
		?>
		ga('ecommerce:addTransaction', {
		  'id':  '<?php echo $transaction_id; ?>',                     // Transaction ID. Required.
		  'affiliation': 'Sanaleo CBD',   // Affiliation or store name.
		  'revenue': '<?php echo $order->get_total(); ?>',               // Grand Total.
		  'shipping': '<?php echo $order->get_shipping_total(); ?>',                  // Shipping.
		  'tax': '0.00'                     // Tax.
		});
		<?php
		foreach( $order->get_items() as $item_id => $item ) :
			$order = wc_get_order( $order_id );
			$transaction_id = $order->get_transaction_id(); // Doesn't always exist
			$product = $item->get_product();
			$categories = wp_get_post_terms( $item->get_product_id(), 'product_cat', array( 'fields' => 'names' ) );
    		$category = reset($categories); // Keep only the first product category
			?>
			ga('ecommerce:addItem', {
				'id':     '<?php echo $item->get_id(); ?>',
				'name':       '<?php echo $item->get_name(); ?>',
				'sku':    '<?php echo $product->get_sku(); ?>',
				'category': '<?php echo $category;?>',
				'price':      '<?php echo wc_get_price_including_tax($product);  // OR wc_get_price_including_tax($product) ?>',
				'quantity': '<?php echo $item->get_quantity(); ?>',
				'currency': '<?php echo get_woocommerce_currency(); // Optional ?>' 
			});
			<?php
		endforeach;
		?>
		
		ga('ecommerce:send');
		<?php
		}
		
		?>
	
	</script>
	<!-- Global site tag (gtag.js) - Google Ads: 666334632 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-666334632"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-666334632');
</script>
	<?php
}
add_action('wp_head', 'insertGABasic');

/* Load FAQ Featured Snippets -> needs to be updated*/


function customjs_load()
{
    if (is_page(65230)) :
   	echo '<script type="application/ld+json" async>
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "Wieso erhalte ich keine Verzehr- bzw. Dosierungsempfehlungen für die CBD Blüten und CBD Tropfen?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Aufgrund der derzeitigen Gesetzeslage und der Einstufung von CBD dürfen wir keine genaue Verzehr- und Dosierungsempfehlung für unsere Produkte abgeben. Der Gesetzgeber ist hier sehr kritisch gegenüber gesundheitsbezogenen Auskünften. Deswegen überlassen wir das den   medizinischen Experten. Falls du gerne nähere Informationen hättest, können wir dir auf Anfrage gerne Ärzte oder Ärztinnen empfehlen, welche   sich gut mit der Wirkung von CBD auskennen."
    }
  },{
    "@type": "Question",
    "name": "Wirkt CBD berauschend?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und ist in Deutschland verboten. Chemisch betrachtet, unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide in ihrer Wirkung. CBD wirkt, im Vergleich zu THC, nicht berauschend. Anders als oft behauptet wird, ist CBD allerdings psychoaktiv. Es kann nämlich sehr wohl Einfluss auf unsere psychische Wahrnehmung haben. Allerdings nehmen wir dies nicht als Rauch war, sondern verspüren wenn dann eine Linderung psychischer Beschwerden, wie zum Beispiel Stress, depressive Verstimmungen oder Ängsten."
    }
	},{
    "@type": "Question",
    "name": "Was ist CBD?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "CBD steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des Endocannabinoid-Systems, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist."
    }
	},{
    "@type": "Question",
    "name": "Ist CBD legal?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Grundsätzlich musst Du dir als CBD-Nutzer*in keine Sorge vor einer rechtlichen Verfolgung nach einem Drogentest machen. CBD ist (im Gegensatz zu THC) kein Rauschmittel. Darum fällt CBD nicht unter das Betäubungsmittelgesetz. Der THC-Gehalt in sämtlichen CBD-Produkten muss in Deutschland unter 0,2% liegen."
    }
	},{
    "@type": "Question",
    "name": "Was ist der Unterschied zwischen CBD und THC?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend."
    }
	}
  ]
}
</script>';
	endif;
}

add_action('wp_head', 'customjs_load', 2);

function schemaMarkupOrganization() {
	if (is_page(65230)) :
	echo '<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Sanaleo CBD Shop",
  "alternateName": "Sanaleo CBD Store",
  "url": "https://sanaleo.com",
  "logo": "https://sanaleo.com/wp-content/uploads/2020/06/Sanaleo-Verkauf-von-CBD-Produkten-CBD-Öl-und-CBD-Blüten-200x200_trans.png",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Leipzig, Germany",
    "postalCode": "04109",
    "streetAddress": "Lessingstraße 29"
  },
  "email": "info@sanaleo-cbd.de",
  "telephone": "+491638913184",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "01638913184",
    "email": "info@sanaleo-cbd.de",
    "contactType": "customer service",
    "contactOption": "TollFree",
    "areaServed": "DE",
    "availableLanguage": "German"
  },
  "sameAs": [
    "https://www.facebook.com/SanaleoCBD",
    "https://twitter.com/sanaleo_cbd",
    "https://www.instagram.com/sanaleo_de/",
    "https://www.pinterest.de/SanaleoCBD/"
  ],
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.81",
    "reviewCount": "623"
  },
  "brand": "Sanaleo",
  "department": [
      {
          "@type": "LocalBusiness",
          "name": "Sanaleo CBD Store Halle",
          "telephone": "+491773967694",
          "openingHours": [
              "Mo-Fr 13:00-19:00"      
              ],
          "image": "https://sanaleo.com/wp-content/uploads/2021/08/CBD-Markt-Franchise-CBD-Shop-Sanaleo.jpg",
          "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "5",
                "reviewCount": "26"
            },
          "priceRange": "$",
          "address": {
              "@type": "PostalAddress",
              "addressLocality": "Halle (Saale), Germany",
              "postalCode": "06108",
              "streetAddress": "Ludwig-Wucherer-Straße 33"           
          },
          "logo": "https://sanaleo.com/wp-content/uploads/2020/06/Sanaleo-Verkauf-von-CBD-Produkten-CBD-Öl-und-CBD-Blüten-200x200_trans.png",
          "url": "https://sanaleo.com",
          "geo": {
              "@type": "GeoCoordinates",
              "latitude": "51.494205720080366",
              "longitude": "11.971570170743293"
          }
          
      },
      {
          "@type": "LocalBusiness",
          "name": "Sanaleo CBD Store Dresden",
          "telephone": "+4917673776705",
          "openingHours": [
              "Mo-Fr 12:00-19:00",
              "Sa 13:00-16:00"      
              ],
          "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.8",
                "reviewCount": "43"
            },
          "priceRange": "$",
          "image": "https://sanaleo.com/wp-content/uploads/2020/07/Franchise-CBD-Shop-Sanaleo-Shop-Dresden.jpg",
          "address": {
              "@type": "PostalAddress",
              "addressLocality": "Dresden, Germany",
              "postalCode": "01099",
              "streetAddress": "Rothenburger Str. 13"           
          },
          "url": "https://sanaleo.com",
          "logo": "https://sanaleo.com/wp-content/uploads/2020/06/Sanaleo-Verkauf-von-CBD-Produkten-CBD-Öl-und-CBD-Blüten-200x200_trans.png",
          "geo": {
              "@type": "GeoCoordinates",
              "latitude": "51.06476038393256",
              "longitude": "13.752094180965518"
          }
          
      }
  ]
}
</script>'; endif;
}
add_action('wp_head', 'schemaMarkupOrganization', 2);


function customjs_load_blueten() {
	if (is_product_category() and is_product_category("CBD Aromablüten")) :
	echo '<script type="application/ld+json" async>
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "Wie werden CBD Blüten angebaut?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Unser Sortiment umfasst Outdoor, Indoor und Greenhouse CBD-Blüten aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unsere Hersteller haben allesamt eine langjährige Expertise beim Anbau von Cannabis vorzuweisen. Das ermöglicht es uns, jederzeit einen exklusiven Anspruch hinsichtlich unserer CBD-Produkte zu gewährleisten. Freu’ Dich auf beste Qualität."
    }
  },{
    "@type": "Question",
    "name": "Wieso haben CBD-Blüten unterschiedlich hohe CBD-Gehalte?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Verschiedene Produkte derselben Kategorie weisen unterschiedliche CBD-Gehalte auf. Vornehmlich bei CBD-Blüten finden wir geringe und vergleichsweise sehr hohe CBD-Anteile. Auch einzelne Chargen derselben Sorte variieren in den Cannabinoidwerten. Doch weshalb ist das so?

1. Das Züchten von Hanf ist eine wirkliche Aufgabe. Die Aufgabe wird genau dann zur Kunst, wenn dieselbe Sorte stabil über Jahre gezüchtet und angebaut werden soll.
2. Das Verhältnis von THC zu CBD und von CBD zu THC ist nicht beliebig. Demnach spielt der gesetzlich vorgeschriebene THC-Gehalt des Vertriebslandes eine entscheidende Rolle für den CBD-Gehalt. Die unterschiedliche Gesetzeslage innerhalb der EU sorgt also dafür, dass in manchen Ländern (Österreich, Luxemburg) der auf natürlichem Wege erzielbare CBD-Gehalt bei max. 9% liegt, in den meisten Ländern der EU bei max. 6% CBD.
3. Gute Werte für das Verhältnis THC zu CBD sind bei natürlichem Anbau von EU-Nutzhanf derzeit 1:20 bis 1:30. Das bedeutet, dass maximal der dreißigfache Anteil von CBD zum zugelassenen THC-Grenzwert erzielt werden kann. In Deutschland wären das bei einem Grenzwert von < 0,2% THC ideal gerechnet max. 6% CBD."
    }
  },{
    "@type": "Question",
    "name": "Was ist CBD?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "CBD steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des Endocannabinoid-Systems, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Die wissenschaftliche These ist: Exogen zugeführte Cannabinoide stimulieren das System, das einen Ausgleich der ausgeschütteten Botenstoffe anstrebt. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt."
    }
  },{
    "@type": "Question",
    "name": "Wirkt CBD berauschend?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und ist in Deutschland verboten. Chemisch betrachtet, unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide in ihrer Wirkung. CBD wirkt, im Vergleich zu THC, nicht berauschend. Anders als oft behauptet wird, ist CBD allerdings psychoaktiv. Es kann nämlich sehr wohl Einfluss auf unsere psychische Wahrnehmung haben. Allerdings nehmen wir dies nicht als Rauch war, sondern verspüren wenn dann eine Linderung psychischer Beschwerden, wie zum Beispiel Stress, depressive Verstimmungen oder Ängsten."
    }
	},{
    "@type": "Question",
    "name": "Was ist der Unterschied zwischen CBD und THC?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend."
    }},{
    "@type": "Question",
    "name": "Wie lagert man die CBD-Hanfblüten sicher und richtig?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Am wohlsten fühlen sich die CBD-Blüten, wenn sie trocken und luftdicht verpackt sind. So trocknen sie nicht aus und bewahren bestens ihr Aroma."
    }},{
    "@type": "Question",
    "name": "Können die CBD-Aromablüten schlecht werden?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Sollten die CBD-Blüten feucht werden, ist eine Schimmelbildung nicht auszuschließen. Deshalb immer darauf achten, dass sie trocken und luftdicht gelagert sind. Im Laufe der Zeit ist ein Austrocknen der Blüten leider kaum zu verhindert. Aber dadurch verlieren sie gewiss nicht an Qualität."
    }}
  ]
}
</script>';
	endif;
}
add_action('wp_head', 'customjs_load_blueten', 2);

function customjs_load_oele() {
	if (is_product_category() and is_product_category("CBD Öle")):
	echo '<script type="application/ld+json" async>
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "Wie werden Sanaleo CBD Öle hergestellt?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Die angebotenen CBD-Öle werden aus getrockneten CBD-Cannabisblüten mit einem natürlicherweise hohen CBD-Gehalt hergestellt. Die dafür verwendeten Cannabisblüten werden ohne jeglichen Einsatz von Herbiziden oder Pestiziden angebaut. Nach der Ernte und dem Trocknungsprozess werden die Cannabisblüten mithilfe eines einzigartigen Verfahren extrahiert, bei dem eine sehr hohe Ausbeute erreicht wird. Nach der Extraktion ist zudem kein Einsatz von Lösungsmitteln erforderlich. Zuletzt wird das Cannabisextrakt mit einer natürlichen Ölbasis vermengt."
    }
  },{
    "@type": "Question",
    "name": "Welche CBD Öle sind bei uns erhältlich?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Wie du bestimmt bereits gesehen hast, kannst du bei SANALEO verschiedene CBD-Öle kaufen:

1. Full Spectrum CBD Öle mit 5% | 15% | 25% CBD
2. Broad Spectrum CBD Öle mit 5% | 15% | 25% CBD
3. Sanaleo Unique Collection (Happy Drops und Ease Drops) mit 5% | 15% | 25% CBD
Unsere Full Spectrum-Öle und Broad Spectrum-Öle unterscheiden sich zum einen in der Zusammensetzung der einzelnen natürlichen Inhaltsstoffe und zum anderen in ihrer Öl-Basis. Full Spectrum-Öle enthalten alle natürlichen Inhaltsstoffe des für die Herstellung verwendeten Pflanzenmaterials, während in Broad Spectrum-Ölen bestimmte Inhaltsstoffe herausgefiltert wurden. Zusätzlich bieten wir verschiedene Full Spectrum-Öle an, die von unserer Aroma-Öl-Expertin mit weiteren natürlichen Pflanzenextrakten ergänzt werden. Diese Spezial-Öl-Mischungen werden mit ätherischen Ölen angereichert. Ätherische Öle enthalten Terpene, die den Entourage-Effekt verstärken können."
    }
  },{
    "@type": "Question",
    "name": "Was ist CBD?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "CBD steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des Endocannabinoid-Systems, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Die wissenschaftliche These ist: Exogen zugeführte Cannabinoide stimulieren das System, das einen Ausgleich der ausgeschütteten Botenstoffe anstrebt. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt."
    }
  },{
    "@type": "Question",
    "name": "Wirkt CBD berauschend?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und ist in Deutschland verboten. Chemisch betrachtet, unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide in ihrer Wirkung. CBD wirkt, im Vergleich zu THC, nicht berauschend. Anders als oft behauptet wird, ist CBD allerdings psychoaktiv. Es kann nämlich sehr wohl Einfluss auf unsere psychische Wahrnehmung haben. Allerdings nehmen wir dies nicht als Rauch war, sondern verspüren wenn dann eine Linderung psychischer Beschwerden, wie zum Beispiel Stress, depressive Verstimmungen oder Ängsten."
    }
	},{
    "@type": "Question",
    "name": "Was ist der Unterschied zwischen CBD und THC?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend."
    }}
  ]
}
</script>';
	endif;
}
add_action('wp_head', 'customjs_load_oele', 2);



/**
 * Allow HTML in term (category, tag) descriptions
 */
foreach ( array( 'woocommerce_archive_description' ) as $filter ) { 
    remove_filter( $filter, 'wp_filter_kses' ); 
} 
foreach ( array( 'woocommerce_archive_description' ) as $filter ) { 
    remove_filter( $filter, 'wp_kses_data' ); 
} 
foreach ( array( 'custom_archive_description' ) as $filter ) { 
    remove_filter( $filter, 'wp_filter_kses' ); 
} 
foreach ( array( 'custom_archive_description' ) as $filter ) { 
    remove_filter( $filter, 'wp_kses_data' ); 
} 

foreach ( array( 'pre_term_description' ) as $filter ) { 
    remove_filter( $filter, 'wp_filter_kses' ); 
} 
foreach ( array( 'term_description' ) as $filter ) { 
    remove_filter( $filter, 'wp_kses_data' ); 
}


/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );
add_filter( 'rank_math/snippet/rich_snippet_product_entity', function( $entity ) {
    $entity['brand'] = 'Sanaleo CBD' ;
    return $entity;
});

add_filter( 'script_loader_tag', 'defer_scripts_myhostingfacts', 10, 3 );
function defer_scripts_myhostingfacts( $tag, $handle, $src ) {
	// The handles of the enqueued scripts we want to defer
	$defer_scripts = array('roleWcAdcellTrackingAllPages', 'roleWcAdcellTrackingRetargetingScript', 'gtm4wp-contact-form-7-tracker', 'gtm4wp-form-move-tracker', 'gtm4wp-woocommerce-classic', 'gtm4wp-woocommerce-enhanced');
    if ( in_array( $handle, $defer_scripts ) ) {
        return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
    }
    
    return $tag;
}

/* 
 * Code-Snippets aus altem Shop - Ende
 * 
 * */


//remove "Zusätzliche Informationen" Product Detail Page -> will be replaced by selfmade.
add_filter('woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 99);
function bbloomer_remove_product_tabs( $tabs ) {
	unset( $tabs['additional_information'] );
	return $tabs;
}


function implement_row_beginning(){
    echo '<div class="ast-row">';
}


/**
 * Exclude products from a particular category on the shop page

function custom_pre_get_posts_query( $q ) {

  $tax_query = (array) $q->get( 'tax_query' );

  $tax_query[] = array(
         'taxonomy' => 'product_cat',
         'field' => 'slug',
         'terms' => array( 'cbd-aromablueten' ), // Don't display products in the clothing category on the shop page.
         'operator' => 'NOT IN'
  );


  $q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );  
*/


// TAKES UNLIMITED CLASSNAMES AND CREATES AN OPENING DIV TAG

function implement_div_classes(...$classes) {

    echo '<div class="';
    
    for($i=0; $i < count($classes); $i++){
    
        echo $cols[$i];
        
        if($i < count($classes)- 1){
            echo ' ';
        }
    
    }
    
    echo '"><br>';
    
}

function close_div($div_num){
    for($i=0; $i < $div_num + 1; $i++){
        echo '</div>';
    }
    
}

function customise_product_page() {

    /** 
     * PRODUCT CATEGORY IDS
     * 
     * ÖLE: 31
     * AROMABLÜTEN: 30
     * VAPE: 140
     * LEBENSMITTEL: 157
     * SCHLAFKAPSELN: 161
     * 
     * 
    */

    if(is_product){

        if(has_term('31' , 'product_cat')){
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
            add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_excerpt', 30);
			
        }
        else if(has_term('30', 'product_cat')){
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
            add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 1);
        }
        else if(has_term('173', 'product_cat')){
          remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
          add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_excerpt', 30);
        }
    
    }
    
}

add_action( 'woocommerce_before_single_product', 'customise_product_page' );



//


// if(is_product && has_term('30', 'product_cat')){
//     add_action('before_single_product', 'blueten_hook');
//     $klassenarbeit = apply_filters('blueten_hook', 'testklasse');
//     echo $klassenarbeit;
// }


// function blueten_hook(...$classes){
// do_action('blueten_hook');
// }


// function implement_div_classes(...$classes) {

//     $div = '<div class="'; 
    
    
//         for($i=0; $i < count($classes); $i++){
//             $div += " " . $classes[$i];
//         }
    
//     $div += '"><br>';
    
//     return $div;
    
//     }
// remove_action( 'wp_head', 'print_google_fonts', 120 );

/* Remove inline <style> blocks. */
// function start_html_buffer() {
//     // buffer output html
//     ob_start();
// }
// function end_html_buffer() {
//     // get buffered HTML
//     $wpHTML = ob_get_clean();

//     // remove <style> blocks using regular expression
//     $wpHTML = preg_replace("/<style[^>]*>[^<]*<\/style>/m",'', $wpHTML);

//     echo $wpHTML;
// }
// add_action('template_redirect', 'start_html_buffer', 0); // wp hook just before the template is loaded
// add_action('wp_footer', 'end_html_buffer', PHP_INT_MAX); // wp hook after wp_footer()




// add_action( 'enqueue_block_editor_assets', function() {
// 	// Overwrite Core theme styles with empty styles.
// 	wp_deregister_style( 'wp-core-blocks-theme' );
// 	wp_register_style( 'wp-core-blocks-theme', '' );
// }, 10 );

// function dequeue_all_styles() {
//     global $wp_styles;
//     foreach( $wp_styles->queue as $style ) {
//         wp_dequeue_style($wp_styles->registered[$style]->handle);
//     }
// }



// add_action('wp_print_styles', 'dequeue_all_styles', PHP_INT_MAX - 2);


// function enqueue_pure_styles() {
//     wp_enqueue_style('dangarous-styles', get_stylesheet_directory_uri().'/extractpurgeminify/css/unified.min.css');
// }

// add_action('wp_print_styles', 'enqueue_pure_styles', PHP_INT_MAX -1);



/* dequeue all stylesheets */
/* dequeue guteberg mist*/
/* enqueue pure style */



add_action( 'init', 'wpb_product_menu' );
function wpb_product_menu() {
	register_nav_menu('product-menu',__( 'Product Menu' ));
  }





  add_action( 'astra_main_header_bar_top', 'sanaleo_display_product_menu' );

  function sanaleo_display_product_menu(){
	echo '<div class = "product-dropdown"><img id="lionhead" class="alignnone size-full wp-image-11" src="https://sanaleo.com/wp-content/uploads/2021/04/CBD-Oele-CBD-Blueten-CBD-Vape-Produtke-Sanaleo-CBD-loewenkopf.png" alt="Sanaleo - Premium CBD Shop Logo" width="50px" /><button id="product-menu-btn"><a href="https://sanaleo.com/shop-sortiment/" title="CBD Produkte bestellen">Produkte</a></button>';

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

// ADD ANIMATION FOR BUD LITS

// ROBERT TODOS

// CONTACTFORM OPTI
//deactivate_plugins( '/wp-content/plugins/wp-contact-form-7.php' );
// REMOVE ASTRA FONT DEFAULT
add_filter( 'astra_enable_default_fonts', '__return_false' );

add_filter( 'astra_google_fonts_selected', '__return_false' );

$trollingbertig = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    // echo "$request_uri";

#error_log(print_r($trollingbertig));
function clean_up_style() {
    if(is_front_page()){
        wp_dequeue_style('gutentor');
    }
}

add_action('wp_enqueue_scripts', 'clean_up_style', 999);

// if(strpos( $request_uri, '' );){
// }

// REMOVE OUT OF STOCK PRODUCTS FROM VARIATION DROPDOWN 
// ROBERT TODOS

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'filter_dropdown_option_html', 12, 2 );
function filter_dropdown_option_html( $html, $args ) {
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' );
    $show_option_none_html = '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

    $html = str_replace($show_option_none_html, '', $html);

    return $html;
}


// Removes specific product cats from single product pages
/*
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
*/


add_filter( 'get_terms', 'ts_get_subcategory_terms', 10, 3 );
    function ts_get_subcategory_terms( $terms, $taxonomies, $args ) {
          $new_terms = array();
          // if it is a product category and on the shop page
          if ( in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_shop() ) {
             foreach ( $terms as $key => $term ) {
                 if ( ! in_array( $term->slug, array( 'buds', 'oil', 'food', 'schlafkapseln' ) ) ) {        //pass the slug name here
                    $new_terms[] = $term;
                 }
          }
          $terms = $new_terms;
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
    echo sprintf('<div id="product_total_price" style="font-size: 16px; font-weight: 200;">%s %s</div>',__('Total Price (incl Tax):','woocommerce'),'<span class="price">'. get_woocommerce_currency_symbol() .' ' .$product->get_price().'</span>');
}


/**
 * Disable out of stock variations @ WooCommerce Single
*/

add_filter( 'woocommerce_variation_is_active', 'njengah_grey_out_variations_out_of_stock', 10, 2 );

function njengah_grey_out_variations_out_of_stock( $is_active, $variation ) {

    if ( ! $variation->is_in_stock() ) return false;

    return $is_active;

}

add_filter( 'wpsl_admin_marker_dir', 'custom_admin_marker_dir' );

function custom_admin_marker_dir() {

    $admin_marker_dir = get_stylesheet_directory() . '/wpsl-markers/';
    
    return $admin_marker_dir;
}

define( 'WPSL_MARKER_URI', dirname( get_bloginfo( 'stylesheet_url') ) . '/wpsl-markers/' );

add_filter( 'wpsl_marker_props', 'custom_marker_props' );

function custom_marker_props( $marker_props ) {
            
    $marker_props['scaledSize'] = '48,70'; // Set this to 50% of the original size
    $marker_props['origin'] = '0,0';
    $marker_props['anchor'] = '12,35';
    
    return $marker_props;
}

// Make WoocommerceProductLoopTitle -> Span
/*remove_action( 'woocommerce_after_shop_loop_item','astra_woo_shop_products_title', 10 );
add_action('woocommerce_after_shop_loop_item', 'astra_woo_shop_products_title_', 10 );
function astra_woo_shop_products_title_() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';

		echo '<span class="woocommerce-loop-product__title">' . esc_html( get_the_title() ) . '</span>';

		echo '</a>';
	}
*/
if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
    function woocommerce_template_loop_product_title() {
        echo '<span class="woocommerce-loop-product__title" title="' . get_the_title() . '">' . get_the_title() . '</span>';
    }
}

/** ADDS CUSTOMER REVIEWS TO SPECIFIC PAGES**/
add_action('woocommerce_after_shop_loop', 'customer_reviews', 50);
function customer_reviews() {
	if (is_product_category('cbd-blueten')){
    	echo '
			<div class="ProductCategoryDescription">
			<h2>Hochwertige CBD-Blüten online bestellen:</h2>
				<ul><li><b>Hochwertige CBD Blüten bester Qualität</b> bei Sanaleo kaufen!</li> <li>Ab 50€ versandkostenfreie Lieferung innerhalb Deutschland!</li> <li>Schnelle Lieferung!</li> <li>Bei Bestellungen vor 12 Uhr erfolgt der Versand der Produkte am gleichen Tag!</li></ul>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">Was sind CBD-Blüten?</h3>
				<p>Bei <b>CBD-Blüten</b> handelt es sich um die Knospen der weiblichen Cannabispflanze. Die angebotenen Blüten stammen ausschließlich von EU-zertifizierten Nutzhanfpflanzen mit einem minimalen THC-Gehalt – statt berauschendem THC gibt es eine volle Ladung entspannendes Cannabidiol. Im Vergleich zu anderen Teilen der Pflanze ist der CBD-Gehalt in den Blüten besonders hoch. Nach der Ernte werden die Blüten unter stetiger Kontrolle getrocknet, um die Reifung und das Aroma der jeweiligen Cannabissorte zu perfektionieren.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">WIE WERDEN SANALEO-CBD-BLÜTEN ANGEBAUT?</h3>
				<p>Unser Sortiment umfasst <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Wie_wird_CBD-Hanf_angebaut" title="Die unterschiedlichen Anbaumethoden von Hanf">Outdoor, Indoor und Greenhouse CBD-Blüten</a> aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unsere Hersteller haben allesamt eine langjährige Expertise beim Anbau von Cannabis vorzuweisen. Das ermöglicht es uns, jederzeit einen exklusiven Anspruch hinsichtlich unserer <a href="https://sanaleo.com/" title="CBD Produkte von Sanaleo">CBD-Produkte</a> zu gewährleisten. Freu’ Dich auf beste Qualität.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">WORIN UNTERSCHEIDEN SICH CANNABISBLÜTEN?</h3>
				<p>Im Laufe der Jahrhunderte wurden in unterschiedlichen Regionen der Welt verschiedene Cannabissorten entdeckt.Im Jahr 1753 klassifizierte der schwedische Naturforscher Carl von Linné die Hanfsorte Cannabis Sativa, in der Übersetzung „gewöhnlicher Hanf“ bedeutend. In Indien wurde 32 Jahre später eine weitere Cannabissorte entdeckt und Cannabis Indica („Indischer Hanf“) getauft. 1926 beschrieb der Botaniker Dimitrij E. Janischwesky die Sorte Ruderalis, auch Ruderal-Hanf genannt.Grundsätzlich gehören diese Cannabissorten zur Pflanzengattung Cannabis Sativa L. Diese einzelnen Cannabissorten unterscheiden sich allerdings in ihrem Aussehen, ihrer Wachstums- und Blütezeit, ihrem Geruch, Geschmack und der natürlichen Zusammensetzung verschiedener Inhaltsstoffe. Mit der Zeit wurden verschiedenste Cannabispflanzen miteinander gekreuzt und so entstanden und entstehen immer neue unterschiedliche Cannabispflanzen und -sorten.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">WAS MACHT SANALEO-CBD-BLÜTEN BESONDERS?</h3>
				<p>Aufgrund der enormen Erfahrung unserer Hersteller beim Anbau von Cannabis können wir eine exklusive Bio-Genetik garantieren. Unsere <strong>CBD-Blüten</strong> zeichnen sich durch ihren intensiven Geruch und ihr exotisches Aussehen aus. Einige von unseren Hanf-Blüten, darunter bspw. „Pineapple Express“ sind sogar aus den USA importiert.</p>
			</div>
			<div class="BewertungsHeading"><h2>Das sagen Kunden, die bei Sanaleo CBD Blüten kaufen:</h2></div>
				<div class="BewertungsContainerMain">
					 <div class="BewertungsConstainer">
						  <div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot"><p>“Preis-Leistung stimmt, super schneller Versand, bestelle immer wieder und bin jedes mal begeistert das die Qualität der CBD Blüten so gut ist.”</p></div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Super schnelle Lieferung, super toll verpackt. Mega zufrieden! Und die Blüten ebenfalls top! Weiter zu empfehlen!!"</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">Die Blüten haben eine sehr gute Qualität und haben ein schönes Aroma. Preis-Leistungs-Verhältnis ist top. Es ist inzwischem meine 3. Bestellung gewesen und weitere werden folgen.”
				</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Die CBD Blüten waren wunderschön in Gläsern verpackt und wurden schnell geliefert. Der Geschmack ist super.”</div></div>
				</div>
			'
			
			
			;
	}
	if (is_product_category('cbd-oele')) {
		echo '
			<div class="ProductCategoryDescription">
			<h2>Hochwertiges CBD-Öl online bestellen:</h2>
				<ul><li><b>Premium CBD Öle bester Qualität</b> bei Sanaleo kaufen!</li> <li>Ab 50€ versandkostenfrei!</li> <li>Schnelle Lieferung!</li> <li>Bei Bestellungen vor 12 Uhr erfolgt der Versand der Produkte am gleichen Tag!</li></ul>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">WIE WERDEN SANALEO-PREMIUM-CBD-ÖLE HERGESTELLT?</h3>
				<p>Die angebotenen CBD-Öle werden aus getrockneten <a href="https://sanaleo.com/cbd-blueten/" title="CBD Blüten von Sanaleo">CBD-Cannabisblüten</a> mit einem <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Wieso_haben_EU-Nutzhanfsorten_unterschiedlich_hohe_CBD-Gehalte" title="CBD Gehalt von Cannabisblüten">natürlicherweise hohen CBD-Gehalt hergestellt.</a> Die dafür verwendeten Cannabisblüten werden ohne jeglichen Einsatz von Herbiziden oder Pestiziden angebaut. Nach der Ernte und dem Trocknungsprozess werden die Cannabisblüten mithilfe eines einzigartigen Verfahren extrahiert, bei dem eine sehr hohe Ausbeute erreicht wird. Nach der Extraktion ist zudem kein Einsatz von Lösungsmitteln erforderlich. Zuletzt wird das Cannabisextrakt mit einer natürlichen Ölbasis vermengt.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">WELCHE CBD-ÖLE SIND BEI UNS ERHÄLTLICH?</h3>
				<p>Wie du bestimmt bereits gesehen hast, kannst du bei SANALEO verschiedene CBD-Öle kaufen:</p>
        <ul>
          <li><a href="https://sanaleo.com/cbd-oele/full-spectrum-oele/" title="Full Spectrum CBD Öl">Full Spectrum CBD Öle</a> mit 5% | 15% | 25% CBD</li>
          <li><a href="https://sanaleo.com/cbd-oele/broad-spectrum/" title="Broad Spectrum CBD Öl">Broad Spectrum CBD Öle</a> mit 5% | 15% | 25% CBD</li>
          <li>Sanaleo Unique Collection (<a href="https://sanaleo.com/cbd-oele/happy-drops/" title="CBD Öl mit Geschmack">Happy Drops</a> und <a href="https://sanaleo.com/cbd-oele/ease-drops/" title="CBD Öl mit Aroma">Ease Drops</a>) mit 5% | 15% | 25% CBD</li>
        </ul>
        <p>Unsere Full Spectrum-Öle und Broad Spectrum-Öle unterscheiden sich zum einen in der Zusammensetzung der einzelnen natürlichen Inhaltsstoffe und zum anderen in ihrer Öl-Basis. Full Spectrum-Öle enthalten alle natürlichen Inhaltsstoffe des für die Herstellung verwendeten Pflanzenmaterials, während in Broad Spectrum-Ölen bestimmte Inhaltsstoffe herausgefiltert wurden. Zusätzlich bieten wir verschiedene Full Spectrum-Öle an, die von unserer Aroma-Öl-Expertin mit weiteren natürlichen Pflanzenextrakten ergänzt werden. Diese Spezial-Öl-Mischungen werden mit ätherischen Ölen angereichert. Ätherische Öle enthalten Terpene, die den <a href="https://sanaleo.com/der-entourage-effekt/" title="Der Entourage-Effekt">Entourage-Effekt</a> verstärken können.</p>
			</div>
			<div class="BewertungsHeading"><h2>Das sagen Kunden, die bei Sanaleo CBD Öl kaufen:</h2></div>
				<div class="BewertungsContainerMain">
					 <div class="BewertungsConstainer">
						  <div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot"><p>“Habe das erste Mal bei Sanaleo bestellt. Bestellung und Lieferung einfach und schnell. Verpackung top mit persönlicher Namenskarte. Habe nach einem CBD Öl für meinen Hund gesucht und bin hier fündig geworden. Verträgt das Öl sehr gut. Wirkung muss sich erst noch einstellen. Bestelle bestimmt nochmal.”</p></div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Ich bin Neukunde und werde sicherlich Bestandskunde. Sehr schnelle Lieferung. Verpackung sehr ansehnlich und das Produkt (CBD Öl) war sicher verpackt.” Die Qualität des Produkts ist sehr gut, für das Preis-Leistungs-Verhältnis ist sehr, sehr gut.<br> Absolut zu Empfehlen, würde gerne mehr Sterne geben."</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">Ich war schon längere Zeit auf der Suche nach einem hochwertigen CBD Öl, das nicht nach Hanf schmeckt. Bisher konnte ich nur auf Kapseln ausweichen, die zwar auch wirken, aber eben zeitverzögert und nicht so intensiv. Jetzt habe ich mit den Ease Drops ein Öl gefunden, das ich geschmacklich toleriere (es schmeckt ein bisschen wie Pesto) und das mir dann hilft, wenn ich es brauche. Vielen Dank.”
				</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Ich habe das 15% CBD Öl gleich ausprobiert und merkte bereits am 3 Tag eine deutliche Verbesserung meiner Entzündung. Ich sag danke und empfehle es gerne weiter!”</div></div>
				</div>
		';
	}
	if (is_product_category('cbd-vape')) {
		echo '
		<div class="ProductCategoryDescription">
				<ul><li><b>Hochwertige CBD Vape Produkte</b> bester Qualität bei Sanaleo kaufen!</li> <li>Ab 50€ versandkostenfreie Lieferung innerhalb Deutschland!</li> <li>Schnelle Lieferung!</li> <li>Bei Bestellungen vor 12 Uhr erfolgt der Versand der Produkte am gleichen Tag!</li></ul>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">WIE NEHME ICH MEIN SANALEO-VAPE-STARTERKIT IN BETRIEB?</h3>
				<p>Lade den Pen vor der ersten Nutzung sicherheitshalber noch einmal auf. Wenn er vollständig geladen ist, leuchtet das grüne Licht durchgängig. Verbinde anschließend deine <a href="https://sanaleo.com/cbd-vape/pineapple-express-kartusche/" title="CBD Kartuschen von Sanaleo kaufen">SANALEO-Vape CBD-Kartusche</a> mit dem <a href="https://sanaleo.com/cbd-vape/cbd-pen/" title="CBD Vape Pen kaufen">SANALEO-Vape-Pen</a> und ziehe daran. Normalerweise sollte es direkt zu einer Dampfbildung kommen, sobald du an dem Pen ziehst. 

      Wichtig ist, dass Du gleichmäßige und nicht zu starke Züge nimmst. Andernfalls könnten die hohen Temperaturen dafür sorgen, dass wichtige Terpene zerstört werden, die im Sinne des <a href="https://sanaleo.com/der-entourage-effekt/" title="Der Entourage Effekt">Entourage-Effekts</a> unbedingt erhalten werden sollten.
      
      Ob die Kartusche permanent auf dem Pen bleibt oder nicht, ist Dir überlassen. Er versetzt sich automatisch in den Standby-Modus, solange länger nicht daran gezogen wurde. Der Pen muss nicht zwangsläufig nach einer bestimmten Zeit entsorgt oder ausgetauscht werden.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">WANN MUSS ICH MEINEN VAPE-PEN LADEN? WANN IST MEIN VAPE-PEN GELADEN?</h3>
				<p>Wenn der <a href="https://sanaleo.com/cbd-vape/cbd-pen/" title="CBD Pen kaufen">CBD-Pen</a> geladen werden muss, blinkt das grüne Licht in regelmäßigen Abständen. Wenn er geladen ist, leuchtet das Licht durchgängig.</p>
			</div>
		<div class="BewertungsHeading"><h2>Das sagen Kunden, die bei Sanaleo CBD-Vape-Produkte kaufen:</h2></div>
				<div class="BewertungsContainerMain">
					 <div class="BewertungsConstainer">
						  <div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot"><p>“Besser kann man es nicht machen. Nicht zu viel versprochen und schnelle Lieferung. Gerne wieder!”</p></div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Ihr seid einfach die Besten. Egal ob Online oder im Shop, ihr seid lieb, schnell vertrauenswürdig und respektvoll. Danke das es eure Shops gibt."</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">Super Service, super Produkte, schnelle Lieferung! Vielen Dank!”
				</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Tolle Sachen, super verpackt! Was soll man sagen es wird mit Liebe gemacht und das sieht man einfach.”</div></div>
				</div>';
	}
	if (is_product_category('cbd-kapseln')) {
		echo '
		<div class="ProductCategoryDescription">
				
				<ul><li><b>Hochwertige CBD Kapseln</b> bester Qualität bei Sanaleo kaufen!</li> <li>Ab 50€ versandkostenfreie Lieferung innerhalb Deutschland!</li> <li>Schnelle Lieferung!</li> <li>Bei Bestellungen vor 12 Uhr erfolgt der Versand der Produkte am gleichen Tag!</li></ul>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">KANN MAN DIE CBD-TRAUMKAPSELN ÜBERDOSIEREN?</h3>
				<p>Eine Überdosierung durch unsere Traumkapseln ist ausgeschlossen, da sie keine berauschende Wirkung besitzen.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">IST IN DEN TRAUMKAPSELN WIRKLICH MELATONIN DRIN?</h3>
				<p>Unsere <a href="https://sanaleo.com/cbd-kapseln/sanaleo-cbd-traumkapseln/" title="CBD Schlafkapseln von Sanaleo">CBD Schlafkapseln</a> bestehen aus Steinpilzpulver, Melissenextrakt, Cannabidiol, Traubenzucker und ja, einer kleinen Prise Melatonin. Die Menge an Melatonin ist jedoch gerade so gering, dass sie gänzlich unbedenklich ist, aber dennoch ihre Wirkung entfaltet.</p>
			</div>
		';
	}
	if (is_product_category('mary-jane-berlin-2021')) {
		echo '
		<div class="ProductCategoryDescription">
				<h2 class="BewertungsHeading">ALLES ZUM THEMA HANF IN DREI HALLEN:</h2>
				<p>Mitten in Berlin in der Arena Berlin direkt an der Spree erwartet Euch an 3 Tagen (22. bis 24. Oktober 2021) Deutschlands größte Hanfmesse. Über 270 nationale & internationale Aussteller aus den unterschiedlichsten Bereichen präsentieren Dir die vielfältigen Anwendungsgebiete der grünen Power-Pflanze!<br>

Neben der Fachmesse beinhaltet das Rahmenprogramm Konferenzen, Fachvorträge und Diskussionen nationaler sowie internationaler Hanf-Experten. Sie sprechen über Themen wie Cannabis Legalisierung / Rechtslage, medizinische Verwendung oder Ernährung.<br>

Im Außenbereich findet das Mary Jane Berlin Festival mit Live Acts, DJs und unzähligen Food-Ständen aus aller Welt statt.<br>

Ein Besuch der Mary Jane Berlin bietet Dir die Möglichkeit die neuesten Entwicklungen und Produkte rund um die grüne Power-Pflanze zu erleben. Sprich mit Ausstellern persönlich und sicher Dir spezielle Messe Angebote – es wird sich definitiv lohnen!</p>
			<div class="ProductCategoryDescription">
				<h3 class="BewertungsHeading">AUF DER HANFMESSE ERHÄLST DU INFORMATIONEN ZU:</h3>
				<ul><li>CBD Produkten wie <a href="https://sanaleo.com/cbd-oele/" title="Informationen zu CBD Öl">CBD Öl</a>, <a href="https://sanaleo.com/cbd-kapseln/" title="Informationen zu CBD Kapseln">CBD Kapseln</a>, <a href="https://sanaleo.com/cbd-blueten/" title="Informationen zu CBD Blüten">CBD Blüten</a>, CBD Kosmetik, CBD Foods usw.</li>
<li class="li_without_padding">Cannabis als Medizin</li>
<li class="li_without_padding">Growbedarf</li>
<li class="li_without_padding">Urban Gardening</li>
<li class="li_without_padding">Hanföl</li>
<li class="li_without_padding">Hanfkosmetik</li>
<li class="li_without_padding">Hanf Superfood</li>
<li class="li_without_padding">Hanfbaustoff</li>
<li class="li_without_padding">Hanfkleidung</li>
<li class="li_without_padding">Paraphernalia</li>
<li class="li_without_padding">Raucherzubehör</li>
<li class="li_without_padding"><a href="https://sanaleo.com/cbd-vape/" title="Erhalte Informationen zu CBD Vape">Vaporizer</a> Und vieles mehr…</li></ul>
			</div>
		';
	}
  
}




/**ADD ACCORDIONS to PRODUCT ARCHIVE*/
add_action('woocommerce_after_shop_loop', 'sana_faq', 50);

function sana_faq(){

  if(is_product_category('cbd-blueten')){
    echo '
      <!--<button class="faq-accordion-archive"><h2 class="accordion_heading">WIE WERDEN SANALEO-CBD-BLÜTEN ANGEBAUT?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Unser Sortiment umfasst <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Wie_wird_CBD-Hanf_angebaut" title="Die unterschiedlichen Anbaumethoden von Hanf">Outdoor, Indoor und Greenhouse CBD-Blüten</a> aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unsere Hersteller haben allesamt eine langjährige Expertise beim Anbau von Cannabis vorzuweisen. Das ermöglicht es uns, jederzeit einen exklusiven Anspruch hinsichtlich unserer <a href="https://sanaleo.com/" title="CBD Produkte von Sanaleo">CBD-Produkte</a> zu gewährleisten. Freu’ Dich auf beste Qualität.</p>
      </div>-->

      <!--<button class="faq-accordion-archive"><h2 class="accordion_heading">WELCHE CBD AROMABLÜTEN SIND BEI UNS ERHÄLTLICH?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p><ul><li>Outdoor: Black Domina | Butters Bud | Skywalker OG</li>
			<li>Indoor: Amnesia | | Gorilla Glue | | Jack Herer | Orange Bud | Pineapple Express | Power Plant | Strawberry Haze | Super Lemon Haze</li> 
<li>Green House: Hawaiian Skunk | Mango Kush</li>
<li>CBD-Hash: Litani Hash | Caramello Hash</li>

Die Analysezertifikate zu jeder Sorte können auf der entsprechenden Produktinformationsseite eingesehen werden. Sie geben Aufschluss über den Gehalt der einzelnen Cannabinoide.
</p>
      </div>-->

      <button class="faq-accordion-archive"><h2 class="accordion_heading">WIESO HABEN EU-NUTZHANFSORTEN UNTERSCHIEDLICH HOHE CBD-GEHALTE?</h2><i class="faq-archive-info-open"></i></button>
        <div class="panel-archive">
          <p>Verschiedene Produkte derselben Kategorie weisen unterschiedliche CBD-Gehalte auf. Vornehmlich bei CBD-Blüten finden wir geringe und vergleichsweise sehr hohe CBD-Anteile. Auch einzelne Chargen derselben Sorte variieren in den Cannabinoidwerten. Doch weshalb ist das so?</p>
 
<ol><li>Das Züchten von Hanf ist eine wirkliche Aufgabe. Die Aufgabe wird genau dann zur Kunst, wenn dieselbe Sorte stabil über Jahre gezüchtet und angebaut werden soll.</li> 
 
<li>Das Verhältnis von THC zu CBD und von CBD zu THC ist nicht beliebig. Demnach spielt der gesetzlich vorgeschriebene THC-Gehalt des Vertriebslandes eine entscheidende Rolle für den CBD-Gehalt. Die unterschiedliche Gesetzeslage innerhalb der EU sorgt also dafür, dass in manchen Ländern (Österreich, Luxemburg) der auf natürlichem Wege erzielbare CBD-Gehalt bei max. 9% liegt, in den meisten Ländern der EU bei max. 6% CBD.</li>
 
<li>Gute Werte für das Verhältnis THC zu CBD sind bei natürlichem Anbau von EU-Nutzhanf derzeit 1:20 bis 1:30. Das bedeutet, dass maximal der dreißigfache Anteil von CBD zum zugelassenen THC-Grenzwert erzielt werden kann. In Deutschland wären das bei einem Grenzwert von < 0,2% THC ideal gerechnet max. 6% CBD.</li>
 
<strong>Weshalb gibt es dennoch Anbieter, die Sorten mit 18% CBD bewerben?</strong>
 
<p>In nahezu allen EU-Ländern gibt es aktuell keine einheitlichen Rahmenbedingungen zur Sicherung von Qualitätsstandards. Mit hoher Wahrscheinlichkeit werden die Blüten mit CBD-Isolaten behandelt, die allerdings eine sehr geringe Bioverfügbarkeit aufweisen. Natürlich gewachsene, unbehandelte Cannabinoide besitzen die höchste Bioverfügbarkeit.
</p>
        </div>
	<!--<button class="faq-accordion-archive"><h2 class="accordion_heading">WORIN UNTERSCHEIDEN SICH CANNABISBLÜTEN?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Im Laufe der Jahrhunderte wurden in unterschiedlichen Regionen der Welt verschiedene Cannabissorten entdeckt.Im Jahr 1753 klassifizierte der schwedische Naturforscher Carl von Linné die Hanfsorte Cannabis Sativa, in der Übersetzung „gewöhnlicher Hanf“ bedeutend. In Indien wurde 32 Jahre später eine weitere Cannabissorte entdeckt und Cannabis Indica („Indischer Hanf“) getauft. 1926 beschrieb der Botaniker Dimitrij E. Janischwesky die Sorte Ruderalis, auch Ruderal-Hanf genannt.Grundsätzlich gehören diese Cannabissorten zur Pflanzengattung Cannabis Sativa L. Diese einzelnen Cannabissorten unterscheiden sich allerdings in ihrem Aussehen, ihrer Wachstums- und Blütezeit, ihrem Geruch, Geschmack und der natürlichen Zusammensetzung verschiedener Inhaltsstoffe. Mit der Zeit wurden verschiedenste Cannabispflanzen miteinander gekreuzt und so entstanden und entstehen immer neue unterschiedliche Cannabispflanzen und -sorten.</p>
      </div>-->
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">WIE UNTERSCHEIDEN SICH SANALEO-CBD-BLÜTEN DER SORTE NACH?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Wie oben bereits beschrieben, unterscheiden sich unsere CBD-Hanfblüten in ihrem Aussehen, Geruch, Geschmack und in ihrer natürlichen Zusammensetzung der verschiedenen Inhaltsstoffe. Außerdem unterscheiden sich unsere Blüten in der Art und Weise des Anbaus. Unsere Sorten werden outdoor, indoor oder in einem Gewächshaus gegrowt. Die entsprechende Anbauweise findest Du in der jeweiligen Produktbeschreibung oder in der unten aufgelisteten Tabelle.</p>
      </div>
	  
	  <!--<button class="faq-accordion-archive"><h2 class="accordion_heading">WAS MACHT SANALEO-CBD-BLÜTEN BESONDERS?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Aufgrund der enormen Erfahrung unserer Hersteller beim Anbau von Cannabis können wir eine exklusive Bio-Genetik garantieren. Unser CBD-Gras zeichnet sich durch  intensiven Geruch und exotisches Aussehen aus. Einige von unseren Hanf-Blüten, darunter bspw. „Pineapple Express“ sind sogar aus den USA importiert.</p>
      </div>-->
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">WIE LAGERT MAN CBD-GRAS SICHER UND RICHTIG?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Am wohlsten fühlt sich CBD-Gras, wenn es trocken und luftdicht verpackt ist. So trocknet es nicht aus und bewahrt bestens sein Aroma.</p>
      </div>
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">KÖNNEN DIE CBD-BLÜTEN SCHLECHT WERDEN?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Sollten die CBD-Blüten feucht werden, ist eine Schimmelbildung nicht auszuschließen. Deshalb immer darauf achten, dass sie trocken und luftdicht gelagert sind. Im Laufe der Zeit ist ein Austrocknen der Blüten leider kaum zu verhindert. Aber dadurch verlieren sie gewiss nicht an Qualität.</p>
      </div>
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST CBD?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p><a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/" title="Was ist CBD?">CBD</a> steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des <a href="https://sanaleo.com/was-ist-cbd-und-wie-wirkt-es-im-menschlichen-koerper/" title="Das Endocannabinoid-System">Endocannabinoid-Systems</a>, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Die wissenschaftliche These ist: Exogen zugeführte Cannabinoide stimulieren das System, das einen Ausgleich der ausgeschütteten Botenstoffe anstrebt. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt.</p>
      </div>
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST DER UNTERSCHIED ZWISCHEN THC UND CBD??</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend. Weitere Informationen zu <a href="https://sanaleo.com/cbd-blueten/" title="CBD Hanfblüten">CBD-Blüten</a> findet ihr beim CBD-Ratgeber.</p>
      </div>
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">CBD ALS PFLANZLICHE ALTERNATIVE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Die Wenigsten von uns sind im Alltag frei von Beschwerden. In Zeiten des Leistungsdrucks und des ständigen “Funktionieren-Müssens” neigen wir dazu, sie leichtfertig zu ignorieren. Schnelle Abhilfe versprechen massenhaft pharmazeutische Produkte, die nicht frei von Nebenwirkungen sind und immer weniger Vertrauen erfahren. Nicht ohne Grund boomen Naturprodukte so sehr wie noch nie. Natur statt Chemie lautet die Devise. Die wichtigste (Wieder-)Entdeckung der vergangenen Jahre: <a href="https://sanaleo.com/anwendungsfelder-und-vorteile-der-hanfpflanze/" title="Anwendungsgebiete Cannabis">Das Potential von Cannabis.</a></p>
      </div>
		
	<button class="faq-accordion-archive"><h2 class="accordion_heading">WAS SIND TERPENE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p><a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Ueberblick_Welche_Cannabinoide_gibt_es_Was_sind_Terpene" title="Was sind Terpene">Terpene</a> sind kurz gesagt Pflanzeninhaltsstoffe. Sie übernehmen häufig funktionale Eigenschaften und sind in vielen Fällen für den charakteristischen Geruch einer Pflanze verantwortlich. Die aromatische Fülle soll Insekten zur Bestäubung anlocken. Auch die Hanfpflanze enthält neben den zentralen Cannabinoiden eine Vielzahl an Terpenen, die den unverkennbaren Geruch verantworten. Die Auswirkungen, die Terpene auf den Menschen haben, werden bereits in der Aromatherapie genutzt. Wissenschaftliche Erkenntnisse legen nahe, dass Hanfextrakte, die neben CBD das volle Spektrum der natürlichen Terpene der Pflanze enthalten, eine höhere Bioverfügbarkeit aufweisen und somit eine bessere Wirkung entfalten. Man spricht hier vom sog. <a href="https://sanaleo.com/der-entourage-effekt/" title="Entourage Effekt">Entourage-Effekt</a>: Die Wirkung der Pflanze ist größer als die Summe ihrer Bestandteile. Bis heute hat die Forschung die Struktur von etwa 20.000 Terpenen identifiziert und analysiert. Die wichtigsten im Zusammenhang mit CBD sind: B-Caryophyllene, Limonene, Linalool, Myrzene und Pinene. Das Zusammenspiel der Terpene birgt enormes Potential, das es verstehen und richtig anzuwenden gilt! Weitere Informationen zu den genannten Terpenen sind im <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Was_Sind_Terpene" title="CBD Wiki - alle Informationen zu CBD">CBD-Wiki</a> zu finden.</p>
      </div>

      <div class="has-text-align-center has-text-color" style="margin-bottom: 5%; font-size: 1.2em; font-weight: 400; max-width: 700px;">
        Die <a href="https://sanaleo.com/cbd-blueten">CBD-Aromablüten</a> von Sanaleo werden unter unterschiedlichen Voraussetzungen angebaut.
      </div>

<div class="product-table-wrapper p-960">
    <div class="product-table-col">
        <div class="product-table-header">
            <h4>Outdoor</h4>
            <img class="product-table-icon" src="https://sanaleo.com/wp-content/uploads/2021/06/outdoor-01.svg" alt="" width="70px">
        </div>
        <div class="product-table-content">
            <ul class="product-table-list">
                <li><a href="https://sanaleo.com/cbd-blueten/black-domina/">Black Domina</a> <img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_blackdomina.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/butters-bud">Butters Bud</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_buttersbud.png" alt=""></li>
            </ul>
        </div>
    </div>
    <div class="product-table-col">
        <div class="product-table-header">
            <h4>Indoor</h4>
            <img class="product-table-icon" src="https://sanaleo.com/wp-content/uploads/2021/06/indoor-01.svg" alt="" width="70px">
        </div>
        <div class="product-table-content">
            <ul class="product-table-list">
                <li><a href="https://sanaleo.com/cbd-blueten/hawaiian-skunk">Hawaiian Skunk</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_hawaiianskunk.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/amnesia/">Amnesia</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_amnesia.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/super-lemon-haze/">Lemon Haze</a> <img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_lemonhaze.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/mango-kush">Mango Kush</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_mangokush.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/orange-bud">Orange Bud</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_orangebud.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/moby-dick">Moby Dick</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_mobydick.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/power-plant">Power Plant</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_powerplant.png" alt=""></li>
            </ul>
        </div>
    </div>
    <div class="product-table-col">
        <div class="product-table-header">
            <h4>Greenhouse</h4>
            <img class="product-table-icon" src="https://sanaleo.com/wp-content/uploads/2021/06/greenhouse-01.svg" alt="" width="70px">
        </div>
        <div class="product-table-content">
            <ul class="product-table-list">
                <li><a href="https://sanaleo.com/cbd-blueten/gorilla-glue/">Gorilla Glue</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_gorillaglue.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/jack-herer/">Jack Herer</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_jackherer.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/skywalker-og/">Skywalker OG</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_skywalkerog.png" alt=""></li>
            </ul>
        </div>
    </div>
</div>

<div class="has-text-align-center has-text-color" style="
	margin-bottom: 5%; margin-top: 10%;

	font-size: 1.2em;
	font-weight: 400; max-width: 700px;">Bei einzelnen CBD-Blütensorten von Sanaleo wurde der CBD-Gehalt nachträglich
    erhöht oder reduziert.
</div>

<div class="product-table-wrapper p-960" style="margin-bottom: 10%">
    <div class="product-table-col">
        <div class="product-table-header">
            <h4>THC-<br>reduziert</h4>
            <img class="product-table-icon" src="https://sanaleo.com/wp-content/uploads/2021/06/THC_Zeichenfläche-1.svg" alt="" width="60px">
        </div>
        <div class="product-table-content">
            <ul class="product-table-list">
                <li><a href="https://sanaleo.com/cbd-blueten/amnesia/">Amnesia</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_amnesia.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/hawaiian-skunk">Hawaiian Skunk</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_hawaiianskunk.png" alt=""></li>

                <li><a href="https://sanaleo.com/cbd-blueten/super-lemon-haze/">Lemon Haze</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_lemonhaze.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/mango-kush">Mango Kush</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_mangokush.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/orange-bud">Orange Bud</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_orangebud.png" alt=""></li>

                <li><a href="https://sanaleo.com/cbd-blueten/black-domina/">Black Domina</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_blackdomina.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/butters-bud">Butters Bud</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_buttersbud.png" alt=""></li>
            </ul>
        </div>
    </div>
    <div class="product-table-col">
        <div class="product-table-header">
            <h4>Komplett<br>naturbelassen</h4>
            <img class="product-table-icon" src="https://sanaleo.com/wp-content/uploads/2021/06/100_-nat-Inh_Zeichenfläche-1.svg" alt="" width="60px">
        </div>
        <div class="product-table-content">
            <ul class="product-table-list">
                <li><a href="">Moby Dick</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_mobydick.png" alt=""></li>
                <li><a href="">Power Plant</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_powerplant.png" alt=""></li>
            </ul>
        </div>
    </div>
    <div class="product-table-col">
        <div class="product-table-header">
            <h4>Erhöhter<br>CBD-Gehalt</h4>
            <img class="product-table-icon" src="https://sanaleo.com/wp-content/uploads/2021/06/erhoehter-CBD-G.-01.svg" alt="" width="60px">
        </div>
        <div class="product-table-content">
            <ul class="product-table-list">
                <li><a href="https://sanaleo.com/cbd-blueten/gorilla-glue/">Gorilla Glue</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_gorillaglue.png" alt=""></li>
                <li><a href="https://sanaleo.com/cbd-blueten/jack-herer/">Jack Herer</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_jackherer.png" alt=""></li>
                <li><a href="">Skywalker OG</a><img src="https://sanaleo.com/wp-content/uploads/2021/10/gradient_skywalkerog.png" alt=""></li>
            </ul>
        </div>
    </div>
</div>
      ';
  }

  else if(is_product_category('cbd-oele')){
    echo '
      <!--<button class="faq-accordion-archive" style="text-align: center;"><h2 class="accordion_heading" style="font-size: 1em; margin-bottom: 0px;">WIE WERDEN SANALEO-PREMIUM-CBD-ÖLE HERGESTELLT?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Die angebotenen CBD-Öle werden aus getrockneten <a href="https://sanaleo.com/cbd-blueten/" title="CBD Blüten von Sanaleo">CBD-Cannabisblüten</a> mit einem <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Wieso_haben_EU-Nutzhanfsorten_unterschiedlich_hohe_CBD-Gehalte" title="CBD Gehalt von Cannabisblüten">natürlicherweise hohen CBD-Gehalt hergestellt.</a> Die dafür verwendeten Cannabisblüten werden ohne jeglichen Einsatz von Herbiziden oder Pestiziden angebaut. Nach der Ernte und dem Trocknungsprozess werden die Cannabisblüten mithilfe eines einzigartigen Verfahren extrahiert, bei dem eine sehr hohe Ausbeute erreicht wird. Nach der Extraktion ist zudem kein Einsatz von Lösungsmitteln erforderlich. Zuletzt wird das Cannabisextrakt mit einer natürlichen Ölbasis vermengt.</p>
      </div>

      <button class="faq-accordion-archive"><h2 class="accordion_heading">WELCHE CBD-ÖLE SIND BEI UNS ERHÄLTLICH?</h2><i class="faq-archive-info-open"></i></button>
      
      <div class="panel-archive">
        <p>Wie du bestimmt bereits gesehen hast, kannst du bei SANALEO verschiedene CBD-Öle kaufen:</p>
        <ul>
          <li><a href="https://sanaleo.com/cbd-oele/full-spectrum-oele/" title="Full Spectrum CBD Öl">Full Spectrum CBD Öle</a> mit 5% | 15% | 25% CBD</li>
          <li><a href="https://sanaleo.com/cbd-oele/broad-spectrum/" title="Broad Spectrum CBD Öl">Broad Spectrum CBD Öle</a> mit 5% | 15% | 25% CBD</li>
          <li>Sanaleo Unique Collection mit 5% | 15% | 25% CBD</li>
        </ul>
        <p>Unsere Full Spectrum-Öle und Broad Spectrum-Öle unterscheiden sich zum einen in der Zusammensetzung der einzelnen natürlichen Inhaltsstoffe und zum anderen in ihrer Öl-Basis. Full Spectrum-Öle enthalten alle natürlichen Inhaltsstoffe des für die Herstellung verwendeten Pflanzenmaterials, während in Broad Spectrum-Ölen bestimmte Inhaltsstoffe herausgefiltert wurden. Zusätzlich bieten wir verschiedene Full Spectrum-Öle an, die von unserer Aroma-Öl-Expertin mit weiteren natürlichen Pflanzenextrakten ergänzt werden. Diese Spezial-Öl-Mischungen werden mit ätherischen Ölen angereichert. Ätherische Öle enthalten Terpene, die den <a href="https://sanaleo.com/der-entourage-effekt/" title="Der Entourage-Effekt">Entourage-Effekt</a> verstärken können.</p>
      </div>-->

      <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS SIND TERPENE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
          <p>Terpene sind kurz gesagt Pflanzeninhaltsstoffe. Sie übernehmen häufig funktionale Eigenschaften und sind in vielen Fällen für den charakteristischen Geruch einer Pflanze verantwortlich. Die aromatische Fülle soll Insekten zur Bestäubung anlocken. Auch die Hanfpflanze enthält neben den zentralen Cannabinoiden eine Vielzahl an Terpenen, die den unverkennbaren Geruch verantworten. Die Auswirkungen, die Terpene auf den Menschen haben, werden bereits in der Aromatherapie genutzt. Wissenschaftliche Erkenntnisse legen nahe, dass Hanfextrakte, die neben CBD das volle Spektrum der natürlichen Terpene der Pflanze enthalten, eine höhere Bioverfügbarkeit aufweisen und somit eine bessere Wirkung entfalten. Man spricht hier vom sog. <a href="https://sanaleo.com/der-entourage-effekt/" title="Der Entourage Effekt">Entourage-Effekt</a>: Die Wirkung der Pflanze ist größer als die Summe ihrer Bestandteile. Bis heute hat die Forschung die Struktur von etwa 20.000 Terpenen identifiziert und analysiert. Die wichtigsten im Zusammenhang mit CBD sind: B-Caryophyllene, Limonene, Linalool, Myrzene und Pinene. Das Zusammenspiel der Terpene birgt enormes Potential, das es verstehen und richtig anzuwenden gilt! Weitere Informationen zu den genannten Terpenen sind im <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Was_Sind_Terpene" title="CBD Wiki - alle Informationen zu CBD">CBD-Wiki</a> zu finden.</p>
      </div>

      <button class="faq-accordion-archive"><h2 class="accordion_heading">WIESO HABEN EU-NUTZHANFSORTEN UNTERSCHIEDLICH HOHE CBD-GEHALTE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Verschiedene Produkte derselben Kategorie weisen unterschiedliche CBD-Gehalte auf. Vornehmlich bei <a href="https://sanaleo.com/cbd-blueten/" title="CBD Aromablüten">CBD-Blüten</a> finden wir geringe und vergleichsweise sehr hohe CBD-Anteile. Auch einzelne Chargen derselben Sorte variieren in den Cannabinoidwerten. Doch weshalb ist das so?</p>
        <ol type="1">
          <li>Das Züchten von Hanf ist eine wirkliche Aufgabe. Die Aufgabe wird genau dann zur Kunst, wenn dieselbe Sorte stabil über Jahre gezüchtet und angebaut werden soll.</li> 
          <li>Das Verhältnis von THC zu CBD und von CBD zu THC ist nicht beliebig. Demnach spielt der gesetzlich vorgeschriebene THC-Gehalt des Vertriebslandes eine entscheidende Rolle für den CBD-Gehalt. Die unterschiedliche Gesetzeslage innerhalb der EU sorgt also dafür, dass in manchen Ländern (Österreich, Luxemburg) der auf natürlichem Wege erzielbare CBD-Gehalt bei max. 9% liegt, in den meisten Ländern der EU bei max. 6% CBD.</li>
          <li>Gute Werte für das Verhältnis THC zu CBD sind bei natürlichem Anbau von EU-Nutzhanf derzeit 1:20 bis 1:30. Das bedeutet, dass maximal der dreißigfache Anteil von CBD zum zugelassenen THC-Grenzwert erzielt werden kann. In Deutschland wären das bei einem Grenzwert von < 0,2% THC ideal gerechnet max. 6% CBD.</li>
        </ol>
        <p>Weshalb gibt es dennoch Anbieter, die Sorten mit 18% CBD bewerben? In nahezu allen EU-Ländern gibt es aktuell keine einheitlichen Rahmenbedingungen zur Sicherung von Qualitätsstandards. Mit hoher Wahrscheinlichkeit werden die Blüten mit CBD-Isolaten behandelt, die allerdings eine sehr geringe Bioverfügbarkeit aufweisen. Natürlich gewachsene, unbehandelte <a href="https://sanaleo.com/ubersicht-cannabinoide-der-hanfplanze/" title="Die Cannabinoide der Hanfpflanze">Cannabinoide</a> besitzen die höchste Bioverfügbarkeit.</p>
      </div>

      <button class="faq-accordion-archive"><h2 class="accordion_heading">Was ist CBD?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p><a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/" title="Was ist CBD?">CBD</a> steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des <a href="https://sanaleo.com/was-ist-cbd-und-wie-wirkt-es-im-menschlichen-koerper/" title="Das Endocannabinoid-System">Endocannabinoid-Systems</a>, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Die wissenschaftliche These ist: Exogen zugeführte Cannabinoide stimulieren das System, das einen Ausgleich der ausgeschütteten Botenstoffe anstrebt. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt.</p>
      </div>
<button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST DER UNTERSCHIED ZWISCHEN THC UND CBD?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend. Weitere Informationen zur <a href="https://cbdratgeber.de/legal/ist-cbd-legal-deutschland/" title="Legalität von CBD Produkten">Legalität von CBD-Produkten</a> findet ihr beim CBD-Ratgeber.
      </p>   
    </div>
      <button class="faq-accordion-archive"><h2 class="accordion_heading">CBD ALS PFLANZLICHE ALTERNATIVE</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
      <p>Die Wenigsten von uns sind im Alltag frei von Beschwerden. In Zeiten des Leistungsdrucks und des ständigen “Funktionieren-Müssens” neigen wir dazu, sie leichtfertig zu ignorieren. Schnelle Abhilfe versprechen massenhaft pharmazeutische Produkte, die nicht frei von Nebenwirkungen sind und immer weniger Vertrauen erfahren. Nicht ohne Grund boomen Naturprodukte so sehr wie noch nie. Natur statt Chemie lautet die Devise. Die wichtigste (Wieder-)Entdeckung der vergangenen Jahre: <a href="https://sanaleo.com/anwendungsfelder-und-vorteile-der-hanfpflanze/" title="Anwendungsgebiete Cannabis">Das Potential von Cannabis.</a></p>
      </div>

      <div class="text-align-justify-last-center p-960 has-text-color" style="color:#0b5a64; font-size: 1.2em;">Wie du bestimmt gesehen hast, kannst du bei SANALEO verschiedene <a href="https://sanaleo.com/cbd-oele">CBD-Öle</a> kaufen:

        <ul style="list-style: none; margin: 20px 0;">
        <li><b>Full Spectrum CBD Öle</b> mit 5% | 15% | 25% CBD</li>
        <li><b>Broad Spectrum CBD Öle</b>mit 5% | 15% | 25% CBD</li>
        <li><b>Happy- und Ease-Drops</b> mit 5% | 15% | 25% CBD</li>
        </ul>
        
        Unsere Unique Collection (Happy und Ease Drops) sind Full Spectrum Öle, die mit ausgewählten ätherischen Ölen angereichert wurden, die bestimmte Effekte verstärken sollen.
        
        Sanaleo CBD-Öle sind:
        
        glutenfrei, vegan und frei von künstlichen Konservierungsmitteln und Farbstoffen.
        transportabel, einfach anzuwenden und nachhaltig verpackt.
        ausgewählt, zertifiziert und vielfach geprüft. 
        
        Sanaleo CBD-Mundöle werden mit einem einzigartigen Extraktionsverfahren hergestellt, bei dem eine außergewöhnlich hohe Extraktionsausbeute erzielt wird. Dieses patentierte Herstellungs-verfahren bringt im Vergleich zur konventionellen CO2-Extraktion einige Vorteile mit sich:
        </div>
        
        
        <div id="oel-table" class="product-table-wrapper p-960">
            <div class="product-table-col">
                <div class="product-table-header">
                    <h4>CO2 Extraktionsmethode</h4>
                </div>
                <div class="product-table-content">
                    <ul class="product-table-list">
                        <li><b>Selektivität:</b> hoher Extraktionsdruck führt zu schlechter Selektivität</li>
                        <li><b>Rückstände:</b>anschließende Reinigungsschritte mit unerwünschten Lösungsmitteln erforderlich</li>
                        <li><b>Lösungsmittel:</b> überkritisches CO2 ist sauer und verursacht Abbau der fragilen Mono- und Di-Terpene</li>
                        <li><b>Temperatur:</b> hohe Verdampfungstemperaturen führen zu einem Abbau der zerbrechlichen aromatischen Terpene</li>
                    </ul>
                </div>
            </div>
                <div class="product-table-col">
                    <div class="product-table-header">
                        <h4>Sanaleos One-Step-Extraction</h4>
                    </div>
                    <div class="product-table-content">
                        <ul class="product-table-list">
                            <li><b>Selektivität:</b> effizient und hochselektiv - weder Wachse noch unerwünschte Polyphenole werden extrahiert</li>
                            <li><b>Rückstände:</b> keine Weiterverarbeitung erforderlich - keine nachweisbaren Mengen an Lösungsmittelrückständen im Endprodukt</li>
                            <li><b>Lösungsmittel:</b> Lösungsmittel ist nicht toxisch, nicht korrosiv, nicht brennbar, inert und pH-neutral</li>
                            <li><b>Temperatur:</b> Extraktion bei niedrigen Temperaturen - die chemische Zusammensetzung ähnelt sehr stark der der Cannabispflanze </li>
                        </ul>
                    </div>
                </div>
        
        </div>
    ';
  }

  else if(is_product_category('lebensmittel')){
    echo '
    <button class="faq-accordion-archive"><h2 class="accordion_heading">KANN MAN ZU VIEL HANF ESSEN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Die Hanflebensmittel haben keinerlei berauschende Wirkung und können ohne Bedenken verzehrt werden. Sie können sämtliche Gerichte mit den Hanfsamen, dem Hanföl und allen anderen Hanfprodukten aufwerten und zusammen mit der Familie genießen. Hanf ist sowohl für Dich als auch die Kleinen vollkommen unbedenklich.</p>
    </div>
    ';
   }

   else if(is_product_category('cbd-vape')){
    echo '
    <!--<button class="faq-accordion-archive"><h2 class="accordion_heading">WIE NEHME ICH MEIN SANALEO-VAPE-STARTERKIT IN BETRIEB?</h2>
    <i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Lade den Pen vor der ersten Nutzung sicherheitshalber noch einmal auf. Wenn er vollständig geladen ist, leuchtet das grüne Licht durchgängig. Verbinde anschließend deine <a href="https://sanaleo.com/cbd-vape/pineapple-express-kartusche/" title="CBD Kartuschen von Sanaleo kaufen">SANALEO-Vape CBD-Kartusche</a> mit dem <a href="https://sanaleo.com/cbd-vape/cbd-pen/" title="CBD Vape Pen kaufen">SANALEO-Vape-Pen</a> und ziehe daran. Normalerweise sollte es direkt zu einer Dampfbildung kommen, sobald du an dem Pen ziehst. 

      Wichtig ist, dass Du gleichmäßige und nicht zu starke Züge nimmst. Andernfalls könnten die hohen Temperaturen dafür sorgen, dass wichtige Terpene zerstört werden, die im Sinne des <a href="https://sanaleo.com/der-entourage-effekt/" title="Der Entourage Effekt">Entourage-Effekts</a> unbedingt erhalten werden sollten.
      
      Ob die Kartusche permanent auf dem Pen bleibt oder nicht, ist Dir überlassen. Er versetzt sich automatisch in den Standby-Modus, solange länger nicht daran gezogen wurde. Der Pen muss nicht zwangsläufig nach einer bestimmten Zeit entsorgt oder ausgetauscht werden.
      </p>
    </div>
    
  <button class="faq-accordion-archive"><h2 class="accordion_heading">WANN MUSS ICH MEINEN VAPE-PEN LADEN? WANN IST MEIN VAPE-PEN GELADEN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Wenn der <a href="https://sanaleo.com/cbd-vape/cbd-pen/" title="CBD Pen kaufen">CBD-Pen</a> geladen werden muss, blinkt das grüne Licht in regelmäßigen Abständen. Wenn er geladen ist, leuchtet das Licht durchgängig.
      </p>   
    </div>-->
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS SIND TERPENE?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Terpene sind kurz gesagt Pflanzeninhaltsstoffe. Sie übernehmen häufig funktionale Eigenschaften und sind in vielen Fällen für den charakteristischen Geruch einer Pflanze verantwortlich. Die aromatische Fülle soll Insekten zur Bestäubung anlocken. Auch die Hanfpflanze enthält neben den zentralen Cannabinoiden eine Vielzahl an Terpenen, die den unverkennbaren Geruch verantworten. Die Auswirkungen, die Terpene auf den Menschen haben, werden bereits in der Aromatherapie genutzt. Wissenschaftliche Erkenntnisse legen nahe, dass Hanfextrakte, die neben CBD das volle Spektrum der natürlichen Terpene der Pflanze enthalten, eine höhere Bioverfügbarkeit aufweisen und somit eine bessere Wirkung entfalten. Man spricht hier vom sog. <a href="https://sanaleo.com/der-entourage-effekt/" title="Entourage Effekt">Entourage-Effekt</a>: Die Wirkung der Pflanze ist größer als die Summe ihrer Bestandteile. Bis heute hat die Forschung die Struktur von etwa 20.000 Terpenen identifiziert und analysiert. Die wichtigsten im Zusammenhang mit CBD sind: B-Caryophyllene, Limonene, Linalool, Myrzene und Pinene. Das Zusammenspiel der Terpene birgt enormes Potential, das es verstehen und richtig anzuwenden gilt! Weitere Informationen zu den genannten Terpenen sind im <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Was_Sind_Terpene" title="CBD Wiki - alle Informationen zu CBD">CBD-Wiki</a> zu finden.</p>
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST CBD?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p><a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/" title="Was ist CBD?">CBD</a> steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des <a href="https://sanaleo.com/was-ist-cbd-und-wie-wirkt-es-im-menschlichen-koerper/" title="Das Endocannabinoid-System">Endocannabinoid-Systems</a>, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Die wissenschaftliche These ist: Exogen zugeführte Cannabinoide stimulieren das System, das einen Ausgleich der ausgeschütteten Botenstoffe anstrebt. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt.</p>
      </p>   
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">CBD ALS PFLANZLICHE ALTERNATIVE?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Die Wenigsten von uns sind im Alltag frei von Beschwerden. In Zeiten des Leistungsdrucks und des ständigen “Funktionieren-Müssens” neigen wir dazu, sie leichtfertig zu ignorieren. Schnelle Abhilfe versprechen massenhaft pharmazeutische Produkte, die nicht frei von Nebenwirkungen sind und immer weniger Vertrauen erfahren. Nicht ohne Grund boomen Naturprodukte so sehr wie noch nie. Natur statt Chemie lautet die Devise. Die wichtigste (Wieder-)Entdeckung der vergangenen Jahre: <a href="https://sanaleo.com/anwendungsfelder-und-vorteile-der-hanfpflanze/" title="Anwendungsgebiete Cannabis">Das Potential von Cannabis.</a></p>   
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">KANN MAN ZU VIEL CBD ZU SICH NEHMEN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      CBD selbst verursacht keinen Rauschzustand. Deshalb kann man es auch nicht überdosieren. Bei einem zertifizierten THC-Gehalt von unter 0,2% ist ein Missbrauch als Rauschmittel auszuschließen. Versuch es also gar nicht erst.
      </p>   
    </div>
	<button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST DER UNTERSCHIED ZWISCHEN THC UND CBD?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend. Weitere Informationen zur <a href="https://cbdratgeber.de/legal/ist-cbd-legal-deutschland/" title="Legalität von CBD Produkten">Legalität von CBD-Produkten</a> findet ihr beim CBD-Ratgeber.
      </p>   
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS MUSS ICH BEI DER KOMBINATION VON CBD MIT MEDIKAMENTEN BEACHTEN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Bei CBD und Medikamenten muss grundsätzlich ein Arzt konsultiert werden. Die meisten ÄrztInnen sind mit der Wirkweise von CBD vertraut und können dir genügend Informationen zu möglichen Wechselwirkungen mit anderen Medikamenten geben.
      </p>   
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">CBD UND SCHWANGERE</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Wegen der geringen Studienlage raten wir Schwangeren vom Genuss von <a href="https://sanaleo.com/shop-sortiment/" title="CBD Produkte im Onlineshop kaufen">CBD-Produkten</a> ab.
      </p>   
    </div>
    ';
    
   }


   else if(is_product_category('cbd-kapseln')){
    echo '
    <!--<button class="faq-accordion-archive"><h2 class="accordion_heading">KANN MAN DIE TRAUMKAPSELN ÜBERDOSIEREN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Eine Überdosierung durch unsere Traumkapseln ist ausgeschlossen, da sie keine berauschende Wirkung besitzen.
      </p>   
    </div>

    <button class="faq-accordion-archive"><h2 class="accordion_heading">IST IN DEN TRAUMKAPSELN WIRKLICH MELATONIN DRIN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Unsere CBD Schlafkapseln bestehen aus Steinpilzpulver, Melissenextrakt, Cannabidiol, Traubenzucker und ja, einer kleinen Prise Melatonin. Die Menge an Melatonin ist jedoch gerade so gering, dass sie gänzlich unbedenklich ist, aber dennoch ihre Wirkung entfaltet.
      </p>   
    </div>-->
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST CBD?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/" title="Was ist CBD?">CBD</a> steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des Endocannabinoid-Systems, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Die wissenschaftliche These ist: Exogen zugeführte Cannabinoide stimulieren das System, das einen Ausgleich der ausgeschütteten Botenstoffe anstrebt. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt. 
      </p>   
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST DER UNTERSCHIED ZWISCHEN THC UND CBD?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend. Weitere Informationen zur <a href="https://cbdratgeber.de/legal/ist-cbd-legal-deutschland/" title="Legalität von CBD Produkten">Legalität von CBD-Produkten</a> findet ihr beim CBD-Ratgeber.
      </p>   
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">CBD ALS PFLANZLICHE ALTERNATIVE?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Die Wenigsten von uns sind im Alltag frei von Beschwerden. In Zeiten des Leistungsdrucks und des ständigen “Funktionieren-Müssens” neigen wir dazu, sie leichtfertig zu ignorieren. Schnelle Abhilfe versprechen massenhaft pharmazeutische Produkte, die nicht frei von Nebenwirkungen sind und immer weniger Vertrauen erfahren. Nicht ohne Grund boomen Naturprodukte so sehr wie noch nie. Natur statt Chemie lautet die Devise. Die wichtigste (Wieder-)Entdeckung der vergangenen Jahre: <a href="https://sanaleo.com/anwendungsfelder-und-vorteile-der-hanfpflanze/" title="Anwendungsgebiete Cannabis">Das Potential von Cannabis.</a></p> 
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">KANN MAN ZU VIEL CBD ZU SICH NEHMEN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      CBD selbst verursacht keinen Rauschzustand. Deshalb kann man es auch nicht überdosieren. Bei einem zertifizierten THC-Gehalt von unter 0,2% ist ein Missbrauch als Rauschmittel auszuschließen. Versuch es also gar nicht erst.
      </p>   
    </div>
   
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS MUSS ICH BEI DER KOMBINATION VON CBD MIT MEDIKAMENTEN BEACHTEN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Bei CBD und Medikamenten muss grundsätzlich ein Arzt konsultiert werden. Die meisten ÄrztInnen sind mit der Wirkweise von CBD vertraut und können dir genügend Informationen zu möglichen Wechselwirkungen mit anderen Medikamenten geben.
      </p>   
    </div>
     
    <button class="faq-accordion-archive"><h2 class="accordion_heading">CBD UND SCHWANGERE</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Wegen der geringen Studienlage raten wir Schwangeren vom Genuss von <a href="https://sanaleo.com/shop-sortiment/" title="CBD Produkte im Onlineshop kaufen">CBD-Produkten</a> ab.
      </p>   
    </div>
    
    ';
  
  }
}


/* ADD SPECIFIC STYLES TO PRODUCTS*/

add_action( 'wp_head', 'add_styles_to_product', 100);
function add_styles_to_product(){
	global $post;
    if ( has_term( 'cbd-vape', 'product_cat', $post->ID ) ) {
		echo "<style>.product-row{background-color: #f3f3d9 !important}#primary{margin-top: 0 !important;}</style>";
	}
	else if ( has_term( 'lebensmittel', 'product_cat', $post->ID ) ) {
		echo "<style>.product-row{background-color: #f6eaff !important}#primary{margin-top: 0 !important;}</style>";
	}
	else if ( has_term( 'cbd-blueten', 'product_cat', $post->ID ) ) {
		echo "<style>.ast-row{background-color: #ecfbe8 !important; padding: 5%; border-radius: 50px}.ast-col-md-5{background-color: white; padding: 5%; border-radius: 50px;}.woocommerce-breadcrumb{margin-top: 5%;}#primary{margin-top: 0 !important;}</style>";
	}
}


/*QUANTITY BUTTON - ADD TO CART - WRAPPER*/

add_action( 'woocommerce_before_add_to_cart_quantity', 'flexwrapper_start' , 10 );
add_action( 'woocommerce_after_add_to_cart_button', 'wrapper_end', 10 );


function flexwrapper_start() {
  echo '<div class="inline-flex-wrapper">';
}

function wrapper_end() {
  echo '</div>';
}


function get_current_product_category(){
	global $post;

  $ers = array(

    ' ' => '_',
    'Ä' => 'Ae',
    'Ö' => 'Oe',
    'Ü' => 'Ue',
    'ä' => 'ae',
    'ö' => 'oe',
    'ü' => 'ue',
    'ß' => 'ss'
);
  
  $terms = get_the_terms( $post->ID, 'product_cat' );        
  
  if($terms) {
		foreach ($terms as $term){
		    $product_cat_name_temp = $term->name;  
		    break;
      }
  }
  
  $product_cat_name = strtr($product_cat_name_temp, $ers);
  $product_cat_name = strtolower($product_cat_name);
  echo $product_cat_name;
}

/**
 * @snippet       Rename SALE badge @ Product Archives and Single Product
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    Woo 4.1
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_filter( 'woocommerce_sale_flash', 'bbloomer_rename_sale_badge', 9999 );
 
function bbloomer_rename_sale_badge() {
   return '<span class="onsale">Angebot!</span>';
}
 
// NOTE: PLEASE KEEP THE <SPAN> TAG

// Check Cart for Blüten and show Klarna Message

add_action('woocommerce_before_cart', 'action_before_cart');
add_action( 'woocommerce_review_order_before_payment', 'action_before_cart');
function action_before_cart() {
    $categories   = array('cbd-blueten');
    $has_category = false;
    
    // Loop through cart items
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        // Check for product categories
        if ( has_term( $categories, 'product_cat', $cart_item['product_id'] ) ) {
            $has_category = true;
            break;
        }
    }
    
    // Testing output (display a notice)
    if ( $has_category ) { 
        wc_print_notice( sprintf( 'Leider dürfen wir für den Kauf von CBD-Blüten keine Zahlung mit Klarna anbieten. Wir bitten um Verständnis.', reset($categories)), 'notice' );
    }
}