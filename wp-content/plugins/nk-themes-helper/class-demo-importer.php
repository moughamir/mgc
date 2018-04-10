<?php
/**
 * Demo Import Helper
 *
 * Example:
 *   // import all demo data
 *   echo '<br><h4>Demo Data:</h4>';
 *   nk_theme()->demo_importer()->import_demo_data($import_data_file);
 *
 *   // setup widgets
 *   echo '<br><h4>Widgets:</h4>';
 *   nk_theme()->demo_importer()->import_demo_widgets($import_widgets_file);
 *
 *   // options tree importer
 *   echo '<br><h4>Theme Options:</h4>';
 *   nk_theme()->demo_importer()->import_demo_options_tree($import_options_file);
 */
if (!class_exists('nK_Helper_Demo_Importer')) :
class nK_Helper_Demo_Importer {
    /**
     * The single class instance.
     *
     * @since 1.0.0
     * @access private
     *
     * @var object
     */
    private static $_instance = null;

    /**
    * Main Instance
    * Ensures only one instance of this class exists in memory at any one time.
    *
    */
    public static function instance () {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct () {
        /* We do nothing here! */
    }

    private function prepare_demo_importer () {
        // set time limit to prevent demo import failings
        set_time_limit(120);

        if (!defined('WP_LOAD_IMPORTERS')) {
            define('WP_LOAD_IMPORTERS', true);
        }

        if (!class_exists('WP_Import')) {
            $wp_importer_path = nk_theme()->plugin_path . 'inc/demo-import/wordpress-importer.php';
            if (file_exists($wp_importer_path)) {
                require_once($wp_importer_path);
            }
        }

        if (!class_exists('Customizer_Import')) {
            $customizer_importer_path = nk_theme()->plugin_path . 'inc/demo-import/customizer-importer.php';
            if (file_exists($customizer_importer_path)) {
                require_once($customizer_importer_path);
            }
        }

        if (!function_exists('wie_import_data')) {
            $widgets_importer_path = nk_theme()->plugin_path . 'inc/demo-import/widgets_import.php';
            if (file_exists($widgets_importer_path)) {
                require_once($widgets_importer_path);
            }
        }
    }
    public function import_demo_data ($file) {
        $this->prepare_demo_importer();
        $wp_import = new WP_Import();
        $wp_import->fetch_attachments = true;
        $wp_import->import($file);
    }
    private function nk_wie_import_data ($file) {
        if (!file_exists($file)) {
            return new WP_Error('widget-import-error', esc_html__('Widgets import file could not be found.', 'nk-themes-helper'));
        }
        $data = file_get_contents($file);
        $data = json_decode($data);
        return wie_import_data($data);
    }
    public function import_demo_widgets ($file) {
        $this->prepare_demo_importer();
        $import_widgets_result = $this->nk_wie_import_data($file);
        if (is_wp_error($import_widgets_result)) {
            echo '<p>' . $import_widgets_result->get_error_message() . '</p>';
        } else {
            echo '<p>Widgets imported.</p>';
        }
    }
    public function import_rev_slider ($file, $slider) {
        if(!class_exists('RevSlider')) {
            echo '<p>Revolution Slider plugin is not installed.</p>';
            return;
        }
        if(!isset($slider)) {
            $slider = new RevSlider();
        }
        if(is_array($file)) {
            foreach($file as $a) {
                $this->import_rev_slider($a, $slider);
            }
            return;
        }
        $this->prepare_demo_importer();
        if (file_exists($file)) {
            $slider->importSliderFromPost(true, true, $file);
            echo '<p>' . basename($file) . ' imported.</p>';
        }
    }
    public function import_demo_options_tree ($file) {
        $this->prepare_demo_importer();
        if (function_exists('ot_options_id') && file_exists($file)) {
            $import_options_data = file_get_contents($file);
            $import_options_data = maybe_unserialize(base64_decode($import_options_data));

            if (!empty($import_options_data) || is_array($import_options_data)) {
                update_option(ot_options_id(), $import_options_data);
                echo '<p>Options imported.</p>';
            } else {
                echo '<p>Options import error.</p>';
            }
        }
    }
    public function import_demo_customizer ($file) {
        $this->prepare_demo_importer();
        $importer = new Customizer_Import;
        $importer->import_images = true;
        $importer->run($file);
    }
}
endif;
