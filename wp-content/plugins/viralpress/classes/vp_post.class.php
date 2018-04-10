<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

class vp_post
{
	public $post_link;
	public $error;
	public $error_selectors;
	public $error_selectors_msg;
	public $message;
	public $render_html;
	public $render_html_after_person1_quiz;
	
	public $post_id;
	public $child_ids;
	public $ans_ids;
	
	public $image_ids;
	
	public function __construct()
	{
	}
	
	public function add_post()
	{
		global $vp_instance;
		$this->post_id = '';
		$this->child_ids = array();
		$this->ans_ids = array();
		$this->image_ids = array();
		
		array_walk_recursive( $_POST, array( &$this, 'strip_shortcodes' ) );
		
		$this->error = '';
		$this->error_selectors = array();
		$this->error_selectors_msg = array();
		
		if ( empty( $_POST['vp_add_post_nonce'] ) || !wp_verify_nonce( $_POST['vp_add_post_nonce'], 'vp-add-post-'.get_current_user_id() ) ) {
        	$this->error = __( 'Failed to validate request. Please try again' , 'viralpress' );
			return false;
        }
		
		if( !is_user_logged_in() ) {
			$this->error = __( 'Login required before making any post' , 'viralpress' );
			return false;
		}
		
		if( !current_user_can( 'edit_posts' ) ) {
			$this->error = __( 'Sorry, you cannot add or edit post. Please contact admin' , 'viralpress' );
			return false;	
		}
		
		if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_post'] ) {
			 $resp = recaptcha_check_answer (
			 				$vp_instance->settings['recap_secret'], 
							$_SERVER["REMOTE_ADDR"],
							@$_POST["recaptcha_challenge_field"],
							@$_POST["recaptcha_response_field"]
					);

        	if ( ! $resp->is_valid ) {
                $this->error = __( 'Invalid captcha response' , 'viralpress' );
				return false;
        	}
	
		}
		
		$post_title = @esc_html( $_POST['post_title'] );
		$post_summary = @esc_html( $_POST['post_summary'] );
		$post_thumbnail = @soft_validate_image( $_POST['post_thumbnail'] );
		
		$preface_title = @esc_html( $_POST['preface_title'] );
		$preface_desc = @wp_kses( $_POST['preface_desc'], $vp_instance->allow_tags );
		$preface_image = @soft_validate_image( $_POST['preface_image'] );
		
		$post_id = @(int)$_POST['vp_post_id'];
		
		$category_ids = @$_POST['cat'];
		$post_tag = @explode( ',', $_POST['post_tag'] );
		
		$post_status = @$_POST['publication'] == 'draft' ? 'draft' : ( @$_POST['publication'] == 'delete' ? 'delete' : 'publish' );
		$items_per_page = @(int)$_POST['items_per_page'];
		$sort_order = @$_POST['sort_order'] == 'asc' ? 'asc' : 'desc';
		$show_numbers = @empty($_POST['show_numbers']) ? 0 : 1;
		$post_type = @esc_html( $_POST['vp_post_type'] );
		$quiz_type = @esc_html( $_POST['quiz_type'] );
		$fixed_ans = empty( $_POST['fixed_answer'] ) ? 0 : 1;
		$voting_till = !empty( $_POST['voting_till'] ) ? esc_html( $_POST['voting_till'] ) : '';
		$list_style = @esc_html( verify_list_style( $_POST['list_style'] ) );
		$list_display = @esc_html( verify_list_display( $_POST['list_display'] ) );
		$allow_copy = @empty($_POST['allow_copy']) ? 0 : 1;
		$open_list = @empty($_POST['open_list']) ? 0 : 1;
		
		$video_thumbs = array();
		$long_image = '';
		$long_image_area = 0;
		$max_score = 0;
		$has_preface = 0;
		
		if( !empty($post_id) ) {
			if ( !current_user_can( 'edit_post', $post_id ) ) {
				$this->error = __( 'Sorry, you cannot edit this post. Please contact admin' , 'viralpress' );
				return false;
			}	
		}
		
