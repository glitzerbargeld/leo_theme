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

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', PHP_INT_MAX );


/**
 * Enqueue scripts
 */

function child_enqueue_scripts(){
	wp_enqueue_script('sanaleo-animations-js', get_stylesheet_directory_uri() . '/js/animations.js', array(), false, true);
	wp_enqueue_script('rellax-js', get_stylesheet_directory_uri() . '/js/rellax-master/rellax.min.js', array(), false, true);
    wp_enqueue_script('my-rellax', get_stylesheet_directory_uri() . '/js/my-rellax.js', array(), false, true);
    wp_enqueue_script('replacement', get_stylesheet_directory_uri() . '/js/replacement.js', array(), false, true);
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array(), false, true);
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_script('jquery-ui-touchpunch', get_stylesheet_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array(), false, true);

    
}

add_action( 'wp_enqueue_scripts', 'child_enqueue_scripts');

include_once( get_stylesheet_directory() .'/woocommerce/product_hooks.php');

/* 
 * Code-Snippets aus altem Shop
 * 
 * */

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

/*
function customjs_load()
{
    if (is_page(40) or is_page(34)) :
   	echo '<script type="application/ld+json" async>
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "Wieso erhalte ich keine Verzehr- bzw. Dosierungsempfehlungen für die CBD Blüten und Tropfen?",
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
    "name": "Werden die CBD Blüten von Sanaleo mit Pestiziden/Herbiziden herangezüchtet?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Unser Sortiment umfasst Outdoor, Indoor und Greenhouse CBD-Blüten aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unser Hersteller arbeitet mit einer professionellen Samenbank zusammen und hat über 15 Jahre beim Anbau von Cannabis. Das ermöglicht es uns eine exklusive Genetik und Qualität anbieten zu können."
    }
	},{
    "@type": "Question",
    "name": "Welche CBD Blüten sind bei uns erhältlich?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Outdoor: Cheese, V1, Black Domina. Indoor: Skywalker OG, Jack Herer, Gorilla Glue, Litani Hash, Caramello Hash, Vanilla Kush, Amnesia, Strawberry Haze, Super Lemon Haze, Watermelon Cookies, Pineapple Express. Green House: Mango Kush. Außerdem können die Analysezertifikate zu jeder Blüte auf der entsprechenden Produktdetailseite betrachtet werden."
    }
	},{
    "@type": "Question",
    "name": "Welche CBD Konzentration ist die Richtige für mich?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Auf diese Frage gibt es keine eindeutige Antwort. Die Forschung zu CBD und weiteren Cannabinoiden steckt noch in den Kinderschuhen, weshalb auch Dosierungsrichtlinien noch nicht umfassend geklärt sind und von individuellen Faktoren abhängen."
    }
	}
  ]
}
</script>';
	endif;
}

add_action('wp_head', 'customjs_load', 2);

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
 */
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
	echo '<div class = "product-dropdown"><img id="lionhead" class="alignnone size-full wp-image-11" src="https://sanaleo.com/wp-content/uploads/2021/04/CBD-Oele-CBD-Blueten-CBD-Vape-Produtke-Sanaleo-CBD-loewenkopf.png" alt="" width="50px" /><button id="product-menu-btn">Produkte</button>';

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
deactivate_plugins( '/wp-content/plugins/wp-contact-form-7.php' );
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



