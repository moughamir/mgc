<?php
/**
 * Cart Page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

	<?php do_action( 'woocommerce_before_cart_table' ); ?>
	
	<?php do_action( 'woocommerce_before_cart_contents' ); ?>

	<?php
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>
			<div class="item angled-bg">
			  <div class="row">
			    <div class="col-lg-2 col-md-3 col-xs-4">
			      <div class="angled-img">
		          <?php
		            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
		            $discount = yp_woo_discount_badge( $_product );

		            if ( ! $_product->is_visible() ) {
		              echo '<div class="img">' . $thumbnail . $discount . '</div>';
		            } else {
		              printf( '<a href="%s" class="img" style="display: block;">%s %s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail, $discount );
		            }
		          ?>
			      </div>
			    </div>
			    <div class="col-lg-10 col-md-9 col-xs-8">
			      <div class="row">
			        <div class="col-xs-6 col-md-9">
			          <h4>
			            <?php
			              if ( ! $_product->is_visible() ) {
			                echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . ($cart_item['quantity']>1 ? '<small> * ' . $cart_item['quantity'] . '</small>' : '');
			              } else {
			                echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s </a>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() ), $cart_item, $cart_item_key ) . ($cart_item['quantity']>1 ? '<small> * ' . $cart_item['quantity'] . '</small>' : '');
			              }
			            ?>
			          </h4>

			          <?php
			            // Meta data
			            echo WC()->cart->get_item_data( $cart_item );

			            // Backorder notification
			            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
			              echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'youplay' ) . '</p>';
			            }
			          ?>

			          <?php echo yp_get_rating( $_product->get_average_rating() ); ?>
			        </div>
			        <div class="col-xs-6 col-md-3 align-right">
			          <div class="price">
			            <?php
			              echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
			            ?>
			          </div>
			          <?php
			          	$isRTL = yp_opts('general_rtl');
			            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="glyphicon glyphicon-remove" style="font-size: 1.7rem; margin-top: 5px; margin-' . ($isRTL?'left':'right') . ': 20px; text-decoration: none;" title="%s"></a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'youplay' ) ), $cart_item_key );
			          ?>
			        </div>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}

	do_action( 'woocommerce_cart_contents' );
	?>

	<?php if ( WC()->cart->coupons_enabled() ) { ?>
		<div class="coupon">
			<div class="youplay-input dib">
				<input type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'youplay' ); ?>" />
			</div>
			<button type="submit" class="btn btn-default" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'youplay' ); ?>"><?php _e( 'Apply Coupon', 'youplay' ); ?></button>

			<?php do_action( 'woocommerce_cart_coupon' ); ?>
		</div>
	<?php } ?>

	<?php do_action( 'woocommerce_cart_actions' ); ?>

	<?php wp_nonce_field( 'woocommerce-cart' ); ?>

	<?php do_action( 'woocommerce_after_cart_contents' ); ?>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
