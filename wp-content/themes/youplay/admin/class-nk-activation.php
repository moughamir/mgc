<?php
/**
 * Store data if activation check succeed
 */
if (!class_exists('nK_Activation')) :
class nK_Activation {
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
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->init_globals();
        }
        return self::$_instance;
    }

    private function __construct() {
        /* We do nothing here! */
    }

    public $new_purchase_codes = null;
    public $new_token = null;
    public $new_refresh_token = null;

    /**
     * Init Global variables
     */
    private function init_globals() {
        // get purchase code from base
        $this->purchase_code = nk_admin()->get_option('activation_purchase_code');
        $this->active = !!$this->purchase_code;

        // check if there is new purchase codes and token
        if(isset($_GET['purchase_codes']) && isset($_GET['token'])) {
            $this->new_purchase_codes = $_GET['purchase_codes'];
            $this->new_token = $_GET['token'];
            $this->new_refresh_token = $_GET['refresh_token'];
        }
    }
}
endif;
