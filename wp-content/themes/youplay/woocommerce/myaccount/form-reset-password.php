<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices(); ?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password">

	<p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'youplay') ); ?></p>

	<div class="form-row form-row-first">
		<p for="password_1"><?php _e( 'New password', 'youplay' ); ?> <span class="required">*</span></p>
		<div class="youplay-input">
			<input type="password" class="input-text" name="password_1" id="password_1" />
		</div>
	</div>
	<div class="form-row form-row-last">
		<p for="password_2"><?php _e( 'Re-enter new password', 'youplay' ); ?> <span class="required">*</span></p>
		<div class="youplay-input">
			<input type="password" class="input-text" name="password_2" id="password_2" />
		</div>
	</div>

	<input type="hidden" name="reset_key" value="<?php echo esc_attr(isset( $args['key'] ) ? $args['key'] : ''); ?>" />
	<input type="hidden" name="reset_login" value="<?php echo esc_attr(isset( $args['login'] ) ? $args['login'] : ''); ?>" />

	<div class="clear"></div>

	<?php do_action( 'woocommerce_resetpassword_form' ); ?>

	<p class="woocommerce-FormRow form-row">
		<input type="hidden" name="wc_reset_password" value="true" />
		<input type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'youplay' ); ?>" />
	</p>
	<div class="form-row">
		<input type="hidden" name="wc_reset_password" value="true" />
		<button type="submit" class="btn btn-default"><?php echo __( 'Save', 'youplay' ); ?></button>
	</div>

	<?php wp_nonce_field( 'reset_password' ); ?>

</form>
