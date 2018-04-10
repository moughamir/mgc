<?php
/**
 * Admin Class for _nK themes
 */
if (!class_exists('nK_Admin_Pages')) :
class nK_Admin_Pages {
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

    /**
     * Init Global variables
     */
    private function init_globals() {
        add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'admin_bar_menu', array($this, 'admin_bar_menu'), 80.22 );
        add_action( 'admin_init', array($this, 'admin_init' ));
    }

    // admin pages
    protected $pages;

    // admin pages data
    protected $pages_data;

    /**
     * Init Method
     */
    public function init($data = array()) {
        $default_data = array(
            'top_message'       => '',
            'foot_message'      => '',
            'top_button_text'   => '',
            'top_button_url'    => '',
            'top_tweet_text'    => '',
            'top_tweet_url'     => '',
            'top_tweet_via'     => ''
        );
        $this->pages_data = array_merge($default_data, $data);
    }

   // admin init action
   public function admin_init() {
       if ( isset( $_GET['nk-deactivate'] ) && 'deactivate-plugin' == $_GET['nk-deactivate'] ) {
           check_admin_referer( 'nk-deactivate', 'nk-deactivate-nonce' );

           $plugins = TGM_Plugin_Activation::$instance->plugins;

           foreach ( $plugins as $plugin ) {
               if ( $plugin['slug'] == $_GET['plugin'] ) {
                   deactivate_plugins( $plugin['file_path'] );
               }
           }
       }
       if ( isset( $_GET['nk-activate'] ) && 'activate-plugin' == $_GET['nk-activate'] ) {
           check_admin_referer( 'nk-activate', 'nk-activate-nonce' );

           $plugins = TGM_Plugin_Activation::$instance->plugins;

           foreach ( $plugins as $plugin ) {
               if ( isset( $_GET['plugin'] ) && $plugin['slug'] == $_GET['plugin'] ) {
                   activate_plugin( $plugin['file_path'] );

                   wp_redirect( admin_url( 'admin.php?page=nk-theme-plugins' ) );
                   exit;
               }
           }
       }
   }

   // add array with admin pages
   public function add_pages($pages = null) {
       if(is_array($pages) && !isset($this->inited_admin_pages)) {
           $this->pages = $pages;
           $this->inited_admin_pages = true;
       }
   }

   // admin menus
   public function admin_menu() {
       if(!is_array($this->pages)) {
           return;
       }

       $main_item_title = nk_admin()->theme_name;
       if(nk_admin()->updater()->is_update_available()) {
            $main_item_title .= ' <span class="awaiting-mod"><span>New</span></span>';
       }

       call_user_func('add_' . 'menu_page', $main_item_title, $main_item_title, 'edit_theme_options', 'nk-theme', array($this, 'print_pages'), 'dashicons-admin-youplay', '3.22');

       foreach( $this->pages as $name => $page ) {
           call_user_func('add_' . 'submenu_page', 'nk-theme', $page['title'], $page['title'], 'edit_theme_options', $name, array($this, 'print_pages'));
       }
   }
   public function admin_bar_menu($admin_bar) {
       if(!is_array($this->pages)) {
           return;
       }
       global $wp_admin_bar;

       $wp_admin_bar->add_menu(array('id' => 'nk-theme-top', 'title' => '<i class="icon-youplay"></i>' . nk_admin()->theme_name, 'href' => menu_page_url('nk-theme', 0)));
       foreach( $this->pages as $name => $page ) {
           $wp_admin_bar->add_menu(array('parent' => 'nk-theme-top', 'title' => $page['title'], 'id' => $name . '_top', 'href' => menu_page_url($name, 0)));
       }
   }

   // print pages
   public function print_pages() {
       ?>
       <div class="wrap about-wrap nk-theme-wrap">

           <h1>Welcome to <?php echo nk_admin()->theme_name; ?>!</h1>

           <div class="nk-theme-logo">
               <div class="icon-youplay"></div>
               <span>Version <?php echo nk_admin()->theme_version; ?></span>
           </div>

           <div class="about-text">
               <?php echo $this->pages_data['top_message']; ?>
           </div>
           <div class="about-text">
               <?php printf('<a href="%s" class="button button-primary" target="_blank">%s</a>', $this->pages_data['top_button_url'], $this->pages_data['top_button_text']); ?>

               <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo esc_attr($this->pages_data['top_tweet_url']); ?>" data-text="<?php echo esc_attr($this->pages_data['top_tweet_text']); ?>" data-via="<?php echo esc_attr($this->pages_data['top_tweet_via']); ?>" data-size="large">Tweet</a>
               <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
           </div>

           <?php
           $tab = 'nk-theme';
           if ( isset($_GET['page']) ) {
               $tab = $_GET['page'];
           }
           echo '<div id="icon-themes" class="icon32"><br></div>';
           echo '<h2 class="nav-tab-wrapper">';
           foreach( $this->pages as $name => $page ) {
               $class = ( $name == $tab ) ? ' nav-tab-active' : '';
               echo "<a class='nav-tab" . $class . "' href='" . menu_page_url($name, 0) . "'>" . $page['title'] . "</a>";
           }
           echo '</h2>';
           ?>

           <div id="poststuff">
               <?php
               if(isset($this->pages[$tab]) && isset($this->pages[$tab]['template'])) {
                   require nk_admin()->admin_path .'/admin_pages/' . $this->pages[$tab]['template'];
               }
               ?>
               <p class="description"><?php echo $this->pages_data['foot_message']; ?></p>
           </div>

       </div>
       <?php
   }
}
endif;
