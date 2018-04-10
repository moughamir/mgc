<?php
/**
 * Plugin Name: ViralPress
 * Plugin URI:  http://inspire007.com/envato/viralpress/
 * Description: ViralPress is a wordpress plugin to turn your wordpress site into a viral content sharing platform. ViralPress supports list items, video, image, news, embeds, galleries, playlists, quiz and polls.
 * Author:      InspiredDev
 * Author URI:  http://codecanyon.net/user/inspireddev
 * Version:     3.4.8
 * Text Domain: viralpress
 */

/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/

/**
 * prevent direct access
 */
defined( 'ABSPATH' ) || exit;

/**
 * define viralpress version
 */
if( !defined('VP_VERSION') ){
	define( 'VP_VERSION', '3.4.8' );	
}

/**
 * do not define if already defined
 */
if(!class_exists( 'ViralPress') ):

class ViralPress
{
	/**
	 * update check url
	 * @since 1.0
	 * @changed 1.6
	 */
	public $version_check_url = "http://inspire007.com/envato/viralpress/customers/viralpress.php";
	
	/**
	 * item link
	 * @since 1.6
	 */
	public $item_link = "http://codecanyon.net/item/viralpress-viral-news-lists-quiz-videos-polls-plugin/14541033?ref=inspireddev";
	
	/**
	 * holds all settings variable
	 * @since 1.0
	 */
	public $settings;
	
	/**
	 * holds all configurable variable
	 * @since 3.3
	 */
	public $configs;
	
	/**
	 * array of viralpress pages
	 * @since 1.0
	 */
	public $vp_pages;
	
	/**
	 * array of viralpress menus
	 * @since 1.0
	 */
	public $vp_menus;
	
	/**
	 * array of viralpress custom post types
	 * @since 1.0
	 */
	public $vp_post_types;
	
	/**
	 * full width template regex
	 * @since 1.0
	 */
	public $full_width_regex;
	
	/**
	 * holds viralpress installed version no
	 * @since 1.0
	 */
	public $installed_version;
	
	/**
	 * holds the update url
	 * @since 1.0
	 */
	public $update_url;
	
	/**
	 * indicates whether the current user is a contributor
	 * @since 1.3
	 */
	public $is_contributor;
	
	/**
	 * holds temporary variables to display posts and others
	 * @since 2.5
	 */
	public $temp_vars;
	
	/**
	 * tags allowed in description html
	 * @since 3.0
	 */
	public $allow_tags;
	
	/**
	 * holds viralpress updater
	 * @since 3.0
	 */
	public $vp_updater;
		
	/**
	 * constructor function of viralpress
	 * initializes the class but does not run it
	 * @since 1.0
	 */	
	public function __construct()
	{
		/**
		 * common pattern of full width template names
		 */
		$this->full_width_regex = '/fullwidth|full width|full-width|nosidebar|no-sidebar|no sidebar|no side bar|no-side-bar/i';
		
		/**
		 * basic settings
		 */
		$dir = rtrim( dirname( __FILE__ ), '/' ).'/';
		$url = plugin_dir_url( __FILE__ );
		$this->settings = array(
			'WP_DIR' => ABSPATH,
			'WP_ADMIN' => str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() ),
			'PLUGIN_DIR' => $dir,
			'CLASS_DIR' => $dir.'classes',
			'LIB_DIR' => $dir.'libraries',
			'ASSET_DIR' => $dir.'assets',
			'TEMPLATE_DIR' => $dir.'templates',
			'IMG_DIR' => $dir.'assets/images',
			'JS_DIR' => $dir.'assets/js',
			'CSS_DIR' => $dir.'assets/css',
			'PLUGIN_URL' => $url,
			'ASSET_URL' => $url . 'assets',
			'IMG_URL' => $url . 'assets/images',
			'JS_URL' => $url . 'assets/js',
			'CSS_URL' => $url . 'assets/css',
			'PLUGIN_SLUG' => plugin_basename( __FILE__ ),
			'PLUGIN_FILE' => basename( __FILE__ ),
			'PLUGIN_FOLDER' => basename( dirname( __FILE__ ) )
		);
		
