<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php 
	astra_content_after();
		
	astra_footer_before();
		
	astra_footer();
		
	astra_footer_after(); 
?>
	</div><!-- #page -->
<?php 
	astra_body_bottom();    
	wp_footer(); 
?>
<script>
    var rellax = new Rellax('.rellax-bottle', {
    speed: -1,
    center: true,
    wrapper: null,
    round: true,
    vertical: true,
    horizontal: false
  });

  var rellax = new Rellax('.rellax-cap', {
    speed: 1,
    center: true,
    wrapper: null,
    round: true,
    vertical: true,
    horizontal: false
  });

  var rellax = new Rellax('.rellax-faq', {
    speed: 3,
    center: true,
    wrapper: null,
    round: true,
    vertical: true,
    horizontal: false
  });
  
</script>

	</body>
</html>
