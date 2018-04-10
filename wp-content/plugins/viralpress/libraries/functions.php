<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

function vp_get_template_html ( $template_name, $attributes = array() )
{
	global $vp_instance;
	
	if ( empty( $attributes ) ) {
		$attributes = array();
	}
	
	ob_start();
	do_action( 'viralpress_before_' . $template_name );
	include_once( $vp_instance->settings['TEMPLATE_DIR'].'/' . $template_name . '.php');
	do_action( 'viralpress_after_' . $template_name );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_'.$template_name, $html );
	return $html;		
}

function vp_text_entry_form( )
{
	ob_start();
	wp_editor( '', 'newsentrydesc', array( 'textarea_name' => 'news_entry_desc[]', 'media_buttons' => false, 'textarea_rows' => 8 ) );
	$editor = ob_get_contents();
	ob_clean();
	
	$html = '<div style="display:none">
				'.$editor.'
			</div>';
			
	return $html;
}	

function vp_output( $response )
{
	echo json_encode( $response );
	exit;	
}

function vp_create_user( $username, $email, $password, $meta_key = '', $meta_value = '' )
{
	if( empty($username) )$username = strtok( $email, '@' );
	if( username_exists( $email ) ){
		return __( 'Email already exists', 'viralpress' );	
	}	
	
	if( empty($password) )$password = wp_generate_password( 12,  false );
	$user_id = wp_create_user( $email, $password, $email );
	if( is_wp_error($user_id) || empty( $user_id ) )return false;
	
	if( !empty($meta_key) && !empty($meta_value) ) {
		add_user_meta( $user_id, $meta_key, $meta_value );
	}
	
	wp_update_user(
		array(
		  'ID'          =>    $user_id,
		  'nickname'    =>    $username,
		  'display_name'=>    $username,
		  'user_nicename' => $username,
		  'role' 		=>    'contributor',
		  'show_admin_bar_front' => false
		)
	);
	
	return $user_id;
}

function vp_download_avatar( $url, $user_id )
{
	$upload_dir = WP_CONTENT_DIR.'/uploads/avatar';
	if( !is_dir($upload_dir) )wp_mkdir_p( $upload_dir );
	
	$data = wp_remote_get( $url, array( 'sslverify' => false ) );
	
	if( is_wp_error( $data ) ) return false;
	
	if( $data['response']['code'] != 200 ){
		return false;
	}
	
	$body = $data['body'];
	$imagename = md5($user_id).'.jpg';
	$image = $upload_dir.'/'.$imagename;
	file_put_contents( $image, $body );
	
	list( $w, $h ) = getimagesize($image);
	if( $w && $h )return WP_CONTENT_URL.'/uploads/avatar/'.$imagename;
	
	@unlink($image);
	return false;
}

function add_update_user_meta( $user_id, $meta_key, $meta_value )
{
	//if( !add_user_meta( $user_id, $meta_key, $meta_value, true ) ) {
	update_user_meta( $user_id, $meta_key, $meta_value );
	//}
}

function add_update_post_meta( $post_id, $meta_key, $meta_value )
{
	//if( !add_post_meta( $post_id, $meta_key, $meta_value, true ) ) {
	update_post_meta( $post_id, $meta_key, $meta_value );
	//}
}

function vp_validate_post_type( $value )
{
	$value = strtolower( $value );
	$valid = array('news', 'lists', 'polls', 'quiz', 'videos', 'audio', 'pins', 'gallery', 'playlist', 'images', 'meme');
	if( !in_array($value, $valid) )return false;
	return true;
}

/**
 * @deprecated since 2.6
 */
function verify_video_entry_value( $value )
{
	$allowed_extra = get_allowed_embed_regex();
	$allowed_sites = array( 'youtube', 'facebook', 'dailymotion', 'vimeo', 'vine', 'ted', 'bbc', 'liveleak', 'custom' );
	
	$vid = explode( '|', $value);
	
	if( count($vid) != 5)return false;
	if( $vid[0] != 'video' )return false;
	
	//site
	if( !in_array( $vid[1], $allowed_sites ) ) {
		if( !preg_match( '/'.$allowed_extra.'/siU', $vid[2] ) ) return false;
	}
	
	if( $vid[1] == 'facebook' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/facebook\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'ted' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/ted\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'liveleak' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/liveleak\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'bbc' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/bbc\.co/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'custom' ){
		if( filter_var( $vid[2], FILTER_VALIDATE_URL ) === false )return false;		
	}
	else {
		if(preg_match( '/[^a-z0-9\-\_]/i', $vid[2] ))return false;		
	}
	
	if( !is_numeric( $vid[3]) )return false;
	if( !is_numeric( $vid[4]) )return false;
	
	return true;
}

/**
 * @deprecated since 2.6
 */
function verify_audio_entry_value( $value )
{
	$allowed_extra = get_allowed_embed_regex();
	$allowed_sites = array( 'soundcloud', 'custom' );
	
	$vid = explode( '|', $value);
	
	if( count($vid) != 5)return false;
	if( $vid[0] != 'audio' )return false;
	
	//site
	if( !in_array( $vid[1], $allowed_sites ) ){
		if( !preg_match( '/'.$allowed_extra.'/siU', $vid[2] ) ) return false;
	}
	
	if( $vid[1] == 'soundcloud' ){
		if(preg_match( '/[^a-z0-9\-\_\/]/i', $vid[2] ))return false;	
	}
	else if( $vid[1] == 'custom' ){
		if( filter_var( $vid[2], FILTER_VALIDATE_URL ) === false )return false;		
	}
	else {
		if(preg_match( '/[^a-z0-9\-\_]/i', $vid[2] ))return false;		
	}
	
	if( !is_numeric( $vid[3]) )return false;
	if( !is_numeric( $vid[4]) )return false;
	
	return true;
}

function get_video_thumb_from_url( $value, $post_id ) 
{
	//'vimeo'
	$vid = explode( '|', $value);
	if( !in_array( $vid[1], array( 'youtube', 'dailymotion' ) ) ) {
		return false;	
	}	
	
	$url = '';
	$title = '';
	if( $vid[1] == 'youtube' ) {
		$url = 'http://img.youtube.com/vi/'.$vid[2].'/maxresdefault.jpg';	
	}
	else {
		if( $vid[1] == 'vimeo' ) $u = 'https://vimeo.com/'.$vid[2];
		else $u = $vid[2];
		
		$html = wp_remote_get( $u );
				
		try{		
			$doc = new DOMDocument();
			@$doc->loadHTML($html['body']);
			
			$metas = $doc->getElementsByTagName('meta');
			
			for ($i = 0; $i < $metas->length; $i++) {
				$meta = $metas->item($i);
				if($meta->getAttribute('property') == 'og:image') $url = $meta->getAttribute('content');
				else if($meta->getAttribute('property') == 'og:title') $title = html_entity_decode( $meta->getAttribute('content') );	
			}
		}catch( Exception $e ){return false;}
	}
	
	//file_put_contents( dirname(__FILE__).'/a.txt', $u.'|'.$url );
	
	if( !empty( $url ) ) {
		$src = media_sideload_image( $url, $post_id, $title, 'src' );
		$id = vp_get_attachment_id_from_src( $src );
		if( is_numeric( $id ) ) return $id;
		return false;	
	}
	
	return false;
}

function vp_get_attachment_id_from_src( $image_src ) 
{
  global $wpdb;
  $query = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid = %s ", $image_src );
  $id = $wpdb->get_var($query);
  return $id;
}

function verify_embed_entry_value( $value )
{
	$allowed_extra = get_allowed_embed_regex();
	$allowed_sites = array( 'youtube', 'facebook', 'dailymotion', 'vimeo', 'ted', 'bbc', 'liveleak', 'instagram', 'fbpage', 'twitter', 'twitter_profile', 'vine', 'pinterest_pin', 'pinterest_board', 'pinterest_profile', 'gplus', 'soundcloud' , 'custom' );
	
	$vid = explode( '|', $value);
	
	if( count($vid) != 5)return false;
	if( $vid[0] != 'pin' && $vid[0] != 'video' && $vid[0] != 'audio' )return false;
	
	//site
	if( !in_array( $vid[1], $allowed_sites ) ){
		if( !preg_match( '/'.$allowed_extra.'/siU', $vid[2] ) ) return false;
	}
	
	if( $vid[1] == 'facebook' || $vid[1] == 'fbpage' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/facebook\.com/i', $uu['host'] ))return false;	
	}
	else if( preg_match( '/pinterest/i', $vid[1] ) ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/pinterest\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'gplus' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/plus\.google\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'facebook' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/facebook\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'ted' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/ted\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'liveleak' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/liveleak\.com/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'bbc' ){
		$uu = parse_url( $vid[2] );
		if(!preg_match( '/bbc\.co/i', $uu['host'] ))return false;	
	}
	else if( $vid[1] == 'soundcloud' ){
		if(preg_match( '/[^a-z0-9\-\_\/]/i', $vid[2] ))return false;	
	}
	else if( $vid[1] == 'custom' ){
		if( filter_var( $vid[2], FILTER_VALIDATE_URL ) === false )return false;		
	}
	else {
		if(preg_match( '/[^a-z0-9\-\_]/i', $vid[2] ))return false;		
	}
	
	if( !is_numeric( $vid[3]) )return false;
	if( !is_numeric( $vid[4]) )return false;
	
	return true;
}

