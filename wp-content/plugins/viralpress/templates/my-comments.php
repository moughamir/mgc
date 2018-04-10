<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
$uid = get_current_user_ID();
add_filter( 'get_comment_text', 'vp_format_dash_comments', 10, 3 );
?>
<div class="row">
	<div class="col-lg-12">
        <?php
		vp_print_comments( $uid, 'by_me', 1 );
		?>
    </div>
</div>
<script>jQuery('.entry-title').eq(0).hide();</script>