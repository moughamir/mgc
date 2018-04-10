<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

function vp_google_auth()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$access_token = $_POST['access_token'];
	$data = wp_remote_get( 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$access_token );
	
	if( $data['response']['code'] != 200 ){
		$response['error'] = __( 'Failed to validate token', 'viralpress' );
		vp_output( $response );	
	} 

	$data = json_decode( $data['body'], true );
	
	if( empty($data['id']) || empty( $data['email']) ) {
		$response['error'] = __( 'Email missing', 'viralpress' );
		vp_output( $response );	
	}
	
	$google_id = $data['id'];
	$email = $data['email'];
	$first_name = $data['given_name'];
	$last_name = $data['family_name'];
	$picture = '';
	$cover = '';
	
	$current_user = get_current_user_id();
	
	if( $current_user ) {
		add_user_meta( $current_user, 'vp_google_id', $google_id );
		
		if( !empty($data['picture']) ){		
			$picture = @$data['picture'];
			$picture = vp_download_avatar( $picture , $current_user);	
		}
		
		if( !empty($first_name) && !empty($last_name) ) {
			wp_update_user( array(
				'ID' => $current_user,
				'first_name' => $first_name,
				'last_name' => $last_name
			) );
		}
		
		if( !empty($picture) ) {
			add_update_user_meta( $current_user, 'vp_avatar', $picture );
		}
		
		vp_output( $response );
	}
	
	$user = get_user_by( 'email', $email );
	if( empty($user) ) {
		$user = get_users( array( 'meta_key' => 'vp_google_id', 'meta_value' => $google_id, 'number' => 1 ) );	
		$user = end( $user );
	}
	
	if( empty($user) ){
		if ( ! get_option( 'users_can_register' ) ) {
			$response['error'] = __( 'Registering new users is currently not allowed.', 'viralpress' );	
			vp_output( $response );
		}
		
		//create new user
		$user_id = vp_create_user( '', $email, '', 'vp_google_id', $google_id );	
		if( $user_id === false || !is_numeric( $user_id ) ) {
			if( $user_id === false )$response['error'] = __( 'Login failed', 'viralpress' );
			else $response['error'] = $user_id;
			vp_output( $response );
		}
		$user = get_user_by( 'id', $user_id );
	}
	
	if( !empty($user) ) {
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );
    	do_action( 'wp_login', $user->user_login );
		
		if( !empty($first_name) && !empty($last_name) ) {
			wp_update_user( array(
				'ID' => $user->ID,
				'first_name' => $first_name,
				'last_name' => $last_name
			) );
		}
		
		if( !empty($data['picture']) ){		
			$picture = @$data['picture'];
			$picture = vp_download_avatar( $picture , $user->ID );	
		}
		
		if( !empty($picture) ) {
			add_update_user_meta( $user->ID, 'vp_avatar', $picture );
		}
	}
	
	do_action( 'viralpress_google_auth', $user );
	
	vp_output( $response );
}

function vp_fb_auth()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$access_token = $_POST['access_token'];
	$data = wp_remote_get( 'https://graph.facebook.com/me?fields='.urlencode('first_name,last_name,email,picture.width(100).height(100),cover').'&access_token='.$access_token );
	
	if( $data['response']['code'] != 200 ){
		$response['error'] = __( 'Failed to validate token', 'viralpress' );
		vp_output( $response );	
	} 

	$data = json_decode( $data['body'], true );
	
	if( empty($data['id']) || empty( $data['email']) ) {
		$response['error'] = __( 'Email missing', 'viralpress' );
		vp_output( $response );	
	}
	
	$fb_id = $data['id'];
	$email = $data['email'];
	$first_name = $data['first_name'];
	$last_name = $data['last_name'];
	$picture = '';
	$cover = '';
	
	$current_user = get_current_user_id();
	
	if( $current_user ) {
		add_user_meta( $current_user, 'vp_fb_id', $fb_id );
		
		if( !empty($first_name) && !empty($last_name) ) {
			wp_update_user( array(
				'ID' => $current_user,
				'first_name' => $first_name,
				'last_name' => $last_name
			) );
		}
		
		if( !empty($data['picture']) ){
			if( $data['picture']['data']['is_silhouette'] == false ){
				$picture = @$data['picture']['data']['url'];
				$picture = vp_download_avatar( $picture, $current_user );	
			}
		}
		
		if( !empty($data['cover']['source']) ){
			$cover = @$data['cover']['source'];
			$cover = vp_download_avatar( $cover, $current_user );	
		}
	
		
		if( !empty($picture) ) {
			add_update_user_meta( $current_user, 'vp_avatar', $picture );
		}
		
		if( !empty($cover) ) {
			add_update_user_meta( $current_user, 'vp_cover', $cover );
		}
		
		vp_output( $response );
	}
	
	$user = get_user_by( 'email', $email );
	if( empty($user) ) {
		$user = get_users( array( 'meta_key' => 'vp_fb_id', 'meta_value' => $fb_id, 'number' => 1 ) );	
		$user = end( $user );
	}
	
	if( empty($user) ){
		if ( ! get_option( 'users_can_register' ) ) {
			$response['error'] = __( 'Registering new users is currently not allowed.', 'viralpress' );	
			vp_output( $response );
		}
		
		//create new user
		$user_id = vp_create_user( '', $email, '', 'vp_fb_id', $fb_id );	
		if( $user_id === false || !is_numeric( $user_id ) ) {
			if( $user_id === false )$response['error'] = __( 'Login failed', 'viralpress' );
			else $response['error'] = $user_id;
			vp_output( $response );
		}
		$user = get_user_by( 'id', $user_id );
	}
	
	if( !empty($user) ) {
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );
    	do_action( 'wp_login', $user->user_login );
		
		if( !empty($first_name) && !empty($last_name) ) {
			wp_update_user( array(
				'ID' => $user->ID,
				'first_name' => $first_name,
				'last_name' => $last_name
			) );
		}
		
		if( !empty($data['picture']) ){
			if( $data['picture']['data']['is_silhouette'] == false ){
				$picture = @$data['picture']['data']['url'];
				$picture = vp_download_avatar( $picture, $user->ID );	
			}
		}
		
		if( !empty($data['cover']['source']) ){
			$cover = @$data['cover']['source'];
			$cover = vp_download_avatar( $cover, $user->ID );	
		}
		
		
		if( !empty($picture) ) {
			add_update_user_meta( $user->ID, 'vp_avatar', $picture );
		}
		
		if( !empty($cover) ) {
			add_update_user_meta( $user->ID, 'vp_cover', $cover );
		}
	}
	
	do_action( 'viralpress_fb_auth', $user );
	
	vp_output( $response );
}