		if( !empty($post_id) && $post_status == 'delete' ) {
			if ( !current_user_can( 'delete_post', $post_id ) ) {
				$this->error = __( 'Sorry, you cannot delete this post. Please contact admin' , 'viralpress' );
				return false;
			}
			
			$this->delete_post( $post_id );
			$success = '';
			if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$this->error = __( 'Post successfully deleted', 'viralpress' );
			}
			$this->message = __( 'Post successfully deleted', 'viralpress' );
			return true;	
		}
		
		if( !vp_validate_post_type( $post_type ) ) {
			$this->error = __( 'Invalid post type' , 'viralpress' );
			return false;
		}
		
		if( $open_list && !$show_numbers ) {
			$this->error = __( 'Open lists must be numbered. To add a summary use preface' , 'viralpress' );
			return false;	
		}
		
		if( in_array( $post_type , array( 'list', 'lists' ) ) && !$vp_instance->settings['list_enabled'] ) {
			$message = __( 'Sorry! List item submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'news' ) ) && !$vp_instance->settings['news_enabled'] ) {
			$message = __( 'Sorry! News submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'image', 'images' ) ) && !$vp_instance->settings['image_enabled'] ) {
			$message = __( 'Sorry! Image submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'meme' ) ) && !$vp_instance->settings['meme_enabled'] ) {
			$message = __( 'Sorry! Meme submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'video', 'videos' ) ) && !$vp_instance->settings['video_enabled'] ) {
			$message = __( 'Sorry! Video submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'audio' ) ) && !$vp_instance->settings['audio_enabled'] ) {
			$message = __( 'Sorry! Audio submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'gallery' ) ) && !$vp_instance->settings['gallery_enabled'] ) {
			$message = __( 'Sorry! Gallery submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'playlist' ) ) && !$vp_instance->settings['playlist_enabled'] ) {
			$message = __( 'Sorry! Playlist submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'quiz' ) ) && !$vp_instance->settings['quiz_enabled'] ) {
			$message = __( 'Sorry! Quiz submission is disabled', 'viralpress' ); 	
			$this->error = $message;
			return false;
		}
		else if( in_array( $post_type , array( 'poll', 'polls' ) ) && !$vp_instance->settings['poll_enabled'] ) {
			$message = __( 'Sorry! Poll submission is disabled', 'viralpress' ); 
			$this->error = $message;
			return false;	
		}
		else if( in_array( $post_type , array( 'quiz', 'poll', 'polls' ) ) && $open_list ) {
			$message = __( 'Sorry! Quiz and poll cannot be open list', 'viralpress' ); 
			$this->error = $message;
			return false;	
		}
		
		if( !$vp_instance->settings['allow_open_list'] && $open_list ) {
			$message = __( 'Sorry! open list submission is disabled', 'viralpress' ); 
			$this->error = $message;
			return false;	
		}
		
		if(! empty( $quiz_type ) ) {
			if( !in_array( $quiz_type, array( 'person1', 'person2', 'trivia', 'mcq' ) ) ){
				$this->error = __( 'Invalid quiz type' , 'viralpress' );
				return false;	
			}		
		}
		
		if( in_array( $quiz_type, array( 'person1', 'person2' ) ) )$fixed_ans = 0;
		else if( in_array( $quiz_type, array( 'trivia', 'mcq' ) ) )$fixed_ans = 1;

		
		if( empty( $post_title ) /*|| empty( $post_summary ) || empty( $post_thumbnail )*/ ) {
			$this->error = __( 'Post title must not be empty' , 'viralpress' );
			return false;
		}
		
		if( !empty($post_thumbnail) )
		if ( !is_numeric( $post_thumbnail ) ) {
			$this->error = __( 'Post thumbnail cannot be a link. Please use image from media library' , 'viralpress' );
			return false;
		}
		
		if( !empty($preface_image) )
		if ( !is_numeric( $preface_image )  && $vp_instance->settings['hotlink_image'] == 0 ) {
			$this->error = __( 'Invalid or unauthorized preface image id' , 'viralpress' );
			return false;
		}
		
		if( empty( $category_ids ) ) {
			$category_ids = get_option( 'default_category' );
			//$this->error = __( 'Please choose a valid category' , 'viralpress' );
			//return false;
		}
		
		$category_ids = array_unique( $category_ids );
		foreach( $category_ids as &$category_id ) {
			$category_id = (int)$category_id;
			if( !term_exists( $category_id, 'category' ) ) {
				$this->error = __( 'Please choose a valid category' , 'viralpress' );
				return false;
			}
		}
		
		if( @$vp_instance->settings['use_category'] == 1 ) {
			
			$slug = '';
			
			if( !empty( $vp_instance->settings['cat_tag'][$post_type] ) ) {
				$slug = $vp_instance->settings['cat_tag'][$post_type]['cat'];
			}
			
			if( empty( $vp_instance->settings['cat_tag'] )  || empty( $slug ) ) {
				$slug = 'news';
				if( $post_type == 'lists' )$slug = 'list';
				else if( $post_type == 'quiz' )$slug = 'quiz';
				else if( $post_type == 'videos' )$slug = 'video';
				else if( $post_type == 'audio' )$slug = 'audio';
				else if( $post_type == 'polls' )$slug = 'poll';
				else if( $post_type == 'gallery' )$slug = 'gallery';
				else if( $post_type == 'playlist' )$slug = 'playlist';
				else if( $post_type == 'images' )$slug = 'image';			
			}
			
			if( is_numeric ( $slug ) ) $tt = get_term_by( 'id', $slug, 'category' );
			else $tt = get_term_by( 'slug', $slug, 'category' );
			
			if( !empty( $tt->term_id ) ) {
				$category_ids[] = (int)$tt->term_id;
				$category_ids = array_unique( $category_ids );	
			}
			else {
				$this->error = __( 'The category for "'.$post_type.'" does not exist. Please create the category first or adjust admin settings for this post type.' , 'viralpress' );
				return false;
			}
		}
		
		/*
		if( !empty( $post_tag ) ) {
			if( !term_exists( $post_tag, 'post_tag' ) ) {
				$this->error = __( 'Please choose a valid post tag' , 'viralpress' );
				return false;
			}
		}
		*/
		
		if( empty($_POST['entry_type']) ) {
			$this->error = __( 'At least one entry required' , 'viralpress' );
			return false;
		}
		
		/*
		if( !empty( $voting_till ) ) {
			if( strtotime( $voting_till ) < time() ) {
				$this->error = __( 'Voting open time cannot be in past' , 'viralpress' );
				return false;
			}	
		}
		*/
		
		if( !empty( $preface_image ) || !empty( $preface_title ) || !empty( $preface_desc ) ) $has_preface = 1;
		
		$score_entries = array();
		$post_entries = array();
		
		if( $post_type == 'quiz' ) {
			if( empty( $_POST['quiz_score_sheet'] ) ) {
				$this->error = __( 'Quiz score sheet missing.' , 'viralpress' );
				return false;
			}
			else{
				$n = 0;
				foreach( $_POST['quiz_score_sheet'] as $i => $s ) {
					$n++;
					$score_from = @(int)$_POST['quiz_score_from'][$i];
					$score_to = @(int)$_POST['quiz_score_to'][$i];	
					$score_title = @esc_html( $_POST['entry_text'][$i] );	
					$score_desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );	
					$score_img = @soft_validate_image( $_POST['entry_images'][$i] );
					$score_post_id = @(int)$_POST['entry_post_id'][$i];
					$score_source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
					
					unset( $_POST['entry_type'][$i] );
					
					if( empty( $score_img ) && empty( $score_title ) && empty( $score_desc ) ) {
						$this->error_selectors[] = -$n;
						$this->error_selectors_msg[] = __( 'At least a title or a description or an image required for each score sheet.', 'viralpress' );
						$this->error = __( 'Missing values in quiz score sheet. Check errors below.' , 'viralpress' );
						$error = 1;	
					}
					else if( $score_from < 0 ) {
						$this->error_selectors[] = -$n;
						$this->error_selectors_msg[] = __( 'Score must be greater than or equal to zero.', 'viralpress' );
						$this->error = __( 'Missing values in quiz score sheet. Check errors below.' , 'viralpress' );
						$error = 1;	
					}
					else {
						
						if( !empty($score_post_id) ) {
							if ( !current_user_can( 'edit_post', $score_post_id ) ) {
								$this->error_selectors[] = -$n;
								$this->error_selectors_msg[] = __( 'Invalid post update request.', 'viralpress' );
								$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
								$error = 1;
							}	
						}
						
						if( !empty($score_img) ) {
							if ( !is_numeric( $score_img ) && $vp_instance->settings['hotlink_image'] == 0 ) {
								$this->error_selectors[] = -$n;
								$this->error_selectors_msg[] = __( 'Image hotlinking is disabled', 'viralpress' );
								$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
								$error = 1;
							}	
						}
						
						if( empty($this->error) ) {
						if( empty($score_title) )$score_title = 'NO_TITLE';
							$data_score = array(
								'post_title' => $score_title,
								'post_content' => $score_desc,
								'post_category' => $category_ids,
								'post_status' => 'inherit',
								'post_type' => 'quiz',
								'custom_fields' => array(
									'vp_quiz_score_from' => $score_from,
									'vp_quiz_score_to' => $score_to,
									'vp_source_url' => $score_source
								)
							);	
							
							if( !empty($score_img) ) $data_score['custom_fields']['vp_quiz_image_entry'] = $score_img;
							if( !empty($score_post_id) ) {
								$data_score['ID'] = $score_post_id;
								$data_score['post_author'] = get_post_field( 'post_author', $score_post_id );
							}
							$score_entries[] = $data_score;
						}
					}
				}	
			}	
		}
		
		$n = 0;
		if( empty($this->error) )
		foreach( $_POST['entry_type'] as $i => $entry_type ):
			$n++;
			$order = (int)$_POST['entry_order'][$i];
			if( empty($order) )continue;
			$entry_show_num = empty( $_POST['entry_show_num'][$i] ) ? 0 : 1;
			
			if( $open_list && !$entry_show_num ) {
				$this->error_selectors[] = $n;
				$this->error_selectors_msg[] = __( 'Open list items must be numbered', 'viralpress' );
				$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
				$error = 1;
			}
			
			if( !empty( $error ) ) continue;
			
			if( $entry_type == 'text' || $entry_type == 'news' ) {
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				
				if( empty($desc) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Description required for this entry', 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if( !empty($entry_post_id) ) {
					if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}	
				}
				
				if( empty($error) ){
					$post_entries[$order] = array(
						'post_title' => $title,
						'post_content' => $desc,
						'post_category' => $category_ids,
						'post_status' => 'inherit',
						'post_type' => 'news',
						'custom_fields' => array(
							'vp_source_url' => $source
						)
					);	
					if( !empty($entry_post_id) ){
						$post_entries[$order]['ID'] = $entry_post_id;
						$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
					}
				}
				
			}
			else if( $entry_type == 'list' || $entry_type == 'lists' ) {
				
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				$img = @soft_validate_image( $_POST['entry_images'][$i] );
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				
				if ( empty( $img ) ||  ( !is_numeric( $img ) && $vp_instance->settings['hotlink_image'] == 0 ) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Image hotlinking is disabled or invalid image', 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if( !empty($entry_post_id) ) {
					if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}	
				}
				
				if( empty($error) ){
					$post_entries[$order] = array(
						'post_title' => $title,
						'post_content' => $desc,
						'post_category' => $category_ids,
						'post_status' => 'inherit',
						'post_type' => 'lists',
						'custom_fields' => array(
							'vp_list_image_entry' => $img,
							'vp_source_url' => $source
						)
					);
					
					if( is_numeric( $img ) ) {
						$this->image_ids[] = $img;
						$img_attr = wp_get_attachment_image_src( $img );
						$long_a = $img_attr[2]; //only height
						if( $long_a > $long_image_area ){
							$long_image = $img;
							$long_image_area = $long_a;
						}
					}
					
					if( !empty($entry_post_id) ){
						$post_entries[$order]['ID'] = $entry_post_id;
						$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
					}
				}
				
			}
			else if( $entry_type == 'gallery' || $entry_type == 'galleries' ) {
				
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				$images = @$_POST['entry_images'][$i];
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				$gallery_type = @$_POST['gal_type'][$i];
				$gallery_col = @(int)$_POST['gal_cols'][$i];
				$gallery_autostart = @(int)$_POST['gal_autostart'][$i];
				
				if( !$vp_instance->settings['gallery_enabled'] ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Galleries are not allowed currently' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;			
				}
				
				if( empty( $error ) ) {
					if( !in_array( $gallery_type, array( "thumbnail","rectangular", "square", "circle", "slideshow", "columns" ) ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Invalid gallery type selected' , 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}
					
					if( empty($images ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Valid images required for this entry' , 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}
					
					foreach( $images as $img ) {
						$this->image_ids[] = $img;
						$img_attr = wp_get_attachment_image_src( $img );
						
						if( empty( $img_attr ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Valid images required for this entry' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;		
						}	
						
						$long_a = $img_attr[2]; //only height
						if( $long_a > $long_image_area ){
							$long_image = $img;
							$long_image_area = $long_a;
						}
					}
					
					if( !empty($entry_post_id) ) {
						if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;
						}	
					}
				}
				
				if( empty($error) ){
					$post_entries[$order] = array(
						'post_title' => $title,
						'post_content' => $desc,
						'post_category' => $category_ids,
						'post_status' => 'inherit',
						'post_type' => 'gallery',
						'custom_fields' => array(
							'vp_list_image_entry' => implode(',', $images),
							'vp_gallery_type' => $gallery_type,
							'vp_gallery_col' => $gallery_col,
							'vp_gallery_autostart' => $gallery_autostart,
							'vp_source_url' => $source,
							'vp_gallery_shortcode' => '[gallery link="post" ids="'.implode(',', $images).'" type="'.$gallery_type.'" columns="'.$gallery_col.'" autostart="'.( $gallery_autostart ? 'true' : 'false' ).'"]'
						)
					);
					
					if( !empty($entry_post_id) ){
						$post_entries[$order]['ID'] = $entry_post_id;
						$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
					}
				}
			}
			else if( $entry_type == 'playlist' || $entry_type == 'playlists' ) {
				
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				$media = @$_POST['entry_media'][$i];
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				
				if( !$vp_instance->settings['playlist_enabled'] ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Playlists are not allowed currently' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;			
				}
				
				if( empty($media ) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Valid media required for this entry' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if( empty( $error ) ) {				
					$p_type = '';
					foreach( $media as $kk => $m ) {
						$type = get_post_mime_type( $m );
						if( vp_verify_video_mime( $type ) ) $type = 'video';
						else if( vp_verify_audio_mime( $type ) ) $type = 'audio';
						else {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'One of the media type is not supported. Check item no '.($kk+1) , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;	
						}
						
						if( !$kk ) $p_type = $type;
						else if( $p_type != $type ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Audio and video cannot be mixed in playlist. Check item no '.($kk+1) , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;
						}
					}
					
					if( !empty($entry_post_id) ) {
						if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;
						}	
					}
				}
				
				if( empty($error) ){
					$post_entries[$order] = array(
						'post_title' => $title,
						'post_content' => $desc,
						'post_category' => $category_ids,
						'post_status' => 'inherit',
						'post_type' => 'playlist',
						'custom_fields' => array(
							'vp_list_image_entry' => implode(',', $media),
							'vp_source_url' => $source,
							'vp_playlist_shortcode' => '[playlist ids="'.implode( ',', $media ).'" type="'.$p_type.'"]'
						)
					);
					
					if( !empty($entry_post_id) ){
						$post_entries[$order]['ID'] = $entry_post_id;
						$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
					}
				}
				
			}
			else if( $entry_type == 'video' || $entry_type == 'videos' ) {
				
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				$vid = @esc_html( $_POST['entry_videos'][$i] );
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				
				if( empty($vid) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Valid video required for this entry' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				else{
					if( !is_numeric( $vid ) ) {
						if( !verify_embed_entry_value( $vid )){
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Valid video required for this entry' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;	
						}
						else {
							$video_thumbs[] = $vid;	
						}
					}
					else {	
						$type = get_post_mime_type( $vid );
						if( !$vp_instance->settings['self_video'] ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Self hosted video are not supported currently' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;			
						}
						else if( !vp_verify_video_mime( $type ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Sorry currently we do not support '.$type , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;			
						}
					}
				}
				
				if( !empty($entry_post_id) ) {
					if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}	
				}
				
				if( empty($error) ){
					$post_entries[$order] = array(
						'post_title' => $title,
						'post_content' => $desc,
						'post_category' => $category_ids,
						'post_status' => 'inherit',
						'post_type' => 'videos',
						'custom_fields' => array(
							'vp_video_entry' => $vid,
							'vp_source_url' => $source
						)
					);	
					if( !empty($entry_post_id) ){
						$post_entries[$order]['ID'] = $entry_post_id;
						$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
					}
				}
				
			}
			else if( $entry_type == 'audio' ) {
				
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				$aid = @esc_html( $_POST['entry_audio'][$i] );
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				
				if( empty($aid) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Valid audio required for this entry' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				else{
					if( !is_numeric( $aid ) ) {	
						if( !verify_embed_entry_value( $aid )){
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Valid audio required for this entry' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;	
						}
						else {
							$video_thumbs[] = $aid;	
						}
					}
					else {
						$type = get_post_mime_type( $aid );
						if( !$vp_instance->settings['self_audio'] ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Self hosted audio are not supported currently' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;			
						}
						else if( !vp_verify_audio_mime( $type ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Sorry currently we do not support '.$type , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;			
						}
					}
				}
				
				if( !empty($entry_post_id) ) {
					if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}	
				}
				
				if( empty($error) ){
					$post_entries[$order] = array(
						'post_title' => $title,
						'post_content' => $desc,
						'post_category' => $category_ids,
						'post_status' => 'inherit',
						'post_type' => 'audio',
						'custom_fields' => array(
							'vp_audio_entry' => $aid,
							'vp_source_url' => $source
						)
					);	
					if( !empty($entry_post_id) ){
						$post_entries[$order]['ID'] = $entry_post_id;
						$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
					}
				}
			}
			else if( $entry_type == 'pin' || $entry_type == 'pins' ) {
				
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @wp_kses( $_POST['entry_desc'][$i], $vp_instance->allow_tags );
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				$eid = @esc_html( $_POST['entry_pins'][$i] );
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				
				if( empty($eid) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Valid embed required for this entry' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				else{
					if( !verify_embed_entry_value( $eid )){
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Valid embed required for this entry' , 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;	
					}
					else {
						$video_thumbs[] = $eid;	
					}
				}
				
				if( !empty($entry_post_id) ) {
					if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}	
				}
				
				if( empty($error) ){
					$post_entries[$order] = array(
						'post_title' => $title,
						'post_content' => $desc,
						'post_category' => $category_ids,
						'post_status' => 'inherit',
						'post_type' => 'pins',
						'custom_fields' => array(
							'vp_embed_entry' => $eid,
							'vp_source_url' => $source
						)
					);	
					if( !empty($entry_post_id) ){
						$post_entries[$order]['ID'] = $entry_post_id;
						$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
					}
				}
				
			}
			else if( $entry_type == 'quiz' || $entry_type == 'poll' || $entry_type == 'polls' ) {
				
				$error = 0;
				$title = @esc_html( $_POST['entry_text'][$i] );
				$desc = @esc_html( $_POST['entry_desc'][$i] );
				$correct_answer = @(int)$_POST['correct_answer'][$i];
				$answers = @$_POST['quiz_answer'][$i];
				$image_answers = @$_POST['quiz_images'][$i];
				$entry_post_id = @(int)$_POST['entry_post_id'][$i];
				$entry_ans_post_ids = @$_POST['entry_ans_post_id'][$i];
				$img = @soft_validate_image( $_POST['entry_images'][$i] );
				$ans_results = @$_POST['quiz_answer_result_p1'][$i];
				$source = @esc_url( $_POST['entry_source'][$i], array( 'http', 'https' ) );
				
				$explain_title = @esc_html( $_POST['explain_text'][$i] );
				$explain_desc = @esc_html( $_POST['explain_desc'][$i] );
				$explain_images = @soft_validate_image( $_POST['explain_images'][$i] );
				
				if( $open_list ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Poll and quiz cannot be added to open list', 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if ( !empty( $explain_images ) && !is_numeric( $explain_images ) && $vp_instance->settings['hotlink_image'] == 0 ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Explanation image is not valid. Image hotlinking is disabled or invalid image', 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				$explain = '';
				if( !empty( $explain_title ) || !empty( $explain_desc ) || !empty( $explain_images ) ) {
					$explain = array( 'title' => $explain_title, 'desc' => $explain_desc, 'image' => $explain_images );
					$explain = serialize( $explain );	
				}
				
				if( ( empty($answers) || !is_array($answers) ) && ( empty($image_answers) || !is_array($image_answers) ) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Valid answers either in text or image required for this entry' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if( ( empty( $correct_answer) ) && $entry_type == 'quiz' && $fixed_ans ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'A correct answer required for this entry' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if( !empty($entry_post_id) ) {
					if ( !current_user_can( 'edit_post', $entry_post_id ) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Invalid post update request', 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}	
				}
				
				if ( !empty( $img ) &&  ( !is_numeric( $img ) && $vp_instance->settings['hotlink_image'] == 0 ) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Image hotlinking is disabled or invalid image', 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if( empty($title) && empty($img) && $entry_type == 'quiz' ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Either an image or a question required for this entry' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				else if( empty($title) && ( $entry_type == 'poll' || $entry_type == 'polls' ) ) {
					$this->error_selectors[] = $n;
					$this->error_selectors_msg[] = __( 'Polls must have text questions' , 'viralpress' );
					$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
					$error = 1;
				}
				
				if( empty($error) ):
					
					$ans_entries = array();
					$ans_entries_ids = array();
					
					foreach( $answers as $j => $answer) {
						$ans = esc_html( $answer );
						$ans_img = @soft_validate_image( $image_answers[$j] );
						$ans_id = @(int)$entry_ans_post_ids[$j];
						$ans_res = @$ans_results[$j];
						
						if( empty($ans) && empty($ans_img) && $entry_type == 'quiz' ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Either an image or a question required for each answer of this entry' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;	
							break;
						}
						else if( empty($ans) && ( $entry_type == 'poll' || $entry_type == 'polls' ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'Poll options must have textual answers' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;
							break;
						}
						else if( $quiz_type == 'person1' && empty( $ans_res ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'You must assign a result for each answer in this question' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;
							break;
						}
						
						if ( !empty( $ans_img ) &&  ( !is_numeric( $ans_img ) && $vp_instance->settings['hotlink_image'] == 0 ) ) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'One or more answer images are not valid in this entry. Image hotlinking is disabled or invalid image', 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;
						}
						
						if( !empty($ans_id) &&  !current_user_can( 'edit_post', $ans_id )) {
							$this->error_selectors[] = $n;
							$this->error_selectors_msg[] = __( 'One or more answer update requests are not valid in this entry' , 'viralpress' );
							$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
							$error = 1;
							break;
						}			
												
						$data_ans = array(
							'post_title' => $ans,
							'post_content' => '',
							'post_category' => $category_ids,
							'post_status' => 'inherit',
							'post_type' => $entry_type == 'poll' ? 'polls' : 'quiz',
							'custom_fields' => array(
								'vp_quiz_image_entry' => $ans_img,
								'vp_ans_result' => $ans_res
							)
						);
						
						if( is_numeric( $img ) ) {						
							$this->image_ids[] = $img;
							$img_attr = wp_get_attachment_image_src( $ans_img );
							$long_a = $img_attr[2]; //only height
							if( $long_a > $long_image_area ){
								$long_image = $img;
								$long_image_area = $long_a;
							}
						}
							
						if( !empty($ans_id) ){
							$data_ans['ID'] = $ans_id;
							$data_ans['post_author'] = get_post_field( 'post_author', $ans_id );
						}
						$ans_entries[] = $data_ans;
						$max_score++;
					}
					
					if( empty($ans_entries) ) {
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'No valid answer found for this entry' , 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}
					else{
						foreach( $ans_entries as $k => $ans_entry ) {
							$custom = array();
							if( !empty( $ans_entry['custom_fields'] ) ) {
								$custom = $ans_entry['custom_fields'];
								unset( $ans_entry['custom_fields'] );	
							}
							
							if( empty($ans_entry['post_title']) && empty($ans_entry['post_content']) )$ans_entry['post_title'] = 'NO_TITLE';
							
							$func = 'wp_insert_post';
							$pid = $func( $ans_entry );
							
							if( !is_wp_error($pid) && $pid ) {
								foreach( $custom as $k => $v ) {
									add_update_post_meta( $pid, $k, $v);	
								}
								$ans_entries_ids[] = $pid;
								$_POST['entry_ans_post_id'][$i][$k] = $pid;	
								$this->ans_ids[] = $pid;
							}
						}	
					}
					
					if( !empty( $ans_entries_ids ) && empty( $this->error ) ) {
						$post_entries[$order] = array(
							'post_title' => $title,
							'post_content' => $desc,
							'post_category' => $category_ids,
							'post_status' => 'inherit',
							'post_type' => $entry_type == 'poll' ? 'polls' : 'quiz',
							'custom_fields' => array(
								'vp_answer_entry' => implode( ',', $ans_entries_ids ),
								'vp_correct_answer' => $correct_answer,
								'vp_explain_ans' => $explain,
								'vp_source_url' => $source
							)
						);	
						if( !empty($entry_post_id) ){
							$post_entries[$order]['ID'] = $entry_post_id;
							$post_entries[$order]['post_author'] = get_post_field( 'post_author', $entry_post_id );
							
							//--start of delete removed answers
							$olds = get_post_meta( $entry_post_id, 'vp_answer_entry' );
							if( !empty($olds) ) {
								$olds = end( $olds );
								$old_post_ids = explode( ',', $olds );
								
								foreach( $old_post_ids as $olds ) {
									if( !in_array( $olds, $ans_entries_ids) )wp_delete_post( $olds, 1 );	
								}
							}
							//--end of delete removed answers
								
						}
						if( !empty($img) ){
							$post_entries[$order]['custom_fields']['vp_quiz_image_entry'] = $img;
							$img_attr = wp_get_attachment_image_src( $img );
							$long_a = $img_attr[2]; //only height
							if( $long_a > $long_image_area ){
								$long_image = $img;
								$long_image_area = $long_a;
							}
						}
					}
					else{
						$this->error_selectors[] = $n;
						$this->error_selectors_msg[] = __( 'Failed to add answer for this entry' , 'viralpress' );
						$this->error = __( 'Please fill up all the required fields for each entries. Check errors marked below.' , 'viralpress' );
						$error = 1;
					}
				endif;
			}
			
			$post_entries[$order]['custom_fields']['vp_show_numbers'] = $entry_show_num; 
			
		endforeach;
		
		ksort( $post_entries );
		if( $sort_order == 'desc' )$post_entries = array_reverse( $post_entries, true );
		
		//no error? ok add post now..
		if( empty($this->error) ) {
			
			$score_ids = array();
			$post_ids = array();
			$post_contents = array();
			
			$total = count( $post_entries );
			$total_added = 0;
			$added = 0;
			$last_poll_entry = -1;
			
			if( $has_preface ) $post_contents[] = '[vp_post_entry type="post_preface" id="-1" order="-1"]';;
			
			$o = 0;
			foreach( $post_entries as $i => $post ) :
				$custom = array();
				if( !empty( $post['custom_fields'] ) ) {
					$custom = $post['custom_fields'];
					unset( $post['custom_fields'] );	
				}
				
				if( empty($post['post_title']) && empty($post['post_content']) )$post['post_title'] = 'NO_TITLE';
				
				$func = 'wp_insert_post';
				$pid = $func( $post );
				
				if( !is_wp_error($pid) && $pid ) {
					foreach( $custom as $k => $v ) {
						add_update_post_meta( $pid, $k, $v);	
					}
					
					$added++;
					$total_added++;
					
					//if( !empty( $post_entries[$i]['custom_fields']['vp_show_numbers'] ) ) $o++;
					$o++;
					
					$post_contents[] = '[vp_post_entry type="'.$post['post_type'].'" id="'.$pid.'" order="'.$o.'"]';
					if( $post['post_type'] == 'polls' && $post_type != 'polls' ){
						$post_contents[] = '';
						$last_poll_entry = count( $post_contents );
					}
					
					if( $items_per_page && $items_per_page <= $added && $total_added < $total && !in_array( $post_type, array( 'quiz', 'polls' ) ) ){
						$post_contents[] = '<!--nextpage-->';
						$added = 0;	
					}
					$post_ids[] = $pid;
					$this->child_ids[] = $pid;	
				}
			endforeach;
			
			if( empty($post_ids) ){
				$this->error = __( 'Failed to add post', 'viralpress' );
				return false;	
			}
			else if( $post_type == 'quiz' ) {
				if( !empty($score_entries) ) {
					foreach( $score_entries as $i => $post ) {
						$custom = array();
						if( !empty( $post['custom_fields'] ) ) {
							$custom = $post['custom_fields'];
							unset( $post['custom_fields'] );	
						}
						
						if( empty($post['post_title']) && empty($post['post_content']) )$post['post_title'] = 'NO_TITLE';
						
						$func = 'wp_insert_post';
						$pid = $func( $post );
						
						if( !is_wp_error($pid) && $pid ) {
							foreach( $custom as $k => $v ) {
								add_update_post_meta( $pid, $k, $v);	
							}
							$score_ids[] = $pid;
							$this->child_ids[] = $pid;	
						}	
					}
					$post_contents[] = '[vp_score_entry]';	
				}
				if( empty($score_ids) ) {
					$this->error = __( 'Failed to add scores', 'viralpress' );
					return false;	
				}	
			}
			else if( $post_type == 'polls' ) {
				$post_contents[] = '[vp_poll_entry]';	
			}
			else if( !empty( $last_poll_entry ) && $last_poll_entry != -1 ) {
				$post_contents[ $last_poll_entry - 1 ] = '[vp_poll_entry]';	
			}
			
			if(!empty( $post_id) ){
				$p_status = get_post_status( $post_id );
				if( $p_status == 'publish' && $post_status == 'publish' )$status = 'pending';
				else if( $p_status == 'publish' && $post_status == 'draft' )$status = 'draft';
				else if( $p_status == 'draft' && $post_status == 'publish' )$status = 'pending';
				else if( $p_status == 'draft' && $post_status == 'draft' )$status = 'draft';
				else $status = $p_status;
			}
			else{
				if( $post_status == 'draft' )$status = 'draft';
				else{					
						$auto_publish = $vp_instance->settings['auto_publish'];
						if( $auto_publish )$status = 'publish';
						else $status = 'pending';
					}
			}
			
			$post_contents = array_filter( $post_contents, 'strlen' );
			
			if( current_user_can( 'administrator' ) && $status == 'pending' ) $status = 'publish';
			
			if( $post_type == 'quiz' && !preg_match('/^quiz/i', $post_title) ) {
				$post_title = apply_filters( 'viralpress_quiz_prefix' , __( 'Quiz', 'viralpress' ).': ' ).$post_title;	
			}
			else if( $post_type == 'polls' && !preg_match('/^poll/i', $post_title) ) {
				$post_title = apply_filters( 'viralpress_poll_prefix' , __( 'Poll', 'viralpress' ).': ' ).$post_title;	
			}
			
			if( !empty( $vp_instance->settings['cat_tag'][$post_type]['tag'] ) ) {
				$tags = array( $vp_instance->settings['cat_tag'][$post_type]['tag'] );
			}
			//else $tags = array( $post_type );
			
			if( !empty( $post_tag ) ) {
				foreach( $post_tag as $pp ) {
					$tags[] = esc_html( $pp );
				}
			}
			
			$tags = array_unique( $tags );
			$tags = apply_filters( 'vp_post_tags', $tags, $post_type );
			
			if( empty( $post_id ) ){
				$func = 'wp_insert_post';
			}
			else $func = 'wp_update_post';
			
			$post = array(
				'post_title' => $post_title,
				'post_excerpt' => $post_summary,
				'post_content' => implode("", $post_contents),
				'post_category' => $category_ids,
				'post_status' => $status,
				'post_type' => 'post',//$post_type,
				'tags_input' => $tags,
				'comment_status' => 'open'
			);
			
			//var_dump($post);exit;
			
			$old_post_ids = array();
			
			if( !empty($post_id) ) {
				if( !vp_lock_post( $post_id ) ) {
					$this->error = __( 'Failed to lock post for editing. Please retry after few seconds' , 'viralpress' );
					return false;	
				}
			}
			
			if( !empty($post_id) ){
				$post['ID'] = $post_id;
				$post['post_author'] = get_post_field( 'post_author', $post_id );
				$this->post_id = $post_id;
				
				$olds = get_post_meta( $post_id, 'vp_child_post_ids' );
				$olds = end( $olds );
				$old_post_ids = explode( ',', $olds );
				
				foreach( $old_post_ids as $olds ) {
					if( !in_array( $olds, $post_ids) ){
						wp_delete_post( $olds, 1 );	
					}
				}
				
				//--start of delete removed score sheets
				if( $post_type == 'quiz' ) {
					$olds = get_post_meta( $post_id, 'vp_score_entry' );					
					if( !empty($olds) ) {
						$olds = end( $olds );
						$old_post_ids = explode( ',', $olds );
						
						foreach( $old_post_ids as $olds ) {
							if( !in_array( $olds, $score_ids) )wp_delete_post( $olds, 1 );	
						}		
					}
				}
				//--end of delete removed score sheets
			}
			
			$pid = $func( $post );
			
			if( !is_wp_error($pid) ) {
				
				if( !empty($post_thumbnail) )
					set_post_thumbnail( $pid, $post_thumbnail );
				else if( !empty($long_image) )
					set_post_thumbnail( $pid, $long_image );
				else {
					if( !empty( $video_thumbs ) ) {
						if( !has_post_thumbnail( $pid ) ) {
							foreach( $video_thumbs as $vv ) {
								$kk = get_video_thumb_from_url( $vv, $pid );
								if( $kk ) {
									$long_image = $kk;
									set_post_thumbnail( $pid, $long_image );
									break;
								}
							}
						}
					}	
				}
				
				$this->post_id = $pid;
				add_update_post_meta( $pid, 'vp_post_type', $post_type);	
				add_update_post_meta( $pid, 'vp_child_post_ids', implode(',', $post_ids));
				add_update_post_meta( $pid, 'vp_items_per_page', $items_per_page);
				add_update_post_meta( $pid, 'vp_sort_order', $sort_order);
				add_update_post_meta( $pid, 'vp_show_numbers', $show_numbers);
				add_update_post_meta( $pid, 'vp_list_style', $list_style );
				add_update_post_meta( $pid, 'vp_list_display', $list_display );
				
				if(!empty($allow_copy)){
					add_update_post_meta( $pid, 'vp_allow_copy', 1 );	
				}
				else add_update_post_meta( $pid, 'vp_allow_copy', 0 );
				
				if(!empty($open_list)){
					add_update_post_meta( $pid, 'vp_open_list', 1 );	
				}
				else add_update_post_meta( $pid, 'vp_open_list', 0 );
				
				if( $has_preface ) {
					add_update_post_meta( $pid, 'vp_has_preface', 1);	
					add_update_post_meta( $pid, 'vp_preface_title', $preface_title);	
					add_update_post_meta( $pid, 'vp_preface_desc', $preface_desc);
					add_update_post_meta( $pid, 'vp_preface_image', $preface_image);
				}
				else {
					add_update_post_meta( $pid, 'vp_has_preface', 0);
					delete_post_meta( $pid, 'vp_preface_title' );
					delete_post_meta( $pid, 'vp_preface_desc' );
					delete_post_meta( $pid, 'vp_preface_image' );
				}
				
				if( !empty( $this->image_ids ) ) {
					foreach( $this->image_ids as $ii ) {
						wp_update_post( array(
								'ID' => $ii,
								'post_parent' => $pid
							)
						);	
					}	
				}
				
				if( !empty( $voting_till ) )add_update_post_meta( $pid, 'vp_voting_open_till', $voting_till );
				else delete_post_meta( $pid, 'vp_voting_open_till' );
				
				if( $post_type == 'quiz' ){
					add_update_post_meta( $pid, 'vp_fixed_ans', $fixed_ans);
					add_update_post_meta( $pid, 'vp_quiz_type', $quiz_type);	
				}
				if( !empty($score_ids) ) add_update_post_meta( $pid, 'vp_score_entry', implode(',', $score_ids) );
				if( !empty( $long_image ) ) add_update_post_meta( $pid, 'vp_list_image_entry', $long_image); 
				
				if( $status == 'draft' )
					$this->message = __( 'Your post has been saved as draft.', 'viralpress' );
					
				else if( $status == 'publish' )
					$this->message = __( 'Your post has been published.', 'viralpress' );
					
				else if( $status == 'pending' )
					$this->message = __( 'Your post has been added to queue and will be published after admins review them.', 'viralpress' );
					
				$this->post_link = get_permalink( $pid );
				
				vp_unlock_post( $pid );
				if( $pid != $post_id )vp_unlock_post( $post_id );
				
				if( $status == 'publish' )vp_bp_add_activity( $pid );
				return true;	
			}
			
			if( !empty( $post_id ) ) vp_unlock_post( $post_id );
			
			$this->error = __( 'Failed to add post', 'viralpress' );
			return false;
		}
		
	}
	
	static function render_post_entry( $atts )
	{
		global $post, $vp_instance, $wpdb;
	
		$html = '';
	
		if( !is_single() && empty( $vp_instance->temp_vars['bypass_single'] ) )return;
		
		$meta_table = $wpdb->prefix.'postmeta';
		$post_table = $wpdb->prefix.'posts';
		$show_numbers = get_post_meta( $post->ID, 'vp_show_numbers' );
		$show_numbers = @(int)$show_numbers[0];
		
		$ptitle = '';
		$pcontent = '';
		
		if( !isset( $vp_instance->temp_vars['post_author'] ) ) {
			$vp_instance->temp_vars['post_author'] = get_post_field( 'post_author', $post->ID );
		}
		if( !isset( $vp_instance->temp_vars['open_list'] ) ) {
			$cc = get_post_meta( $post->ID, 'vp_open_list' );
			$cc = @(int)$cc[0];
			$vp_instance->temp_vars['open_list'] = $cc;
		}
		
		/*
		if( !isset( $vp_instance->temp_vars['comments_per_list'] ) ) {
			$vp_instance->temp_vars['comments_per_list'] = get_post_meta( $post->ID, 'vp_comments_per_list' );
			$vp_instance->temp_vars['comments_per_list'] = @(int)$vp_instance->temp_vars['comments_per_list'][0];
		}
		*/
		
		$open_list = $vp_instance->temp_vars['open_list']; 
		$post_author = $vp_instance->temp_vars['post_author'];
		$cls = '';
		if( empty( $show_numbers ) )$cls = 'vp-noborder';
		
		$child_post_id = (int)$atts['id'];
		//if( empty( $child_post_id ) )return;
		$type = $atts['type'];
		$order = @(int)$atts['order'];
		
		$current_user_is_admin = current_user_can( 'administrator' );
		
		/**
		 * preview requested
		 */
		if( $order == 1 && !empty( $_REQUEST['vp_preview'] ) ) {
			
			$preview_id = (int)$_REQUEST['vp_preview'];
			$preview_post = get_post( $preview_id );
			
			if( $preview_post->post_author == get_current_user_id() || $current_user_is_admin ) {			
				if( $preview_post instanceof WP_Post ) {
					$html .= '<style>.vp-preview fieldset{border:none !important;}.vp-preview legend{display: none}.box_list,.inline_list{display:none}</style>';
					$html .= '<div class="vp-clearfix-lg"></div><div class="vp-preview"><ul id="vp-tab"><li class="active vp-op-list">';
					$html .= do_shortcode( '[vp_post_entry type="'.$preview_post->post_type.'" id="'.$preview_id.'" order="0"]' );
					$html .= '</li></ul>';
					$html .= '<script>jQuery(document).ready(function($){$("html,body").animate({scrollTop: $(".vp-preview").offset().top}, "fast");})</script>';
					$html .= '</div>';
				}
			}
		}
		
		$child_post = get_post( $child_post_id );
		
		if ( $child_post instanceof WP_Post ) {
			
			$child_author = get_post_field( 'post_author', $child_post->ID );
			
			if( !isset( $vp_instance->temp_vars['child_post_count'] ) ) {
				$child_post_ids = get_post_meta( $post->ID, 'vp_child_post_ids' );
				$vp_instance->temp_vars['child_post_count'] = @count( explode( ',', $child_post_ids[0] ));
				$vp_instance->temp_vars['child_posts'] = @$child_post_ids[0];
			}
			
			$child_post_count = $vp_instance->temp_vars['child_post_count'];
			if( $order == 1 ) {
				$q = $wpdb->get_results("SELECT post_id FROM $meta_table WHERE meta_key = 'vp_ad_pos' AND meta_value = 'begin' LIMIT 1", ARRAY_A);
				$ad_id = @$q[0]['post_id'];
			}
			else if( $order == $child_post_count) {
				$q = $wpdb->get_results("SELECT post_id FROM $meta_table WHERE meta_key = 'vp_ad_pos' AND meta_value = 'end' LIMIT 1", ARRAY_A);
				$ad_id = @$q[0]['post_id'];	
			}
			else {
				$q = $wpdb->get_results("SELECT post_id FROM $meta_table WHERE meta_key = 'vp_ad_index' AND meta_value = '".( $order - 1 )."' LIMIT 1", ARRAY_A);
				$ad_id = @$q[0]['post_id'];	
			}
			
			if( !empty( $ad_id ) &&  $order != $child_post_count && empty( $vp_instance->temp_vars['bypass_single'] ) ) {
				$html .= vp_print_ad( $ad_id );
			}
			
			$l_show_numbers = get_post_meta( $child_post->ID, 'vp_show_numbers' );
			$l_show_numbers = @(int)$l_show_numbers[0];
			
			if( !$l_show_numbers ){
				$show_numbers = 0;
				$cls = 'vp-noborder';
			}
			
			if( $show_numbers || $open_list ){
				if( empty( $vp_instance->temp_vars['order'] ) ) {
					
					$sorder = get_post_meta( $post->ID, 'vp_sort_order');
					$sorder = @end($sorder);
					
					$sql = @$wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'vp_show_numbers' AND post_id IN (".$vp_instance->temp_vars['child_posts'].") ORDER BY FIND_IN_SET( post_id, '".$vp_instance->temp_vars['child_posts']."' )" );
					$results = $wpdb->get_results( $sql, ARRAY_A );
					
					if( $sorder == 'desc' ) $results = array_reverse( $results );
					
					$ii = 1;
					$order_array = array();
					foreach( $results as $r ) {
						if( $r['meta_value'] == 1 ) $order_array[ $r['post_id'] ] = $ii++;	
					}
					$vp_instance->temp_vars['order'] = $order_array;
				}
				
				if( !empty( $vp_instance->temp_vars['order'][$child_post->ID] ) )$list_order = $vp_instance->temp_vars['order'][$child_post->ID];
				else $list_order = $order;
			}
			else $list_order = $order;
			
			if( $type == 'text' || $type == 'news' ) {
				$content = '';
				$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
				$html .= '<fieldset class="'.$cls.'">';
				list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );
				$html .= $pbody;
				$html .= '</fieldset>';
				$html .= '</div>';
			}
			else if( $type == 'lists' ) {
				
				$img = get_post_meta( $child_post_id, 'vp_list_image_entry');
				$img = @end($img);
				
				if( is_numeric( $img ) ) {
					$link = wp_get_attachment_image( $img, 'large' ); 
					$link_data = vp_get_attachment( $img );
				}
				else $link = '<a href="'.$img.'" class="vp-list-image-link"><img src="'.$img.'" class="vp-list-image"/></a>';
				
				$content = $link;
			
				if( !empty($link_data['caption'] ) ) $content .= '<div class="vp-media-caption">'.esc_html( htmlspecialchars_decode( $link_data['caption'] ) ).'</div>';
				
				$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
				$html .= '<fieldset class="'.$cls.'">';
				list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );
				$html .= $pbody;
				$html .= '</fieldset>';
				$html .= '</div>';
			}
			else if( $type == 'gallery' ) {
				$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
				$html .= '<fieldset class="'.$cls.'">';
				$content = prepare_gallery( $child_post );				
				list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );
				$html .= $pbody;
				$html .= '</fieldset>';
				$html .= '</div>';
			}
			else if( $type == 'playlist' ) {
				$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
				$html .= '<fieldset class="'.$cls.'">';
				$content = prepare_playlist( $child_post );				
				list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );
				$html .= $pbody;
				$html .= '</fieldset>';
				$html .= '</div>';
			}
			else if( $type == 'videos' ) {
				$vid = get_post_meta( $child_post_id, 'vp_video_entry');
				$vid = @end($vid);
				
				if( !is_numeric( $vid ) ) {
					if( verify_embed_entry_value($vid) ) {
						$elem_id = rand().time();
						$code = get_embed_code( $vid, $elem_id );
						$content = '<div id="'.$elem_id.'" class="vp-videowrapper">'.$code.'</div>';
						
						$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
						$html .= '<fieldset class="'.$cls.'">';
						list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );	
						$html .= $pbody;				
						$html .= '</fieldset>';
						$html .= '</div>';
					}
				}
				else {
					$link = wp_video_shortcode( array( 'src' => wp_get_attachment_url( $vid ) ) ); 
					$link_data = vp_get_attachment( $vid );
				
					$content = '<div class="vp-video">'.$link.'</div>';
					if( !empty($link_data['caption'] ) ) $content .= '<div class="vp-media-caption">'.esc_html( htmlspecialchars_decode( $link_data['caption'] ) ).'</div>';
					
					$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
					$html .= '<fieldset class="'.$cls.'">';
					list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );
					$html .= $pbody;
					$html .= '</fieldset>';
					$html .= '</div>';	
				}
			}
			else if( $type == 'audio' ) {
				$vid = get_post_meta( $child_post_id, 'vp_audio_entry');
				$vid = @end($vid);
				if( !is_numeric( $vid ) ) {
					if( verify_embed_entry_value($vid) ) {
						$elem_id = rand().time();
						$code = get_embed_code( $vid, $elem_id );
						$content = '<div id="'.$elem_id.'"  class="vp-videowrapper">'.$code.'</div>';
						
						$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
						$html .= '<fieldset class="'.$cls.'">';
						list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );	
						$html .= $pbody;
						$html .= '</fieldset>';
						$html .= '</div>';
					}
				}
				else {
					$link = wp_audio_shortcode( array( 'src' => wp_get_attachment_url( $vid ) ) ); 
					$link_data = vp_get_attachment( $vid );
				
					$content = '<div class="vp-clearfix"></div><div class="vp-audio">'.$link.'</div>';
					if( !empty($link_data['caption'] ) ) $content .= '<div class="vp-media-caption">'.esc_html( htmlspecialchars_decode( $link_data['caption'] ) ).'</div>';
					
					$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
					$html .= '<fieldset class="'.$cls.'">';
					list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );
					$html .= $pbody;
					$html .= '</fieldset>';
					$html .= '</div>';		
				}
			}
			else if( $type == 'pins' ) {
				$vid = get_post_meta( $child_post_id, 'vp_embed_entry');
				$vid = @end($vid);
				if( verify_embed_entry_value($vid) ) {
					
					$elem_id = rand().time();
					$code = get_embed_code( $vid, $elem_id );
					$content = '<div id="'.$elem_id.'" class="vp-pins">'.$code.'</div>';
					
					$html .= '<div class="vp-entry" id="vp-entry-'.$child_post_id.'" data-rel="'.$child_post_id.'">';
					$html .= '<fieldset class="'.$cls.'">';
					list( $ptitle, $pcontent, $pbody ) = display_list( $post, $child_post, $content, $show_numbers, $list_order );	
					$html .= $pbody;
					$html .= '</fieldset>';
					$html .= '</div>';
				}
				
				$html .= vp_get_template_html( 'sdks' );
			}
			else if( $type == 'quiz' || $type == 'polls' ) {
				
				$fixed_ans = get_post_meta( $post->ID, 'vp_fixed_ans' );
				if( !empty( $fixed_ans ) ) $fixed_ans = end( $fixed_ans );
				
				$img = get_post_meta( $child_post_id, 'vp_quiz_image_entry');
				
				if( !empty($img[0]) ){
					if( is_numeric( $img[0] ) ) $link = wp_get_attachment_image( $img[0], 'large' ); 
					else $link = '<a href="'.$img[0].'" class="vp-list-image-link"><img src="'.$img[0].'" class="vp-list-image"/></a>';
				}
				
				$list_style = '';
				
				$html .= '<div class="vp-entry quiz-unchecked" id="vp-entry-'.$child_post_id.'">';
				$html .= '<fieldset class="'.$cls.'">';
				if( $show_numbers ){
					$list_style = vp_get_list_style( $post->ID );
					if( $list_style == 'legend' )$html .= '<legend>'.$list_order.'</legend>';
				}
				$html .= '<h3 class="quiz_title" data-rel="'.$post->ID.'">';
				$html .= ( $list_style == 'inline' ? 
					'<span class="inline_list">'.$list_order.'.</span> &nbsp;' : ( $list_style == 'boxed' ? '<span class="box_list">'.$list_order.'</span>' : '' ));
				if( !empty($child_post->post_title) && $child_post->post_title != 'NO_TITLE' )
					$html .= $child_post->post_title;
				$html .= '</h3>';
				
				if( !empty($link) )$html .= '<div class="vp-quiz-img">'.$link.'</div><div class="vp-clearfix"></div>';
				
				if( !empty($child_post->post_content) )
				$html .= '<div class="vp-clearfix"></div>
					  <div class="entry">'.vp_format_content( $child_post->post_content ).'</div>
					  <div class="vp-clearfix"></div>';
				
				$source_url = '';
				$ss = get_post_meta( $child_post->ID, 'vp_source_url' );
				if( !empty( $ss[0]) ) $source_url = $ss[0];
				
				if( $source_url )$html .= '<div class="vp-media-caption">'.pretty_source( $source_url ).'</div><div class="vp-clearfix"></div>';
				
				$html .= __( 'Choose an answer', 'viralpress' ).'<div class="vp-clearfix"></div>';
				
				$answers = get_post_meta( $child_post_id, 'vp_answer_entry');
				if( !empty($answers) ){
					$answers = explode( ',', $answers[0] );
					
					$correct = get_post_meta( $child_post_id, 'vp_correct_answer' );
					$correct = @(int)$correct[0];
					
					$html .= '<div class="row quiz_ans_list" data-rel="'.$child_post_id.'">';
					
					$j = 0 ;
					$k = 0;
					
					$c = 12;
					$s = 1;
					$ss = 0;
					
					foreach( $answers as $ans ) {
						
						$rid = rand( 111111, 999999 );
						if( $correct == ++$k ) {
							if( ! ( $rid % 2 ) ) $rid++;	
						}
						else {
							if( ( $rid % 2 ) ) $rid++;
						}
						
						if( empty( $fixed_ans ) ) $rid = $k;
						
						$personal = get_post_meta( $ans, 'vp_ans_result' );
						$personal = @(int)$personal[0];
						
						$img = get_post_meta( $ans, 'vp_quiz_image_entry');
						if( !empty($img) ){
							
							if( is_numeric( $img[0] ) ) {
								$link = wp_get_attachment_image_src( $img[0], 'vp-quiz-image' );
								$link = $link[0];
							}
							else $link = $img[0];
							
							if( !$ss && $link ) {
								$ss = 1;
								$c = 4;
								$s = 3;								
								
								if( count( $answers ) <= 2 || count( $answers ) == 4 ) {
									$ss = 1;
									$c = 6;
									$s = 2;	
								}
							}
						}
						
						$title = get_the_title( $ans );
						if( $title == 'NO_TITLE' )$title = '';
						$html .= '<div class="col-lg-'.$c.'"><div class="quiz_ans_list_obs"></div><div class="quiz_row" data-rel="'.$ans.'">';
						if(!empty($link))$html .= '<div class="quiz_ans_img_'.$s.'" style="background-image: url(\''.$link.'\')"></div>';
						//<img class="vp-quiz-ans-image" src="'.$link.'"/></div><div class="vp-clearfix">
						$html .= '<div class="quiz_ans">
								<input type="checkbox" class="quiz_checkbox css-checkbox lrg" value="'.$rid.'" id="quiz_ans_'.$ans.'" data-personal="'.$personal.'"/>
								<label name="checkbox69_lbl" class="css-label lrg vlad"></label>
								<span class="quiz_ch_label" style="font-size:'.( $c == 12 ? '18px' : '14px' ).';">'.$title.'</span>
							  </div>';
						$html .= '</div></div>';
						
						if( ++$j >= $s ){
							$html .= '<div class="vp-clearfix"></div>';
							$j = 0;
						}
					}
					
					$html .= '</div>';
					
					$html .= vp_get_template_html( 'sdks' );
				}
				
				//exp
				$explain = get_post_meta( $child_post_id, 'vp_explain_ans' );
				$explain = end( $explain );
				if( !empty( $explain ) ) {
					$explain = unserialize( $explain );
					$img = $explain['image'];
					$text = '<h3>'.$explain['title'].'</h3><div class="vp-entry">'.$explain['desc'].'</div>';
					
					if( $img ){
						if( is_numeric( $img ) )$img = wp_get_attachment_image( $img, 'large' );
						else $img = '<a href="'.$img.'" class="vp-list-image-link"><img src="'.$img.'" class="vp-list-image"/></a>';	
					}
					
					$html .= '<div class="row quiz_ans_exp">
								'.(  $img ? '<div class="col-lg-6">' : '<div class="col-lg-12">' ).'
								'.( $img ? $img.'</div><div class="col-lg-6">'.$text.'</div>' : $text.'</div>' ).'
							</div>';	
				}
				
				$html .= '</fieldset>';
				$html .= '</div>';		
			}
		}
		else if( $type == 'post_preface' ) {
			$title = get_post_meta( $post->ID, 'vp_preface_title' );
			$title = @$title[0];
			
			$desc = get_post_meta( $post->ID, 'vp_preface_desc' );
			$desc = @$desc[0];
			
			$image = get_post_meta( $post->ID, 'vp_preface_image' );
			$image = @$image[0];	
			
			$html .= '<div class="vp-entry">';
			$html .= '<fieldset class="vp-noborder">';
			if( !empty($title) )
			$html .= '<h3>'.$title.'</h3>';
			
			if( !empty( $image ) ) {
				if( is_numeric( $image) ) {
					$link = wp_get_attachment_image( $image, 'large' ); 
					$link_data = vp_get_attachment( $image );
					
					if( !empty( $link ) ) {
						$html .= $link;
						if( !empty($link_data['caption'] ) ) $html .= '<div class="vp-media-caption">'.$link_data['caption'].'</div><div class="vp-clearfix-lg"></div>';
					}
				}
				else {
					$html .= $link = '<a href="'.$image.'" class="vp-list-image-link"><img src="'.$image.'" class="vp-list-image"/></a>';	
				}
			}
			
			if( !empty($desc) )
			$html .= '<div class="entry">'.vp_format_content( $desc ).'</div>';
			$html .= '</fieldset>';
			$html .= '</div>';
			
		}
		
		if( $type != 'quiz' && $type != 'poll' && $type != 'post_preface' && ( ( $open_list && !$vp_instance->settings['hide_vote_buttons_op'] && $show_numbers ) || ( $show_numbers && !$vp_instance->settings['hide_vote_buttons'] ) ) ) {
			$u = esc_js( esc_url( get_permalink().'#vp-entry-'.$child_post_id ) );
			$i = '';
			if( !empty( $link_data['src'] ) ) $i = $link_data['src'];
			$d = esc_js( htmlspecialchars_decode( $pcontent ) );
			$c = esc_js( htmlspecialchars_decode( $ptitle == 'NO_TITLE' ? '' : $ptitle ) );
			
			$editable = 0;
			
			if( is_user_logged_in() )$uid = get_current_user_id();
			else {
				$uid = @$_COOKIE['vp_unan'];
				if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
					$uid = '';	
				}	
			}
			
			if( ( $current_user_is_admin || $child_author == $uid ) && $child_post->post_status == 'vp_open_list' ) $editable = 1;
			
			$html .= '<div class="vp-author-info">';
			$html .= '<div class="vp-clearifx"></div>';
			$html .= '<hr/>';
			$html .= '<div class="vp-op-au-1 vp-op-au-5">
					<a href="javascript:void(0)" onclick="fb_sharer(\''.$u.'\', \''.$i.'\', \''.$c.'\')">
						<div class="fb-share block pointer" style="margin-top:-5px"></div>
					</a>
					<a target="_blank" href="//pinterest.com/pin/create%2Fbutton/?url='.urlencode($u).'&media='.urlencode($i).'&description='.urlencode( $d ? $d : $c ).'">
						<div class="pin-share block pointer" style="margin-top:5px"></div>
					</a>
				  </div>';
			$html .= '<div class="vp-op-au-1">';
			$html .= get_avatar( $child_author, 60 );
			$html .= '</div>';
			$html .= '<div class="vp-op-au-2">';
			$html .= __( 'Submitted by', 'viralpress' ).'<br/>';
			$html .= vp_get_author_url( $child_author );
			$html .= '</div>';
			
			
			if( empty( $vp_instance->settings['show_like_dislike'] ) ) {			
			
				if( $editable ) {
					$html .= '<div class="vp-op-au-4"><br/>';
					$html .= '<i class="glyphicon glyphicon-trash vp-pointer vp_open_list_del" data-rel="'.$child_post_id.'"></i>';
					$html .= '</div>';
				}
				
				$html .= do_shortcode( '[vp_post_upvote_buttons post_id="'.$child_post_id.'"]' );
			}
			else {
				
				$html .= do_shortcode( '[vp_post_like_buttons post_id="'.$child_post_id.'"]' );
				if( $editable )
					$html .= '<div class="vp-op-au-3">
							<i class="glyphicon glyphicon-trash vp-pointer vp_open_list_del" data-rel="'.$child_post_id.'"></i>
						  </div>' ;
			}
			
			$html .= '<div class="vp-clearifx"></div><div class="vp-clearfix"></div>';
			$html .= '</div>';
		}
		
		if( !empty( $ad_id ) &&  $order == $child_post_count ) {
			$html .= vp_print_ad( $ad_id );
		}
		
		if( $vp_instance->settings['fb_comments'] ) {
			$html .= vp_get_template_html( 'sdks' );	
		}
		
		//fb-comments 
		if( $type != 'quiz' && $type != 'polls' && ( ( $open_list && $show_numbers ) || $show_numbers ) && !empty( $vp_instance->settings['comments_per_list'] ) && is_single() ) {
			$html .= '<div class="vp-clearfix"></div><div class="fb-comm-waypoint" data-href="'.get_the_permalink().'#'.$child_post_id.'" data-numposts="5" data-width="100%">'.__( 'Loading comments. Please wait...', 'viralpress' ).'</div><div class="vp-clearfix"></div>';
		}
		
		return $html;
	}
	
	static function vp_score_entry()
	{
		if( !is_single() )return;
		global $post, $vp_instance;
		
		$html = '';
		
		$post_author = get_post_field( 'post_author', $post->ID );
		
		$fixed_ans = get_post_meta( $post->ID, 'vp_fixed_ans' );
		if( !empty( $fixed_ans ) ) $fixed_ans = end( $fixed_ans );
		
		$quiz_type = get_post_meta( $post->ID, 'vp_quiz_type' );
		if( !empty( $quiz_type ) ) $quiz_type = end( $quiz_type );
		
		if( empty( $quiz_type) && is_numeric( $fixed_ans ) ) {
			if( !$fixed_ans ) $quiz_type = 'person2';
			else $quiz_type = 'mcq';	
		};
		
		if( !is_numeric( $fixed_ans ) ) {
			if( in_array( $quiz_type, array( 'mcq', 'trivia' ) ) ) $fixed_ans = 1;
			else $fixed_ans = 0;	
		}
		
		$scores = get_post_meta( $post->ID, 'vp_score_entry' );
		$data = array();
		
		if( !empty($scores) ){
			$scores = explode( ',', $scores[0] );
			foreach( $scores as $score ) {
				$pdata = get_post( $score );
				$child_author = get_post_field( 'post_author', $pdata->ID );
				if( $child_author != $post_author ) continue;
				
				$t = $pdata->post_title;
				if( $t == 'NO_TITLE' )$t = '';
				$min = get_post_meta( $score, 'vp_quiz_score_from' );
				$max = get_post_meta( $score, 'vp_quiz_score_to' );
				$url = '';
				
				$img = get_post_meta( $score, 'vp_quiz_image_entry' );
				if( !empty($img) ){
					$img = $img[0];
					if( is_numeric( $img ) ) {
						$url = wp_get_attachment_image_src( $img, 'large' );
						$url = $url[0];
					}
					else $url = $img;	
				}
				
				$body = $pdata->post_content;
				
				$sss = '';
				$ss = get_post_meta( $score, 'vp_source_url' );
				if( !empty( $ss[0]) ) $sss = '<div class="vp-clearfix"></div><div class="vp-media-caption">'.pretty_source( $ss[0] ).'</div><div class="vp-clearfix"></div>';
				
				$data[] = array( 'min_score' => (int)$min[0], 'max_score' => (int)$max[0], 'title' => esc_js( htmlspecialchars_decode( $t ) ), 'desc' => esc_js(htmlspecialchars_decode( $body ) ), 'image' => $url, 'source' => esc_js( $sss ) );	
			}
		}
		
		if( !empty($data) ){
			add_thickbox();
			$u = esc_html( get_permalink() );
			if( !empty( $fixed_ans ) ) $t = esc_html( sprintf( __( 'I scored %s and got: %s on this quiz: %s', 'viralpress' ), '[myscore]', '[myresult]', get_the_title() ) ) ;
			else $t = esc_html( sprintf( __( 'I got: %s on this quiz: %s', 'viralpress' ), '[myresult]', get_the_title() ) ) ;
			
			$tsh = $vp_instance->settings['share_quiz_force'] ? esc_html( sprintf( __( 'I have taken this quiz on %s', 'viralpress' ), get_bloginfo( 'name' ) ) ) : $t;
			
			$html .= "<script>var quiz_type = '".$quiz_type."';var score_sheets = '".str_replace("\\\\'", "\\'", json_encode($data))."';var score_sheet_fixed_ans = '".( (int)$fixed_ans )."';</script>";
			$html .= 
			'<a href="#TB_inline?width=600&height=700&inlineId=score_modal" 
				title="'.__( 'Quiz results', 'viralpress' ).'" class="thickbox score_modal_link" style="display:none"></a>
				<div class="entry">
					<fieldset class="vp-noborder">
						<div id="score_modal" style="display:none">
							<div class="row">
								<div class="col-lg-12">
									<p>
										<h3 class="score_score quiz_hhh"></h3>
										<h3 class="score_title quiz_hhh"></h3>
										<div class="score_image quiz_hhh"></div>
										<div class="score_desc quiz_hhh"></div>
										<div class="shares">
											<h4>'.__( 'Share on social media', 'viralpress' ).'</h4>
											'.( $vp_instance->settings['share_quiz_force'] ? '<h4 class="mshare_quiz">'.__( 'You must share the quiz before seeing your result', 'viralpress' ).'</h4>' : '' ).'
											<div class="row">
												<div class="col-lg-6">
													<a target="_blank" href="javascript:void(0)" class="fb-share-quiz-res" data-href="'. $u .'">
														<div class="fb-share-lg block pointer"><div style="display:none">'. $tsh .'</div></div>
													</a>
												</div>
												'.( !$vp_instance->settings['share_quiz_force'] ? '
												<div class="col-lg-6">
													<a target="_blank" href="https://twitter.com/intent/tweet?source='. $u .'&text='. $tsh .':%20'. $u .'">
														<div class="tw-share-lg block pointer"></div>
													</a>
												</div>' : '' ).'							
											</div>
										</div>
									</p>
								</div>
							</div>
						</div>
					</fieldset>
				</div>';
		}
		
		return $html;
	}
	
	static function vp_poll_entry()
	{
		if( !is_single() )return;
		$post_id = get_the_ID();
		
		$html = '';
		
		if( is_user_logged_in() )$uid = get_current_user_id();
		else {
			$uid = @$_COOKIE['vp_unan'];
			if( empty($uid) || preg_match('/[^a-z0-9]/i', $uid) ) {
				$uid = '';	
			}	
		}
		
		if( $uid ) {
			$voted = has_user_voted( $post_id, $uid );
		}
		else $voted = '';
		
		$voting_till = get_post_meta( $post_id, 'vp_voting_open_till' );
		if( !empty( $voting_till ) ) $voting_till = end( $voting_till );
		
		$html .= '<div class="poll-feedback"></div>';
		if( empty($voted) ) {
			$html .= '<div class="poll_submit" style="display:none">
					<br/><br/>
					<button class="btn btn-info poll_submit_btn"><i class="glyphicon glyphicon-tasks"></i>&nbsp;&nbsp;'.__( 'Submit my votes', 'viralpress' ).'</button>
					<br/><br/>
					<p><small>'.__( 'Note: You cannot change vote once submitted', 'viralpress' ).'</small></p>
				  </div>';
		}
		else {
			$html .= '<p><small>'.__( 'You have submitted your vote. Thank you for your participation.' ).'</small></p>';
			$p = json_encode( get_poll_results( $post_id) );	  
		}
		
		if( !empty( $voting_till ) ) {
			if( strtotime( $voting_till ) < time() ) {
				$voted = 1;
				$html .= '<div class="vp-clearfix"></div><div class="alert alert-info">'.__( 'Voting has been closed', 'viralpress' ).'</div>';
				if( empty( $p ) ) $p = json_encode( get_poll_results( $post_id) );
			}
			else $html .= '<div class="vp-clearfix"></div><div class="alert alert-info">'.__( 'Voting will close on', 'viralpress' ).' '.$voting_till.'</div>';
		}
		
		
		$html .= '<div class="poll-results-p" style="display:none"><h3>'.__( 'Poll Results', 'viralpress' ).'</h3>
			  <div class="poll-results">	
			  </div></div>';
		
		
		$html .= '<script>var poll_submit = 1;var user_already_voted = '.( empty($voted) ? 0 : 1 ).';var user_votes = \''.$voted.'\';var poll_id = '.$post_id.';'.( !empty($p) ? 'print_poll_results(\''.$p.'\');' : '' ).';</script>';
		
		return $html;
	}
	
	public function render_editor( $attributes )
	{
		/*
		global $vp_instance;
		
		array_walk_recursive( $_POST, array( &$this, 'strip_shortcodes' ) );
		
		$this->render_html = '<script>var prevent_new_item=1;jQuery(document).ready(function($){';
		
		$title = @$_POST['post_title'];
		$summary = @$_POST['post_summary'];
		$thumb = @$_POST['post_thumbnail'];
		
		$preface_title = @$_POST['preface_title'];
		$preface_desc = @$_POST['preface_desc'];
		$preface_image = @$_POST['preface_image'];
		
		$quiz_type = @$_POST['quiz_type'];
		$fixed_ans = @(int)$_POST['fixed_answer'];
		$voting_till = @$_POST['voting_till'];
		
		$list_style = @verify_list_style( $_POST['list_style'] );
		$list_display = @verify_list_display( $_POST['list_display'] );
		
		if( !isset( $vp_instance->temp_vars['is_copying'] ) ) {
			$vp_instance->temp_vars['is_copying'] = $attributes['is_copying'];
		}
		
		$this->render_editor_top( $title, $summary, $thumb, $preface_title, $preface_desc, $preface_image );
		$entries = array();
		
		if( !empty($_POST['entry_type']) ):
			foreach( $_POST['entry_type'] as $i => $entry_type ):
				$order = (int)$_POST['entry_order'][$i];
				$entries[$order] = $i;
			endforeach;
		endif;

		ksort( $entries );
		$j = 0;
		
		$post_type = @esc_html($_POST['vp_post_type']);
		if( empty( $post_type ) || !vp_validate_post_type( $post_type ) ){
			$this->render_html = '';
			return false;
		}
		
		$this->render_html .= '$("#vp_post_type").val("'.$post_type.'");';
		
		$category_ids = @$_POST['cat'];
		$post_status = @$_POST['publication'] == 'draft' ? 'draft' : 'publish';
		$items_per_page = @(int)$_POST['items_per_page'];
		$sort_order = @$_POST['sort_order'] == 'asc' ? 'asc' : 'desc';
		$show_numbers = @empty($_POST['show_numbers']) ? 0 : 1;
		$allow_copy = @empty($_POST['allow_copy']) ? 0 : 1;
		$open_list = @empty($_POST['open_list']) ? 0 : 1;
		
		$category_id = array();
		foreach( @$category_ids as $cc ) {
			$cc = (int)$cc;
			$category_id[] = $cc;
		}
		
		$tags = @explode( ',', $_POST['post_tags'] );
		foreach( $tags as &$tt ) $tt = esc_js( htmlspecialchars_decode( $tt ) ); 
		if( empty( $tags ) ) $itags = '[]';
		else $itags = '["'.implode('","', $tags).'"]';
		
		$this->render_html .= '$("#cat").val(['.implode(',', $category_id).']);$("#cat option:selected").prependTo("#cat");$("#publication").val("'.$post_status.'");$("#items_per_page").val("'.$items_per_page.'");$("#sort_order").val("'.$sort_order.'");$("#post_tags").val("'.implode(',', $tags).'");$("#post_tags").tagEditor( { \'placeholder\': lang.add_tags, \'initialTags\' : '.$itags.' } );';
				
		if( $show_numbers )$this->render_html .= '$("#show_numbers").prop("checked", true);';
		else $this->render_html .= '$("#show_numbers").prop("checked", false);';
		
		if( $allow_copy )$this->render_html .= '$("#allow_copy").prop("checked", true);';
		else $this->render_html .= '$("#allow_copy").prop("checked", false);';
		
		if( $open_list )$this->render_html .= '$("#open_list").prop("checked", true);';
		else $this->render_html .= '$("#open_list").prop("checked", false);';
		
		if( !empty($fixed_ans) )$this->render_html .= 'quiz_fixed_ans = 1;$("#fixed_answer").prop("checked", true).trigger( "change" );';
		else $this->render_html .= 'quiz_fixed_ans = 0;$("#fixed_answer").prop("checked", false).trigger( "change" );';
		
		if( !empty($voting_till) ){
			$voting_till = esc_js( htmlspecialchars_decode( $voting_till ) );
			$this->render_html .= '$("#voting_till").val("'.$voting_till.'");$(".voting_till_span").show();';
		}

		$list_style = esc_js( $list_style );
		$this->render_html .= '$("#list_style").val("'.$list_style.'");';
		
		$list_display = esc_js( $list_display );
		$this->render_html .= '$("#list_display").val("'.$list_display.'");';
		
		foreach( $entries as $i ) {
			$type = @$_POST['entry_type'][$i] ;
			$pid = @$_POST['entry_post_id'][$i];
			
			if( $type == 'text' || $type == 'news'){
				@$this->render_news_entry( $_POST['entry_text'][$i], $_POST['entry_desc'][$i], $_POST['entry_show_num'][$i], @$_POST['entry_source'][$i], $pid );		
			}
			else if( $type == 'list' || $type == 'lists' ){
				$this->render_list_entry( $_POST['entry_text'][$i], $_POST['entry_desc'][$i], @$_POST['entry_images'][$i], @$_POST['entry_show_num'][$i], @$_POST['entry_source'][$i], $pid );
			}
			else if( $type == 'gallery' || $type == 'galleries' ){
				$this->render_gallery_entry( $_POST['entry_text'][$i], $_POST['entry_desc'][$i], @$_POST['entry_images'][$i], @$_POST['entry_show_num'][$i], @$_POST['entry_source'][$i], @$_POST['gal_type'][$i], @$_POST['gal_col'][$i], @$_POST['gal_autostart'][$i], $pid );
			}
			else if( $type == 'video' || $type == 'videos' ){
				$this->render_embed_entry( $_POST['entry_text'][$i], $_POST['entry_desc'][$i], @$_POST['entry_videos'][$i], @$_POST['entry_show_num'][$i], @$_POST['entry_source'][$i], $type, $pid );
			}
			else if( $type == 'audio' ){
				$this->render_embed_entry( $_POST['entry_text'][$i], $_POST['entry_desc'][$i], @$_POST['entry_audio'][$i], @$_POST['entry_show_num'][$i], @$_POST['entry_source'][$i], $type, $pid );
			}
			else if( $type == 'pin' || $type == 'pins' ){
				$this->render_embed_entry( $_POST['entry_text'][$i], $_POST['entry_desc'][$i], @$_POST['entry_pins'][$i], @$_POST['entry_show_num'][$i], @$_POST['entry_source'][$i], $type, $pid );
			}
			else if( $type == 'quiz' || $type == 'poll' || $type == 'polls' ){
				$this->render_quiz_entry( @$_POST['entry_text'][$i], @$_POST['entry_desc'][$i], @$_POST['entry_images'][$i], @$_POST['correct_answer'][$i], @$_POST['quiz_answer'][$i], @$_POST['quiz_images'][$i], @$_POST['entry_ans_post_id'][$i], $_POST['entry_show_num'][$i], $type, $pid );
			}
		}
		
		if( $attributes['post_type'] == 'quiz' ) {
			foreach( $_POST['quiz_score_from'] as $i => $s ) {
				$this->render_score_entry( @$_POST['entry_text'][$i], @$_POST['entry_desc'][$i],  @$_POST['entry_images'][$i], @(int)$_POST['entry_post_id'][$i], $s, @$_POST['quiz_score_to'][$i] );
			}		
		}
		
		$t = __( 'Edit post', 'viralpress' ).' - '.get_bloginfo( 'name' );
		
		$this->render_html .= 'document.title="'.$t.'";$(".editor_loader").hide();$("#add_new_post_form").show();});';
		$this->render_html .= '</script>';
		*/
	}
	
	public function render_post_editor( $post_id, $copy = 0 )
	{
		global $vp_instance;
		
		$this->render_html_after_person1_quiz = '';
		$this->render_html = '<script>var prevent_new_item=1;var prevent_item_scroll=1;jQuery(document).ready(function($){';
		
		$post = get_post( $post_id );
		
		$title = $post->post_title;
		$summary = $post->post_excerpt;
		$thumb = get_post_thumbnail_id( $post_id );
		
		$preface_title = '';
		$preface_desc = '';
		$preface_image = '';
		
		if( !isset( $vp_instance->temp_vars['is_copying'] ) ) {
			$vp_instance->temp_vars['is_copying'] = $copy;
		}
		
		$fixed_ans = '';
		$quiz_type = '';
		
		$fixed_ans = get_post_meta( $post->ID, 'vp_fixed_ans' );
		if( !empty( $fixed_ans ) ) $fixed_ans = end( $fixed_ans );
		
		$quiz_type = get_post_meta( $post->ID, 'vp_quiz_type' );
		if( !empty( $quiz_type ) ) $quiz_type = end( $quiz_type );
		else if( is_numeric( $fixed_ans ) ) {
			if( !$fixed_ans ) $quiz_type = 'person2';
			else $quiz_type = 'mcq';	
		};
		
		if( !is_numeric( $fixed_ans ) ) {
			if( in_array( $quiz_type, array( 'mcq', 'trivia' ) ) ) $fixed_ans = 1;
			else $fixed_ans = 0;	
		}
		
		$voting_till = get_post_meta( $post->ID, 'vp_voting_open_till' );
		if( !empty( $voting_till ) ) $voting_till = end( $voting_till );
		else $voting_till = '';
		
		$has_preface = get_post_meta( $post->ID, 'vp_has_preface' );
		if( !empty( $has_preface[0] ) ) {
			$preface_title = get_post_meta( $post->ID, 'vp_preface_title' );
			$preface_title = @$preface_title[0];
			
			$preface_desc = get_post_meta( $post->ID, 'vp_preface_desc' );
			$preface_desc = @$preface_desc[0];
			
			$preface_image = get_post_meta( $post->ID, 'vp_preface_image' );
			$preface_image = @$preface_image[0];
		}
		
		$list_style = vp_get_list_style( $post->ID );
		$list_display = vp_get_list_display( $post->ID );
				
		$this->render_editor_top( $title, $summary, $thumb, $preface_title, $preface_desc, $preface_image );
		
		$childs = get_post_meta( $post_id, 'vp_child_post_ids');
		$childs = end($childs);
		$childs = explode( ',', $childs );
		
		$order = get_post_meta( $post_id, 'vp_sort_order');
		$order = end($order);
		
		if( $order == 'desc' )$childs = array_reverse( $childs, true );
		
		$type = get_post_meta( $post_id,  'vp_post_type' );
		if( empty($type) ){
			$this->render_html = '';
			return false;
		}
		
		$this->render_html .= 'init_vp_editor("'.esc_js( esc_html( $type[0] ) ).'");';
		$this->render_html .= '$("#vp_post_type").val("'.$type[0].'");';
		
		$cats = get_the_category( $post_id );
		$category_id = array();
		foreach( $cats as $cc ) {
			$cc = (int)$cc->term_id;
			$category_id[] = $cc;
		}
		
		$post_status = $post->post_status == 'draft' ? 'draft' : 'publish';
		
		$eid = get_post_meta( $post_id, 'vp_items_per_page');
		$eid = end($eid);
		$items_per_page = @(int)$eid;
		
		$eid = get_post_meta( $post_id, 'vp_sort_order');
		$eid = end($eid);
		$sort_order = $eid == 'asc' ? 'asc' : 'desc';
		
		$eid = get_post_meta( $post_id, 'vp_show_numbers');
		$eid = end($eid);
		$show_numbers = @empty($eid) ? 0 : 1;
		
		$eid = get_post_meta( $post_id, 'vp_allow_copy');
		$eid = end($eid);
		$allow_copy = @empty($eid) ? 0 : 1;
		
		$eid = get_post_meta( $post_id, 'vp_open_list');
		$eid = end($eid);
		$open_list = @empty($eid) ? 0 : 1;
		
		$tags = array();
		$t = wp_get_post_tags( $post_id );
		foreach( $t as $tt ) {
			if( vp_validate_post_type( $tt->name ) || @strtolower( $vp_instance->settings['cat_tag'][$type[0]]['tag'] ) == strtolower( $tt->name ) ) continue;
			$tags[] = esc_js( htmlspecialchars_decode( $tt->name ) ); 
		}
		if( empty( $tags ) ) $itags = '[]';
		else $itags = '["'.implode('","', $tags).'"]';
		
		$this->render_html .= '$("#cat").val(['.implode(',', $category_id).']);$("#cat option:selected").prependTo("#cat");$("#publication").val("'.$post_status.'");$("#items_per_page").val("'.$items_per_page.'");$("#sort_order").val("'.$sort_order.'");$("#post_tags").val("'.implode(',', $tags).'");$("#post_tags").tagEditor( { \'placeholder\': vp_lang.add_tags, \'initialTags\' : '.$itags.' } );';
		
		if( $show_numbers )$this->render_html .= '$("#show_numbers").prop("checked", true);';
		else $this->render_html .= '$("#show_numbers").prop("checked", false);';
		
		if( $allow_copy )$this->render_html .= '$("#allow_copy").prop("checked", true);';
		else $this->render_html .= '$("#allow_copy").prop("checked", false);';
		
		if( $open_list )$this->render_html .= '$("#open_list").prop("checked", true);';
		else $this->render_html .= '$("#open_list").prop("checked", false);';
		
		/*
		if( !empty($fixed_ans) )$this->render_html .= 'quiz_fixed_ans = 1;$("#fixed_answer").prop("checked", true).trigger( "change" );';
		else $this->render_html .= 'quiz_fixed_ans = 0;$("#fixed_answer").prop("checked", false).trigger( "change" );';
		*/
		
		
		if( !empty($quiz_type) )$this->render_html .= 'quiz_fixed_ans = '.(int)$fixed_ans.';$("#quiz_type").val("'.$quiz_type.'").trigger( "change" );';
		
		if( !empty($voting_till) ){
			$voting_till = esc_js( htmlspecialchars_decode( $voting_till ) );
			$this->render_html .= '$("#voting_till").val("'.$voting_till.'");$(".voting_till_span").show();';
		}

		$list_style = esc_js( $list_style );
		$this->render_html .= '$("#list_style").val("'.$list_style.'");';
		
		$list_display = esc_js( $list_display );
		$this->render_html .= '$("#list_display").val("'.$list_display.'");';
		
		foreach( $childs as $child ) {
			
			$child_post = get_post( $child );
			
			$type = @$child_post->post_type ;
			$pid = $child;
			
			$l_show_numbers = get_post_meta( $child_post->ID, 'vp_show_numbers' );
			$l_show_numbers = @(int)$l_show_numbers[0];
			
			$source = get_post_meta( $child_post->ID, 'vp_source_url' );
			$source = @(string)$source[0];
			
			if( $type == 'text' || $type == 'news' ){
				$this->render_news_entry( $child_post->post_title, $child_post->post_content, $l_show_numbers, $source, $pid );		
			}
			else if( $type == 'list' || $type == 'lists' ){
				$eid = get_post_meta( $pid, 'vp_list_image_entry');
				$eid = end($eid);
				$this->render_list_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, $source, $pid );
			}
			else if( $type == 'gallery' || $type == 'galleries' ){
				$eid = get_post_meta( $pid, 'vp_list_image_entry');
				$eid = end($eid);
				$gal_type = get_post_meta( $pid, 'vp_gallery_type');
				$gal_type = @end($gal_type);
				$gal_col = get_post_meta( $pid, 'vp_gallery_col');
				$gal_col = @end($gal_col);
				$gal_autostart = get_post_meta( $pid, 'vp_gallery_autostart');
				$gal_autostart = @end($gal_autostart);
				$this->render_gallery_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, $source, $gal_type, $gal_col, $gal_autostart, $pid );
			}
			else if( $type == 'playlist' || $type == 'playlists' ){
				$eid = get_post_meta( $pid, 'vp_list_image_entry');
				$eid = end($eid);
				$this->render_playlist_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, $source, $pid );
			}
			else if( $type == 'video' || $type == 'videos' ){
				$eid = get_post_meta( $pid, 'vp_video_entry');
				$eid = end($eid);
				//support for old versions
				if( !is_numeric( $eid ) )
					$this->render_embed_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, $source, 'pin', $pid );
				else {
					//self hosted
					$this->render_hosted_media_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, $source, 'video', $pid );	
				}
			}
			else if( $type == 'audio' ){
				$eid = get_post_meta( $pid, 'vp_audio_entry');
				$eid = end($eid);
				//support for old versions
				if( !is_numeric( $eid ) )
					$this->render_embed_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, 'pin', $type, $pid );
				else {
					//self hosted
					$this->render_hosted_media_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, $source, 'audio', $pid );
				}
			}
			else if( $type == 'pin' || $type == 'pins' ){
				$eid = get_post_meta( $pid, 'vp_embed_entry');
				$eid = end($eid);
				$this->render_embed_entry( $child_post->post_title, $child_post->post_content, $eid, $l_show_numbers, $source, 'pin', $pid );
			}
			else if( $type == 'quiz' || $type == 'poll' || $type == 'polls' ){
				$eid = get_post_meta( $pid, 'vp_quiz_image_entry');
				if(!empty($eid))$eid = end($eid);
				
				$aa = get_post_meta( $pid, 'vp_correct_answer');
				if(!empty($aa)){
					$c_ans = end($aa);
				}
				else $c_ans = 0;
				
				$ans_text = array();
				$ans_imgs = array();
				$ans_ids = array();
				
				$aid = get_post_meta( $pid, 'vp_answer_entry');
				if(!empty($aid)) {
					$aid = explode(',', $aid[0]);	
					foreach( $aid as $aa ) {
						$ans_ids[] = $aa;
						$atid = get_post_meta( $aa, 'vp_quiz_image_entry');
						if(!empty($aa)){
							$ans_imgs[] = end($atid);
						}
						else $ans_imgs[] = 0;
						$ans_text[] = get_the_title( $aa );
					}
				}
				$this->render_quiz_entry( $child_post->post_title, $child_post->post_content, $eid, $c_ans, $ans_text, $ans_imgs, $ans_ids, $l_show_numbers, $quiz_type, $type,$source, $pid );
			}
		}
		
		if( $type == 'quiz' ) {
			$aid = get_post_meta( $post_id, 'vp_score_entry');			
			if(!empty($aid)) {
				$aid = explode(',', $aid[0]);
				//var_dump($aid);	
				foreach( $aid as $aa ) {
					$atid = get_post_meta( $aa, 'vp_quiz_image_entry');
					if(!empty($aa)){
						$ans_imgs = end($atid);
					}
					else $ans_imgs = 0;
					
					$p = get_post( $aa );
					$ans_text = $p->post_title;
					$ans_desc = $p->post_content;
					
					$score_from = get_post_meta( $aa, 'vp_quiz_score_from' );
					$score_from = $score_from[0];
					
					$score_to = get_post_meta( $aa, 'vp_quiz_score_to' );
					$score_to = $score_to[0];
					
					$score_source = get_post_meta( $aa, 'vp_source_url' );
					
					if( !empty( $score_source[0] ) )$score_source = $score_source[0];
					else $score_source = '';
					
					$this->render_score_entry( $ans_text, $ans_desc, $ans_imgs, $aa, $score_from, $score_to, $score_source );
				}
			}
			if( !empty( $this->render_html_after_person1_quiz ))$this->render_html .= $this->render_html_after_person1_quiz;	
		}
		
		$t = __( 'Edit post', 'viralpress' ).' - '.get_bloginfo( 'name' );
		$this->render_html .= 'document.title="'.$t.'";$(".editor_loader").hide();$("#add_new_post_form").show();});jQuery(window).load(function(){prevent_item_scroll=0;}); </script>';
	}
	
	public function render_editor_top( $title, $summary, $thumb, $preface_title, $preface_desc, $preface_image )
	{
		$show_preface = 0;
		if( !empty($title) ){
			$title = esc_js( htmlspecialchars_decode($title) );
			$this->render_html .= '$("#post_title").val($("<div />").html("'.$title.'").text());';	
		}
		
		if( !empty($preface_title) ){
			$show_preface = 1;
			$preface_title = esc_js( htmlspecialchars_decode($preface_title) );
			$this->render_html .= '$("#preface_title").val($("<div />").html("'.$preface_title.'").text());';	
		}
		
		if( !empty($summary) ){
			$summary = esc_js( htmlspecialchars_decode($summary) );
			$this->render_html .= '$("#post_summary").val($("<div />").html("'.$summary.'").text());';	
		}
		
		if( !empty($preface_desc) ){
			$show_preface = 1;
			$preface_desc = esc_js( htmlspecialchars_decode($preface_desc) );
			$this->render_html .= '$("#preface_desc").val($("<div />").html("'.$preface_desc.'").text());';	
		}
		
		$thumb = vp_esc_url( $thumb );
		if( !empty($thumb) ){
			if ( is_numeric( $thumb ) ) {
				$url = wp_get_attachment_image_src( $thumb, 'large' );
				$url = $url[0];
			}
			else $url = $thumb;
			
			$this->render_html .= 'add_thumb_image( jQuery(".thumbnail_uploader").eq(0), "'.$thumb.'", "'.$url.'" );';		
		}
		
		$preface_image = vp_esc_url( $preface_image);
		if( !empty($preface_image) ){
			if ( is_numeric( $preface_image ) ) {
				$show_preface = 1;
				$url = wp_get_attachment_image_src( $preface_image, 'large' );
				$url = $url[0];
			}		
			else $url = $preface_image;
			
			$this->render_html .= 'add_thumb_image( jQuery(".thumbnail_uploader").eq(3), "'.$preface_image.'", "'.$url.'" );';
		}
		
		if( $show_preface ) $this->render_html .= '$(".editor_add_preface").click();';
	}
	
	public function render_news_entry( $title, $desc, $l_show_numbers, $source, $pid )
	{
		global $vp_instance;
		
		if($title == 'NO_TITLE') $title = '';
		$title = esc_js( htmlspecialchars_decode($title) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$source = esc_js( esc_url( $source ) );
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid ) || !empty($vp_instance->temp_vars['is_copying']) ) {
			$pid = 0;
		}	
		
		$this->render_html .= 'p = add_new_entry( "news" );add_news_entry( p, $("<div />").html("'.$title.'").text(), $("<div />").html("'.$desc.'").text() ); pp = p.parents(".more_items:first"); pp.find(".entry_source").val("'.$source.'");';
		if( !empty($pid) )$this->render_html .= 'pp.find(".entry_post_id").val("'.$pid.'");';
		if( !empty($l_show_numbers) )$this->render_html .= 'pp.find(".entry-show-num").prop("checked", true);';
		else $this->render_html .= 'pp.find(".entry-show-num").prop("checked", false);pp.removeClass("more_items_numbered");pp.find(".entry-no").html("");';
	}
	
	public function render_list_entry( $title, $desc, $thumb, $l_show_numbers, $source, $pid )
	{
		global $vp_instance;
		
		if($title == 'NO_TITLE') $title = '';
		$title = esc_js( htmlspecialchars_decode($title) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$source = esc_js( esc_url( $source ) );
		$url = '';
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid ) || !empty($vp_instance->temp_vars['is_copying'])  ) {
			$pid = 0;
		}
		
		$thumb = vp_esc_url( $thumb );
		if( !empty($thumb) ){
			if ( is_numeric( $thumb ) ) {
				$url = wp_get_attachment_image_src( $thumb, 'large' );
				$url = $url[0];
			}
			else $url = $thumb;			
		}
		
		$this->render_html .= 'p = add_new_entry( "list" ); pp = p.parents(".more_items:first"); pp.find(".entry_text").val($("<div />").html("'.$title.'").text());pp.find(".entry_desc").val($("<div />").html("'.$desc.'").text());pp.find(".entry_source").val("'.$source.'");';
		if( !empty($url) )$this->render_html .= 'add_thumb_image( pp.find(".thumbnail_uploader"), "'.$thumb.'", "'.$url.'" );';
		if( !empty($pid) )$this->render_html .= 'pp.find(".entry_post_id").val("'.$pid.'");';
		if( !empty($l_show_numbers) )$this->render_html .= 'pp.find(".entry-show-num").prop("checked", true);';
		else $this->render_html .= 'pp.find(".entry-show-num").prop("checked", false);pp.removeClass("more_items_numbered");pp.find(".entry-no").html("");';
	}
	
	public function render_gallery_entry( $title, $desc, $thumb, $l_show_numbers, $source, $gal_type, $gal_col, $gal_auto, $pid )
	{
		global $vp_instance;
		
		if($title == 'NO_TITLE') $title = '';
		$title = esc_js( htmlspecialchars_decode($title) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$source = esc_js( esc_url( $source ) );
		$url = '';
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid ) || !empty($vp_instance->temp_vars['is_copying'])  ) {
			$pid = 0;
		}
		
		if( is_string( $thumb ) ) $thumb = explode( ',', $thumb );
		$ii = array();
		if( !empty($thumb) ){
			foreach( $thumb as $i ) {
				$i = (int)$i;
				if ( /*current_user_can( 'edit_post', $thumb )*/ wp_attachment_is_image( $i ) ) {
					$url = wp_get_attachment_image_src( $i, 'large' );
					$url = $url[0];	
					$ii[$i] = esc_js( esc_url( $url ) ); 
				}	
			}
		}
		
		$this->render_html .= 'p = add_new_entry( "gallery" ); pp = p.parents(".more_items:first"); pp.find(".entry_text").val($("<div />").html("'.$title.'").text());pp.find(".entry_desc").val($("<div />").html("'.$desc.'").text());pp.find(".entry_source").val("'.$source.'");';
		if( !empty($pid) )$this->render_html .= 'pp.find(".entry_post_id").val("'.$pid.'");';
		if( !empty($l_show_numbers) )$this->render_html .= 'pp.find(".entry-show-num").prop("checked", true);';
		else $this->render_html .= 'pp.find(".entry-show-num").prop("checked", false);pp.removeClass("more_items_numbered");pp.find(".entry-no").html("");';
		$this->render_html .= 'pp.find(".more_details_btn").click(); pp.find(".vp-uploader").hide(); pp.find(".vp-uploader-nopad").show();pp.find(".gal_type").val("'.$gal_type.'");pp.find(".gal_col").val("'.$gal_col.'");;pp.find(".gal_autostart").val("'.$gal_auto.'");';
		
		$hh = '';
		foreach( $ii as $iii => $url ) {
			$this->render_html .= 'hh = get_gallery_editor_html("'.$iii.'", "'.$url.'", pp.find(".vp-uploader"), 250); pp.find(".vp-uploader-image").append(hh);';	
		}
	}
	
	public function render_playlist_entry( $title, $desc, $thumb, $l_show_numbers, $source, $pid )
	{
		global $vp_instance;
		
		if($title == 'NO_TITLE') $title = '';
		$title = esc_js( htmlspecialchars_decode($title) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$source = esc_js( esc_url( $source ) );
		$url = '';
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid ) || !empty($vp_instance->temp_vars['is_copying'])  ) {
			$pid = 0;
		}
		
		if( is_string( $thumb ) ) $thumb = explode( ',', $thumb );
		$ii = array();
		if( !empty($thumb) ){
			foreach( $thumb as $i ) {
				$i = (int)$i;
				$type = get_post_mime_type( $i );
				if( vp_verify_video_mime( $type ) ) $type = 'video';
				else if( vp_verify_audio_mime( $type ) ) $type = 'audio';
				else continue;
				
				$name = esc_js( htmlspecialchars_decode(get_the_title( $i ) ) );
				$ii[$i] = array( 'url' => esc_js( esc_url( $vp_instance->settings['IMG_URL'].'/'.$type.'.png' ) ), 'type' => $type, 'name' => $name ); 	
			}
		}
		
		$this->render_html .= 'p = add_new_entry( "playlist" ); pp = p.parents(".more_items:first"); pp.find(".entry_text").val($("<div />").html("'.$title.'").text());pp.find(".entry_desc").val($("<div />").html("'.$desc.'").text());pp.find(".entry_source").val("'.$source.'");';
		if( !empty($pid) )$this->render_html .= 'pp.find(".entry_post_id").val("'.$pid.'");';
		if( !empty($l_show_numbers) )$this->render_html .= 'pp.find(".entry-show-num").prop("checked", true);';
		else $this->render_html .= 'pp.find(".entry-show-num").prop("checked", false);pp.removeClass("more_items_numbered");pp.find(".entry-no").html("");';
		$this->render_html .= 'pp.find(".more_details_btn").click(); pp.find(".vp-uploader").hide(); pp.find(".vp-uploader-nopad").show();';
		
		$hh = '';
		foreach( $ii as $iii => $item ) {
			$this->render_html .= 'hh = get_playlist_editor_html("'.$iii.'", "'.$item['url'].'", "'.$item['type'].'", "'.$item['name'].'", pp.find(".vp-uploader"), 200); pp.find(".vp-uploader-image").append(hh);';	
		}
	}
	
	public function render_embed_entry( $title, $desc, $val, $l_show_numbers, $source, $type, $pid )
	{
		global $vp_instance;
		
		if($title == 'NO_TITLE') $title = '';
		$title = esc_js( htmlspecialchars_decode($title) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$source = esc_js( esc_url( $source ) );
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid ) || !empty($vp_instance->temp_vars['is_copying'])  ) {
			$pid = 0;
		}
		
		if( !verify_embed_entry_value($val) )$val = '';
		
		$this->render_html .= 'p = add_new_entry( "'.$type.'" ); pp = p.parents(".more_items:first"); pp.find(".entry_text").val($("<div />").html("'.$title.'").text());pp.find(".entry_desc").val($("<div />").html("'.$desc.'").text());pp.find(".entry_source").val("'.$source.'");';
		if( !empty($val) )$this->render_html .= 'add_embed( p, "'.$val.'" );';
		if( !empty($pid) )$this->render_html .= 'pp.find(".entry_post_id").val("'.$pid.'");';
		$this->render_html .= 'pp.find(".more_details_btn").click();';
		if( !empty($l_show_numbers) )$this->render_html .= 'pp.find(".entry-show-num").prop("checked", true);';
		else $this->render_html .= 'pp.find(".entry-show-num").prop("checked", false);pp.removeClass("more_items_numbered");pp.find(".entry-no").html("");';
	}
	
	public function render_hosted_media_entry( $title, $desc, $val, $l_show_numbers, $source, $type, $pid )
	{
		global $vp_instance;
		
		if($title == 'NO_TITLE') $title = '';
		$title = esc_js( htmlspecialchars_decode($title) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$source = esc_js( esc_url( $source ) );
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid ) || !empty($vp_instance->temp_vars['is_copying'])  ) {
			$pid = 0;
		}
		
		$thumb = (int)$val;
		$url = esc_js( htmlspecialchars_decode( wp_get_attachment_url( $thumb ) ) );
		$caption = esc_js( htmlspecialchars_decode(get_the_title( $thumb) ) );
		
		$this->render_html .= 'p = add_new_entry( "'.$type.'" ); pp = p.parents(".more_items:first"); pp.find(".entry_text").val($("<div />").html("'.$title.'").text());pp.find(".entry_desc").val($("<div />").html("'.$desc.'").text());pp.find(".entry_source").val("'.$source.'");';
		if( !empty($url) )$this->render_html .= 'add_thumb_image( pp.find(".thumbnail_uploader"), "'.$thumb.'", "'.$url.'", "'.$caption.'" );';
		if( !empty($pid) )$this->render_html .= 'pp.find(".entry_post_id").val("'.$pid.'");';
		if( !empty($l_show_numbers) )$this->render_html .= 'pp.find(".entry-show-num").prop("checked", true);';
		else $this->render_html .= 'pp.find(".entry-show-num").prop("checked", false);pp.removeClass("more_items_numbered");pp.find(".entry-no").html("");';
	}
	
	public function render_quiz_entry( $q, $desc, $thumb, $correct_ans, $ans, $ans_imgs, $entry_ans_post_id, $l_show_numbers, $quiz_type, $type, $source, $pid ) 
	{
		global $vp_instance;
		
		if($q == 'NO_TITLE') $q = '';
		$q = esc_js( htmlspecialchars_decode($q) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$correct_ans = (int)$correct_ans;
		
		$explain = get_post_meta( $pid, 'vp_explain_ans' );
		$explain = end( $explain );
		$old_pid = $pid;
		
		$url = '';
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid )  || !empty($vp_instance->temp_vars['is_copying']) ) {
			$pid = 0;
		}
		
		$thumb = vp_esc_url( $thumb );
		if( !empty($thumb) ){
			if ( is_numeric( $thumb ) ) {
				$url = wp_get_attachment_image_src( $thumb, 'large' );
				$url = $url[0];
			}
			else $url = $thumb;			
		}
		
		if( $type == 'quiz' )$this->render_html .= 'p = add_new_entry( "quiz" );';
		else $this->render_html .= 'p = add_new_entry( "poll" );$("#voting_till").show();';
		if( !empty($q) )$this->render_html .= 'p.find(".entry_text").val($("<div />").html("'.$q.'").text());';
		if( !empty($desc) )$this->render_html .= 'p.find(".entry_desc").val($("<div />").html("'.$desc.'").text());';
		if( !empty($url) )$this->render_html .= 'add_thumb_image( p.find(".thumbnail_uploader"), "'.$thumb.'", "'.$url.'" );';
		if( !empty($pid) )$this->render_html .= 'p.find(".entry_post_id").val("'.$pid.'");';
		$this->render_html .= 'p.find(".entry_source").val("'.$source.'");';
		
		
		if( !empty( $explain ) ) {
			$explain = unserialize( $explain );
			
			$t = esc_js( htmlspecialchars_decode( $explain['title'] ) );
			$d = esc_js( htmlspecialchars_decode( $explain['desc'] ) );
			$img = vp_esc_url( $explain['image'] );
			
			if( !empty($img) ){
				if ( is_numeric( $img ) ) {
					$url = wp_get_attachment_image_src( $img, 'large' );
					$url = $url[0];
				}
				else $url = $img;			
			}
						
			$this->render_html .= 'p.find(".show_explain_ans").click();';
			if( !empty( $t ) )$this->render_html .= 'p.find(".explain_text").val("'.$t.'");';
			if( !empty( $d ) )$this->render_html .= 'p.find(".explain_desc").val("'.$d.'");';
			if( !empty( $url ) )$this->render_html .= 'add_thumb_image( p.find(".exp_img"), "'.$img.'", "'.$url.'" );';
		}
		
		$url = '';
		
		foreach( $ans as $k => $a ) {
			if($a == 'NO_TITLE') $a = '';
			$a = esc_js( htmlspecialchars_decode($a) );
			$thumb = @vp_esc_url( $ans_imgs[$k] );
			$pid = @(int)$entry_ans_post_id[$k];
			$url = '';
			$ans_res = '';
			
			if( $quiz_type == 'person1' ) {
				$xx = get_post_meta( $pid, 'vp_ans_result' );
				$ans_res = @(int)end($xx);	
			}
			
			if ( !current_user_can( 'edit_post', $pid )  || !empty($vp_instance->temp_vars['is_copying']) ) {
				$pid = 0;
			}
			
			if( !empty($thumb) ){
				if ( is_numeric( $thumb ) ) {
					$url = wp_get_attachment_image_src( $thumb, 'vp-quiz-image' );
					$url = $url[0];
				}
				else $url = $thumb;			
			}
			
			$this->render_html .= 'q = add_quiz_answers(p);';
			if( !empty($a) )$this->render_html .= 'q.find(".entry_text").val($("<div />").html("'.$a.'").text());';
			if( !empty($url) )$this->render_html .= 'add_thumb_image( q.find(".thumbnail_uploader"), "'.$thumb.'", "'.$url.'" );';
			if( !empty($pid) )$this->render_html .= 'q.find(".entry_ans_post_id").val("'.$pid.'");';
			if( !empty($ans_res) ){
				$this->render_html .= 'qq_'.$pid.' = q;';
				$this->render_html_after_person1_quiz .= 'qq_'.$pid.'.find(".personality_res_ans").val("'.$ans_res.'");qq_'.$pid.'.find(".for_personality_quiz").show();';
			}
		}
		
		$this->render_html .= 'pp = p.parents(".more_items:first");';
		if( !empty($correct_ans) ) $this->render_html .= 'p.find(".correct_answer").val("'.$correct_ans.'");';	
		if( !empty($l_show_numbers) )$this->render_html .= 'pp.find(".entry-show-num").prop("checked", true);';
		else $this->render_html .= 'pp.find(".entry-show-num").prop("checked", false);pp.removeClass("more_items_numbered");pp.find(".entry-no").html("");';
	}
	
	public function render_score_entry( $title, $desc, $thumb, $pid, $score_from, $score_to, $source )
	{
		global $vp_instance;
		
		if($title == 'NO_TITLE') $title = '';
		$title = esc_js( htmlspecialchars_decode($title) );
		$desc = esc_js( htmlspecialchars_decode($desc) );
		$score_from = (int)$score_from;
		$score_to = (int)$score_to;
		$url = '';
		
		$pid = (int)$pid;
		if ( !current_user_can( 'edit_post', $pid ) || !empty($vp_instance->temp_vars['is_copying'])  ) {
			$pid = 0;
		}
		
		if( !empty($thumb) ){
			$thumb = vp_esc_url( $thumb );
			if( !empty($thumb) ){
				if ( is_numeric( $thumb ) ) {
					$url = wp_get_attachment_image_src( $thumb, 'large' );
					$url = $url[0];
				}
				else $url = $thumb;			
			}
		}
		
		$this->render_html .= 'p = add_new_entry( "results" ); pp = p.parents(".more_items_x:first"); pp.find(".entry_text").val($("<div />").html("'.$title.'").text()).trigger( "change" );pp.find(".entry_desc").val($("<div />").html("'.$desc.'").text());';
		if( isset( $score_from ) ) $this->render_html .= 'pp.find(".quiz_res_from_score").val("'.$score_from.'");pp.find(".quiz_res_to_score").val("'.$score_to.'");';
		if( !empty($url) )$this->render_html .= 'add_thumb_image( pp.find(".thumbnail_uploader"), "'.$thumb.'", "'.$url.'" );';
		if( !empty($pid) )$this->render_html .= 'pp.find(".entry_post_id").val("'.$pid.'");';
		$this->render_html .= 'pp.find(".entry_source").val("'.$source.'");';
	}
	
	public function render_errors( $error, $selectors, $messages )
	{
		$this->render_html = '<script>jQuery(document).ready(function($){';
		$this->render_html .= '$(".submit_errors").html("'.$error.'").show();';
		foreach( $selectors as $k => $i ) {
			if( $i > 0 )
				$this->render_html .= '$(".more_items").eq('.($i).').prepend("<div class=\'alert alert-danger\'>'.$messages[$k].'</div>");';
			else 
				$this->render_html .= '$(".more_results_holder").find(".more_items_x").eq('.(-$i).').prepend("<div class=\'alert alert-danger\'>'.$messages[$k].'</div>");';
		}
		$this->render_html .= '});</script>';
	}
	
	public function delete_post( $post_id, $i_dont_know_why_this_is_here = 0 )
	{
		global $wpdb;
		
		$olds = get_post_meta( $post_id, 'vp_child_post_ids' );
		
		if( !empty($olds) ){
			$olds = end( $olds );
			$old_post_ids = explode( ',', $olds );
			
			foreach( $old_post_ids as $olds ) {
				$this->delete_post( $olds, 1 );	
			}
		}
		
		$olds = $wpdb->get_results( 
					$wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'vp_submitted_to' AND  meta_value = %d", $post_id ), 
					ARRAY_A
				);
		
		if( !empty($olds) ){
			foreach( $olds as $o ) {
				$this->delete_post( $o['post_id'], 1 );	
			}
		}
		
		$olds = get_post_meta( $post_id, 'vp_answer_entry' );
		
		if( !empty($olds) ){
			$olds = end( $olds );
			$old_post_ids = explode( ',', $olds );
			
			foreach( $old_post_ids as $olds ) {
				$this->delete_post( $olds, 1 );	
			}
		}
		
		$olds = get_post_meta( $post_id, 'vp_score_entry' );
		
		if( !empty($olds) ){
			$olds = end( $olds );
			$old_post_ids = explode( ',', $olds );
			
			foreach( $old_post_ids as $olds ) {
				$this->delete_post( $olds, 1 );	
			}
		}
		
		global $wpdb;
		$sql = $wpdb->prepare( "DELETE FROM ".$wpdb->prefix."postmeta WHERE post_id = %d", array($post_id) );
		$wpdb->query( $sql );
		
		//$this->delete_associated_media( $post_id );
		vp_bp_delete_activity( $post_id );
		
		wp_delete_post( $post_id, 1 );	
	}
	
	public function delete_associated_media( $id ) {
		$media = get_children( array(
			'post_parent' => $id,
			'post_type'   => 'attachment'
		) );
	
		if( empty( $media ) ) {
			return;
		}
	
		foreach( $media as $file ) {
			wp_delete_attachment( $file->ID, 1 );
		}
	}
	
	public function strip_shortcodes( &$item, $key )
	{
		$item = strip_shortcodes( $item );
	}
	
	static function render_my_recent_post_comments()
	{
		if ( !is_user_logged_in() ) {
			return __( 'You must be logged in to view this page!', 'viralpress' );
		}
		echo vp_get_template_html( 'post-comments' );	
	}
	
	static function render_my_recent_comments()
	{
		if ( !is_user_logged_in() ) {
			return __( 'You must be logged in to view this page!', 'viralpress' );
		}
		echo vp_get_template_html( 'my-comments' );	
	}
}

?>