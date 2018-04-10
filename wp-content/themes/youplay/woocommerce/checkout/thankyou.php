<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'youplay' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'youplay' );
			else
				_e( 'Please attempt your purchase again.', 'youplay' );
		?></p>

		<div class="btn-group">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn-default"><?php _e( 'Pay', 'youplay' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-default"><?php _e( 'My Account', 'youplay' ); ?></a>
			<?php endif; ?>
		</div>

	<?php else : ?>

		<ul class="order_details h4 pl-0 mb-40">
			<li class="order">
				<?php _e( 'Order Number:', 'youplay' ); ?>
				<strong><?php echo esc_html($order->get_order_number()); ?></strong>
			</li>
			<li>
				<?php _e( 'Date:', 'youplay' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'youplay' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'youplay' ); ?>
				<strong><?php echo esc_html($order->payment_method_title); ?></strong>
			</li>
			<?php endif; ?>
		</ul>


		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'youplay' ), $order ); ?></p>

		<div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'youplay' ), null ); ?></p>

<?php endif; ?>
