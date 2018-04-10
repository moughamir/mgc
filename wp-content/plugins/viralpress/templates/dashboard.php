<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

$vp_instance = $attributes['vp_instance'];
$uid = get_current_user_ID();
$udata = get_userdata( $uid );
$loader = '<img src="'.$vp_instance->settings['IMG_URL'].'/spinner.gif"/>';
$profile = home_url( empty( $vp_instance->settings['page_slugs']['profile'] ) ? 'profile/' : $vp_instance->settings['page_slugs']['profile'].'/' );//get_author_posts_url( $uid );
add_filter( 'get_comment_text', 'vp_format_dash_comments', 10, 3 );
list( $fb_url, $tw_url, $gp_url ) = get_user_social_profiles( $uid );

if( !empty( $_REQUEST['logout'] ) && $vp_instance->settings['disable_login'] ) {
	$uu = wp_logout_url( home_url( '/' ) );
	echo '<script>window.location.href="'.html_entity_decode($uu).'"</script>';
	exit;
}

$bp = (int)vp_bp_noti_count();
$pn = (int)vp_post_noti_count();
$cn = (int)vp_comment_noti_count();
$on = (int)vp_op_noti_count();

$no = (int)(bool)$bp + (int)(bool)$pn + (int)(bool)$cn + (int)(bool)$on;

