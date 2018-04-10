<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
$vp_instance = $attributes[ 'vp_instance' ];

if( !empty($_POST['delete_vp_ad']) ) {
	if ( empty( $_REQUEST['_nonce'] ) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp-ad-action-'.get_current_user_id() ) ) {
		echo '<div class="error"><p>'. __( 'Failed to validate request. Please try again' , 'viralpress' ). '</p></div>';
	}
	else {
		$post_id = $_POST['post_id'];
		$t = get_post_type( $post_id );
		if( $t != 'vp_ads_code' ) {
			echo '<div class="error"><p>'. __( 'Selected post is not viralpress ad post' , 'viralpress' ). '</p></div>';	
		}
		else {
			wp_delete_post( $post_id );
			$myvals = get_post_meta( $post_id );
            foreach($myvals as $key=>$val)  {
                delete_post_meta($post_id, $key);
            }
			echo '<div class="updated"><p>'. __( 'Successfully deleted ad code' , 'viralpress' ). '</p></div>';	
		}
	}
}
else if( !empty($_POST['vp_save_ads']) ) {
	if ( empty( $_REQUEST['_nonce'] ) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp-ad-action-'.get_current_user_id() ) ) {
		echo '<div class="error"><p>'. __( 'Failed to validate request. Please try again' , 'viralpress' ). '</p></div>';
	}
	else {
		$ad_code = $_POST['ad_code'];
		$align = $_POST['align'];
		$margin = (int)$_POST['margin'];
		$pos = $_POST['pos'];
		$index = (int)$_POST['index'];	
		$post_id = $_POST['post_id'];
		
		if( !in_array( $align, array( 'left', 'right', 'center' ) ) ) {
			echo '<div class="error"><p>'. __( 'Invalid alignment' , 'viralpress' ). '</p></div>';	
		}
		else if( $pos == 'list' && empty($index) ) {
			echo '<div class="error"><p>'. __( 'List no required to show ads after' , 'viralpress' ). '</p></div>';	
		}
		else if( empty( $ad_code ) ) {
			echo '<div class="error"><p>'. __( 'Ad code is empty' , 'viralpress' ). '</p></div>';	
		}
		else{
			$args = array();
			if( !empty($post_id) && is_numeric($post_id) ) {
				$args['ID'] = $post_id;	
			}
			
			$args['post_title'] = 'VP_ADS';
			$args['post_content'] = $ad_code;
			$args['post_status'] = 'inherit';
			$args['post_type'] = 'vp_ads_code';
			
			$p = wp_insert_post($args);
			
			if(is_wp_error($p)){
				echo '<div class="error"><p>'.__( 'Failed to save data', 'viralpress' ).'</p></div>';	
			}
			else {
				add_update_post_meta( $p, 'vp_ad_margin', $margin );
				add_update_post_meta( $p, 'vp_ad_align', $align );
				add_update_post_meta( $p, 'vp_ad_index', $index );
				add_update_post_meta( $p, 'vp_ad_pos', $pos );
				
				echo '<div class="updated"><p>'.__( 'Ads successfully saved', 'viralpress' ).'</p></div>';	
			}
		}
	}
}

?>
<div id="wpbody-content">
    <div class="wrap" id="vp-update">
		 <h1><?php _e( 'Ad settings for viral posts', 'viralpress' )?></h1>	
         <form method="post">
			<?php wp_nonce_field( 'vp-ad-action-'.get_current_user_id(), '_nonce' ); ?>
            <input type="hidden" name="vp_save_ads" value="1"/>
            <input type="hidden" name="post_id" value="0" />
            <table class="form-table">
                <tr>
                    <th scope="row" style="width:120px">
                        <label for="add"><?php _e( 'Ads Code/HTML/Shortcode', 'viralpress' )?></label><br/>
                    </th>
                    <td style="width:250px">
                        <textarea cols="50" rows="6" name="ad_code"></textarea> 
                    </td>
                    <td valign="top">
                        <?php _e( 'Margin', 'viralpress');?>:&nbsp;&nbsp;
                        <input type="text" style="width:80px" name="margin">&nbsp;px
                        <br/><br/>
                        <?php _e( 'Alignment', 'viralpress');?>:&nbsp;&nbsp;
                        <select name="align">
                            <option value="left"><?php _e( 'Left', 'viralpress');?></option>
                            <option value="right"><?php _e( 'Right', 'viralpress');?></option>
                            <option value="center"><?php _e( 'Center', 'viralpress');?></option>	
                        </select>
                        <br/><br/>
                        <?php _e( 'Placement', 'viralpress');?>:&nbsp;&nbsp;
                        <select name="pos" class="vp_ad_pos">
                            <option value="begin"><?php _e( 'At the beginning', 'viralpress');?></option>	
                            <option value="end"><?php _e( 'At the end', 'viralpress');?></option>
                            <option value="list"><?php _e( 'After specific list number', 'viralpress');?></option>	
                        </select>
                        <input type="text" name="index" style="width:80px; display:none" placeholder="<?php _e( 'List no.', 'viralpress' )?>">
                    </td>
                </tr>
             </table>
             <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save this code', 'viralpress' )?>"  /></p>
        </form>
    </div>
