<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Youplay
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php if ( yp_opts('general_favicon') ): ?>
        <link rel="shortcut icon" href="<?php echo esc_url(yp_opts('general_favicon')); ?>" />
    <?php endif; ?>

    <style><?php yp_opts_e('general_custom_css'); ?></style>
    <script><?php yp_opts_e('general_custom_js'); ?></script>

    <?php youplay_print_open_graph_meta(); ?>

    <?php wp_head(); ?>
</head>

<?php
$parallax_background = '';
if(yp_opts('general_background', true) && yp_opts('general_background_parallax', true)) {
    $parallax_scroll = yp_opts('general_background_parallax', true);
    $parallax_background = 'data-start="background-position: 50% ' . esc_attr($parallax_scroll) . ';" data-end="background-position: 50% 0px;"';
}
?>

<body <?php body_class(); ?> <?php echo $parallax_background; ?>>


    <?php if ( yp_opts('general_preloader') ): ?>
        <!-- Preloader -->
        <div class="page-preloader preloader-wrapp">
            <?php if ( yp_opts('general_preloader_logo') ): ?>
                <img src="<?php echo esc_url(yp_opts('general_preloader_logo')); ?>" alt="">
            <?php endif; ?>
            <div class="preloader"></div>
        </div>
        <!-- /Preloader -->
    <?php endif; ?>

    <?php if ( yp_opts('navigation_show') ): ?>
        <!-- Navbar -->
        <nav class="navbar-youplay navbar navbar-default navbar-fixed-top <?php echo yp_opts('navigation_small_size') ? 'navbar-small' : ''; ?>">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="off-canvas" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php if ( yp_opts('general_logo') && yp_opts('navigation_logo') ): ?>
                        <a class="navbar-brand" href="<?php echo esc_url(home_url()); ?>">
                            <img src="<?php echo esc_url(yp_opts('general_logo')); ?>" alt="">
                        </a>
                    <?php endif; ?>
                </div>

                <div id="navbar" class="navbar-collapse collapse">
                    <?php wp_nav_menu(array(
                        'theme_location'  => 'primary',
                        'container'       => '',
                        'menu_class'      => 'nav navbar-nav',
                        'walker'          => new nk_walker()
                    ) ); ?>

                    <?php if(yp_opts('navigation_cart') && class_exists('woocommerce') && function_exists('woocommerce_mini_cart')):
                        $cart_contents_count = WC()->cart->get_cart_contents_count();
                        $show_count = $cart_contents_count > 0 ? '' : 'style="display: none;"';
                    ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown dropdown-hover dropdown-cart">
                                <a href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php if(yp_opts('navigation_cart_icon')): ?>
                                        <i class="fa fa-shopping-cart"></i>
                                    <?php endif; ?>

                                    <?php if(yp_opts('navigation_cart_count')): ?>
                                        <span class="nav_products_count badge bg-default mnl-10" <?php echo $show_count; ?>>
                                            <?php echo intval($cart_contents_count); ?>
                                        </span>
                                        &zwnj;
                                    <?php endif; ?>

                                    <?php if(yp_opts('navigation_cart_total')): ?>
                                        <span class="ml-5">
                                            <?php echo WC()->cart->get_cart_subtotal(); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                                <div class="dropdown-menu pb-20" style="width: 300px;">
                                    <div class="widget_shopping_cart_content">
                                        <?php woocommerce_mini_cart(); ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    <?php endif; ?>

                    <?php if(yp_opts('navigation_search')) : ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="search-toggle"><a href="javascript:void(0)" role="button" aria-expanded="false"><span class="fa fa-search"></span></a></li>
                        </ul>
                    <?php endif; ?>

                    <?php if ( function_exists( 'login_with_ajax' ) && yp_opts('navigation_login') ) : ?>
                        <?php
                            $username = '';

                            if(yp_opts('navigation_login_name')) {
                                $username = wp_get_current_user();
                                $username = $username->data ? $username->display_name : '';
                            }
                        ?>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown dropdown-hover dropdown-cart">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-user"></i>
                                    <?php echo esc_html($username); ?>
                                </a>
                                <div class="dropdown-menu pb-20" style="width: 300px;">
                                    <div class="block-content m-20 mnb-10 mt-0">
                                        <?php
                                        login_with_ajax(array(
                                            "profile_link" => 1
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    <?php endif; ?>

                    <?php wp_nav_menu(array(
                        'theme_location'  => 'primary-right',
                        'container'       => '',
                        'menu_class'      => 'nav navbar-nav navbar-right',
                        'walker'          => new nk_walker()
                    ) ); ?>
                </div>
            </div>
        </nav>
        <!-- /Navbar -->
    <?php endif; ?>
