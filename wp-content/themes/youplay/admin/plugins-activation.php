<?php
/**
 * Youplay Plugins Activation
 *
 * @package Youplay
 */
require nk_admin()->admin_path . '/lib/class-tgm-plugin-activation.php';


/**
 * Register Required Plugins
 */
add_action( 'tgmpa_register', 'youplay_register_required_plugins' );
if ( ! function_exists( 'youplay_register_required_plugins' ) ) :
function youplay_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // nK Themes Helper
        array(
            'name'       => 'nK Themes Helper',
            'slug'       => 'nk-themes-helper',
            'source'     => 'http://a.nkdev.info/wp-plugins/nk-themes-helper.zip',
            'version'   => '1.2.0',
            'required'   => true,
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/nk-themes-helper.png',
        ),

        // Visual Composer
        array(
            'name'       => 'WPBakery Visual Composer',
            'slug'       => 'js_composer',
            'source'     => 'http://a.nkdev.info/wp-plugins/js_composer.zip',
            'required'   => true,
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/js_composer.png',
        ),

        // Rev Slider
        array(
            'name'       => 'Slider Revolution',
            'slug'       => 'revslider',
            'source'     => 'http://a.nkdev.info/wp-plugins/revslider.zip',
            'required'   => false,
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/revslider.png',
        ),

        // YP ClanWars
        array(
            'name'       => 'YP ClanWars',
            'slug'       => 'yp-clanwars',
            'source'     => 'http://a.nkdev.info/wp-plugins/yp-clanwars.zip',
            'required'   => false,
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/yp-clanwars.png',
        ),

        // WooCommerce
        array(
            'name'       => 'WooCommerce',
            'slug'       => 'woocommerce',
            'required'   => false,
            'version'    => '2.5.2',
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/woocommerce.png',
        ),

        // BuddyPress
        array(
            'name'       => 'BuddyPress',
            'slug'       => 'buddypress',
            'required'   => false,
            'version'    => '2.4.3',
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/buddypress.png',
        ),

        // bbPress
        array(
            'name'       => 'bbPress',
            'slug'       => 'bbpress',
            'required'   => false,
            'version'    => '2.5.9',
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/bbpress.png',
        ),

        // Login With Ajax
        array(
            'name'       => 'Login With Ajax',
            'slug'       => 'login-with-ajax',
            'required'   => false,
            'version'    => '3.1.6',
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/login-with-ajax.png',
        ),

        // Facebook Widget
        array(
            'name'       => 'Facebook Widget',
            'slug'       => 'facebook-pagelike-widget',
            'required'   => false,
            'version'    => '4.1',
            'thumbnail'  => nk_admin()->admin_uri . '/assets/images/plugins/facebook-pagelike-widget.png',
        ),

    );

    $config = array(
        'domain'           => 'youplay',
        'default_path'     => '',
        'has_notices'      => true,
        'is_automatic'     => true,
        'message'          => ''
    );

    tgmpa( $plugins, $config );
}
endif;


// Visual Composer as theme
add_action( 'vc_before_init', 'youplay_vc_setastheme' );
function youplay_vc_setastheme() {
    vc_set_as_theme();
}

// Revolution Slider as theme
if(function_exists( 'set_revslider_as_theme' )) {
    add_action( 'init', 'youplay_rev_setastheme' );
    function youplay_rev_setastheme() {
        set_revslider_as_theme();
    }
}
