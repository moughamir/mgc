<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
$uid = get_current_user_ID();
$noti_count = (int)vp_comment_noti_count();
add_filter( 'get_comment_text', 'vp_format_dash_comments', 10, 3 );
?>
<div class="row">
	<div class="col-lg-12">
    	<?php if($noti_count){?>
        <div class="alert alert-info"><?php echo sprintf( __( 'You received %d new comment(s) approved since your last visit to this page', 'viralpress' ), $noti_count ) ?></div>
        <?php 
			update_user_meta( $uid, 'vp_comment_noti_count', 0 );
		}
        ?>
        <?php
		vp_print_comments( $uid, 'on_post', 1 );
		?>
    </div>
</div>
<script>jQuery('.entry-title').eq(0).hide();</script>