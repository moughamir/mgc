<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

// Attchment images gallery
$attachment_imgs = $product->get_gallery_attachment_ids();

if( count( $attachment_imgs ) > 0 ) {
  $carousel = '';
  foreach($attachment_imgs as $img) {
  	$carousel .= '[yp_carousel_img img_src="' . $img . '"]';
  }

  echo do_shortcode('[yp_carousel style="3"]' . $carousel . '[/yp_carousel]');
}
?>