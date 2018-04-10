<?php
/**
 * The template for displaying all single matches.
 *
 * @package Youplay
 */
$post = get_post(get_the_ID());

$side = strpos(yp_opts('single_match_layout', true), 'side-cont') !== false
                    ? 'left'
                    : (strpos(yp_opts('single_match_layout', true), 'cont-side') !== false
                      ? 'right'
                      : false);
$boxed_cont = yp_opts('single_match_boxed_cont', true);
$banner = $post->post_excerpt;
$rev_slider = yp_opts('single_match_revslider', true) && function_exists('putRevSlider');
$rev_slider_alias = yp_opts('single_match_revslider_alias', true);

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
                $title = '<h1 class="mb-30">' . get_the_title() . '</h1>';
                if ($banner) {
                    $title = '
                        <div class="row pt-40">
                            <div class="col-xs-12 text-center">
                                ' . $title . '
                            </div>
                        </div>';
                }
                echo do_shortcode('[yp_banner img_src="' . yp_opts('single_match_banner_image', true) . '" img_size="1400x600" banner_size="' . yp_opts('single_match_banner_size', true) . '" parallax="' . yp_opts('single_match_banner_parallax', true) . '" top_position="true"]'
                        . $title
                        . ($banner?wp_kses_post($banner):'')
                    . '[/yp_banner]');
            } else if (!$rev_slider) {
                the_title('<h1 class="' . ($boxed_cont?'container':'') . '">', '</h1>');
            }
        ?>

        <div class="<?php echo yp_sanitize_class($boxed_cont?'container':'container-fluid'); ?> youplay-news youplay-post">
            <div class="row">
                <?php
                    // check if left sidebar
                    if ($side == 'left') {
                        get_sidebar();
                    }
                ?>

                <main class="<?php echo yp_sanitize_class($side?'col-md-9':'col-xs-12 p-0'); ?>">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'template-parts/content', 'single-match' ); ?>

                        <?php
                            // If comments are open or we have at least one comment, load up the comment template
                            if ( yp_opts('single_match_comments', true) && (comments_open() || get_comments_number()) ) :
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
