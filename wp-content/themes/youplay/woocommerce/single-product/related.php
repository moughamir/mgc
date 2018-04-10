<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}

$related = $product->get_related( $posts_per_page );

if ( sizeof( $related ) == 0 ) return;
?>

	<h2 class="container"><?php _e( 'Related Products', 'youplay' ); ?></h2>

	<?php 
	$relatedProds = "";
	foreach($related as $id) {
		if($id != $product->id) {
			$relatedProds .= $id . ",";
		}
	}
	?>

	<?php echo do_shortcode('[yp_posts_carousel show_rating="true" show_price="true" posts="' . $relatedProds . '"]'); ?>

<?php wp_reset_postdata();
