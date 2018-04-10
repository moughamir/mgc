<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Youplay
 */

?>

        <?php if ( !is_404() ) : ?>
            <div class="clearfix"></div>

            <!-- Footer -->
            <footer id="footer" class="<?php echo yp_opts('footer_parallax') ? 'youplay-footer-parallax' : ''; ?>">
                <div class="wrapper"
                    <?php if( yp_opts('footer_show_background') && yp_opts('footer_background') ): ?>
                         style="background-image: url(<?php echo esc_url(yp_opts('footer_background')); ?>)"
                    <?php endif; ?>
                >

                    <?php if(yp_opts('footer_widgets')): ?>
                        <div class="widgets">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php dynamic_sidebar('footer_widgets_1'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php dynamic_sidebar('footer_widgets_2'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php dynamic_sidebar('footer_widgets_3'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php dynamic_sidebar('footer_widgets_4'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(yp_opts('footer_social')): ?>
                        <!-- Social Buttons -->
                        <div class="social">
                            <div class="container">

                                <?php echo wp_kses_post(yp_opts('footer_social_text')); ?>

                                <?php
                                    // chech how many social buttons anabled and create additional offset or hide all buttons
                                    $social_buttons = 4;
                                    if(!yp_opts('footer_social_fb')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_tw')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_gp')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_yt')) {
                                        $social_buttons--;
                                    }
                                ?>

                                <?php if($social_buttons != 0): ?>
                                    <div class="social-icons">
                                        <?php if(yp_opts('footer_social_fb')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_fb_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_fb_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_fb_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_tw')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_tw_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_tw_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_tw_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_gp')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_gp_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_gp_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_gp_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_yt')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_yt_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_yt_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_yt_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <!-- /Social Buttons -->
                    <?php endif; ?>

                    <!-- Copyright -->
                    <div class="copyright">
                        <div class="container">
                            <?php echo wp_kses_post(yp_opts('footer_text')); ?>
                        </div>
                    </div>
                    <!-- /Copyright -->

                </div>
            </footer>
            <!-- /Footer -->
        <?php endif; ?>

    </section>
    <!-- /Content -->


    <!-- Search Block -->
    <div class="search-block">
        <a href="#" class="search-toggle glyphicon glyphicon-remove"></a>
        <?php get_search_form(); ?>
    </div>
    <!-- /Search Block -->

    <!-- init youplay -->
    <script>
    jQuery(function() {
        if(typeof youplay !== 'undefined') {
            youplay.init({
                // enable parallax
                parallax:         <?php echo (yp_opts('general_parallax')?'true':'false'); ?>,

                // set small navbar on load
                navbarSmall:      false,

                // enable fade effect between pages
                fadeBetweenPages: <?php echo (yp_opts('general_fade_between_pages') && yp_opts('general_preloader')?'true':'false'); ?>,

                // twitter and instagram php paths (no need for WordPress version)
                php: {
                    twitter: true,
                    instagram: true
                }
            });
        }
    })
    </script>
    <!-- /init youplay -->

    <?php wp_footer();?>
</body>
</html>
