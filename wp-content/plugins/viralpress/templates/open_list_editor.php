<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

$lang = $attributes['lang'];
$vp_instance = $attributes['vp_instance'];
$post = $attributes['post'];
$type = get_post_meta( $post->ID, 'vp_post_type' );
$type = @$type[0];

$hide_tab = 0;

$tab = array();
$nav = array();

$tab['image'] = ' <li class="vp-op-list"><h3>'.__( 'Submit an image to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
$tab['news'] = ' <li class="vp-op-news"><h3>'.__( 'Submit a news to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
$tab['embed'] = ' <li class="vp-op-embed"><h3>'.__( 'Embed URL to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
$tab['gallery'] = ' <li class="vp-op-gallery"><h3>'.__( 'Submit a gallery to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
$tab['video'] = ' <li class="vp-op-video"><h3>'.__( 'Submit a video to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
$tab['audio'] = ' <li class="vp-op-audio"><h3>'.__( 'Submit an audio to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
$tab['playlist'] = ' <li class="vp-op-playlist"><h3>'.__( 'Submit a playlist to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
$tab['meme'] = ' <li class="vp-op-meme"><h3>'.__( 'Create a new meme', 'viralpress' ).'</h3><div class="op_editor"></div></li>';

$nav['image'] = '<li class="vp-op-list">'.__( 'Image', 'viralpress' ).'</li>';
$nav['news'] = '<li class="vp-op-news">'.__( 'News', 'viralpress' ).'</li>';
$nav['embed'] = '<li class="vp-op-embed">'.__( 'Embed', 'viralpress' ).'</li>';
$nav['gallery'] = '<li class="vp-op-gallery">'.__( 'Gallery', 'viralpress' ).'</li>';
$nav['video'] = '<li class="vp-op-video">'.__( 'Video', 'viralpress' ).'</li>';
$nav['audio'] = '<li class="vp-op-audio">'.__( 'Audio', 'viralpress' ).'</li>';
$nav['playlist'] = '<li class="vp-op-playlist">'.__( 'Playlist', 'viralpress' ).'</li>';
$nav['meme'] = '<li class="vp-op-meme add_new_meme_gen open_list">'.__('Meme', 'viralpress').'</button>';


if( !$vp_instance->settings['list_enabled'] ) {
	unset( $tab['image'] );
	unset( $tab['news'] );
	unset( $tab['embed'] );
	unset( $tab['meme'] );
	
	unset( $nav['image'] );
	unset( $nav['news'] );
	unset( $nav['embed'] );
	unset( $nav['meme'] );	
}

if( !$vp_instance->settings['image_enabled'] ) {
	unset( $tab['image'] );
	unset( $tab['meme'] );
	
	unset( $nav['image'] );
	unset( $nav['meme'] );
}

if( !$vp_instance->settings['meme_enabled'] ) {
	unset( $tab['meme'] );
	
	unset( $nav['meme'] );
}

if( !$vp_instance->settings['news_enabled'] ) {
	unset( $tab['news'] );
	
	unset( $nav['news'] );
}

if( !$vp_instance->settings['embed_enabled'] ) {
	unset( $tab['embed'] );
	
	unset( $nav['embed'] );
}

if( !$vp_instance->settings['playlist_enabled'] ) {
	unset( $tab['playlist'] );
	
	unset( $nav['playlist'] );
}

if( !$vp_instance->settings['gallery_enabled'] ) {
	unset( $tab['gallery'] );
	
	unset( $nav['gallery'] );
}

if( $vp_instance->settings['audio_enabled'] ) {
	
	if( !$vp_instance->settings['self_audio'] ) {
		$hide_tab = 1;
		unset( $tab['audio'] );
		
		unset( $nav['audio'] );
	}
	
	if( !$vp_instance->settings['embed_enabled'] ) {
		$hide_tab = 1;
		unset( $tab['embed'] );
		
		unset( $nav['embed'] );
	}
}
else {
	unset( $tab['audio'] );
	unset( $nav['audio'] );	
	unset( $tab['embed'] );
	unset( $nav['embed'] );	
}

if( $vp_instance->settings['video_enabled'] ) {
	if( !$vp_instance->settings['self_video'] ) {
		$hide_tab = 1;
		unset( $tab['video'] );
		
		unset( $nav['video'] );
	}
	
	if( !$vp_instance->settings['embed_enabled'] ) {
		$hide_tab = 1;
		unset( $tab['embed'] );
		
		unset( $nav['embed'] );
	}
}
else {
	unset( $tab['video'] );
	unset( $nav['video'] );	
	unset( $tab['embed'] );
	unset( $nav['embed'] );		
}

if( $type == 'news' ) {
	$tt = @$tab['image'];
	$nn = @$nav['image'];
	$tab['image'] = @$tab['news'];
	$nav['image'] = @$nav['news'];
	$tab['news'] = @$tt;
	$nav['news'] = @$nn;
}
else if( $type == 'image' || $type == 'images' ) {
	
	if( !empty( $nav['image'] ) ) $nav['image'] = '<li class="vp-op-list">'.__( 'Upload an image', 'viralpress' ).'</li>';
	if( !empty( $nav['meme'] ) ) $nav['meme'] = '<li class="vp-op-meme add_new_meme_gen open_list">'.__( 'Create a meme', 'viralpress' ).'</li>';
	
	$nav = array( @$nav['image'], @$nav['meme'] );
	$tab = array( @$tab['image'], @$tab['meme'] );
}
else if( $type == 'meme' ) {
	
	if( !empty( $tab['image'] ) ) $tab['image'] = ' <li class="vp-op-list"><h3>'.__( 'Submit a meme to this list', 'viralpress' ).'</h3><div class="op_editor"></div></li>';
	
	if( !empty( $nav['image'] ) ) $nav['image'] = '<li class="vp-op-list">'.__( 'Upload existing meme', 'viralpress' ).'</li>';
	if( !empty( $nav['meme'] ) ) $nav['meme'] = '<li class="vp-op-meme add_new_meme_gen open_list">'.__( 'Create a new meme', 'viralpress' ).'</li>';
	
	$nav = array( @$nav['image'], @$nav['meme'] );
	$tab = array( @$tab['image'], @$tab['meme'] );
}
else if( $type == 'gallery' ) {
	$new_tab = @$tab['gallery'];
	$new_nav = @$nav['gallery'];
	
	$nav = array($new_nav);
	$tab = array($new_tab);
}
else if( $type == 'playlist' ) {
	$new_tab = @$tab['playlist'];
	$new_nav = @$nav['playlist'];
	
	$nav = array($new_nav);
	$tab = array($new_tab);
}
else if( $type == 'audio' ) {
	
	if( !empty( $nav['audio'] ) )$nav['audio'] = '<li class="vp-op-audio">'.__( 'Upload audio', 'viralpress' ).'</li>';
	if( !empty( $nav['embed'] ) )$nav['embed'] = '<li class="vp-op-embed">'.__( 'Embed from sites', 'viralpress' ).'</li>';
	
	$nav = array( @$nav['audio'], @$nav['embed'] );
	$tab = array( @$tab['audio'], @$tab['embed'] );
}
else if( $type == 'video' || $type == 'videos' ) {
	
	if( !empty( $nav['video'] ) )$nav['video'] = '<li class="vp-op-video">'.__( 'Upload video', 'viralpress' ).'</li>';
	if( !empty( $nav['embed'] ) )$nav['embed'] = '<li class="vp-op-embed">'.__( 'Embed from sites', 'viralpress' ).'</li>';
	
	$nav = array( @$nav['video'], @$nav['embed'] );
	$tab = array( @$tab['video'], @$tab['embed'] );
}

$nav = array_filter( $nav );
$tab = array_filter( $tab );

if( count( $nav ) <= 1 ) $hide_tab = 1; 
else if( count( $nav ) > 7 ) echo '<style>ul#vp-tabs li, ul#vp-tabs2 li{padding: 5px 15px !important}</style>';
?>
<div class="vp-clearfix-lg"></div>
<form id="open_list_form">
<?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>

<ul id="vp-tab">
	<?php echo implode($tab);?>
</ul>
<div class="vp-clearfix"></div>

<div class="alert alert-info open_list_form_feedback" style="display:none"></div>
<input type="hidden" name="post_id" value="<?php echo $post->ID?>"/>
<input type="hidden" name="action" value="vp_open_list_submit"/>
</form>

<ul id="vp-tabs" style="display:<?php if( $hide_tab ) echo "none"?>">
	<?php echo implode($nav);?>
</ul>

<?php echo vp_get_template_html('modals')?>