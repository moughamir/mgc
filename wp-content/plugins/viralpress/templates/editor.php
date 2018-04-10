<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
?>
<?php 

echo vp_text_entry_form();
if( !empty( $attributes['edit_post_id'] ) ) $edit_post_id = $attributes['edit_post_id'] ;
$post_type = @$attributes['post_type'];	
	
$btn = array();	
$btn['text'] = '<button class="btn btn-success add_text_item"><i class="glyphicon glyphicon-book"></i>&nbsp;'.__('Text', 'viralpress').'</button>';
$btn['image'] = '<button class="btn btn-info add_list_item"><i class="glyphicon glyphicon-picture"></i>&nbsp;'.__('Image', 'viralpress').'</button>';
$btn['meme'] = ' <button class="btn btn-primary add_new_meme_gen"><i class="glyphicon glyphicon-tint"></i>&nbsp;'.__('Meme', 'viralpress').'</button>';
$btn['more_image_meme'] = ' <button class="btn btn-primary add_new_meme_gen"><i class="glyphicon glyphicon-tint"></i>&nbsp;'.__('Create a meme', 'viralpress').'</button>';
$btn['more_image'] = ' <button class="btn btn-info add_list_item"><i class="glyphicon glyphicon-picture"></i>&nbsp;'.__('Upload image', 'viralpress').'</button>';
$btn['more_meme'] = ' <button class="btn btn-primary add_new_meme_gen"><i class="glyphicon glyphicon-tint"></i>&nbsp;'.__('Create a new meme', 'viralpress').'</button>';
$btn['meme_image'] = ' <button class="btn btn-info add_list_item"><i class="glyphicon glyphicon-picture"></i>&nbsp;'.__('Upload existing meme', 'viralpress').'</button>';
$btn['embed'] = '<button class="btn btn-primary add_pin_item"><i class="glyphicon glyphicon-new-window"></i>&nbsp;'.__('Embed', 'viralpress'). '</button>';
$btn['gallery'] = '<button class="btn btn-danger add_gallery_item"><i class="glyphicon glyphicon-camera"></i>&nbsp;'.__('Gallery', 'viralpress').'</button>';
$btn['video'] = '<button class="btn btn-danger add_self_video_item"><i class="glyphicon glyphicon-film"></i>&nbsp;'.__('Video', 'viralpress').'</button>';
$btn['audio'] = '<button class="btn btn-success add_self_audio_item"><i class="glyphicon glyphicon-music"></i>&nbsp;'.__('Audio', 'viralpress').'</button>';
$btn['playlist'] = '<button class="btn btn-info add_playlist_item"><i class="glyphicon glyphicon-film"></i>&nbsp;'.__('Playlist', 'viralpress').'</button>';
$btn['poll'] = '<button class="btn btn-info add_quiz_item no_quiz"><i class="glyphicon glyphicon-tasks"></i>&nbsp;'.__('Poll', 'viralpress') .'</button>';
$btn['more_video'] = ' <button class="btn btn-danger add_self_video_item"><i class="glyphicon glyphicon-film"></i>&nbsp;'.__('Upload video', 'viralpress') .'</button>';
$btn['more_audio'] = ' <button class="btn btn-success add_self_audio_item"><i class="glyphicon glyphicon-music"></i>&nbsp;'.__('Upload audio', 'viralpress') .'</button>';
$btn['more_gallery'] = '<button class="btn btn-danger add_gallery_item"><i class="glyphicon glyphicon-camera"></i>&nbsp;'.__('Add more gallery', 'viralpress') .'</button>';
$btn['more_playlist'] = '<button class="btn btn-info add_playlist_item"><i class="glyphicon glyphicon-film"></i>&nbsp;'.__('Add more playlist', 'viralpress') .'</button>';
$btn['embed_from'] = '<button class="btn btn-primary add_pin_item"><i class="glyphicon glyphicon-new-window"></i>&nbsp;'.__('Embed from websites', 'viralpress'). '</button>';
$btn['embed_from'] = '<button class="btn btn-primary add_pin_item"><i class="glyphicon glyphicon-new-window"></i>&nbsp;'.__('Embed from websites', 'viralpress'). '</button>';

