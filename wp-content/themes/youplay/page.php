<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Youplay
 */

$side = strpos(yp_opts('single_page_layout', true), 'side-cont') !== false
					? 'left'
					: (strpos(yp_opts('single_page_layout', true), 'cont-side') !== false
					  ? 'right'
					  : false);
$boxed_cont = yp_opts('single_page_boxed_cont', true);
$banner = strpos(yp_opts('single_page_layout', true), 'banner') !== false;
$banner_cont = yp_opts('single_page_banner_cont', true);
$rev_slider = yp_opts('single_page_revslider', true) && function_exists('putRevSlider');
$rev_slider_alias = yp_opts('single_page_revslider_alias', true);

$show_title = yp_opts('single_page_show_title', true)?'':'style="display:none;"';
$no_padding = yp_opts('single_page_nopadding', true)?'mt-0 mb-0':'';

get_header();

if($rev_slider) {
	putRevSlider($rev_slider_alias);
	$banner = true;
}

?>

  <section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
		<?php
			// check if layout with banner
			if ($banner && !$rev_slider) {
				echo do_shortcode('[yp_banner img_src="' . yp_opts('single_page_banner_image', true) . '" img_size="1400x600" banner_size="' . yp_opts('single_page_banner_size', true) . '" parallax="' . yp_opts('single_page_banner_parallax', true) . '" top_position="true"]' . ($banner_cont?wp_kses_post($banner_cont):'<h1 class="h2" ' . $show_title . '>' . get_the_title() . '</h1>') . '[/yp_banner]');
			} else if(!$rev_slider) {
				the_title('<h1 class="' . ($boxed_cont?'container':'') . '" ' . $show_title . '>', '</h1>');
			}
		?>

		<div class="<?php echo yp_sanitize_class(($boxed_cont?'container ':'container-fluid ') . $no_padding . ' youplay-content'); ?> ">
            <div class="row">
    			<?php
    				// check if left sidebar
    				if ($side == 'left') {
    					get_sidebar();
    				}
    			?>

    			<main class="<?php echo yp_sanitize_class($side?'col-md-9':'col-xs-12 p-0'); ?>">

    				<?php while ( have_posts() ) : the_post(); ?>

    					<?php get_template_part( 'template-parts/content', 'page' ); ?>

    					<?php
    						// If comments are open or we have at least one comment, load up the comment template
    						if ( yp_opts('single_page_comments', true) && (comments_open() || get_comments_number()) ) :
    							comments_template();
    						endif;
    					?>

    				<?php endwhile; // end of the loop. ?>

    			</main>

    			<?php
    				// check if right sidebar
    				if ($side == 'right') {
    					get_sidebar();
    				}
    			?>
            </div>
		</div>


<?php get_footer(); ?>