</div>

<div id="wpbody-content">
    <div class="wrap" id="vp-update">
		<h1><?php _e( 'Existing ad settings', 'viralpress' )?></h1>	
        <?php
		$args = array(
			'post_type'		=>	'vp_ads_code',
			'post_status' 	=>  'inherit'
		);
		$query = new WP_Query( $args );	
		
		if( $query->have_posts() ) {
		  while( $query->have_posts() ) {
			$query->the_post();
			$meta = get_post_meta( get_the_ID() );
			$margin = @$meta[ 'vp_ad_margin' ][0];
			$align = @$meta[ 'vp_ad_align' ][0];
			$pos = @$meta[ 'vp_ad_pos' ][0];
			$index = @$meta[ 'vp_ad_index' ][0];
			?>
			<form method="post">
				<?php wp_nonce_field( 'vp-ad-action-'.get_current_user_id(), '_nonce' ); ?>
                <input type="hidden" name="vp_save_ads" value="1"/>
                <input type="hidden" name="post_id" value="<?php echo get_the_ID()?>" />
                <input type="hidden" name="delete_vp_ad" value="0" />
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:120px">
                            <label for="add"><?php _e( 'Ads Code/HTML/Shortcode', 'viralpress' )?></label><br/>
                        </th>
                        <td style="width:250px">
                            <textarea cols="50" rows="6" name="ad_code"><?php echo @get_the_content()?></textarea> 
                        </td>
                        <td valign="top">
                            <?php _e( 'Margin', 'viralpress');?>:&nbsp;&nbsp;
                            <input type="text" style="width:80px" name="margin" value="<?php echo $margin?>">&nbsp;px
                            <br/><br/>
							<?php _e( 'Alignment', 'viralpress');?>:&nbsp;&nbsp;
                            <select name="align">
                                <option value="left" <?php if( $align == 'left')echo "selected"?>><?php _e( 'Left', 'viralpress');?></option>
                                <option value="right" <?php if( $align == 'right')echo "selected"?>><?php _e( 'Right', 'viralpress');?></option>
                                <option value="center" <?php if( $align == 'center')echo "selected"?>><?php _e( 'Center', 'viralpress');?></option>	
                            </select>
                            <br/><br/>
                            <?php _e( 'Placement', 'viralpress');?>:&nbsp;&nbsp;
                            <select name="pos" class="vp_ad_pos">
                                <option value="begin" <?php if( $pos == 'begin')echo "selected"?>><?php _e( 'At the beginning', 'viralpress');?></option>	
                                <option value="end" <?php if( $pos == 'end')echo "selected"?>><?php _e( 'At the end', 'viralpress');?></option>
                                <option value="list" <?php if( $pos == 'list')echo "selected"?>><?php _e( 'After specific list number', 'viralpress');?></option>	
                            </select>
                            <input type="text" name="index" style="width:80px; display:<?php echo !empty( $index ) ? '' : "none"?>" value="<?php echo $index?>" placeholder="<?php _e( 'List no.', 'viralpress' )?>">
                        </td>
                    </tr>
                    <tr>
                    	<td>
                 			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Update this code', 'viralpress' )?>"  /></p>
                 		</td>
                        <td>
                 			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-secondary delete_vp_ad" value="<?php _e( 'Delete this code', 'viralpress' )?>"  /></p>
                 		</td>
                    </tr>
                 </table>
            </form>
            <?php
			
		  }
		}
		else {
			echo '<div>
					<p>No ad settings found</p>
				</div>';	
		}
		wp_reset_postdata();
		?>
        
    </div>
</div>