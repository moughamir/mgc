<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
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
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="col2-set" id="customer_login">

	<div class="col-1">

<?php endif; ?>

		<h2><?php _e( 'Login', 'youplay' ); ?></h2>

		<form method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<label for="username"><?php _e( 'Username or email address', 'youplay' ); ?> <span class="required">*</span></label>
			<div class="youplay-input">
				<input type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
			</div>

			<label for="password"><?php _e( 'Password', 'youplay' ); ?> <span class="required">*</span></label>
			<div class="youplay-input">
				<input class="input-text" type="password" name="password" id="password" />
			</div>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<span class="btn btn-default">
				<?php _e( 'Login', 'youplay' ); ?>
				<input type="submit" style="opacity: 0;position: absolute;left: 0;top: 0;right: 0;bottom: 0;width: 100%;" class="button" name="login" value="<?php _e( 'Login', 'youplay' ); ?>" />
			</span>

			<div class="youplay-checkbox dib ml-15">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
				<label for="rememberme" style="line-height: normal;"><?php _e( 'Remember me', 'youplay' ); ?></label>
			</div>

			<p class="lost_password">
				<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'youplay' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	</div>

	<div class="col-2">

		<h2><?php _e( 'Register', 'youplay' ); ?></h2>

		<form method="post">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<label for="reg_username"><?php _e( 'Username', 'youplay' ); ?> <span class="required">*</span></label>
				<div class="youplay-input">
					<input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</div>

			<?php endif; ?>

				<label for="reg_email"><?php _e( 'Email address', 'youplay' ); ?> <span class="required">*</span></label>
				<div class="youplay-input">
					<input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
				</div>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<label for="reg_password"><?php _e( 'Password', 'youplay' ); ?> <span class="required">*</span></label>
				<div class="youplay-input">
					<input type="password" class="input-text" name="password" id="reg_password" />
				</div>

			<?php endif; ?>

			<!-- Spam Trap -->
			<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'youplay' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>
			<?php do_action( 'register_form' ); ?>

			<?php wp_nonce_field( 'woocommerce-register' ); ?>
			<span class="btn btn-default">
				<?php _e( 'Register', 'youplay' ); ?>
				<input type="submit" style="opacity: 0;position: absolute;left: 0;top: 0;right: 0;bottom: 0;width: 100%;" class="button" name="register" value="<?php _e( 'Register', 'youplay' ); ?>" />
			</span>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