function vp_download_image()
{
	$response = array();
	$response['error'] = '';
	$post_id = 0;
	
	validate_vp_ajax( $response );
	
	$url = @$_POST['load_url'];
	$caption = @esc_html( $_POST['caption'] );
	$alt = @esc_attr( $_POST['alt'] );
	
	if( empty($url) ) {
		$response['error'] = __( 'Invalid image URL' );
		vp_output( $response );	
	}
	
	preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $url, $matches );
	if ( ! $matches ) {
		$response['error'] = __( 'Invalid image URL' );
		vp_output( $response );
	}

	$file_array = array();
	$file_array['name'] = basename( $matches[0] );
	$file_array['tmp_name'] = download_url( $url );

	if ( is_wp_error( $file_array['tmp_name'] ) ) {
		$response['error'] = __( 'Download failed', 'viralpress'  );
		vp_output( $response );
	}

	$id = media_handle_sideload( $file_array, $post_id, '' );

	if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			$response['error'] = __( 'Failed to save image', 'viralpress' );
			vp_output( $response );
	}
	
	$src = wp_get_attachment_url( $id );
	
	$response['id'] = $id;
	$response['url'] = $src;
	
	if( !empty( $alt ) ) add_update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	if( !empty( $caption ) ) wp_update_post( array( 'ID' => $id, 'post_excerpt' => $caption ) );
	
	do_action( 'viralpress_image_downloaded', $id, $src );
	
	vp_output( $response );
}

