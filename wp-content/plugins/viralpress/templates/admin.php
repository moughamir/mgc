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

$tab = @$_REQUEST['tab'];
if( !in_array( $tab, array( 'general', 'social', 'captcha', 'modules', 'editor', 'demo', 'gif', 'embed', 'page' ) ) ) {
	$tab = 'general';	
}

if ( isset( $_REQUEST['install_demo'] ) ) {
	$ins = get_option( 'vp-demo-installed', -1 );
	$ok = 1; 
	if( $ins == 1 || $ins == -1 ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Menu and categories already installed', 'viralpress' ).'</p></div>';
	}
	else if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp_install_demo' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->register_categories();
		$vp_instance->load_page_definitions();
		$vp_instance->create_menus();	
		echo '<div class="updated"><p>'.__( 'Menu and categories successfully installed. Please activate ViralPress login and menu using the form below.', 'viralpress' ).'</p></div>';
	}	
}	
else if ( isset( $_REQUEST['install_menu'] ) ) {
	$ok = 1;
	if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'install_menu' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->load_page_definitions();
		$vp_instance->create_menus();	
		echo '<div class="updated"><p>'.__( 'Menu successfully installed. Please activate ViralPress login and menu using the form below.', 'viralpress' ).'</p></div>';
	}
}
else if ( isset( $_REQUEST['delete_menu'] ) ) {
	$ok = 1;
	if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'delete_menu' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->load_page_definitions();
		$vp_instance->delete_menus();	
		echo '<div class="updated"><p>'.__( 'Menu successfully deleted.', 'viralpress' ).'</p></div>';
	}
}
else if ( isset( $_REQUEST['install_cat'] ) ) {
	$ok = 1;
	if( !isset($_REQUEST['_nonce']) || !wp_verify_nonce( $_REQUEST['_nonce'], 'install_cat' ) ) {
		$ok = 0;
		echo '<div class="error"><p>'.__( 'Failed to validate request. Please try later', 'viralpress' ).'</p></div>';
	}	
	
	if( $ok ) {
		$vp_instance->register_categories();
		echo '<div class="updated"><p>'.__( 'Categories successfully installed.', 'viralpress' ).'</p></div>';
	}
}