add_filter( 'get_terms', 'ts_get_subcategory_terms', 10, 3 );
    function ts_get_subcategory_terms( $terms, $taxonomies, $args ) {
          $new_terms = array();
          // if it is a product category and on the shop page
          if ( in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_shop() ) {
             foreach ( $terms as $key => $term ) {
                 if ( ! in_array( $term->slug, array( 'buds', 'oil', 'food', 'schlafkapseln', 'cbd-aromablueten' ) ) ) {        //pass the slug name here
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


/**ADD ACCORDIONS to PRODUCT ARCHIVE*/


add_action('woocommerce_after_shop_loop', 'sana_faq', 50);



function sana_faq(){
  if(is_product_category('cbd-aromablueten')){
    echo '
    <button class="faq-accordion-archive">WIE WERDEN SANALEO-CBD-BLÜTEN ANGEBAUT? <i class="faq-archive-info-open"></i></button>
<div class="panel-archive">
  <p>Unser Sortiment umfasst Outdoor, Indoor und Greenhouse CBD-Blüten aus nachhaltigem Anbau. Beim Anbau werden weder Pestizide, Herbizide oder chemische Düngemittel verwendet. Unsere Hersteller haben allesamt eine langjährige Expertise beim Anbau von Cannabis vorzuweisen. Das ermöglicht es uns, jederzeit einen exklusiven Anspruch hinsichtlich unserer Produkte zu gewährleisten. Freu’ Dich auf beste Qualität.</p>
</div>

<button class="faq-accordion-archive">WELCHE CBD AROMABLÜTEN SIND BEI UNS ERHÄLTLICH?
<i class="faq-archive-info-open"></i></button>
<div class="panel-archive">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>

<button class="faq-accordion-archive">Wer ist CBD? <i class="faq-archive-info-open"></i></button>
<div class="panel-archive">
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
';
  }
  else if(is_product_category('cbd-oele')){
    echo '
    <button class="faq-accordion-archive">WIE WERDEN SANALEO-PREMIUM-CBD-ÖLE HERGESTELLT? <i class="faq-archive-info-open"></i></button>
      <div class="panel-archive">
        <p>Unsere CBD-Öle werden aus getrockneten Cannabisblüten mit einem natürlicherweise hohen CBD-Gehalt hergestellt. Die dafür verwendeten Cannabisblüten werden ohne jeglichen Einsatz von Herbiziden oder Pestiziden angebaut. Nach der Ernte und dem Trocknungsprozess werden die getrockneten Cannabisblüten mithilfe eines einzigartigen Extraktionsverfahren verarbeitet, bei dem eine außergewöhnlich hohe Extraktionsausbeute erreicht wird. Nach der Extraktion ist zudem kein Einsatz von Lösungsmitteln erforderlich. Zuletzt wird das Cannabisextrakt mit einer natürlichen Ölbasis vermengt.</p>
      </div>

      <button class="faq-accordion-archive"> WELCHE CBD-ÖLE SIND BEI UNS ERHÄLTLICH?<i class="faq-archive-info-open"></i></button>

<div class="panel-archive">
  <p>Wie du bestimmt bereits gesehen hast, gibt es bei SANALEO verschiedene CBD-Öle:</p>
  <ul>
  <li>Full Spectrum Öle mit 5% | 15% | 25% CBD
  </li>
  <li>Broad Spectrum Öle mit 5% | 15% | 25% CBD
  </li>
  <li>Sanaleo Unique Collection mit 5% | 15% | 25% CBD
  </li>
  </ul>
  <p>
  Unsere Full Spectrum-Öle und Broad Spectrum-Öle unterscheiden sich zum einen in der Zusammensetzung der einzelnen natürlichen Inhaltsstoffe und zum anderen in ihrer Öl-Basis. Full Spectrum-Öle enthalten alle natürlichen Inhaltsstoffe des für die Herstellung verwendeten Pflanzenmaterials, während in Broad Spectrum-Ölen bestimmte Inhaltsstoffe herausgefiltert wurden.
Zusätzlich bieten wir verschiedene Full Spectrum-Öle an, die von unserer Aroma-Öl-Expertin mit weiteren natürlichen Pflanzenextrakten ergänzt werden. Diese Spezial-Öl-Mischungen werden mit ätherischen Ölen angereichert. Ätherische Öle enthalten Terpene, die den Entourage-Effekt verstärken können.

  </p>
</div>

<button class="faq-accordion-archive">WAS SIND TERPENE?
<i class="faq-archive-info-open"></i></button>
<div class="panel-archive">
  <p>Terpene sind kurz gesagt Pflanzeninhaltsstoffe. Sie übernehmen häufig funktionale Eigenschaften und sind in vielen Fällen für den charakteristischen Geruch einer Pflanze verantwortlich. Die aromatische Fülle soll Insekten zur Bestäubung anlocken. Auch die Hanfpflanze enthält neben den zentralen Cannabinoiden eine Vielzahl an Terpenen, die den unverkennbaren Geruch verantworten. Die Auswirkungen, die Terpene auf den Menschen haben, werden bereits in der Aromatherapie genutzt. Wissenschaftliche Erkenntnisse legen nahe, dass Hanfextrakte, die neben CBD das volle Spektrum der natürlichen Terpene der Pflanze enthalten, eine höhere Bioverfügbarkeit aufweisen und somit eine bessere Wirkung entfalten. Man spricht hier vom sog. Entourage-Effekt: Die Wirkung der Pflanze ist größer als die Summe ihrer Bestandteile. Bis heute hat die Forschung die Struktur von etwa 20.000 Terpenen identifiziert und analysiert. Die wichtigsten im Zusammenhang mit CBD sind: B-Caryophyllene, Limonene, Linalool, Myrzene und Pinene. Das Zusammenspiel der Terpene birgt enormes Potential, das es verstehen und richtig anzuwenden gilt!
  </p>
</div>

<button class="faq-accordion-archive">WIESO HABEN EU-NUTZHANFSORTEN UNTERSCHIEDLICH HOHE CBD-GEHALTE?
<i class="faq-archive-info-open"></i></button>
<div class="panel-archive">
  <p>Verschiedene Produkte derselben Kategorie weisen unterschiedliche CBD-Gehalte auf. Vornehmlich bei CBD-Blüten finden wir geringe und vergleichsweise sehr hohe CBD-Anteile. Auch einzelne Chargen derselben Sorte variieren in den Cannabinoidwerten. Doch weshalb ist das so?</p>
  
  <ol type="1">

  <li>Das Züchten von Hanf ist eine wirkliche Aufgabe. Die Aufgabe wird genau dann zur Kunst, wenn dieselbe Sorte stabil über Jahre gezüchtet und angebaut werden soll.</li> 
  
  <li>Das Verhältnis von THC zu CBD und von CBD zu THC ist nicht beliebig. Demnach spielt der gesetzlich vorgeschriebene THC-Gehalt des Vertriebslandes eine entscheidende Rolle für den CBD-Gehalt. Die unterschiedliche Gesetzeslage innerhalb der EU sorgt also dafür, dass in manchen Ländern (Österreich, Luxemburg) der auf natürlichem Wege erzielbare CBD-Gehalt bei max. 9% liegt, in den meisten Ländern der EU bei max. 6% CBD.</li>
  
  <li>Gute Werte für das Verhältnis THC zu CBD sind bei natürlichem Anbau von EU-Nutzhanf derzeit 1:20 bis 1:30. Das bedeutet, dass maximal der dreißigfache Anteil von CBD zum zugelassenen THC-Grenzwert erzielt werden kann. In Deutschland wären das bei einem Grenzwert von < 0,2% THC ideal gerechnet max. 6% CBD.</li>
  
  </ol>
<p>
  Weshalb gibt es dennoch Anbieter, die Sorten mit 18% CBD bewerben?
  
  In nahezu allen EU-Ländern gibt es aktuell keine einheitlichen Rahmenbedingungen zur Sicherung von Qualitätsstandards. Mit hoher Wahrscheinlichkeit werden die Blüten mit CBD-Isolaten behandelt, die allerdings eine sehr geringe Bioverfügbarkeit aufweisen. Natürlich gewachsene, unbehandelte Cannabinoide besitzen die höchste Bioverfügbarkeit.
</p>
</div>

';
  }
}

