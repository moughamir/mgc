<?php global $video_grid_count, $video_grid_filter, $video_grid_set_list;
$real_post = $post;
$ent_attrs = get_option('youtube_showcase_attr_list');
?>
<a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>">
    <div class="col-sm-3">
        <div class="panel panel-info">
            <div class="panel-body">
                <div style="height: 100px" class="thumbnail imgLiquidFill">
                    <img src="https://img.youtube.com/vi/<?php echo esc_html(emd_mb_meta('emd_video_key')); ?>
/<?php echo emd_get_attr_val('youtube_showcase', $post->ID, 'emd_video', 'emd_video_thumbnail_resolution', 'key'); ?>default.jpg" alt="<?php echo get_the_title(); ?>">
                </div>
            </div>
            <div class="panel-footer textRegular"><?php echo get_the_title(); ?></div>
        </div>
    </div>
</a>