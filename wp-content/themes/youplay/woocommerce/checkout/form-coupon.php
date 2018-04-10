<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'youplay' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'youplay' ) . '</a>' );
wc_print_notice( $info_message, 'notice' );
?>

<form class="checkout_coupon" method="post" style="display:none; padding: 0; border: none;">

	<div class="form-row form-row-first">
		<div class="youplay-input mb-0">
      <input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'youplay' ); ?>" id="coupon_code" value="" />  
    </div>
	</div>

	<div class="form-row form-row-last">
    <button class="btn btn-default" type="submit" name="apply_coupon"><?php _e( 'Apply Coupon', 'youplay' ); ?></button>
	</div>

	<div class="clear"></div>
</form>
