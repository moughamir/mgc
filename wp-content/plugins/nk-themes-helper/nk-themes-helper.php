<?php
/**
 * Plugin Name:  nK Themes Helper
 * Description:  Helper for nK themes
 * Version:      1.3.8
 * Author:       nK
 * Author URI:   http://nkdev.info
 * License:      GPLv2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  nk-themes-helper
 */

define('NK_HELPER_DOMAIN', 'nk-themes-helper');

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


add_action('plugins_loaded', 'nk_helper_load_textdomain');
function nk_helper_load_textdomain() {
    load_plugin_textdomain( 'nk-themes-helper', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}


/**
 * Include SCSS Compiler
 */
require_once(plugin_dir_path(__FILE__) . 'inc/scssphp/scss.inc.php');
use Leafo\ScssPhp\Compiler;


/**
 * Rewrite rules on activation and deactivation plugin for portfolio post type support
 */
function nk_helper_rewrite_rules_portfolio () {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'nk_helper_rewrite_rules_portfolio');
register_deactivation_hook(__FILE__, 'nk_helper_rewrite_rules_portfolio');


/**
 * nK Theme Helper Class
 */
if (!class_exists('nK')) :
class nK {
    /**
     * The single class instance.
     */
    private static $_instance = null;

    /**
    * Main Instance
    * Ensures only one instance of this class exists in memory at any one time.
    */
    public static function instance () {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public $upload_dir;
    public $compile_folder;
    public $compile_path;
    public $compile_url;

    public $plugin_path;
    public $plugin_url;
    public $plugin_name;
    public $plugin_version;
    public $plugin_slug;
    public $plugin_name_sanitized;

    public function __construct() {
        $this->init_options();
        $this->init_hooks();
    }

    public function init_options() {
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->plugin_url = plugin_dir_url(__FILE__);

        // prepare variables for compilation
        $this->compile_folder = 'nk-custom-styles';
        $this->upload_dir = wp_upload_dir();
        $this->compile_path = trailingslashit($this->upload_dir['basedir']) .  $this->compile_folder;
        $this->compile_url = trailingslashit($this->upload_dir['baseurl']) .  $this->compile_folder;

        // create compilation folder
        if (!is_dir(trailingslashit($this->compile_path))) {
            mkdir($this->compile_path, 0777, true);
        }

        // include helper files
        $this->include_dependencies();

        // clear caches
        $this->clear_expired_caches();
    }

    public function init_hooks() {
        add_action('admin_init', array($this, 'admin_init'));
    }

    public function admin_init () {
        // get current plugin data
        $data = get_plugin_data(__FILE__);
        $this->plugin_name = $data['Name'];
        $this->plugin_version = $data['Version'];
        $this->plugin_slug = plugin_basename(__FILE__, '.php');
        $this->plugin_name_sanitized = basename(__FILE__, '.php');

        // init updater class to plugin updates check
        $this->updater();
    }

    // include
    private function include_dependencies () {
        require_once($this->plugin_path . 'class-demo-importer.php');
        require_once($this->plugin_path . 'class-twitter.php');
        require_once($this->plugin_path . 'class-instagram.php');
        require_once($this->plugin_path . 'class-updater.php');
        require_once($this->plugin_path . 'class-portfolio.php');
        require_once($this->plugin_path . 'class-custom-post-type.php');
    }


    /**
     * Additional Classes
     */
    public function demo_importer () {
        return nK_Helper_Demo_Importer::instance();
    }
    public function twitter () {
        return nK_Helper_Twitter::instance();
    }
    public function instagram () {
        return nK_Helper_Instagram::instance();
    }
    public function updater () {
        return nK_Helper_Updater::instance();
    }
    public function portfolio ($options = array()) {
        return nK_Helper_Portfolio::instance($options);
    }
    public function custom_post_type ( $post_type_names, $options = array()) {
        return new NK_Helper_Custom_Post_Type($post_type_names, $options);
    }
    public function reg_shortcode ($tag , $func){
        return add_shortcode($tag , $func);
    }
    /**
     * Options
     */
    private function get_options () {
        $options_slug = 'nk_theme_helper_options';
        return unserialize( get_option($options_slug, 'a:0:{}') );
    }
    public function update_option ($name, $value) {
        $options_slug = 'nk_theme_helper_options';
        $options = self::get_options();
        $options[self::sanitize_key($name)] = $value;
        update_option($options_slug, serialize($options));
    }
    public function get_option ($name, $default = null) {
        $options = self::get_options();
        $name = self::sanitize_key($name);
        return isset($options[$name]) ? $options[$name] : $default;
    }
    private function sanitize_key ($key) {
        return preg_replace( '/[^A-Za-z0-9\_]/i', '', str_replace( array( '-', ':' ), '_', $key ) );
    }


    /**
     * Cache
     * $time in seconds
     */
    private function get_caches () {
        $caches_slug = 'cache';
        return $this->get_option($caches_slug, array());
    }
    public function set_cache ($name, $value, $time = 3600) {
        if(!$time || $time <= 0) {
            return;
        }
        $caches_slug = 'cache';
        $caches = self::get_caches();

        $caches[self::sanitize_key($name)] = array(
            'value'   => $value,
            'expired' => time() + ((int) $time ? $time : 0)
        );
        $this->update_option($caches_slug, $caches);
    }
    public function get_cache ($name, $default = null) {
        $caches = self::get_caches();
        $name = self::sanitize_key($name);
        return isset($caches[$name]['value']) ? $caches[$name]['value'] : $default;
    }
    public function clear_cache ($name) {
        $caches_slug = 'cache';
        $caches = self::get_caches();
        $name = self::sanitize_key($name);
        if(isset($caches[$name])) {
            $caches[$name] = null;
            $this->update_option($caches_slug, $caches);
        }
    }
    public function clear_expired_caches () {
        $caches_slug = 'cache';
        $caches = self::get_caches();
        foreach($caches as $k => $cache) {
            if(isset($cache) && isset($cache['expired']) && $cache['expired'] < time()) {
                $caches[$k] = null;
            }
        }
        $this->update_option($caches_slug, $caches);
    }


    /**
     * File Operations
     */
    private function wp_fs() {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        global $wp_filesystem;
        WP_Filesystem();
        return $wp_filesystem;
    }

    public function file_put_contents($file_name = null, $content = '') {
        return $this->wp_fs()->put_contents($file_name, $content, 0644);
    }

    public function file_get_contents($file_name = null) {
        return $this->wp_fs()->get_contents($file_name);
    }

    // file_exists
    public function file_exists($file_name = null) {
        return $this->wp_fs()->exists($file_name);
    }

    // get file version (based on file change date)
    public function get_file_version($file_name = null) {
        return $this->wp_fs()->mtime($file_name);
    }

    // unlink
    public function unlink($file_name = null) {
        return $this->wp_fs()->delete($file_name);
    }


    /**
     * Compile SCSS and Less
     */
    public function get_compiled_css_path($file_name = 'nk-custom-style.css') {
        $file_path = trailingslashit($this->compile_path) . $file_name;
        return $this->file_exists($file_path) ? $file_path : false;
    }
    public function get_compiled_css_url($file_name = 'nk-custom-style.css') {
        return $this->get_compiled_css_path($file_name) ? trailingslashit($this->compile_url) . $file_name : false;
    }
    public function get_compiled_css_version($file_name = 'nk-custom-style.css') {
        $file_path = $this->get_compiled_css_path($file_name);
        return $file_path ? $this->get_file_version($file_path) : false;
    }
    public function scss($file_name = 'nk-custom-style.css', $path = null, $custom_scss = '') {
        $stored_scss = get_option($this->compile_folder . $file_name);

        // if cached, return false
        if($stored_scss == $custom_scss) {
            return false;
        }

        // if there is no cached version - let's compile!
        $scss = new Compiler();

        // $scss = new scssc();
        $scss->addImportPath($path);
        $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');

        $result = $scss->compile($custom_scss);

        $this->file_put_contents(trailingslashit($this->compile_path) . $file_name, $result);

        // cache
        update_option($this->compile_folder . $file_name, $custom_scss);

        return $result;
    }
    public function less($file_name = 'nk-custom-style.css', $src_file = null, $variables = array()) {
        require_once($this->plugin_path . 'inc/less.php/lessc.inc.php');

        $stored_less = get_option($this->compile_folder . $file_name);

        // cache
        if($stored_less == $variables) {
            return false;
        } else {
            update_option($this->compile_folder . $file_name, $variables);
        }

        $less = new Less_Parser();
        $less->parseFile($src_file);
        $less->ModifyVars($variables);
        $result = $less->getCss();

        $this->file_put_contents(trailingslashit($this->compile_path) . $file_name, $result);

        return $result;
    }
}
endif;
if (!function_exists('nk_theme')) :
function nk_theme () {
	return nK::instance();
}
endif;

add_action('plugins_loaded', 'nk_theme');
