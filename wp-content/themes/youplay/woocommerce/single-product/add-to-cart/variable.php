<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'youplay' ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="pr-20"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>
						<td class="pt-5 pb-5">
							<div class="youplay-select">
								<?php
									$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
									wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
								?>
							</div>
						</td>
					</tr>
					<?php
						echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<tr><td></td><td><a class="reset_variations" href="#">' . __( 'Clear', 'youplay' ) . '</a></td></tr>' ) : '';
					?>
		        <?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap mt-10" style="display:none;">
			<?php
				/**
				 * woocommerce_before_single_variation Hook
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);
				do_action( 'woocommerce_single_variation' );

				/**
				 * Output the add to cart button for variations.
				 */
				// function woocommerce_single_variation_add_to_cart_button() {
				?>
				<div class="variations_button mt-10">
					<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
					<button type="submit" class="btn btn-default btn"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
					<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
					<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
					<input type="hidden" name="variation_id" class="variation_id" value="" />
				</div>

				<?php

				/**
				 * woocommerce_after_single_variation Hook
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