?>
<div id="tabs" class="c-tabs no-js">
  <div class="c-tabs-nav">
    <a href="#" class="c-tabs-nav__link is-active">
		<i class="glyphicon glyphicon-home"></i>
        <span><?php _e( 'Home', 'viralpress' )?></span>
    </a>
    <a href="#" class="c-tabs-nav__link">
    	<i class="glyphicon glyphicon-user"></i>
        <span><?php _e( 'Personal Info', 'viralpress' )?></span>
    </a>
    <a href="#" class="c-tabs-nav__link">
    	<i class="glyphicon glyphicon-th"></i>
        <span><?php _e( 'Social Settings', 'viralpress' )?></span>
    </a>
    <a href="#" class="c-tabs-nav__link">
    	<i class="glyphicon glyphicon-lock"></i>
        <span><?php _e( 'Security', 'viralpress' )?></span>
    </a>
    <a href="#" class="c-tabs-nav__link">
    	<i class="glyphicon glyphicon-info-sign"></i>
        <span><?php _e( 'Account Info', 'viralpress' )?></span>
    </a>
    <a href="#" class="c-tabs-nav__link">
    	<i class="glyphicon glyphicon-wrench"></i>
        <span><?php _e( 'Manage data', 'viralpress' )?></span>
    </a>
    <a href="#" class="c-tabs-nav__link">
    	<i class="glyphicon glyphicon-globe"></i>
        <span><?php _e( 'Notifications', 'viralpress' )?> <?php if( $no ) echo '<sup style="color:brown">('.$no.')</sup>';?></span>
    </a>
  </div>
  <div class="c-tab is-active">
    <div class="c-tab__content">
    	<h3 class="text-center">
			<?php _e( 'Welcome', 'viralpress' )?> <?php echo ( $udata->first_name ? ( $udata->first_name.' '.$udata->last_name ) : $udata->display_name )?>
        </h3>
        <div class="text-center"><?php _e( 'This is your personal dashboard. Select a tab from above to view or change your account information and other settings', 'viralpress' )?></div>
    </div>
  </div>
  <div class="c-tab">
    <div class="c-tab__content">
    	<form id="udata_form">
        	<input type="hidden" name="tab" value="1" />
        	<?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>
            <h4><?php _e( 'Personal Information' , 'viralpress' ); ?></h4>
            <input type="hidden" name="action" value="vp_update_user">
            <label><?php _e( 'First name', 'viralpress' )?></label>
            <input type="text" class="vp-form-control" name="fname" value="<?php echo $udata->first_name?>">
            <label><?php _e( 'Last name', 'viralpress' )?></label>
            <input type="text" class="vp-form-control" name="lname" value="<?php echo $udata->last_name?>">
            <label><?php _e( 'Email', 'viralpress' )?></label>
            <input type="text" class="vp-form-control" name="email" value="<?php echo $udata->user_email?>">
            <!--
            <label><?php _e( 'Display name', 'viralpress' )?></label>
            <input type="text" class="vp-form-control" name="dname" value="<?php echo $udata->display_name?>">
            -->
            <br/><br/>
            <button class="btn btn-danger udata_form_btn"><?php _e( 'Save', 'viralpress' )?></button>
            <span class="vp_save_loader" style="display:none"><?php echo $loader?></span>
            <span class="vp_save_ok" style="display:none">&nbsp;&nbsp;<i class="glyphicon glyphicon-ok"></i></span>
        </form>
    </div>
  </div>
   <div class="c-tab">
    <div class="c-tab__content">
    <form id="udata_c_form">
    	<input type="hidden" name="tab" value="2" />
        <?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>
        <h4><?php _e( 'Social Ids' , 'viralpress' ); ?></h4>
        <input type="hidden" name="action" value="vp_c_update_user">
        <label><?php _e( 'Facebook profile', 'viralpress' )?></label>
        <input type="text" class="vp-form-control" name="fb_url" value="<?php echo $fb_url?>">
        <label><?php _e( 'Twitter Profile', 'viralpress' )?></label>
        <input type="text" class="vp-form-control" name="tw_url" value="<?php echo $tw_url?>">
        <label><?php _e( 'Google Profile', 'viralpress' )?></label>
        <input type="text" class="vp-form-control" name="gp_url" value="<?php echo $gp_url?>"><br/><br/>
        <button class="btn btn-danger udata_c_form_btn"><?php _e( 'Save', 'viralpress' )?></button>
        <span class="vp_c_save_loader" style="display:none"><?php echo $loader?></span>
        <span class="vp_c_save_ok" style="display:none">&nbsp;&nbsp;<i class="glyphicon glyphicon-ok"></i></span>
    </form>
    </div>
  </div>
   <div class="c-tab">
    <div class="c-tab__content">
    	<form id="udata_s_form">
        	<input type="hidden" name="tab" value="3" />
        	<?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>
            <h4><?php _e( 'Account Security' , 'viralpress' ); ?></h4>
            <input type="hidden" name="action" value="vp_s_update_user">
            <label><?php _e( 'New password', 'viralpress' )?></label>
            <input type="password" class="vp-form-control" name="pwd">
            <label><?php _e( 'Repeat password', 'viralpress' )?></label>
            <input type="password" class="vp-form-control" name="pwd2"><br/><br/>
            <button class="btn btn-danger udata_s_form_btn"><?php _e( 'Save', 'viralpress' )?></button>
            <span class="vp_s_save_loader" style="display:none"><?php echo $loader?></span>
            <span class="vp_s_save_ok" style="display:none">&nbsp;&nbsp;<i class="glyphicon glyphicon-ok"></i></span>
        </form>
    </div>
  </div>
   <div class="c-tab">
    <div class="c-tab__content">
    	<h4><?php _e( 'Account Information' , 'viralpress' ); ?></h4>
        <div class="row">
            <div class="col-lg-6">
            	<label><?php _e( 'Username', 'viralpress' );echo '<br/><b>'.$udata->user_login.'</b>'?></label><br/>
            	<label><?php _e( 'Display name', 'viralpress' );echo '<br/><b>'.$udata->display_name.'</b>'?></label><br/>
                <label><?php _e( 'Member since', 'viralpress' );echo '<br/><b>'.@date( 'M Y', strtotime( $udata->user_registered ) ).'</b>'?></label><br/>
            </div>
            <div class="col-lg-6">
           		<label><?php _e( 'Total post', 'viralpress' );?> <br/><span class="label label-info"><?php echo count_user_posts($uid)?></span></label><br/>
            	<label><?php _e( 'Total open list', 'viralpress' );?> <br/><span class="label label-info"><?php echo vp_count_all_open_list($uid)?></span></label><br/>
                <label><?php _e( 'Total comments', 'viralpress' );?> <br/><span class="label label-info"><?php echo vp_comment_count($udata->user_email)?></span></label><br/>
            </div>
        </div>
    </div>
  </div>
   <div class="c-tab">
    <div class="c-tab__content">
    	 <h4><?php _e( 'Manage Data' , 'viralpress' ); ?></h4>
        <button class="btn btn-info vp-dash-media"><i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<?php _e( 'Manage your media files', 'viralpress' )?></button>
        <a class="btn btn-primary" style="color:white" href="<?php echo $profile?>"><i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;<?php _e( 'View & manage your posts', 'viralpress' )?></a>
        
        <a class="btn btn-info" href="<?php echo home_url( '/post-comments' )?>"><i class="glyphicon glyphicon-share"></i>&nbsp;&nbsp;<?php _e( 'View all comments on your posts', 'viralpress' )?></a>
        <a class="btn btn-primary" href="<?php echo home_url( '/my-comments' )?>"><i class="glyphicon glyphicon-share"></i>&nbsp;&nbsp;<?php _e( 'View all comments by you', 'viralpress' )?></a>
        
    </div>
  </div>
   <div class="c-tab">
    <div class="c-tab__content">
    	<ul class="vp-dash-noti">
    	<?php
		
		if( $bp && function_exists( 'bp_core_get_userlink' ) )echo '<li>'.sprintf( __( 'You have %s buddpress notifications. View %s here %s', 'viralpress' ), $bp, '<a href="'.bp_core_get_userlink( bp_loggedin_user_id(), false, true ).'notifications/">', '</a>' ).'</li>';
		
		if( $pn )echo '<li>'.sprintf( __( 'You have %d new post approval. View %s here %s', 'viralpress' ), $pn, '<a href="'.$profile.'">', '</a>' ).'</li>';
		
		if( $cn )echo '<li>'.sprintf( __( 'You have %d new comments notification. View your post comments %s here %s and replies to your comments %s here %s ', 'viralpress' ), $cn, '<a href="'.home_url( '/post-comments' ).'">', '</a>', '<a href="'.home_url( '/my-comments' ).'">', '</a>' ).'</li>';
		
		if( $on )echo '<li>'.sprintf( __( 'You have %d new open lists approved. View %s here %s', 'viralpress' ), $on, '<a href="'.$profile.'">', '</a>' ).'</li>';
		
		if( !$no ) echo '<li>'.__( 'No new notification.', 'viralpress' ).'</li>';
		?>
        </ul>
    </div>
  </div>
</div>

<script>
	jQuery('.entry-title').eq(0).hide();
	jQuery( document ).ready( function( $ ){
		var vp_dash_tab = vp_tabs({
			el: '#tabs',
			tabNavigationLinks: '.c-tabs-nav__link',
			tabContentContainers: '.c-tab'
		  });
		vp_dash_tab.init();
		<?php if( isset( $_REQUEST['tab'] ) )echo 'vp_dash_tab.goToTab('.(int)$_REQUEST['tab'].')';?>
	});
</script>