function vp_poll_vote()
{
	global $vp_instance;
	
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		if( !$vp_instance->settings['anon_votes'] )	 {
			$response['error'] = __( 'You must login to vote.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
		
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$response['error'] = __( 'Sorry, something went wrong.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
	}
		
	$poll_id = (int)$_POST['poll_id'];
	$poll_votes = @json_decode( stripslashes( $_POST['poll_votes'] ), true );
	
	if( has_user_voted( $poll_id, $uid ) ) {
		$response['error'] = __( 'You already submitted your vote for this poll.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );
	}
	
	$voting_till = get_post_meta( $poll_id, 'vp_voting_open_till' );
	if( !empty( $voting_till[0] ) ) {
		if( strtotime( $voting_till[0] ) < time() ) {
			$response['error'] = __( 'Sorry voting has been closed.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}	
	}
	
	$j = 0;
	$ans = get_post_meta( $poll_id, 'vp_child_post_ids' );
	if( !empty($ans) ){
		$ans = @end( $ans );
		$ans = @explode( ',', $ans );
		$poll_exists = 0;
		
		foreach( $ans as $a ) {
			$j++;
			
			$t = get_post_type( $a );
			if( $t != 'polls' ) {
				continue;	
			}
			
			$poll_exists++;
			$v = @(int)$poll_votes[$a];
			if( empty( $v ) ) {
				$response['error'] = __( 'Please answer all the questions.', 'viralpress' );
				$response['error_elem'] = $j; 
				vp_output( $response );		
			}
			
			$ii = get_post_meta( $a, 'vp_answer_entry' );
			$ii = end( $ii );
			$ii = explode( ',', $ii );
			
			if( !in_array( $v, $ii ) ) {
				$response['error'] = __( 'Invalid answer provided.', 'viralpress' );
				$response['error_elem'] = $j;
				vp_output( $response );
			}	
		}
		
		if( !$poll_exists ) {
			$response['error'] = __( 'Could not find any poll for selected post.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
		
		add_post_meta( $poll_id, 'vp_user_poll_votes_'.$uid, json_encode( $poll_votes), true );
		foreach( $poll_votes as $ans => $vote ) {
			$k = 'vp_ans_poll_votes_'.$vote;
			$ev = 1;
			$e = get_post_meta( $poll_id, $k );
			if( !empty($e[0]) )$ev = $e[0] + 1;
			
			add_update_post_meta( $poll_id, $k, $ev );
		} 
		
		$ev = 1;
		$e = get_post_meta( $poll_id, 'vp_user_poll_votes_total' );
		if( !empty($e[0]) )$ev = $e[0] + 1;
		add_update_post_meta( $poll_id, 'vp_user_poll_votes_total', $ev );
		
		do_action( 'viralpress_poll_voted', $poll_id, $uid, $poll_votes );
		
		$response['data'] = json_encode( get_poll_results( $poll_id ) );
		vp_output( $response );	
	}
	else{
		$response['error'] = __( 'Failed to load answers for this poll. Please try later.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
}

function vp_mass_delete_post()
{
	$response = array();
	$response['error'] = '';
	$deleted = array();
	
	validate_vp_ajax( $response );
	
	$v = new vp_post();
	$post = explode( ',' ,$_POST['post_ids'] );
	foreach( $post as $p ) {
		$p = (int)$p;
		if ( current_user_can( 'edit_post', $p ) ) {
			$v->delete_post( $p );
			$deleted[] = $p;
		}	
	}
	
	$response['post_count'] = count($post);
	$response['edit_count'] = count($deleted);
	$response['deleted'] = implode(',', $deleted);
	
	do_action( 'viralpress_post_deleted', $post );
	
	vp_output( $response );
}

function vp_mass_publish_post()
{	
	$response = array();
	$response['error'] = '';
	$edited = array();
	
	validate_vp_ajax( $response );
	
	$is_admin = current_user_can( 'administrator' );
	
	$post = explode( ',' ,$_POST['post_ids'] );
	foreach( $post as $p ) {
		$p = (int)$p;
		if ( current_user_can( 'edit_post', $p ) ) {
			if( !$is_admin ) {
				$s = get_post_status( $p );
				if( $s != 'publish' )$s = 'pending';	
			}
			else{
				$s = 'publish';	
			}
			$k = wp_update_post( array('ID' => $p, 'post_status' => $s ) );
			if( !is_wp_error( $k ) )$edited[$p] = $s;
		}	
	}
	
	do_action( 'viralpress_post_published', $post );
	
	$response['post_count'] = count($post);
	$response['edit_count'] = count($edited);
	$response['edited'] = json_encode($edited);
	vp_output( $response );
}

function vp_mass_draft_post()
{	
	$response = array();
	$response['error'] = '';
	$edited = array();
	
	validate_vp_ajax( $response );
	
	$post = explode( ',' ,$_POST['post_ids'] );
	foreach( $post as $p ) {
		$p = (int)$p;
		if ( current_user_can( 'edit_post', $p ) ) {
			$s = 'draft';	
			$k = wp_update_post( array('ID' => $p, 'post_status' => $s ) );
			if( !is_wp_error( $k ) )$edited[$p] = $s;
		}	
	}
	
	do_action( 'viralpress_post_drafted', $post );
	
	$response['post_count'] = count($post);
	$response['edit_count'] = count($edited);
	$response['edited'] = json_encode($edited);
	vp_output( $response );
}

function vp_set_avatar()
{
	$response = array();
	$response['error'] = '';
	$media_id = (int)$_POST['media_id'];
	
	validate_vp_ajax( $response );
	
	if ( current_user_can( 'edit_post', $media_id ) && wp_attachment_is_image( $media_id ) ) {
		//$url = wp_get_attachment_image_src( $media_id, 'large' );
		$uid = get_current_user_id();
		//add_update_user_meta( $uid, 'vp_avatar', $url[0] );
		add_update_user_meta( $uid, 'vp_avatar', $media_id );
		
		do_action( 'viralpress_avatar_set', $uid, $media_id );
	}	
	else{
		$response['error'] = __( 'Invalid image', 'viralpress' );	
	}
	
	vp_output( $response );		
}

function vp_set_cover()
{
	$response = array();
	$response['error'] = '';
	$media_id = (int)$_POST['media_id'];
	
	validate_vp_ajax( $response );
	
	if ( current_user_can( 'edit_post', $media_id ) && wp_attachment_is_image( $media_id ) ) {
		//$url = wp_get_attachment_image_src( $media_id, 'large' );
		$uid = get_current_user_id();
		//add_update_user_meta( $uid, 'vp_cover', $url[0] );
		add_update_user_meta( $uid, 'vp_cover', $media_id );
		
		do_action( 'viralpress_cover_set', $uid, $media_id );
	}	
	else{
		$response['error'] = __( 'Invalid image', 'viralpress' );	
	}
	
	vp_output( $response );
}

function vp_update_user()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$uid = get_current_user_id();
	$fname = esc_html( $_POST['fname'] );
	$lname = esc_html( $_POST['lname'] );
	$email = $_POST['email'];
	//$dname = esc_html( $_POST['dname'] );
	
	if( strlen($fname) < 1 ) {
		$response['error'] = __( 'First name must be at least one character long', 'viralpress' );
		vp_output( $response );
	}
	
	if( strlen($lname) < 1 ) {
		$response['error'] = __( 'Last name must be at least one character long', 'viralpress' );
		vp_output( $response );
	}
	
	/*if( strlen($dname) < 4 ) {
		$response['error'] = __( 'Display name must be at least four character long', 'viralpress' );
		vp_output( $response );
	}*/
	
	if( !is_email($email) ) {
		$response['error'] = __( 'Invalid email address', 'viralpress' );
		vp_output( $response );
	}
	
	$e = email_exists($email);
	if( $e && $e != $uid ) {
		$response['error'] = __( 'This email address is already registered with another account', 'viralpress' );
		vp_output( $response );	
	}
	
	if( defined( 'VP_DEMO' ) ) {
		$response['error'] = __( 'Action not permitted on demo', 'viralpress' );
		vp_output( $response );
	}
	
	wp_update_user(
		array(
		  'ID'          =>    $uid,
		  'user_email' =>    $email,
		  'first_name'  =>    $fname,
		  'last_name'  => 	  $lname,
		  //'display_name'=>    $dname
		)
	);
	
	do_action( 'viralpress_user_update', $uid, $email );
	
	vp_output( $response );
}

function vp_s_update_user()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$uid = get_current_user_id();
	$pwd = $_POST['pwd'];
	$pwd2 = $_POST['pwd2'];
	
	if( strlen($pwd) < 6 ) {
		$response['error'] = __( 'Password must be at least six character long', 'viralpress' );
		vp_output( $response );
	}
	
	if( $pwd != $pwd2 ) {
		$response['error'] = __( 'Passwords do not match', 'viralpress' );
		vp_output( $response );
	}
	
	if( defined( 'VP_DEMO' ) ) {
		$response['error'] = __( 'Action not permitted on demo', 'viralpress' );
		vp_output( $response );
	}
	
	wp_set_password( $pwd, $uid);
	
	do_action( 'viralpress_user_credentials_update', $uid );
	
	vp_output( $response );
}

function vp_c_update_user()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$uid = get_current_user_id();
	$fb_url = esc_url( $_POST['fb_url'] );
	$tw_url = esc_url( $_POST['tw_url'] );
	$gp_url = esc_url( $_POST['gp_url'] );
	
	if( !empty($fb_url) ) {
		$t = parse_url( $fb_url );
		if( !preg_match('/facebook\.com|fb\.com/i', @$t['host']) ) {
			$response['error'] = __( 'Invalid facebook profile url', 'viralpress' );
			vp_output( $response );
		}
	}
	
	if( !empty($tw_url) ) {
		$t = parse_url( $tw_url );
		if( !preg_match('/twitter\.com/i', @$t['host']) ) {
			$response['error'] = __( 'Invalid twitter profile url', 'viralpress' );
			vp_output( $response );
		}
	}
	
	if( !empty($gp_url) ) {
		$t = parse_url( $gp_url );
		if( !preg_match('/plus\.google\.com/i', @$t['host']) ) {
			$response['error'] = __( 'Invalid google profile url', 'viralpress' );
			vp_output( $response );
		}
	}
	
	add_update_user_meta( $uid, 'vp_fb_url', $fb_url );
	add_update_user_meta( $uid, 'vp_tw_url', $tw_url );
	add_update_user_meta( $uid, 'vp_gp_url', $gp_url );
	
	do_action( 'viralpress_user_social_update', $uid, $fb_url, $tw_url, $gp_url );
	
	vp_output( $response );
}


function vp_add_post()
{
	global $wpdb;
	$response = array();
	$response['error'] = '';
	
	$error = '';
	$error_selectors = array();
	$error_selectors_msg = array();
	$success = '';
	$show_editor = 1;
	$message = '';
	
	wp_defer_term_counting( false );
	wp_defer_comment_counting( false );
	$wpdb->query( 'SET autocommit = 0;' );
	
	$vp_post = new vp_post();
	$vp_post->add_post();
	
	wp_defer_term_counting( true );
	wp_defer_comment_counting( true );
	$wpdb->query( 'SET autocommit = 1;' );
	$wpdb->query( 'COMMIT;' );

	if( empty($vp_post->error) && $vp_post->post_link ){
		$response['success'] = 1;
		$response['preview'] = $vp_post->post_link;
		$response['message'] = 
			$vp_post->message.' '.__( 'View the preview', 'viralpress' ).' <a href="'.$vp_post->post_link.'" target="_blank">'.__( 'here', 'viralpress' ).'</a>';
		$response['post_id'] = $vp_post->post_id;
		$response['child_ids'] = $vp_post->child_ids;
		$response['ans_ids'] = $vp_post->ans_ids;
		$response['edit_url'] = get_edit_post_link( $vp_post->post_id );
		
		do_action( 'viralpress_post_added', $vp_post, $vp_post->post_id, $vp_post->post_link );
	}
	else{
		$response['success'] = 0;
		$response['error'] = $vp_post->error;
		$response['error_selectors'] = $vp_post->error_selectors;
		$response['error_selectors_msg'] = $vp_post->error_selectors_msg;
	}
	
	vp_output( $response );
}

function vp_post_react()
{
	global $vp_instance;
	
	$response = array();
	$response['error'] = '';
	$response['withdrawn'] = 0;
	$is_new = 1;
	
	validate_vp_ajax( $response );
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		if( !$vp_instance->settings['anon_votes'] )	 {
			$response['error'] = __( 'You must login to vote.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
		
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$response['error'] = __( 'Sorry, something went wrong.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
	}
	
	$post_id = (int)$_POST['post_id'];
	$type = $_POST['type'];
	if( !in_array( $type, array( 'NICE', 'SAD', 'CRYING', 'FUNNY', 'LOL', 'COOL', 'EW', 'OMG', 'WTF', 'WOW', 'ISEE', 'ME2', ) ) ) {
		$response['error'] = __( 'Invalid reaction', 'viralpress' );
		vp_output( $response );
	}
	
	$k_u = 'vp_user_react_'.$uid;
	$k_t = 'vp_post_react_'.$type;
	
	$cval = get_post_meta( $post_id, $k_t );
	$cval = @(int)$cval[0];
	
	$mm = get_post_meta( $post_id, $k_u );
	if( !empty( $mm[0] ) ) {
		$is_new = 0;
		if( $mm[0] == $type ) {
			add_update_post_meta( $post_id, $k_t, --$cval );
			delete_post_meta( $post_id, $k_u, $type );
			$response['withdrawn'] = 1;
			do_action( 'viralpress_post_reaction_withdrawn', $post_id, $uid, $type );
			vp_output( $response );			
		}
		
		$cval_mm = get_post_meta( $post_id, 'vp_post_react_'.$mm[0] );
		$cval_mm = @(int)end( $cval_mm );
		delete_post_meta( $post_id, 'vp_post_react_'.$mm[0] );
		add_update_post_meta( $post_id, 'vp_post_react_'.$mm[0], --$cval_mm );
		
		//$response['error'] = __( 'You already reacted to this post. Withdraw the previous reaction to react again.', 'viralpress' );
		//vp_output( $response );
	}
	
	add_update_post_meta( $post_id, $k_t, ++$cval );
	add_update_post_meta( $post_id, $k_u, $type );
	
	$ev = 1;
	$e = get_post_meta( $post_id, 'vp_user_react_total' );
	if( !empty($e[0]) )$ev = $e[0] + 1;
	add_update_post_meta( $post_id, 'vp_user_react_total', $ev );
	
	do_action( 'viralpress_post_reacted', $post_id, $uid, $type, $is_new );
	
	vp_output( $response );
}

function vp_upvote_item()
{
	global $vp_instance, $wpdb;
	
	$response = array();
	$response['error'] = '';
	$response['vote_withdrawn'] = 0;
	
	validate_vp_ajax( $response );
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		if( !$vp_instance->settings['anon_votes'] )	 {
			$response['error'] = __( 'You must login to vote.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
		
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$response['error'] = __( 'Sorry, something went wrong.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
	}
	
	$post_id = (int)$_POST['post_id'];
	if( false === get_post_status( $post_id ) ) {
		$response['error'] = __( 'Post does not exist.', 'viralpress' );
		vp_output( $response );	
	}
	
	$parent = vp_get_parent_post_id( $post_id );
	$cc = get_post_meta( $parent, 'vp_open_list' );
	$cc = @(int)$cc[0];
	
	if( empty( $cc ) && $vp_instance->settings['hide_vote_buttons'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	else if( $vp_instance->settings['hide_vote_buttons_op'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	
	$mm = has_user_voted_list( $post_id, $uid );
	
	$u = get_post_meta( $post_id, 'vp_up_votes' );
	$u = end($u);
	
	$d = get_post_meta( $post_id, 'vp_down_votes' );
	$d = end($d);
	
	if( empty( $u )) $u = 0;
	if( empty( $d )) $d = 0;
	
	if( $mm == 1 ) {
		//$response['error'] = __( 'You already upvoted this list', 'viralpress' );
		if( $u > 0 ) {
			$u--;
			add_update_post_meta( $post_id, 'vp_up_votes', $u );	
		}
		delete_post_meta( $post_id, 'vp_user_list_votes_'.$uid );
		$response['total_votes'] = $u - $d;
		$response['vote_withdrawn'] = 1;
		do_action( 'viralpress_list_upvote_withdrawn', $post_id, $uid, $u, $d, 0, $parent, $cc );
		vp_output( $response );
	}
	else if( $mm == -1 && $d > 0 ) {
		$d--;
		add_update_post_meta( $post_id, 'vp_down_votes', $d );	
	}
	
	add_update_post_meta( $post_id, 'vp_user_list_votes_'.$uid, 1 );
	
	$u++;
	add_update_post_meta( $post_id, 'vp_up_votes', $u );
	
	$response['total_votes'] = $u - $d;
	
	do_action( 'viralpress_list_upvoted', $post_id, $uid, $u, $d, $mm == -1 ? 1 : 0, $parent, $cc );
	
	vp_output( $response );
}

function vp_downvote_item()
{
	global $vp_instance, $wpdb;
	
	$response = array();
	$response['error'] = '';
	$response['vote_withdrawn'] = 0;
	
	validate_vp_ajax( $response );
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		if( !$vp_instance->settings['anon_votes'] )	 {
			$response['error'] = __( 'You must login to vote.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
		
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$response['error'] = __( 'Sorry, something went wrong.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
	}
	
	$post_id = (int)$_POST['post_id'];
	if( false === get_post_status( $post_id ) ) {
		$response['error'] = __( 'Post does not exist.', 'viralpress' );
		vp_output( $response );	
	}
	
	$parent = vp_get_parent_post_id( $post_id );
	$cc = get_post_meta( $parent, 'vp_open_list' );
	$cc = @(int)$cc[0];
	
	if( empty( $cc ) && $vp_instance->settings['hide_vote_buttons'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	else if( $vp_instance->settings['hide_vote_buttons_op'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	
	$mm = has_user_voted_list( $post_id, $uid );
	
	$u = get_post_meta( $post_id, 'vp_up_votes' );
	$u = end($u);
	
	$d = get_post_meta( $post_id, 'vp_down_votes' );
	$d = end($d);
	
	if( empty( $u )) $u = 0;
	if( empty( $d )) $d = 0;
	
	if( $mm == -1 ) {
		//$response['error'] = __( 'You already downvoted this list', 'viralpress' );
		if( $d > 0 ) {
			$d--;
			add_update_post_meta( $post_id, 'vp_down_votes', $d );	
		}
		delete_post_meta( $post_id, 'vp_user_list_votes_'.$uid );
		$response['total_votes'] = $u - $d;
		$response['vote_withdrawn'] = 1;
		do_action( 'viralpress_list_downvote_withdrawn', $post_id, $uid, $u, $d, 0, $parent, $cc );
		vp_output( $response );
	}
	else if( $mm == 1 && $u > 0 ) {
		$u--;
		add_update_post_meta( $post_id, 'vp_up_votes', $u );	
	}
	
	add_update_post_meta( $post_id, 'vp_user_list_votes_'.$uid, -1 );
	
	$d++;
	add_update_post_meta( $post_id, 'vp_down_votes', $d );
	
	$response['total_votes'] = $u - $d;
	
	do_action( 'viralpress_list_downvoted', $post_id, $uid, $u, $d, $mm == 1 ? 1 : 0, $parent, $cc );
	
	vp_output( $response );
}

function vp_like_item()
{
	global $vp_instance, $wpdb;
	
	$response = array();
	$response['error'] = '';
	$response['vote_withdrawn'] = 0;
	
	validate_vp_ajax( $response );
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		if( !$vp_instance->settings['anon_votes'] )	 {
			$response['error'] = __( 'You must login to like.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
		
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$response['error'] = __( 'Sorry, something went wrong.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
	}
	
	$post_id = (int)$_POST['post_id'];
	if( false === get_post_status( $post_id ) ) {
		$response['error'] = __( 'Post does not exist.', 'viralpress' );
		vp_output( $response );	
	}
	
	$parent = vp_get_parent_post_id( $post_id );
	$cc = get_post_meta( $parent, 'vp_open_list' );
	$cc = @(int)$cc[0];
	
	if( empty( $cc ) && $vp_instance->settings['hide_vote_buttons'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	else if( $vp_instance->settings['hide_vote_buttons_op'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	
	$mm = has_user_liked_list( $post_id, $uid );
	
	$u = get_post_meta( $post_id, 'vp_list_likes' );
	$u = end($u);
	
	$d = get_post_meta( $post_id, 'vp_list_dislikes' );
	$d = end($d);
	
	if( empty( $u )) $u = 0;
	if( empty( $d )) $d = 0;
	
	if( $mm == 1 ) {
		//$response['error'] = __( 'You already upvoted this list', 'viralpress' );
		if( $u > 0 ) {
			$u--;
			add_update_post_meta( $post_id, 'vp_list_likes', $u );	
		}
		delete_post_meta( $post_id, 'vp_user_list_likes_'.$uid );
		$response['total_likes'] = $u;
		$response['total_dislikes'] = $d;
		$response['vote_withdrawn'] = 1;
		do_action( 'viralpress_list_like_withdrawn', $post_id, $uid, $u, $d, 0, $parent, $cc );
		vp_output( $response );
	}
	else if( $mm == -1 && $d > 0 ) {
		$d--;
		add_update_post_meta( $post_id, 'vp_list_dislikes', $d );	
	}
	
	add_update_post_meta( $post_id, 'vp_user_list_likes_'.$uid, 1 );
	
	$u++;
	add_update_post_meta( $post_id, 'vp_list_likes', $u );
	
	$response['total_likes'] = $u;
	$response['total_dislikes'] = $d;
	
	do_action( 'viralpress_list_liked', $post_id, $uid, $u, $d, $mm == -1 ? 1 : 0, $parent, $cc );
	
	vp_output( $response );
}

function vp_dislike_item()
{
	global $vp_instance, $wpdb;
	
	$response = array();
	$response['error'] = '';
	$response['vote_withdrawn'] = 0;
	
	validate_vp_ajax( $response );
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		if( !$vp_instance->settings['anon_votes'] )	 {
			$response['error'] = __( 'You must login to dislike.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
		
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			$response['error'] = __( 'Sorry, something went wrong.', 'viralpress' );
			$response['error_elem'] = -1; 
			vp_output( $response );	
		}
	}
	
	$post_id = (int)$_POST['post_id'];
	if( false === get_post_status( $post_id ) ) {
		$response['error'] = __( 'Post does not exist.', 'viralpress' );
		vp_output( $response );	
	}
	
	$parent = vp_get_parent_post_id( $post_id );
	$cc = get_post_meta( $parent, 'vp_open_list' );
	$cc = @(int)$cc[0];
	
	if( empty( $cc ) && $vp_instance->settings['hide_vote_buttons'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	else if( $vp_instance->settings['hide_vote_buttons_op'] ) {
		$response['error'] = __( 'Sorry! Voting is disabled.', 'viralpress' );
		$response['error_elem'] = -1; 
		vp_output( $response );	
	}
	
	$mm = has_user_liked_list( $post_id, $uid );
	
	$u = get_post_meta( $post_id, 'vp_list_likes' );
	$u = end($u);
	
	$d = get_post_meta( $post_id, 'vp_list_dislikes' );
	$d = end($d);
	
	if( empty( $u )) $u = 0;
	if( empty( $d )) $d = 0;
	
	if( $mm == -1 ) {
		//$response['error'] = __( 'You already upvoted this list', 'viralpress' );
		if( $d > 0 ) {
			$d--;
			add_update_post_meta( $post_id, 'vp_list_dislikes', $u );	
		}
		delete_post_meta( $post_id, 'vp_user_list_likes_'.$uid );
		$response['total_likes'] = $u;
		$response['total_dislikes'] = $d;
		$response['vote_withdrawn'] = 1;
		do_action( 'viralpress_list_dislike_withdrawn', $post_id, $uid, $u, $d, 0, $parent, $cc );
		vp_output( $response );
	}
	else if( $mm == 1 && $u > 0 ) {
		$u--;
		add_update_post_meta( $post_id, 'vp_list_likes', $u );	
	}
	
	add_update_post_meta( $post_id, 'vp_user_list_likes_'.$uid, -1 );
	
	$d++;
	add_update_post_meta( $post_id, 'vp_list_dislikes', $d );
	
	$response['total_likes'] = $u;
	$response['total_dislikes'] = $d;
	
	do_action( 'viralpress_list_disliked', $post_id, $uid, $u, $d, $mm == 1 ? 1 : 0, $parent, $cc );
	
	vp_output( $response );
}

function vp_quiz_taken()
{
	global $vp_instance;
	
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	if( is_user_logged_in() )$uid = get_current_user_id();
	else {
		$uid = @$_COOKIE['vp_unan'];
		if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
			return;
		}
	}
	
	$shared = @esc_html( $_POST['shared'] );
	$post_id = (int)$_POST['post_id'];
	$mm = get_post_meta( $post_id, 'vp_post_type' );
	if( empty( $mm[0] ) || @$mm[0] != 'quiz' ) {
		return;
	}
	
	$k_u = 'vp_user_quiz_taken_'.$uid;
	$mm = get_post_meta( $post_id, $k_u );
	if( !empty( $mm[0] ) ) {
		return;
	}
	
	$cval = get_post_meta( $post_id, 'vp_total_quiz_taken' );
	$cval = @(int)$cval[0];
	
	add_update_post_meta( $post_id, 'vp_total_quiz_taken', ++$cval );
	add_update_post_meta( $post_id, $k_u, $uid );
	
	if( !empty( $shared ) ) {
		$ii = get_post_meta( $post_id, 'vp_quiz_share_ids' );
		$ii = @$ii[0];
		
		if(  !empty( $ii ) ) {
			$ii = json_decode( $ii, true );
			$ii['count']++;
		}	
		else {
			$ii = array();
			$ii['count'] = 1;
			$ii['ids'] = array();
		}
		
		$ii['ids'][] = array( 'post_id' => $shared, 'site' => 'facebook', 'time' => date( 'd-M-Y H:i:s' ) );
		$ii['ids'] = array_reverse( $ii['ids'] );
		$ii['ids'] = array_slice( $ii['ids'], 0, 100 );	
		add_update_post_meta( $post_id, 'vp_quiz_share_ids', json_encode( $ii ) );
	}
	
	do_action( 'viralpress_quiz_taken', $post_id, $uid, $cval );
	return;
}

function vp_gif_react()
{
	global $vp_instance;
	
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$uid = get_current_user_id();
	$post_id = (int)$_POST['post_id'];
	$url = esc_url( $_POST['url'] , array( 'http', 'https' ));
	$comment = esc_html( $_POST['comment'] );
	
	if( empty( $url ) ) {
		$response['error'] = __( 'Link required!', 'viralpress' );
		vp_output( $response );	
	}
	
	$safe = 0;

	$gifs = $vp_instance->settings['react_gifs'];
	if( !empty( $gifs ) ) {
		$gifs = json_decode( $gifs, true );
		foreach( $gifs as $gg ) {
			if( $gg['url'] == $url ) {
				$safe = 1;
				break;	
			}	
		}	
	}
	
	if( !comments_open( $post_id ) ) {
		$response['error'] = __( 'Sorry! Comments are closed for this post '.$post_id, 'viralpress' );
		vp_output( $response );	
	}
	
	if( !$safe ) {
		
		if( empty( $vp_instance->settings['show_gif_reactions_upload'] ) ) {
			$response['error'] = __( 'Sorry! custom gif reaction is not allowed', 'viralpress' );
			vp_output( $response );	
		}
		
		$url = soft_validate_image( $url, 1 );
		if( empty( $url ) ) {
			$response['error'] = __( 'This url is not accessible or not an image.', 'viralpress' );
			vp_output( $response );		
		}	
	}
	
	if( $safe && !empty( $comment ) ) $safe = 0;
	
	if( !$safe ) {
		if( current_user_can( 'administrator' ) ) $safe = 1;		
	}
	
	$data = array(
		'comment_post_ID' => $post_id,
		'comment_content' => ( $comment ? $comment.'<br/><br/>' : '' ).'<a class="vp-comment-link" href="'.$url.'" target="_blank"><img class="vp-comment-image" src="'.$url.'"/></a>',
		'comment_type' => '',
		'comment_parent' => 0,
		'user_id' => $uid,
		'comment_approved' => $safe,
	);
	
	$data = apply_filters( 'viralpress_gif_react_comment_data', $data, $post_id, $uid, $url );
	
	add_filter( 'comment_flood_filter', 'vp_comment_flood_filter', 10, 3 );
	
	$ok = wp_allow_comment( $data );
	
	remove_filter( 'comment_flood_filter', 'vp_comment_flood_filter' );
	
	if( $ok === 'spam'  ) {
		$response['error'] = __( 'Failed to add comment', 'viralpress' );
		vp_output( $response );		
	}
	
	$comment_id = wp_insert_comment( $data );
	
	if( is_wp_error( $comment_id ) ) {
		$response['error'] = __( 'Failed to add comment', 'viralpress' ).' '. $comment_id->get_error_message();
		vp_output( $response );		
	}
	
	$response['comment_id'] = $comment_id;
	//$response['comment_url'] = get_comment_link( $comment_id );
	do_action( 'viralpress_gif_reacted', $post_id, $uid, $comment_id, $safe, $url );
	
	vp_output( $response );
}

function vp_open_list_submit()
{
	global $vp_instance;
	
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	$uid = get_current_user_id();
	
	if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_post'] ) {
		 $resp = recaptcha_check_answer (
						$vp_instance->settings['recap_secret'], 
						$_SERVER["REMOTE_ADDR"],
						@$_POST["recaptcha_challenge_field"],
						@$_POST["recaptcha_response_field"]
				);

		if ( ! $resp->is_valid ) {
			$response['error'] = __( 'Invalid captcha response' , 'viralpress' );
			vp_output( $response );	
		}

	}
	
	if( !$vp_instance->settings['allow_open_list'] ) {
		$response['error'] = __( 'Sorry! open list submission is disabled', 'viralpress' ); 
		vp_output( $response );	
	}
	
	array_walk_recursive( $_POST, 'vp_strip_shortcodes' );
	
	$post_id = (int)$_POST['post_id'];
	
	$post_status = get_post_status( $post_id );
	if( $post_status != 'publish' ){
		$response['error'] = __( 'This post is not published yet!' , 'viralpress' );
		vp_output( $response );	
	}
	
	$cc = get_post_meta( $post_id, 'vp_open_list' );
	$cc = @(int)$cc[0];
	
	if(empty($cc)){
		$response['error'] = __( 'Sorry, open list is closed for this post!' , 'viralpress' );
		vp_output( $response );	
	}
	
	//$sn = (int)$_POST['open_list_num'];	
	$sn = 1;
	$title = esc_html( $_POST['open_list_title'] );
	$desc = wp_kses( $_POST['open_list_desc'], $vp_instance->allow_tags );
	$source = @esc_url( $_POST['open_list_source'], array( 'http', 'https' ) );
	$in = $_POST['open_list_input'];
	
	$post = array();
	
	switch( $_POST['open_list_entry_type'] ) {
		
		case "list":
			
			if( !$vp_instance->settings['list_enabled'] ) {
				$response['error'] = __( 'List submission is not allowed currently' , 'viralpress' );
				vp_output( $response );		
			}
			
			$in = (int)$in;
			if( !wp_attachment_is_image( $in ) ) {
				$response['error'] = __( 'Valid image required for list entry' , 'viralpress' );
				vp_output( $response );		
			}
			
			$post = array(
				'post_title' => $title,
				'post_content' => $desc,
				'post_status' => 'vp_open_list_pending',
				'post_type' => 'lists',
				'custom_fields' => array(
					'vp_list_image_entry' => $in,
					'vp_source_url' => $source,
					'vp_submitted_to' => $post_id,
					'vp_show_numbers' => $sn
				)
			);
			
		break;
		
		case "news":
		
			if( !$vp_instance->settings['list_enabled'] ) {
				$response['error'] = __( 'News submission is not allowed currently' , 'viralpress' );
				vp_output( $response );		
			}
		
			if( empty( $desc ) ) {
				$response['error'] = __( 'News entries must have a description' , 'viralpress' );
				vp_output( $response );		
			}
			
			$post = array(
				'post_title' => $title,
				'post_content' => $desc,
				'post_status' => 'vp_open_list_pending',
				'post_type' => 'news',
				'custom_fields' => array(
					'vp_source_url' => $source,
					'vp_submitted_to' => $post_id,
					'vp_show_numbers' => $sn
				)
			);	
		break;
		
		case "video":
			$vid = @esc_html( $in );				
			if( empty($vid) ) {
				$response['error'] = __( 'Valid video required for this entry' , 'viralpress' );
				vp_output( $response );
			}
			else{
				$type = get_post_mime_type( $vid );
				if( !$vp_instance->settings['self_video'] ) {
					$response['error'] = __( 'Self hosted video are not supported currently' , 'viralpress' );
					vp_output( $response );		
				}
				else if( !vp_verify_video_mime( $type ) ) {
					$response['error'] = __( 'Sorry currently we do not support '.$type , 'viralpress' );
					vp_output( $response );		
				}
			}			
			$post = array(
				'post_title' => $title,
				'post_content' => $desc,
				'post_status' => 'vp_open_list_pending',
				'post_type' => 'videos',
				'custom_fields' => array(
					'vp_video_entry' => $vid,
					'vp_source_url' => $source,
					'vp_submitted_to' => $post_id,
					'vp_show_numbers' => $sn
				)
			);	
					
		break;
		
		case "audio":
			$aid = @esc_html( $in );				
			if( empty($aid) ) {
				$response['error'] = __( 'Valid audio required for this entry' , 'viralpress' );
				vp_output( $response );				}
			else{
				$type = get_post_mime_type( $aid );
				if( !$vp_instance->settings['self_audio'] ) {
					$response['error'] = __( 'Self hosted audio are not supported currently' , 'viralpress' );
					vp_output( $response );		
				}
				else if( !vp_verify_audio_mime( $type ) ) {
					$response['error'] = __( 'Sorry currently we do not support '.$type , 'viralpress' );
					vp_output( $response );		
				}
			}
			$post = array(
				'post_title' => $title,
				'post_content' => $desc,
				'post_status' => 'vp_open_list_pending',
				'post_type' => 'audio',
				'custom_fields' => array(
					'vp_audio_entry' => $aid,
					'vp_source_url' => $source,
					'vp_submitted_to' => $post_id,
					'vp_show_numbers' => $sn
				)
			);	
					
		break;
		
		case "embed":
		case "pin":
			
			if( !$vp_instance->settings['list_enabled'] ) {
				$response['error'] = __( 'Embeds are not allowed currently' , 'viralpress' );
				vp_output( $response );		
			}
			
			$eid = @esc_html( $in );				
			if( empty($eid) ) {
				$response['error'] = __( 'Valid embed required for this entry' , 'viralpress' );
				vp_output( $response );
			}
			else{
				if( !verify_embed_entry_value( $eid )){
					$response['error'] = __( 'Valid embed required for this entry' , 'viralpress' );
					vp_output( $response );	
				}
			}				
			$post = array(
				'post_title' => $title,
				'post_content' => $desc,
				'post_status' => 'vp_open_list_pending',
				'post_type' => 'pins',
				'custom_fields' => array(
					'vp_embed_entry' => $eid,
					'vp_source_url' => $source,
					'vp_submitted_to' => $post_id,
					'vp_show_numbers' => $sn
				)
			);
		break;
		
		case "gallery":
		
			if( !$vp_instance->settings['gallery_enabled'] ) {
				$response['error'] = __( 'Gallery submission is not allowed currently' , 'viralpress' );
				vp_output( $response );		
			}
			
			$gallery_type = @$_POST['gal_type'];
			$gallery_col = @(int)$_POST['gal_cols'];
			$gallery_autostart = @(int)$_POST['gal_autostart'];
				
			if( !in_array( $gallery_type, array( "thumbnail","rectangular", "square", "circle", "slideshow" ) ) ) {
				$response['error'] = __( 'Invalid gallery type selected' , 'viralpress' );
				vp_output( $response );	
			}
				
			if( empty($in ) ) {
				$response['error'] = __( 'Valid images required for this entry' , 'viralpress' );
				vp_output( $response );	
			}
							
			foreach( @$in as $img ) {
				$img_attr = wp_get_attachment_image_src( $img );
				
				if( empty( $img_attr ) ) {
					$response['error'] = __( 'Valid images required for this entry' , 'viralpress' );
					vp_output( $response );
				}	
			}
			
			$post = array(
				'post_title' => $title,
				'post_content' => $desc,
				'post_status' => 'vp_open_list_pending',
				'post_type' => 'gallery',
				'custom_fields' => array(
					'vp_list_image_entry' => implode(',', $in),
					'vp_gallery_type' => $gallery_type,
					'vp_gallery_col' => $gallery_col,
					'vp_gallery_autostart' => $gallery_autostart,
					'vp_submitted_to' => $post_id,
					'vp_show_numbers' => $sn,
					'vp_source_url' => $source,
					'vp_gallery_shortcode' => '[gallery link="post" ids="'.implode(',', $in).'" type="'.$gallery_type.'" columns="'.$gallery_col.'" autostart="'.( $gallery_autostart ? 'true' : 'false' ).'"]'
				)
			);
		break;
		
		case "playlist":
		
			if( !$vp_instance->settings['playlist_enabled'] ) {
				$response['error'] = __( 'Playlist submission is not allowed currently' , 'viralpress' );
				vp_output( $response );		
			}
				
			if( empty($in ) ) {
				$response['error'] = __( 'Valid media required for this entry' , 'viralpress' );
				vp_output( $response );	
			}
			
			$p_type = '';				
			foreach( @$in as $kk => $m ) {
				$type = get_post_mime_type( $m );
				if( vp_verify_video_mime( $type ) ) $type = 'video';
				else if( vp_verify_audio_mime( $type ) ) $type = 'audio';
				else {
					$response['error'] = __( 'One or more uploaded file type is not supported' , 'viralpress' );
					vp_output( $response );
				}
				
				if( !$kk ) $p_type = $type;
				else if( $p_type != $type ) {
					$response['error'] = __( 'Audio and video cannot be mixed in playlist.' , 'viralpress' );
					vp_output( $response );
				}
			}
			
			$post = array(
				'post_title' => $title,
				'post_content' => $desc,
				'post_status' => 'vp_open_list_pending',
				'post_type' => 'playlist',
				'custom_fields' => array(
					'vp_list_image_entry' => implode(',', $in),
					'vp_submitted_to' => $post_id,
					'vp_show_numbers' => $sn,
					'vp_source_url' => $source,
					'vp_playlist_shortcode' => '[playlist ids="'.implode( ',', $in ).'" type="'.$p_type.'"]'
				)
			);
		break;
		
		default:
			$response['error'] = __( 'Invalid post type.' , 'viralpress' );
			vp_output( $response );
			
	}
	
	if(! empty( $post ) ){
		$custom = array();
		if( !empty( $post['custom_fields'] ) ) {
			$custom = $post['custom_fields'];
			unset( $post['custom_fields'] );	
		}
		
		global $wpdb;
		
		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );
		$wpdb->query( 'SET autocommit = 0;' );
			
		if( empty($post['post_title']) && empty($post['post_content']) )$post['post_title'] = 'NO_TITLE';		
		$func = 'wp_insert_post';
		$pid = $func( $post );
			
		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query( 'COMMIT;' );
				
		if( !is_wp_error($pid) && $pid ) {
			foreach( $custom as $k => $v ) {
				add_update_post_meta( $pid, $k, $v);	
			}
			
			do_action( 'viralpress_open_list_submitted', $pid, $uid, $post_id );
		}
		else {
			$response['error'] = __( 'Post submission failed' , 'viralpress' );
			vp_output( $response );
		}
	}
	else {
		$response['error'] = __( 'Invalid entry type' , 'viralpress' );
		vp_output( $response );	
	}
	
	$response['post_id'] = $pid;
	vp_output( $response );
}

function vp_load_open_list_editor()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	global $vp_instance;
	$lang = $vp_instance->getJSLang();
	
	$post_id = (int)$_POST['post_id'];
	
	$cc = get_post_meta( $post_id, 'vp_open_list' );
	$cc = @(int)$cc[0];
	
	if(empty($cc)){
		$response['error'] = __( 'Sorry, open list is closed for this post!' , 'viralpress' );
		vp_output( $response );	
	}
	
	$t = vp_get_template_html( 'open_list_editor', array( 'lang' => $lang, 'vp_instance' => &$vp_instance, 'post' => get_post( $post_id ) ) );
	
	$response['editor'] = $t;
	vp_output( $response );
}

function vp_get_noti_count()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$response['bp_noti'] = (int)vp_bp_noti_count();
	$response['post_noti'] = (int)vp_post_noti_count();
	$response['comment_noti'] = (int)vp_comment_noti_count();
	$response['op_noti'] = (int)vp_op_noti_count();
	$response['total_noti'] = (int)(bool)$response['bp_noti'] + (int)(bool)$response['post_noti'] + (int)(bool)$response['comment_noti'] + (int)(bool)$response['op_noti'];
	
	vp_output( $response );
}

function vp_open_list_del()
{
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );
	
	$post_id = (int)$_POST['post_id'];
	
	$pauthor = get_post_field( 'post_author', $post_id );;
	$current_user = get_current_user_id();
	
	if( $pauthor != $current_user && !current_user_can( 'administrator' ) ) {
		$response['error'] = __( 'You are not allowed to delete this item!' , 'viralpress' );
		vp_output( $response );	
	} 
	
	$a = delete_open_list( $post_id );
	if( empty( $a ) ) {
		$response['error'] = __( 'Failed to delete this item!' , 'viralpress' );
		vp_output( $response );	
	} 
	
	do_action( 'viralpress_open_list_deleted', $post_id, $pauthor );
	
	vp_output( $response );
}

function vp_meme_save()
{
	global $vp_instance;
	
	$response = array();
	$response['error'] = '';
	
	validate_vp_ajax( $response );

	if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_post'] ) {
		 $resp = recaptcha_check_answer (
						$vp_instance->settings['recap_secret'], 
						$_SERVER["REMOTE_ADDR"],
						@$_POST["recaptcha_challenge_field"],
						@$_POST["recaptcha_response_field"]
				);

		if ( ! $resp->is_valid ) {
			$response['error'] = __( 'Invalid captcha response' , 'viralpress' );
			vp_output( $response );	
		}
	}
	
	if( @empty( $vp_instance->settings['meme_enabled'] ) ) {
		$response['error'] =  __( 'Sorry! Meme generator is disabled' , 'viralpress' );	
		vp_output( $response );	
	}

	$media_id = @(int)$_POST['media_id'];
	
	if( $media_id && !current_user_can( 'edit_post', $media_id ) ) {
		$response['error'] = __( 'You cannot edit this media!' , 'viralpress' );
		vp_output( $response );	
	}

	if( !current_user_can( 'edit_posts' ) ) {
		$response['error'] = __( 'You cannot add or edit posts!' , 'viralpress' );
		vp_output( $response );	
	}

	
	$new_id = media_handle_upload( 'file', $media_id );
	
	if( is_wp_error( $new_id ) ) {
		$response['error'] = $new_id->get_error_message();	
	}
	else {
		$response['media_id'] = $new_id;
		$response['media_url'] = wp_get_attachment_url( $new_id );
	}
	
	do_action( 'viralpress_meme_saved', $new_id );
	
	vp_output( $response );
}

function load_meme_modal()
{
	$media_id = (int)$_REQUEST['media_id'];
	$tb = base64_encode($_REQUEST['elem']);
	echo do_shortcode('[viralpress_meme_generator media_id="'.$media_id.'" tb="'.$tb.'"]');
	die(1);
}

?>