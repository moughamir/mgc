<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

class vp_user
{
	public $email;
	
	public function __construct()
	{	
	}
	
	public function init()
	{
		add_action( 'wp_loaded', array( &$this, 'vp_logout' ) );
		add_action( 'init', array( &$this, 'render_modals') , 10 );
		
		add_action( 'login_form_login', array( &$this, 'redirect_to_viralpress_login' ) );
		add_action( 'login_form_register', array( &$this, 'redirect_to_vp_register' ) );
		add_action( 'login_form_register', array( &$this, 'do_register_user' ) );
		add_action( 'login_form_lostpassword', array( &$this, 'redirect_to_vp_lostpassword' ) );
		add_action( 'login_form_rp', array( &$this, 'redirect_to_vp_password_reset' ) );
		add_action( 'login_form_resetpass', array( &$this, 'redirect_to_vp_password_reset' ) );
		
		add_action( 'login_form_lostpassword', array( &$this, 'do_password_lost' ) );
		add_action( 'login_form_rp', array( &$this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( &$this, 'do_password_reset' ) );
		add_action( 'wp_authenticate', array( &$this, 'vp_login_with_email_address' ) );
		
		add_filter( 'authenticate', array( &$this, 'validate_authentication' ), 101, 3 );
		
		add_shortcode( 'viralpress_registration_page' , array( &$this, 'render_viralpress_registration_page' ) );
		add_shortcode( 'vp_password_lost_form' , array( &$this, 'render_viralpress_password_lost_form' ) );
		add_shortcode( 'vp_password_reset_form' , array( &$this, 'render_viralpress_password_reset_form' ) );
		add_shortcode( 'viralpress_login_page' , array( &$this, 'render_viralpress_login_page' ) );
		
		//add_filter( 'login_url', array( &$this, 'vp_login_url' ), 10, 3 );
		//add_filter( 'register_url', array( &$this, 'vp_register_url' ), 10, 1 );
	}
	
	public function render_modals()
	{
		if( !is_user_logged_in() ){
			add_action( 'get_footer', array( &$this, 'vp_render_login_modal' ) );
			add_action( 'get_footer', array( &$this, 'vp_render_register_modal' ) );
			add_action( 'get_footer', array( &$this, 'vp_render_forgot_modal' ) );	
		}
	}
	
	function vp_login_with_email_address( &$username ) 
	{
		$user = get_user_by( 'email', $username );
		
		if ( !empty( $user->user_login ) ) {
			$this->email = $username;
			$username = $user->user_login;
		}
		return $username;
	}
	
	public function render_viralpress_login_page( $attributes = array() )
	{	
		if ( is_user_logged_in() ) {
			return __( 'You are already logged in!', 'viralpress' );
		}
		
		$attributes = array(); 
		$attributes['redirect'] = '';
		$attributes['registered'] = isset( $_REQUEST['registered'] );
		$attributes['lost_password_sent'] = @ isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';
		$attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';
		
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
			$error_codes = explode( ',', $_REQUEST['login'] );
		 
			foreach ( $error_codes as $code ) {
				$errors []= $this->get_error_message( $code );
			}
		}
		$attributes['errors'] = $errors;
		
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
		}
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;
		
		$login = vp_get_template_html( 'login', $attributes );
		$sdk = vp_get_template_html( 'sdks',  array( 'load_fb' => 1, 'load_goo' => 1 ) );
		
