<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Youplay
 */
?>

<div class="col-md-3">
	<?php
    $post_type = get_post_type();
    if(!$post_type) {
        $post_type = 'page';
    }

    // get sidebar from metabox
    $custom_sidebar = yp_opts('single_' . $post_type . '_sidebar', true);

    if( $custom_sidebar ) {
        dynamic_sidebar( $custom_sidebar );
    } else if ( $post_type === 'match' ) {
        dynamic_sidebar( 'matches_sidebar' );
    } else if ( is_active_sidebar('woocommerce_sidebar') && function_exists('is_woocommerce') && is_woocommerce() ) {
        dynamic_sidebar( 'woocommerce_sidebar' );
    } else if ( is_active_sidebar('buddypress_sidebar') && function_exists('is_buddypress') && is_buddypress() ) {
        dynamic_sidebar( 'buddypress_sidebar' );
    } else if ( is_active_sidebar('bbpress_sidebar') && function_exists('is_bbpress') && is_bbpress() ) {
        dynamic_sidebar( 'bbpress_sidebar' );
    } else {
        dynamic_sidebar( 'sidebar-1' );
    }
  ?>
</div>
