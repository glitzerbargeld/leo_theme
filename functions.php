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
	?>
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
  "openingHours": [
    "Mo-So 00:00-24:00"
  ],
  "priceRange": "$",
  "brand": "Sanaleo",
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "51.342509967071756",
    "longitude": "12.36211580490183"
  },
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

/*
function customjs_load_blueten() {
	if (is_product_category() and is_product_category("CBD Aromablüten")) :
	echo '<script type="application/ld+json" async>
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "Wieso erhalte ich keine Verzehr- bzw. Dosierungsempfehlungen für die CBD Blüten und Tropfen?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Auf Grund der derzeitigen Gesetzeslage und der Einstufung von CBD dürfen wir keine genaue Verzehr- und Dosierungsempfehlung für unsere Produkte abgeben. Der Gesetzgeber ist hier sehr kritisch gegenüber   gesundheitsbezogenen Auskünften. Deswegen überlassen wir das den   medizinischen Experten. Falls du gerne nähere Informationen hättest,   können wir dir auf Anfrage gerne Ärzte oder Ärztinnen empfehlen, welche   sich gut mit der Wirkung von CBD auskennen."
    }
  },{
    "@type": "Question",
    "name": "Was, wenn mich die Polizei kontrolliert und bei den CBD-Blüten stutzig wird?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Falls Du einmal von der Polizei kontrolliert werden solltest, verweise einfach auf die Etiketten mit unserem Firmennamen oder führe ein Analysenzertifikat mit Dir mit. So bist du garantiert auf der sicheren Seite."
    }
  },{
    "@type": "Question",
    "name": "Wie wirkt CBD auf meinen Körper und wie kann ich es einnehmen?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Unsere Blüten, Pollinate und Tropfen sind nicht zur Einnahme empfohlen. Daher geben wir keine Auskunft bezüglich etwaiger Wirkungen oder Ähnlichem. Wir haben jedoch Ansprechpartner aus dem medizinischen Bereich, welche sich gut mit den Wirkungsmechanismen von CBD auskennen und euch genauere Auskünfte erteilen können. Bei Interesse einfach eine E-Mail an info@sanaleo-cbd.de schicken, oder unser Kontaktformular nutzen."
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
    "name": "Informationen zum Anbau der angebotenenen CBD Blüten:",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Outdoor: Cheese, V1, Black Domina. Indoor: Skywalker OG, Jack Herer, Gorilla Glue, Litani Hash, Caramello Hash, Vanilla Kush, Amnesia, Strawberry Haze, Super Lemon Haze, Watermelon Cookies, Pineapple Express. Green House: Mango Kush. Außerdem können die Analysezertifikate zu jeder Blüte auf der entsprechenden Produktdetailseite betrachtet werden."
    }},{
    "@type": "Question",
    "name": "Wie lagert man die CBD-Blüten sicher und richtig?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Am wohlsten fühlen sich die CBD-Blüten, wenn sie trocken und luftdicht verpackt sind. So trocknen sie nicht aus und bewahren bestens ihr Aroma."
    }},{
    "@type": "Question",
    "name": "Können die CBD-Blüten schlecht werden?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Sollten die CBD-Blüten feucht werden, ist eine Schimmelbildung nicht auszuschließen. Deshalb immer darauf achten, dass sie trocken und luftdicht gelagert sind. Im Laufe der Zeit ist ein Austrocknen der Blüten leider kaum zu verhindert. Aber dadurch verlieren sie gewiss nicht an Qualität."
    }},{
    "@type": "Question",
    "name": "Kann man zu viel CBD zu sich nehmen?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "CBD selbst verursacht keinen Rauschzustand. Deshalb kann man es auch nicht überdosieren. Auch bei einem zertifizierten THC-Gehalt von unter 0,2% ist ein Rauschmissbrauch auszuschließen. Versuch es also gar nicht erst."
    }},{
    "@type": "Question",
    "name": "Welche CBD Blüten sind bei uns erhältlich?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Unser Sortiment umfasst Outdoor, Indoor und Greenhouse CBD-Blüten aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unser Hersteller arbeitet mit einer professionellen Samenbank zusammen und hat über 15 Jahre beim Anbau von Cannabis. Das ermöglicht es uns eine exklusive Genetik und Qualität anbieten zu können."
    }}
  ]
}
</script>';
	endif;
}
add_action('wp_head', 'customjs_load_blueten', 2);

function customjs_load_oele() {
	if (is_product_category() and is_product_category("CBD Aromaöle")):
	echo '<script type="application/ld+json" async>
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "Wieso erhalte ich keine Verzehr- bzw. Dosierungsempfehlungen für die CBD Blüten und Tropfen?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Auf Grund der derzeitigen Gesetzeslage und der Einstufung von CBD dürfen wir keine genaue Verzehr- und Dosierungsempfehlung für unsere Produkte abgeben. Der Gesetzgeber ist hier sehr kritisch gegenüber   gesundheitsbezogenen Auskünften. Deswegen überlassen wir das den   medizinischen Experten. Falls du gerne nähere Informationen hättest,   können wir dir auf Anfrage gerne Ärzte oder Ärztinnen empfehlen, welche   sich gut mit der Wirkung von CBD auskennen."
    }
  },{
    "@type": "Question",
    "name": "Was passiert wenn mich die Polizei aufhält? Ist meine Fahrtüchtigkeit beeinflusst?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Insbesondere unsere Full Spectrum Öle können einen gewissen THC Gehalt aufweisen. Dieser liegt selbstverständlich unter dem von der EU vorgegebenen Grenzwert von 0,2% THC und fällt daher nicht unter den THC-Gehalt von verschreibungspflichtigen Arzneimittel. Dennoch ist es möglich, dass ein Urintest auf THC-Konsum positiv ausfällt."
    }
  },{
    "@type": "Question",
    "name": "Was ist CBD?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "CBD steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des Endocannabinoid-Systems, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt."
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
    "name": "Informationen zur Herstellung unserer CBD Öle:",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Unsere CBD-Öle werden aus getrockneten Cannabisblüten mit einem natürlichlicherweise hohen Cannabidiolgehalt hergestellt. Die dafür verwendeten Cannabisblüten werden ohne jeglichen Einsatz von Herbiziden oder Pestiziden angebaut. Nach der Ernte und dem Trocknungsprozess werden die getrockneten Cannabisblüten mithilfe eines einzigartigen Extraktionsverfahren verarbeitet, bei dem eine außergewöhnlich hohe Extraktionsausbeute erreicht wird. Nach der Extraktion ist zudem kein Einsatz von Lösungsmitteln erforderlich. Zum Schluss wird das Cannabisextrakt mit einer natürlichen Ölbasis vermengt. Alle aktuellen Zertifikate zum jeweiligen Öl können auf unserer Website auf der entsprechenden Produktinformationsseite eingesehen werden."
    }},{
    "@type": "Question",
    "name": "Welche CBD Öle sind bei uns erhältlich?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Wie du bestimmt bereits gesehen hast, gibt es bei uns verschiedene CBD-Öle. Unsere Full Spectrum-Öle und Broad Spectrum-Öle unterscheiden sich zum einen in der Zusammensetzung der einzelnen natürlichen Inhaltsstoffe und zum Anderen in ihrer Öl-Basis. Full Spectrum-Öle enthalten alle natürlichen Inhaltsstoffe des für die Herstellung verwendeten Pflanzenmaterials, während in Broad Spectrum-Ölen bestimmte Inhaltsstoffe herausgefiltert wurden. Zusätzlich bieten wir verschiedene Full Spectrum-Öle an, die von unserer Aroma-Öl-Expertin mit weiteren natürlichen Pflanzenextrakten ergänzt werden. Diese Spezial-Öl-Mischungen werden mit ätherischen Ölen angereichert. Ätherische Öle enthalten Terpene, die den Entourage-Effekt verstärken können. Unsere besten CBD-Öle lassen sich günstig online bestellen und sind innerhalb von wenigen Tagen bei Dir!"
    }}
  ]
}
</script>';
	endif;
}
add_action('wp_head', 'customjs_load_oele', 2);

*/

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
            // add_action('woocommerce_before_single_product_summary', 'implement_row_beginning', 50);
            // add_action('woocommerce_after_single_product_summary', 'implement_div_classes', 10, 4);

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
        echo '<span class="woocommerce-loop-product__title">' . get_the_title() . '</span>';
    }
}