		return $login.' '.$sdk;
	}
	
	public function redirect_to_viralpress_login()
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
		 
			if ( is_user_logged_in() ) {
				if ( $redirect_to ) {
					wp_safe_redirect( $redirect_to );
				} else {		
					$this->redirect_logged_in_user();
				}
				exit;
			}
	 
			// The rest are redirected to the login page
			/*$login_url = home_url( 'login' );
			if ( ! empty( $redirect_to ) ) {
				$login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
			}
	 
			wp_redirect( $login_url );
			exit;*/
		}
	}
	
	public function validate_authentication( $user, $username, $password )
	{
		global $vp_instance;
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			if ( is_wp_error( $user ) ) {
				$error_codes = join( ',', $user->get_error_codes() );
	 
				$login_url = home_url( $vp_instance->settings['page_slugs']['login'] );
				$login_url = add_query_arg( array( 'login' => $error_codes, 'username' => $this->email ? $this->email : $username), $login_url );
	 
				wp_redirect( $login_url );
				exit;
			}
		}
		
		if( is_user_logged_in() ) {
			wp_redirect( home_url( $vp_instance->settings['page_slugs']['dashboard'] ) );
		}
		
		return $user;
	}
	
	public function vp_logout_page($logout_url, $redirect)
	{
		return home_url( '/logout/' );
	}
	
	public function vp_logout()
	{
		if( isset($_GET['logout']) ) { 
			wp_logout();
			
			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$redirect_url = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
			}
			else $redirect_url = home_url( $vp_instance->settings['page_slugs']['login'].'?logged_out=true' );
			
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}
	
	public function vp_render_login_modal()
	{
		$attributes = array( 'show_err' => 1 );
		$attributes['redirect'] = '';
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
		}
		else {
			$attributes['redirect'] = wp_validate_redirect( $_SERVER['REQUEST_URI'] );
		}
		
		add_thickbox();
		echo 
		'<a href="#TB_inline?width=350&height=600&inlineId=login_modal" class="thickbox login_modal_link" id="login_modal_link" style="display:none"></a>
		<div id="login_modal">';
		print_login_form( $attributes )	;
		echo '</div>';
		echo vp_get_template_html('sdks', array( 'load_fb' => 1, 'load_goo' => 1 ) );
	}
	
	
	public function render_viralpress_registration_page( $attributes, $content = null )
	{
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['register-errors'] ) ) {
			$error_codes = explode( ',', $_REQUEST['register-errors'] );
		 
			foreach ( $error_codes as $error_code ) {
				$attributes['errors'] []= $this->get_error_message( $error_code );
			}
		}

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'viralpress' );
		} elseif ( ! get_option( 'users_can_register' ) ) {
			return __( 'Registering new users is currently not allowed.', 'viralpress' );
		} else {
			return vp_get_template_html( 'register', $attributes );
		}
	}
	
	public function redirect_to_vp_register()
	{
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
				exit;
			} else {
				//wp_redirect( home_url( 'register' ) );
			}
		}	
	}
	
	private function register_user( $email, $username ) {
		
		global $vp_instance;
		$errors = new WP_Error();
		
		if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_login'] ) {
			 $resp = recaptcha_check_answer (
			 				$vp_instance->settings['recap_secret'], 
							$_SERVER["REMOTE_ADDR"],
							@$_POST["recaptcha_challenge_field"],
							@$_POST["recaptcha_response_field"]
					);

        	if ( ! $resp->is_valid ) {
                $error = $resp->error;
				$errors->add( 'recap', $error );
				return $errors;
        	}
	
		}
	
		if ( ! is_email( $email ) ) {
			$errors->add( 'email', $this->get_error_message( 'email' ) );
			return $errors;
		}
		
		if( empty($username) || !validate_username( $username ) ) {
			$errors->add( 'name', $this->get_error_message( 'name' ) );
			return $errors;
		}
		
		if ( email_exists( $email ) ) {
			$errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
			return $errors;
		}
		
		if ( username_exists( $username ) ) {
			$errors->add( 'name_exists', $this->get_error_message( 'name_exists') );
			return $errors;
		}
		
		$password = wp_generate_password( 12, false );
		
		$user_data = array(
			'user_login'    => $username,
			'user_email'    => $email,
			'user_pass'     => $password,
			'nickname'      => $username,
			'user_nicename' => $username,
			'role' 		=>    'contributor',
		  	'show_admin_bar_front' => false
		);
		
		$user_id = wp_insert_user( $user_data );
		wp_new_user_notification( $user_id, null, 'both' );
		
		return $user_id;
	}
	
	public function do_register_user() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$redirect_url = home_url( $vp_instance->settings['page_slugs']['register'] );
	 
			if ( ! get_option( 'users_can_register' ) ) {
				$redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
			} else {
				$email = $_POST['email'];
				$username = $_POST['username'];
	 
				$result = $this->register_user( $email, $username );
	 
				if ( is_wp_error( $result ) ) {
					$errors = join( ',', $result->get_error_codes() );
					$redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
				} else {
					$redirect_url = home_url( 'login' );
					$redirect_url = add_query_arg( 'registered', $username, $redirect_url );
				}
			}
	 
			wp_redirect( $redirect_url );
			exit;
		}
	}
	
	public function vp_render_register_modal()
	{
		$attributes = array( 'show_err' => 1 );
		$attributes['redirect'] = '';
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
		}
		
		add_thickbox();
		echo 
		'<a href="#TB_inline?width=350&height=600&inlineId=register_modal" class="thickbox register_modal_link" id="register_modal_link" style="display:none"></a>
		<div id="register_modal">';
		print_register_form( $attributes )	;
		echo '</div>';
		echo vp_get_template_html('sdks', array( 'load_fb' => 1, 'load_goo' => 1 ) );
	}
	
	public function render_viralpress_password_lost_form()
	{
		$attributes = array( 'show_title' => false );
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['errors'] ) ) {
			$error_codes = explode( ',', $_REQUEST['errors'] );
		 
			foreach ( $error_codes as $error_code ) {
				$attributes['errors'] []= $this->get_error_message( $error_code );
			}
		}
	
		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'viralpress' );
		} else {
			return vp_get_template_html( 'password_lost', $attributes );
		}
	}
	
	public function render_viralpress_password_reset_form()
	{
		$attributes = array( 'show_title' => false );
		
		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'viralpress' );
		} else {
			if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
				$attributes['login'] = $_REQUEST['login'];
				$attributes['key'] = $_REQUEST['key'];
	 
				$errors = array();
				if ( isset( $_REQUEST['error'] ) ) {
					$error_codes = explode( ',', $_REQUEST['error'] );
	 
					foreach ( $error_codes as $code ) {
						$errors []= $this->get_error_message( $code );
					}
				}
				$attributes['errors'] = $errors;
	 
				return vp_get_template_html( 'password_reset', $attributes );
			} else {
				return __( 'Invalid password reset link.', 'viralpress' );
			}
		}
	}
	
	public function redirect_to_vp_password_reset() {
		
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			$user = check_password_reset_key( @$_REQUEST['key'], @$_REQUEST['login'] );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( $vp_instance->settings['page_slugs']['login'].'?login=expiredkey' ) );
				} else {
					wp_redirect( home_url( $vp_instance->settings['page_slugs']['login'].'?login=invalidkey' ) );
				}
				exit;
			}
	 
			$redirect_url = home_url( $vp_instance->settings['page_slugs']['password-reset'] );
			$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
			$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
	 
			wp_redirect( $redirect_url );
			exit;
		}
	}
	
	public function redirect_to_vp_lostpassword()
	{
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
				exit;
			}
	 
			wp_redirect( home_url( $vp_instance->settings['page_slugs']['password-lost'] ) );
			exit;
		}
	}
	
	public function do_password_lost()
	{
		global $vp_instance;
		$errors = new WP_Error();
		
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			
			if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_login'] ) {
				 $resp = recaptcha_check_answer (
							$vp_instance->settings['recap_secret'], 
							$_SERVER["REMOTE_ADDR"],
							@$_POST["recaptcha_challenge_field"],
							@$_POST["recaptcha_response_field"]
					);
				if ( ! $resp->is_valid ) {
					$error = $resp->error;
					$errors->add( 'recap', $error );
					$redirect_url = home_url( $vp_instance->settings['page_slugs']['password-lost'] );
					$redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
				}
			}
			
			if ( is_wp_error( $errors ) ) {
				$redirect_url = home_url( $vp_instance->settings['page_slugs']['password-lost'] );
				$redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
			} 
			else {			
				$errors = retrieve_password();
				if ( is_wp_error( $errors ) ) {
					$redirect_url = home_url( $vp_instance->settings['page_slugs']['password-lost'] );
					$redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
				} else {
					$redirect_url = home_url( $vp_instance->settings['page_slugs']['login'] );
					$redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
				}
			}
	 
			wp_redirect( $redirect_url );
			exit;
		}
	}
	
	public function do_password_reset() {
		
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$rp_key = $_REQUEST['rp_key'];
			$rp_login = $_REQUEST['rp_login'];
	 
			$user = check_password_reset_key( $rp_key, $rp_login );
	 
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( $vp_instance->settings['page_slugs']['login'].'?login=expiredkey' ) );
				} else {
					wp_redirect( home_url( $vp_instance->settings['page_slugs']['login'].'?login=invalidkey' ) );
				}
				exit;
			}
	 
			if ( isset( $_POST['pass1'] ) ) {
				if ( $_POST['pass1'] != $_POST['pass2'] ) {
					// Passwords don't match
					$redirect_url = home_url( $vp_instance->settings['page_slugs']['password-reset'] );
	 
					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
	 
					wp_redirect( $redirect_url );
					exit;
				}
	 
				if ( empty( $_POST['pass1'] ) ) {
					// Password is empty
					$redirect_url = home_url( $vp_instance->settings['page_slugs']['password-reset'] );
	 
					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
	 
					wp_redirect( $redirect_url );
					exit;
				}
	 
				// Parameter checks OK, reset password
				reset_password( $user, $_POST['pass1'] );
				wp_redirect( home_url( $vp_instance->settings['page_slugs']['login'].'?password=changed' ) );
			} else {
				echo "Invalid request.";
			}
	 
			exit;
		}
	}
	
	public function vp_render_forgot_modal()
	{
		$attributes = array( 'show_err' => 1, 'show_title' => 1 );
		$attributes['redirect'] = '';
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
		}
		else {
			$attributes['redirect'] = wp_validate_redirect( $_SERVER['REQUEST_URI'] );
		}
		
		add_thickbox();
		echo 
		'<a href="#TB_inline?width=350&height=350&inlineId=forgot_modal" class="thickbox forgot_modal_link" id="forgot_modal_link" style="display:none"></a>
		<div id="forgot_modal">';
		print_password_lost_form( $attributes )	;
		echo '</div>';
	}
	
	public function redirect_logged_in_user()
	{
		wp_redirect( home_url( $vp_instance->settings['page_slugs']['dashboard'] ) );
		exit;
	}
	
	public function vp_login_url( $login_url, $redirect, $force_reauth )
	{
		return home_url( $vp_instance->settings['page_slugs']['login'].'?redirect_to=' . $redirect );
	}
	
	public function vp_register_url( $register_url )
	{
		return home_url( $vp_instance->settings['page_slugs']['register'] );
	}
	
	public function vp_lostpass_url( $lostpassword_url, $redirect )
	{
		return home_url( $vp_instance->settings['page_slugs']['password-lost'].'?redirect_to='.$redirect );
	}
	
	static function render_viralpress_profile_page( $attributes = array() )
	{	
		if ( !is_user_logged_in() ) {
			return __( 'You are not logged in!', 'viralpress' );
		}
		
		$attributes = array(); 
		$attributes['skip_headers'] = 1;
		
		return vp_get_template_html( 'profile', $attributes );
	}
	
	public function get_error_message( $error_code ) {
		switch ( $error_code ) {
			case 'recap':
				return __( 'Invalid captcha response', 'viralpress' );
				
			case 'empty_username':
				return __( 'Email address or username required.', 'viralpress' );
		
			case 'empty_password':
				return __( 'Password required', 'viralpress' );
		
			case 'invalid_username':
				return __(
					"Invalid login information provided.",
					'viralpress'
				);
		
			case 'incorrect_password':
				$err = __(
					"Invalid login information provided.",
					'viralpress'
				);
				return sprintf( $err, wp_lostpassword_url() );
		
			case 'email':
				return __( 'The email address you entered is not valid.', 'viralpress' );
			 
			case 'email_exists':
				return __( 'An account exists with this email address.', 'viralpress' );
			 
			case 'closed':
				return __( 'Registering new users is currently not allowed.', 'viralpress' );
			
			case 'name':
				return __( 'Valid username required.', 'viralpress' );
				
			case 'name_exists':
				return __( 'This username is already taken.', 'viralpress' );	

			case 'empty_username':
				return __( 'You need to enter your email address or username to continue.', 'viralpress' );
			 
			case 'invalid_email':
			case 'invalidcombo':
				return __( 'There are no users registered with this email address or username.', 'viralpress' );

			case 'expiredkey':
			case 'invalidkey':
				return __( 'The password reset link you used is not valid anymore.', 'viralpress' );
			 
			case 'password_reset_mismatch':
				return __( "The two passwords you entered don't match.", 'viralpress' );
				 
			case 'password_reset_empty':
				return __( "Sorry, we don't accept empty passwords.", 'viralpress' );

			default:
				break;
		}
		 
		return __( 'An unknown error occurred. Please try again later.', 'viralpress' );
	}
}

?>