$text1 = array();
$text1['news'] = __( 'Add one or more news entry', 'viralpress' );
$text1['image'] = __( 'Add one or more image', 'viralpress' );
$text1['list'] = __( 'Add one or more list item', 'viralpress' );
$text1['media'] = __( 'Add one or more media entry', 'viralpress' );
$text1['playlist'] = __( 'Add one or more playlist', 'viralpress' );
$text1['gallery'] = __( 'Add one or more gallery', 'viralpress' );
$text1['quiz'] = __( 'Quiz questions', 'viralpress' );
$text1['poll'] = __( 'Add one or more poll', 'viralpress' );
?>
<div class="vp_container_test"></div>
<div class="alert alert-success submit_errors" style="display:none"></div>

<div class="text-center editor_loader" style="min-height:300px">
	<img src="<?php echo $attributes['vp_instance']->settings['IMG_URL']?>/spinner-2x.gif"/>
</div>

<div class="row vp_editor_intro vp-display-none">
	<div class="col-lg-12">
        <?php 
		$printed = 0;
		global $current_user;
		?>
        
        <div class="vp-clearfix">
        	<h3><?php echo sprintf( __( 'Welcome %s, What type of content you want to submit?', 'viralpress' ), $current_user->user_nicename )?></h3>
        </div>
    
    	<?php if( $vp_instance->settings['news_enabled'] ): $printed ++;?>
    	<button class="btn btn-default vp-add-item" data-rel="news">
        	<i class="glyphicon glyphicon-book"></i>
            <span><?php _e('Submit a news or story', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['list_enabled'] ): $printed ++;?>
        <button class="btn btn-default vp-add-item" data-rel="lists">
        	<i class="glyphicon glyphicon-list"></i>
            <span><?php _e('Submit a list', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['image_enabled'] ): $printed ++;?>
        <button class="btn btn-default vp-add-item" data-rel="images">
        	<i class="glyphicon glyphicon-picture"></i>
            <span><?php _e('Submit image', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['meme_enabled'] ): $printed ++;?>
        <button class="btn btn-default vp-add-item" data-rel="meme">
        	<i class="glyphicon glyphicon-tint"></i>
        	<span><?php _e('Create meme', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
         <?php if( $printed >= 5): $printed = 0;?>
        <div class="vp-clearfix-lg"></div>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['poll_enabled'] ): $printed++;?>
        <button class="btn btn-default vp-add-item" data-rel="polls">
        	<i class="glyphicon glyphicon-tasks"></i>
            <span><?php _e('Create a poll', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $printed >= 5): $printed = 0;?>
        <div class="vp-clearfix-lg"></div>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['quiz_enabled'] ): $printed++;?>
        <button class="btn btn-default vp-add-item" data-rel="quiz">
        	<i class="glyphicon glyphicon-globe"></i>
            <span><?php _e('Create a quiz', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $printed >= 5): $printed = 0;?>
        <div class="vp-clearfix-lg"></div>
        <?php endif;?>
        
         <?php if( $vp_instance->settings['self_video'] || $vp_instance->settings['video_enabled'] ): $printed++;?>
        <button class="btn btn-default vp-add-item" data-rel="videos">
        	<i class="glyphicon glyphicon-film"></i>
            <span><?php _e('Submit video', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $printed >= 5): $printed = 0;?>
        <div class="vp-clearfix-lg"></div>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['self_audio'] || $vp_instance->settings['audio_enabled']  ): $printed++;?>
        <button class="btn btn-default vp-add-item" data-rel="audio">
        	<i class="glyphicon glyphicon-music"></i>
            <span><?php _e('Submit audio', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $printed >= 5): $printed = 0;?>
        <div class="vp-clearfix-lg"></div>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['gallery_enabled'] ): $printed++;?>
        <button class="btn btn-default vp-add-item" data-rel="gallery">
        	<i class="glyphicon glyphicon-camera"></i>
            <span><?php _e('Submit gallery', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $printed >= 5): $printed = 0;?>
        <div class="vp-clearfix-lg"></div>
        <?php endif;?>
        
        <?php if( $vp_instance->settings['playlist_enabled'] ): $printed++;?>
        <button class="btn btn-default vp-add-item" data-rel="playlist">
        	<i class="glyphicon glyphicon-list-alt"></i>
            <span><?php _e('Submit playlist', 'viralpress')?></span>
        </button>
        <?php endif;?>
        
        <?php if( $printed >= 5): $printed = 0;?>
        <div class="vp-clearfix-lg"></div>
        <?php endif;?>
    </div>
