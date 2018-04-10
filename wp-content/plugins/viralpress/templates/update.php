<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
 * @modified v1.1
*/
defined( 'ABSPATH' ) || exit;
$vp_instance = $attributes[ 'vp_instance' ];
$vp_instance->plugin_update_message();
?>
<div id="wpbody-content">
    <div class="wrap" id="vp-update">
        <br/>
        <h1><?php _e( 'Viralpress Auto Update Configurations', 'viralpress' )?></h1>
        <?php
        if( !empty($_POST['vp_save_config']) ) {
          
            if ( empty( $_REQUEST['_nonce'] ) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp-admin-action-'.get_current_user_id() ) ) {
                echo '<div class="error"><p>'. __( 'Failed to validate request. Please try again' , 'viralpress' ). '</p></div>';
            }
            else {
                
				if( !empty( $_POST['envato_username'] ) && !empty( $_POST['envato_api_key'] ) && !empty( $_POST['envato_purchase_code'] ) ) {
					$r = vp_activate_license( $_POST['envato_username'], $_POST['envato_api_key'], $_POST['envato_purchase_code'] );
					if( $r === true ) {
						$envato_purchase_code = esc_html( $_POST['envato_purchase_code'] );
                		update_option( 'vp-envato-purchase-code', $envato_purchase_code );
						update_option( 'vp-license', $envato_purchase_code );
					}
					else {
						echo '<div class="error"><p>'. sprintf( __( 'Failed to install license. %s', 'viralpress' ), $r ) . '</p></div>';	
					}	
				}
				else{
					echo '<div class="error"><p>'. __( 'You need envato username, api key & license code to activate auto updates' , 'viralpress' ). '</p></div>';		
				}
				
				$envato_username = esc_html( $_POST['envato_username'] );
                update_option( 'vp-envato-username', $envato_username );
                
                $envato_api_key = esc_html( $_POST['envato_api_key'] );
                update_option( 'vp-envato-api-key', $envato_api_key );
                              
                $attributes['vp_instance']->load_settings();
                
                echo '<div class="updated"><p>'.__( 'Settings saved', 'viralpress' ).'</p></div>';
            }
        }
        $settings = $attributes['vp_instance']->settings;
    ?>
    	<?php if ( vp_check_license() == - 1 ) :?>
        <div class="error">
        	<p>
            	<?php _e( 'ViralPress license is not activated yet', 'viralpress' );?>
            </p>
        </div>
        <?php endif;?>
        
        <form method="post">
            <?php wp_nonce_field( 'vp-admin-action-'.get_current_user_id(), '_nonce' ); ?>
            <input type="hidden" name="vp_save_config" value="1"/>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="envato_username"><?php _e( 'Your Envato Username', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['envato_username']?>" id="envato_username" name="envato_username" class="regular-text"> 
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="envato_api_key"><?php _e( 'Your Envato Api Key', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['envato_api_key']?>" id="envato_api_key" name="envato_api_key" class="regular-text">
                   </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="envato_purchase_code"><?php _e( 'Your Item Purchase Code', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['envato_purchase_code']?>" id="envato_purchase_code" name="envato_purchase_code" class="regular-text">
                    </td>
                </tr>
            </table> 
            <p class="description"><?php echo sprintf( __( 'Learn %s here %s how to get an api key', 'viralpress' ), '<a href="https://b6444c9bc653c97975df133b789efcaf310304c1.googledrive.com/host/0B34QQcRSxhm2RTJrNHFqa0tzTTg/api_key.png" target="_blank">', '</a>' )?></p>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'viralpress' )?>"  /></p>   	
        </form>
    </div>
</div>