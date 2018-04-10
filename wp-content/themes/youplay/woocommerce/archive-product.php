<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$shop_id = get_option( 'woocommerce_shop_page_id' );
$side = strpos(yp_opts('single_page_layout', true, $shop_id), 'side-cont') !== false
					? 'left'
					: (strpos(yp_opts('single_page_layout', true, $shop_id ), 'cont-side') !== false
					  ? 'right'
					  : false);
$boxed_cont = yp_opts('single_page_boxed_cont', true, $shop_id);
$banner = strpos(yp_opts('single_page_layout', true, $shop_id ), 'banner') !== false;
$banner_cont = yp_opts('single_page_banner_cont', true, $shop_id );
$rev_slider = yp_opts('single_page_revslider', true, $shop_id ) && function_exists('putRevSlider');
$rev_slider_alias = yp_opts('single_page_revslider_alias', true, $shop_id );

get_header( 'shop' );

if($rev_slider) {
	putRevSlider($rev_slider_alias);
	$banner = true;
}
?>

  	<section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
		<?php
			// check if layout with banner
			if ($banner && !$rev_slider) {
				$title = '';
				if($banner_cont) {
					$title = $banner_cont;
				} else if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
					$title = '<h2>' . woocommerce_page_title(false) . '</h2>';
				}

				echo do_shortcode('[yp_banner img_src="' . yp_opts('single_page_banner_image', true, $shop_id) . '" img_size="1400x600" banner_size="' . yp_opts('single_page_banner_size', true, $shop_id) . '" parallax="' . yp_opts('single_page_banner_parallax', true, $shop_id) . '" top_position="true"]' . wp_kses_post($title) . '[/yp_banner]');
			} else if(!$rev_slider) {
				if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<h1 class="<?php echo ($boxed_cont?'container':''); ?>"><?php woocommerce_page_title(); ?></h1>
				<?php endif;
			}
		?>

		<div class="<?php echo ($boxed_cont?'container':'container-fluid'); ?> youplay-store store-grid">
            <div class="row">
    			<?php
    				// check if left sidebar
    				if ($side == 'left') {
    					/**
    					 * woocommerce_sidebar hook
    					 *
    					 * @hooked woocommerce_get_sidebar - 10
    					 */
    					do_action( 'woocommerce_sidebar' );
    				}
    			?>

    			<?php
                    echo '<main class="' . ($side?'col-md-9':'col-xs-12 p-0') . '">';

    				/**
    				 * woocommerce_before_main_content hook
    				 *
    				 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
    				 * @hooked woocommerce_breadcrumb - 20
    				 */
    				if (!yp_opts('shop_show_breadcrumbs')) {
    					remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    				}
    				remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    				do_action( 'woocommerce_before_main_content' );

    				$side = strpos(yp_opts('single_page_layout', true, $shop_id), 'side-cont') !== false
    					? 'left'
    					: (strpos(yp_opts('single_page_layout', true, $shop_id), 'cont-side') !== false
    						? 'right'
    						: false);
    			?>

    			<?php do_action( 'woocommerce_archive_description' ); ?>

    			<?php if ( have_posts() ) : ?>

    				<?php
    					/**
    					 * woocommerce_before_shop_loop hook
    					 *
    					 * @hooked woocommerce_result_count - 20
    					 * @hooked woocommerce_catalog_ordering - 30
    					 */
    					if (!yp_opts('shop_show_result_count')) {
    						remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    					}
    					if (!yp_opts('shop_show_order_by')) {
    						remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    					}
    					do_action( 'woocommerce_before_shop_loop' );
    				?>

    				<?php woocommerce_product_loop_start(); ?>

    					<?php woocommerce_product_subcategories(); ?>

    					<?php while ( have_posts() ) : the_post(); ?>

    						<?php wc_get_template_part( 'content', 'product' ); ?>

    						<?php
    							// If comments are open or we have at least one comment, load up the comment template
    							if ( yp_opts('single_page_comments', true, $shop_id) && (comments_open() || get_comments_number()) ) :
    								comments_template();
    							endif;
    						?>

    					<?php endwhile; // end of the loop. ?>

    				<?php woocommerce_product_loop_end(); ?>

    				<?php
    					/**
    					 * woocommerce_after_shop_loop hook
    					 *
    					 * @hooked woocommerce_pagination - 10
    					 */
    					do_action( 'woocommerce_after_shop_loop' );
    				?>

    			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

    				<?php wc_get_template( 'loop/no-products-found.php' ); ?>

    			<?php endif; ?>

    			<?php
    				/**
    				 * woocommerce_after_main_content hook
    				 *
    				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
    				 */
    				do_action( 'woocommerce_after_main_content' );
    			?>


    			<?php
    				// check if right sidebar
    				if ($side == 'right') {
    					/**
    					 * woocommerce_sidebar hook
    					 *
    					 * @hooked woocommerce_get_sidebar - 10
    					 */
    					do_action( 'woocommerce_sidebar' );
    				}
    			?>
            </div>
		</div>


<?php get_footer( 'shop' ); ?>