function vp_print_post_data( $small = 0 )
{
	global $post;
	
	if($small)$h = 'h4';
	else $h = 'h2';
	
	$pid = get_the_ID();
	$cats = get_the_category( $pid );
	$cats = end($cats);
	$cat_name = esc_html( $cats->name );
	
	$thumb = $small ? get_the_post_thumbnail( null, 'vp-thumbnail-300' ) : get_the_post_thumbnail( null, 'vp-thumbnail-300' );
	if( empty($thumb) ) {
		$img = get_post_meta( $pid, 'vp_list_image_entry');
		if( !empty($img[0]) ) $img = wp_get_attachment_link( $img[0], 'vp-thumbnail-300' ); 	
	}
	
	ob_start();
	do_action( 'viralpress_print_post_data_before_loop' );
	?>
    <div class="entry vp_post_entry">
        <div class="row">
            <div class="col-lg-3">
                <?php echo get_avatar( $post->post_author, 49 );?>
                <footer class="entry-footer">                	                    
					<?php the_author_posts_link(); ?><br/>
                    <?php the_time( get_option('date_format') )?><br/>
                    <?php if( current_user_can( 'edit_post', $pid ) )edit_post_link()?>
                </footer>
            </div>
            <div class="col-lg-9">
                <<?php echo $h?>><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></<?php echo $h?>>
                <div class="entry">
                    <?php the_excerpt(); ?> 
                    <div style="clear:both"></div>
                    <?php
					if( $thumb )echo $thumb.'<div style="clear:both"></div><br/>';
					else if( !empty( $img ) )echo $img.'<div style="clear:both"></div><br/>';
					?>				
                </div>
                <span class="label label-info"><?php echo $cat_name?></span>
                <span class="label label-danger"><?php echo get_post_type( $pid )?></span>
                <br/><br/>
                <hr/>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
    <?php
	do_action( 'viralpress_print_post_data_after_loop' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_print_post_data_loop', $html, $pid );
	echo $html;
}

function print_login_form( $attributes = array() )
{
	global $vp_instance;
	ob_start();
	do_action( 'viralpress_before_login_form' );
	?>
    <div class="login-form-container" style="max-width:300px">
    	<?php if ( !empty($attributes['show_title']) ) : ?>
            <h2><?php _e( 'Login', 'viralpress' ); ?></h2>
        <?php endif;?>
		
        <?php if ( !empty($attributes['show_err']) ) :?> 
		<div class="login-error"></div>
		<?php endif;?>
        
		<?php 
        $or = 0;
		
		if( !empty( $vp_instance->settings['wsl_int'] ) ) {
			$or = 1;
			echo '<div style="text-align:center">'.do_shortcode( '[wordpress_social_login]' ).'</div>';
		}
		else {
			if(!empty($vp_instance->settings['fb_app_id'])):
			$or = 1;?>
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="vp-fb-login-button vp-pointer"></div>
				</div>
			</div>
			<?php endif;?>
			<?php if(!empty($vp_instance->settings['google_api_key']) && !empty($vp_instance->settings['google_oauth_id'])):
			$or = 1;?>
			<div class="row">
				<div class="col-lg-12 text-center">   
					<div class="vp-gp-login-button vp-pointer"></div>
				</div>
			</div> 
			<?php endif;
		}?>
        
        <?php if($or):?>
        <div class="row">
            <div class="col-lg-5"><br/><hr/></div>
            <div class="col-lg-2 text-center" style="margin-top:10px">
                <?php _e( 'OR', 'viralpress' )?>
            </div>
            <div class="col-lg-5"><br/><hr/></div>
        </div>
        <?php endif;?>
        <div class="vp-clearfix"></div> 
        <?php
            wp_login_form(
                array(
                    'label_username' => __( 'Email or Username', 'viralpress' ),
                    'label_log_in' => __( 'Login', 'viralpress' ),
                    'redirect' => empty($attributes['redirect']) ? home_url( '/dashboard' ) : $attributes['redirect'],
                    'value_username' => empty($_GET['username']) ? '' : $_GET['username']
                )
            );
        ?>
         
        <a class="forgot-password" href="<?php echo home_url( '/password-lost' ); ?>">
            <?php _e( 'Forgot your password?', 'viralpress' ); ?>
        </a>
    </div>
    <?php
	do_action( 'viralpress_after_login_form' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_login_form', $html );
	echo $html;
}

function print_register_form( $attributes = array() )
{
	global $vp_instance;
	ob_start();
	do_action('viralpress_before_register_form');
	?>
    <div class="register-form-container" style="max-width:300px">
    	<?php if ( !empty($attributes['show_title']) ) : ?>
            <h2><?php _e( 'Register', 'viralpress' ); ?></h2>
        <?php endif;?>
		
        <?php if ( !empty($attributes['show_err']) ) :?> 
		<div class="login-error"></div>
		<?php endif;?>
        
		<?php 
        $or = 0;
		
		if( !empty( $vp_instance->settings['wsl_int'] ) ) {
			$or = 1;
			echo '<div style="text-align:center">'.do_shortcode( '[wordpress_social_login]' ).'</div>';
		}
		else {
			if(!empty($vp_instance->settings['fb_app_id'])):
			$or = 1;?>
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="vp-fb-login-button vp-pointer"></div>
				</div>
			</div>
			<?php endif;?>
			<?php if(!empty($vp_instance->settings['google_api_key']) && !empty($vp_instance->settings['google_oauth_id'])):
			$or = 1;?>
			<div class="row">
				<div class="col-lg-12 text-center">   
					<div class="vp-gp-login-button vp-pointer"></div>
				</div>
			</div> 
			<?php endif;
		}?>
        
        <?php if($or):?>
        <div class="row">
            <div class="col-lg-5"><br/><hr/></div>
            <div class="col-lg-2 text-center" style="margin-top:10px">
                <?php _e( 'OR', 'viralpress' )?>
            </div>
            <div class="col-lg-5"><br/><hr/></div>
        </div>
        <?php endif;?>
        <div class="vp-clearfix"></div>
        <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
        	<input type="hidden" id="regcaphidden" value="1" />
            <p class="form-row nocap">
                <label for="email"><?php _e( 'Email', 'viralpress' ); ?></label>
                <input type="text" name="email" id="email" class="vp-form-control" value="<?php @esc_html( $_REQUEST['email'] )?>">
            </p>
     
            <p class="form-row nocap">
                <label for="username"><?php _e( 'Username', 'viralpress' ); ?></label>
                <input type="text" name="username" id="username" class="vp-form-control" value="<?php @esc_html( $_REQUEST['username'] )?>">
            </p>
    
            <p class="form-row nocap">
                <small>
					<?php _e( 'Note: Your password will be generated automatically and sent to your email address.', 'viralpress' ); ?>
                </small>
            </p>
     		
            <?php if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_login'] && empty($vp_instance->temp_vars['cap_loaded'])  ) :
				$vp_instance->temp_vars['cap_loaded'] = 1;
			?>
     		<div class="form-row reg-recap recap-html" style="display:none">
                <label for="recap"><?php _e( 'Type the text you see', 'viralpress' ); ?></label>
                <?php echo recaptcha_get_html($vp_instance->settings['recap_key'], '', true);?>
            </div>
            <?php else:?>
            <div class="form-row reg-recap" style="display:none"></div>
     		<script>var load_reg_recap = 1;</script>
            <?php endif;?>
     
            <p class="signup-submit">
                <input type="submit" name="submit" class="register-button vp-form-control"
                 value="<?php _e( 'Register', 'viralpress' ); ?>"/>
            </p>
        </form>
	</div>
    <?php
	do_action( 'viralpress_after_register_form' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_register_form', $html );
	echo $html;
}

function print_password_lost_form( $attributes = array() )
{
	global $vp_instance;
	ob_start();
	do_action( 'viralpress_before_password_lost_form' );
	?>
    <div id="password-lost-form">
		<?php if ( @$attributes['show_title'] ) : ?>
            <h3><?php _e( 'Forgot Your Password?', 'viralpress' ); ?></h3>
        <?php endif; ?>
     
        <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        	<input type="hidden" id="lostcaphidden" value="1" />
            <p class="form-row nolostrecap">
                <label for="user_login"><?php _e( 'Email or Username', 'viralpress' ); ?></label>
                <input type="text" name="user_login" id="user_login" class="vp-form-control" value="<?php echo @esc_html($_REQUEST['user_login'])?>">
            </p>
     
            <p class="nolostrecap">
                <small>
                <?php
                    _e(
                        "Enter your email address and we'll send you a link you can use to pick a new password.",
                        'viralpress'
                    );
                ?>
                </small>
            </p>
 
     		<?php if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_login'] && empty($vp_instance->temp_vars['cap_loaded']) ) :
				$vp_instance->temp_vars['cap_loaded'] = 1;
			?>
     		<div class="form-row lost-recap recap-html" style="display:none">
                <label for="recap"><?php _e( 'Type the text you see', 'viralpress' ); ?></label>
                <?php echo recaptcha_get_html($vp_instance->settings['recap_key'], '', true);?>
            </div>
            <?php else:?>
            <div class="form-row lost-recap" style="display:none"></div>
     		<script>var load_lost_recap = 1;</script>
            <?php endif;?>
     
            <p class="lostpassword-submit">
                <input type="submit" name="submit" class="lostpassword-button vp-form-control"
                       value="<?php _e( 'Reset Password', 'viralpress' ); ?>"/>
            </p>
        </form>
    </div>
    <?php
	do_action( 'viralpress_after_password_lost_form' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_password_lost_form', $html );
	echo $html;
}

function print_password_reset_form( $attributes = array() )
{
	ob_start();
	do_action( 'viralpress__before_password_reset_form' );
	?>
    <div id="password-reset-form">
		<?php if ( @$attributes['show_title'] ) : ?>
            <h3><?php _e( 'Pick a New Password', 'viralpress' ); ?></h3>
        <?php endif; ?>
     
        <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
            <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( @$attributes['login'] ); ?>" autocomplete="off" />
            <input type="hidden" name="rp_key" value="<?php echo esc_attr( @$attributes['key'] ); ?>" />
            <p>
                <label for="pass1"><?php _e( 'New password', 'viralpress' ) ?></label>
                <input type="password" name="pass1" id="pass1" class="input vp-form-control" size="20" value="" autocomplete="off"/>
            </p>
            <p>
                <label for="pass2"><?php _e( 'Repeat new password', 'viralpress' ) ?></label>
                <input type="password" name="pass2" id="pass2" class="input vp-form-control" size="20" value="" autocomplete="off" />
            </p>
             
            <p class="description"><small><?php echo wp_get_password_hint(); ?></small></p>
             
            <p class="resetpass-submit">
                <input type="submit" name="submit" id="resetpass-button"
                       class="button vp-form-control" value="<?php _e( 'Reset Password', 'viralpress' ); ?>" />
            </p>
        </form>
    </div>
    <?php	
	do_action( 'viralpress_after_password_reset_form' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_password_reset_form', $html );
	echo $html;
}

function get_embed_code( $vid , $elem_id)
{
	$vid = explode( '|', $vid );
	$site = $vid[1];
	$param = $vid[2];
	$w = $vid[3];
	$h = $vid[4];
	
	$w = apply_filters( 'viralpress_embed_width', $w, $h, $vid[0], $site, $param );
	$h = apply_filters( 'viralpress_embed_height', $h, $w, $vid[0], $site, $param );
	
	//$w = 600;
	
	if( $vid[0] == 'video' || $vid[0] == 'audio' ){
		$code = '';
		
		if( $site == 'youtube' ){
			return '<iframe width="'. $w .'" height="'. $h .'" src="https://www.youtube.com/embed/'. $param .'" frameborder="0" allowfullscreen></iframe>';
		}
		else if( $site == 'ted' ){
			return '<iframe width="'. $w .'" height="'. $h .'" src="'. esc_url( $param, array( 'http', 'https' ) ) .'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		else if( $site == 'bbc' ){
			return '<iframe width="'. $w .'" height="'. $h .'" src="'. esc_url( $param, array( 'http', 'https' ) ) .'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		else if( $site == 'liveleak' ){
			return '<iframe width="'. $w .'" height="'. $h .'" src="'. esc_url( $param, array( 'http', 'https' ) ) .'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		else if( $site == 'ytaudio' ){
			//none
		}
		else if( $site == 'facebook' ){
			return '<div class="fb-video" data-href="'. esc_url( $param, array( 'http', 'https' ) ) .'" data-allowfullscreen="true" data-width="'. $w .'" data-height="'. $h .'"></div>';	
		}
		else if( $site == 'dailymotion' ){
			return '<iframe '.
					'src="//www.dailymotion.com/embed/video/'. $param .'" '.
					'width="'. $w .'" '.
					'height="'. $h .'" '.
					'frameborder="0" '.
					'allowfullscreen></iframe>';
		}
		else if( $site == 'vimeo' ){
			return '<iframe src="https://player.vimeo.com/video/'. $param .'" width="'. $w .'" height="'. $h .'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}
		else if( $site == 'vine' ){
			return '<iframe src="https://vine.co/v/'. $param .'/embed/simple" width="'. $w .'" height="'. $w .'" frameborder="0"></iframe><script src="https://platform.vine.co/static/scripts/embed.js"></script>';
		}
		else if( $site == 'soundcloud' ){
			return '<iframe width="'. $w .'" height="'. $h .'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/'. $param .'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>';
		}
		else if( $site == 'custom' ){
			return '<iframe width="'. $w .'" height="'. $h .'" src="'. esc_url( $param, array( 'http', 'https' ) ) .'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		
		return $code;	
	}
	else if( $vid[0] == 'pin' ) {
		if( $site == 'facebook' ){
			return '<div class="fb-post" data-href="'. esc_url( $param, array( 'http', 'https' ) ) .'" data-allowfullscreen="true" data-width="'. $w .'" data-height="'. $h .'"></div>';	
		}
		else if( $site == 'fbpage' ){
			return '<div class="fb-page" data-tabs="timeline,events,messages" data-href="'. esc_url( $param, array( 'http', 'https' ) ) .'" data-allowfullscreen="true" data-width="'. $w .'" data-height="'. $h .'"></div>';	
		}
		else if( $site == 'vine' ){
			return '<iframe src="https://vine.co/v/'. $param .'/embed/postcard" width="'. $w .'" height="'. $w .'" frameborder="0"></iframe>';
		}
		else if( $site == 'instagram' ){
			return '<iframe src="https://instagram.com/p/'. $param .'/embed/" width="'. $w .'" height="'. $h .'" frameborder="0"></iframe>';
		}
		else if( $site == 'twitter' ){
			return '<blockquote class="twitter-tweet" lang="en" width="100%"><a href="https://twitter.com/user/status/'.$param.'"></a></blockquote>';
		}
		else if( $site == 'twitter_profile' ){
			return '<a class="twitter-timeline" data-widget-id="'.$param.'"></a>';
		}
		else if( $site == 'pinterest_pin' ){
			return '<a data-pin-do="embedPin" data-pin-width="large" href="'. $param .'"></a>';
		}
		else if( $site == 'pinterest_board' ){
			return '<a data-pin-do="embedBoard" data-pin-board-width="'. $w .'" data-pin-scale-height="'. $h .'" data-pin-scale-width="80" href="'. esc_url( $param, array( 'http', 'https' ) ).'"></a>';
		}
		else if( $site == 'pinterest_profile' ){
			return '<a data-pin-do="embedUser" data-pin-board-width="'. $w .'" data-pin-scale-height="'. $h .'" data-pin-scale-width="80" href="'. esc_url( $param, array( 'http', 'https' ) ) .'"></a>';
		}
		else if( $site == 'gplus' ){
			return '<div class="g-post" style="width:'. $w .'px;height:'. $h .'px" data-href="'. esc_url( $param, array( 'http', 'https' ) ) .'"></div>';
		}
		else if( $site == 'custom' ){
			return '<iframe width="'. $w .'" height="'. $h .'" src="'. esc_url( $param, array( 'http', 'https' ) ) .'" frameborder="0"></iframe>';
		}
	}
}

function has_user_voted_list( $post_id, $uid )
{
	$voted = get_post_meta( $post_id, 'vp_user_list_votes_'.$uid );
	if( !empty($voted) ) $voted = $voted[0];
	else $voted = '';
	return $voted;	
}

function has_user_liked_list( $post_id, $uid )
{
	$voted = get_post_meta( $post_id, 'vp_user_list_likes_'.$uid );
	if( !empty($voted) ) $voted = $voted[0];
	else $voted = '';
	return $voted;	
}

function get_vp_post_status( $post_id )
{
	$status = get_post_status( $post_id );
	if( $status == 'publish' )$status = 'published';
	else if( $status == 'trash' )$status = 'trashed';
	
	return $status;
}

function has_user_voted( $post_id, $uid )
{
	$voted = get_post_meta( $post_id, 'vp_user_poll_votes_'.$uid );
	if( !empty($voted) ) $voted = $voted[0];
	else $voted = '';
	return $voted;	
}

function get_poll_results( $post_id )
{
	$votes = array();
	$q = get_post_meta( $post_id, 'vp_child_post_ids' );
	if( empty($q) ) return false;
	$childs = explode( ',', $q[0] );
	
	foreach( $childs as $child ) {
		$t = get_post_type( $child );
		if( $t != 'polls' ) {
			continue;	
		}
		$t = get_the_title( $child );
		$a = array();
		$v = get_post_meta( $child, 'vp_answer_entry' );	
		$tv = 0;
		if( !empty($v) ) {
			$v = explode( ',', $v[0] );
			foreach( $v as $vv ) {
				$tt = get_the_title( $vv );
				$vvv = get_post_meta( $post_id, 'vp_ans_poll_votes_'.$vv );
				$vvvv = @(int)$vvv[0];
				$link = '';
				$img = get_post_meta( $vv, 'vp_quiz_image_entry');
				if( !empty($img) ){
					$link = wp_get_attachment_image_src( $img[0], array( 32, 32 ) );
					$link = $link[0];
				}
				$a[] = array( 'title' => esc_js( $tt ), 'votes' => $vvvv, 'image' => esc_js( $link ) );	
				$tv += $vvvv;
			}
		}
		$votes[] = array( 'title' => esc_js( $t ), 'results' => $a, 'total_votes' => $tv );
	}
	return $votes;	
}

function get_post_search_form( $edit, $hide_actions = 0 )
{
	global $vp_instance;
	$loader = '<img src="'.$vp_instance->settings['IMG_URL'].'/spinner.gif"/>';
		
	$h = '';
	$h .= '<form class="profile_post_search_form">';
	
	if( $edit && !$hide_actions ) {
		$h .= '<div class="vp-pull-left">';
		$h .= '<select class="vp_post_select_action vp_post_select_sm" name="vp_mass_post_action">';
		$h .= '<option value="">'.__( 'Select action', 'viralpress' ).'</option>';
		$h .= '<option value="vp_mass_draft_post">'.__( 'Draft', 'viralpress' ).'</option>';
		$h .= '<option value="vp_mass_publish_post">'.__( 'Publish', 'viralpress' ).'</option>';
		$h .= '<option value="vp_mass_delete_post">'.__( 'Delete', 'viralpress' ).'</option>';
		$h .= '</select>&nbsp;&nbsp;';
		$h .= '<button class="btn btn-xs btn-danger vp_mass_post_action_btn" style="margin-top:-3px;display:none">';
		$h .= '<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;'.__( 'Apply', 'viralpress' );
		$h .= '</button>';
		$h .= '<span class="vp_mass_post_loader">'.$loader.'</span>';
		$h .= '</div>';
	}
	
	$h .= '<div class="'.( !$edit || $hide_actions ? ' vp-pull-left ' : 'vp-pull-right' ).' vp-search-form"  style="width:'.( $hide_actions ? 100 : 65).'%">';
	$h .= '<input type="text" name="query" class="vp_post_select_sm" placeholder="'.__( 'Title', 'viralpress' ).'">&nbsp;';
	//$h .= '<div class="col-lg-2">';
	$h .=	wp_dropdown_categories( array( 'orderby' => 'NAME', 'show_option_all' => __( 'Post category', 'viralpress' ), 'hide_empty' => 1, 'class' => 'vp_post_select_sm', 'echo' => 0 ) );
	//$h .= '</div>';
	//$h .= '<div class="col-lg-2">';
	$h .= '<select name="tags" id="tags" class="vp_post_select_sm">';
	$h .= '<option value="">'.__( 'Post type', 'viralpress' ).'</option>';
	foreach( $vp_instance->vp_post_types as $post_type ) {
		$h .= '<option value="'.$post_type['type'].'">'.($post_type['name'] == 'Pins' ? 'Embeds' : $post_type['name']).'</option>';
	}
	$h .= '</select>&nbsp;';
	//$h .= '</div>';
	if( $edit ){
		$h .= '<select name="status" class="vp_post_select_sm">
				<option value="">'.__( 'Post status', 'viralpress' ).'</option>
				<option value="publish">'.__( 'Published', 'viralpress' ).'</option>
				<option value="draft">'.__( 'Draft', 'viralpress' ).'</option>
				<option value="pending">'.__( 'Pending', 'viralpress' ).'</option>
				<option value="future">'.__( 'Future', 'viralpress' ).'</option>
				<option value="private">'.__( 'Private', 'viralpress' ).'</option>
			</select>';
	}
	$h .= '<button class="btn btn-default profile_post_search_i"><i class="glyphicon glyphicon-search vp-pointer"></button></i>';
	$h .= '</div>';	
	$h .= '<input type="hidden" name="show_p" value="1">';
	$h .= '</form>';
	return apply_filters( 'viralpress_posts_search_form', $h, $edit, $hide_actions );
}

function display_tabular_posts( $wp_query, $paged, $uid, $edit )
{
	global $vp_instance;
	
	$vp_instance->load_post_types();
	$loader = '<img src="'.$vp_instance->settings['IMG_URL'].'/spinner.gif"/>';
	
	ob_start();
	do_action( 'viralpress_before_tabular_posts' );
	
	echo '<span class="label label-info">'.$wp_query->found_posts.'</span>&nbsp;&nbsp;';
	echo '<div class="vp-clearfix"></div><div class="vp-clearfix"></div><div class="vp-clearfix"></div>';
	
	echo get_post_search_form( $edit );
	
	echo '<div class="vp-clearfix"></div>';
	
	if( $wp_query->have_posts() ):
		echo '<div class="vp-grid">';
		/*echo '<table cellpadding="5" cellspacing="5" class="vp_dash_tab">';
		echo '<tr>
				'.( $edit ? '<th width="5%"><input type="checkbox" class="vp_post_sel_all"/></th>' : '' ).'
				<th width="55%">'.__( 'Title', 'viralpress' ).'</th>
				<th width="10%">'.__( 'Type', 'viralpress' ).'</th>
				'.( $edit ? '<th width="10%">'.__( 'Status', 'viralpress' ).'</th>' : '' ).'
				<th width="10%">'.__( 'Comments', 'viralpress' ).'</th>
				<th width="10%">'.__( 'Date', 'viralpress' ).'</th>
			 </tr>';*/
			while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$pid = get_the_ID();
				$t = get_post_meta( $pid, 'vp_post_type' );
				if( !empty($t[0]) ){
					if( $t[0] == 'news' )$t = '<span class="label label-info">'.__( 'News', 'viralpress' ).'</span>';
					else if( $t[0] == 'lists' )$t = '<span class="label label-success">'.__( 'Lists', 'viralpress' ).'</span>';
					else if( $t[0] == 'videos' )$t = '<span class="label label-warning">'.__( 'Videos', 'viralpress' ).'</span>';
					else if( $t[0] == 'pins' )$t = '<span class="label label-primary">'.__( 'Embeds', 'viralpress' ).'</span>';
					else if( $t[0] == 'quiz' )$t = '<span class="label label-danger">'.__( 'Quiz', 'viralpress' ).'</span>';
					else if( $t[0] == 'polls' )$t = '<span class="label label-default">'.__( 'Polls', 'viralpress' ).'</span>';
					else if( $t[0] == 'images' )$t = '<span class="label label-default">'.__( 'Images', 'viralpress' ).'</span>';	
					else if( $t[0] == 'meme' )$t = '<span class="label label-default">'.__( 'Meme', 'viralpress' ).'</span>';
					else $t = '<span class="label label-default">'.$t[0].'</span>';
				}
				else $t = '<span class="label label-default">'.__( 'Default', 'viralpress' ).'</span>';
				
				$s = get_post_status();
				$excerpt = get_the_excerpt();
				if( !empty( $excerpt ) ) $excerpt = '<br/><small style="color:gray">'.$excerpt.'</small>';
				$thumb = get_the_post_thumbnail( null, array(300, 300) );
				$p = get_the_permalink();
				
				$h = '<div class="vp-grid-item vp_profile_post_grid p_'.$pid.'" data-rel="'.$pid.'">
						<div class="vp-gd-thumb">
							<a href="'.$p.'">'.( $thumb ? $thumb : '' ).'</a>
						</div>
						<div class="vp-gd-summary">
							'.( $edit ? '<input type="checkbox" class="vp_post_sel" value="'.$pid.'"/>' : '' ).'
							<a href="'.$p.'">
								'.get_the_title().'
								'.$excerpt.'
							</a>
							<br/>
							<div style="font-size:14px">
								<div class="vp-pull-left" style="margin-right:5px">'.$t.'</div>
								<div class="post_status vp-pull-left">
								'.( $edit ? '<span class="label label-'.( $s == 'publish' ? 'success' : 'danger' ).'">'.$s.'</span>' : '' ).'
								</div>
							</div>
						</div>
						<div class="vp-gd-actions" style="font-size:14px">
							'.( current_user_can( 'edit_post', $pid ) ? 
							'<br/><br/>
							<a href="'.get_edit_post_link().'">
								<i class="glyphicon glyphicon-edit vp-pointer"></i>&nbsp;&nbsp;'.__( 'Edit', 'viralpress' ).'
							</a>&nbsp;&nbsp;
							<span class="vp_single_action_'.$pid.'">
								<a href="javascript:void(0)" class="vp_single_post_publish">
									<i class="glyphicon glyphicon-ok vp-pointer"></i>&nbsp;&nbsp;'.__( 'Publish', 'viralpress' ).'
								</a>&nbsp;&nbsp;
								<a href="javascript:void(0)" class="vp_single_post_draft">
									<i class="glyphicon glyphicon-pencil vp-pointer"></i>&nbsp;&nbsp;'.__( 'Draft', 'viralpress' ).'
								</a>&nbsp;&nbsp;
								<a href="javascript:void(0)" class="vp_single_post_delete">
									<i class="glyphicon glyphicon-trash vp-pointer"></i>&nbsp;&nbsp;'.__( 'Delete', 'viralpress' ).'
								</a>
							</span>
							<span class="vp_single_loader_'.$pid.'" style="display:none">
								'.$loader.'
							</span>' : '' ).'
						</div>
						<div style="font-size:14px">
						'.get_the_time( get_option( 'date_format' ) ).'
						</div>
					 </div>';
					 
				echo apply_filters( 'viralpress_tabular_posts_loop', $h, $pid );
					 
			endwhile;
			
		//echo '</table>';
		echo '</div><div class="vp-clearfix-lg"></div>';
		
		$GLOBALS['wp_query']->max_num_pages = $wp_query->max_num_pages;
		
		echo '<div class="vp_dash_pagina">';
		if( function_exists( 'the_posts_pagination' ) ) 
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => __( '« Previous Page', 'viralpress' ),
				'next_text' => __( 'Next Page »', 'viralpress' ),
				'add_args' => array( 'show_p' => 1 )
			) );
		else vp_numeric_posts_nav();
		echo '</div>';
	else:
		echo '<div style="margin-top:80px" class="alert alert-info">';
		_e( 'No post found.', 'viralpress' );
		echo '</div>';
	endif;
	
	do_action( 'viralpress_after_tabular_posts' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_tabular_posts', $html );
	echo $html;
	
	wp_reset_query();
}

function vp_numeric_posts_nav( $paged_var = null ) {

	global $wp_query;
	if( !$paged_var )$paged_var = 'paged';

	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( $paged_var ) ? absint( get_query_var( $paged_var ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="nav-links">' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<span class="page-numbers previous">%s</span>' . "\n", get_previous_posts_link() );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="page-numbers active"' : ' class="page-numbers"';

		printf( '<a%s href="%s">%s</a>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<span class="page-numbers">...</span>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="page-numbers active"' : ' class="page-numbers"';
		printf( '<a%s href="%s">%s</a>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<span class="page-numbers">...</span>' . "\n";

		$class = $paged == $max ? ' class="page-numbers active"' : ' class="page-numbers"';
		printf( '<a%s href="%s">%s</a>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<span class="page-numbers next">%s</span>' . "\n", get_next_posts_link() );

	echo '</div>' . "\n";

}

function vp_title_filter( $where, &$wp_query )
{
	global $wpdb;
	$q = @$wp_query->get( 'search_prod_title' );
	if( !empty( $q ) ) {
		if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
		}
	}
	return $where;
}

function vp_return_404() {
	status_header(404);
	nocache_headers();
	include( get_404_template() );
	exit;
}

function vp_print_comments( $uid, $type, $show_pagina = 1)
{
	$page = (int) (!isset($_REQUEST["comment_page"]) ? 1 : $_REQUEST["comment_page"]);
	$limit = 10;
	$offset = ($page * $limit) - $limit;
	
	if( $type == 'on_post' ) {
		$param = array(
			'status' => 'approve',
			'offset' => $offset,
			'number' => $limit,
			'post_author' => $uid
		);
		
		$total_comments = get_comments( array( 'status' => 'approve', 'count' => 1, 'post_author' => $uid) );
		echo '<h4>'.__( 'Recent comments on your posts', 'viralpress' ).'&nbsp;&nbsp;<span class="label label-success">'.$total_comments.'</span></h4>';
	}
	else{
		$param = array(
			'status' => 'approve',
			'offset' => $offset,
			'number' => $limit,
			'author__in' => array( $uid ),
			'orderby' => 'date'
		);
		
		$total_comments = get_comments( array( 'status' => 'approve', 'count' => 1, 'author__in' => array( $uid ) ) );
		echo '<h4>'.__( 'Recent comments by you', 'viralpress' ).'&nbsp;&nbsp;<span class="label label-success">'.$total_comments.'</span></h4>';
			
	}
	
	$pages = ceil($total_comments / $limit);
	$comments = get_comments($param);
	
	if( $type == 'by_me' ) {
		$tt = array();
		$carray = array();	
		foreach( $comments as $c )$carray[] = $c->comment_ID;
		foreach( $comments as $c ) {
			$cc = get_comments( array( 'status' => 'approve', 'parent' => $c->comment_ID, 'number' => 3, 'orderby' => 'date') );
			foreach( $cc as $ccc){
				if( !in_array( $ccc->comment_ID, $carray ) ) {
					$carray[] = $ccc->comment_ID;
					$comments[] = $ccc;	
				}
			}
			
			if( $c->comment_parent ) $tt[] = $c->comment_parent;
		}
		
		if( !empty( $tt ) ) {
			$cc = get_comments( array( 'status' => 'approve', 'comment__in' => $tt, 'number' => 3, 'orderby' => 'date') );
			foreach( $cc as $ccc){
				if( !in_array( $ccc->comment_ID, $carray ) ) {
					$carray[] = $ccc->comment_ID;
					$comments[] = $ccc;	
				}
			}
		}
	}
	
	do_action( 'viralpress_pre_comments' );
	ob_start();
	?>
	<div id="comments" class="vp-comments comments-area">
		<?php wp_list_comments( array( 'style' => 'ol', 'max_depth' => 3 ), $comments ); ?>
	</div>
	<?php
		if( $show_pagina ) {
			$args = array(
				'base'         => '%_%',
				'format'       => '?comment_page=%#%',
				'total'        => $pages,
				'current'      => $page,
				'show_all'     => False,
				'end_size'     => 1,
				'mid_size'     => 2,
				'prev_next'    => True,
				'prev_text'    => __('&laquo; Previous'),
				'next_text'    => __('Next &raquo;'),
				'type'         => 'plain'
			);
			
			echo '<div class="vp-clearfix-lg"></div>';
			echo '<div class="vp_dash_pagina">';
			echo paginate_links( $args );
			echo '</div>';
		}
		
	do_action( 'viralpress_after_comments' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_comments', $html );
	echo $html;	
}

function vp_format_dash_comments( $text, $comment, $args )
{
	$post_id = $comment->comment_post_ID;
	$url = esc_url( get_permalink( $post_id ) );
	$url = __( 'Posted on ', 'viralpress' ). '<a href="'.$url.'">'.get_the_title( $post_id ).'</a>';
	return apply_filters( 'viralpress_format_dash_comments', $text.'<br/><br/><small>'.$url.'</small>' );
}

function get_user_social_profiles( $uid )
{
	$fb_url = get_user_meta( $uid, 'vp_fb_url');
	if(!empty($fb_url)){
		$fb_url = esc_url( $fb_url[0], array( 'http', 'https' ) );
	}
	else $fb_url = '';
	
	$tw_url = get_user_meta( $uid, 'vp_tw_url');
	if(!empty($tw_url)){
		$tw_url = esc_url( $tw_url[0], array( 'http', 'https' ) );
	}
	else $tw_url = '';
	
	$gp_url = get_user_meta( $uid, 'vp_gp_url');
	if(!empty($gp_url)){
		$gp_url = esc_url( $gp_url[0], array( 'http', 'https' ) );
	}
	else $gp_url = '';
	
	return array( $fb_url, $tw_url, $gp_url );
}

function vp_format_content( $content )
{
	$content = vp_custom_nl2br( $content );
	return apply_filters( 'viralpress_format_content', $content );
}

function vp_custom_nl2br( $string ) {
	
   	$string = nl2br($string); 
	$string = str_replace("\n", "", $string);
    $string = str_replace("\r", "", $string);

	if(preg_match_all('/\<table\>(.*?)\<\/table\>/', $string, $match)){
        foreach($match as $a){
            foreach($a as $b){
            	$string = str_replace('<table>'.$b.'</table>', "<table>".str_replace("<br />", '', $b)."</table>", $string);
            }
        }
    }

    // Arround <ul></ul> tags
    $string = str_replace("<br /><br /><ul>", '<br /><ul>', $string);
    $string = str_replace("</ul><br /><br />", '</ul><br />', $string);
    
	// Inside <ul> </ul> tags
    $string = str_replace("<ul><br />", '<ul>', $string);
    $string = str_replace("<br /></ul>", '</ul>', $string);

    // Arround <ol></ol> tags
    $string = str_replace("<br /><br /><ol>", '<br /><ol>', $string);
    $string = str_replace("</ol><br /><br />", '</ol><br />', $string);
    // Inside <ol> </ol> tags
    $string = str_replace("<ol><br />", '<ol>', $string);
    $string = str_replace("<br /></ol>", '</ol>', $string);

    // Arround <li></li> tags
    $string = str_replace("<br /><li>", '<li>', $string);
    $string = str_replace("</li><br />", '</li>', $string);
	
	// Arround <p></p> tags
    $string = str_replace("<br /><p>", '<p>', $string);
    $string = str_replace("</p><br />", '</p>', $string);

    return apply_filters( 'viralpress_strip_extra_lines', $string );
}

function vp_get_attachment( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	return array(
		'caption' => @$attachment->post_excerpt,
		'description' => @$attachment->post_content,
		'src' => @$attachment->guid,
		'title' => @$attachment->post_title
	);
}

function validate_vp_ajax( $response )
{
	if ( empty( $_REQUEST['_nonce'] ) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp-ajax-action-'.get_current_user_id() ) ) {
		$response['error'] = __( 'Failed to validate request. Please try again' , 'viralpress' );
		vp_output( $response );
	}
}

function vp_activate_deactivate( $status, $vp_instance )
{
	$license_host = $vp_instance->version_check_url;
	
	global $user_email;
	
	$data = wp_remote_post( $license_host, array( 'sslverify' => false, 'body' => array( 'plugin_status_change' => 1, 'plugin_activation_status' => $status, 'url' => get_bloginfo( 'url' ), 'email' => $user_email, 'license' => vp_check_license() ) ) );
			
	if( is_wp_error( $data ) ) return false;
	
	if( $data['response']['code'] != 200 ){
		return false;
	}
	
	$body = json_decode( $data['body'], true );
	
	if( $body['success'] == 1 ) return true;
	else {
		return $body['error'];
	}
}

function vp_activate_license( $username, $api, $purchase_code )
{
	global $vp_instance;
	$license_host = $vp_instance->version_check_url;
	
	global $user_email;
	
	$data = wp_remote_post( $license_host, array( 'sslverify' => false, 'body' => array( 'activate' => 1, 'username' => $username, 'api' => $api, 'key' => $purchase_code, 'url' => get_bloginfo( 'url' ), 'email' => $user_email ) ) );
	
	if( is_wp_error( $data ) ) return false;
	
	if( $data['response']['code'] != 200 ){
		return false;
	}
	
	$body = json_decode( $data['body'], true );
	
	if( $body['success'] == 1 ) return true;
	else {
		return $body['error'];
	}
}

function vp_check_license()
{
	return get_option( 'vp-license', -1 );
}

function prepare_gallery( $child_post ) 
{
	$code = get_post_meta( $child_post->ID, 'vp_gallery_shortcode' );
	$code = end($code);
	return apply_filters( 'viralpress_gallery_output', do_shortcode( $code ), $child_post, $code );
}

function prepare_playlist( $child_post ) 
{
	$code = get_post_meta( $child_post->ID, 'vp_playlist_shortcode' );
	$code = end($code);
	return apply_filters( 'viralpress_playlist_output', do_shortcode( $code ), $child_post, $code );
}

function display_list( $post, $child_post, $content, $show_numbers, $order, $quiz = 0 )
{
	do_action( 'viralpress_before_list_display', $post, $child_post, $content, $show_numbers, $order, $quiz );
	
	$pbody = '';
	$list_style = '';
	$list_disp = '';
	
	if( $show_numbers ){
		$list_style = vp_get_list_style( $post->ID );
		if( $list_style == 'legend' )$pbody .= '<legend>'.$order.'</legend>';
		$list_disp = vp_get_list_display( $post->ID );
	}
	
	$source_url = '';
	$ss = get_post_meta( $child_post->ID, 'vp_source_url' );
	if( !empty( $ss[0]) ) $source_url = $ss[0];
	
	if( preg_match( '/two_/i', $list_disp ) )$pbody .= '<div class="vp-clearfix"></div><div class="row">';
	
	if( $list_disp == 'two_alt' ) $list_disp = $order % 2 == 1 ? 'two_l' : 'two_r';
	if( empty( $content ) ) $list_disp = 'one';
	
	if( $list_disp == 'two_l' ) {
		
		$content = preg_replace("/width=\"([0-9]+)\"/", "", $content);
		$content = preg_replace("/height=\"([0-9]+)\"/", "", $content);
		
		$tt = '<div class="col-lg-5">';
		$tt .= $content;
		if( $source_url )$tt .= '<div class="vp-media-caption">'.pretty_source( $source_url ).'</div>';
		$tt .= '</div>';
		$tt .= '<div class="col-lg-7">';
		$tt .= '<h3 '.( $quiz ? 'quiz_title' : '' ).'>';
		$tt .= ( $list_style == 'inline' ? 
			'<span class="inline_list">'.$order.'</span> &nbsp;' : ( $list_style == 'boxed' ? '<span class="box_list">'.$order.'</span>' : '' ));
		if( !empty($child_post->post_title) && $child_post->post_title != 'NO_TITLE' )
			$tt .= $child_post->post_title;
		$tt .= '</h3>';
		if( !empty($child_post->post_content) )
		$tt .= '<div class="entry">'.vp_format_content( $child_post->post_content ).'</div>';
		$tt .= '</div>';
		
		$pbody .= apply_filters( 'viralpress_two_col_left_picture_html', $tt, $post, $child_post, $content, $show_numbers, $order, $quiz = 0 );	
	}
	else if( $list_disp == 'two_r' ) {
		
		$content = preg_replace("/width=\"([0-9]+)\"/", "", $content);
		$content = preg_replace("/height=\"([0-9]+)\"/", "", $content);
		
		$tt = '<div class="col-lg-7">';
		$tt .= '<h3 '.( $quiz ? 'quiz_title' : '' ).'>';
		$tt .= ( $list_style == 'inline' ? 
			'<span class="inline_list">'.$order.'.</span> &nbsp;' : ( $list_style == 'boxed' ? '<span class="box_list">'.$order.'</span>' : '' ));
		if( !empty($child_post->post_title) && $child_post->post_title != 'NO_TITLE' )
			$tt .= $child_post->post_title;
		$tt .= '</h3>';
		if( !empty($child_post->post_content) )
		$tt .= '<div class="entry">'.vp_format_content( $child_post->post_content ).'</div>';
		$tt .= '</div>';
		$tt .= '<div class="col-lg-5">';
		$tt .= $content;
		if( $source_url )$tt .= '<div class="vp-media-caption">'.pretty_source( $source_url ).'</div>';
		$tt .= '</div>';
		
		$pbody .= apply_filters( 'viralpress_two_col_right_picture_html', $tt, $post, $child_post, $content, $show_numbers, $order, $quiz = 0 );
	}
	else{	
		$tt = '<h3 '.( $quiz ? 'quiz_title' : '' ).'>';
		$tt .= ( $list_style == 'inline' ? 
			'<span class="inline_list">'.$order.'.</span> &nbsp;' : ( $list_style == 'boxed' ? '<span class="box_list">'.$order.'</span>' : '' ));
		if( !empty($child_post->post_title) && $child_post->post_title != 'NO_TITLE' )
			$tt .= $child_post->post_title;
		$tt .= '</h3>';
		$tt .= $content;
		if( !empty( $content ) && $source_url )$tt .= '<div class="vp-media-caption">'.pretty_source( $source_url ).'</div>';
		if( !empty($child_post->post_content) )
		$tt .= '<div class="vp-clearfix"></div><div class="entry">'.vp_format_content( $child_post->post_content ).'</div>';
		if( empty( $content ) && $source_url )$tt .= '<div class="vp-media-caption">'.pretty_source( $source_url ).'</div>';
		
		$pbody .= apply_filters( 'viralpress_normal_display_list_html', $tt, $post, $child_post, $content, $show_numbers, $order, $quiz = 0 );
	}
	
	if( preg_match( '/two_/i', $list_disp ) )$pbody .= '</div>';
	
	do_action( 'viralpress_after_list_display', $post, $child_post, $content, $show_numbers, $order, $quiz );
	
	$pbody = apply_filters( 'viralpress_list_display_html', $pbody, $post, $child_post, $content, $show_numbers, $order, $quiz  );

	return array( $child_post->post_title, $child_post->post_content, $pbody );
}

function pretty_source( $url )
{
	$uu = parse_url( $url );
	$h = @$uu['host'];
	if( empty( $h ) )return false;	
	
	$h = explode( '.', $h );
	if( count( $h ) > 2 )array_shift( $h );
	return apply_filters ( 'viralpress_list_source', '<a href="'.$url.'" target="_blank">'.__( 'via', 'viralpress' ).' '.implode('.', $h ).'</a>', $url );
}

function vp_get_list_style( $post_id )
{
	$style = 'legend';
	$list_style = get_post_meta( $post_id, 'vp_list_style' );
	if( !empty( $list_style[0]) ){
		$style = $list_style[0];	
	}
	
	$style = verify_list_style( $style );
	return $style;
}


function verify_list_style( $style )
{
	if( !in_array( $style, array( 'legend', 'boxed', 'inline' ) ) ) $style = 'legend';
	return $style;
}

function vp_get_list_display( $post_id )
{
	$disp = 'one';
	$list_disp = get_post_meta( $post_id, 'vp_list_display' );
	if( !empty( $list_disp[0]) ){
		$disp = $list_disp[0];	
	}
	
	$disp = verify_list_display( $disp );
	return $disp;
}


function verify_list_display( $disp )
{
	if( !in_array( $disp, array( 'one', 'two_l', 'two_r', 'two_alt' ) ) ) $disp = 'one';
	return $disp;
}

function vp_print_emoji_reactions( $post )
{
	global $vp_instance;
	
	if( $post instanceof WP_Post ){}
	else {
		$post = get_post( @$post['post_id'] );	
	}
	
	if( $post instanceof WP_Post ){}
	else {
		return false;	
	}
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$uid = '';	
		}	
	}
	
	$tt = '';
	
	if( $vp_instance->settings['show_reactions'] ) {
		do_action( 'viralpress_before_emo', $post );
		
		$tt .= '<div class="vp_emoji">';
		$tt .= '<h3>'.__( 'Your reaction', 'viralpress' ).'</h3>';
		$tt .= '<div class="row emoji_row emoji_row_nosel" data-rel="'.$post->ID.'">';
		$tt .= '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" rel="NICE">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'NICE', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/263a.png" style="width:40px"/>
			 </div>';
		$tt .= '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" rel="SAD">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'SAD', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f641.png" style="width:40px"/>
			 </div>';
		$tt .= '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" rel="FUNNY">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'FUNNY', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v1/72x72/1f602.png" style="width:40px"/>
			 </div>';
		$tt .= '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" rel="OMG">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'OMG', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f631.png" style="width:40px"/>
			 </div>';
		$tt .= '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" rel="WTF">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'WTF', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v1/72x72/1f620.png" style="width:40px"/>
			 </div>';	
		$tt .= '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" rel="WOW">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'WOW', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f60d.png" style="width:40px"/>
			 </div>';
		/*
		$tt .= '<div class="col-lg-1 col-md-3 col-sm-4 col-xs-4" rel="EW">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'EW', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v1/72x72/1f623.png" style="width:40px"/>
			 </div>';	 
		$tt .= '<div class="col-lg-1 col-md-3 col-sm-4 col-xs-4" rel="LOL">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'LOL', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f61c.png" style="width:40px"/>
			 </div>';	
		$tt .= '<div class="col-lg-1 col-md-3 col-sm-4 col-xs-4" rel="CRYING">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'CRYING', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f62d.png" style="width:40px"/>
			 </div>'; 
		$tt .= '<div class="col-lg-1 col-md-3 col-sm-4 col-xs-4" rel="COOL">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'COOL', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f60e.png" style="width:40px"/>
			 </div>';
		$tt .= '<div class="col-lg-1 col-md-3 col-sm-4 col-xs-4" rel="ISEE">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'I SEE', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f914.png" style="width:40px"/>
			 </div>';
		$tt .= '<div class="col-lg-1 col-md-3 col-sm-4 col-xs-4" rel="ME2">
				<div class="react_votes"><div class="vbar"></div><span class="vbar_num"></span></div>
				'.__( 'ME 2', 'viralpress' ).'<br/>
				<img src="https://abs.twimg.com/emoji/v2/72x72/1f64b-1f3fb.png" style="width:40px"/>
			 </div>';
		*/
		
		$tt .= '</div>';
		$tt .= '<div class="vp-clearfix"></div><div class="vp-clearfix"></div>';
		$tt .= '</div>';
		
		do_action( 'viralpress_after_emo', $post );
	
	}
	
	if( !empty( $vp_instance->settings['react_gifs'] ) && $vp_instance->settings['show_gif_reactions'] ) {
		
		do_action( 'viralpress_before_react_gif' );
		
		$tt .= '<h3>'.__( 'React with gif', 'viralpress' ).'</h3>';
		$tt .= '<div class="row regif_row_parent" data-rel="'.$post->ID.'">';
		$tt .= '<div class="col-lg-12 regif_row" style="display:none">';
		
		if( !wp_script_is( 'jq-bxslider', 'done' ) ) {
			wp_register_script( 'jq-bxslider', $vp_instance->settings['JS_URL'].'/jquery.bxslider.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'jq-bxslider' );	
			
			wp_register_style( 'jq-bxslider-css', $vp_instance->settings['CSS_URL'].'/jquery.bxslider.css' , array() );
			wp_enqueue_style( 'jq-bxslider-css' );
		}
		
		$gifs = json_decode( $vp_instance->settings['react_gifs'], true );
		foreach( $gifs as $j => $gif ) {
			if( $j == 1 && $vp_instance->settings['show_gif_reactions_upload'] ) {
				$tt .= apply_filters( 'vp_add_custom_gif_html', '<div class="slide vp_gif_add_new"><img style="height:150px" src="'.$vp_instance->settings['IMG_URL'].'/add_new.png" class="vp-pointer add_new_react" title="'.__( 'Add your image', 'viralpress' ).'"></div>' );	
			}
			$tt .= '<div class="slide"><img style="height:150px" src="'.$gif['static'].'" class="vp-pointer react_gif_img" title="'.$gif['caption'].'" data-static-url="'.$gif['static'].'" data-gif-url="'.$gif['url'].'"></div>';
		}
		
		$tt .=	 '</div>';
		$tt .= '</div>';
		
		if( is_user_logged_in() ) {
			
			if ( ! did_action( 'wp_enqueue_media' ) )wp_enqueue_media();
					
			$tt .= '<div class="vp_react_gif_wrap" style="display:none">
					<ul id="vp-tab2">
						<li class="active custom_gif">
							<h2>'.__( 'Submit your own image or gif reaction', 'viralpress' ).'</h2>
							<label>'.__( 'Image URL', 'viralpress' ).'</label>
							<input type="text" class="vp-form-control vp_react_gif_url"/>
							<div class="vp-clearfix"></div>
							<label>'.__( 'OR', 'viralpress' ).'</label><br/>
							<button class="btn btn-info comm-react edit-profile-images"><i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;'.__( 'Upload your image', 'viralpress' ).'</button>
							<div class="vp-clearfix"></div>
							<img style="max-width: 250px; max-height:250px" class="gif_react_url"/>
							<div class="vp-clearfix"></div>
							<label>'.__( 'Comment (Optional)', 'viralpress' ).'</label>
							<textarea class="vp-form-control vp_react_gif_comm"></textarea>
							<div class="vp-clearfix"></div>
							<div class="vp-clearfix"></div>
							<button class="btn btn-info react_with_gif custom_gif"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;'.__( 'Post this reaction!', 'viralpress' ).'</button>
						</li>
						<li class="pre_gif">
							<h2>'.__( 'Are you sure to react with this gif?', 'viralpress' ).'</h2>
							<img style="max-width: 250px; max-height:250px" class="gif_react_url"/>
							<div class="vp-clearfix"></div>
							<div class="vp-clearfix"></div>
							<label>'.__( 'Comment (Optional)', 'viralpress' ).'</label>
							<textarea class="vp-form-control vp_react_gif_comm"></textarea>
							<div class="vp-clearfix"></div>
							<div class="vp-clearfix"></div>
							<button class="btn btn-info react_with_gif">'.__( 'Post this reaction!', 'viralpress' ).'</button>
						</li>
					  </ul>
					  <input type="hidden" id="react_with_gif_post_id">
				</div>
				<div class="vp-clearfix"></div>
				<div class="alert alert-info vp_react_gif_feed" style="display:none">'.__( 'Submitting. Please wait...', 'viralpress' ).'</div>
				';
			
			$tt .= '<div class="vp-clearfix"></div><div class="vp-clearfix"></div>';	
			
			do_action( 'viralpress_after_react_gif', $post );
		}
	}
	
	if( $vp_instance->settings['show_reactions'] ) {
	
		global $wpdb;
		$table = $wpdb->prefix.'postmeta';
		$q = $wpdb->get_results("SELECT * FROM $table WHERE post_id = '$post->ID' AND meta_key LIKE 'vp_post_react_%'", ARRAY_A);
		
		$s = '';
		$dd = array();
		$sum = 0;
		foreach($q as $row){
			$k = str_replace( 'vp_post_react_', '', $row['meta_key'] );
			$v = (int)$row['meta_value'];
			$dd[$k] = $v;
			$sum += $v;
		}
		
		foreach( $dd as $k => $v ) {
			$s .= '$("div[rel=\''.$k.'\']").find(".vbar_num").html("'.$v.'");$("div[rel=\''.$k.'\']").find(".vbar").css("height", "'.( @round($v/$sum, 2) * 100 ).'%");';	
		}
		
		$type = '';
		if( $sum && $uid ) {
			$k = 'vp_user_react_'.$uid;
			$q = get_post_meta( $post->ID, $k );
			if( !empty( $q[0] ) ) {
				$type = $q[0];
				//$('.emoji_row_nosel').removeClass('emoji_row_nosel');
				$s .= "$('div[rel=\"".$type."\"]').addClass('emoji_row_sel');";	
			}	
		}
		
		$tt .= '<script>jQuery(document).ready(function($){$(".react_votes").find(".vbar_num").append("0");'.$s.'});</script>';
	}
	
	return apply_filters( 'viralpress_emoji_html', $tt, $post, $uid, $type);
}

function vp_print_ad( $ad_id ) 
{
	global $wpdb;
	$post_table = $wpdb->prefix.'posts';
	
	$ad_margin = get_post_meta( $ad_id, 'vp_ad_margin' );	
	$ad_margin = @(int)$ad_margin[0];
	
	$ad_align = get_post_meta( $ad_id, 'vp_ad_align' );	
	$ad_align = @$ad_align[0];	
	
	$q = $wpdb->get_results("SELECT post_content FROM $post_table WHERE ID = '$ad_id' LIMIT 1", ARRAY_A);
	$ads = @$q[0]['post_content'];
	
	$html = '';
	
	if( !empty( $ads ) ) {
		
		do_action( 'viralpress_before_ad' );
		
		$html .= '<div class="vp-clearfix"></div><div class="vp-clearfix"></div><div style="margin:'.$ad_margin.'; align:'.$ad_align.' !important; text-align:'.$ad_align.' !important">';
		$html .= do_shortcode( $ads );
		$html .= '</div>';
		
		do_action( 'viralpress_after_ad' );
		
		$html = apply_filters( 'viralpress_ad_html', $html, $ad_id, $ad_margin, $ad_align, $ads );
		return $html;
	}
}

/***
 * buddypress integration
 */
function vp_bp_add_activity( $ID )
{
	global $vp_instance;
	if( empty( $vp_instance->settings['vp_bp'] ) ) return;
	
	if ( function_exists('bp_activity_add') ) {
		
		if ( $ID instanceof WP_Post ) {
			$post = $ID;
			$ID = $post->ID;
		}
		else $post = get_post( $ID );
		
		if( $post->post_type != 'post' )return;
		
		$bp_post_id = get_post_meta( $ID, 'vp_bp_activity_id' );
			
		$slug = $post->guid;
		$tags = get_post_meta( $ID, 'vp_post_type' );
		$type = @$tags[0];
		$type = $type == 'news' ? 'news' : rtrim( $type, 's' );
		$bp_uid = $post->post_author;
		
		$data = array();
		if( ! empty( $bp_post_id[0]) ) $data['id'] =  $bp_post_id[0];
		$data['action'] = sprintf( __( '%s created %s %s, %s', 'viralpress' ), bp_core_get_userlink( $bp_uid ), ( $type == 'audio' || $type == 'image' ? 'an' : 'a' ), $type, '<a href="'.$slug.'">'.preg_replace( '/^quiz\:|^poll\:/', '', $post->post_title ).'</a>' );
		$data['content'] = $post->post_excerpt.' '.get_the_post_thumbnail( $ID, 'small' );
		$data['primary_link'] = $slug;
		$data['type'] = 'vp_activity_update';
		$data['component'] = 'activity';
		$data['user_id'] = $post->post_author;
		
		$data = apply_filters( 'viralpress_bp_activity', $data, $ID, $post, $bp_uid, $bp_post_id );
		
		$bid = bp_activity_add( $data );
		
		add_update_post_meta( $ID, 'vp_bp_activity_id', $bid );
		
		/**
		 * send a buddypress notification
		 */
		if ( bp_is_active( 'notifications' ) ) {
			vp_post_approve_bp_noti( $bp_uid, $ID );
		}
		
		$a = get_user_meta( $bp_uid, 'vp_post_noti_count' );
		$a = @(int)end($a);
		$a++;
		add_update_user_meta( $bp_uid, 'vp_post_noti_count', $a );
	}
}

function vp_post_approve_bp_noti( $user_id, $post_id )
{
	$a = bp_notifications_add_notification( array(
		'user_id'           => $user_id,
		'item_id'           => $post_id,
		'component_name'    => 'vp_posts',
		'component_action'  => 'vp_post_approved_noti',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1
	) );
}

function vp_custom_filter_notifications_get_registered_components( $component_names = array() ) 
{
	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}
	array_push( $component_names, 'vp_posts' );
	return $component_names;
}

function vp_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $ac_name = null, $c_name = null, $id = null  ) 
{
	if ( 'vp_post_approved_noti' === $action ) {
		$post = get_post( $item_id );
		
		if( empty( $post ) || is_wp_error( $post ) ) {
			$custom_title = __( 'Item no longer available.', 'viralpress' );
			$custom_link  = '';
			$custom_text =  __( 'Item no longer available.', 'viralpress' );
			if( $id )bp_notifications_delete_notification( $id );
		}
		else if( $post->post_status == 'vp_open_list' ) {
			
			$n = get_post_meta( $item_id, 'vp_submitted_to' );
			$n = end($n);
			$nn = get_post( $n );
		
			
			$custom_title = __( 'Your open list submission has been approved.', 'viralpress' );
			$custom_link  = $nn->guid;
			$custom_text =  sprintf( __( 'Your open list submission on the post titled "%s" has been published.', 'viralpress' ), $nn->post_title );	
		}
		else {
			$custom_title = __( 'Your submission has been published.', 'viralpress' );
			$custom_link  = $post->guid;
			$custom_text =  sprintf( __( 'Your submission titled "%s" has been published.', 'viralpress' ), $post->post_title );
		}
	}
	else if( 'vp_comment_reply_noti' === $action ) {
		
		$comment_id = $item_id;
		$in_reply_to = $secondary_item_id;
		
		$dd = get_comment( $comment_id );
		$pp = get_comment( $in_reply_to );
		$text = substr( strip_tags( $pp->comment_content ), 0, 75 ).'...';
		$name = $dd->comment_author ;
		$link = get_comment_link( $in_reply_to );
		
		if( empty( $link ) ) {
			$custom_title = __( 'Item no longer available.', 'viralpress' );
			$custom_link  = '';
			$custom_text =  __( 'Item no longer available.', 'viralpress' );
			if( $id )bp_notifications_delete_notification( $id );
		}
		else {
			$custom_title = __( 'You received new reply on your comment.', 'viralpress' );
			$custom_link  = $link;
			$custom_text =  sprintf( __( '"%s" replied to your comment titled "%s".', 'viralpress' ), $name, $text );
		}	
	}	
	
	else if( 'vp_post_comment_noti' === $action ) {
		
		$comment_id = $item_id;
		$post_id = $secondary_item_id;
		
		$dd = get_comment( $comment_id );
		
		$text = substr( strip_tags( get_post_field( 'post_title', $post_id ) ), 0, 75 ).'...';
		$name = $dd->comment_author;
		$link = get_comment_link( $comment_id );
		
		if( empty( $link ) ) {
			$custom_title = __( 'Item no longer available.', 'viralpress' );
			$custom_link  = '';
			$custom_text =  __( 'Item no longer available.', 'viralpress' );
			if( $id )bp_notifications_delete_notification( $id );
		}
		else {
			$custom_title = __( 'You received new reply on your post.', 'viralpress' );
			$custom_link  = $link;
			$custom_text =  sprintf( __( '"%s" commented on your post titled "%s".', 'viralpress' ), $name, $text );
		}	
	}	
	
	// WordPress Toolbar
	if ( 'string' === $format ) {
		$return = apply_filters( 'viralpress_bp_noti_display', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>', $custom_text, $custom_link );

	// Deprecated BuddyBar
	} else {
		$return = apply_filters( 'viralpress_bp_noti_display', array(
			'text' => $custom_text,
			'link' => $custom_link
		), $custom_link, (int) $total_items, $custom_text, $custom_title );
	}
	
	return $return;	
}

function vp_bp_delete_activity( $ID )
{
	if ( function_exists('bp_activity_delete') ) {
		
		if ( $ID instanceof WP_Post ) {
			$post = $ID;
			$ID = $post->ID;
		}
		else $post = get_post( $ID );
		
		if( $post->post_type != 'post' )return;
		
		$bid = get_post_meta( $ID, 'vp_bp_activity_id' );
		if( !empty( $bid[0] ) ) {
			bp_activity_delete( array( 'id' => $bid[0] ) );
			delete_post_meta( $ID, 'vp_bp_activity_id' );
		}
		
		do_action( 'viralpress_after_bp_activity_delete' );
	}
}

function vp_bp_postsonprofile() 
{
	global $vp_instance;	
	if( empty( $vp_instance->settings['vp_bp'] ) ) return;
	$vp_instance->temp_vars['bp_post_status'] = 'publish';
	add_action( 'bp_template_content', 'vp_profile_screen_posts_show' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function vp_bp_draftpostsonprofile()
{
	global $vp_instance;
	if( empty( $vp_instance->settings['vp_bp'] ) ) return;
	$vp_instance->temp_vars['bp_post_status'] = 'draft';
	add_action( 'bp_template_content', 'vp_profile_screen_posts_show' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function vp_bp_pendingpostsonprofile()
{
	global $vp_instance;
	if( empty( $vp_instance->settings['vp_bp'] ) ) return;
	$vp_instance->temp_vars['bp_post_status'] = 'pending';
	add_action( 'bp_template_content', 'vp_profile_screen_posts_show' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function vp_bp_openpostsonprofile()
{
	global $vp_instance;
	if( empty( $vp_instance->settings['vp_bp'] ) ) return;
	$vp_instance->temp_vars['bp_post_status'] = 'openlist';
	add_action( 'bp_template_content', 'vp_profile_screen_openlists_show' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function vp_profile_screen_posts_show() 
{
	global $wp_query, $vp_instance;
	$temp = $wp_query;
	$theuser = bp_displayed_user_id();
	$viewer = bp_loggedin_user_id();
	
	ob_start();
	do_action( 'viralpress_bp_post_tab_before' );
	
	if( in_array( $vp_instance->temp_vars['bp_post_status'], array( 'draft', 'pending', 'openlist' ) ) && $theuser != $viewer ) {
		if( !current_user_can( 'administrator' ) ) {
			echo '<div class="alert alert-info">';
			_e( 'You are not authorized to view this tab.', 'viralpress' );
			echo '</div>';
			return;	
		}
	}
	
	$edit = $theuser == $viewer;
	$order = 'DESC';
	$q =  !empty( $_GET['query'] ) ? esc_js( $_GET['query'] ) : '';
	$cat = get_query_var( 'cat' ) ? (int)get_query_var( 'cat' ) : '';
	$paged = get_query_var( 'paged' ) ? (int)get_query_var( 'paged' ) : 0;
	$status = !empty( $_GET['status'] ) ? esc_js( $_GET['status'] ) : '';
	$type = !empty( $_GET['tags'] ) ? esc_js( $_GET['tags'] ) : '';
	
	if($edit)$ps = array('publish', 'pending', 'draft', 'future', 'private');
	else $ps = array( 'publish' );
	
	if( !empty($status) && $edit ) {
		if( in_array($status, $ps) )$ps = array( $status );	
	}
			
	$query = array(
		'search_prod_title' => $q,
		'paged' => $paged,
		'cat' => $cat,
		'author' => $theuser,
		'post_status' => $ps,
		'orderby' => 'date',
		'order' => $order,
		'tag' => $type
	);
	
	add_filter( 'posts_where', 'vp_title_filter', 10, 2 );
	$wp_query = new WP_Query($query);
	remove_filter( 'posts_where', 'vp_title_filter', 10, 2 );
	
	echo get_post_search_form( $edit, 1 );
	echo '<div class="vp-clearfix"></div>';
											
	if( $wp_query->have_posts() ) : ?>
	<?php 
		while( $wp_query->have_posts() ) : $wp_query->the_post();
			vp_print_post_data();			
		endwhile;
		echo '<div class="vp_dash_pagina">';
			echo paginate_links( array(
                'base' => preg_replace('/\?.*/', '/', get_pagenum_link()) . '%_%',
				'format' => '?paged=%#%',
                'current' => max( 1, $paged ),
                'total' => $wp_query->max_num_pages
        	) );
		echo '</div>';
	else:
		echo '<div class="alert alert-info">';
		_e( 'No post found.', 'viralpress' );
		echo '</div>';
	endif;
		
	echo '<script>
			jQuery("#cat").val("'.(int)$cat.'");jQuery(".vp-hidden-search-from").show();
			jQuery("#tags").val("'.esc_js(htmlspecialchars_decode($type)).'");jQuery(".vp-hidden-search-from").show();
			jQuery("select[name=\'status\']").val("'.esc_js(htmlspecialchars_decode($status)).'");jQuery(".vp-hidden-search-from").show();
			jQuery("input[name=\'query\']").val("'.esc_js(htmlspecialchars_decode($q)).'");jQuery(".vp-hidden-search-from").show();
		</script>';
	
	do_action( 'viralpress_bp_post_tab_after' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_bp_post_tab', $html, $theuser, $viewer );
	echo $html;
	
	$wp_query = $temp;
}

function vp_profile_screen_openlists_show( $theuser = '', $viewer = '', $card = 0 )
{
	global $vp_instance, $post;
	
	$oldpost = $post;
	$bp = 0;
	
	if( empty( $theuser ) ) {
		$bp = 1;
		if( !function_exists( 'bp_displayed_user_id' ) ) return;
		$theuser = bp_displayed_user_id();
		$viewer = bp_loggedin_user_id();
	}
	
	$per_page = 10;
	$all = 1;
	
	if( $theuser != $viewer ) {
		$all = 0;
	}
	else {
		if( @$_REQUEST['post_status'] == 'pending' ) $all = 2;	
		else if( @$_REQUEST['post_status'] == 'published' ) $all = 0;	
	}
	
	$noti_count = 0;	
	if( $theuser == $viewer ) {
		$noti_count = (int)vp_op_noti_count();
		update_user_meta( $theuser, 'vp_op_noti_count', 0 );
	}
	
	ob_start();
	do_action( 'viralpress_bp_openlist_tab_before' );
	
	if( $noti_count ){
		echo '<div class="vp-clearfix"></div><div class="alert alert-info">'.sprintf( __( 'You have %d new open list(s) approved since your last visit to this page' , 'viralpress' ), $noti_count ).'</div>';
    }
	
	echo '<style>.vp-entry fieldset{border:none !important;}.vp-author-info,.vp-entry legend{display: none}.vp-entry .box_list{display: none}.vp-entry .inline_list{display: none}</style><div class="vp-clearfix"></div>';
	$vp_instance->temp_vars['bypass_single'] = 1;
	$paged = !empty( $_REQUEST['paged'] ) ? (int)$_REQUEST['paged'] : 1;
	
	if( $all != 2 ) {
		$total = vp_count_all_open_list( $theuser, !$all );
		$posts = vp_get_all_open_list( $theuser, $paged, $per_page, 'post_date', 'desc', !$all );
	}
	else {
		$total = vp_count_pending_open_list( $theuser );
		$posts = vp_get_pending_open_list( $theuser, $paged, $per_page, 'post_date', 'desc' );
	}
	if( !empty( $posts ) ) : ?>
	<?php 
		if( $theuser == $viewer ) {
			
			if( $bp )echo '<div class="vp-clearfix"></div>';
			
			echo '<div class="vp-pull-right">';
			echo sprintf( __( 'Total %d entries', 'viralpress' ), $total );
			echo '<form style="margin-right:20px">';
			if( !$bp ) echo '<input type="hidden" name="show_op" value="1">';
			echo '
					<select name="post_status" onchange="$(this).parents(\'form:first\').submit()">
						<option value="all">'.__( 'Show all', 'viralpres' ).'</option>
						<option value="published" '.( @$_REQUEST['post_status'] == 'published' ? 'selected' : '' ).'>'.__( 'Show published', 'viralpres' ).'</option>
						<option value="pending" '.( @$_REQUEST['post_status'] == 'pending' ? 'selected' : '' ).'>'.__( 'Show pending', 'viralpres' ).'</option>
					</select>';
			echo '</form>';
			echo '</div>';
			echo '<div class="vp-clearfix-lg"></div><div class="vp-clearfix"></div>';
		}
		
		if( $card ) echo '<div class="vp-grid-2">';
		
		foreach( $posts as $p ) :
			$n = get_post_meta( $p['ID'], 'vp_submitted_to' );
			$n = end($n);
			$post = get_post( $n );
			if(empty($post))continue;
			setup_postdata( $post );  
			$s = '[vp_post_entry type="'.$p['post_type'].'" id="'.$p['ID'].'" order="1"]';
			
			$tt = '';
			
			if( $card ) $tt .= '<div class="vp-grid-item-2">';
			$tt .= '<div class="vp-op-bp-wrap">';
			if( !$card ) $tt .= '<hr/><div class="vp-clearfix"></div>';
			
			
			if( $theuser == $viewer ) {
				if($p['post_status'] == 'vp_open_list') {
					$tt .= '<div class="vp-pull-left">';
					$tt .= '<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;';
					$tt .= sprintf( __( 'This submission has been published to %s', 'viralpress' ), $p['parent_post'], '</a>' );
					$tt .= '</div>';
				}
				else {
					$tt .= '<div class="vp-pull-left">';
					$tt .= '<i class="glyphicon glyphicon-hourglass"></i>&nbsp;&nbsp;';
					$tt .= sprintf( __( 'This submission is pending to %s', 'viralpress' ), $p['parent_post'], '</a>' );
					$tt .= '</div>';
				}
				$tt .= '<div class="vp-pull-right">';
				$tt .= '<i class="glyphicon glyphicon-trash vp_open_list_del vp-pointer" data-rel="'.$p['ID'].'"></i>';	
				$tt .= '</div>';
			}
			else {
				$tt .= '<div class="vp-pull-left">';
				$tt .= sprintf( __( 'Published to %s', 'viralpress' ), $p['parent_post'] );
				$tt .= '</div>';	
			}
			$tt .= '<div class="vp-clearfix"></div><ul id="vp-tab">
    				<li class="active vp-op-list">';
			ob_start();
			$tt .= do_shortcode($s);
			$tt .= ob_get_contents();
			ob_end_clean();
			$tt .= '</li></ul></div>';
			
			if( $card ) $tt .= '</div>';
			else $tt .= '<div class="vp-clearfix-lg"></div>';
			
			//$p == 'this post' $post == 'submitted to'
			echo apply_filters( 'viralpress_bp_openlist_tab_loop', $tt, $p, $post, $s );
			
		endforeach;
		
		if( $card ) echo '</div>';
		
		//var_dump( $total );
		
		$aa = array( 'post_status' => @$_REQUEST['post_status'] );
		if( !$bp ) $aa['show_op'] = 1;
		
		$args = array(
			'total'              => ceil( $total / $per_page ),
			'base'               => '%_%',
			'format'             => '?paged=%#%',
			'add_args'           => $aa,
			'type'				 => 'plain',
			
 		);
		
		
		echo '<div class="vp_dash_pagina">';
		echo paginate_links( $args );
		echo '</div>';
		
	else:
		echo '<div class="alert alert-info">';
		_e( 'No post found.', 'viralpress' );
		echo '</div>';
	endif;
	
	do_action( 'viralpress_bp_openlist_tab_after' );
	$html = ob_get_contents();
	ob_end_clean();
	$html = apply_filters( 'viralpress_bp_openlist_tab', $html );
	echo $html;
	
	$post = $oldpost;
}

function vp_get_author_url( $child_author )
{
	$r = '';
	if( function_exists( 'bp_core_get_userlink' ) ) {
		$r .= bp_core_get_userlink( $child_author );	
	} 
	else {
		$name = get_the_author_meta( 'user_nicename' , $child_author );
		$r .= '<a href="'.get_author_posts_url( $child_author ).'">'.$name.'</a>';	
	}
	return apply_filters( 'viralpress_author_url', $r, $child_author );
}

function vp_bp_noti_count()
{
	global $vp_instance;
	if( empty( $vp_instance->settings['vp_bp'] ) ) return false;
	
	if( function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
		return bp_notifications_get_unread_notification_count( bp_loggedin_user_id() );
	}
	else return false;
}


function vp_bp_send_comment_noti( $comment, $to, $reply )
{
	global $vp_instance;
	if( empty( $vp_instance->settings['vp_bp'] ) ) return;
	
	if ( !bp_is_active( 'notifications' ) ) return;
	if( $reply ) {
		$a = bp_notifications_add_notification( array(
			'user_id'           => $to,
			'item_id'           => $comment->comment_ID,
			'secondary_item_id' => $comment->comment_parent,
			'component_name'    => 'vp_posts',
			'component_action'  => 'vp_comment_reply_noti',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}
	else {
		$a = bp_notifications_add_notification( array(
			'user_id'           => $to,
			'item_id'           => $comment->comment_ID,
			'secondary_item_id' => $comment->comment_post_ID ,
			'component_name'    => 'vp_posts',
			'component_action'  => 'vp_post_comment_noti',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );	
	}
	
	
}

/**
 * end buddypress integration
 */

function vp_count_pending_open_list( $uid = 0 ) 
{
	global $vp_instance, $wpdb;
	
	$querystr = "
		SELECT COUNT( * ) AS num_posts
		FROM $wpdb->posts
		WHERE 
		$wpdb->posts.post_status = 'vp_open_list_pending'
		". ( $uid ? " AND $wpdb->posts.post_author = '$uid' " : "" );

 	$p = $wpdb->get_results($querystr, ARRAY_A );
	foreach( $p as $pp ) return $pp['num_posts'];
}

function vp_count_all_open_list( $uid = 0, $only_published = 0 ) 
{
	global $vp_instance, $wpdb;
	
	$querystr = "
		SELECT COUNT( * ) AS num_posts
		FROM $wpdb->posts
		WHERE 
		".( $only_published ? " $wpdb->posts.post_status = 'vp_open_list' " : " ( $wpdb->posts.post_status = 'vp_open_list' OR $wpdb->posts.post_status = 'vp_open_list_pending' ) " )."
		". ( $uid ? " AND $wpdb->posts.post_author = '$uid' " : "" );

 	$p = $wpdb->get_results($querystr, ARRAY_A );
	foreach( $p as $pp ) return $pp['num_posts'];
}	

function vp_get_pending_open_list( $uid = 0, $paged = 1, $per_page = 10, $orderby, $order ) 
{
	global $vp_instance, $wpdb;
	
	if( $paged > 0 ) $paged--;
	$from = $paged * $per_page;
	$posts = array();
	
	$querystr = "
		SELECT $wpdb->posts.ID 
		FROM $wpdb->posts
		WHERE 
		$wpdb->posts.post_status = 'vp_open_list_pending'
		". ( $uid ? " AND $wpdb->posts.post_author = '$uid' " : "" )." 
		ORDER BY $wpdb->posts.".( $orderby == 'post_title' ? 'post_title' : 'post_date' )." ".( $order == 'asc' ? 'ASC' : 'DESC' )."
		LIMIT $from, $per_page
	";

	do_action( 'viralpress_get_openlist_pending_before' );

 	$p = $wpdb->get_results($querystr);
	foreach( $p as $pp ) {
		$ppp = get_post( $pp->ID, ARRAY_A );
		
		$n = get_post_meta( $pp->ID, 'vp_submitted_to' );
		$n = end($n);
		$nn = get_post( $n );
		
		if( !empty( $nn ) ) {
			$plink_wp = get_permalink( $nn->ID ) ;
			if( preg_match( '/\?/', $plink_wp ))$plink = $plink_wp.'&vp_preview='.$ppp['ID'];
			else $plink = $plink_wp.'?vp_preview='.$ppp['ID'];
			$ppp['parent_author'] = @vp_get_author_url( $nn->post_author );
			$ptitle = $nn->post_title;
		}
		else {
			$plink = '';	
			$ptitle = 'N/A';
			$ppp['parent_author'] = 'N/A';
			$plink_wp = '';
		}
		
		$ppp['post_title'] = '<a href="'.$plink_wp.'">'.( $ppp['post_title'] ? $ppp['post_title'] : __( 'View post', 'viralpress' ) ).'</a>';
		$ppp['author'] = vp_get_author_url( $ppp['post_author'] );
		$ppp['parent_post'] = '<a href="'.$plink_wp.'">'.$ptitle.'</a>';
		$ppp['parent_post_link'] = $plink;
		
		
		//$ppp['actions'] = '<button class="button button-secondary">'.__( 'Approve', 'viralpress' ).'</button> <br/><br/> <button class="button button-primary">'.__( 'Delete', 'viralpress' ).'</button>';
		
		$ppp['actions'] = '<a class="button button-primary" href="'.$plink.'">'.__( 'Preview', 'viralpress' ).'</a>';
		
		$ppp = apply_filters( 'viralpress_get_openlist_loop', $ppp, $pp->ID, $pp );
		
		$posts[] = $ppp;
	}
	
	do_action( 'viralpress_get_openlist_pending_after' );
	
	return $posts;
}

function vp_get_all_open_list( $uid = 0, $paged = 1, $per_page = 10, $orderby, $order, $only_published = 0 ) 
{
	global $vp_instance, $wpdb;
	
	if( $paged > 0 ) $paged--;
	$from = $paged * $per_page;
	$posts = array();
	
	$querystr = "
		SELECT $wpdb->posts.ID 
		FROM $wpdb->posts
		WHERE 
		".( $only_published ? " $wpdb->posts.post_status = 'vp_open_list' " : " ( $wpdb->posts.post_status = 'vp_open_list' OR $wpdb->posts.post_status = 'vp_open_list_pending' ) " )."
		". ( $uid ? " AND $wpdb->posts.post_author = '$uid' " : "" )." 
		ORDER BY $wpdb->posts.".( $orderby == 'post_title' ? 'post_title' : 'post_date' )." ".( $order == 'asc' ? 'ASC' : 'DESC' )."
		LIMIT $from, $per_page
	";

	do_action( 'viralpress_get_openlist_all_before' );

 	$p = $wpdb->get_results($querystr);
	foreach( $p as $pp ) {
		$ppp = get_post( $pp->ID, ARRAY_A );
		
		$n = get_post_meta( $pp->ID, 'vp_submitted_to' );
		$n = end($n);
		
		/*
		if( empty($n) ) {
			$qqq = "
				SELECT $wpdb->postmeta.post_id 
				FROM $wpdb->postmeta
				WHERE 
				$wpdb->postmeta.meta_key =  'vp_child_post_ids' AND
				( $wpdb->postmeta.meta_value LIKE '".$pp->ID.",%' OR $wpdb->postmeta.meta_value LIKE '%,".$pp->ID."' OR $wpdb->postmeta.meta_value LIKE '%,".$pp->ID.",%' OR $wpdb->postmeta.meta_value LIKE '%,".$pp->ID."' OR $wpdb->postmeta.meta_value = '".$pp->ID."' )
				LIMIT 1 
			";
			$qqqq = $wpdb->get_results($qqq);	
			$n = end($qqqq);
			$n = @$n->post_id;			
		}
		*/
		
		$nn = get_post( $n );
		
		if( !empty( $nn ) ) {
			$plink_wp = get_permalink( $nn->ID ) ;
			if( preg_match( '/\?/', $plink_wp ))$plink = $plink_wp.'&vp_preview='.$ppp['ID'];
			else $plink = $plink_wp.'?vp_preview='.$ppp['ID'];
			$ppp['parent_author'] = @vp_get_author_url( $nn->post_author );
			$ptitle = $nn->post_title;
		}
		else {
			$plink = '';	
			$ptitle = 'N/A';
			$ppp['parent_author'] = 'N/A';
			$plink_wp = '';
		}
		
		$ppp['post_title'] = '<a href="'.$plink_wp.'">'.( $ppp['post_title'] ? $ppp['post_title'] : __( 'View post', 'viralpress' ) ).'</a>';
		$ppp['author'] = vp_get_author_url( $ppp['post_author'] );
		$ppp['parent_post'] = '<a href="'.$plink_wp.'">'.$ptitle.'</a>';
		$ppp['parent_post_link'] = $plink;
		
		
		//$ppp['actions'] = '<button class="button button-secondary">'.__( 'Approve', 'viralpress' ).'</button> <br/><br/> <button class="button button-primary">'.__( 'Delete', 'viralpress' ).'</button>';
		
		$ppp['actions'] = '<a class="button button-primary" href="'.$plink.'">'.__( 'Preview', 'viralpress' ).'</a>';
		
		$ppp = apply_filters( 'viralpress_get_openlist_loop', $ppp, $pp->ID, $pp );
		
		$posts[] = $ppp;	
	}
	
	do_action( 'viralpress_get_openlist_all_after' );
	
	return $posts;
}	

function approve_open_list( $post_id )
{
	$the_post = get_post( $post_id );
				
	$mm = get_post_meta( $post_id, 'vp_submitted_to' );
	$mm = (int)end($mm);
	
	$that_post = get_post( $mm );
	
	$pauthor = $the_post->post_author;
	$current_user = get_current_user_id();
	
	if( !current_user_can( 'publish_posts' ) ) {
		return false;	
	}
	
	if( is_wp_error( $that_post ) || is_wp_error( $the_post ) || empty( $that_post ) || empty( $the_post ) )return false;
	
	if( $the_post->post_status != 'vp_open_list_pending' )return false;
	
	$c = $that_post->post_content;
	
	preg_match_all( '/\[vp_post_entry.*\]/siU', $c, $m );
	$order = count( $m[0] ) + 1;
	
	$eid = get_post_meta( $that_post->ID, 'vp_items_per_page');
	$eid = end($eid);
	$items_per_page = @(int)$eid;
	
	$new =  '';
	if( $items_per_page && @$order%$items_per_page == 1 ) $new = '<!--nextpage-->';
	$new .= '[vp_post_entry type="'.$the_post->post_type.'" id="'.$post_id.'" order="'.$order.'"]';
	
	$c .= $new;
	
	$a = false;
	if(!empty($c)){
		if( !vp_lock_post( $mm ) ) return false;
		$a = wp_update_post( array( 'ID' => $mm, 'post_content' => $c ) );
		vp_unlock_post( $mm );
	}
	
	if( !is_wp_error( $a ) && $a !== false ) {
		
		wp_update_post( array( 'post_status' => 'vp_open_list', 'ID' => $post_id ) );
		
		$olds = get_post_meta( $mm, 'vp_child_post_ids' );
		$olds = end( $olds );
		$old_post_ids = explode( ',', $olds );	
		$old_post_ids[] = $post_id;
		
		add_update_post_meta( $mm, 'vp_child_post_ids', implode( ',', $old_post_ids ) );
		
		if( function_exists( 'bp_is_active' ) ) {
			if ( bp_is_active( 'notifications' ) ) {
				vp_post_approve_bp_noti( $the_post->post_author, $post_id );
			}
		}
		
		$k = get_user_meta( $the_post->post_author, 'vp_op_noti_count' );
		$k = @(int)end($k);
		$k++;
		add_update_user_meta( $the_post->post_author, 'vp_op_noti_count', $k );
	}
	
	do_action( 'viralpress_open_list_approve', empty( $a ) ? 0 : 1, $the_post, $that_post );
	return $a;
}

function delete_open_list( $post_id )
{
	global $wpdb;
	
	$the_post = get_post( $post_id );
	$pauthor = $the_post->post_author;
	$current_user = get_current_user_id();
	
	if( $pauthor != $current_user && !current_user_can( 'publish_posts' ) ) {
		return false;	
	}
				
	$mm = get_post_meta( $post_id, 'vp_submitted_to' );
	$mm = (int)end($mm);
	
	$that_post = get_post( $mm );
	
	if( is_wp_error( $the_post ) || empty( $the_post ) )return false;
	
	if( !is_wp_error( $that_post ) && !empty( $that_post ) ) {
		
		//if post is approved before	
		if( $the_post->post_status == 'vp_open_list' ) {	
			
			$c = $that_post->post_content;
			$new = '';
			$o = 1;
			
			preg_match_all( '/\[vp_post_entry type="(.*)" id="(\d+)" order="(\d+)"\]/siU', $c, $m );
			
			foreach( $m[2] as $ii => $vv ) {
				if( $vv == $post_id ) {
				}
				else {
					$new .= '[vp_post_entry type="'.$m[1][$ii].'" id="'.$m[2][$ii].'" order="'.$o.'"]';
					$o++;
				}
			}
			
			$a = false;
			if( !empty($new) ) {
				wp_defer_term_counting( false );
				wp_defer_comment_counting( false );
				$wpdb->query( 'SET autocommit = 0;' );
	
				$a = wp_update_post( array( 'ID' => $mm, 'post_content' => $new ) );
				
				wp_defer_term_counting( true );
				wp_defer_comment_counting( true );
				$wpdb->query( 'SET autocommit = 1;' );
				$wpdb->query( 'COMMIT;' );
			}
			
			if( !is_wp_error( $a ) && $a !== false ) {
							
				$sql = $wpdb->prepare( "DELETE FROM ".$wpdb->prefix."postmeta WHERE post_id = %d", array($post_id) );
				$wpdb->query( $sql );
				
				$olds = get_post_meta( $mm, 'vp_child_post_ids' );
				$olds = end( $olds );
				$old_post_ids = explode( ',', $olds );	
				foreach( $old_post_ids as $i => $old ) {
					if( $old == $post_id ) unset( $old_post_ids[$i] );	
				}
				add_update_post_meta( $mm, 'vp_child_post_ids', implode( ',', $old_post_ids ) );
				wp_delete_post( $post_id, 1 );
			}
		}
		//if post is pending
		else {
			$sql = $wpdb->prepare( "DELETE FROM ".$wpdb->prefix."postmeta WHERE post_id = %d", array($post_id) );
			$wpdb->query( $sql );		
			wp_delete_post( $post_id, 1 );
			
			$a = true;
		}		
	}
	//if parent post is deleted
	else {
		$sql = $wpdb->prepare( "DELETE FROM ".$wpdb->prefix."postmeta WHERE post_id = %d", array($post_id) );
		$wpdb->query( $sql );		
		wp_delete_post( $post_id, 1 );
		
		$a = true;
	}
	
	if( $a ) {
		$k = get_user_meta( $the_post->post_author, 'vp_op_noti_count' );
		$k = @(int)end($k);
		$k--;
		if( $k >= 0)add_update_user_meta( $the_post->post_author, 'vp_op_noti_count', $k );	
	}
	
	do_action( 'viralpress_open_list_delete', empty( $a ) ? 0 : 1, $the_post, $that_post );
	
	return $a;	
}

function vp_array_splice_assoc(&$input, $offset, $length, $replacement = array()) {
    $replacement = (array) $replacement;
    $key_indices = array_flip(array_keys($input));
    if (isset($input[$offset]) && is_string($offset)) {
            $offset = $key_indices[$offset];
    }
    if (isset($input[$length]) && is_string($length)) {
            $length = $key_indices[$length] - $offset;
    }

    $input = array_slice($input, 0, $offset, TRUE)
            + $replacement
            + array_slice($input, $offset + $length, NULL, TRUE); 
}

function vp_post_noti_count()
{
	$uid = get_current_user_id();
	$a = get_user_meta( $uid, 'vp_post_noti_count' );
	return @(int)end($a);
}

function vp_comment_noti_count()
{
	$uid = get_current_user_id();
	$a = get_user_meta( $uid, 'vp_comment_noti_count' );
	return @(int)end($a);
}

function vp_op_noti_count()
{
	$uid = get_current_user_id();
	$a = get_user_meta( $uid, 'vp_op_noti_count' );
	return @(int)end($a);
}

function soft_validate_image( $in, $allow_hotlink = 0 )
{
	global $vp_instance;
	
	if( empty( $in ) ) return '';
	if( is_numeric( $in ) ) {
		if( !wp_attachment_is_image( $in ) ) return '';
		return $in;
	}
	else {
		$in = esc_url( $in, array( 'http', 'https' ) );
		if( $vp_instance->settings['hotlink_image'] == 0 && !$allow_hotlink ) return '';
		else if( filter_var($in, FILTER_VALIDATE_URL) === false ) return '';
		else {
			$size = getimagesize( $in );
			$valid_types = array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG );
			if( in_array( $size[2],  $valid_types ) ) {
				return $in;
			} 
			else {
				return '';
			}
			return '';
		}
	}
	return '';
}

function vp_esc_url( $url )
{
	if( empty( $url ) ) return false;
	if( is_numeric( $url ) ) {
		if( wp_attachment_is_image( $url ) ) return $url;
		else return false; 	
	}
	return esc_js( esc_url( $url , array( 'http', 'https' ) ) );
}

function register_vp_mycred__hook( $installed )
{
	global $vp_instance;
	if( empty( $vp_instance->settings['vp_mycred'] ) ) return $installed;
	
	$installed['vp_mycred_hooks'] = array(
		'title'       => __( 'ViralPress Interactions', 'viralpress' ),
		'description' => __( 'Awards for likes, dislikes, votes and reactions', 'textdomain' ),
		'callback'    => array( 'vp_mycred_hooks' )
	);
	return $installed;
}

function get_allowed_embed_regex()
{
	global $vp_instance;
	
	$aembeds = $vp_instance->settings['allowed_embeds'];
	if( !empty( $aembeds ) ) {
		$aembeds = explode( ',', $aembeds );
		foreach( $aembeds as &$a ) {
			$a = preg_quote( trim($a), '/' );	
		}
		$aembeds = implode('|', $aembeds );	
	}
	return $aembeds;
}

function vp_post_like_buttons( $atts )
{
	$post_id = @$atts['post_id'];
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$uid = '';	
		}	
	}
	
	if(empty( $post_id ) ) return false;
	
	$liked = has_user_liked_list( $post_id, $uid );
				
	$likes = get_post_meta( $post_id, 'vp_list_likes');
	$likes = (int)end($likes);
	
	$dislikes = get_post_meta( $post_id, 'vp_list_dislikes');
	$dislikes = (int)end($dislikes);
	
	
	do_action( 'viralpress_pre_like_buttons', $post_id, $uid, $likes, $dislikes, $liked );
	
	$t = '<div class="vp-op-au-3" data-rel="'.$post_id.'"'.( !empty( $atts['no_padding_top'] ) ? ' style="padding-top: 0px !important" ' : '' ).'>';
	$t .= '<span class="list_like_count">'.$likes.'</span>
			<i class="glyphicon glyphicon-thumbs-up '.( $liked == 1 ? 'vp_liked' : '' ).' vp-pointer vp_like_item" title="'.__( 'Like this', 'viralpress' ).'"></i>
		  <span class="list_dislike_count">'.$dislikes.'</span>	
			<i class="glyphicon glyphicon-thumbs-down '.( $liked == -1 ? 'vp_liked' : '' ).' vp-pointer vp_dislike_item" title="'.__( 'Dislike this', 'viralpress' ).'"></i>';
	$t .= '</div>';	
	
	do_action( 'viralpress_post_like_buttons', $post_id, $uid, $likes, $dislikes, $liked );
	
	return apply_filters( 'viralpress_like_buttons_html', $t, $post_id, $uid, $likes, $dislikes, $liked );
}

function vp_post_upvote_buttons( $atts )
{
	$post_id = @$atts['post_id'];
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$uid = '';	
		}	
	}
	
	if(empty( $post_id ) ) return false;
	
	$voted = has_user_voted_list( $post_id, $uid );
				
	$up_votes = get_post_meta( $post_id, 'vp_up_votes');
	$up_votes = end($up_votes);
	
	$down_votes = get_post_meta( $post_id, 'vp_down_votes');
	$down_votes = end($down_votes);
	
	$score = $up_votes - $down_votes;
	
	
	do_action( 'viralpress_pre_upvote_buttons', $post_id, $uid, $up_votes, $down_votes, $voted );
	$t = '<div class="vp-op-au-4" data-rel="'.$post_id.'">';
	$t .= '<i class="glyphicon glyphicon-chevron-up vp_upvote vp-pointer vp_upvote_item '.( $voted == 1 ? 'vp_list_vote_added' : '' ).'" title="'.__( 'Upvote this', 'viralpress' ).'"></i>
			<div class="vp_item_score_count">'.$score.'</div>
		  <i class="glyphicon glyphicon-chevron-down vp_downvote vp-pointer vp_downvote_item '.( $voted == -1 ? 'vp_list_vote_added' : '' ).'"  title="'.__( 'Downvote this', 'viralpress' ).'"></i>';
	$t .= '</div>';
	do_action( 'viralpress_post_upvote_buttons', $post_id, $uid, $up_votes, $down_votes, $voted );
	
	return apply_filters( 'viralpress_upvote_buttons_html', $t, $post_id, $uid, $up_votes, $down_votes, $voted );
}

function vp_verify_video_mime( $mime ) 
{
	return in_array( $mime, array( 'video/mp4', 'video/webm', 'video/ogg' ) );	
}

function vp_verify_audio_mime( $mime ) 
{
	return in_array( $mime, array( 'audio/mp3', 'audio/wav', 'audio/mpeg' ) );	
}

function vp_get_meme_lang()
{
	return array(
		'inv_input' => __( 'Invalid input', 'viralpress' ),
		'saving_img' => __( "Saving image", 'viralpress' ),
		'file_imported' => __( "File successfully imported", 'viralpress' ),
		'font_size_must' => __( "Font size must be less than 200px and greater than 1px", 'viralpress' ),
		'angle_must' => __( "Rotation must be between -180 to 180 degree", 'viralpress' ),
		'opa_must' => __( "Opacity must be between 0 to 1", 'viralpress' ),
		'ang_center' => __( "Angled watermark can only be placed on center. Proceed?", 'viralpress' ),
		'inv_input' => __( "Invalid input", 'viralpress' ),
		'set_size' => __( "Set Size", 'viralpress' ),
		'btm' => __( "Both text missing", 'viralpress' ),
		'added_now_drag' => __( "Text added. You can drag the text to your desired position if you want", 'viralpress' ),
		'img_save_fail' => __( "Failed to save media", 'viralpress' ),
		'img_saved' => __( "Image saved successfully", 'viralpress' ),
		'view_here' => __( "View here", 'viralpress' ),
		'no_change' => __( "You must add some text first", 'viralpress' )
	);
}

function vp_enqueue_script_meme_page()
{
	global $vp_instance;
	
	$meme_lang = vp_get_meme_lang();
	
	wp_register_style( 'viralpress-colorpicker', $vp_instance->settings['CSS_URL'].'/colorpicker.css' , array(), '1', 'all');
	wp_enqueue_style( 'viralpress-colorpicker' );
	
	wp_register_script( 'vp-cp-js', $vp_instance->settings['JS_URL'].'/colorpicker.js', array( 'jquery' ), true );
	wp_enqueue_script( 'vp-cp-js' );
	wp_register_script( 'vp-esl-js', $vp_instance->settings['JS_URL'].'/easeljs.js', true );
	wp_enqueue_script( 'vp-esl-js' );
	wp_register_script( 'vp-meme-js', $vp_instance->settings['JS_URL'].'/meme.js', array( 'jquery' ), VP_VERSION, true );
	wp_enqueue_script( 'vp-meme-js' );
	
	wp_localize_script( 'vp-meme-js', 'meme_lang', $meme_lang );
	wp_localize_script( 'vp-meme-js', 'meme_ajaxurl', admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'vp-meme-js', 'meme_ajax_nonce', wp_create_nonce( 'vp-ajax-action-'.get_current_user_id() ) );
}

function vp_get_parent_post_id( $id )
{
	global $wpdb;
	$q = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'vp_child_post_ids' AND ( meta_value = %d OR meta_value LIKE %s OR meta_value LIKE %s OR meta_value LIKE %s)", array( $id, '%,'. $id. ',%', '%,'. $id,  $id. ',%') );
	
	$r = $wpdb->get_results( $q );
	if( empty( $r[0]) ) return false;
	return $r[0]->post_id;	
}

function resort_vp_post( $post_id, $type )
{
	global $wpdb, $vp_instance;
	if( !$vp_instance->settings['sort_op_vote'] ) return;
	
	$c = $childs = get_post_meta( $post_id, 'vp_child_post_ids' );
	$childs = end( $childs );
	$c = end( $c );
	$childs = explode( ',', $childs );
	$childs = array_filter( $childs, 'strlen' );
	if( empty( $childs ) || count( $childs ) == 1 )return;
	
	$order = 'desc';
	$dd = get_post_meta( $post_id, 'vp_sort_order' );
	$dd = end( $dd );
	if( !empty( $dd ) ) $order = $dd;
	
	
	$new_childs = array();
	$vals = array();
	
	if( $type == 'like' ) {
		$q = @$wpdb->prepare( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id IN (".$c.") AND ( meta_key = 'vp_list_likes' OR meta_key = 'vp_list_dislikes' OR meta_key = 'vp_show_numbers' )" );
		$r = $wpdb->get_results( $q );	
	}
	else if( $type == 'vote' ) {
		$q = @$wpdb->prepare( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id IN (".$c.") AND ( meta_key = 'vp_up_votes' OR meta_key = 'vp_down_votes' OR meta_key = 'vp_show_numbers' )" );
		$r = $wpdb->get_results( $q );	
	}
	
	if( empty( $r ) )return;
	
	foreach( $childs as $ccc ) $vals[ $ccc ] = array( 'post_id' => $ccc, 'up' => 0, 'down' => 0, 'score' => 0, 'type' => '', 'show_numbers' => 1 );
	
	foreach( $r as $rr ) {
		
		if( $rr->meta_key == 'vp_list_likes' || $rr->meta_key == 'vp_up_votes' ) {
			$vals[ $rr->post_id ][ 'up' ] = $rr->meta_value;
			if( !empty( $vals[ $rr->post_id ][ 'down' ] ) ) $vals[ $rr->post_id ][ 'score' ] = $vals[ $rr->post_id ][ 'up' ] - $vals[ $rr->post_id ][ 'down' ]; 
		}
		
		else if( $rr->meta_key == 'vp_list_dislikes' || $rr->meta_key == 'vp_down_votes' ) {
			$vals[ $rr->post_id ][ 'down' ] = $rr->meta_value;
			if( !empty( $vals[ $rr->post_id ][ 'up' ] ) ) $vals[ $rr->post_id ][ 'score' ] = $vals[ $rr->post_id ][ 'up' ] - $vals[ $rr->post_id ][ 'down' ];
		}
		
		else if( $rr->meta_key == 'vp_show_numbers' ) {
			$vals[ $rr->post_id ][ 'show_numbers' ] = (int)$rr->meta_value;
		}
		
	}
	
	if( empty( $vals ) )return;
	
	foreach( $vals as &$vvv ) {
		if( empty( $vvv['score'] ) ) @$vvv['score'] = (int)$vvv['up'] - (int)$vvv['down'];	
	}
	
	$p = get_post( $post_id );
	$content = $p->post_content;
	$new = $content;
	$error = 0;
	
	preg_match_all( '/\[vp_post_entry type="([a-z]+)" id="(\d+)" order="(\d+)"\]/siU', $content, $m );
	
	foreach( $m[1] as $i => $mm ) {
		$vals[ (int)$m[2][$i] ][ 'type' ] = trim( $mm );	
	}

	$ovals = $vals;
	$vals = array_values( $vals );
	
	if( $order == 'desc' )usort($vals, 'vp_sort_score_down');
	else usort($vals, 'vp_sort_score_up');
	
	foreach( $m[2] as $i => $mm ) {
		
		$vv = array_shift( $vals );
		
		if( empty( $vv['type'] ) ){
			$error = 1;
			continue;	
		}
		
		$new = preg_replace( '/type="'.$m[1][$i].'" id="'.$mm.'" order="'.$m[3][$i].'"/iU', 'type="'.$vv['type'].'" id="'.$vv['post_id'].'" order="'.$m[3][$i].'"', $new );	
		$new_childs[] = $vv['post_id'];
	}
	
	$st = time();
	if( !vp_lock_post( $post_id ) ) {
		do{
			if( vp_lock_post( $post_id ) ) break;
			if( time() - $st > 30 ) return;
			sleep(3);
		}while(1);
	}
	
	if( $new != $content && !empty( $new ) && !$error ) {
		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );
		$wpdb->query( 'SET autocommit = 0;' );
		
		wp_update_post( array( 'ID' => $post_id, 'post_content' => $new ));
		
		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query( 'COMMIT;' );	
		
		if( !empty( $new_childs ) ) {
			add_update_post_meta( $post_id, 'vp_child_post_ids',  implode( ',', $new_childs ) );
		}
	}
	
	
	//print_r($new_childs);
	//echo "NEW: ".$new."\r\n OLD:".$content;
	
	vp_unlock_post( $post_id );
	
	return;
}

function vp_sort_score_up($a, $b) 
{
	if( $b['score'] == $a['score'] ) return $b['up'] - $a['up'];
    return $b['score'] - $a['score'];
}

function vp_sort_score_down($a, $b) 
{
	if( $b['score'] == $a['score'] ) return $a['up'] - $b['up'];
    return $a['score'] - $b['score'];
}

function vp_lock_post( $post_id )
{
	$n = get_post_meta( $post_id, 'vp_post_lock' );
	$n = end($n);
	
	if( !empty( $n ) && ( ( time() - $n ) < 180 ) ) return false;
	
	return update_post_meta( $post_id, 'vp_post_lock', time() );
}

function vp_unlock_post( $post_id )
{
	delete_post_meta( $post_id, 'vp_post_lock' );
}

function vp_strip_shortcodes( &$item, $key )
{
	$item = strip_shortcodes( $item );
}

function vp_comment_count( $email ) 
{
    global $wpdb;
    $count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE comment_author_email = %s', array( $email ) ) );
    echo $count;
}

function vp_admin_config_save( $data )
{
	global $vp_instance;
	$u = 0;
	foreach( $data as $k => $v ) {
		//if( isset( $vp_instance->settings[ $k ] ) ) {
		$u = 1;
		$vp_instance->configs[ $k ] = $v;	
		//}
	}
	
	if( $u ) {
		$settings = json_encode( $vp_instance->configs );
		update_option( 'vp-global-configs', $settings, false );	
	}
}

function vp_count_entries( $post_id ) 
{
	$c = get_post_meta( $post_id, 'vp_child_post_ids' );
	if( empty( $c[0] ) ) return 0;
	return count( explode( ',', $c[0] ) );	
}

function vp_comment_flood_filter( $a, $b, $c )
{
	if( empty( $b ) || empty( $c ) ) return false;
	return $a;
}
?>