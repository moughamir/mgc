<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

$au_name = @get_query_var( 'author_name' );
if( !empty( $au_name ) ){
	$uid = @get_user_by( 'slug', get_query_var( 'author_name' ) )->ID;
}
else {
	$uid = get_current_user_ID();	
}
$udata = get_userdata( $uid );

$noti_count = 0;
if( $uid == get_current_user_ID() ) {
	$noti_count = (int)vp_post_noti_count();
	update_user_meta( $uid, 'vp_post_noti_count', 0 );
}

if( empty($udata) ) :
	vp_return_404();
else:
	$name = "{$udata->first_name} {$udata->last_name}";
	if( empty($name) ) $name = $udata->display_name;
	$cover = get_user_meta( $uid, 'vp_cover');
	if(!empty($cover)){
		$cover = $cover[0];
		if( is_numeric( $cover ) ) {
			$cover = wp_get_attachment_image_src( $cover, 'large' );
			$cover = $cover[0];	
		}
	}
	else $cover = $vp_instance->settings['IMG_URL'].'/default-cover-image.jpg';

	list( $fb_url, $tw_url, $gp_url ) = get_user_social_profiles( $uid );
	
	$h = 1;
	if( !empty( $attributes ) ) {
		if( @$attributes['skip_headers'] == 1 )$h = 0;
	}
	
	$iedit = 0;
	$edit = 0;
	
	if( $uid == get_current_user_ID() ) $iedit = 1;
	if( $uid == get_current_user_ID() || current_user_can( 'administrator' ) ){
		$edit = 1;
	}
	
	//if( $h ) $edit = $iedit = 0;
	if( $edit ) {
		wp_enqueue_media ();
		wp_enqueue_script( 'imgareaselect' );
		wp_register_script( 'vp-image-edit', get_admin_url().'js/image-edit.js', array(), '1' );
		wp_enqueue_script( 'vp-image-edit' );
		
		wp_enqueue_style( 'imgareaselect' );
		wp_register_style( 'viralpress-imgedit', $vp_instance->settings['CSS_URL'].'/vp-imgedit.css' , array(), '1', 'all');
		wp_enqueue_style( 'viralpress-imgedit' );	
	}
	
	wp_register_script( 'masonary', $vp_instance->settings['JS_URL'].'/masonry.pkgd.min.js', array( 'jquery' ), VP_VERSION );
	wp_enqueue_script( 'masonary' );
	
	if( $h ) get_header();
	?>
	<div class="container vp-check-container" id="container">
    	<div class="vp_container_test"></div>
		<div class="row">
			<div class="col-lg-12">
				<div class="vp-cover" style="background:url(<?php echo $cover?>);color:white;">
                	<?php if($iedit): ?>
                	<div class="vp-pull-right" style="margin:10px; color:black; font-size:30px">
                    	<i class="glyphicon glyphicon-camera vp-pointer edit-profile-images edit-cover"></i>
                    </div>
                    <?php endif;?>
					<div class="vp-avatar">
                    	<?php if($iedit): ?>
                    	<div style="position:absolute;margin-left:150px;margin-top:5px; color:black; font-size:30px">
                            <i class="glyphicon glyphicon-camera vp-pointer edit-profile-images edit-avatar"></i>
                        </div>
                        <?php endif;?>
                        <div class="vp-avatar-img">
							<?php echo get_avatar( $uid, 200 );?>
                        </div>
					</div>
                    <div class="vp-clearfix"></div>
					<div class="vp-nameplate vp-pull-left">
						<?php echo "<h2 style='font-size:24px !important;'>{$name} @{$udata->display_name}</h2>"?>
					</div>
                    <div class="vp-pull-right vp-profile-edit-btns">
                    	<?php if($iedit): ?>
                			<button class="edit-profile-images edit-avatar"><?php _e( 'Update avatar', 'viralpress' )?></button>
                    		<button class="edit-profile-images edit-cover"><?php _e( 'Update cover', 'viralpress' )?></button>
                    	<?php endif;?>
                    </div>
                    <div class="vp-profile-cf"></div>
                    <div class="vp-pull-right vp-profile-social-btns">
                    	<div class="row" style="max-width:170px">
                        	<?php if( !empty( $fb_url ) ):?>
                            <div class="col-lg-3">
                                <a target="_blank" href="<?php echo $fb_url?>"><div class="fb-share block pointer"></div></a>
                            </div>
                            <?php else:?>
                            <div class="col-lg-3"></div>
                            <?php endif;?>
                            <?php if( !empty( $tw_url ) ):?>
                            <div class="col-lg-3">
                                <a target="_blank" href="<?php echo $tw_url?>"><div class="tw-share block pointer"></div></a>
                            </div>
                            <?php else:?>
                            <div class="col-lg-3"></div>
                            <?php endif;?>
                            <?php if( !empty( $gp_url ) ):?>
                            <div class="col-lg-3">
                                <a target="_blank" href="<?php echo $gp_url?>"><div class="gp-share block pointer"></div></a>
                            </div>
                            <?php else:?>
                            <div class="col-lg-3"></div>
                            <?php endif;?>
                        </div>
                    </div>
				</div>
				<div style="margin-top:80px;clear:both"></div>
                
				<?php if( $noti_count ){?>
                <div class="alert alert-info"><?php echo sprintf( __(  'You have %d new post(s) approved since your last visit to this page', 'viralpress' ), $noti_count )?></div>
                <?php }?>
                
                <?php if( empty( $_REQUEST['show_op'] ) ) :?>
                <b><?php _e( 'Posts by ', 'viralpress' )?><?php echo "@{$udata->display_name}"?></b>
				<?php
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
					'author' => $uid,
					'post_status' => $ps,
					'orderby' => 'date',
					'order' => $order,
					'tag' => $type
				);
						
				add_filter( 'posts_where', 'vp_title_filter', 10, 2 );
				$wp_query = new WP_Query($query);
				remove_filter( 'posts_where', 'vp_title_filter', 10, 2 );
				
				display_tabular_posts( $wp_query, $paged, $uid, $edit );
				?>
                <?php endif;?>
                
                <?php if( empty( $_GET['show_p'] ) ) :?>
                <div class="vp-clearfix-lg"></div>
                <hr/>
                <div class="vp-pull-left"><b><?php _e( 'Open lists by ', 'viralpress' )?><?php echo "@{$udata->display_name}"?></b></div>
               	<?php vp_profile_screen_openlists_show( $uid, get_current_user_ID(), 1 );?>
                <?php endif;?>
			</div>
		</div>
	</div>
	<script>jQuery('.entry-title').eq(0).hide();var show_media_editor = 1;//normalize_profile_page();</script>
    <script>
		<?php if( !empty($cat) ) echo 'jQuery("#cat").val("'.(int)$cat.'");jQuery(".vp-hidden-search-from").show();'?>
		<?php if( !empty($type) ) echo 'jQuery("#tags").val("'.esc_js(htmlspecialchars_decode($type)).'");jQuery(".vp-hidden-search-from").show();'?>
		<?php if( !empty($status) ) echo 'jQuery("select[name=\'status\']").val("'.esc_js(htmlspecialchars_decode($status)).'");jQuery(".vp-hidden-search-from").show();'?>
		<?php if( !empty($q) ) echo 'jQuery("input[name=\'query\']").val("'.esc_js(htmlspecialchars_decode($q)).'");jQuery(".vp-hidden-search-from").show();'?>
	</script>
<?php endif;?>
<?php if( $h )get_footer();?>