$ins = get_option( 'vp-demo-installed', -1 );
?>
<div id="wpbody-content">
	<?php if( empty( $_REQUEST['tab'] ) ):?>
    <div id="welcome-panel" class="welcome-panel">
        <div class="welcome-panel-content">
            <h2><?php _e( 'Welcome to ViralPress!', 'viralpress' )?></h2>
            <p class="about-description"><?php _e( 'Turn your website into a viral content sharing platform!', 'viralpress' )?></p>
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column" style="width:33% !important">
                    <h3><?php _e( 'Get Started', 'viralpress' )?></h3>
                    <?php if( $ins == 0 ):?>
                    <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'vp_install_demo', '_nonce' );?>"> 
                    <button class="button button-primary button-hero">
                        <?php _e( 'Install Categories & Menu', 'viralpress' )?>
                    </button>
                    <input type="hidden" name="install_demo" value="1" />
                    </form>
                    <?php else:?>
                    <a class="button button-primary button-hero" href="#vp-config">
                        <?php _e( 'Configure ViralPress', 'viralpress' )?>
                    </a>
                    <?php endif;?>
                    <ul>
                        <li>
                            <a href="options-permalink.php" class="welcome-icon welcome-write-blog">
                                <?php _e( 'Update permalink structure', 'viralpress' )?>
                            </a>
                        </li>
                        <li>
                            <div class="welcome-icon welcome-widgets-menus">
                                <?php _e( 'Update', 'viralpress' )?> 
                                <a href="widgets.php"><?php _e( 'widgets', 'viralpress' )?></a>
                                <?php _e( 'or', 'viralpress' )?> <a href="nav-menus.php"><?php _e( 'menus', 'viralpress' )?>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="welcome-panel-column" style="width:33% !important">
                    <h3><?php _e( 'Next Steps', 'viralpress' )?></h3>
                    <?php if( $ins == 0 ):?>
                    <a class="button button-secondary button-hero" href="#vp-config">
                        <?php _e( 'Configure ViralPress', 'viralpress' )?>
                    </a>
					<?php else:?>
                    <a class="button button-secondary button-hero" href="https://drive.google.com/file/d/0B34QQcRSxhm2Sm9rOVE2MW9MWDQ/view" target="_blank">
                        <?php _e( 'Read the documentation', 'viralpress' )?>
                    </a>
					<?php endif;?>
                    <ul>
                        <li>
                            <a href="<?php echo home_url( '/create/' ).'?type=news'?>" class="welcome-icon welcome-write-blog">
                                <?php _e( 'Write your first viral post', 'viralpress' )?>
                            </a>
                        </li>
                        <li><a href="options-discussion.php" class="welcome-icon welcome-comments">Turn comments on or off</a></li>
                    </ul>
                </div>
                <div class="welcome-panel-column" style="width:33% !important">
                    <h3><?php _e( 'More Actions', 'viralpress' )?></h3>
                    <?php if( $ins == 0 ) : ?>
                    <a class="button button-primary button-hero" href="https://drive.google.com/file/d/0B34QQcRSxhm2Sm9rOVE2MW9MWDQ/view" target="_blank">
                        <?php _e( 'Read the documentation', 'viralpress' )?>
                    </a>
               		<?php else:?>
                    <a class="button button-primary button-hero" href="http://codecanyon.net/user/inspireddev/portfolio?ref=inspireddev" target="_blank">
                        <?php _e( 'Visit the item page', 'viralpress' )?>
                    </a>
                    <?php endif;?>
                     <ul>
                        <li><a href="<?php echo home_url( '/' )?>" class="welcome-icon welcome-view-site"><?php _e( 'View your site', 'viralpress' )?></a></li>
                        <li><a href="theme.php" class="welcome-icon welcome-view-site"><?php _e( 'Update theme', 'viralpress' )?></a></li>
                    </ul>
                </div>
                <!--
                <div class="welcome-panel-column" style="width:25% !important">
                    <h3><?php _e( 'Link to viral editor', 'viralpress' )?></h3>
                    <br/>    
                    <div style="float:left">                
                     	<ul>
                        <li><a href="<?php echo home_url( '/create?type=news' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create news', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=list' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create list', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=poll' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create poll', 'viralpress' )?></a></li>
                    	</ul>
                    </div>
                    <div>
                    	<ul>                          
                        <li><a href="<?php echo home_url( '/create?type=quiz' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create quiz', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=video' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create video', 'viralpress' )?></a></li>
                        <li><a href="<?php echo home_url( '/create?type=gallery' )?>" class="welcome-icon welcome-write-blog"><?php _e( 'Create gallery', 'viralpress' )?></a></li>
                        </ul>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
    
    <?php endif;?>
    
    <div class="wrap" id="vp-config">
    	<?php if ( vp_check_license() == - 1 ) :?>
        <div class="error">
        	<p>
            	<?php echo sprintf( __( 'ViralPress license is not activated yet. Activate it %s here %s', 'viralpress' ), '<a href="' . admin_url( 'admin.php?page=viralpress-update' ) . '">', '</a>' );?>
            </p>
        </div>
        <?php endif;?>
        <br/>
        <h1><?php _e( 'Viralpress Configurations', 'viralpress' )?></h1>
        <?php
        if( !empty($_POST['vp_save_config']) ) {
          
            if ( empty( $_REQUEST['_nonce'] ) || !wp_verify_nonce( $_REQUEST['_nonce'], 'vp-admin-action-'.get_current_user_id() ) ) {
                echo '<div class="error"><p>'. __( 'Failed to validate request. Please try again' , 'viralpress' ). '</p></div>';
            }
            else {
				if( $tab == 'general' ) {
					$data = array();
					$data['auto_publish'] = @(int)$_POST['auto_publish'];
					$data['custom_profiles'] = @(int)$_POST['custom_profiles'];	
					$data['show_menu'] = @(int)$_POST['show_menu'];	
					$data['show_menu_on'] = @$_POST['show_menu_on'];	
					if( !in_array( $data['show_menu_on'], array( 'both', 'primary', 'secondary' ) ) ) $data['show_menu_on'] = 'both';
					$data['block_admin'] = @(int)$_POST['block_admin'];	
					$data['block_edits'] = @(int)$_POST['block_edits'];	
					$data['use_category'] = @(int)$_POST['use_category'];	
					$data['show_reactions'] = @(int)$_POST['show_reactions'];
					$data['show_gif_reactions'] = @(int)$_POST['show_gif_reactions'];
					$data['show_gif_reactions_upload'] = @(int)$_POST['show_gif_reactions_upload'];	
					$data['hide_vote_buttons'] = @(int)$_POST['hide_vote_buttons'];
					$data['hide_vote_buttons_op'] = @(int)$_POST['hide_vote_buttons_op'];
					$data['sort_op_vote'] = @(int)$_POST['sort_op_vote'];
					$data['show_like_dislike'] = @(int)$_POST['show_like_dislike'];	
					$data['anon_votes'] = @(int)$_POST['anon_votes'];	
					$data['share_quiz_force'] = @(int)$_POST['share_quiz_force'];	
					$data['hotlink_image'] = @(int)$_POST['hotlink_image'];	
					
					vp_admin_config_save( $data );
				}
				
				else if( $tab == 'social' ) {
					
					$data = array();
					
					$data['fb_app_id'] = esc_html( $_POST['fb_app_id'] );
					$data['google_api_key'] = esc_html( $_POST['google_api_key'] );
					$data['google_oauth_id'] = esc_html( $_POST['google_oauth_id'] );
					$data['fb_comments'] = @(int)$_POST['fb_comments'];	
					$data['share_buttons'] = @(int)$_POST['share_buttons'];	
                	$data['comments_per_list'] = @(int)$_POST['comments_per_list'];	
                	
					vp_admin_config_save( $data );
				}
				
				else if( $tab == 'editor' ) {
				
					$data = array();
				
					$data['only_admin'] = @(int)$_POST['only_admin'];	
					$data['allow_copy'] = @(int)$_POST['allow_copy'];	
					$data['allow_open_list'] = @(int)$_POST['allow_open_list'];	
					
					vp_admin_config_save( $data );
				
				}
				
				else if( $tab == 'captcha' ) {	
					
					$data = array();
							
					$data['recap_key'] = @$_POST['recap_key'];	
					$data['recap_secret'] = @$_POST['recap_secret'];	
					$data['recap_login'] = @(int)$_POST['recap_login'];	
					$data['recap_post'] = @(int)$_POST['recap_post'];	
					
					vp_admin_config_save( $data );
				}
				
				else if( $tab == 'modules' ) {		
				
					$data = array();
				
					$data['news_enabled'] = @(int)$_POST['news_enabled'];	
					$data['embed_enabled'] = @(int)$_POST['embed_enabled'];
					$data['meme_enabled'] = @(int)$_POST['meme_enabled'];
					$data['image_enabled'] = @(int)$_POST['image_enabled'];	
					$data['video_enabled'] = @(int)$_POST['video_enabled'];	
					$data['audio_enabled'] = @(int)$_POST['audio_enabled'];	
					$data['list_enabled'] = @(int)$_POST['list_enabled'];	
					$data['quiz_enabled'] = @(int)$_POST['quiz_enabled'];	
					$data['poll_enabled'] = @(int)$_POST['poll_enabled'];	
					$data['disable_login'] = @!(int)$_POST['disable_login'];
					$data['wsl_int'] = @(int)$_POST['wsl_int'];
					$data['vp_bp'] = @(int)$_POST['vp_bp'];	
					$data['vp_mycred'] = @(int)$_POST['vp_mycred'];	
					$data['self_video'] = @(int)$_POST['self_video'];	
					$data['self_audio'] = @(int)$_POST['self_audio'];	
					$data['gallery_enabled'] = @(int)$_POST['gallery_enabled'];	
					$data['playlist_enabled'] = @(int)$_POST['playlist_enabled'];
					
					vp_admin_config_save( $data );	
				}
				
				else if( $tab == 'embed' ) {
					
					$data = array();
					$data['allowed_embeds'] = @$_POST['allowed_embeds'];
					
					vp_admin_config_save( $data );
				}
				
				else if( $tab == 'gif' ) {
					$gifs = $_POST['react_gif']['url'];
					if( empty( $gifs ) ) {
						update_option( 'vp-react-gifs', '' );	
					}
					else {
						$gifs_new = array();
						foreach( $gifs as $i => $b ) {
							if( !empty( $b ) ) {
								$arr = array();
								$arr['url']	= esc_url( $b, array( 'http', 'https' ) );;
								$arr['caption']	= esc_html( $_POST['react_gif']['caption'][$i] );
								$arr['static']	= esc_url( $_POST['react_gif']['static'][$i], array( 'http', 'https' ) );
								
								$gifs_new[] = $arr;
							}	
						}
						update_option( 'vp-react-gifs', json_encode( $gifs_new ) );	
					}
				}
				
				else if( $tab == 'page' ) {
					
					$save = 0;
					$p = array();
					
					foreach( $_POST['vp_page'] as $i => $s ) {
						
						if( @$vp_instance->settings['page_slugs'][$i] == $s ) {
							$p[$i] = $s;
 							continue;
						}
						
						$page = get_page_by_path( $s );
						
						if ( empty($page) ) {
							$error = sprintf( __( 'Page by slug %s does not exist', 'viralpress' ), '"'.$s.'"' );
							break;
						}
						
						if ( !empty($page->post_content) ) {
							$error = sprintf( __( 'Page by slug %s is not empty. ViralPress needs empty page to save shortcode. You can add your content to the pages later. If you want to use that page anyway delete all old contents from the page and try again.', 'viralpress' ), '"'.$s.'"' );
							break;
						}
						
						if( $i == 'profile' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[viralpress_profile_page]'  ) );
						}
						else if( $i == 'login' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[viralpress_login_page]'  ) );
						}
						else if( $i == 'register' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[viralpress_registration_page]'  ) );
						}
						else if( $i == 'password-lost' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[vp_password_lost_form]'  ) );
						}
						else if( $i == 'password-reset' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[vp_password_reset_form]'  ) );
						}
						else if( $i == 'create' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[viralpress_user_create_entry]'  ) );
						}
						else if( $i == 'dashboard' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[viralpress_user_dashboard]'  ) );
						}
						else if( $i == 'meme-generator' ) {
							wp_update_post( array( 'ID' => $page->ID, 'post_content' => '[viralpress_meme_generator]'  ) );
						}
						else {
							$error = sprintf( __( 'Unrecognized page type %s', 'viralpress' ), $i );
							break;
						}
						
						$save = 1;
						$p[$i] = $s;
						
					}
					
					if( empty( $error ) && !empty( $p ) && $save ) {
						update_option( 'vp-page-slugs', json_encode( $p ) );	
					}
					
					$c = array();
					foreach( $_POST['cat'] as $i => $s ) {
						$cat = $_POST['cat'][$i];
						$tag = $_POST['tag'][$i];
						
						$c[$i] = array( 'cat' => $cat, 'tag' => $tag );	
					}
					
					if( !empty( $c ) ) {
						update_option( 'vp-cat-tag', json_encode( $c ) );	
					}
				}
							
				if( !empty( $use_category ) && empty( $_POST['disable_auto_cat'] ) ) {
					if( get_option( 'vp-type-cat-installed', -1 ) == - 1 ) {
						$vp_instance->register_type_categories();	
					}	
				}
				
                $vp_instance->load_settings();
                
				if( !empty( $error ) ) echo '<div class="error"><p>'.$error.'</p></div>';
                else echo '<div class="updated"><p>'.__( 'Settings saved', 'viralpress' ).'</p></div>';
            }
        }
		
        $settings = $vp_instance->settings;
    ?>
    
    	<h2 class="nav-tab-wrapper">
            <a href="?page=viralpress&tab=general" class="nav-tab <?php echo $tab == 'general' ? 'nav-tab-active' : ''?>"><?php _e( 'General settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=social" class="nav-tab <?php echo $tab == 'social' ? 'nav-tab-active' : ''?>"><?php _e( 'Social settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=captcha" class="nav-tab <?php echo $tab == 'captcha' ? 'nav-tab-active' : ''?>"><?php _e( 'Captcha settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=editor" class="nav-tab <?php echo $tab == 'editor' ? 'nav-tab-active' : ''?>"><?php _e( 'Editor settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=modules" class="nav-tab <?php echo $tab == 'modules' ? 'nav-tab-active' : ''?>"><?php _e( 'Module settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=demo" class="nav-tab <?php echo $tab == 'demo' ? 'nav-tab-active' : ''?>"><?php _e( 'Category & menu', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=gif" class="nav-tab <?php echo $tab == 'gif' ? 'nav-tab-active' : ''?>"><?php _e( 'Gif Reactions', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=embed" class="nav-tab <?php echo $tab == 'embed' ? 'nav-tab-active' : ''?>"><?php _e( 'Embed settings', 'viralpress' ) ?></a>
            <a href="?page=viralpress&tab=page" class="nav-tab <?php echo $tab == 'page' ? 'nav-tab-active' : ''?>"><?php _e( 'Page settings', 'viralpress' ) ?></a>
        </h2>
        
        <div class="clear"></div><div class="clear"></div>
        
        <?php
		if( $tab == 'demo' ) {
		?>
        <h2><?php _e( 'Install menu', 'viralpress' )?></h2>
        <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'install_menu', '_nonce' );?>"> 
            <button class="button button-primary button-hero" onclick="return confirm('<?php _e( 'WARNING: If you already have menu installed, performing this action will make duplicate entries on menu. Please proceed with caution. NOTE: This action will take few minutes to complete.', 'viralpress' )?>');">
                <?php _e( 'Install Menu', 'viralpress' )?>
            </button>
            <input type="hidden" name="install_menu" value="1"/>
        </form>
        <h2><?php _e( 'Install categories', 'viralpress' )?></h2>
        <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'install_cat', '_nonce' );?>"> 
            <button class="button button-secondary button-hero">
                <?php _e( 'Install Categories', 'viralpress' )?>
            </button>
            <input type="hidden" name="install_cat" value="1"/>
        </form>
        <h2><?php _e( 'Delete menu', 'viralpress' )?></h2>
        <form method="post" action="<?php echo wp_nonce_url( 'admin.php?page=viralpress&tab='.$tab, 'delete_menu', '_nonce' );?>"> 
            <button class="button button-primary button-hero" onclick="return confirm('<?php _e( 'WARNING: Are you sure to delete ViralPress menu? NOTE: This action will take few minutes to complete.', 'viralpress' )?>');">
                <?php _e( 'Delete Menu', 'viralpress' )?>
            </button>
            <input type="hidden" name="delete_menu" value="1"/>
        </form>
        <br/><br/>
        <div><?php _e( 'Note: To reinstall the menu, delete first and then install again.', 'viralpress' )?></div>
        <?php		
		}else{
		?>
        
        <form method="post">
            <?php wp_nonce_field( 'vp-admin-action-'.get_current_user_id(), '_nonce' ); ?>
            <input type="hidden" name="vp_save_config" value="1"/>
            <table class="form-table">
            	<?php if( $tab == 'general' ) : ?> 
                <tr>
                    <th scope="row">
                        <label for="auto_publish"><?php _e( 'Publication', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="auto_publish" id="auto_publish" value="1" <?php if( $settings['auto_publish'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="auto_publish"><?php _e( 'Auto publish user submitted posts', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_reactions"><?php _e( 'Post Reactions', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_reactions" id="show_reactions" value="1" <?php if( $settings['show_reactions'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="show_reactions"><?php _e( 'Show reaction buttons under each post', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_gif_reactions"><?php _e( 'Gif Reactions', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_gif_reactions" id="show_gif_reactions" value="1" <?php if( $settings['show_gif_reactions'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="show_gif_reactions"><?php _e( 'Show gif reactions under each post', 'viralpress' )?>. <input name="show_gif_reactions_upload" type="checkbox" value="1" <?php if( $settings['show_gif_reactions_upload'] ) echo "checked='checked'"?>/> <?php echo sprintf( __( 'Allow custom gif upload', 'viralpress' ) )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="hide_vote_buttons"><?php _e( 'Remove voting for closed list', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="hide_vote_buttons" id="hide_vote_buttons" value="1" <?php if( $settings['hide_vote_buttons'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="hide_vote_buttons"><?php _e( 'Remove vote/like buttons from numbered list items', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="hide_vote_buttons_op"><?php _e( 'Remove voting for open list', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="hide_vote_buttons_op" id="hide_vote_buttons_op" value="1" <?php if( $settings['hide_vote_buttons_op'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="hide_vote_buttons_op"><?php _e( 'Remove vote/like buttons from open list items', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sort_op_vote"><?php _e( 'Sort open list by voting', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="sort_op_vote" id="sort_op_vote" value="1" <?php if( $settings['sort_op_vote'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="sort_op_vote"><?php _e( 'Check this to sort open lists by voting (Takes effect after enabling)', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_like_dislike"><?php _e( 'Use like/dislike buttons', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_like_dislike" id="show_like_dislike" value="1" <?php if( $settings['show_like_dislike'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="show_like_dislike"><?php _e( 'Show likes/dislikes buttons instead of up/down votes for individual list items', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="custom_profiles"><?php _e( 'Custom Author Profiles', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="custom_profiles" id="custom_profiles" value="1" <?php if( $settings['custom_profiles'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="custom_profiles"><?php _e( 'Replace default author page with custom author page of ViralPress', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_menu"><?php _e( 'Show ViralPress Menu', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="show_menu" id="show_menu" value="1" <?php if( $settings['show_menu'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="show_menu"><?php _e( 'This will replace your wordpress menu with viralpress menu', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="block_admin"><?php _e( 'Block Admin Panel Access', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="block_admin" id="block_admin" value="1" <?php if( $settings['block_admin'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="block_admin"><?php _e( 'Block contributors from admin panel. Highly recommended to keep this option enabled.', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="block_edits"><?php _e( 'Disallow approved post edits', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="block_edits" id="block_edits" value="1" <?php if( $settings['block_edits'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="block_edits"><?php _e( 'Block contributors from editing or deleting approved posts', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="use_category"><?php _e( 'Save Viral Posts into Categories', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="use_category" id="use_category" value="1" <?php if( $settings['use_category'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="use_category"><?php echo sprintf( __( 'If checked each type of viral posts will be saved into a category with the same name as their type or as configured from the page settings tabs of viralpress admin. Check this %s to disable automatic category creation using the same name as post types.', 'viralpress' ), '<input type="checkbox" value="1" name="disable_auto_cat"/>' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_menu_on"><?php _e( 'Show menu on', 'viralpress' )?></label>
                    </th>
                    <td>
                        <select id="show_menu_on" name="show_menu_on">
                        	<option value="both" <?php if( $settings['show_menu_on'] == 'both' ) echo "selected='selected'"?>>Both</option>
                            <option value="primary" <?php if( $settings['show_menu_on'] == 'primary' ) echo "selected='selected'"?>>Primary</option>
                            <option value="secondary" <?php if( $settings['show_menu_on'] == 'secondary' ) echo "selected='selected'"?>>Secondary</option>
                        </select>&nbsp;
                        <label for="show_menu_on"><?php _e( 'Choose on which menu viralpress menu will be active. If you choose secondary menu your theme must support it', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="anon_votes"><?php _e( 'Allow anonymous poll votes and reactions', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="anon_votes" id="anon_votes" value="1" <?php if( $settings['anon_votes'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="anon_votes"><?php _e( 'If checked guests can cast votes on polls and react to posts', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="share_quiz_force"><?php _e( 'Share quiz before seeing result', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="share_quiz_force" id="share_quiz_force" value="1" <?php if( $settings['share_quiz_force'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="share_quiz_force"><?php _e( 'If checked users must share quiz to view their result. Only facebook share available on this mode', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="hotlink_image"><?php _e( 'Allow hotlinking in editor', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="hotlink_image" id="hotlink_image" value="1" <?php if( $settings['hotlink_image'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="hotlink_image"><?php _e( 'If checked external images added from link will be hotlinked directly without downloading to your server', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'social' ) : ?>
                <tr>
                    <th scope="row">
                        <label for="fb_app_id"><?php _e( 'Facebook App Id', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['fb_app_id']?>" id="fb_app_id" name="fb_app_id" class="regular-text"> 
                        <p class="description">Must be filled to enable facebook login.</p>   
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="google_api_key"><?php _e( 'Google Api Key', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['google_api_key']?>" id="google_api_key" name="google_api_key" class="regular-text">
                        <p class="description"><?php _e( 'Must be filled to enable google login.<br/>Make sure to enable google plus api in your app.', 'viralpress' )?></p>
                   </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="google_oauth_id"><?php _e( 'Google OAuth Id', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['google_oauth_id']?>" id="google_oauth_id" name="google_oauth_id" class="regular-text">
                        <p class="description"><?php _e( 'Must be filled to enable google login.<br/>Make sure to enable google plus api in your app.', 'viralpress' )?></p>
                    </td>
                </tr>
				<tr>
                    <th scope="row">
                        <label for="fb_comments"><?php _e( 'Facebook Comments', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="fb_comments" id="fb_comments" value="1" <?php if( $settings['fb_comments'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="fb_comments"><?php _e( 'Add facebook comments plugin with each post', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="comments_per_list"><?php _e( 'Display seperate facebook comments per list', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="comments_per_list" id="comments_per_list" value="1" <?php if( $settings['comments_per_list'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="comments_per_list"><?php _e( 'If checked each list will display seperate facebook comments box. Exception: polls and quizzes', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="share_buttons"><?php _e( 'Share Buttons', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="share_buttons" id="share_buttons" value="1" <?php if( $settings['share_buttons'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="share_buttons"><?php _e( 'Show social share buttons under each post', 'viralpress' )?></label>
                    </td>
                </tr>
				<?php endif;?>
                 
                <?php if( $tab == 'captcha' ) : ?>
                <tr>
                    <th scope="row">
                        <label for="recap_key"><?php _e( 'Recaptcha Public Key', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['recap_key']?>" id="recap_key" name="recap_key" class="regular-text"> 
                        <p class="description"><?php _e( 'Must be filled to enable captcha protection.', 'viralpress' )?></p>   
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="recap_secret"><?php _e( 'Recaptcha Secret', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $settings['recap_secret']?>" id="recap_secret" name="recap_secret" class="regular-text"> 
                        <p class="description"><?php _e( 'Must be filled to enable captcha protection.', 'viralpress' )?></p>   
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="recap_login"><?php _e( 'Captcha on signup', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="recap_login" id="recap_login" value="1" <?php if( $settings['recap_login'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="recap_login"><?php _e( 'If checked captcha challenge will be shown on signup', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="recap_post"><?php _e( 'Captcha on editor', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="recap_post" id="recap_post" value="1" <?php if( $settings['recap_post'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="recap_post"><?php _e( 'If checked user must solve captcha before submitting new post', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                
                <?php if( $tab == 'editor' ):?>
                <tr>
                    <th scope="row">
                        <label for="only_admin"><?php _e( 'Allow only admins', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="only_admin" id="only_admin" value="1" <?php if( $settings['only_admin'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="only_admin"><?php _e( 'If checked ViralPress editor can only be accessed by admins and editors', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="allow_copy"><?php _e( 'Allow copying viral posts by other users', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="allow_copy" id="allow_copy" value="1" <?php if( $settings['allow_copy'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="allow_copy"><?php _e( 'If checked any user can copy others list and save on their own name when the list owner checks the option to allow copy.', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="allow_open_list"><?php _e( 'Allow open list', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="allow_open_list" id="allow_open_list" value="1" <?php if( $settings['allow_open_list'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="allow_open_list"><?php _e( 'If checked any user can contribute to list that has been submitted as open list. Submitted lists will be queued for approval from admins. Exception: polls and quizzes', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'modules' ) : ?> 
                <tr>
                    <th scope="row">
                        <label for="list_enabled"><?php _e( 'List enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="list_enabled" id="list_enabled" value="1" <?php if( $settings['list_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="list_enabled"><?php _e( 'Indicates whether list item submission is enabled or disabled', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="news_enabled"><?php _e( 'News enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="news_enabled" id="news_enabled" value="1" <?php if( $settings['news_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="news_enabled"><?php _e( 'Indicates whether news item submission is enabled or disabled', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="quiz_enabled"><?php _e( 'Quiz enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="quiz_enabled" id="quiz_enabled" value="1" <?php if( $settings['quiz_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="quiz_enabled"><?php _e( 'Indicates whether quiz submission is enabled or disabled', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="poll_enabled"><?php _e( 'Poll enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="poll_enabled" id="poll_enabled" value="1" <?php if( $settings['poll_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="poll_enabled"><?php _e( 'Indicates whether poll submission is enabled or disabled', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="image_enabled"><?php _e( 'Image enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="image_enabled" id="image_enabled" value="1" <?php if( $settings['image_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="image_enabled"><?php _e( 'Uncheck to disable image submission', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="embed_enabled"><?php _e( 'Embeds enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="embed_enabled" id="embed_enabled" value="1" <?php if( $settings['embed_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="embed_enabled"><?php _e( 'Uncheck to disable adding embeds', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="meme_enabled"><?php _e( 'Meme enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="meme_enabled" id="meme_enabled" value="1" <?php if( $settings['meme_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="meme_enabled"><?php _e( 'Uncheck to disable meme submission', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="video_enabled"><?php _e( 'Video enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="video_enabled" id="video_enabled" value="1" <?php if( $settings['video_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="video_enabled"><?php _e( 'Uncheck to disable video submission (Embed or self hosted. To disable self hosted check below)', 'viralpress' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="audio_enabled"><?php _e( 'Audio enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="audio_enabled" id="audio_enabled" value="1" <?php if( $settings['audio_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="audio_enabled"><?php _e( 'Uncheck to disable audio submission (Embed or self hosted. To disable self hosted check below)', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="self_video"><?php _e( 'Self hosted video', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="self_video" id="self_video" value="1" <?php if( $settings['self_video'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="self_video"><?php _e( 'Uncheck to disable self hosted video uploads', 'viralpress' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="self_audio"><?php _e( 'Self hosted audio', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="self_audio" id="self_audio" value="1" <?php if( $settings['self_audio'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="self_audio"><?php _e( 'Uncheck to disable self hosted audio uploads', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="playlist_enabled"><?php _e( 'Playlist enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="playlist_enabled" id="playlist_enabled" value="1" <?php if( $settings['playlist_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="playlist_enabled"><?php _e( 'Uncheck to disable playlist', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="gallery_enabled"><?php _e( 'Gallery enabled', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="gallery_enabled" id="gallery_enabled" value="1" <?php if( $settings['gallery_enabled'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="gallery_enabled"><?php _e( 'Uncheck to disable gallery', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="disable_login"><?php _e( 'ViralPress login system', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="disable_login" id="disable_login" value="1" <?php if( !$settings['disable_login'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="disable_login"><?php _e( 'Disable if you want to use your existing login system. ViralPress login, logout, registration and recovery pages will not work.', 'viralpress' )?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wsl_int"><?php _e( 'Integrate with wordpress social login', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="wsl_int" id="wsl_int" value="1" <?php if( $settings['wsl_int'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="wsl_int"><?php echo sprintf( __( 'If checked %s wordpress social login %s plugin will be integrated with ViralPress. The social login icons from WSL plugin will be shown on top of login form instead of ViralPress native social login forms. Note: You must use ViralPress login system for this integration. Warning: If you are using WSL plugin all the way, unchecking this will show ViralPress login and users logged in with WSL may not be connected with their previous account.', 'viralpress' ), '<a href="https://wordpress.org/plugins/wordpress-social-login/">', '</a>' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="vp_bp"><?php _e( 'BuddyPress Integration', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="vp_bp" id="vp_bp" value="1" <?php if( $settings['vp_bp'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="vp_bp"><?php _e( 'Uncheck to disable ViralPress notifications, post tabs from BuddyPress.', 'viralpress' )?></label>
                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="vp_mycred"><?php _e( 'myCRED Integration', 'viralpress' )?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="vp_mycred" id="vp_mycred" value="1" <?php if( $settings['vp_mycred'] ) echo "checked='checked'"?>/>&nbsp;
                        <label for="vp_mycred"><?php _e( 'Uncheck to disable ViralPress hooks for myCRED.', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'gif' ) : ?>
                <tr>
                    <th scope="row">
                        <label for=""><?php _e( 'Gif Reactions', 'viralpress' )?></label>
                    </th>
                    <td>
                    	<div class="gifs">
                        <?php
						if( !empty( $vp_instance->settings['react_gifs'] ) ) {
							$gifs = json_decode( $vp_instance->settings['react_gifs'], true );
							$gg = array();
							foreach( $gifs as $g ) {
								echo '<div class="gif_row">
								'.__( 'GIF URL:', 'viralpress' ).'<input type="text" name="react_gif[url][]" value="'.$g['url'].'"/>
								'.__( 'Caption:', 'viralpress' ).'<input type="text" name="react_gif[caption][]" value="'.$g['caption'].'"/>
								'.__( 'Static Image URL:', 'viralpress' ).'<input type="text" name="react_gif[static][]" value="'.$g['static'].'"/>
								<a href="javascript:void(0)" onclick="jQuery(this).parents(\'.gif_row:first\').remove()">'.__( 'Remove', 'viralpress' ).'</a>
								<br/></div>
								';	
							}
						}
						?>
                        </div>
                        <br/>
                        <a href="javascript:void(0)" class="add_new_gif"><?php _e( 'Add new+', 'viralpress' )?></a>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'embed' ) : ?>
                
                <tr>
                    <th scope="row">
                        <label for="allowed_embeds"><?php _e( 'Allowed external embeds', 'viralpress' )?></label>
                    </th>
                    <td>
                        <textarea rows="10" cols="50" type="checkbox" name="allowed_embeds" id="allowed_embeds"><?php echo @$settings['allowed_embeds']?></textarea>&nbsp;
                        <br/>
                        <label for="allowed_embeds"><?php _e( 'Put additional domain names you want to allow to be embeded. <br/> Only domain name. No URL please. Seperate them by comma <br/>Popular sites like youtube, facebook, bbc, twitter, youtube etc. are already allowed by default.', 'viralpress' )?></label>
                    </td>
                </tr>
                <?php endif;?>
                
                <?php if( $tab == 'page' ) : 
					$pp = get_pages();
					$hh = '<option value="">'.__( 'None', 'viralpress' ).'</option>';
					foreach( $pp as $p ) {
						$hh .= '<option value="'.$p->post_name.'">'.$p->post_title.'</option>';
					}
					
					$select_cats = wp_dropdown_categories( 
						array( 
							'orderby' => 'NAME', 
							'hide_empty' => 0, 
							'class' => 'vp-form-control' ,
							'echo' => 0,
							'show_option_all' => __( 'None', 'viralpress' )
							) 
						);
				?>
                
                <tr>
                    <th scope="row">
                        <label for=""><?php _e( 'Adjust page slugs', 'viralpress' )?></label><br/>
                        <small><?php _e( 'If you change page slugs, you need to manually change the new page links in navigation menu. The menu items that will need change is shown below each page name', 'viralpress' )?></small>
                    </th>
                    <td>
                    	<table>
                        	<tr>
                            	<td>
                       				<?php _e( 'Profile page', 'viralpress' )?><br/>
                                    <small><?php _e( 'username menu > profile', 'viralpress')?></small>
                                </td>
                                <td>    
                                    <select name="vp_page[profile]"><?php echo $hh?></select>
                       			</td>
                             </tr>
                             <tr>
                            	<td>
                       				<?php _e( 'Login page', 'viralpress' )?><br/>
                                    <small><?php _e( 'login menu', 'viralpress')?></small>
                                <td>
                                	<select name="vp_page[login]"><?php echo $hh?></select>
                                </td>
                             </tr>
                      		<tr>
                            	<td>
                      				<?php _e( 'Register page', 'viralpress' )?><br/>
                                    <small><?php _e( 'register menu', 'viralpress')?></small>
                                </td>
                                <td>
                                	<select name="vp_page[register]"><?php echo $hh?></select>
                                </td>
                            </tr>
                            <tr>
                      			<td>
									<?php _e( 'Password lost page', 'viralpress' )?>
                                </td>
                               	<td>
                                	<select name="vp_page[password-lost]"><?php echo $hh?></select>
                            	</td>
                            </tr>
                            <tr>
                            	<td>
                       				<?php _e( 'Password reset page', 'viralpress' )?>
                                </td>
                                <td>
                                	<select name="vp_page[password-reset]"><?php echo $hh?></select>
                                </td>
                            </tr>
                       		<tr>
                            	<td>
                       				<?php _e( 'Editor page', 'viralpress' )?><br/>
                                    <small><?php _e( 'submit post menu > all links', 'viralpress')?></small>
                                </td>
                                <td>
                       				<select name="vp_page[create]"><?php echo $hh?></select>
                       			</td>
                            </tr>
                       		<tr>
                            	<td>	
                       				<?php _e( 'Dashboard', 'viralpress' )?><br/>
                                    <small><?php _e( 'username menu > dashboard', 'viralpress')?></small>
                                </td>
                                <td>
                                	<select name="vp_page[dashboard]"><?php echo $hh?></select>
                       			</td>
                            </tr>
                       		<tr>
                            	<td>
                       				<?php _e( 'Meme generator', 'viralpress' )?>
                                </td>
                                <td>
                                	<select name="vp_page[meme-generator]"><?php echo $hh?></select>
                    			</td>
                			</tr>
                       </table>
                    </td>
                </tr>
                <tr>
                	<th valign="top" style="vertical-align: top;">
                    	<br/>
                    	<?php _e( 'Adjust categories & tags to save viral posts', 'viralpress' ) ?><br/>
                        <small><?php _e( 'To save posts into category, check viralpress admin settings', 'viralpress' )?></small>
                    </th>
                    <td>
                    	<table>
                        	<tr>
                            	<th><?php _e( 'Post type', 'viralpress' ) ?></th>
                                <th><?php _e( 'Category to save<br/><small>If category is used</small>', 'viralpress' ) ?></th>
                                <th><?php _e( 'Tag to save<br/><small>Always used</small>', 'viralpress' ) ?></th>
                            </tr>
                        	<tr>
                            	<td><?php _e( 'News', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[news]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[news]" value="" /></td>
                            </tr>
                        	<tr>
                            	<td><?php _e( 'List', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[lists]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[lists]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Image', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[images]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[images]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Meme', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[meme]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[meme]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Audio', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[audio]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[audio]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Video', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[videos]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[videos]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Quiz', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[quiz]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[quiz]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Poll', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[polls]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[polls]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Gallery', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[gallery]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[gallery]" value="" /></td>
                            </tr>
                            <tr>
                            	<td><?php _e( 'Playlist', 'viralpress' ) ?></td>
                                <td><?php echo str_replace( "name='cat' id=", "name='cat[playlist]' id=", $select_cats );?></td>
                                <td><input type="text" name="tag[playlist]" value="" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                <?php
				$page_slugs = $vp_instance->settings['page_slugs'];
				if( !empty( $page_slugs ) ) {
					$h = '<script>jQuery(document).ready(function($){';
					
					foreach( $page_slugs as $i => $p ) $h .= '$("select[name=\'vp_page['.esc_js( $i ).']\']").val("'.esc_js( $p ).'");';
						
					$h .= '});</script>';	
					
					echo $h;
				}
				
				$cat_tag = $vp_instance->settings['cat_tag'];
				if( !empty( $cat_tag ) ) {
					
					$h = '<script>jQuery(document).ready(function($){';
					
					foreach( $cat_tag as $i => $p ) $h .= '$("select[name=\'cat['.esc_js( $i ).']\']").val("'.esc_js( is_numeric( $p['cat'] ) ? $p['cat'] : 0 ).'");$("input[name=\'tag['.esc_js( $i ).']\']").val("'.esc_js( $p['tag'] ).'");';
					
					$h .= '});</script>';	
					
					echo $h;
				}
				?>
                
                <?php endif;?>
            </table> 
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'viralpress' )?>"  /></p>   	
        </form>
        <?php }?>
    </div>
</div>
<script>
jQuery( document ).ready( function( $ ){
	$('.add_new_gif').click( function(){
		h = '<div class="gif_row">'+
			'<?php _e( 'GIF URL:', 'viralpress' )?><input type="text" name="react_gif[url][]" value=""/>&nbsp;'+
			'<?php _e( 'Caption:', 'viralpress' )?><input type="text" name="react_gif[caption][]" value=""/>&nbsp;'+
			'<?php _e( 'Static Image URL:', 'viralpress' )?><input type="text" name="react_gif[static][]" value=""/>&nbsp;'+
			'<a href="javascript:void(0)" onclick="jQuery(this).parents(\'.gif_row:first\').remove()"><?php _e( 'Remove', 'viralpress' )?></a>'+
			'<br/></div>';	
		$('.gifs').append(h);
	});
});
</script>