<?php $real_post = $post;
$ent_attrs = get_option('youtube_showcase_attr_list');
?>
<div class="emd-video-wrapper">
<a title="<?php echo get_the_title(); ?>" href="<?php echo get_permalink(); ?>"><img style="width:320px;height:180px;padding:5px" src="https://img.youtube.com/vi/<?php echo esc_html(emd_mb_meta('emd_video_key')); ?>
/<?php echo emd_get_attr_val('youtube_showcase', $post->ID, 'emd_video', 'emd_video_thumbnail_resolution', 'key'); ?>default.jpg" alt="<?php echo get_the_title(); ?>">
<div class="video-title widget"><?php echo get_the_title(); ?></div>
</a>
</div>