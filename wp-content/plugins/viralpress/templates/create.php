<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

$exit = 0;
$is_copying = 0;
$edit_post_id = 0;
if( !empty($_GET['id']) ):
	$pid = (int)$_GET['id'];
	if ( !current_user_can( 'edit_post', $pid ) ) {
		if( empty( $_REQUEST['copy'] ) || empty( $vp_instance->settings['allow_copy'] ) ) {
			$exit = 1;	
		}
		else if( !empty( $_REQUEST['copy'] ) ) {
			$cc = get_post_meta( $pid, 'vp_allow_copy' );
			$cc = @(int)$cc[0];
			
			if( empty( $cc ) ) $exit = 1;	
		}
		if( $exit ) {
			echo '<script>window.location.href="'. home_url( '/' ) .'"</script>';
			exit;
		}
	}
		
	if( $exit ) {
		echo '<script>window.location.href="'. home_url( '/' ) .'"</script>';
		exit;
	}
	if( !empty( $_REQUEST['copy'] ) ) $is_copying = 1;
endif;

if( $exit ) {
	echo '<script>window.location.href="'. home_url( '/' ) .'"</script>';
	exit;
}

if( !empty( $pid ) ) {
	$edit_post_id = $pid;
	$t = get_post_meta( $edit_post_id, 'vp_post_type' );
	if( !empty($t) ){
		$attributes['post_type'] = $t[0];
	}	
}


$error = '';
$error_selectors = array();
$error_selectors_msg = array();
$success = '';
$show_editor = 1;
$message = '';

if( in_array( $attributes['post_type'] , array( 'list' ) ) && !$attributes['vp_instance']->settings['list_enabled'] ) {
	$message = __( 'Sorry! List item submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'image', 'images' ) ) && !$attributes['vp_instance']->settings['image_enabled'] ) {
	$message = __( 'Sorry! Image submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'meme' ) ) && !$attributes['vp_instance']->settings['meme_enabled'] ) {
	$message = __( 'Sorry! Meme submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'news' ) ) && !$attributes['vp_instance']->settings['news_enabled'] ) {
	$message = __( 'Sorry! News submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'quiz' ) ) && !$attributes['vp_instance']->settings['quiz_enabled'] ) {
	$message = __( 'Sorry! Quiz submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'video', 'videos' ) ) && !$attributes['vp_instance']->settings['video_enabled'] ) {
	$message = __( 'Sorry! Video submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'audio' ) ) && !$attributes['vp_instance']->settings['audio_enabled'] ) {
	$message = __( 'Sorry! Audio submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'poll', 'polls' ) ) && !$attributes['vp_instance']->settings['poll_enabled'] ) {
	$message = __( 'Sorry! Poll submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'playlist', 'playlists' ) ) && !$attributes['vp_instance']->settings['playlist_enabled'] ) {
	$message = __( 'Sorry! Playlist submission is disabled', 'viralpress' ); 	
}
else if( in_array( $attributes['post_type'] , array( 'gallery' ) ) && !$attributes['vp_instance']->settings['gallery_enabled'] ) {
	$message = __( 'Sorry! Gallery submission is disabled', 'viralpress' ); 	
}

if( !empty($_POST['vp_add_new_post']) && empty( $message ) ){
	
	$vp_post = new vp_post();
	$vp_post->add_post();
	
	if( !empty($vp_post->error) ){
		$error = $vp_post->error;	
		$error_selectors = $vp_post->error_selectors;
		$error_selectors_msg = $vp_post->error_selectors_msg;
	}
	else{
		$success = $vp_post->post_link;	
		$message = $vp_post->message;		
	}
}

if( !empty($success) ) {
	echo 
		'<script>window.location.href = "'.home_url( $attributes->settings['page_slugs']['create'] ).'?msg='.base64_encode($message.' '.__( 'View the preview', 'viralpress' ).' <a href="'.$success.'">'.__( 'here', 'viralpress' ).'</a>').'";</script>';
		exit();
}

if( !empty($_GET['msg']) ){
	echo
	'<div class="alert alert-info">
		'.wp_kses( base64_decode(str_replace(' ', '+', $_GET['msg']) ), array( 'a' => array('href' => array()) ) ).'</a>
	</div>
	<br/><br/>';
	$show_editor = 0;
}

if( !empty($message) ){
	echo
	'<div class="alert alert-info">
		'.$message.'
	</div>
	<br/><br/>';
	$show_editor = 0;
}

if( !empty($_GET['delete']) && $edit_post_id && $show_editor ){
	if( wp_verify_nonce( $_GET['_nonce'], 'delete-post_'.$edit_post_id) ) {
		echo '<div class="alert alert-info">
		      	'.__( 'Are you sure to delete this? ', 'viralpress' ).'&nbsp;
		      	<a href="javascript:void(0)" class="cancel_delete_post_l">'.__( 'No ', 'viralpress' ).'</a>&nbsp;&nbsp;
		      	<a href="javascript:void(0)" class="delete_post_l">'.__( 'Yes ', 'viralpress' ).'</a>
			  </div>';
	}
	else {
		echo '<div class="alert alert-danger">'.__( 'Failed to validate request. Please delete using the form below.', 'viralpress' ).'</div>';
	}
}

if( $show_editor ){
	
	$attributes['edit_post_id'] = $edit_post_id;
	$attributes['is_copying'] = $is_copying;
	
	echo vp_get_template_html( 'editor', $attributes );
	
	if( !empty($error) ){
		
		$vp_post->render_editor( $attributes );	
		$script = $vp_post->render_html;
		echo $script;
		
		$vp_post->render_errors( $error, $error_selectors, $error_selectors_msg );
		$script = $vp_post->render_html;
		echo $script;
	}
	else if( $edit_post_id ) {
		if( empty( $vp_post ) )$vp_post = new vp_post();
		$vp_post->render_post_editor( $edit_post_id, $is_copying );
		$script = $vp_post->render_html;
		echo $script;
	}
}

?>