		$this->allow_tags = 
		array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'b' => array(),
			'blockquote' => array(),
			'p' => array(
				'style' => array()
			),
			'ul' => array(),
			'li' => array(),
			'ol' => array(),
			'table' => array(),
			'tbody' => array(),
			'tr' => array(),
			'td' => array(),
			'th' => array()
		);
		
		/**
		 * load secondary settings
		 */
		$this->load_settings();
		
		/**
		 * array of guest and subscriber menus  
		 */
		$this->vp_menus = array(
			'guest' => 'vp_guest_menu_items',
			'subscriber' => 'vp_subscriber_menu_items'
		);
	}
	
	/**
	 * initiates the plugin
	 * @since 1.0
	 */
	public function init()
	{
		/**
		 * load necessary include files
		 */
		$this->load_includes();	
		
		/**
		 * if there is a fb app id, put it on header meta
		 */
		if( !empty( $this->settings['fb_app_id'] ) ) {
			add_action( 'wp_head', array( &$this, 'vp_add_meta_tags' ) );	
		}
		
		/**
		 * our plugin activation and deactivation hooks
		 */
		register_activation_hook( __FILE__, array( &$this, 'plugin_activate' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'plugin_deactivate' ) );
		
		/**
		 * version update hook
		 */
		if( $this->installed_version != VP_VERSION && !empty( $this->installed_version ) ) {
			$this->plugin_activate();	
		}
		
		/**
		 * initialize viralpress updater
		 */
		$this->vp_updater = new vp_updater( $this->settings, $this->installed_version, $this->item_link, $this->version_check_url );
		
		/**
		 * hook after plugin activated
		 */
		add_action( 'activated_plugin', array( &$this, 'plugin_activated' ) );
		
		/**
		 * block admin access for non-admin users
		 */
		add_action( 'init', array( &$this, 'vp_check_user_roles'), 0 );
		
		/**
		 * anon cookie
		 */
		add_action( 'init', array( &$this, 'vp_set_anon_cookie'), 0 );
		
		/**
		 * admin menu hook
		 */
		add_action( 'admin_menu', array( &$this, 'register_admin_menu'));
		
		/**
		 * register viralpress post types
		 */ 
		add_action( 'init', array( &$this, 'register_vp_post_types') );
		
		/**
		 * load viralpress textdomain
		 */ 
		add_action( 'init', array( &$this, 'load_vp_text_domain') );
		
		
		/**
		 * block admin access for non-admin users
		 */
		add_action( 'init', array( &$this, 'vp_check_user_perms') );
		
		/**
		 * redirect secured page from guest users
		 */
		add_action( 'init', array( &$this, 'redirect_auth' ));
		
		/**
		 * viralpress new author base
		 * not used currently
		 */
		add_action( 'init', array( &$this, 'vp_new_author_base' ) );
		
		/**
		 * viralpress admin bar menus
		 */
		add_action( 'admin_bar_menu', array( &$this, 'vp_admin_bar_links' ), 999 );
		
		/**
		 * enqueue our scripts
		 */
		add_action( 'wp_enqueue_scripts', array( &$this, 'loadJS' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'loadCSS' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'loadAdminJS' ) );
		
		/**
		 * display our custom posts on home 
		 * not used currently
		 */
		add_action( 'pre_get_posts', array( &$this, 'display_vp_posts_home' ), 10, 1 );
		
		/**
		 * add new category on wordpress menu
		 */
		add_action( 'created_category', array( &$this, 'on_category_created' ), 10, 3 );
		
		/**
		 * hook when theme is changed
		 * checks new full width template and register it with our page
		 */
		add_action( 'after_switch_theme', array( &$this, 'vp_theme_change_hook' ) );
		
		/**
		 * buddypress integration when post status is changed
		 */
		add_action( 'transition_post_status', array( &$this, 'vp_post_status_update' ), 10, 3 );
		
		/**
		 * buddypress custom profile tabs
		 */
		add_action( 'bp_setup_nav', array( &$this, 'vp_add_profileposts_tab' ), 100 );
		
		
		/**
		 * noti count when comments are approved
		 */
		add_action('comment_post', array( &$this, 'vp_approve_comment_callback' ), 10, 2);
		
		/**
		 * add column to admin panel posts section
		 */
		add_action('manage_posts_custom_column', array( &$this, 'vp_posts_column_display' ), 10, 2);
		
		/**
		 * gives upload permission to contributors
		 */
		require_once( $this->settings['WP_DIR']. WPINC .'/pluggable.php');
		
		/**
		 * current username and url shortcodes for nav menu
		 */
		add_shortcode( 'current-username' , array( &$this, 'ss_get_current_username' ) );
		add_shortcode( 'vp_logout_t' , array( &$this, 'vp_print_time' ) );
		add_shortcode( 'current-user-url' , array( &$this, 'vp_get_current_user_url' ) );
		add_shortcode( 'current-bp-user-url' , array( &$this, 'vp_bp_get_current_user_url' ) );
		add_shortcode( 'current-bp-noti-url' , array( &$this, 'vp_bp_get_noti_url' ) );
		add_shortcode( 'current-bp-op-url' , array( &$this, 'vp_bp_get_op_url' ) );
		
		
		/**
		 * post entry render shortcode
		 */
		add_shortcode( 'vp_post_entry', array( 'vp_post', 'render_post_entry' ));
		
		/**
		 * shortcode to render my posts recent comments
		 */
		add_shortcode( 'vp_my_recent_post_comments', array( 'vp_post', 'render_my_recent_post_comments' ));
		
		/**
		 * shortcode to render my recent comments
		 */
		add_shortcode( 'vp_my_recent_comments', array( 'vp_post', 'render_my_recent_comments' ));
		
		/**
		 * shortcode to render viralpress dashboard
		 */
		add_shortcode( 'viralpress_user_dashboard' , array( &$this, 'render_viralpress_user_dashboard' ) );
		
		/**
		 * shortcode to render post editor
		 */
		add_shortcode( 'viralpress_user_create_entry', array( &$this, 'render_vp_editor' ) );
		
		/**
		 * shortcode to meme generator
		 */
		add_shortcode( 'viralpress_meme_generator', array( &$this, 'vp_meme_generator' ) );
		
		/**
		 * shortcode to render score entry
		 */
		add_shortcode( 'vp_score_entry', array( 'vp_post', 'vp_score_entry' ) );
		
		/**
		 * shortcode to render poll entry
		 */
		add_shortcode( 'vp_poll_entry', array( 'vp_post', 'vp_poll_entry' ) );
		
		/**
		 * shortcode for viralpress profile page
		 */
		add_shortcode( 'viralpress_profile_page' , array( 'vp_user', 'render_viralpress_profile_page' ) );
		
		/**
		 * post like buttons render shortcode
		 */
		add_shortcode( 'vp_post_like_buttons', 'vp_post_like_buttons' );
		
		/**
		 * post like buttons render shortcode
		 */
		add_shortcode( 'vp_post_upvote_buttons', 'vp_post_upvote_buttons' );
		
		/**
		 * post react buttons render shortcode
		 */
		add_shortcode( 'vp_post_react_buttons', 'vp_print_emoji_reactions' );
		
		
		/**
		 * filter to render viralpress custom post type
		 */
		add_filter( 'the_content', array( &$this, 'vp_custom_content_single' ) );
		
		/**
		 * filter to render menu shortcodes
		 */
		add_filter( 'wp_nav_menu', array( &$this, 'menu_shortcodes' ) ); 
		
		/**
		 * filter to render our custom avatar
		 */
		add_filter( 'get_avatar', array( &$this, 'vp_gravatar' ), 1, 5 );
		
		/**
		 * filter to show different menus for guest & subscribers
		 */
		add_filter( 'wp_nav_menu_args', array( &$this, 'vp_menu_args' ), 0);
	
		/**
		 * prevent users from viewing others uploaded attachments
		 */
		add_filter( 'posts_where', array( &$this, 'users_own_attachments' ) );
		add_action( 'pre_get_posts', array( &$this, 'users_own_attachments_w' ) );
		
		/**
		 * custom templates for viralpress i.e. profile page
		 */
		add_filter( 'template_include', array( &$this, 'vp_custom_templates' ) );
		
		/**
		 * add post edit permissions
		 */
		add_filter( 'map_meta_cap', array( &$this, 'vp_map_meta_cap' ), 10, 4 );
		
		/**
		 * custom post edit links in frontend
		 */
		add_filter( 'get_edit_post_link', array( &$this, 'vp_edit_post_link'), 10, 2 );
		
		/**
		 * custom delete post links in frontend
		 */
		add_filter( 'get_delete_post_link', array( &$this, 'vp_delete_post_link'), 10, 2 );
		
		/**
		 * filter to render our custom posts in widgets
		 */
		add_filter( 'widget_posts_args', array( &$this, 'vp_widget_post_args' ) ); 
		
		/**
		 * show pending post notification in admin menu
		 */
		add_filter( 'add_menu_classes', array( &$this, 'show_pending_count'), 8);
		
		/**
		 * show pending open list notification in admin menu
		 */
		add_filter( 'add_menu_classes', array( &$this, 'show_pending_open_list_count'), 8);
		
		/**
		 * allow only image uploads
		 */
		add_filter( 'upload_mimes', array( &$this, 'vp_restrict_mime' ) );
		
		/**
		 * admin link to open in viralpress editor
		 */
		//add_filter( 'post_row_actions', array( $this, 'add_vp_editor_link' ), 10, 2 );
		
		/**
		 * if fb comments or share buttons enabled show them before comments template or print emoji reactions
		 */
		add_filter( 'comments_template', array( &$this, 'vp_pre_comments' ), 1 );
		
		/**
		 * filters for buddypress notifications
		 */
		add_filter( 'bp_notifications_get_registered_components', 'vp_custom_filter_notifications_get_registered_components' );
		add_filter( 'bp_notifications_get_notifications_for_user', 'vp_format_buddypress_notifications', 10, 8 );
		
		/**
		 * myCred support
		 */
		add_filter( 'mycred_setup_hooks', 'register_vp_mycred__hook' ); 
		
		/**
		 * deny open list edit
		 */
		add_filter( 'user_has_cap', array( &$this, 'vp_deny_open_list_edit' ), 100, 3 );
		
		/**
		 * script loader tag
		 */
		add_filter('script_loader_tag', array( &$this, 'vp_script_loader_tag' ), 10, 2);
		
		/**
		 * script loader tag
		 */
		add_filter('manage_posts_columns', array( &$this, 'vp_posts_column' ) );
			
		
		/**
		 * register ajax calls
		 */
		$this->register_ajax();
		
		/**
		 * register viralpress quiz images
		 */
		add_image_size( 'vp-quiz-image', 400, 400, true );		
	}
	
	/**
	 * loads variable settings
	 * @since 1.0
	 * @changed 1.1
	 * @changed 1.2
	 */
	public function load_settings()
	{
		
		$this->installed_version = get_option('vp-version');
		$this->configs = array();
		
		$vp_settings = get_option( 'vp-global-configs' );
		
		if( is_string( $vp_settings ) && !empty( $vp_settings ) ) {
			$vp_settings = json_decode( $vp_settings, true );
			foreach( $vp_settings as $k => $v ) {
				$this->configs[ $k ] = $v;
				$this->settings[ $k ] = $v;
			}
		}
		
		$this->settings['load_recap'] = @$this->settings['recap_key'] && $this->settings['recap_secret'] && ($this->settings['recap_login'] || $this->settings['recap_post']);
		
		$this->settings['react_gifs'] = get_option( 'vp-react-gifs' );
		
		$this->settings['page_slugs'] = get_option( 'vp-page-slugs' );
		$this->settings['page_slugs'] = @json_decode( $this->settings['page_slugs'], true );
		
		$this->settings['cat_tag'] = get_option( 'vp-cat-tag' );
		$this->settings['cat_tag'] = @json_decode( $this->settings['cat_tag'], true );
		
		$this->settings['envato_username'] = get_option( 'vp-envato-username' );
		$this->settings['envato_api_key'] = get_option( 'vp-envato-api-key' );
		$this->settings['envato_purchase_code'] = get_option( 'vp-envato-purchase-code' );
	}
	
	/**
	 * loads old settings from v3.3
	 * @since 3.3
	 */
	public function load_old_settings()
	{
		$s = array();
		$s['fb_app_id'] = get_option( 'vp-fb-app-id' );
		$s['google_oauth_id'] = get_option( 'vp-google-oauth-id' );
		$s['google_api_key'] = get_option( 'vp-google-api-key' );
		$s['auto_publish'] = get_option( 'vp-auto-publish-post' );
		$s['fb_comments'] = get_option( 'vp-show-fb-comments' );
		$s['share_buttons'] = get_option( 'vp-share-buttons' );
		$s['custom_profiles'] = get_option( 'vp-custom-profiles' );
		$s['show_menu'] = get_option( 'vp-show-menu' );
		$s['block_admin'] = get_option( 'vp-block-admin' );
		$s['block_edits'] = get_option( 'vp-block-edits' );
		$s['disable_login'] = get_option('vp-disable-login');
		$s['use_category'] = get_option('vp-use-category');
		$s['only_admin'] = get_option('vp-only-admin');
		$s['show_reactions'] = get_option('vp-show-reactions');
		$s['show_menu_on'] = get_option('vp-show-menu-on');
		$s['recap_key'] = get_option('vp-recap-key');
		$s['recap_secret'] = get_option('vp-recap-secret');
		$s['recap_login'] = get_option('vp-recap-login');
		$s['recap_post'] = get_option('vp-recap-post');
		$s['load_recap'] = $s['recap_key'] && $s['recap_secret'] && ($s['recap_login'] || $s['recap_post']);
		$s['anon_votes'] = get_option( 'vp-anon-votes' );
		$s['share_quiz_force'] = get_option( 'vp-share-quiz-force' );
		$s['allow_copy'] = get_option( 'vp-allow-copy' );
		$s['allow_open_list'] = get_option( 'vp-allow-open-list' );
		$s['comments_per_list'] = get_option( 'vp-comments-per-list' );
		
		$s['list_enabled'] = get_option( 'vp-allow-list' );
		$s['quiz_enabled'] = get_option( 'vp-allow-quiz' );
		$s['poll_enabled'] = get_option( 'vp-allow-poll' );
		$s['playlist_enabled'] = get_option( 'vp-allow-playlist' );
		$s['gallery_enabled'] = get_option( 'vp-allow-gallery' );
		$s['self_video'] = get_option( 'vp-self-video' );
		$s['self_audio'] = get_option( 'vp-self-audio' );
		
		$s['show_like_dislike'] = get_option( 'vp-show-like-dislike' );
		$s['allowed_embeds'] = get_option( 'vp-allowed-embeds' );
		$s['hotlink_image'] = get_option( 'vp-hotlink-image' );
		$s['vp_bp'] = get_option( 'vp-bp-int' );
		$s['vp_mycred'] = get_option( 'vp-mycred-int' );
		$s['hide_vote_buttons'] = get_option( 'vp-hide-vote' );
		$s['hide_vote_buttons_op'] = get_option( 'vp-hide-vote-op' );
		$s['sort_op_vote'] = get_option( 'vp-sort-op-vote' );
		
		return $s;
	}
	
	/**
	 * prints share buttons and/or fb comments before wp comment box or emoji reactions
	 * @since 1.0
	 * @changed 1.3
	 * @changed 1.9
	 * @changed 3.0
	 */
	public function vp_pre_comments( $template )
	{
		if( is_single() ) {
			
			global $post;
			
			$class = apply_filters( 'viralpress_get_comments_area_class', 'comments-area' );
			
			$cl = 0;
			$tt = '<div class="'.$class.'">';
			
			//$editable = current_user_can( 'edit_post', $post->ID );
			//$edit_link = get_edit_post_link();
			
			/*
			if( empty( $this->settings['hide_vote_buttons'] ) ) {
			
				if( empty( $this->settings['show_like_dislike'] ) ) {			
				
					if( $editable ) {
						$n = wp_create_nonce( 'delete-post_'.$post->ID );
						$tt .= '<div class="vp-op-au-4">';
						$tt .= '<a class="btn btn-info" href="'.$edit_link.'&_nonce='.$n.'&delete=1" style="text-decoration:none; color:white" onclick="return confirm(\''.__( 'Are you sure to delete this?', 'viralpress' ).'\')"><i class="glyphicon glyphicon-trash vp-pointer"></i></a>';
						$tt .= '</div>';
					}
					
					$tt .= do_shortcode( '[vp_post_upvote_buttons post_id="'.$post->ID.'"]' );
				}
				else {
					
					if( $editable ){
						$n = wp_create_nonce( 'delete-post_'.$post->ID );
						$tt .= '<div class="vp-pull-right">&nbsp;&nbsp;&nbsp;
								<a class="btn btn-info" href="'.$edit_link.'&_nonce='.$n.'&delete=1" style="text-decoration:none; color:white" onclick="return confirm(\''.__( 'Are you sure to delete this?', 'viralpress' ).'\')"><i class="glyphicon glyphicon-trash vp-pointer"></i></a>
							  </div>' ;
					}
					$tt .= do_shortcode( '[vp_post_like_buttons post_id="'.$post->ID.'" no_padding_top="1"]' );
				}
			}
			*/
			
			if( $this->settings['show_reactions'] || $this->settings['show_gif_reactions']  ) {
				$tt .= vp_print_emoji_reactions( $post );
			}
			if( $this->settings['share_buttons'] ) {
				$tt .= vp_get_template_html( 'share_buttons', array( 'post' => $post ) ).'<div class="vp-clearfix-lg"></div>';		
			}
			if( $this->settings['fb_comments'] ) {
				$tt .= '<div class="fb-comments" data-href="'.get_the_permalink().'" data-numposts="5" data-width="100%"></div><div class="vp-clearfix"></div>';
			}
			
			$tt .= '</div>';
			
			do_action( 'viralpress_after_post_end_links' );
			
			echo apply_filters( 'viralpress_end_post_section', $tt, $post, $this->settings );
		}
		return $template;
	}
	
	/**
	 * adds fb app id on header meta
	 * @since 1.0
	 */
	public function vp_add_meta_tags()
	{
		echo '<meta property="fb:app_id" content="'.$this->settings['fb_app_id'].'" />';
	}
	
	/**
	 * register viralpress admin menu
	 * @since 1.0
	 */
	public function register_admin_menu()
	{
		add_menu_page( 'ViralPress', 'ViralPress' ,'manage_options', 'viralpress', array( $this, 'vp_config_page' ), 'dashicons-vault', 7 );
		add_submenu_page( 'viralpress', __( 'ViralPress Settings' ), __( 'ViralPress Settings' ) ,'manage_options', 'viralpress', array( $this, 'vp_config_page' ), 'dashicons-vault', 7 );
		add_submenu_page( 'viralpress', __( 'Auto Update', 'viralpress' ), __( 'Auto Update', 'viralpress' ) ,'manage_options', 'viralpress-update', array( $this, 'vp_update_page' ), 'dashicons-vault', 7 );
		add_submenu_page( 'viralpress', __( 'Ad Settings', 'viralpress' ), __( 'Ad Settings', 'viralpress' ) ,'manage_options', 'viralpress-ad-settings', array( $this, 'vp_ad_settings' ), 'dashicons-slides', 7 );
		add_submenu_page( null, __( 'Poll Results', 'viralpress' ), __( 'Poll Results', 'viralpress' ) ,'manage_options', 'viralpress-poll', array( $this, 'vp_poll_results' ), 'dashicons-vault', 7 );
		add_submenu_page( null, __( 'Quiz Shares', 'viralpress' ), __( 'Quiz Shares', 'viralpress' ) ,'manage_options', 'viralpress-quiz-share', array( $this, 'vp_quiz_share' ), 'dashicons-vault', 7 );
		add_menu_page( 'Open lists', 'Open lists' ,'manage_options', 'viralpress-openlists', array( $this, 'vp_open_list' ), 'dashicons-images-alt', 7 );
	}
	
	/**
	 * displays viralpress admin config page
	 * @since 1.0
	 */
	public function vp_config_page()
	{
		$attributes = array( 'vp_instance' => &$this );
		echo vp_get_template_html( 'admin', $attributes );
	}
	
	public function vp_ad_settings()
	{
		$attributes = array( 'vp_instance' => &$this );
		echo vp_get_template_html( 'ads', $attributes );
	}
	
	public function vp_open_list()
	{
		$attributes = array( 'vp_instance' => &$this );
		echo vp_get_template_html( 'open_list_admin_page', $attributes );	
	}
	
	/**
	 * displays viralpress update config page
	 * @since 1.6
	 */
	public function vp_update_page()
	{
		$attributes = array( 'vp_instance' => &$this );
		echo vp_get_template_html( 'update', $attributes );
	}
	
	/**
	 * viralpress poll results display page
	 * @since 2.4
	 */
	public function vp_poll_results()
	{
		if(empty($_GET['poll_id'])){
			echo '<div class="error"><p>'.__( 'Invalid poll ID', 'viralpress' ).'</p></div>';	
			return;
		}
		
		$voted = '';
		$post_id = (int)$_GET['poll_id'];
		$p = json_encode( get_poll_results( $post_id) );
		echo '<div class="wrap">
				<div class="poll-results-p" style="display:block">
					<h3>'.__( 'Poll Results', 'viralpress' ).'</h3>
			  		<div class="poll-results">	
			  		</div>
				</div>
			</div>';
		
		echo '<script>var poll_submit = 1;var user_already_voted = '.( empty($voted) ? 0 : 1 ).';var user_votes = \''.$voted.'\';var poll_id = '.$post_id.';'.( !empty($p) ? 'print_poll_results(\''.$p.'\');' : '' ).';</script>';
	}
	
	/**
	 * viralpress quiz_share display page
	 * @since 3.0
	 */
	public function vp_quiz_share()
	{
		if(empty($_GET['quiz_id'])){
			echo '<div class="error"><p>'.__( 'Invalid quiz ID', 'viralpress' ).'</p></div>';	
			return;
		}
		
		$voted = '';
		$post_id = (int)$_GET['quiz_id'];
		
		echo '<div class="wrap">
				<div class="poll-results-p" style="display:block"><h3>'.__( 'Quiz Shares', 'viralpress' ).'</h3>
			  		<div class="poll-results">';	
		
		$ii = get_post_meta( $post_id, 'vp_quiz_share_ids' );
		$ii = @$ii[0];
		
		if(  !empty( $ii ) ) {
			$ii = json_decode( $ii, true );
			echo '<div class="updated">'.$ii['count'].' '.__( 'shares', 'viralpress' ).'</div>';
			echo '<table cellpadding="5" cellspacing="5">';
			echo '<tr><th>'.__( 'Platform', 'viralpress' ).' </th><th>'.__( 'Link', 'viralpress' ).' </th><th>'.__( 'Date shared', 'viralpress' ).' </th></tr>';	
			foreach( $ii['ids'] as $kk ) {
				echo '<tr>
						<td>'.@$kk['site'].'</td>
						<td><a href="https://fb.com/'.@$kk['post_id'].'" target="_blank">'.__( 'View', 'viralpress' ).'</a></td>
						<td>'.@$kk['time'].'</td>
					 </tr>';	
			}
			echo '</table>';
		}	
		else {
			echo '<div class="error"><p>'.__( 'No shares yet! Did you turn on force quiz share option?', 'viralpress' ).'</p></div>';	
		}
			  
		echo '		</div>
				</div>
			 </div>';
	}
	
	/**
	 * loads viralpress page defininitions
	 * @since 1.0
	 * @changed 3.0 category menu removed
	 */
	public function load_page_definitions()
	{
		$args = array( 'hide_empty' => 0 );
		$categories = get_categories( $args );
		$category_submenu = array();
		
		/*
		foreach( $categories as $category ) {
			$category_submenu[] = array(
				'title' => $category->name,
				'category_id' => $category->term_id,
				'url' => get_category_link( $category->term_id )	
			);
		} 
		*/
		
		$this->vp_pages = array(
			'home' => array(
				'title' => __( 'Home', 'viralpress' ),
				'user' => 'all',
				'add_page' => false,
				'add_menu' => true,
				'url' => home_url( '/' )
			),
			'categories' => array(
				'title' => __( 'Categories', 'viralpress' ),
				'user' => 'all',
				'add_page' => false,
				'add_menu' => false,
				'url' => 'javascript:void(0)',
				'xfn' => 'category',
				'sub_menu' => $category_submenu
			),
			'post-comments' => array(
				'title' => __( 'Recent comments on my posts', 'viralpress' ),
				'user' => 'subscriber',
				'add_page' => true,
				'add_menu' => false,
				'content' => '[vp_my_recent_post_comments]',
			),
			'my-comments' => array(
				'title' => __( 'Recent comments by me', 'viralpress' ),
				'user' => 'all',
				'add_page' => true,
				'add_menu' => false,
				'content' => '[vp_my_recent_comments]',
			),
			'latest' => array(
				'title' => __( 'Latest', 'viralpress' ),
				'user' => 'all',
				'add_page' => false,
				'add_menu' => true,
				'url' => 'javascript:void(0)',
				'sub_menu' => array(	
					'news' => array(
						'title' => __( 'News', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['news']['tag'] ) ? 'News' : $this->settings['cat_tag']['news']['tag'] ).'/' )
					),
					'lists' => array(
						'title' => __( 'Lists', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['lists']['tag'] ) ? 'Lists' : $this->settings['cat_tag']['lists']['tag'] ).'/' )
					),
					'images' => array(
						'title' => __( 'Images', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['images']['tag'] ) ? 'Images' : $this->settings['cat_tag']['images']['tag'] ).'/' )
					),
					'meme' => array(
						'title' => __( 'Meme', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['meme']['tag'] ) ? 'Meme' : $this->settings['cat_tag']['meme']['tag'] ).'/' )
					),
					'quiz' => array(
						'title' => __( 'Quiz', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['quiz']['tag'] ) ? 'Quiz' : $this->settings['cat_tag']['quiz']['tag'] ).'/' )
					),				
					'polls' => array(
						'title' => __( 'Polls', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['polls']['tag'] ) ? 'Polls' : $this->settings['cat_tag']['polls']['tag'] ).'/' )
					),
					'videos' => array(
						'title' => __( 'Videos', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['videos']['tag'] ) ? 'Videos' : $this->settings['cat_tag']['videos']['tag'] ).'/' )
					),
					'audio' => array(
						'title' => __( 'Audio', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['audio']['tag'] ) ? 'Audio' : $this->settings['cat_tag']['audio']['tag'] ).'/' )
					),
					'gallery' => array(
						'title' => __( 'Gallery', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['gallery']['tag'] ) ? 'Gallery' : $this->settings['cat_tag']['gallery']['tag'] ).'/' )
					),
					'playlist' => array(
						'title' => __( 'Playlist', 'viralpress' ),
						'url' => home_url( '/tag/'.( empty( $this->settings['cat_tag']['playlist']['tag'] ) ? 'Playlist' : $this->settings['cat_tag']['playlist']['tag'] ).'/' )
					)
				)
			),
			'login' => array(
				'title' => __( 'Login', 'viralpress' ),
				'content' => '[viralpress_login_page]',
				'user' => 'guest',
				'add_page' => true,
				'add_menu' => true
			),
			'profile' => array(
				'title' => __( 'Profile', 'viralpress' ),
				'content' => '[viralpress_profile_page]',
				'user' => 'subscriber',
				'add_page' => true,
				'add_menu' => false
			),
			'register' => array(
				'title' => __( 'Register', 'viralpress' ),
				'content' => '[viralpress_registration_page]',
				'user' => 'guest',
				'add_page' => true,
				'add_menu' => true
			),
			'meme-generator' => array(
				'title' => __( 'Meme generator', 'viralpress' ),
				'content' => '[viralpress_meme_generator]',
				'user' => 'guest',
				'add_page' => true,
				'add_menu' => false
			),
			'create' => array(
				'title' => __( 'Submit post', 'viralpress' ),
				'content' => '[viralpress_user_create_entry]',
				'user' => 'subscriber',
				'url' => home_url( $this->settings['page_slugs']['create']. '/' ),
				'add_page' => true,
				'add_menu' => true,
				/*'sub_menu' => array(
					'create-news' => array(
						'title' => __( 'News', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=news'
					),
					'create-lists' => array(
						'title' => __( 'List', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=list'
					),
					'create-images' => array(
						'title' => __( 'Image', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=image'
					),
					'create-meme' => array(
						'title' => __( 'Meme', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=meme'
					),
					'create-quiz' => array(
						'title' => __( 'Quiz', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=quiz'
					),
					'create-polls' => array(
						'title' => __( 'Poll', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=poll'
					),
					'create-videos' => array(
						'title' => __( 'Video', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=video'
					),
					'create-audio' => array(
						'title' => __( 'Audio', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=audio'
					),
					'create-gallery' => array(
						'title' => __( 'Gallery', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=gallery'
					),
					'create-playlist' => array(
						'title' => __( 'Playlist', 'viralpress' ),
						'url' => home_url( '/' ) . ( empty( $this->settings['page_slugs']['create'] ) ? 'create' : $this->settings['page_slugs']['create'] ).'/?type=playlist'
					)
				)*/
			),
			'dashboard' => array(
				'title' => __( 'Dashboard', 'viralpress' ),
				'content' => '[viralpress_user_dashboard]',
				'user' => 'subscriber',
				'add_page' => true,
				'add_menu' => false
			),
			'welcome' => array(
				//[current-username] shortcode removed from menu
				'title' => __( '[current-username]', 'viralpress' ),
				'user' => 'subscriber',
				'url' => 'javascript:void(0)',
				'add_page' => false,
				'add_menu' => true,
				'sub_menu' => array(
					'dashboard' => array(
						'title' => __( 'Dashboard', 'viralpress' ),
						'url' => home_url( empty( $this->settings['page_slugs']['dashboard'] ) ? 'dashboard/' : $this->settings['page_slugs']['dashboard'].'/' )
					),
					'profile' => array(
						'title' => __( 'Profile', 'viralpress' ),
						'url' => home_url( empty( $this->settings['page_slugs']['profile'] ) ? 'profile/' : $this->settings['page_slugs']['profile'].'/' )
					),
					'myposts' => array(
						'title' => __( 'My Posts', 'viralpress' ),
						'url' => home_url( empty( $this->settings['page_slugs']['profile'] ) ? 'profile/' : $this->settings['page_slugs']['profile'].'/' )
					),
					'post-comments' => array(
						'title' => __( 'Post comments', 'viralpress' ),
						'url' => home_url( '/post-comments/' )
					),
					'my-comments' => array(
						'title' => __( 'My comments', 'viralpress' ),
						'url' => home_url( '/my-comments/' )
					),
					'logout' => array(
						'title' => __( 'Logout', 'viralpress' ),
						'url' => home_url( empty( $this->settings['page_slugs']['dashboard'] ) ? 'dashboard/' : $this->settings['page_slugs']['dashboard'].'/' ). '?logout=true&t=vp_logout_t',
					)
				)
			),
			'password-lost' => array(
				'title' => __( 'Forgot Your Password?', 'viralpress' ),
				'content' => '[vp_password_lost_form]',
				'user' => 'guest',
				'add_page' => true,
				'add_menu' => false
			),
			'password-reset' => array(
				'title' => __( 'Pick a New Password', 'viralpress' ),
				'content' => '[vp_password_reset_form]',
				'user' => 'guest',
				'add_page' => true,
				'add_menu' => false
			)
		);
		
		/**
		 * add some buddypress menus
		 */
		if( function_exists( 'bp_core_get_userlink' ) ) {
			$m = $this->vp_pages[ 'welcome' ][ 'sub_menu' ];
			
			$a = array( 
					'vp-bp-profile' => array(
						'title' => __( 'Profile', 'viralpress' ),
						'url' => '[current-bp-user-url]',
					)
				);
			
			unset( $m['profile'] );
			vp_array_splice_assoc( $m, 1, 0, $a );
			
			$a = array(
				'vp-bp-noti' => array(
						'title' => __( 'Notification', 'viralpress' ),
						'url' => '[current-bp-noti-url]',
					)
				);	
			vp_array_splice_assoc( $m, 2, 0, $a );	
			
			$a = array(
				'vp-bp-openlist' => array(
						'title' => __( 'My Open Lists', 'viralpress' ),
						'url' => '[current-bp-op-url]',
					)
				);	
			vp_array_splice_assoc( $m, 2, 0, $a );	
			
			$this->vp_pages[ 'welcome' ][ 'sub_menu' ] = $m;			
		}
	}
	
	/**
	 * displays custom post type
	 * @since 3.3
	 */
	public function vp_custom_content_single( $content ) 
	{
		global $post;
		
		if( vp_validate_post_type( $post->post_type ) && is_single() ) {
			$ttt = '<div class="vp-clearfix"></div>
					<style>.vp-entry fieldset{border:none !important;}.vp-entry legend{display: none}.box_list,.inline_list{display:none}</style>';
			$ttt .= do_shortcode( '[vp_post_entry type="'.$post->post_type.'" id="'.$post->ID.'" order="0"]' );
			
			$ttt = apply_filters( 'viralpress_custom_post_single', $ttt );
			
			return $content.$ttt;
		}
		
		if( is_single() ) {
			
			$type = get_post_meta( $post->ID,  'vp_post_type' );
			if( empty( $type ) ) return $content;
			
			$ttt = '<div class="vp-clearfix"></div>';
			$ttt .= '<div class="row vp_after_content">';
			
			$buttons = array();
			
			$editable = current_user_can( 'edit_post', $post->ID );
			$edit_link = '';
			$delete_link = '';
			
			if( $editable ) {
				$buttons['edit'] = 1;
				$buttons['delete'] = 1;
			}
			
			if( !empty( $this->settings['allow_copy'] ) ) {
				
				$cc = get_post_meta( $post->ID, 'vp_allow_copy' );
				$cc = @(int)$cc[0];
				
				if( $cc ) {	
					$buttons['copy'] = 1;			
				}
			}	
			
			if( !empty( $this->settings['allow_open_list'] ) && empty( $_REQUEST['vp_preview'] ) ) {
				
				$oplist = get_post_meta( $post->ID, 'vp_open_list' );
				$oplist = @(int)$oplist[0];
				
				if( $oplist ) {
					$this->temp_vars['open_list_'.$post->ID] = 1;
					$buttons['op'] = 1;
				}
			}
			
			if( $editable || !empty( $buttons['copy'] ) ) {
				if( !empty( $type ) ) {
					$edit_link = home_url( $this->settings['page_slugs']['create'] ).'?type='.$type[0].'&id='.$post->ID;
					$n = wp_create_nonce( 'delete-post_'.$post->ID );
					$delete_link = $edit_link.'&_nonce='.$n.'&delete=1';
				}	
			}
			
			$op_text = __( 'Add your submission', 'viralpress' );
			$copy_text = __( 'Copy this post', 'viralpress' );
			$fs = '';
			
			$ot = rtrim( $type[0], 's' );
			if( $ot == 'new' ) $ot = 'news';
			
			if( count( $buttons ) == 1 ) {
				$entries = vp_count_entries( $post->ID );
				$d = '<div class="col-lg-12 vp_after_content_row">';	
				$op_text = sprintf( __( '%d people submitted their %s to this post. Add your awesome %s now.', 'viralpress' ), $entries, $ot, $ot );
				$copy_text = __( 'Copy this post & create a new one', 'viralpress' );
				$fs = '17px';
			}
			else if( count( $buttons ) == 2 ) {
				$d = '<div class="col-lg-6 vp_after_content_row">';	
				$op_text = sprintf( __( 'Add your awesome %s to this post', 'viralpress' ), $ot  );
				$copy_text = __( 'Copy this post & create a new one', 'viralpress' );
				$fs = '15px';
			}
			else if( count( $buttons ) == 3 ) {
				$d = '<div class="col-lg-4 vp_after_content_row">';	
				$op_text = sprintf( __( 'Add your %s', 'viralpress' ), $ot );
				$copy_text = __( 'Copy this post', 'viralpress' );
			}
			else if( count( $buttons ) == 4 ) {
				$d = '<div class="col-lg-3 vp_after_content_row">';	
				$op_text = sprintf( __( 'Add your %s', 'viralpress' ), $ot );
				$copy_text = __( 'Copy this post', 'viralpress' );
			}
			
			if( !empty( $buttons['edit'] ) ) {
				$ttt .= $d;
				$ttt .= '<a class="btn btn-info" href="'.$edit_link.'">
							<i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;'.__( 'Edit this post', 'viralpress' ).'
					  	</a>';
				$ttt .= '</div>';	
			}
			
			if( !empty( $buttons['delete'] ) ) {
				$ttt .= $d;
				$ttt .= '<a class="btn btn-danger" href="'.$delete_link.'">
							<i class="glyphicon glyphicon-trash"></i>&nbsp;&nbsp;'.__( 'Delete this post', 'viralpress' ).'
					  	</a>';
				$ttt .= '</div>';	
			}
			
			if( !empty( $buttons['copy'] ) ) {
				$ttt .= $d;
				$ttt .= '<a class="btn btn-primary vp_copy_post" href="'.$edit_link.'&copy=1">
							<i class="glyphicon glyphicon-paste"></i>&nbsp;&nbsp;'.$copy_text.'
						  </a>';
				$ttt .= '</div>';	
			}
			
			if( !empty( $buttons['op'] ) ) {				
				$ttt .= $d;
				$ttt .= '<a class="btn btn-info show_open_list_editor" href="javascript:void(0)" data-rel="'.$post->ID.'">
							<i class="glyphicon glyphicon-indent-left"></i>&nbsp;&nbsp;'.$op_text.'
						  </a>';	
				$ttt .= '</div>';	
			}
			
			$ttt .= '</div><div class="vp-clearfix"></div>';
			
			
			if( !empty( $this->settings['allow_open_list'] ) && is_user_logged_in() && empty( $_REQUEST['vp_preview'] ) ) {
				
				if( !empty( $this->temp_vars['open_list_'.$post->ID] ) ) {
					$ttt .= '<div class="open_list_editor" style="display:none">';		
					$ttt .= '</div>';
					
					$ttt .= '<div class="open_list_editor_load_feed" style="display:none"><div class="vp-clearfix-lg"></div>
							<div class="alert alert-info">'.__( 'Loading editor....', 'viralpress' ).'</div>
						  </div>';
					
					if( $this->settings['recap_post'] && $this->settings['load_recap'] ){
						$ttt .= 
						'<div class="recap_openlist" style="display:none">
						<div class="vp-clearfix"></div>
						'.recaptcha_get_html($this->settings['recap_key'], '').'
						<div class="vp-clearfix"></div>
						</div>';
					}
                    			
					if ( ! did_action( 'wp_enqueue_media' ) )wp_enqueue_media();
					add_thickbox();
					
					$ttt .= '<style>.edit-attachment{display:none !important}</style>';
					$ttt .= vp_text_entry_form();	
					$ttt .= '<div class="vp-clearfix-lg"></div>';
				}
			}
			
			
			if( !empty( $fs ) ) $ttt .= '<style>.vp_after_content a,.vp_after_content button{font-size:'.apply_filters( 'vp_content_buttons_font_size', $fs ).'}</style>';
			
			$ttt .= '<style>.vp_after_content_row a,.vp_after_content_row button{white-space: normal !important;}</style>';
			$ttt = apply_filters( 'viralpress_after_content', $ttt, $post );
			
			return $content.$ttt;	
		}
		
		return $content;
	}
	
	/**
	 * helper to force parse shortcodes in menu items
	 * @since 1.0
	 */
	public function menu_shortcodes( $menu ) {
		$menu = do_shortcode( $menu );
		/**
		 * support for older wordpress
		 */
		$menu = str_replace( array( '[current-user-url]', 'current-user-url' ) , $this->vp_get_current_user_url(), $menu );
		$menu = str_replace( array( '[current-bp-user-url]', 'current-bp-user-url' ) , $this->vp_bp_get_current_user_url(), $menu );
		$menu = str_replace( array( '[current-bp-noti-url]', 'current-bp-noti-url' ) , $this->vp_bp_get_noti_url(), $menu );
		$menu = str_replace( array( '[current-bp-op-url]', 'current-bp-op-url' ) , $this->vp_bp_get_op_url(), $menu );
		$menu = str_replace( array( '[current-username]', 'current-username' ), $this->ss_get_current_username(), $menu );
		$menu = str_replace( array( 'vp_logout_t', '[vp_logout_t]' ), time(), $menu );
		
		return $menu; 	
	}
	
	/**
	 * removes http or https from author url
	 * needs to remove double http in nav menu author url
	 * needs to format correct author url in nav menu
	 * @since 1.0
	 */
	public function vp_get_current_user_url( ) {
		$uid = get_current_user_id();
		return preg_replace( '/(http|https):\/\//i', '', get_author_posts_url( $uid ) );	
	}
	
	/**
	 * gets buddypress author URL
	 * removes http or https from author url
	 * needs to remove double http in nav menu author url
	 * needs to format correct author url in nav menu
	 * @since 3.0
	 */
	public function vp_bp_get_current_user_url()
	{
		if( function_exists( 'bp_core_get_userlink' )) {
			$bp_uid = bp_loggedin_user_id();
			return preg_replace( '/(http|https):\/\//i', '', bp_core_get_userlink( $bp_uid, false, true ) );
		}
		else return $this->vp_get_current_user_url();
		return;
	}
	
	/**
	 * gets buddypress notification URL
	 * removes http or https from author url
	 * needs to remove double http in nav menu author url
	 * needs to format correct author url in nav menu
	 * @since 3.2
	 */
	public function vp_bp_get_noti_url()
	{
		if( function_exists( 'bp_core_get_userlink' )) {
			$bp_uid = bp_loggedin_user_id();
			return preg_replace( '/(http|https):\/\//i', '', bp_core_get_userlink( $bp_uid, false, true ) ).'notifications/';
		}
		else return $this->vp_get_current_user_url();
		return;
	}
	
	/**
	 * gets buddypress openlists URL
	 * removes http or https from author url
	 * needs to remove double http in nav menu author url
	 * needs to format correct author url in nav menu
	 * @since 3.2
	 */
	public function vp_bp_get_op_url()
	{
		if( function_exists( 'bp_core_get_userlink' )) {
			$bp_uid = bp_loggedin_user_id();
			return preg_replace( '/(http|https):\/\//i', '', bp_core_get_userlink( $bp_uid, false, true ) ).'openlist/';
		}
		else return $this->vp_get_current_user_url();
		return;
	}
	
	/**
	 * nav menu author name
	 * disabled since username can be large to show on nav menu
	 * @since 1.0
	 */
	public function ss_get_current_username() {
		$user = wp_get_current_user();
		return preg_replace('/[^a-z0-9\.\_]/i', '', $user->display_name);
	}
	
	/**
	 * print random time to skip wordpress redirect cache
	 * @since 1.3
	 */
	public function vp_print_time()
	{
		return time();
	}
	
	/**
	 * loads viralpress post types
	 * @since 1.0
	 */
	public function load_post_types()
	{
		$this->vp_post_types = array(
			array(
				'type' => 'news',
				'name' => __( 'News' ),
				'labels' => array(
					'name' => __( 'News' ),
					'singular_name' => __( 'News' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New News' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit News' ),
					'new_item' => __( 'New News' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All News' ),
					'search_items' => __( 'Search News' ),
					'not_found' => __( 'No News found' ),
					'not_found_in_trash' => __( 'No News found in Trash' ),
					'parent' => __( 'Parent News' )
				)
			),
			array(
				'type' => 'quiz',
				'name' => 'Quiz',
				'labels' => array(
					'name' => __( 'Quiz' ),
					'singular_name' => __( 'Quiz' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Quiz' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Quiz' ),
					'new_item' => __( 'New Quiz' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Quiz' ),
					'search_items' => __( 'Search Quiz' ),
					'not_found' => __( 'No Quiz found' ),
					'not_found_in_trash' => __( 'No Quiz found in Trash' ),
					'parent' => __( 'Parent Quiz' )
				)
			),
			array(
				'type' => 'polls',
				'name' => 'Polls',
				'labels' => array(
					'name' => __( 'Polls' ),
					'singular_name' => __( 'Poll' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Poll' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Poll' ),
					'new_item' => __( 'New Poll' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Polls' ),
					'search_items' => __( 'Search Polls' ),
					'not_found' => __( 'No Poll found' ),
					'not_found_in_trash' => __( 'No Poll found in Trash' ),
					'parent' => __( 'Parent Poll' )
				)
			),
			array(
				'type' => 'lists',
				'name' => 'Lists',
				'labels' => array(
					'name' => __( 'Lists' ),
					'singular_name' => __( 'List' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New List' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit List' ),
					'new_item' => __( 'New List' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Lists' ),
					'search_items' => __( 'Search Lists' ),
					'not_found' => __( 'No List found' ),
					'not_found_in_trash' => __( 'No List found in Trash' ),
					'parent' => __( 'Parent List' )
				)
			),
			array(
				'type' => 'images',
				'name' => 'Images',
				'labels' => array(
					'name' => __( 'Images' ),
					'singular_name' => __( 'Image' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Image' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Image' ),
					'new_item' => __( 'New Image' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Images' ),
					'search_items' => __( 'Search Images' ),
					'not_found' => __( 'No Image found' ),
					'not_found_in_trash' => __( 'No Image found in Trash' ),
					'parent' => __( 'Parent Image' )
				)
			),
			array(
				'type' => 'meme',
				'name' => 'Meme',
				'labels' => array(
					'name' => __( 'Meme' ),
					'singular_name' => __( 'Meme' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Meme' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Meme' ),
					'new_item' => __( 'New Meme' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Meme' ),
					'search_items' => __( 'Search Meme' ),
					'not_found' => __( 'No Meme found' ),
					'not_found_in_trash' => __( 'No Meme found in Trash' ),
					'parent' => __( 'Parent Meme' )
				)
			),
			array(
				'type' => 'gallery',
				'name' => 'Gallery',
				'labels' => array(
					'name' => __( 'Gallery' ),
					'singular_name' => __( 'Gallery' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Gallery' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Gallery' ),
					'new_item' => __( 'New Gallery' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Galleries' ),
					'search_items' => __( 'Search Galleries' ),
					'not_found' => __( 'No Gallery found' ),
					'not_found_in_trash' => __( 'No Gallery found in Trash' ),
					'parent' => __( 'Parent Gallery' )
				)
			),
			array(
				'type' => 'playlist',
				'name' => 'Playlist',
				'labels' => array(
					'name' => __( 'Playlist' ),
					'singular_name' => __( 'Playlist' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Playlist' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Playlist' ),
					'new_item' => __( 'New Playlist' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Playlist' ),
					'search_items' => __( 'Search Playlist' ),
					'not_found' => __( 'No Playlist found' ),
					'not_found_in_trash' => __( 'No Playlist found in Trash' ),
					'parent' => __( 'Parent Playlist' )
				)
			),
			array(
				'type' => 'videos',
				'name' => 'Videos',
				'labels' => array(
					'name' => __( 'Videos' ),
					'singular_name' => __( 'Video' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Video' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit List' ),
					'new_item' => __( 'New Video' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Videos' ),
					'search_items' => __( 'Search Videos' ),
					'not_found' => __( 'No Video found' ),
					'not_found_in_trash' => __( 'No Video found in Trash' ),
					'parent' => __( 'Parent Video' )
				)
			),
			array(
				'type' => 'audio',
				'name' => 'Audio',
				'labels' => array(
					'name' => __( 'Audio' ),
					'singular_name' => __( 'Audio' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Audio' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Audio' ),
					'new_item' => __( 'New Audio' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Audio' ),
					'search_items' => __( 'Search Audio' ),
					'not_found' => __( 'No Audio found' ),
					'not_found_in_trash' => __( 'No Audio found in Trash' ),
					'parent' => __( 'Parent Audio' )
				)
			),
			array(
				'type' => 'pins',
				'name' => 'Pins',
				'labels' => array(
					'name' => __( 'Pin' ),
					'singular_name' => __( 'Pins' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Pin' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Pin' ),
					'new_item' => __( 'New Pin' ),
					'view' => __( 'View' ),
					'view_item' => __( 'View All Pins' ),
					'search_items' => __( 'Search Pin' ),
					'not_found' => __( 'No Pin found' ),
					'not_found_in_trash' => __( 'No Pin found in Trash' ),
					'parent' => __( 'Parent Pin' )
				)
			)
		);
	}
	
	
	/**
	 * hook on theme changge
	 * searches the full width template when theme changes and assign it to viralpress pages
	 * @since 1.0
	 */
	public function vp_theme_change_hook()
	{
		$this->load_page_definitions();
		$full_width = '';
		
		if( file_exists( $this->settings['WP_ADMIN'].'/includes/theme.php' ) ) {
			require_once( $this->settings['WP_ADMIN'].'/includes/theme.php');
			
			if( function_exists( 'get_page_templates' ) ) {
				$p = $this->full_width_regex;
				$templates = get_page_templates();
				foreach ( $templates as $template_name => $template_filename ) {
					if( preg_match($p, $template_name) || preg_match( $p, $template_filename) ){
						$full_width = $template_filename;
						break;
					}
				}
			}
		}
		
		if( empty($full_width) ) return false;
		
		foreach ( $this->vp_pages as $slug => $page ) {
			if( !$page['add_page'] )continue;
			
			$post = get_page_by_path( $slug ) ;
			if( !empty($post) ) {
				$page_data = array(
					'ID'      		  => $post->ID,
					'page_template'   => $full_width
				);	
				wp_update_post( $page_data );
			}
		}
	}
	
	/**
	 * insert activity feed on buddypress profiles
	 * @since 1.1
	 */
	public function vp_post_status_update( $new, $old, $post )
	{
		if( $new == 'publish' && $old != 'publish' )vp_bp_add_activity( $post );
		else if( $old == 'publish' && ( $new == 'pending' || $new == 'trash' ) )vp_bp_delete_activity( $post );
	}
	
	/**
	 * insert posts tab on buddypress
	 * @since 1.1
	 */
	public function vp_add_profileposts_tab()
	{
		global $bp;
		
		if( empty( $this->settings['vp_bp'] ) ) return;
		
		bp_core_new_nav_item( array(
			'name' => __( 'Posts', 'viralpress' ),
			'slug' => 'posts',
			'screen_function' => 'vp_bp_postsonprofile',
			'default_subnav_slug' => __( 'Posts', 'viralpress' ), 
			'position' => 20
		));
		
		/*
		bp_core_new_nav_item( array(
			'name' => __( 'Drafts', 'viralpress' ),
			'slug' => 'drafts',
			'screen_function' => 'vp_bp_draftpostsonprofile',
			'show_for_displayed_user' => false,
			'default_subnav_slug' => __( 'Drafts', 'viralpress' ), 
			'position' => 21
		));
		
		bp_core_new_nav_item( array(
			'name' => __( 'Pending', 'viralpress' ),
			'slug' => 'pending',
			'screen_function' => 'vp_bp_pendingpostsonprofile',
			'show_for_displayed_user' => false,
			'default_subnav_slug' => __( 'Pending', 'viralpress' ), 
			'position' => 22
		));
		*/
		
		bp_core_new_nav_item( array(
			'name' => __( 'Open Lists', 'viralpress' ),
			'slug' => 'openlist',
			'screen_function' => 'vp_bp_openpostsonprofile',
			'default_subnav_slug' => __( 'Open Lists', 'viralpress' ), 
			'position' => 23
		));
	}
	

	/**
	 * call back on approve comment to increase notification count
	 * @since 3.0
	 */
	public function vp_approve_comment_callback( $comment_ID, $approved )
	{
		if($approved ) {
			$comment = get_comment( $comment_ID );
			$pid = $comment->comment_post_ID;
			$post_author = get_post_field( 'post_author', $pid );
			$comment_author = $comment->user_id;
			
			$reply = 0;
			$to = $post_author;
			
			if( !empty( $comment->comment_parent ) ) {
				$to = get_comment( $comment->comment_parent );
				$to = $to->user_id;
				$reply = 1;	
			}		
			
			if( $to != $comment_author ) {				
				$a = get_user_meta( $to, 'vp_comment_noti_count' );
				$a = @(int)end($a);
				$a++;
				add_update_user_meta( $to, 'vp_comment_noti_count', $a );
				vp_bp_send_comment_noti( $comment, $to, $reply );
			}
		}
	}
	
	/**
	 * add a column in admin panel to show post type
	 * @since 3.4
	 */
	public function vp_posts_column( $defaults )
	{
		$defaults[ 'vp_custom' ] = __( 'ViralPress', 'viralpress' );
		return $defaults;
	}
	
	/**
	 * add content to custom column in admin panel to show post type
	 * @since 3.4
	 */
	public function vp_posts_column_display( $name, $post_id )
	{
		if( $name == 'vp_custom' ) {
			$ok = get_post_meta( $post_id, 'vp_post_type' );
			
			if( !empty( $ok[0] ) ) {	
			
				$html = '';			
				$op = get_post_meta( $post_id, 'vp_open_list' );
				if( !empty( $op[0] ) ) $html .= __( 'Open list', 'viralpress' ).'<br/>';
				
				if ( current_user_can( 'edit_post', $post->ID ) || empty($ok[0]) ) {		
					$html .= '<a href="' . home_url( $this->settings['page_slugs']['create'].'/?type='.$ok[0].'&id='.$post_id ) 
						. '" title="'
						. esc_attr( __( 'Edit with ViralPress editor', 'viralpress'  ) ) 
						. '">' . sprintf( __( '%s editor', 'viralpress' ), ucwords( $ok[0] ) ) . '</a><br/>';
						
					if($ok[0] == 'polls'){
						$html .= '<a class="thickbox" href="' .( 'admin.php?page=viralpress-poll&poll_id='.$post_id.'&TB_iframe=true' ) 
						. '" title="'
						. esc_attr( __( 'View poll results', 'viralpress'  ) ) 
						. '">' . __( 'Poll results', 'viralpress'  ) . '</a><br/>';	
					}
					else if($ok[0] == 'quiz'){
						$html .= '<a class="thickbox" href="' .( 'admin.php?page=viralpress-quiz-share&quiz_id='.$post_id.'&TB_iframe=true' ) 
						. '" title="'
						. esc_attr( __( 'View quiz shares', 'viralpress'  ) ) 
						. '">' . __( 'Quiz shares', 'viralpress'  ) . '</a><br/>';	
					}
					
					$html .= ucwords( $m[0] );	
				}
				
				echo $html;
			}	
			//else echo __( 'Wordpress' , 'viralpress' );
		}
	}
	
	/**
	 * show pending post count in admin menu
	 * @since 1.0
	 */
	public function show_pending_count( $menu )
	{
		$num_posts = wp_count_posts( 'post', 'readable' );
		$status = "pending";
		$pending_count = 0;
		if ( !empty($num_posts->$status) )
			$pending_count = $num_posts->$status;
		
		foreach( $menu as $menu_key => $menu_data ) :
			if( 'edit.php' != $menu_data[2] )continue;
			$menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
		endforeach;

		return $menu;

	}
	
	/**
	 * show pending post count in admin menu
	 * @since 3.0
	 */
	public function show_pending_open_list_count( $menu )
	{
		$pending_count = vp_count_pending_open_list();
		
		foreach( $menu as $menu_key => $menu_data ) :
			if( 'viralpress-openlists' != $menu_data[2] )continue;
			$menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
		endforeach;

		return $menu;

	}
	
	/**
	 * shows viralpress custom post types in widgets
	 * not used currently
	 * @since 1.0
	 */
	public function vp_widget_post_args($params) {
		/*$this->load_post_types();
		$types = array( 'post' );
		
		foreach( $this->vp_post_types as $post_type )
			$types[] = $post_type[ 'type' ];
			
	   	$params['post_type'] = $types;*/
	   	return $params;
	}
	
	/**
	 * front end edit post links
	 * @since 1.0
	 * @changed 1.2
	 */
	public function vp_edit_post_link( $link, $post_id)
	{
		$this->load_post_types();
		$types = array();
		
		$c =  $this->is_contributor;
		if( get_post_type( $post_id ) == 'attachment' && $c ) {
			return home_url( $this->settings['page_slugs']['create'].'?type=none' );	
		}
		
		foreach( $this->vp_post_types as $post_type )
			$types[] = $post_type[ 'type' ];
		
		$type = get_post_meta( $post_id,  'vp_post_type' );
		if( !empty( $type ) &&  ( !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) ) {
			return home_url( $this->settings['page_slugs']['create'].'?type='.$type[0].'&id='.$post_id );
		}
		
		return $link;
	}
	
	/**
	 * front end delete post links
	 * @since 1.0
	 */
	public function vp_delete_post_link( $link, $post_id)
	{
		$this->load_post_types();
		$types = array();
		
		foreach( $this->vp_post_types as $post_type )
			$types[] = $post_type[ 'type' ];
		
		$type = get_post_meta( $post_id,  'vp_post_type' );
		if( !empty($type) && !is_admin() ) {
			return home_url( $this->settings['page_slugs']['create'].'?type='.$type[0].'&id='.$post_id.'&delete=true&_nonce='.wp_create_nonce( 'delete-post_'.$post_id ) );
		}
		
		return $link;
	}
	
	/**
	 * adds post edit permissions
	 * @since 1.0
	 */
	public function vp_map_meta_cap( $caps, $cap, $user_id, $args ){
		if ( 'edit_post' == $cap && !empty( $post->post_type) ) {
			$post = get_post( $args[0] );
			$post_type = get_post_type_object( $post->post_type );
			$caps = array();
			if ( $user_id == $post->post_author )
				$caps[] = $post_type->cap->edit_posts;
			else
				$caps[] = $post_type->cap->edit_others_posts;
		}
		return $caps;
	}
	
	/**
	 * restrict mime to images only
	 * @since 1.0
	 * @edited 1.3
	 */
	public function vp_restrict_mime($mimes) {
		if( $this->is_contributor || !is_admin() ) {
			$mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
				'mp4' => 'video/mp4',
				'webm' => 'video/webm',
				'ogg' => 'video/ogg',
				'mp3' => 'audio/mp3',
				'mpeg' => 'audio/mpeg'
			);
			
			if( !$this->settings['self_video'] ) {
				unset( $mimes['mp4'] );
				unset( $mimes['webm'] );
				unset( $mimes['ogg'] );
			}
			
			if( !$this->settings['self_audio'] ) {
				unset( $mimes['mp3'] );
				unset( $mimes['mpeg'] );
			}
		}
		return $mimes;
	}
	
	/**
	 * open in viralpress editor link to admin panel
	 * @since 1.6
	 */
	public function add_vp_editor_link( $actions, $id )
	{
		global $post, $current_screen, $mode;

       	$ok = get_post_meta( $post->ID, 'vp_post_type' );

        if ( ! current_user_can( 'edit_post', $post->ID ) || empty($ok[0]) )
            return $actions;

        $actions['vp_editor'] = '<a href="' . home_url( $this->settings['page_slugs']['create'].'/?type='.$ok[0].'&id='.$post->ID ) 
            . '" title="'
            . esc_attr( __( 'Edit with ViralPress Editor', 'viralpress'  ) ) 
            . '">' . __( 'ViralPress Editor', 'viralpress'  ) . '</a>';
			
		if($ok[0] == 'polls'){
			$actions['vp_poll_results'] = '<a class="thickbox" href="' .( 'admin.php?page=viralpress-poll&poll_id='.$post->ID.'&TB_iframe=true' ) 
            . '" title="'
            . esc_attr( __( 'View poll results', 'viralpress'  ) ) 
            . '">' . __( 'Poll results', 'viralpress'  ) . '</a>';	
		}
		else if($ok[0] == 'quiz'){
			$actions['vp_quiz_shares'] = '<a class="thickbox" href="' .( 'admin.php?page=viralpress-quiz-share&quiz_id='.$post->ID.'&TB_iframe=true' ) 
            . '" title="'
            . esc_attr( __( 'View quiz shares', 'viralpress'  ) ) 
            . '">' . __( 'Quiz shares', 'viralpress'  ) . '</a>';	
		}

        return $actions;
	}
	
	/**
	 * new author base for viralpress
	 * not used currently
	 * @since 1.0
	 */
	public function vp_new_author_base()
	{
		/*global $wp_rewrite;
    	$author_slug = 'profile';
    	$wp_rewrite->author_base = $author_slug;*/
	}
	
	/**
	 * viralpress admin bar links
	 * @since 3.0
	 */
	public function vp_admin_bar_links( $wp_admin_bar )
	{
		if( is_admin_bar_showing() ) {
			global $pagenow;
			
			if( in_array( $pagenow, array( 'post.php' ) ) ) {
				global $post;
				/**
				 * meme generator
				 */
				if( $post->post_type == 'attachment' && in_array( $post->post_mime_type, array( 'image/png', 'image/jpg', 'image/jpeg', 'image/gif' ) ) ) {
					$args = array(
						'id'    => 'vp_meme_gen',
						'title' => __( 'Generate meme', 'viralpress' ),
						'href'  => home_url( '/meme-generator?media_id='.$post->ID ),
						'meta'  => array( 'class' => 'vp-meme-page' )
					);
					$wp_admin_bar->add_node( $args );	
				}	
				else if( $post->post_type == 'post' && preg_match( '/\[vp_post_entry/i', $post->post_content ) ) {
					$args = array(
						'id'    => 'vp_editor_page',
						'title' => __( 'Open in ViralPress Editor', 'viralpress' ),
						'href'  => home_url( $this->settings['page_slugs']['create'].'/?id='.$post->ID ),
						'meta'  => array( 'class' => 'vp-editor-page' )
					);
					$wp_admin_bar->add_node( $args );	
				}
			}
			
			if( current_user_can( 'edit_posts' ) ) {
				$args = array(
					'id'    => 'vp_editor_pages',
					'title' => __( 'ViralPress', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=list' ),
					'meta'  => array( 'class' => 'vp-editor-pages' )
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_news',
					'title' => __( 'News', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=news' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_lists',
					'title' => __( 'List', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=list' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_images',
					'title' => __( 'Image', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=image' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_meme',
					'title' => __( 'Meme', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=meme' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_video',
					'title' => __( 'Video', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=video' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_audio',
					'title' => __( 'Audio', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=audio' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_gallery',
					'title' => __( 'Gallery', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=gallery' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_playlist',
					'title' => __( 'Playlist', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'?type=playlist' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_quiz',
					'title' => __( 'Quiz', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'/?type=quiz' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );
				$args = array(
					'id'    => 'vp_editor_pages_poll',
					'title' => __( 'Poll', 'viralpress' ),
					'href'  => home_url( $this->settings['page_slugs']['create'].'?type=poll' ),
					'meta'  => array( 'class' => 'vp-editor-pages' ),
					'parent'=> 'vp_editor_pages'
				);
				$wp_admin_bar->add_node( $args );	
			}
		}
	}
	
	/**
	 * get custom avatar for viralpress
	 * @since 1.0
	 */
	public function vp_gravatar( $avatar, $id_or_email, $size, $default, $alt )
	{
		if( empty( $this->settings['custom_profiles'] ) ) return $avatar;
		
		if( is_numeric( $id_or_email) ){
			$imgpath = get_user_meta( $id_or_email, 'vp_avatar');
		}
		else if( is_string($id_or_email) ){
			$uu = get_user_by( 'email', $id_or_email );
			$imgpath = @get_user_meta( $uu->ID, 'vp_avatar');	
		}
		else if( !empty($id_or_email->user_id) ) {
			$imgpath = get_user_meta( $id_or_email->user_id, 'vp_avatar');		
		}
	
		if( !empty($imgpath[0]) && is_numeric( $imgpath[0]) ){
			$imgpath = wp_get_attachment_image_src( $imgpath[0], array( $size, $size ) );
			$imgpath = array( $imgpath[0] );
		}
		
		if( !empty( $imgpath ) ){
			return "<img src='".end($imgpath)."' alt='".$alt."' height='".$size."' width='".$size."' class='avatar photo'/>";
		}
		return $avatar;
	}
	
	/**
	 * allow file uploads by contributors
	 * @since 1.0
	 * @changed 1.1 added edit published post
	 * @changed 1.2 added control for edit published post
	 */
	public function allow_contributor_uploads()
	{
		$w = 0;
		$e = 0;
		
		$edit_p = current_user_can( 'edit_published_posts' );
		$delete_p = current_user_can( 'delete_published_posts');
		
		if ( !current_user_can('upload_files') ) $w = 1; 
		if( ( !$edit_p || !$delete_p ) && !$this->settings['block_edits'] ) $e = 1;
		else if( ( $edit_p || $delete_p ) && $this->settings['block_edits'] ) $e = 1;
		
		if( $w || $e ) $contributor = get_role('contributor');
		if( $w )$contributor->add_cap('upload_files');
		
		if( !$this->settings['block_edits'] && $e ) {
			$contributor->add_cap( 'edit_published_posts' );
			$contributor->add_cap( 'delete_published_posts' );
		}
		
		if( $this->settings['block_edits'] && $e ) {
			$contributor->remove_cap( 'edit_published_posts' );
			$contributor->remove_cap( 'delete_published_posts' );
		}
	}
	
	/**
	 * prevent authors from viewing others attachments
	 * @since 1.0
	 */
	public function users_own_attachments( $where )
	{
		global $current_user;

		if( is_user_logged_in() && $this->is_contributor ){
			if( isset( $_POST['action'] ) ){
				if( $_POST['action'] == 'query-attachments' ){
					$where .= ' AND post_author='.$current_user->data->ID;
				}
			}
		}
		return $where;
	}
	
	/**
	 * prevent authors from viewing others attachments
	 * @since 3.0
	 */
	public function users_own_attachments_w( $wp_query_obj )
	{
		global $current_user, $pagenow;
		
		if( !$this->is_contributor ) return;
		
		if( !is_a( $current_user, 'WP_User') )
			return;
	
		if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
			return;
	
		//if( !current_user_can('delete_pages') )
		$wp_query_obj->set('author', $current_user->ID );
	
		return;
	}
	
	/**
	 * display our custom post types on homepage
	 * @since 1.0
	 */
	public function display_vp_posts_home( $q )
	{
		/*if(is_admin() || !$q->is_main_query() || ( !is_home() && !is_category() && !is_tag() && !is_feed() && !is_front_page() ))
        	return;
		
		$this->load_post_types();
		$types = array( 'post' );
		
		foreach( $this->vp_post_types as $post_type )
			$types[] = $post_type[ 'type' ];
		
		$q->set( 'post_type', $types );*/
	}
	
	/**
	 * shows different menu for different users i.e guest and subscribers
	 * @since 1.0
	 * @modified 1.1
	 * @modified 2.3
	 */
	public function vp_menu_args( $args = array() )
	{
		$menu_on = $this->settings['show_menu_on'];
		
		if( $this->settings['show_menu'] == 1 ) {
			
			if( $menu_on == 'primary' && !preg_match('/primary/i', $args['theme_location']) ) return $args;
			else if( $menu_on == 'secondary' && !preg_match('/secondary/i', $args['theme_location']) ) return $args;
						
			if( is_user_logged_in() ) {
				$args['menu'] = $this->vp_menus['subscriber'];
			} 
			else {
				$args['menu'] = $this->vp_menus['guest'];
			}
		}
	    return $args;	
	}
	
	/**
	 * register our custom post types
	 * @since 1.0
	 */
	public function register_vp_post_types()
	{
		$this->load_post_types();
		register_post_status( 'vp_open_list_pending', array( 'internal' => true, 'public' => false ) );
		register_post_status( 'vp_open_list', array( 'internal' => true, 'public' => false ) );
		
		foreach( $this->vp_post_types as $post_type ) { 
			register_post_type( $post_type['type'],
				array(
				  'labels' => $post_type['labels'],
				  'public' => true,
				  'has_archive' => true,
				  'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields', 'excerpt', 'post-formats' ),
				  'taxonomies' => array( 'category', 'post_tag' ),
				  'slug' => $post_type['type'],
				  'show_ui' => false
				)
			);
		}		
	}
	
	/**
	 * load viralpress textdomain
	 * @since 1.1
	 */
	public function load_vp_text_domain()
	{
		load_plugin_textdomain( 'viralpress', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
	}
	
	/**
	 * register ajax hooks
	 * @since 1.0
	 */
	public function register_ajax()
	{
		add_action( "wp_ajax_nopriv_vp_google_auth", "vp_google_auth" );
		add_action( "wp_ajax_nopriv_vp_fb_auth", "vp_fb_auth" );
		add_action( "wp_ajax_vp_download_image", "vp_download_image" );
		add_action( "wp_ajax_vp_get_noti_count", "vp_get_noti_count" );
		
		add_action( "wp_ajax_vp_load_open_list_editor", "vp_load_open_list_editor" );
		add_action( "wp_ajax_vp_poll_vote", "vp_poll_vote" );
		add_action( "wp_ajax_vp_post_react", "vp_post_react" );
		add_action( "wp_ajax_vp_upvote_item", "vp_upvote_item" );
		add_action( "wp_ajax_vp_downvote_item", "vp_downvote_item" );
		add_action( "wp_ajax_vp_like_item", "vp_like_item" );
		add_action( "wp_ajax_vp_dislike_item", "vp_dislike_item" );	
		add_action( "wp_ajax_vp_quiz_taken", "vp_quiz_taken" );
		add_action( "wp_ajax_nopriv_vp_quiz_taken", "vp_quiz_taken" );
		
		add_action( "wp_ajax_vp_meme_save", "vp_meme_save" );
		add_action( "wp_ajax_vp_open_list_submit", "vp_open_list_submit" );
		add_action( "wp_ajax_vp_open_list_del", "vp_open_list_del" );
		add_action( "wp_ajax_load_meme_modal", "load_meme_modal" );
		
	    if( !empty( $this->settings['anon_votes'] ) ) {
			add_action( "wp_ajax_nopriv_vp_poll_vote", "vp_poll_vote" );
			add_action( "wp_ajax_nopriv_vp_post_react", "vp_post_react" );
			add_action( "wp_ajax_nopriv_vp_upvote_item", "vp_upvote_item" );
			add_action( "wp_ajax_nopriv_vp_downvote_item", "vp_downvote_item" );
			add_action( "wp_ajax_nopriv_vp_like_item", "vp_like_item" );
			add_action( "wp_ajax_nopriv_vp_dislike_item", "vp_dislike_item" );		
		}
		
		add_action( "wp_ajax_vp_mass_delete_post", "vp_mass_delete_post" );
		add_action( "wp_ajax_vp_mass_draft_post", "vp_mass_draft_post" );
		add_action( "wp_ajax_vp_mass_publish_post", "vp_mass_publish_post" );
		add_action( "wp_ajax_vp_mass_publish_post", "vp_mass_publish_post" );
		add_action( "wp_ajax_vp_set_avatar", "vp_set_avatar" );
		add_action( "wp_ajax_vp_set_cover", "vp_set_cover" );
		add_action( "wp_ajax_vp_update_user", "vp_update_user" );
		add_action( "wp_ajax_vp_s_update_user", "vp_s_update_user" );
		add_action( "wp_ajax_vp_c_update_user", "vp_c_update_user" );
		add_action( "wp_ajax_vp_add_post", "vp_add_post" );
		add_action( "wp_ajax_vp_gif_react", "vp_gif_react" );
	}
	
	/**
	 * load js lang files
	 * @since 2.4
	 */
	public function getJSLang()
	{
		$op_url = home_url( $this->settings['page_slugs']['profile'].'/' );
		if( function_exists( 'bp_core_get_userlink' ) && $this->settings['vp_bp'] ) {
			$op_url = bp_core_get_userlink( get_current_user_id(), false, true ).'openlist';	
		}
		
		$lang = array(
			'add_tags' => __( 'Add tags to your post', 'viralpress' ),
			'add_preface' => __( 'Add preface', 'viralpress' ),
			'hide_preface' => __( 'Hide preface', 'viralpress' ),
			'optional' => __( 'Optional', 'viralpress' ),
			'op_ok' => __( 'Operation successful', 'viralpress' ),
			'saved' => __( 'Saved successfully', 'viralpress' ),
			'save' => __( 'Save', 'viralpress' ),
			'close' => __( 'Close', 'viralpress' ),
			'source' => __( 'Source', 'viralpress' ),
			'manage_media' => __( 'Manage media', 'viralpress' ),
			'upload_media' => __( 'Upload media', 'viralpress' ),
			'upload_avatar' => __( 'Upload new avatar', 'viralpress' ),
			'upload_cover' => __( 'Upload new cover', 'viralpress' ),
			'upload_from_url' => __( 'Upload from URL', 'viralpress' ),
			'login_success_wait' => __( 'Please wait...', 'viralpress' ),
			'login_failed' => __( 'Login failed', 'viralpress' ),
			'remove_entry' => __( 'Remove this entry', 'viralpress' ),
			'title' => __( 'Title', 'viralpress' ),
			'type_title' => __( 'Type a title of this entry', 'viralpress' ),
			'type_source' => __( 'Add a source URL', 'viralpress' ),
			'type_desc' => __( 'Type a description of this entry', 'viralpress' ),
			'type_qu' => __( 'Type a question or add an image', 'viralpress' ),
			'type_ans' => __( 'Type an answer or add an image', 'viralpress' ),
			'optional' => __( '(Optional)', 'viralpress' ),
			'required' => __( '(Required)', 'viralpress' ),
			'desc' => __( 'Description', 'viralpress' ),
			'add_photo' => __( 'Add a photo', 'viralpress' ),
			'show_details' => __( 'Show details', 'viralpress' ),
			'hide_details' => __( 'Hide details', 'viralpress' ),
			'toggle_editor' => __( 'Toggle editor', 'viralpress' ),
			'upload_photo' => __( 'Upload a photo', 'viralpress' ),
			'insert' => __( 'Insert', 'viralpress' ),
			'remove' => __( 'Remove', 'viralpress' ),
			'add_thumb' => __( 'Add a thumbnail', 'viralpress'  ),
			'downloading' => __( 'Downloading...', 'viralpress' ),
			'change_photo' => __( 'Change photo', 'viralpress' ),
			'add_video' => __( 'Upload video', 'viralpress' ),
			'add_audio' => __( 'Upload audio', 'viralpress' ),
			'add_pin' => __( 'Embed from websites', 'viralpress' ),
			'change_video' => __( 'Change video', 'viralpress' ),
			'change_audio' => __( 'Change audio', 'viralpress' ),
			'change_pin' => __( 'Change item', 'viralpress' ),
			'insert_url' => __( 'Insert a url', 'viralpress' ),
			'choose_valid_video_domain' => sprintf( __( 'Please insert a supported video host - youtube, dailymotion, vimeo, vine, bbc, ted, liveleak, facebook, %s', 'viralpress' ), $this->settings['allowed_embeds'] ),
			'choose_valid_audio_domain' => sprintf( __( 'Please insert a supported audio host - soundcloud, %s', 'viralpress') , $this->settings['allowed_embeds'] ),
			'choose_valid_pin_domain' => sprintf( __( 'Please insert a supported embed host - %s, %s', 'viralpress' ), implode(',', array( 'youtube', 'facebook', 'dailymotion', 'vimeo', 'ted', 'bbc', 'liveleak', 'instagram', 'fbpage', 'twitter', 'twitter_profile', 'vine', 'pinterest_pin', 'pinterest_board', 'pinterest_profile', 'gplus', 'soundcloud' , 'custom' )), $this->settings['allowed_embeds'] ),
			'choose_valid_pin_code' => __( 'Invalid embed code', 'viralpress'),
			'change_video' => __( 'Change video', 'viralpress' ),
			'invalid_url' => __( 'Failed to parse url', 'viralpress' ),
			'more_details' => __( 'More details', 'viralpress' ),
			'hide_details' => __( 'Hide details', 'viralpress' ),
			'poll' => __( 'Poll', 'viralpress' ),
			'text' => __( 'Text', 'viralpress' ),
			'quiz' => __( 'Quiz', 'viralpress' ),
			'image' => __( 'Image', 'viralpress' ),
			'video' => __( 'Video', 'viralpress' ),
			'audio' => __( 'Audio', 'viralpress' ),
			'pin' => __( 'Embed', 'viralpress' ),
			'results' => __( 'Results', 'viralpress' ),
			'question' => __( 'Question', 'viralpress' ),
			'possible_answers' => __( 'Possible answers', 'viralpress' ),
			'answers' => __( 'Answers', 'viralpress' ),
			'answer' => __( 'Answer', 'viralpress' ),
			'correct_answer' => __( 'Correct Answer', 'viralpress' ),
			'you_score' =>  __( 'SCORE', 'viralpress' ),
			'you_got' =>  __( 'You Got', 'viralpress' ),
			'out_of' =>  __( 'out of', 'viralpress' ),
			'add_answer' => __( 'Add more answer', 'viralpress' ),
			'title_of_exp' => __( 'Title of explanation', 'viralpress' ),
			'desc_of_exp' => __( 'Describe answer in details', 'viralpress' ),
			'explain_answer' => __( 'Explain correct answer', 'viralpress' ),
			'explain_answer' => __( 'Explain correct answer', 'viralpress' ),
			'explain_answer' => __( 'Explain correct answer', 'viralpress' ),
			'explain_answer' => __( 'Explain correct answer', 'viralpress' ),
			'explain_answer' => __( 'Explain correct answer', 'viralpress' ),
			'withdraw_last_vote' => __( 'You already reacted to this post. Withdraw the previous reaction to react again.', 'viralpress' ),
			'scoring' => __( 'Show this result for scores between', 'viralpress' ),
			'from' => __( 'From', 'viralpress' ),
			'to' => __( 'To', 'viralpress' ),
			'all_required' => __( 'Please answer all the questions.', 'viralpress' ),
			'vote_done' => __( 'You have submitted your vote. Thank you for your participation.', 'viralpress' ),
			'votes' => __( 'votes', 'viralpress' ),
			'sel_mass_action' => __( 'Please select a mass action', 'viralpress' ),
			'sel_at_one_post' => __( 'Please select at least one post', 'viralpress' ),
			'deleted' => __( 'Deleted', 'viralpress' ),
			'select' => __( 'Select', 'viralpress' ),
			'could_not_edit' => __( 'post could not be edited', 'viralpress' ),
			'confirm_action' => __( 'Are you sure to perform this action?', 'viralpress' ),
			'confirm_del' => __( 'Are you sure to delete this item?', 'viralpress' ),
			'big_or' => __( 'OR', 'viralpress' ),
			'upload_from_link' => __( 'Upload from link', 'viralpress' ),
			'show_numering' => __( 'Show numbering', 'viralpress' ),
			'edit_post_title' => __( 'Edit post', 'viralpress' ).' - '.get_bloginfo( 'name' ),
			'lk_embed_url' => __( 'Liveleak embed code required. Liveleak video url will not work', 'viralpress' ),
			'must_share_quiz' => __( 'You must share the quiz before you can see result', 'viralpress' ),
			'sure_react' => __( 'Are you sure to react with this gif?', 'viralpress' ),
			'submit' => __( 'Submit', 'viralpress' ),
			'gallery' => __( 'Gallery', 'viralpress' ),
			'playlist' => __( 'Playlist', 'viralpress' ),
			'sel_img' => __( 'Select images', 'viralpress' ),
			'sel_playlist' => __( 'Select audio or video files', 'viralpress' ),
			'one_type_playlist' => __( 'Audio and video files cannot be mixed in playlist', 'viralpress' ),
			'add_more_photo' => __( 'Add more photo', 'viralpress' ),
			'must_login' => __( 'You must login to perform this action', 'viralpress' ),
			'result' => __( 'Result', 'viralpress' ),
			'select_one' => __( 'Select one', 'viralpress' ),
			'gal_cols' => __( 'Gallery column', 'viralpress' ),
			'gal_autostart' => __( 'Caraousel autostart', 'viralpress' ),
			'gal_type' => __( 'Gallery type', 'viralpress' ),
			'thumbnail' => __( 'Thumbnail grid', 'viralpress' ),
			'rectangular' => __( 'Tiled mosaic', 'viralpress' ),
			'columns' => __( 'Tiled columns', 'viralpress' ),
			'square' => __( 'Square tiles', 'viralpress' ),
			'circle' => __( 'Circle', 'viralpress' ),
			'slideshow' => __( 'Slideshow/carousel', 'viralpress' ),
			'yes' => __( 'Yes', 'viralpress' ),
			'no' => __( 'No', 'viralpress' ),
			'submitting_open_list' => __( 'Submitting open list...', 'viralpress' ),
			'submitted_open_list' => sprintf( __( 'Your list was successfully submitted for review. See your submissions %s here %s', 'viralpress' ), '<a href="'.$op_url.'">', '</a>' ),
			'entry_deleted' => __( 'Item deleted successfully.', 'viralpress' ),
			'url_required_react' => __( 'URL required for posting reaction.', 'viralpress' ),
			'gen_meme' => __( 'Create meme', 'viralpress' ),
			'img_req' => __( 'Image required', 'viralpress' ),
			'vp_req' => __( 'required', 'viralpress' ),
			'add_more_media' => __( 'Add more media', 'viralpress' ),
			'sel_img_meme' => __( 'Select an image', 'viralpress' ),
			'add_thumb' => __( 'Thumb & subtitle', 'viralpress' ),
			'hide_thumb' => __( 'Hide settings', 'viralpress' ),
			'entry_move_up' => __( 'Move entry up', 'viralpress' ),
			'entry_move_down' => __( 'Move entry down', 'viralpress' ),
			'toggle_source' => __( 'Toggle source code', 'viralpress' ),
			'show_adv_opt' => __( 'Show advanced options', 'viralpress' ),
			'hide_adv_opt' => __( 'Hide advanced options', 'viralpress' ),
			'pl_wait_action' => __( 'Please wait while we complete your previous action', 'viralpress' ),
			'sure_exit' => __( 'Are you sure to exit?', 'viralpress' ),
			'news_text1' => __( 'Add one or more news entry', 'viralpress'),
			'image_text1' => __( 'Add one or more image', 'viralpress'),
			'meme_text1' => __( 'Add one or more meme', 'viralpress'),
			'list_text1' => __( 'Add one or more list item', 'viralpress'),
			'audio_text1' => __( 'Add one or more audio', 'viralpress'),
			'video_text1' => __( 'Add one or more video', 'viralpress'),
			'playlist_text1' => __( 'Add one or more playlist', 'viralpress'),
			'gallery_text1' => __( 'Add one or more gallery', 'viralpress'),
			'quiz_text1' => __( 'Quiz questions', 'viralpress'),
			'poll_text1' => __( 'Add one or more poll', 'viralpress' ),
			'news_title' => __( 'Submit a news or story', 'viralpress'),
			'image_title' => __( 'Submit image', 'viralpress'),
			'meme_title' => __( 'Create meme', 'viralpress'),
			'list_title' => __( 'Create list', 'viralpress'),
			'audio_title' => __( 'Submit audio', 'viralpress'),
			'video_title' => __( 'Submit video', 'viralpress'),
			'playlist_title' => __( 'Submit playlist', 'viralpress'),
			'gallery_title' => __( 'Submit gallery', 'viralpress'),
			'quiz_title' => __( 'Create a quiz', 'viralpress'),
			'poll_title' => __( 'Create a poll', 'viralpress' ),
			'news_text2' => __( 'Want to add more news, photos or embeds?', 'viralpress'),
			'image_text2' => __( 'Want to add more image or meme?', 'viralpress'),
			'meme_text2' => __( 'Choose an image and start building your great meme', 'viralpress'),
			'list_text2' => __( 'What type of list you want to add next?', 'viralpress'),
			'audio_text2' => __( 'What to do next - upload audio or embed from websites?', 'viralpress'),
			'video_text2' => __( 'What to do next - upload video or embed from websites?', 'viralpress'),
			'playlist_text2' => __( 'Want to add more playlist?', 'viralpress'),
			'gallery_text2' => __( 'Want to add more gallery?', 'viralpress'),
			'quiz_text2' => __( 'Want to add more question?', 'viralpress'),
			'poll_text2' => __( 'Want to add more poll or describe your poll?', 'viralpress' )
		);
		
		return $lang;
	}
	
	/**
	 * load js files
	 * @since 1.0
	 */
	public function loadJS()
	{
		wp_register_script( 'viralpress-core-js', $this->settings['JS_URL'].'/viralpress.min.js', array( 'jquery' ), VP_VERSION );
		wp_enqueue_script( 'viralpress-core-js' );
		
		$lang = $this->getJSLang();
		
		$aembeds = get_allowed_embed_regex();
		
		$l = (string)is_user_logged_in();
		wp_localize_script( 'viralpress-core-js', 'vp_user_logged_in', $l );
		wp_localize_script( 'viralpress-core-js', 'vp_ajax_nonce', wp_create_nonce( 'vp-ajax-action-'.get_current_user_id() ) );
		wp_localize_script( 'viralpress-core-js', 'vp_fb_app_id', (string)$this->settings['fb_app_id'] );
		wp_localize_script( 'viralpress-core-js', 'vp_google_oauth_id', (string)$this->settings['google_oauth_id'] );
		wp_localize_script( 'viralpress-core-js', 'vp_google_api_key', (string)$this->settings['google_api_key'] );
		wp_localize_script( 'viralpress-core-js', 'vp_home_url', home_url( '/' ) );
		wp_localize_script( 'viralpress-core-js', 'vp_meme_gen_url', home_url( $this->settings['page_slugs']['meme-generator'] /*'/meme-generator'*/ ) );
		wp_localize_script( 'viralpress-core-js', 'vp_create_url', home_url( $this->settings['page_slugs']['create'] ) );
		wp_localize_script( 'viralpress-core-js', 'vp_img_dir_url', $this->settings['IMG_URL'] );
		wp_localize_script( 'viralpress-core-js', 'vp_spinner_url', $this->settings['IMG_URL']."/spinner.gif" );
		wp_localize_script( 'viralpress-core-js', 'vp_ajaxurl', admin_url( 'admin-ajax.php' ) );
		wp_localize_script( 'viralpress-core-js', 'vp_lang', $lang );
		wp_localize_script( 'viralpress-core-js', 'vp_allow_anon_votes', (string)$this->settings['anon_votes'] );
		wp_localize_script( 'viralpress-core-js', 'vp_share_quiz_force', (string)apply_filters( 'vp_share_quiz_force', $this->settings['share_quiz_force'] ) );
		wp_localize_script( 'viralpress-core-js', 'vp_hotlink_image', (string)$this->settings['hotlink_image'] );
		wp_localize_script( 'viralpress-core-js', 'vp_allowed_embeds', $aembeds );
		wp_localize_script( 'viralpress-core-js', 'vp_autoload_op_editor', apply_filters( 'vp_autoload_op_editor', '0' ) );
		wp_localize_script( 'viralpress-core-js', 'vp_self_video', (string)$this->settings['self_video'] );
		wp_localize_script( 'viralpress-core-js', 'vp_self_audio', (string)$this->settings['self_audio'] );
		wp_localize_script( 'viralpress-core-js', 'vp_meme_enabled', (string)$this->settings['meme_enabled'] );
		
		
		if( is_single() && $this->settings['show_reactions'] ) {
			//add_thickbox();
			wp_register_script( 'jq-bxslider', $this->settings['JS_URL'].'/jquery.bxslider.min.js', array( 'jquery' ), '1', true );
			wp_enqueue_script( 'jq-bxslider' );	
		}
		
		if( is_single() && $this->settings['comments_per_list'] ) {
			wp_register_script( 'jq-waypoint', $this->settings['JS_URL'].'/jquery.waypoints.min.js', array( 'jquery' ), '1', true );
			wp_enqueue_script( 'jq-waypoint' );	
		}
		
		global $post;
		if( !empty( $post->post_name )){
    		$post_slug = $post->post_name;
			if( $post_slug == $this->settings['page_slugs']['create']/*'create'*/ ) {
				wp_localize_script( 'viralpress-core-js', 'create_post', empty($_GET['type']) ? '' : esc_js( $_GET['type'] ) );
				if ( ! did_action( 'wp_enqueue_media' ) )wp_enqueue_media();
				wp_enqueue_script('jquery-ui-datepicker');
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'jquery-ui-droppable' );
				wp_enqueue_script( 'imgareaselect' );
				wp_register_script( 'vp-image-edit', get_admin_url().'js/image-edit.js', array() );
				wp_enqueue_script( 'vp-image-edit' );
				wp_register_script( 'viralpress-tagit', $this->settings['JS_URL'].'/tagit.js', array( 'jquery' ) );
				wp_enqueue_script( 'viralpress-tagit' );
				//vp_enqueue_script_meme_page();
			}
			else if( $post_slug == $this->settings['page_slugs']['dashboard']/*'dashboard'*/ ) {
				if ( ! did_action( 'wp_enqueue_media' ) )wp_enqueue_media();
				wp_enqueue_script( 'imgareaselect' );
				wp_register_script( 'vp-image-edit', get_admin_url().'js/image-edit.js', array() );
				wp_enqueue_script( 'vp-image-edit' );
			}
			else if( $post_slug == $this->settings['page_slugs']['meme-generator']/*'meme-generator'*/ ) {
				vp_enqueue_script_meme_page();
			}
			else if( $post_slug == $this->settings['page_slugs']['password-lost']/*'password-lost'*/ ) {
				wp_localize_script( 'viralpress-core-js', 'prevent_login_modals', '1' );
			}
			else if( $post_slug == $this->settings['page_slugs']['register']/*'register'*/ ) {
				wp_localize_script( 'viralpress-core-js', 'prevent_login_modals', '1' );
			}
			else if( $post_slug == $this->settings['page_slugs']['password-reset']/*'password-reset'*/ ) {
				wp_localize_script( 'viralpress-core-js', 'prevent_login_modals', '1' );
			}
		}
	}
	
	/**
	 * load admin js files
	 * @since 2.4
	 */
	public function loadAdminJS()
	{
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox'); 
		
		try{
			$s = get_current_screen();
			if($s->base == 'admin_page_viralpress-poll' || $s->base == 'viralpress_page_viralpress-ad-settings'){
				wp_register_script( 'viralpress-core-js', $this->settings['JS_URL'].'/viralpress.min.js', array( 'jquery' ), VP_VERSION );
				wp_enqueue_script( 'viralpress-core-js' );
				
				$lang = $this->getJSLang();
				
				$l = (string)is_user_logged_in();
				wp_localize_script( 'viralpress-core-js', 'vp_user_logged_in', $l );
				wp_localize_script( 'viralpress-core-js', 'vp_ajax_nonce', wp_create_nonce( 'vp-ajax-action-'.get_current_user_id() ) );
				wp_localize_script( 'viralpress-core-js', 'vp_fb_app_id', (string)$this->settings['fb_app_id'] );
				wp_localize_script( 'viralpress-core-js', 'vp_google_oauth_id', (string)$this->settings['google_oauth_id'] );
				wp_localize_script( 'viralpress-core-js', 'vp_google_api_key', (string)$this->settings['google_api_key'] );
				wp_localize_script( 'viralpress-core-js', 'vp_home_url', home_url( '/' ) );
				wp_localize_script( 'viralpress-core-js', 'vp_ajaxurl', admin_url( 'admin-ajax.php' ) );
				wp_localize_script( 'viralpress-core-js', 'vp_lang', $lang );	
				wp_localize_script( 'viralpress-core-js', 'vp_img_dir_url', $this->settings['IMG_URL'] );
				wp_localize_script( 'viralpress-core-js', 'vp_autoload_op_editor', apply_filters( 'vp_autoload_op_editor', '0' ) );
				wp_localize_script( 'viralpress-core-js', 'vp_self_video', (string)$this->settings['self_video'] );
				wp_localize_script( 'viralpress-core-js', 'vp_self_audio', (string)$this->settings['self_audio'] );
				wp_localize_script( 'viralpress-core-js', 'vp_meme_enabled', (string)$this->settings['meme_enabled'] );
				
				wp_register_style( 'viralpress-core-css', $this->settings['CSS_URL'].'/viralpress.min.css' , array(), VP_VERSION, 'all');
				wp_enqueue_style( 'viralpress-core-css' );
			}
		}catch(Exception $e){}
	}
	
	/**
	 * load css files
	 * @since 1.0
	 */
	public function loadCSS()
	{
		wp_register_style( 'viralpress-core-css', $this->settings['CSS_URL'].'/viralpress.min.css' , array(), VP_VERSION, 'all');
		wp_enqueue_style( 'viralpress-core-css' );

		if( file_exists( $this->settings['CSS_DIR'].'/custom.css' ) ) {
			wp_register_style( 'viralpress-custom-css', $this->settings['CSS_URL'].'/custom.css' , array(), VP_VERSION, 'all');
			wp_enqueue_style( 'viralpress-custom-css' );
		}
		
		if( is_single() && $this->settings['show_reactions'] ) {
			wp_register_style( 'jq-bxslider-css', $this->settings['CSS_URL'].'/jquery.bxslider.css' , array() );
			wp_enqueue_style( 'jq-bxslider-css' );	
		}
		
		global $post;
		if( !empty( $post->post_name )){
    		$post_slug = $post->post_name;
			if( $post_slug == $this->settings['page_slugs']['create']/*'create'*/ || $post_slug == $this->settings['page_slugs']['dashboard']/*'dashboard'*/ ) {
				wp_enqueue_style('jquery-ui-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
				wp_enqueue_style( 'imgareaselect' );
				wp_register_style( 'viralpress-imgedit', $this->settings['CSS_URL'].'/vp-imgedit.css' , array(), '1', 'all');
				wp_enqueue_style( 'viralpress-imgedit' );
			}
		}
	}
	
	/**
	 * check user roles
	 * @since 1.4
	 */
	public function vp_check_user_roles()
	{
		$this->is_contributor = /*current_user_can( 'contributor' ) && */ !current_user_can( 'administrator' ) && !current_user_can( 'editor' ) && !current_user_can( 'author' ) && is_user_logged_in();
	}
	
	/**
	 * viralpress set anon cookie
	 * @since 2.5
	 */
	public function vp_set_anon_cookie()
	{
		if( empty( $_COOKIE['vp_unan'] ) ) {
			setcookie( 'vp_unan', uniqid(), time() + 86400 * 1000, COOKIEPATH, COOKIE_DOMAIN );
		}
	}
	
	/**
	 * block admin access for contributors
	 * @since 1.0
	 * @changed 1.8
	 */
	public function vp_check_user_perms() 
	{
		if ( $this->is_contributor && is_user_logged_in() ) {
			$this->allow_contributor_uploads();
		}

		$c =  $this->is_contributor;
		if ( $c && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && $this->settings['block_admin']  ) {
			show_admin_bar(false);	
		}
		if ( is_admin() && $c && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && $this->settings['block_admin'] ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}		
	}

	/**
	 * load include files
	 * @since 1.0
	 */
	public function load_includes()
	{
		require_once( $this->settings['CLASS_DIR']. '/vp_user.class.php' );
		require_once( $this->settings['CLASS_DIR']. '/vp_post.class.php' );
		require_once( $this->settings['CLASS_DIR']. '/vp_updater.class.php' );
		require_once( $this->settings['CLASS_DIR']. '/vp_widgets.class.php' );
		require_once( $this->settings['CLASS_DIR']. '/vp_mycred_hooks.class.php' );

		require_once( $this->settings['LIB_DIR']. '/viralmain.php' );
		require_once( $this->settings['LIB_DIR']. '/functions.php' );
		require_once( $this->settings['LIB_DIR']. '/ajax.php' );
		require_once( $this->settings['LIB_DIR']. '/vp_hooks.php' );
		
		if( !function_exists( 'recaptcha_get_html' ) )
			require_once( $this->settings['ASSET_DIR']. '/recaptcha/recaptchalib.php');
	}
	
	/**
	 * viralpress custom templates i.e. profile page
	 * @since 1.0
	 */
	public function vp_custom_templates( $template )
	{
		if( is_404() ){
			flush_rewrite_rules();	
			return $template;
		}
		
		if( is_author() && $this->settings['custom_profiles'] ) {
			return $this->settings['TEMPLATE_DIR']. '/profile.php';
		}
		/*
		else {
			global $post;
			if( !empty( $post->post_name )){
				$post_slug = $post->post_name;
				if( $post_slug == 'profile' ) {
					return $this->settings['TEMPLATE_DIR']. '/profile.php';
				}
			}
		}
		*/
		
		return $template;
	}
	
	/**
	 * redirect from secured pages
	 * @since 1.0
	 */
	public function redirect_auth()
	{
		$is_user_logged_in = is_user_logged_in();
		$pagename = preg_replace( '/[^a-z0-9\-\_]/i', '', basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ); 
		$pagename = strtolower($pagename);
		
		if( empty( $pagename ) ) return;
		
		$l = $this->settings['disable_login'] == 1 ? 0 : 1;
		
		if( $pagename == $this->settings['page_slugs']['login'] /*'login'*/ && $is_user_logged_in && $l ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['register'] /*'register'*/ && $is_user_logged_in && $l ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['password-lost'] /*'password-lost'*/ && $is_user_logged_in && $l ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['password-reset'] /*'password-reset'*/ && $is_user_logged_in && $l ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['dashboard'] /*'dashboard'*/ && !$is_user_logged_in ) {
			auth_redirect();
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['profile'] /*'profile'*/ && !$is_user_logged_in ) {
			auth_redirect();
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['create'] /*'create'*/ && !$is_user_logged_in ) {
			auth_redirect();
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['meme-generator'] /*'meme-generator'*/ && !$is_user_logged_in ) {
			auth_redirect();
			exit;
		}
		else if( $pagename == 'post-comments' && !$is_user_logged_in ) {
			auth_redirect();
			exit;
		}
		else if( $pagename == 'my-comments' && !$is_user_logged_in ) {
			auth_redirect();
			exit;
		}
		else if( $pagename == $this->settings['page_slugs']['create'] /*'create'*/ && $this->settings['only_admin'] == 1 && $this->is_contributor ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}
	}
	
	/**
	 * hook on plugin activate
	 * @since 1.0
	 * @changed 1.1
	 * @changed 1.2
	 */
	public function plugin_activate()
	{
		update_option( 'vp-version', VP_VERSION );
		@unlink( $this->settings[ 'PLUGIN_DIR' ] . '/doupdate.txt' );
		@unlink( $this->settings[ 'PLUGIN_DIR' ] . '/toupdate.txt' );
		
		if( empty( $this->installed_version) ) {
			$this->register_tags();
			$this->register_pages();
		}
		
		$settings = array();
		
		/**
		 * this means viralpress was installed before
		 */
		  
		if( get_option( 'vp-custom-profiles', -1 ) != -1 ) {
			$old = $this->load_old_settings();
			foreach( $old as $k => $v ) $settings[ $k ] = $v;
			
			delete_option( 'vp-fb-app-id' );
			delete_option( 'vp-google-oauth-id' );
			delete_option( 'vp-google-api-key' );
			delete_option( 'vp-auto-publish-post' );
			delete_option( 'vp-show-fb-comments' );
			delete_option( 'vp-share-buttons' );
			delete_option( 'vp-custom-profiles' );
			delete_option( 'vp-show-menu' );
			delete_option( 'vp-block-admin' );
			delete_option( 'vp-block-edits' );
			delete_option( 'vp-disable-login' );
			delete_option( 'vp-use-category' );
			delete_option( 'vp-only-admin' );
			delete_option( 'vp-show-reactions' );
			delete_option( 'vp-show-menu-on' );
			delete_option( 'vp-recap-key' );
			delete_option( 'vp-recap-secret' );
			delete_option( 'vp-recap-login' );
			delete_option( 'vp-recap-post' );
			delete_option( 'vp-anon-votes' );
			delete_option( 'vp-share-quiz-force' );
			delete_option( 'vp-allow-copy' );
			delete_option( 'vp-allow-open-list' );
			delete_option( 'vp-comments-per-list' );
			
			delete_option( 'vp-allow-list' );
			delete_option( 'vp-allow-quiz' );
			delete_option( 'vp-allow-poll' );
			delete_option( 'vp-allow-playlist' );
			delete_option( 'vp-allow-gallery' );
			delete_option( 'vp-self-video' );
			delete_option( 'vp-self-audio' );
			
			delete_option( 'vp-show-like-dislike' );
			delete_option( 'vp-allowed-embeds' );
			delete_option( 'vp-hotlink-image' );
			delete_option( 'vp-bp-int' );
			delete_option( 'vp-mycred-int' );
			delete_option( 'vp-hide-vote' );
			delete_option( 'vp-hide-vote-op' );
			delete_option( 'vp-sort-op-vote' );
		}
		/**
		 * else load default settings for new user
		 */
		else if( get_option( 'vp-global-configs', -1 ) == -1 ){
			$settings['fb_app_id'] = '';
			$settings['google_oauth_id'] = '';
			$settings['google_api_key'] = '';
			$settings['auto_publish'] = 0;
			$settings['fb_comments'] = 0;
			$settings['share_buttons'] = 0;
			$settings['custom_profiles'] = 0;
			$settings['show_menu'] = 0;
			$settings['block_admin'] = 0;
			$settings['block_edits'] = 1;
			$settings['envato_username'] = '';
			$settings['envato_api_key'] = '';
			$settings['envato_purchase_code'] = '';
			$settings['disable_login'] = 1;
			$settings['use_category'] = 0;
			$settings['only_admin'] = 0;
			$settings['show_reactions'] = 0;
			$settings['show_menu_on'] = 'both';
			$settings['recap_key'] = '';
			$settings['recap_secret'] = '';
			$settings['recap_login'] = 0;
			$settings['recap_post'] = 0;
			$settings['load_recap'] = 0;
			$settings['anon_votes'] = 0;
			$settings['share_quiz_force'] = 0;
			$settings['allow_copy'] = 0;
			$settings['allow_open_list'] = 0;
			$settings['comments_per_list'] = 0;
			
			$settings['list_enabled'] = 1;
			$settings['quiz_enabled'] = 1;
			$settings['poll_enabled'] = 1;
			$settings['playlist_enabled'] = 1;
			$settings['gallery_enabled'] = 1;
			$settings['self_video'] = 0;
			$settings['self_audio'] = 0;
			
			$settings['show_like_dislike'] = 0;
			$settings['allowed_embeds'] = '';
			$settings['hotlink_image'] = 0;
			$settings['vp_bp'] = 0;
			$settings['vp_mycred'] = 0;
			$settings['hide_vote_buttons'] = 1;
			$settings['hide_vote_buttons_op'] = 0;
			$settings['sort_op_vote'] = 0;
		}
		/**
		 * else load previous settings
		 */
		else $settings = $this->configs;
		
		/*******
		 * Put any new settings here
		 ********/
		 
		if( !isset( $this->configs['image_enabled'] ) ) $settings['image_enabled'] = 1;
		if( !isset( $this->configs['embed_enabled'] ) ) $settings['embed_enabled'] = 1;
		if( !isset( $this->configs['video_enabled'] ) ) $settings['video_enabled'] = 1;
		if( !isset( $this->configs['audio_enabled'] ) ) $settings['audio_enabled'] = 1;
		if( !isset( $this->configs['meme_enabled'] ) ) $settings['meme_enabled'] = 1;
		if( !isset( $this->configs['news_enabled'] ) ) $settings['news_enabled'] = 1;
		if( !isset( $this->configs['wsl_int'] ) ) $settings['wsl_int'] = 0;
		if( !isset( $this->configs['show_gif_reactions'] ) ) $settings['show_gif_reactions'] = 0;
		if( !isset( $this->configs['show_gif_reactions_upload'] ) ) $settings['show_gif_reactions_upload'] = 0;
		
		$settings = json_encode( $settings );
		
		update_option( 'vp-global-configs', $settings, false );
		
		if( get_option( 'vp-cat-tag', -1 ) == -1 ) {
			$cat_tag = array(
				'news' => array( 'cat' => 'news', 'tag' => 'News' ),
				'lists' => array( 'cat' => 'list', 'tag' => 'Lists' ),
				'images' => array( 'cat' => 'image', 'tag' => 'Images' ),
				'meme' => array( 'cat' => 'meme', 'tag' => 'Meme' ),
				'polls' => array( 'cat' => 'poll', 'tag' => 'Polls' ),
				'quiz' => array( 'cat' => 'quiz', 'tag' => 'Quiz' ),
				'audio' => array( 'cat' => 'audio', 'tag' => 'Audio' ),
				'videos' => array( 'cat' => 'video', 'tag' => 'Videos' ),
				'gallery' => array( 'cat' => 'gallery', 'tag' => 'Gallery' ),
				'playlist' => array( 'cat' => 'playlist', 'tag' => 'Playlist' ),
			);
			update_option( 'vp-cat-tag', json_encode( $cat_tag ) );	
		}
		if( get_option( 'vp-page-slugs', -1 ) == -1 ) {
			if( !empty( $this->temp_vars['reg_pages'] ) ) {
				update_option( 'vp-page-slugs', json_encode( $this->temp_vars['reg_pages'] ) );	
			}
			else {
				$page_slugs = array(
					'profile' => 'profile',
					'login' => 'login',
					'register' => 'register',
					'password-lost' => 'password-lost',
					'password-reset' => 'password-reset',
					'create' => 'create',
					'dashboard' => 'dashboard',
					'meme-generator' => 'meme-generator'
				);
				update_option( 'vp-page-slugs', json_encode( $page_slugs ) );
			}
		}
		else {
			/**
			 * put all new added pages here
			 */
			if( empty( $this->settings['page_slugs']['meme-generator'] ) && !empty( $this->settings['page_slugs']['profile']) && get_page_by_path( 'meme-generator' ) ) {
				$this->settings['page_slugs']['meme-generator'] = 'meme-generator';	
				update_option( 'vp-page-slugs', json_encode( $this->settings['page_slugs'] ) );
			}
		}
		
		if( get_option( 'vp-react-gifs', -1 ) == - 1) {
			$url = plugin_dir_url( __FILE__ );
			
			$gifs = array(
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'wtf_is_that.gif', 
					'static' => $url . 'assets/images/reaction_gifs/' . 'wtf_is_that.gif.gif', 
					'caption'  => 'WTF is that!' 
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'yessss.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'yessss.gif.gif',
					'caption' => 'Yessss!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'haaaa.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'haaaa.gif.gif',
					'caption' => 'Haaaa!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'hehehe.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'hehehe.gif.gif',
					'caption' => 'HeHeHe!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'yeeee.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'yeeee.gif.gif',
					'caption' => 'Yeeee!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'frustrated.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'frustrated.gif.gif',
					'caption' => 'Damn!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'fuck.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'fuck.gif.gif',
					'caption' => 'Fuck!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'i_cant_take_it.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'i_cant_take_it.gif.gif',
					'caption' => 'I can\'t take it anymore!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'aaaaaa.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'aaaaaa.gif.gif',
					'caption' => 'AAAAAA!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'get_me_outta_here.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'get_me_outta_here.gif.gif',
					'caption' => 'Get me outta here!'
				),
				array( 
					'url' => $url . 'assets/images/reaction_gifs/' . 'zzzzz.gif',
					'static' => $url . 'assets/images/reaction_gifs/' . 'zzzzz.gif.gif',
					'caption' => 'ZZzzz!'
				)
			);	
			
			update_option( 'vp-react-gifs', json_encode( $gifs ) );
		}
		
				
		if( get_option( 'vp-demo-installed', - 1 ) == -1 && !empty( $this->installed_version ) ) {
			update_option( 'vp-demo-installed', 1, false );
		}
		else if( get_option( 'vp-demo-installed', - 1 ) == -1 && empty( $this->installed_version ) ) {
			update_option( 'vp-demo-installed', 0, false );
		}
		
		if( !empty( $this->settings['cat_tag'] ) && empty( $this->settings['cat_tag']['meme'] ) ) {
			$this->settings['cat_tag']['meme'] = array( 'cat' => 'meme', 'tag' => 'Meme' );	
			update_option( 'vp-cat-tag', json_encode( $this->settings['cat_tag'] ) );
		}
		
		$old_hook = wp_get_schedule( 'vp_check_update_hook' );
		if( $old_hook ) wp_clear_scheduled_hook( 'vp_check_update_hook' );
		
		$version = array(
			'script_version' => VP_VERSION,
			'db_version' => '1.0',
			'item_url' => $this->item_link,
			'time' => time()
		);
		
		if( !function_exists( 'vp_activate_deactivate' ) ) { 
			require_once( $this->settings['LIB_DIR']. '/functions.php' );
		}
		
		vp_activate_deactivate( 1, $this );
		file_put_contents( $this->settings[ 'PLUGIN_DIR' ] . '/pluginversion.json', json_encode( $version ) );
		
		$this->load_settings();
	}
	
	/**
	 * hook after plugin activated
	 * @since 1.6
	 */
	public function plugin_activated( $plugin )
	{
		if( $plugin == plugin_basename( __FILE__ ) ) {
			exit( wp_redirect( admin_url( 'admin.php?page=viralpress' ) ) );
		}
	}
	
	/**
	 * hook on plugin deactivate
	 * @since 1.0
	 * @changed 1.8
	 */
	public function plugin_deactivate()
	{
		delete_option( 'vp-version' );
		$old_hook = wp_get_schedule( 'vp_check_update_hook' );
		if( $old_hook ) wp_clear_scheduled_hook( 'vp_check_update_hook' );
		vp_activate_deactivate( 0, $this );
		
		/*
		delete_option( 'vp-demo-installed' );
		$this->deregister_pages();
		*/
	}
	
	/**
	 * register viralpress pages
	 * @since 1.0
	 */
	public function register_pages()
	{
		$this->load_page_definitions();
		
		$full_width = '';
		if( file_exists( $this->settings['WP_ADMIN'].'/includes/theme.php' ) ) {
			require_once( $this->settings['WP_ADMIN'].'/includes/theme.php' );
			
			if( function_exists( 'get_page_templates' ) ) {
				$p = $this->full_width_regex;
				$templates = get_page_templates();
				foreach ( $templates as $template_name => $template_filename ) {
					if( preg_match($p, $template_name) || preg_match( $p, $template_filename) ){
						$full_width = $template_filename;
						break;
					}
				}
			}
		}
		
		foreach ( $this->vp_pages as $slug => $page ) {
			if( !$page['add_page'] )continue;
			$page_data = array(
				'post_content'   => $page['content'],
				'post_name'      => $slug,
				'post_title'     => $page['title'],
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'ping_status'    => 'closed',
				'comment_status' => 'closed',
			);
			if( !empty( $full_width ) )$page_data[ 'page_template' ] = $full_width;
			
			$page = get_page_by_path( $slug );
			$page_id = '';
			
			if ( ! $page ) {
				$page_id = wp_insert_post( $page_data );
				add_update_post_meta( $page_id, 'vp_custom_page', 1 );
			}
			else {
				/*
				$page_id = $page->ID;
				$page_data['ID'] = $page_id;
				wp_update_post( $page_data );*/
				continue;
			}
			
			if(empty( $page_id )) {
				$this->temp_vars['failed_pages'][] = $page['title'];
				update_option( 'vp-admin-notice', sprintf( __( 'Some of the page could not be registered. Please assign them manually from settings %s', 'viralpress' ), implode( ',', $this->temp_vars['failed_pages'] ) ), false );
				continue;
				//die( 'Failed to register pages' );
			}
			else {
				$this->temp_vars['reg_pages'][$slug] = $slug;	
			}
		}
	}
	
	/**
	 * delete viralpress menus
	 * @since 3.0
	 */
	public function delete_menus()
	{
		foreach( $this->vp_menus as $type => $menuname ) {	
			$menu_exists = wp_get_nav_menu_object( $menuname );
			
			if( $menu_exists){
				wp_delete_nav_menu($menuname);
			}
		}
	}
	
	/**
	 * create viralpress menus
	 * @since 1.6
	 */
	public function create_menus()
	{
		foreach( $this->vp_menus as $type => $menuname ) {	
			$menu_exists = wp_get_nav_menu_object( $menuname );
			
			if( !$menu_exists){
				$menu_id = wp_create_nav_menu($menuname);
			}
			else $menu_id = $menu_exists->term_id;
			$this->register_menus( $menu_id, $type );
		}
	}
	
	/**
	 * register viralpress menus
	 * @since 1.0
	 */
	public function register_menus( $menu_id, $type )
	{
		update_option( 'vp-'.$type.'-menu', $menu_id, false );
		
		foreach( $this->vp_pages as $slug => $page ) {
			
			if( ( $page['user'] != $type && $page['user'] != 'all' ) || empty( $page['add_menu'] ) )continue;
			
			$s = $slug;
			
			if( !empty( $this->settings['page_slugs'][$slug] ) )$slug = home_url( $this->settings['page_slugs'][$slug]. '/' );
			else if( !empty($page['url']) )$slug = $page['url'];
			else $slug = home_url( $slug. '/' );
			
			$menu = array(
				'menu-item-title' =>  $page['title'],
				'menu-item-url' => $slug, 
				'menu-item-status' => 'publish'
			);
			if( !empty( $page['xfn']) ){
				$menu[ 'menu-item-xfn' ] = $page[ 'xfn' ];
				$menu[ 'menu-item-classes' ] = 'vp-sub-menu-columns';
			}

			if( $s == 'login' )$menu[ 'menu-item-classes' ] = 'vp-nav-login';
			else if( $s == 'register' )$menu[ 'menu-item-classes' ] = 'vp-nav-register';
			else if( $s == 'welcome' )$menu[ 'menu-item-classes' ] = 'vp-nav-welcome';
			//else if( $s == 'post-comments' )$menu[ 'menu-item-classes' ] = 'vp-nav-post-comments';
			//else if( $s == 'my-comments' )$menu[ 'menu-item-classes' ] = 'vp-nav-my-comments';
						
			$sub_menu_id = wp_update_nav_menu_item(
				$menu_id, 0, $menu
			);
			
			if( !empty( $page['xfn']) )add_option( 'vp-'.$type.'-category-menu', $sub_menu_id );
			
			if( !$sub_menu_id ){
				continue;
				//die( 'Failed to register menu' );
			}
			if( !empty( $page['sub_menu'] ) ) {
				
				foreach( $page['sub_menu'] as $ss => $sub_menu ) {
					
					$cc = '';
					if( $ss == 'post-comments' )$cc = 'vp-nav-post-comments';
					else if( $ss == 'my-comments' )$cc = 'vp-nav-my-comments';
					else if( $ss == 'vp-bp-noti' )$cc = 'vp-bp-noti';
					else if( $ss == 'vp-bp-profile' )$cc = 'vp-bp-profile';
					else if( $ss == 'vp-bp-openlist' )$cc = 'vp-bp-openlist';
					else if( $ss == 'myposts' )$cc = 'vp-bp-posts';
					
					$u = $sub_menu['url'];
					if( !empty( $this->settings['page_slugs'][$ss] ) )$u = home_url( $this->settings['page_slugs'][$ss] );
					
					if( empty( $page['xfn']) )
						$sub_menu_data = array(
								'menu-item-title' =>  $sub_menu['title'],
								'menu-item-status' => 'publish',
								'menu-item-url' => $u,
								'menu-item-parent-id' => $sub_menu_id,
								'menu-item-classes' => $cc
							);

					else
						$sub_menu_data = array(
								'menu-item-title' =>  $sub_menu['title'],
								'menu-item-object-id' => $sub_menu['category_id'],
								'menu-item-status' => 'publish',
								'menu-item-object' => 'category',
								'menu-item-url' => $sub_menu['url'],
								'menu-item-parent-id' => $sub_menu_id,
								'menu-item-type' => 'taxonomy',
								'menu-item-classes' => $cc
							);
								
					wp_update_nav_menu_item(
						$menu_id, 0, $sub_menu_data
					);
				}
			}	
		}
	}
	
	/**
	 * register default categories
	 * @since 1.0
	 */
	public function register_categories()
	{
		$categories = array(
			array(
				'name' => 'Animals',
				'slug' => 'animals'
			),
			array(
				'name' => 'Books',
				'slug' => 'books'
			),
			array(
				'name' => 'Celebrity',
				'slug' => 'celebrity'
			),
			array(
				'name' => 'Entertainment',
				'slug' => 'entertainment'
			),
			array(
				'name' => 'Food',
				'slug' => 'food'
			),
			array(
				'name' => 'Funny',
				'slug' => 'funny'
			),
			array(
				'name' => 'Health',
				'slug' => 'health'
			),
			array(
				'name' => 'Ideas',
				'slug' => 'ideas'
			),
			array(
				'name' => 'Music',
				'slug' => 'music'
			),
			array(
				'name' => 'Politics',
				'slug' => 'politics'
			),
			array(
				'name' => 'Puzzles',
				'slug' => 'puzzles'
			),
			array(
				'name' => 'Science',
				'slug' => 'science'
			),
			array(
				'name' => 'Sports',
				'slug' => 'sports'
			),
			array(
				'name' => 'Style',
				'slug' => 'style'
			),
			array(
				'name' => 'Travel',
				'slug' => 'travel'
			),
			array(
				'name' => 'World',
				'slug' => 'world'
			)
		);
		
		foreach( $categories as $category ) {
			wp_insert_term($category['name'], 'category', array(
					'slug' => $category['slug'],
					'parent' => 0
				)
			);
		}
		
		update_option( 'vp-demo-installed', 1, false );
	}
	
	/**
	 * register default categories
	 * @since 1.6
	 */
	public function register_type_categories()
	{
		$categories = array(
			array(
				'name' => 'News',
				'slug' => 'news'
			),
			array(
				'name' => 'List',
				'slug' => 'list'
			),
			array(
				'name' => 'Image',
				'slug' => 'image'
			),
			array(
				'name' => 'Meme',
				'slug' => 'meme'
			),
			array(
				'name' => 'Quiz',
				'slug' => 'quiz'
			),
			array(
				'name' => 'Poll',
				'slug' => 'poll'
			),
			array(
				'name' => 'Video',
				'slug' => 'video'
			),
			array(
				'name' => 'Audio',
				'slug' => 'audio'
			),
			array(
				'name' => 'Gallery',
				'slug' => 'gallery'
			),
			array(
				'name' => 'Playlist',
				'slug' => 'playlist'
			)
		);
		
		foreach( $categories as $category ) {
			wp_insert_term($category['name'], 'category', array(
					'slug' => $category['slug'],
					'parent' => 0
				)
			);
		}
		
		update_option( 'vp-type-cat-installed', 1, false );
	}
	
	/**
	 * register default tags
	 * @since 1.0
	 */
	public function register_tags()
	{
		$this->load_post_types();
		foreach( $this->vp_post_types as $post_type ) {
			wp_insert_term($post_type['name'], 'post_tag', array(
					'slug' => $post_type['type'],
					'parent' => 0
				)
			);	
		}
		
		/**
		 * default tags
		 */
		$tags = array(
			array(
				'name' => __( 'lol', 'ViralPress' ),
				'slug' => 'lol'
			),
			array(
				'name' => __( 'win', 'ViralPress' ),
				'slug' => 'win'
			),
			array(
				'name' => __( 'omg', 'ViralPress' ),
				'slug' => 'omg'
			),
			array(
				'name' => __( 'cute', 'ViralPress' ),
				'slug' => 'cute'
			),
			array(
				'name' => __( 'fail', 'ViralPress' ),
				'slug' => 'fail'
			),
			array(
				'name' => __( 'wtf', 'ViralPress' ),
				'slug' => 'wtf'
			),
			array(
				'name' => __( 'trending', 'ViralPress' ),
				'slug' => 'trending'
			),
			array(
				'name' => __( 'top', 'ViralPress' ),
				'slug' => 'top'
			),
			array(
				'name' => __( 'hot', 'ViralPress' ),
				'slug' => 'hot'
			)
		);
		
		foreach( $tags as $tag ) {
			wp_insert_term($tag['name'], 'post_tag', array(
					'slug' => $tag['slug'],
					'parent' => 0
				)
			);	
		}
	}
	
	/**
	 * hook on category create
	 * add them on existing menu
	 * deprecated since 3.0
	 * @since 1.0
	 */
	public function on_category_created( $term_id )
	{
		/*
		$category = get_category( $term_id );
		if( $category->category_parent != 0 )return false;
		
		foreach( $this->vp_menus as $type => $menuname ) {	
			
			$menu_id = get_option( 'vp-'.$type.'-menu' );
			$sub_menu_id = get_option( 'vp-'.$type.'-category-menu' );
			
			wp_update_nav_menu_item(
				$menu_id, 0, array(
					'menu-item-title' =>  $category->name,
					'menu-item-object-id' => $category->term_id, 
					'menu-item-status' => 'publish',
					'menu-item-object' => 'category',
					'menu-item-url' => get_category_link( $category->term_id ),
					'menu-item-parent-id' => $sub_menu_id,
					'menu-item-type' => 'taxonomy'
				)
			);
		}
		*/		
		//menu-item-position
	}
	
	/**
	 * denies open list edit by non admins
	 * @since 3.0
	 */
	public function vp_deny_open_list_edit( $capauser, $capask, $param )
	{
		global $wpdb;   
  		if( empty( $param[2] ) ) return $capauser;
		$post = @get_post( $param[2] );
		if( empty( $post ) ) return $capauser;
		
	  	if( $post->post_status == 'publish' ){
			if( @$capauser['administrator'] != 1 ){
				if( ( $param[0] == "edit_post") || ( $param[0] == "delete_post" ) ) {
					$cc = get_post_meta( $post->ID, 'vp_open_list' );
					$cc = @(int)$cc[0];
					
					if( $cc ) {
						foreach( (array) $capask as $capasuppr) {
							if ( array_key_exists($capasuppr, $capauser) ) {
                				$capauser[$capasuppr] = 0;

              				}
						}
					}
				}
		  	}
		}
		return $capauser;
	}
	
	/**
	 * add async tags to viralpress script
	 */
	public function vp_script_loader_tag( $tag, $handle )
	{
		if ( 'viralpress-core-js' !== $handle && 'jquery-core' !== $handle && 'jquery-migrate' !== $handle )return $tag;
    	return str_replace( ' src', ' data-cfasync="false" src', $tag ); //false to disable
	}
	
	/**
	 * delete pages before deactivation
	 * @since 1.0
	 */
	public function deregister_pages()
	{
		$this->load_page_definitions();
		
		foreach ( $this->vp_pages as $slug => $page ) {
			$page = get_page_by_path( $slug );
			
			if ( !empty($page) ) {
				$page_id = $page->ID;
				$tt = get_post_meta( $page_id, 'vp_custom_page' );
				if( !empty($tt[0]) ) {
					wp_delete_post( $page_id, 1 );
				}
			}
		}
		
		foreach( $this->vp_menus as $type => $menuname ) {
			$menu_exists = wp_get_nav_menu_object( $menuname );
			
			if( $menu_exists){
				$menu_id = $menu_exists->term_id;
				wp_delete_nav_menu( $menu_id );
			}
			
			delete_option( 'vp-'.$type.'-menu'  );
			delete_option( 'vp-'.$type.'-category-menu' );
		}	
		
	}
	
	/**
	 * render user dashboard
	 * @since 1.0
	 */
	public function render_viralpress_user_dashboard()
	{
		if ( !is_user_logged_in() ) {
			return __( 'You must be logged in to view this page!', 'viralpress' );
		}
		return vp_get_template_html( 'dashboard', array( 'uid' => get_current_user_id(), 'vp_instance' => &$this ) );
	}
	
	/**
	 * render post editor
	 * @since 1.0
	 */
	public function render_vp_editor()
	{
		if ( !is_user_logged_in() ) {
			return __( 'You must be logged in to create new post!', 'viralpress' );
		}
	
		$attributes = array( 'title' => __( 'Submit a post', 'viralpress' ), 'vp_instance' => &$this );
		$type = '';
		if( !empty($_GET['type']) )$type = esc_js( esc_html( $_GET['type'] ) );
		
		if($type == 'news'){
			$attributes = array( 'title' => __( 'Submit a news', 'viralpress' ), 'vp_instance' => &$this );
			$attributes['post_type'] = 'news';
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'list' || $type == 'lists'){
			$attributes = array( 'title' => __( 'Create a list', 'viralpress' ), 'post_type' => 'lists', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'image' || $type == 'images'){
			$attributes = array( 'title' => __( 'Add image', 'viralpress' ), 'post_type' => 'images', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'meme'){
			$attributes = array( 'title' => __( 'Add meme', 'viralpress' ), 'post_type' => 'meme', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'polls' || $type == 'poll'){
			$attributes = array( 'title' => __( 'Create poll', 'viralpress' ), 'post_type' => 'polls', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'quiz'){
			$attributes = array( 'title' => __( 'Create a quiz', 'viralpress' ), 'post_type' => 'quiz', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'videos' || $type == 'video'){
			$attributes = array( 'title' => __( 'Add video', 'viralpress' ), 'post_type' => 'videos', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'audio'){
			$attributes = array( 'title' => __( 'Add audio', 'viralpress' ), 'post_type' => 'audio', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'pin' || $type == 'pins'){
			$attributes = array( 'title' => __( 'Add pin', 'viralpress' ), 'post_type' => 'pins', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'gallery'){
			$attributes = array( 'title' => __( 'Submit gallery', 'viralpress' ), 'post_type' => 'gallery', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else if($type == 'playlist'){
			$attributes = array( 'title' => __( 'Submit playlist', 'viralpress' ), 'post_type' => 'playlist', 'vp_instance' => &$this);
			return vp_get_template_html( 'create', $attributes );	
		}
		else{
			$attributes['post_type'] = '';
			return vp_get_template_html( 'editor', $attributes );	
		}
	}
	
	/**
	 * viralpress meme generator
	 * @since 3.0
	 */
	public function vp_meme_generator( $in = array() )
	{
		if ( !is_user_logged_in() ) {
			return __( 'You must be logged in to create new post!', 'viralpress' );
		}
	
		$attributes = array( 'title' => __( 'Meme generator', 'viralpress' ), 'vp_instance' => &$this, 'in' => $in );
		return vp_get_template_html( 'meme', $attributes );	
	}
	
	/**
	 * prints the plugin update message
	 * @since 1.0
	 */
	public function plugin_update_message()
	{
		$d = $this->settings[ 'PLUGIN_DIR' ] . '/toupdate.txt';
		if( file_exists( $d ) ) {
			
			$plugin_slug = $this->settings['PLUGIN_SLUG'];
			$username = $this->settings[ 'envato_username' ];
			$api_key = $this->settings[ 'envato_api_key' ];
			$purchase_code = $this->settings[ 'envato_purchase_code' ];
			
			if ( empty( $username ) || empty( $api_key ) || empty( $purchase_code ) ) {
				echo '<div class="update-nag">' . sprintf( __( 'A new version of this plugin is available. Download from %s CodeCanyon %s .', 'viralpress' ), '<a href="' . $this->item_link . '">', '</a>' ) . '</a></div>';
			} 
			else {
				echo '<div class="update-nag">' . sprintf( __( 'A new version of this plugin is available. Click %s here %s to update ViralPress now.', 'viralpress' ), '<a href="' . wp_nonce_url( admin_url( 'update.php?action=upgrade-plugin&plugin=' . $plugin_slug ), 'upgrade-plugin_' . $plugin_slug ) . '">', '</a>' ) . '</a></div>';
			}		
		}
	}
}
 
endif;

/**
 * initialize viralpress
 */
$vp_instance = new ViralPress();
$vp_instance->init();

/**
 * initialize viralpress user
 */
if( empty( $vp_instance->settings['disable_login'] ) ) {
	$vp_user = new vp_user();
	$vp_user->init();
}
?>