</div>

<form style="display:none" action="" id="add_new_post_form" method="POST">
	<?php wp_nonce_field( 'vp-add-post-'.get_current_user_id(), 'vp_add_post_nonce' ); ?>
	<input type="hidden" name="vp_post_id" value="<?php if(!empty($edit_post_id) && empty($attributes['is_copying']))echo $edit_post_id;?>" />
    <input type="hidden" name="vp_post_type" id="vp_post_type" value="<?php echo $attributes['post_type']?>" />
    <input type="hidden" name="vp_add_new_post" id="add_new_post" value="true" />
    <input type="hidden" name="action" value="vp_add_post" />
    
	<div class="row vp-glow">
    	<div class="col-lg-9">
        	<div class="vp-pull-left">
            	<label for="post_title"><?php _e('Title of the post', 'viralpress') ?></label>
            </div>
            <div class="vp-pull-right">
            	<button class="btn btn-xs btn-info editor_add_thumb">
                	<i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e( 'Add thumbnail', 'viralpress' )?>
                </button>
                <button class="btn btn-xs btn-warning editor_add_preface">
                    <i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e( 'Add preface', 'viralpress' )?>
                </button>
            </div>
            <div class="vp-clearfix"></div>
            <input type="text" name="post_title" id="post_title" class="vp-form-control required" value="" 
            placeholder="<?php _e( 'Type a title', 'viralpress' );echo ' '; _e( '(Required)', 'viralpress' )?>"/>
            <br/><br/>
            <div class="thumb_and_sub" style="display:none">    
            	<div class="vp-clearfix"></div>
                <div class="row more_items_x">
                    <div class="col-lg-5">
                        <div class="vp-uploader vp-uploader-sum text-center" data-target="post_thumbnail">
                            <button class="thumbnail_uploader sum_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Upload a thumbnail' , 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>
                            <h4 class="big_or"><?php _e( 'OR' , 'viralpress' )?></h4>
                            <span class="thumbnail_uploader sum_img thumbnail_uploader_url vp-pointer" style="font-size:14px">
                            	<i class="glyphicon glyphicon-link"></i>&nbsp;<?php _e( 'Upload from link' , 'viralpress' )?>
                            </span>
                        </div>
                        <div class="vp-uploader-nopad vp-uploader-nopad-sum">
                        	<div class="vp-uploader-image vp-uploader-image-sum"></div>
                            <button class="thumbnail_uploader sum_img btn btn-sm btn-info change-item-btn">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Change photo', 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>&nbsp;
                            <button class="thumbnail_remove sum_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;
                                <?php _e( 'Remove' , 'viralpress' )?>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-7">	
                        <label for="post_summary"><?php _e('Subtitle', 'viralpress') ?></label>
                        <textarea name="post_summary" id="post_summary" rows="8" class="vp-form-control" 
                        placeholder="<?php _e( 'Type a subtitle', 'viralpress' ); echo ' '; _e( '(Optional)', 'viralpress' )?>"></textarea>
                    </div>
                </div>
            </div>
            
            <fieldset class="editor_preface" style="display:none">
            	<legend class="more_items_glow"><?php _e( 'Post Preface', 'viralpress' )?></legend>
                <div class="vp-pull-right vp-pointer">
					<i class="glyphicon glyphicon-trash remove_preface" title="<?php _e( 'Remove preface', 'viralpress' )?>"></i>
				</div>
                <div class="row more_items_x">
                    <div class="col-lg-5">
                        <div class="vp-uploader vp-uploader-pref vp-uploader-lesspad text-center" data-target="preface_image">
                            <button class="thumbnail_uploader pref_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Upload a thumbnail' , 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>
                            <h4><?php _e( 'OR' , 'viralpress' )?></h4>
                            <span class="thumbnail_uploader pref_img thumbnail_uploader_url vp-pointer" style="font-size:14px">
                            	<i class="glyphicon glyphicon-link"></i>&nbsp;<?php _e( 'Upload from link' , 'viralpress' )?>
                            </span>
                        </div>
                        <div class="vp-uploader-nopad vp-uploader-nopad-pref">
                        	<div class="vp-uploader-image vp-uploader-image-pref"></div>
                            <button class="thumbnail_uploader pref_img btn btn-sm btn-info change-item-btn">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Change photo', 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>&nbsp;
                            <button class="thumbnail_remove pref_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;
                                <?php _e( 'Remove' , 'viralpress' )?>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-7">	
                        <label for="preface_title"><?php _e('Title', 'viralpress') ?></label>
                        <input type="text" name="preface_title" id="preface_title" class="vp-form-control required" value="" 
                        placeholder="<?php _e( 'Type a title', 'viralpress' );echo ' '; _e( '(Optional)', 'viralpress' )?>"/>
                        <br/><br/>
                        <label for="preface_desc"><?php _e('Summary', 'viralpress') ?></label>
                        <div class="wp-editor-tabs">
							<img class="pull-right toggle_tinymce vp-pointer" src="<?php echo $vp_instance->settings['IMG_URL']?>/scode.png" width="25px"/>
						</div>
                        <textarea name="preface_desc" id="preface_desc" rows="4" class="vp-form-control tinymce" 
                        placeholder="<?php _e( 'Type a summary', 'viralpress' ); echo ' '; _e( '(Optional)', 'viralpress' )?>"></textarea>
                    </div>
                </div>
            </fieldset>
            
            <div class="vp_post_entries_main">
                        
            	<h3 class="post_entries" style="display:none">
					<?php _e( 'Post entires', 'viralpress' )?> 
                </h3> 
            
                <div class="more_items_holder">
                    <div class="more_items more_items_numbered">
                    </div>
                </div>
                
                <div class="vp-for-quiz vp-display-none">
                    <br/>
                    <fieldset class="vp-for-quiz vp-display-none">
                        <button class="btn btn-success add_quiz_item">
                            <i class="glyphicon glyphicon-tasks"></i>&nbsp;<?php _e('Add More Question', 'viralpress') ?>
                        </button>
                        <button class="btn btn-info add_res_item"><i class="glyphicon glyphicon-align-justify"></i>&nbsp;<?php _e('Add More Results', 'viralpress') ?></button>
                    </fieldset>
                </div>
            
            </div>
            
            <div class="vp_quiz_divider">
            	<input type="hidden" id="vp_quiz_person_sw" value="0" />
            </div>
            
            <div class="vp_post_entries_options">
            	<div class="vp-for-quiz vp-display-none">
                    <h3><?php _e( 'Quiz results', 'Viralpress' )?></h3>
                    <div class="more_results_holder">
                        <div class="more_items_x">
                        </div>
                    </div>
                    <br/>
                </div>
            
            <?php if( $attributes['post_type'] != 'quiz' ) echo '<h3 class="post_entries_2">'.__( 'What type of item you want to add next?', 'Viralpress' ).'</h3>'?>
            
                <fieldset>
                    <div class="vp-for-lists vp-for-list vp-for-news vp-for-poll vp-for-polls vp-display-none">
                        <?php if( $vp_instance->settings['list_enabled'] ):?>
                            <?php if( $vp_instance->settings['news_enabled'] ) echo $btn['text']?>
                            <?php if( $vp_instance->settings['image_enabled'] ) echo $btn['image']?>
                            <?php if( $vp_instance->settings['meme_enabled'] ) echo $btn['meme']?>
                            <?php if( $vp_instance->settings['embed_enabled'] ) echo $btn['embed']?>
                        <?php endif;?>
                        
                        <?php if( $vp_instance->settings['gallery_enabled'] ):?>
                            <?php echo $btn['gallery']?>
                        <?php endif;?>
                        
                        <?php if($vp_instance->settings['self_video']):?>
                            <?php echo $btn['video']?>
                        <?php endif;?>
                        
                        <?php if($vp_instance->settings['self_audio']):?>
                            <?php echo $btn['audio']?>
                        <?php endif;?>
                        
                        <?php if( $vp_instance->settings['playlist_enabled'] && ( $vp_instance->settings['self_audio'] || $vp_instance->settings['self_video'] ) ):?>
                            <?php echo $btn['playlist']?>
                        <?php endif;?>
                    
                        <?php if( $attributes['vp_instance']->settings['poll_enabled'] ) :?>
                            <?php echo $btn['poll']?>
                        <?php endif;?>
                    </div>
                    
                    <?php if( $vp_instance->settings['image_enabled'] ):?>
                    <div  class="vp-for-images vp-for-image vp-display-none">
                        <?php echo $btn['more_image']?>
                        <?php if( $vp_instance->settings['meme_enabled'] ) echo $btn['more_image_meme']?>
                    </div>
                    <?php endif;?>
                    
                    <?php if( $vp_instance->settings['meme_enabled'] ):?>
                    <div  class="vp-for-meme vp-display-none">
                        <?php echo $btn['more_meme']?>
                        <?php if( $vp_instance->settings['image_enabled'] )echo $btn['meme_image']?>
                    </div>
                    <?php endif;?>
                    
                    <?php if( $vp_instance->settings['self_video'] || $vp_instance->settings['video_enabled'] ):?>
                    <div  class="vp-for-videos vp-for-video vp-display-none">
                        <?php if( $vp_instance->settings['self_video'] ) echo $btn['more_video']?>
                        <?php if( $vp_instance->settings['embed_enabled'] ) echo $btn['embed_from']?>	
                    </div>
                    <?php endif;?>
                    
                    <?php if($vp_instance->settings['self_audio'] || $vp_instance->settings['audio_enabled'] ):?>
                    <div  class="vp-for-audio vp-display-none">
                        <?php if( $vp_instance->settings['self_audio'] ) echo $btn['more_audio']?>
                        <?php if( $vp_instance->settings['embed_enabled'] ) echo $btn['embed_from']?>	
                    </div>
                    <?php endif;?>
                    
                    <?php if($vp_instance->settings['gallery_enabled']):?>
                    <div  class="vp-for-gallery vp-display-none">
                        <?php echo $btn['more_gallery']?>
                    </div>
                    <?php endif;?>
                    
                    <?php if($vp_instance->settings['playlist_enabled']):?>
                    <div  class="vp-for-playlist vp-display-none">
                        <?php echo $btn['more_playlist']?>
                    </div>
                    <?php endif;?>
                    
                    <!--for quizzes-->
                    <?php if( $vp_instance->settings['quiz_enabled'] ) :?>
                    <div  class="vp-for-quiz vp-display-none">
                        <button class="btn btn-success add_quiz_item">
                            <i class="glyphicon glyphicon-tasks"></i>&nbsp;<?php _e('Add More Question', 'viralpress') ?>
                        </button>
                        <button class="btn btn-info add_res_item vp-for-quiz"><i class="glyphicon glyphicon-align-justify"></i>&nbsp;<?php _e('Add More Results', 'viralpress') ?></button>    
                    </div>
                    <?php endif;?>
                    
                </fieldset>
             </div>
 		</div>
        <div class="col-lg-3 more_item_x">
        	<fieldset>
            	<legend class="more_items_glow"><?php _e( 'Settings', 'viralpress' )?></legend>
                <div class="vp-clearfix"></div>
                <div class="vp-clearfix"></div>
                <div class="vp-clearfix"></div>
                <div class="vp-clearfix"></div>
                
                <div class="vp-for-quiz vp-display-none">
                    <label><?php _e( 'Select the quiz type', 'viralpress' )?></label>                
                    <select name="quiz_type" id="quiz_type">
                        <option value="trivia"><?php _e( 'Trivia Quiz', 'viralpress' )?></option>
                        <option value="mcq"><?php _e( 'MCQ Quiz', 'viralpress' )?></option>
                        <option value="person1"><?php _e( 'Personality Quiz (Type - I)', 'viralpress' )?></option>
                        <option value="person2"><?php _e( 'Personality Quiz (Type - II)', 'viralpress' )?></option>
                    </select>
                    <br/><br/>
				</div>
   				
                <div class="for_vp_category">
                    <label><?php _e( 'Category', 'viralpress' )?></label><br/>
                    <?php 
                        
                        $select_cats = wp_dropdown_categories( 
                            array( 
                                'orderby' => 'NAME', 
                                'hide_empty' => 0, 
                                'class' => 'vp_post_select vp-form-control' ,
                                'echo' => 0
                                ) 
                            );
                        echo $select_cats = str_replace( "name='cat' id=", "name='cat[]' multiple='multiple' id=", $select_cats );
                    ?>
                </div>
                <br/><br/>
                
                <label><?php _e( 'Publication status', 'viralpress' )?></label><br/>
                <select name="publication" id="publication" class="vp_post_select vp-form-control">
                	<option value="draft"><?php _e( 'Save as draft', 'viralpress' )?></option>
                    <option value="publish"><?php _e( 'Publish now', 'viralpress' )?></option>
                    <?php if(!empty($edit_post_id)):?>
                    <option value="delete"><?php _e( 'Delete', 'viralpress' )?></option>
                    <?php endif;?>
                </select>
                
                <br/><br/>
                
                <div class="vp-editor-advanced" style="display:none">                
                    <label><?php _e( 'Post tags', 'viralpress' )?></label><br/>
                    <input type="text" name="post_tag" id="post_tags" value="" class="vp-form-control"/>
                    <br/>
                    
                    <label><?php _e( 'Items per page', 'viralpress' )?></label><br/>
                    <select name="items_per_page" id="items_per_page" class="vp_post_select vp-form-control" 
                        <?php if( in_array( $attributes['post_type'], array( 'quiz', 'poll', 'polls' ) ) )echo 'disabled';?>>
                        <option value="0"><?php _e( 'All', 'viralpress' )?></option>
                        <option value="1"><?php _e( '1', 'viralpress' )?></option>
                        <option value="2"><?php _e( '2', 'viralpress' )?></option>
                        <option value="3"><?php _e( '3', 'viralpress' )?></option>
                        <option value="4"><?php _e( '4', 'viralpress' )?></option>
                        <option value="5"><?php _e( '5', 'viralpress' )?></option>
                        <option value="7"><?php _e( '7', 'viralpress' )?></option>
                        <option value="10"><?php _e( '10', 'viralpress' )?></option>
                        <option value="15"><?php _e( '15', 'viralpress' )?></option>
                        <option value="20"><?php _e( '20', 'viralpress' )?></option>
                        <option value="25"><?php _e( '25', 'viralpress' )?></option>
                        <option value="30"><?php _e( '30', 'viralpress' )?></option>
                    </select>
                    
                    <br/><br/>
                    <label><?php _e( 'List sort order', 'viralpress' )?></label>
                    <select name="sort_order" id="sort_order" class="vp_post_select vp-form-control">
                        <option value="asc"><?php _e( 'Show in ascending order', 'viralpress' )?></option>
                        <option value="desc"><?php _e( 'Show in descending order', 'viralpress' )?></option>
                    </select>
                    
                    <br/><br/>
                    <label><?php _e( 'List style', 'viralpress' )?></label>
                     <select name="list_style" id="list_style" class="vp_post_select vp-form-control">
                        <option value="legend"><?php _e( 'Legend', 'viralpress' )?></option>
                        <option value="boxed"><?php _e( 'Boxed', 'viralpress' )?></option>
                        <option value="inline"><?php _e( 'Inline', 'viralpress' )?></option>
                    </select>
                    
                    <br/><br/>
                    <label><?php _e( 'List display', 'viralpress' )?></label>
                    <select name="list_display" id="list_display" class="vp_post_select vp-form-control">
                        <option value="one"><?php _e( 'One column', 'viralpress' )?></option>
                        <option value="two_l"><?php _e( 'Two column, picture on left', 'viralpress' )?></option>
                        <option value="two_r"><?php _e( 'Two column, picture on right', 'viralpress' )?></option>
                        <option value="two_alt"><?php _e( 'Two column, alternating', 'viralpress' )?></option>
                    </select> 
                    
                    <br/><br/>
                     
                </div>
                
                <button class="btn btn-info show_editor_advanced" style="width:100%">
                	<i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;<?php _e( 'Show advanced options', 'viralpress' )?>
                </button>
               
               	<br/><br/>
                <?php if($vp_instance->settings['allow_copy']):?>
                    <div class="vp-pull-left">
                        <label class="vp-switch">
                          <input type="checkbox" name="allow_copy" id="allow_copy" value="1"/>
                          <div class="vp-slider vp-round"></div>
                        </label>
                    </div>
                    <div class="vp-pull-left vp-ed-lbl">
                    	<div class="vp-tooltip">
                        	<label for="allow_copy"><?php _e( 'Allow copying this post', 'viralpress' )?></label>
                          	<span class="vp-tooltiptext"><?php _e( 'This means other people can copy your list and make a new list of their own by adding more items, editing or deleting items. Similar to creative common licensing', 'viralpress' )?></span>
                        </div>
                    </div>
                    <br/><br/>
                    <?php endif;?>
                    <?php if( $vp_instance->settings['allow_open_list'] ) :?>
                    <div class="vp-not-for-quiz vp-not-for-poll vp-not-for <?php if( $attributes['post_type'] == 'quiz' || $attributes['post_type'] == 'poll' ) echo 'vp-display-none'?>">
                        <div class="vp-pull-left">
                            <label class="vp-switch">
                              <input type="checkbox" name="open_list" id="open_list" value="1"/>
                              <div class="vp-slider vp-round"></div>
                            </label>
                        </div>
                        <div class="vp-pull-left vp-ed-lbl">
                            <div class="vp-tooltip">
                            	<label for="open_list"><?php _e( 'Make this open list', 'viralpress' )?></label>
                            	<span class="vp-tooltiptext"><?php _e( 'Anyone can submit their items to this post and contribute. Added lists may be sorted by votes.', 'viralpress' )?></span>
                        	</div>
                    	</div>
                        <br/><br/>
                    </div>
                    <?php endif;?>
                    <div class="vp-pull-left">
                        <label class="vp-switch">
                        <input type="checkbox" name="show_numbers" id="show_numbers" checked="checked"/>
                        <div class="vp-slider vp-round"></div>
                        </label>
                    </div>
                    <div class="vp-pull-left vp-ed-lbl">
                        <label for="show_numbers"><?php _e( 'Show numbering', 'viralpress' )?></label>
                    </div>
                    <br/>
               
                <span class="voting_till_span" style="display:none">
                	<br/>
                    <label for="voting_till"><?php _e( 'Voting Open Till', 'viralpress' )?></label><br/>
                    <input type="text" name="voting_till" id="voting_till" class="vp-form-control" />
                </span>                
                <?php if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_post'] ) :?>
                <br/>
                <div class="editor_cap erecap">
                	<?php echo recaptcha_get_html($vp_instance->settings['recap_key'], '', true);?>
                </div>
                <?php else:?>
                <br/>
                <?php endif;?>
                <br/>
                <button class="btn btn-primary vp_submit_post" style="width:100%">
					<i class="glyphicon glyphicon-thumbs-up"></i>&nbsp;&nbsp;
					<?php _e( 'Submit post', 'viralpress' )?>
                </button>
                <span class="vp_submit_post_loader" style="display:none"><img src="<?php echo $vp_instance->settings['IMG_URL']?>/loader-2.gif"/></span>
                <br/><br/>
            </fieldset>
            
            <br/>
            <fieldset>
                <h3 class="vp-pointer" onclick="jQuery('.editor_tips').slideToggle()">
                    <?php _e( 'Notes & Tips', 'viralpress' )?> &nbsp;&nbsp;
                </h3>
                <div id="vp-carousel" class="editor_tips">
                    <div id="vp-buttons">
                        <i class="glyphicon glyphicon-menu-left vp-pull-left vp-pointer" style="font-size:20px" id="vp-slides-prev"></i>&nbsp;&nbsp;
                        <i class="glyphicon glyphicon-menu-right vp-pull-right vp-pointer" style="font-size:20px" id="vp-slides-next"></i>&nbsp;&nbsp;
                    </div>
                    <div id="vp-slides"> 
                        <ul>
                            <li><?php _e( 'Drag & drop entries to reposition them.', 'viralpress' )?></li>
                            <li><?php _e( 'Uncheck or check show numbering checkbox to remove or add numbering from list.', 'viralpress' )?></li>
                            <li><?php _e( 'Each Text entry must have description. Title is optional', 'viralpress' )?></li>
                            <li><?php _e( 'Each Image entry must have an image upload. Title and description are optional.', 'viralpress' )?></li>
                            <li><?php _e( 'Embeds must have and a url embedded. Title and description are optional.', 'viralpress' )?></li>
                            <li><?php _e( 'When you add embed url for video, audio or social sites, fields to add title and description will be shown.', 'viralpress' )?></li>
                            <li><?php _e( 'If you do not add any thumbnail to post, one of the images added to the post will be set as thumbnail.', 'viralpress' )?></li>
                            <li><?php _e( 'When you create quiz or poll, each answer & question must have either a textual or visual presentation or both.', 'viralpress' )?></li>
                            <li><?php _e( 'If you want to put caption into an image, use the caption text box  for an image in media gallery.', 'viralpress' )?></li>
                            <li><?php _e( 'Hit SHIFT+ENTER to put a small line break in rich text editor.', 'viralpress' )?></li>
                        </ul>
                    </div>
                </div>
            </fieldset>
            
            <?php if( $attributes['post_type'] == 'quiz' ): ?>
            <br/>
            <fieldset>
            	<h3 class="vp-pointer" onclick="jQuery('.quiz_tips').slideToggle()">
                    <?php _e( 'Quiz Rules', 'viralpress' )?> &nbsp;&nbsp;
                    <i class="glyphicon glyphicon-chevron-down" style="font-size:12px !important"></i>
                </h3>
                <div class="quiz_tips" style="display:none">
                    <h4><?php _e( 'Scoring rules (MCQ/Trivia)', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'Suitable when one question has only one correct answer', 'viralpress' )?></li>
                        <li><?php _e( 'One point for correct answer', 'viralpress' )?></li>
                        <li><?php _e( 'Zero point for wrong answer', 'viralpress' )?></li>
                        <li><?php _e( 'Use explanation to explain asnwers if needed. MCQ quizzes will show explanation at the end of quiz and trivia will show instantly after answering one question.', 'viralpress' )?></li>
                    </ul>
                    
                    <h4><?php _e( 'Scoring rules (Personality Type 1)', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'Suitable when you want to analyze users habit, behavior or choice based on their answers i.e analytical type quiz', 'viralpress' )?></li>
                        <li><?php _e( 'First add all possible personality results', 'viralpress' )?></li>
                        <li><?php _e( 'Then choose results for each answer', 'viralpress' )?></li>
                        <li><?php _e( 'The final quiz results will be made based on the count of results the user got most of the time', 'viralpress' )?></li>
                    </ul>
                    
                    <h4><?php _e( 'Scoring rules (Personality Type 2)', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'Suitable when you want to analyze users habit, behavior or choice based on their answers i.e analytical type quiz', 'viralpress' )?></li>
                        <li><?php _e( 'When user chooses answer 1, they get one point', 'viralpress' )?></li>
                        <li><?php _e( 'When user chooses answer 2, they get two points', 'viralpress' )?></li>
                        <li><?php _e( 'Similarly when user chooses answer y, they get y points', 'viralpress' )?></li>
                        <li><?php _e( 'In the results section, define the results for the range of point user scores', 'viralpress' )?></li>
                    </ul>
                    
                    <h4><?php _e( 'Score sheet', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'You can set results based on the score user gets', 'viralpress' )?></li>
                        <li><?php _e( 'For different range of scoring, set different results', 'viralpress' )?></li>
                        <li><?php _e( 'If you do not specify results for a specific range, the last score sheet will be used for that range while scoring', 'viralpress' )?></li>
                        <li><?php _e( 'Points above are not applicable for personality quiz type 1', 'viralpress' )?></li>
                    </ul>
                </div>
            </fieldset>
            <?php endif;?>
        </div>
    </div>
</form>

<?php echo vp_get_template_html('modals')?>
<?php echo vp_get_template_html('sdks')?>
<script>
	<?php
	if( $post_type == 'lists' ) {?>prevent_new_item = 1;<?php }?>
	jQuery('.entry-title').eq(0).hide();
	document.title = '<?php echo __( 'Create a post', 'viralpress' )?>';
	jQuery( document ).ready( function( $ ){
		normalize_editor_page();
		<?php
		if( !empty( $post_type ) && empty( $edit_post_id ) ) {?>
		$( '#add_new_post_form' ).show();	
		init_vp_editor( '<?php echo $post_type;?>' );
		<?php }else if( empty( $edit_post_id ) ) {?>
		$( '.vp_editor_intro' ).show();
		<?php }?>	
		$('.editor_loader').hide();
		<?php if( empty( $edit_post_id ) ):?>
		if( typeof vp_lang != 'undefined' )$("#post_tags").val("");$("#post_tags").tagEditor( { 'placeholder': vp_lang.add_tags, 'initialTags' : '' } );
		<?php endif;?>	
	});
</script>