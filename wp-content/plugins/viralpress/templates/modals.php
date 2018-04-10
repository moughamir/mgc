<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
?>
<?php add_thickbox(); ?>
<a href="#TB_inline?width=550&height=500&inlineId=add_video_modal" class="thickbox add_video_modal_link" id="add_video_modal_link" style="display:none" title="<?php _e( 'Add a video', 'viralpress' )?>"></a>	
<div id="add_video_modal" style="display:none">
    <div class="row">
    	<div class="col-lg-12">
        	<p>        	
                <span class="video_add_error_msg"></span>
                <label><?php _e( 'Insert iframe embed code or URL', 'viralpress' )?></label><br/>
                <input type="text" class="url vp-form-control" id="video_url" placeholder="<?php _e( 'Enter iframe embed code or URL', 'viralpress' )?>"/><br/>
                <input type="hidden" id="video_url_elem" />
                <label><?php _e( 'Width', 'viralpress' )?></label><br/>
                <input type="text" class="w vp-form-control" id="video_width" value="<?php echo apply_filters( 'viralpress_editor_embed_width', 600, 400, 'video', null, null )?>" /><br/>
                <label><?php _e( 'Height', 'viralpress' )?></label><br/>
                <input type="text" class="h vp-form-control" id="video_height" value="<?php echo apply_filters( 'viralpress_editor_embed_height', 400, 600, 'video', null, null )?>" /><br/><br/>
                <button class="btn btn-info add_video_submit"><?php _e( 'Fetch', 'viralpress' ) ?></button>
            </p>
        </div>
    </div>
</div>

<a href="#TB_inline?width=550&height=500&inlineId=add_audio_modal" class="thickbox add_audio_modal_link" id="add_audio_modal_link" style="display:none" title="<?php _e( 'Add an audio', 'viralpress' )?>"></a>	
<div id="add_audio_modal" style="display:none">
    <div class="row">
    	<div class="col-lg-12">
        	<p>
                <span class="audio_add_error_msg"></span>
                <label><?php _e( 'Insert iframe embed code or url', 'viralpress' )?></label><br/>
                <input type="text" class="url vp-form-control" id="audio_url" placeholder="<?php _e( 'Enter iframe embed code or URL', 'viralpress' )?>"/><br/>
                <input type="hidden" id="audio_url_elem" />
                <label><?php _e( 'Width', 'viralpress' )?></label><br/>
                <input type="text" class="w vp-form-control" id="audio_width" value="<?php echo apply_filters( 'viralpress_editor_embed_width', 600, 800, 'audio', null, null )?>" /><br/>
                <label><?php _e( 'Height', 'viralpress' )?></label><br/>
                <input type="text" class="h vp-form-control" id="audio_height" value="<?php echo apply_filters( 'viralpress_editor_embed_height', 800, 600, 'audio', null, null )?>" /><br/><br/>
                <button class="btn btn-info add_audio_submit"><?php _e( 'Fetch', 'viralpress' ) ?></button>
            </p>
        </div>
    </div>
</div>

<a href="#TB_inline?width=550&height=500&inlineId=add_pin_modal" class="thickbox add_pin_modal_link" id="add_pin_modal_link" style="display:none" title="<?php _e( 'Add embed', 'viralpress' )?>"></a>	
<div id="add_pin_modal" style="display:none">
    <div class="row">
    	<div class="col-lg-12">
        	<p>
                <span class="pin_add_error_msg"></span>
                <label><?php _e( 'Enter iframe embed code or URL', 'viralpress' )?></label>
                <input type="text" class="url vp-form-control" id="pin_url" placeholder="<?php _e( 'Enter iframe embed code or URL', 'viralpress' )?>"/><br/>
                <input type="hidden" id="pin_url_elem" /><br/>
                <label><?php _e( 'Width', 'viralpress' )?></label><br/>
                <input type="text" class="w vp-form-control" id="pin_width" value="<?php echo apply_filters( 'viralpress_editor_embed_width', 600, 350, 'pin', null, null )?>" /><br/>
                <label><?php _e( 'Height', 'viralpress' )?></label><br/>
                <input type="text" class="h vp-form-control" id="pin_height" value="<?php echo apply_filters( 'viralpress_editor_embed_height', 350, 600, 'pin', null, null )?>" /><br/><br/>
                <button class="btn btn-info add_pin_submit"><?php _e( 'Fetch', 'viralpress' ) ?></button>
            </p>
        </div>
    </div>
</div>
