<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

class vp_updater
{
	const PLUGIN_TITLE = "ViralPress";
	public $current_version;
	public $plugin_slug;
	public $slug;
	public $item_link;
	public $update_host;
	public $vp_settings;
	public $hook;
		
	public function __construct( $settings, $current_version, $item_link, $update_host )
	{
		$this->vp_settings = $settings;
		$this->plugin_slug = $settings['PLUGIN_SLUG'];
		$this->current_version = $current_version;
		$this->update_host = $update_host;
		$this->item_link = $item_link;
		
		$t = explode( '/', $this->plugin_slug );
		$this->slug = str_replace( '.php', '', end($t) );
		
		$folder = $settings['PLUGIN_FOLDER'];
		$file = $settings['PLUGIN_FILE'];
		
		$hook = "in_plugin_update_message-{$folder}/{$file}";
		$this->hook = $hook;
		
		add_action( 'init', array( &$this, 'vp_plugin_update_hooks'), 0 );
	}
	
	public function vp_plugin_update_hooks()
	{
		add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'vp_check_update' ) );
		add_action( $this->hook, array( &$this, 'vp_update_message' ), 10, 2 ); 
		add_filter( 'plugins_api', array( &$this, 'vp_check_info' ), 10, 3 );
		add_filter( 'plugin_row_meta', array( &$this, 'vp_plugin_row_meta' ) , 10, 2 );
		
		add_filter( 'upgrader_pre_download', array( &$this, 'vp_download_from_envato' ), 10, 4 );
		add_action( 'upgrader_process_complete', array( &$this, 'vp_upgrade_done' ) );	
		add_filter( 'plugin_action_links_' . $this->plugin_slug, array( &$this, 'vp_plugin_action_links' ) );

	}
	
	public function vp_check_update( $transient ) 
	{
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		
		$remote_version = $this->check_vp_version();
		if( empty( $remote_version ) ) $remote_version = $this->current_version; 

		if ( version_compare( $this->current_version, $remote_version, '<' ) ) {
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $remote_version;
			$obj->url = '';
			$obj->package = '';
			$obj->name = self::PLUGIN_TITLE;
			$transient->response[ $this->plugin_slug ] = $obj;
			
			file_put_contents( $this->vp_settings[ 'PLUGIN_DIR' ] . '/toupdate.txt', '' );
		}

		return $transient;
	}
	
	public function check_vp_version()
	{
		$data = wp_remote_get( $this->update_host, array( 'sslverify' => false ) );
		
		if( is_wp_error( $data ) ) return false;
		
		if( $data['response']['code'] != 200 ){
			return false;
		}
		
		$data = unserialize( $data['body'] );
		if( !empty( $data->new_version ) ) {
			$latest_v = $data->new_version;
			return $latest_v;
		}
		
		return false;
	}
	
	public function vp_check_info( $data, $action, $arg ) 
	{
		if ( isset( $arg->slug ) && $arg->slug === $this->slug ) {
			
			$information = $this->get_vp_info();
			return $information;
		}
		
		return $data;
	}
	
	
	public function vp_plugin_row_meta( $links, $file ) 
	{
		if ( strpos( $file, $this->plugin_slug ) !== false ) {
			$details = get_admin_url().'plugin-install.php?tab=plugin-information&plugin=viralpress&TB_iframe=true&width=600&height=550';
			$new_links = array(
					'details' => '<a href="'.$details.'" class="thickbox">'.__( 'View details', 'viralpress' ).'</a>',
					'doc' => '<a href="https://drive.google.com/file/d/0B34QQcRSxhm2Sm9rOVE2MW9MWDQ/view" target="_blank">'.__( 'Documentation', 'viralpress' ).'</a>'
			);
			
			$links = array_merge( $links, $new_links );
		}
	
		return $links;	
	}
	
	public function vp_plugin_action_links( $links )
	{
		return array_merge(
		array(
				'settings' => '<a href="' . get_admin_url() . 'admin.php?page=viralpress">'.__( 'Settings', 'viralpress' ).'</a>'
			),
			$links
		);
	}
	
	public function get_vp_info() 
	{
		$request = wp_remote_get( $this->update_host );
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return unserialize( $request['body'] );
		}

		return false;
	}
	
	public function vp_update_message() 
	{
		$username = $this->vp_settings[ 'envato_username' ];
		$api_key = $this->vp_settings[ 'envato_api_key' ];
		$purchase_code = $this->vp_settings[ 'envato_purchase_code' ];
		
		if ( empty( $username ) || empty( $api_key ) || empty( $purchase_code ) ) {
			echo ' <a href="' . $this->item_link . '">' . __( 'Download new version from CodeCanyon.', 'viralpress' ) . '</a>';
		} 
		else {
			echo '<a href="' . wp_nonce_url( admin_url( 'update.php?action=upgrade-plugin&plugin=' . $this->plugin_slug ), 'upgrade-plugin_' . $this->plugin_slug ) . '">' . __( 'Click here to update ViralPress now.', 'viralpress' ) . '</a>';
		}
	}

	public function vp_download_from_envato( $default, $package, $updater )
	{
		global $wp_filesystem;

		if ( ( isset( $updater->skin->plugin ) && $updater->skin->plugin === $this->plugin_slug ) ||
		     ( isset( $updater->skin->plugin_info ) && $updater->skin->plugin_info['Name'] === self::PLUGIN_TITLE )
		) {
			$updater->strings['downloading'] = __( 'Downloading package from envato...', 'viralpress' );
			$updater->skin->feedback( 'downloading' );
			
			$package_filename = 'viralpress.zip';
			
			$res = $updater->fs_connect( array( WP_CONTENT_DIR ) );
			if ( ! $res ) {
				return new WP_Error( 'no_credentials', __( "Failed to connect to wordpress file system", 'viralpress' ) );
			}
			
			$username = $this->vp_settings[ 'envato_username' ];
			$api_key = $this->vp_settings[ 'envato_api_key' ];
			$purchase_code = $this->vp_settings[ 'envato_purchase_code' ];
		
			if ( vp_check_license() == - 1 || empty( $username ) || empty( $api_key ) || empty( $purchase_code ) ) {
				return new WP_Error( 'no_credentials', sprintf( __( 'ViralPress license is not activated. Please visit %s here %s to activate', 'viralpress' ), '<a href="' . admin_url( 'admin.php?page=viralpress-update' ) . '" target="_blank">', '</a>' ) );
			}
			
			$json = wp_remote_get( $this->query_envato_downloads( $username, $api_key, $purchase_code ) );
			$result = json_decode( $json['body'], true );
			if ( ! isset( $result['download-purchase']['download_url'] ) ) {
				return new WP_Error( 'no_credentials', sprintf( __( 'Envato API error%s' , 'viralpress' ), isset( $result['error'] ) ? ': ' . $result['error'] : '.'  ) );
			}
			
			$result['download-purchase']['download_url'];
			$download_file = download_url( $result['download-purchase']['download_url'] );
			if ( is_wp_error( $download_file ) ) {
				return $download_file;
			}
			
			$upgrade_folder = $wp_filesystem->wp_content_dir() . 'uploads/vp_update_dir/';
			if ( is_dir( $upgrade_folder ) ) {
				$wp_filesystem->delete( $upgrade_folder, true );
			}
			$result = unzip_file( $download_file, $upgrade_folder );
			if ( $result && is_file( $upgrade_folder . '/' . $package_filename ) ) {
				return $upgrade_folder . '/' . $package_filename;
			}
			$this->vp_upgrade_done();
			return new WP_Error( 'no_credentials', __( 'Error on unzipping package', 'viralpress' ) );
		}
		
		return $default;
	}
	
	public function vp_upgrade_done() 
	{
		global $wp_filesystem;
		if ( is_dir( $wp_filesystem->wp_content_dir() . 'uploads/vp_update_dir' ) ) {
			$wp_filesystem->delete( $wp_filesystem->wp_content_dir() . 'uploads/vp_update_dir', true );
		}
	}

	private function query_envato_downloads( $username, $api_key, $purchase_code ) 
	{
		return 'http://marketplace.envato.com/api/edge/' . rawurlencode( $username ) . '/' . rawurlencode( $api_key ) . '/download-purchase:' . rawurlencode( $purchase_code ) . '.json';
	}
}