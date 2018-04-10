<?php
// Importer AJAX Action
add_action('wp_ajax_nk_demo_import_action', 'nk_demo_import_action');
function nk_demo_import_action () {
    if(!current_user_can('manage_options') || !function_exists('nk_theme')) {
        return;
    }

    $demo_name = 'dark';
    if (isset($_POST['demo_name']) && trim($_POST['demo_name']) != '') {
        $demo_name = $_POST['demo_name'];
    }

    $demo_path = nk_admin()->admin_path . '/demos/' . $demo_name;
    $import_data_file = $demo_path . '/content.xml';
    $import_widgets_file = $demo_path . '/widgets.json';
    $import_options_file = $demo_path . '/theme_options.txt';
    $main_page_title = 'Main';
    $import_rev_sliders = false;
    if($demo_name === 'dark' && class_exists('RevSlider')) {
        $import_rev_sliders = array(
            $demo_path . '/products_slider.zip'
        );
    }

    // remove old menus
    $delete_menus = wp_get_nav_menus();
    foreach ($delete_menus as $delete_menu) {
        if (is_nav_menu($delete_menu->term_id)) {
            wp_delete_nav_menu($delete_menu->term_id);
        }
    }

    // import all demo data
    echo '<br><h4>Demo Data:</h4>';
    nk_theme()->demo_importer()->import_demo_data($import_data_file);

    // setup widgets
    echo '<br><h4>Widgets:</h4>';
    nk_theme()->demo_importer()->import_demo_widgets($import_widgets_file);

    // options tree importer
    echo '<br><h4>Theme Options:</h4>';
    nk_theme()->demo_importer()->import_demo_options_tree($import_options_file);


    // Rev Slider
    if (is_array($import_rev_sliders) && method_exists(nk_theme()->demo_importer(), 'import_rev_slider')) {
        echo '<br><h4>Revolution Sliders:</h4>';
        nk_theme()->demo_importer()->import_rev_slider($import_rev_sliders);
    }

    // setup menus
    $locations = get_theme_mod('nav_menu_locations');
    $menus = wp_get_nav_menus();
    if ($menus) {
        echo '<br><h4>Menus:</h4>';
        foreach($menus as $menu) {
            switch($menu->name) {
                case 'Main Menu':
                    $locations['primary'] = $menu->term_id;
                    echo '<p>Added menu: ' . $menu->name . '</p>';
                    break;
                case 'Right Menu':
                    $locations['primary-right'] = $menu->term_id;
                    echo '<p>Added menu: ' . $menu->name . '</p>';
                    break;
            }
        }
    }
    set_theme_mod('nav_menu_locations', $locations);


    echo '<br><h4>Blog Settings:</h4>';
    // change some settings
    // permalink
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );

    // home page
    $homepage = get_page_by_title($main_page_title);
    if (isset($homepage) && $homepage->ID) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $homepage->ID);
    }
    echo '<p>Imported</p>';


    // default WooCommerce settings
    if(function_exists('is_woocommerce')) {
        echo '<br><h4>WooCommerce Settings:</h4>';
        update_option('shop_catalog_image_size', array(
            'width'  => 500,
            'height' => 375,
            'crop'   => 1
        ));
        update_option('shop_single_image_size', array(
            'width'  => 1600,
            'height' => 1000,
            'crop'   => 0
        ));
        update_option('shop_thumbnail_image_size', array(
            'width'  => 180,
            'height' => 135,
            'crop'   => 1
        ));
        $woo_shop = get_page_by_title('Shop');
        $woo_cart = get_page_by_title('Basket');
        $woo_checkout = get_page_by_title('Checkout');
        $woo_account = get_page_by_title('My Account');
        if(isset($woo_shop)) {
            update_option('woocommerce_shop_page_id', $woo_shop->ID);
        }
        if(isset($woo_cart)) {
            update_option('woocommerce_cart_page_id', $woo_cart->ID);
        }
        if(isset($woo_checkout)) {
            update_option('woocommerce_checkout_page_id', $woo_checkout->ID);
        }
        if(isset($woo_account)) {
            update_option('woocommerce_myaccount_page_id', $woo_account->ID);
        }
        echo '<p>Imported</p>';
    }

    die();
}
