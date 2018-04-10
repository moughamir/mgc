<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
?>
<?php 
	$post = $attributes['post'];
	$post_id = $post->ID;
	$u = urlencode( get_permalink( $post->ID ) );
	$t = urlencode( $post->post_title ); 
	$e = urlencode( $post->post_excerpt );
	$i = '';
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
	$i = @$thumb['0'];
?>

<h3><?php _e( 'Share this post on social media', 'viralpress' )?></h3>            
<div class="row" style="max-width:500px">
    <div class="col-lg-12">
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $u?>"><div class="fb-share block pointer"></div></a>
        </div>	
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="https://twitter.com/intent/tweet?source=<?php echo $u?>&text=<?php echo $t?>:%20<?php echo $u?>"><div class="tw-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="https://plus.google.com/share?url=<?php echo $u?>"><div class="gp-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="http://www.tumblr.com/share?v=3&u=<?php echo $u?>&t=<?php echo $t?>&s="><div class="tm-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="//pinterest.com/pin/create%2Fbutton/?url=<?php echo $u?>&media=<?php echo $i?>&description=<?php echo $t?>"><div class="pin-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="https://getpocket.com/save?url=<?php echo $u?>&title=<?php echo $t?>"><div class="pk-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="http://www.reddit.com/submit?url=<?php echo $u?>&title=<?php echo $t?>"><div class="rd-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $u?>&title=<?php echo $t?>&source=<?php echo $u?>&summary=<?php echo $e?>"><div class="in-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="http://wordpress.com/press-this.php?u=<?php echo $u?>&t=<?php echo $t?>&s=<?php echo $e?>&i=<?php echo $i?>"><div class="wp-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="https://pinboard.in/popup_login/?url=<?php echo $u?>&title=<?php echo $t?>&description=<?php echo $e?>"><div class="pinb-share block pointer"></div></a>
        </div>
        <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
            <a target="_blank" href="mailto:?subject=<?php echo $t?>&body=<?php echo $e?>: <?php echo $u?>"><div class="email-share block pointer"></div></a>
        </div>
    </div>
</div>