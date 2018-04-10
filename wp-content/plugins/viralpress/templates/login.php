<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
?>
<!-- Show logged out message if user just logged out -->
<?php if ( $attributes['logged_out'] ) : ?>
	<div class="alert alert-info">
		<?php _e( 'You have been signed out. Would you like to sign in again?', 'viralpress' ); ?>
	</div>
<?php endif; ?> 
<!-- Show errors if there are any -->
<div class="login-error">
<?php if ( !empty( $attributes['errors'] )) : ?>
<?php foreach ( $attributes['errors'] as $error ) : ?>
    <div class="alert alert-danger">
        <?php echo $error;?>
    </div>
    <?php break;?>
<?php endforeach; ?>
<?php endif; ?> 
</div>

<?php if ( $attributes['registered'] ) : ?>
    <div class="alert alert-success">
        <?php
            printf(
                __( 'You have successfully registered to %s. We have emailed your password to the email address you entered.', 'viralpress' ),
                '<strong>'.get_bloginfo( 'name' ).'</strong>'
            );
        ?>
    </div>
<?php endif; ?>

<?php if ( $attributes['lost_password_sent'] ) : ?>
    <div class="alert alert-success">
        <?php _e( 'Check your email for a link to reset your password.', 'viralpress' ); ?>
    </div>
<?php endif; ?>

<?php if ( $attributes['password_updated'] ) : ?>
    <div class="alert alert-success">
        <?php _e( 'Your password has been changed. You can sign in now.', 'viralpress' ); ?>
    </div>
<?php endif; ?>
         
<div class="vp_container_test"></div>            
<div class="row vp_login">
	<div class="col-lg-4"></div>
	<div class="col-lg-4">
        <?php print_login_form( $attributes )?>
	</div>
    <div class="col-lg-4"></div>
</div>
<script>
	jQuery('.entry-title').eq(0).hide();
	jQuery( document ).ready( function( $ ){
		normalize_login_page();
	});
</script>