/** ADDS CUSTOMER REVIEWS TO SPECIFIC PAGES**/
add_action('woocommerce_after_shop_loop', 'customer_reviews', 50);
function customer_reviews() {
	if (is_product_category('cbd-blueten')){
    	echo '
		<div class="BewertungsHeading"><h2>Das sagen Kunden, die bei Sanaleo CBD Blüten kaufen:</h2></div>
				<div class="BewertungsContainerMain">
					 <div class="BewertungsConstainer">
						  <div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot"><p>“Preis-Leistung stimmt, super schneller Versand, bestelle immer wieder und bin jedes mal begeistert das die Qualität der CBD Blüten so gut ist.”</p></div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Super schnelle Lieferung, super toll verpackt. Mega zufrieden! Und die Blüten ebenfalls top! Weiter zu empfehlen!!"</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">Die Blüten haben eine sehr gute Qualität und haben ein schönes Aroma. Preis-Leistungs-Verhältnis ist top. Es ist inzwischem meine 3. Bestellung gewesen und weitere werden folgen.”
				</div></div>
					 <div class="BewertungsConstainer"><div class="BCtop">&#9733;&#9733;&#9733;&#9733;&#9733;</div><div class="BCbot">“Die CBD Blüten waren wunderschön in Gläsern verpackt und wurden schnell geliefert. Der Geschmack ist super.”</div></div>
				</div>
			<div class="ProductCategoryDescription">
				<h2 class="BewertungsHeading">WIE WERDEN SANALEO-CBD-BLÜTEN ANGEBAUT?</h2>
				<p>Unser Sortiment umfasst <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Wie_wird_CBD-Hanf_angebaut" title="Die unterschiedlichen Anbaumethoden von Hanf">Outdoor, Indoor und Greenhouse CBD-Blüten</a> aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unsere Hersteller haben allesamt eine langjährige Expertise beim Anbau von Cannabis vorzuweisen. Das ermöglicht es uns, jederzeit einen exklusiven Anspruch hinsichtlich unserer Produkte zu gewährleisten. Freu’ Dich auf beste Qualität.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h2 class="BewertungsHeading">WORIN UNTERSCHEIDEN SICH CANNABISBLÜTEN?</h2>
				<p>Im Laufe der Jahrhunderte wurden in unterschiedlichen Regionen der Welt verschiedene Cannabissorten entdeckt.Im Jahr 1753 klassifizierte der schwedische Naturforscher Carl von Linné die Hanfsorte Cannabis Sativa, in der Übersetzung „gewöhnlicher Hanf“ bedeutend. In Indien wurde 32 Jahre später eine weitere Cannabissorte entdeckt und Cannabis Indica („Indischer Hanf“) getauft. 1926 beschrieb der Botaniker Dimitrij E. Janischwesky die Sorte Ruderalis, auch Ruderal-Hanf genannt.Grundsätzlich gehören diese Cannabissorten zur Pflanzengattung Cannabis Sativa L. Diese einzelnen Cannabissorten unterscheiden sich allerdings in ihrem Aussehen, ihrer Wachstums- und Blütezeit, ihrem Geruch, Geschmack und der natürlichen Zusammensetzung verschiedener Inhaltsstoffe. Mit der Zeit wurden verschiedenste Cannabispflanzen miteinander gekreuzt und so entstanden und entstehen immer neue unterschiedliche Cannabispflanzen und -sorten.</p>
			</div>
			<div class="ProductCategoryDescription">
				<h2 class="BewertungsHeading">WAS MACHT SANALEO-CBD-BLÜTEN BESONDERS?</h2>
				<p>Aufgrund der enormen Erfahrung unserer Hersteller beim Anbau von Cannabis können wir eine exklusive Bio-Genetik garantieren. Unsere CBD-Blüten zeichnen sich durch ihren intensiven Geruch und ihr exotisches Aussehen aus. Einige von unseren Hanf-Blüten, darunter bspw. „Pineapple Express“ sind sogar aus den USA importiert.</p>
			</div>
			'
			
			
			;
	}
	if (is_product_category('cbd-oele')) {
		echo '
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
  
}




/**ADD ACCORDIONS to PRODUCT ARCHIVE*/
add_action('woocommerce_after_shop_loop', 'sana_faq', 50);

function sana_faq(){

  if(is_product_category('cbd-blueten')){
    echo '
      <!--<button class="faq-accordion-archive"><h2 class="accordion_heading">WIE WERDEN SANALEO-CBD-BLÜTEN ANGEBAUT?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Unser Sortiment umfasst <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Wie_wird_CBD-Hanf_angebaut" title="Die unterschiedlichen Anbaumethoden von Hanf">Outdoor, Indoor und Greenhouse CBD-Blüten</a> aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unsere Hersteller haben allesamt eine langjährige Expertise beim Anbau von Cannabis vorzuweisen. Das ermöglicht es uns, jederzeit einen exklusiven Anspruch hinsichtlich unserer Produkte zu gewährleisten. Freu’ Dich auf beste Qualität.</p>
      </div>-->

      <button class="faq-accordion-archive"><h2 class="accordion_heading">WELCHE CBD AROMABLÜTEN SIND BEI UNS ERHÄLTLICH?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p><ul><li>Outdoor: Black Domina | Butters Bud | Skywalker OG</li>
			<li>Indoor: Amnesia | | Gorilla Glue | | Jack Herer | Orange Bud | Pineapple Express | Power Plant | Strawberry Haze | Super Lemon Haze</li> 
<li>Green House: Hawaiian Skunk | Mango Kush</li>
<li>CBD-Hash: Litani Hash | Caramello Hash</li>

Die Analysezertifikate zu jeder Sorte können auf der entsprechenden Produktinformationsseite eingesehen werden. Sie geben Aufschluss über den Gehalt der einzelnen Cannabinoide.
</p>
      </div>

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
        <p>Aufgrund der enormen Erfahrung unserer Hersteller beim Anbau von Cannabis können wir eine exklusive Bio-Genetik garantieren. Unsere CBD-Blüten zeichnen sich durch ihren intensiven Geruch und ihr exotisches Aussehen aus. Einige von unseren Hanf-Blüten, darunter bspw. „Pineapple Express“ sind sogar aus den USA importiert.</p>
      </div>-->
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST CBD?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p><a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/" title="Was ist CBD?">CBD</a> steht für Cannabidiol und ist ein Inhaltsstoff der Cannabispflanze. Er gehört zu den sogenannten Phytocannabinoiden. Dabei handelt es sich um pflanzliche Inhaltsstoffe, die nur die Cannabispflanze produziert. Alle Säugetiere, Fische und Weichtiere produzieren jedoch von Natur aus körpereigene Cannabinoide, die den Phytocannabinoiden strukturell sehr ähnlich sind. Unsere körpereigenen Cannabinoide sind Teil des <a href="https://sanaleo.com/was-ist-cbd-und-wie-wirkt-es-im-menschlichen-koerper/" title="Das Endocannabinoid-System">Endocannabinoid-Systems</a>, welches an unserem Gesundheitserhalt, an gewissen Genesungsprozessen und folglich auch an unserer Gemütslage beteiligt ist. Die wissenschaftliche These ist: Exogen zugeführte Cannabinoide stimulieren das System, das einen Ausgleich der ausgeschütteten Botenstoffe anstrebt. Durch die Entdeckung dieses körpereigenen Systems hat sich das Verständnis der Wissenschaft von CBD und anderer Phytocannabinoide enorm erweitert und weiterführende Forschung angeregt.</p>
      </div>
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS IST DER UNTERSCHIED ZWISCHEN THC UND CBD??</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Das neben CBD bekannteste Cannabinoid ist THC. Es ist für die berauschende Wirkung von Cannabis verantwortlich und in Deutschland verboten. Chemisch betrachtet unterscheiden sich CBD und THC nur minimal in ihrer Struktur. Dennoch unterscheiden sich die beiden Cannabinoide essentiell in ihrer Wirkung. CBD wirkt im Vergleich zu THC nicht berauschend. Weitere Informationen zu CBD-Blüten findet ihr beim CBD-Ratgeber.</p>
      </div>
	  
	  <button class="faq-accordion-archive"><h2 class="accordion_heading">CBD ALS PFLANZLICHE ALTERNATIVE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Die Wenigsten von uns sind im Alltag frei von Beschwerden. In Zeiten des Leistungsdrucks und des ständigen “Funktionieren-Müssens” neigen wir dazu, sie leichtfertig zu ignorieren. Schnelle Abhilfe versprechen massenhaft pharmazeutische Produkte, die nicht frei von Nebenwirkungen sind und immer weniger Vertrauen erfahren. Nicht ohne Grund boomen Naturprodukte so sehr wie noch nie. Natur statt Chemie lautet die Devise. Die wichtigste (Wieder-)Entdeckung der vergangenen Jahre: <a href="https://sanaleo.com/anwendungsfelder-und-vorteile-der-hanfpflanze/" title="Anwendungsgebiete Cannabis">Das Potential von Cannabis.</a></p>
      </div>
		
	<button class="faq-accordion-archive"><h2 class="accordion_heading">WAS SIND TERPENE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Terpene sind kurz gesagt Pflanzeninhaltsstoffe. Sie übernehmen häufig funktionale Eigenschaften und sind in vielen Fällen für den charakteristischen Geruch einer Pflanze verantwortlich. Die aromatische Fülle soll Insekten zur Bestäubung anlocken. Auch die Hanfpflanze enthält neben den zentralen Cannabinoiden eine Vielzahl an Terpenen, die den unverkennbaren Geruch verantworten. Die Auswirkungen, die Terpene auf den Menschen haben, werden bereits in der Aromatherapie genutzt. Wissenschaftliche Erkenntnisse legen nahe, dass Hanfextrakte, die neben CBD das volle Spektrum der natürlichen Terpene der Pflanze enthalten, eine höhere Bioverfügbarkeit aufweisen und somit eine bessere Wirkung entfalten. Man spricht hier vom sog. Entourage-Effekt: Die Wirkung der Pflanze ist größer als die Summe ihrer Bestandteile. Bis heute hat die Forschung die Struktur von etwa 20.000 Terpenen identifiziert und analysiert. Die wichtigsten im Zusammenhang mit CBD sind: B-Caryophyllene, Limonene, Linalool, Myrzene und Pinene. Das Zusammenspiel der Terpene birgt enormes Potential, das es verstehen und richtig anzuwenden gilt! Weitere Informationen zu den genannten Terpenen sind im <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Was_Sind_Terpene" title="CBD Wiki - alle Informationen zu CBD">CBD-Wiki</a> zu finden.</p>
      </div>
      ';
  }

  else if(is_product_category('cbd-oele')){
    echo '
      <button class="faq-accordion-archive" style="text-align: center;"><h2 class="accordion_heading" style="font-size: 1em; margin-bottom: 0px;">WIE WERDEN SANALEO-PREMIUM-CBD-ÖLE HERGESTELLT?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Die angebotenen CBD-Öle werden aus getrockneten Cannabisblüten mit einem <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Wieso_haben_EU-Nutzhanfsorten_unterschiedlich_hohe_CBD-Gehalte" title="CBD Gehalt von Cannabisblüten">natürlicherweise hohen CBD-Gehalt hergestellt.</a> Die dafür verwendeten Cannabisblüten werden ohne jeglichen Einsatz von Herbiziden oder Pestiziden angebaut. Nach der Ernte und dem Trocknungsprozess werden die Cannabisblüten mithilfe eines einzigartigen Verfahren extrahiert, bei dem eine sehr hohe Ausbeute erreicht wird. Nach der Extraktion ist zudem kein Einsatz von Lösungsmitteln erforderlich. Zuletzt wird das Cannabisextrakt mit einer natürlichen Ölbasis vermengt.</p>
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
      </div>

      <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS SIND TERPENE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
          <p>Terpene sind kurz gesagt Pflanzeninhaltsstoffe. Sie übernehmen häufig funktionale Eigenschaften und sind in vielen Fällen für den charakteristischen Geruch einer Pflanze verantwortlich. Die aromatische Fülle soll Insekten zur Bestäubung anlocken. Auch die Hanfpflanze enthält neben den zentralen Cannabinoiden eine Vielzahl an Terpenen, die den unverkennbaren Geruch verantworten. Die Auswirkungen, die Terpene auf den Menschen haben, werden bereits in der Aromatherapie genutzt. Wissenschaftliche Erkenntnisse legen nahe, dass Hanfextrakte, die neben CBD das volle Spektrum der natürlichen Terpene der Pflanze enthalten, eine höhere Bioverfügbarkeit aufweisen und somit eine bessere Wirkung entfalten. Man spricht hier vom sog. Entourage-Effekt: Die Wirkung der Pflanze ist größer als die Summe ihrer Bestandteile. Bis heute hat die Forschung die Struktur von etwa 20.000 Terpenen identifiziert und analysiert. Die wichtigsten im Zusammenhang mit CBD sind: B-Caryophyllene, Limonene, Linalool, Myrzene und Pinene. Das Zusammenspiel der Terpene birgt enormes Potential, das es verstehen und richtig anzuwenden gilt! Weitere Informationen zu den genannten Terpenen sind im <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Was_Sind_Terpene" title="CBD Wiki - alle Informationen zu CBD">CBD-Wiki</a> zu finden.</p>
      </div>

      <button class="faq-accordion-archive"><h2 class="accordion_heading">WIESO HABEN EU-NUTZHANFSORTEN UNTERSCHIEDLICH HOHE CBD-GEHALTE?</h2><i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Verschiedene Produkte derselben Kategorie weisen unterschiedliche CBD-Gehalte auf. Vornehmlich bei CBD-Blüten finden wir geringe und vergleichsweise sehr hohe CBD-Anteile. Auch einzelne Chargen derselben Sorte variieren in den Cannabinoidwerten. Doch weshalb ist das so?</p>
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
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WIE NEHME ICH MEIN SANALEO-VAPE-STARTERKIT IN BETRIEB?</h2>
    <i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Lade den Pen vor der ersten Nutzung sicherheitshalber noch einmal auf. Wenn er vollständig geladen ist, leuchtet das grüne Licht durchgängig. Verbinde anschließend deine SANALEO-Vape CBD-Kartusche mit dem SANALEO-Vape-Pen und ziehe daran. Normalerweise sollte es direkt zu einer Dampfbildung kommen, sobald du an dem Pen ziehst. 

      Wichtig ist, dass Du gleichmäßige und nicht zu starke Züge nimmst. Andernfalls könnten die hohen Temperaturen dafür sorgen, dass wichtige Terpene zerstört werden, die im Sinne des Entourage-Effekts unbedingt erhalten werden sollten.
      
      Ob die Kartusche permanent auf dem Pen bleibt oder nicht, ist Dir überlassen. Er versetzt sich automatisch in den Standby-Modus, solange länger nicht daran gezogen wurde. Der Pen muss nicht zwangsläufig nach einer bestimmten Zeit entsorgt oder ausgetauscht werden.
      </p>
    </div>
    
  <button class="faq-accordion-archive"><h2 class="accordion_heading">WANN MUSS ICH MEINEN VAPE-PEN LADEN? WANN IST MEIN VAPE-PEN GELADEN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Wenn der Pen geladen werden muss, blinkt das grüne Licht in regelmäßigen Abständen. Wenn er geladen ist, leuchtet das Licht durchgängig.
      </p>   
    </div>
    <button class="faq-accordion-archive"><h2 class="accordion_heading">WAS SIND TERPENE?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Terpene sind kurz gesagt Pflanzeninhaltsstoffe. Sie übernehmen häufig funktionale Eigenschaften und sind in vielen Fällen für den charakteristischen Geruch einer Pflanze verantwortlich. Die aromatische Fülle soll Insekten zur Bestäubung anlocken. Auch die Hanfpflanze enthält neben den zentralen Cannabinoiden eine Vielzahl an Terpenen, die den unverkennbaren Geruch verantworten. Die Auswirkungen, die Terpene auf den Menschen haben, werden bereits in der Aromatherapie genutzt. Wissenschaftliche Erkenntnisse legen nahe, dass Hanfextrakte, die neben CBD das volle Spektrum der natürlichen Terpene der Pflanze enthalten, eine höhere Bioverfügbarkeit aufweisen und somit eine bessere Wirkung entfalten. Man spricht hier vom sog. Entourage-Effekt: Die Wirkung der Pflanze ist größer als die Summe ihrer Bestandteile. Bis heute hat die Forschung die Struktur von etwa 20.000 Terpenen identifiziert und analysiert. Die wichtigsten im Zusammenhang mit CBD sind: B-Caryophyllene, Limonene, Linalool, Myrzene und Pinene. Das Zusammenspiel der Terpene birgt enormes Potential, das es verstehen und richtig anzuwenden gilt! Weitere Informationen zu den genannten Terpenen sind im <a href="https://sanaleo.com/cbd-was-ist-das-uberhaupt/#Was_Sind_Terpene" title="CBD Wiki - alle Informationen zu CBD">CBD-Wiki</a> zu finden.</p>
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
      Wegen der geringen Studienlage raten wir Schwangeren vom Genuss von CBD-Produkten ab.
      </p>   
    </div>
    ';
    
   }


   else if(is_product_category('cbd-kapseln')){
    echo '
    <button class="faq-accordion-archive"><h2 class="accordion_heading">KANN MAN DIE TRAUMKAPSELN ÜBERDOSIEREN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>Eine Überdosierung durch unsere Traumkapseln ist ausgeschlossen, da sie keine berauschende Wirkung besitzen.
      </p>   
    </div>

    <button class="faq-accordion-archive"><h2 class="accordion_heading">IST IN DEN TRAUMKAPSELN WIRKLICH MELATONIN DRIN?</h2><i class="faq-archive-info-open"></i></button>
    <div class="panel-archive">
      <p>
      Unsere CBD Schlafkapseln bestehen aus Steinpilzpulver, Melissenextrakt, Cannabidiol, Traubenzucker und ja, einer kleinen Prise Melatonin. Die Menge an Melatonin ist jedoch gerade so gering, dass sie gänzlich unbedenklich ist, aber dennoch ihre Wirkung entfaltet.
      </p>   
    </div>
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
      Wegen der geringen Studienlage raten wir Schwangeren vom Genuss von CBD-Produkten ab.
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


