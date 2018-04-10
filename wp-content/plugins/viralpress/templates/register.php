<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
?>
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

<div class="vp_container_test"></div>
<div class="row vp_login">
	<div class="col-lg-4"></div>
	<div class="col-lg-4">
        <?php print_register_form( $attributes )?>
	</div>
    <div class="col-lg-4"></div>
</div>
<script>
	jQuery('.entry-title').eq(0).hide();
	jQuery( document ).ready( function( $ ){
		normalize_login_page();
	});
</script>