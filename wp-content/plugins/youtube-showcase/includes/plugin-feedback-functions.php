<?php
/**
 * Plugin Page Feedback Functions
 *
 * @package YOUTUBE_SHOWCASE
 * @since WPAS 5.3
 */
if (!defined('ABSPATH')) exit;
add_filter('plugin_row_meta', 'youtube_showcase_plugin_row_meta', 10, 2);
add_filter('plugin_action_links', 'youtube_showcase_plugin_action_links', 10, 2);
add_action('wp_ajax_youtube_showcase_send_deactivate_reason', 'youtube_showcase_send_deactivate_reason');
global $pagenow;
if ('plugins.php' === $pagenow) {
	add_action('admin_footer', 'youtube_showcase_deactivation_feedback_box');
}
add_action('wp_ajax_youtube_showcase_show_rateme', 'youtube_showcase_show_rateme_action');
//check min entity count if its not -1 then show notice
$min_trigger = get_option('youtube_showcase_show_rateme_plugin_min', 5);
if ($min_trigger != - 1) {
	add_action('admin_notices', 'youtube_showcase_show_rateme_notice');
}
function youtube_showcase_show_rateme_action() {
	if (!wp_verify_nonce($_POST['rateme_nonce'], 'youtube_showcase_rateme_nonce')) {
		exit;
	}
	$min_trigger = get_option('youtube_showcase_show_rateme_plugin_min', 5);
	if ($min_trigger == - 1) {
		die;
	}
	if (5 === $min_trigger) {
		$min_trigger = 10;
	} else {
		$min_trigger = - 1;
	}
	update_option('youtube_showcase_show_rateme_plugin_min', $min_trigger);
	echo 1;
	die;
}
function youtube_showcase_show_rateme_notice() {
	if (!current_user_can('manage_options')) {
		return;
	}
	$min_count = 0;
	$ent_list = get_option('youtube_showcase_ent_list');
	$min_trigger = get_option('youtube_showcase_show_rateme_plugin_min', 5);
	$triggerdate = get_option('youtube_showcase_activation_date', false);
	$installed_date = (!empty($triggerdate) ? $triggerdate : '999999999999999');
	$today = mktime(0, 0, 0, date('m') , date('d') , date('Y'));
	$label = $ent_list['emd_video']['label'];
	$count_posts = wp_count_posts('emd_video');
	if ($count_posts->publish > $min_trigger) {
		$min_count = $count_posts->publish;
	}
	if ($min_count > 5 || ($min_trigger == 5 && $installed_date <= $today)) {
		$message_start = '<div class="emd-show-rateme update-nag success">
                        <span class=""><b>YouTube Showcase</b></span>
                        <div>';
		if ($min_count > 5) {
			$message_start.= sprintf(__("Hi, I noticed you just crossed the %d %s milestone on YouTube Showcase - that's awesome!", "youtube-showcase") , $min_trigger, $label);
		} elseif ($installed_date <= $today) {
			$message_start.= __("Hi, I just noticed you have been using YouTube Showcase for about a week now - that's awesome!", "youtube-showcase");
		}
		$message_level1 = __('Could you please do me a <span style="color:red" class="dashicons dashicons-heart"></span> BIG favor <span style="color:red" class="dashicons dashicons-heart"></span> and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', "youtube-showcase");
		$message_level2 = sprintf(__("Would you like to upgrade now to get more out of your %s?", "youtube-showcase") , $label);
		$message_end = '<br/><br/>
                        <strong>Safiye Duman</strong><br>eMarket Design Cofounder<br><a data-rate-action="twitter" style="text-decoration:none" href="https://twitter.com/safiye_emd" target="_blank"><span class="dashicons dashicons-twitter"></span>@safiye_emd</a>
                        </div>
                        <div style="background-color: #f0f8ff;padding: 0 0 10px 10px;width: 300px;border: 1px solid;border-radius: 10px;margin: 14px 0;"><br><strong>Thank you</strong> <span class="dashicons dashicons-smiley"></span>
                        <ul data-nonce="' . wp_create_nonce('youtube_showcase_rateme_nonce') . '">';
		$message_end1 = '<li><a data-rate-action="do-rate" data-plugin="youtube_showcase" href="https://wordpress.org/support/plugin/youtube-showcase/reviews/#postform">' . __('Ok, you deserve it', 'youtube-showcase') . '</a>
       </li>
        <li><a data-rate-action="done-rating" data-plugin="youtube_showcase" href="#">' . __('I already did', 'youtube-showcase') . '</a></li>
        <li><a data-rate-action="not-enough" data-plugin="youtube_showcase" href="#">' . __('Maybe later', 'youtube-showcase') . '</a></li>';
		$message_end2 = '<li><a data-rate-action="upgrade-now" data-plugin="youtube_showcase" href="https://emdplugins.com/plugin_tag/youtube-showcase">' . __('I want to upgrade', 'youtube-showcase') . '</a>
       </li>
        <li><a data-rate-action="not-enough" data-plugin="youtube_showcase" href="#">' . __('Maybe later', 'youtube-showcase') . '</a></li>';
	}
	if ($min_count > 10 && $min_trigger == 10) {
		echo $message_start . '<br>' . $message_level2 . ' ' . $message_end . ' ' . $message_end2 . '</ul></div></div>';
	} elseif ($min_count > 5 || ($min_trigger == 5 && $installed_date <= $today)) {
		echo $message_start . '<br>' . $message_level1 . ' ' . $message_end . ' ' . $message_end1 . '</ul></div></div>';
	}
}
/**
 * Adds links under plugin description
 *
 * @since WPAS 5.3
 * @param array $input
 * @param string $file
 * @return array $input
 */
function youtube_showcase_plugin_row_meta($input, $file) {
	if ($file != 'youtube-showcase/youtube-showcase.php') return $input;
	$links = array(
		'<a href="https://docs.emdplugins.com/docs/youtube-showcase-community-documentation/">' . __('Docs', 'youtube-showcase') . '</a>',
		'<a href="https://emdplugins.com/plugin_tag/youtube-showcase">' . __('Pro Version', 'youtube-showcase') . '</a>'
	);
	$input = array_merge($input, $links);
	return $input;
}
/**
 * Adds links under plugin description
 *
 * @since WPAS 5.3
 * @param array $input
 * @param string $file
 * @return array $input
 */
function youtube_showcase_plugin_action_links($links, $file) {
	if ($file != 'youtube-showcase/youtube-showcase.php') return $links;
	foreach ($links as $key => $link) {
		if ('deactivate' === $key) {
			$links[$key] = $link . '<i class="youtube_showcase-deactivate-slug" data-slug="youtube_showcase-deactivate-slug"></i>';
		}
	}
	$new_links['settings'] = '<a href="' . admin_url('admin.php?page=youtube_showcase_settings') . '">' . __('Settings', 'youtube-showcase') . '</a>';
	$links = array_merge($new_links, $links);
	return $links;
}
function youtube_showcase_deactivation_feedback_box() {
	wp_enqueue_style("emd-plugin-modal", YOUTUBE_SHOWCASE_PLUGIN_URL . 'assets/css/emd-plugin-modal.css');
	$feedback_vars['submit'] = __('Submit & Deactivate', 'youtube-showcase');
	$feedback_vars['skip'] = __('Skip & Deactivate', 'youtube-showcase');
	$feedback_vars['cancel'] = __('Cancel', 'youtube-showcase');
	$feedback_vars['ask_reason'] = __('Kindly tell us the reason so we can improve', 'youtube-showcase');
	$feedback_vars['nonce'] = wp_create_nonce('youtube_showcase_deactivate_nonce');
	$reasons[1] = __('I no longer need the plugin', 'youtube-showcase');
	$reasons[2] = __('I found a better plugin', 'youtube-showcase');
	$reasons[8] = __('I haven\'t found a feature that I need', 'youtube-showcase');
	$reasons[3] = __('I only needed the plugin for a short period', 'youtube-showcase');
	$reasons[4] = __('The plugin broke my site', 'youtube-showcase');
	$reasons[5] = __('The plugin suddenly stopped working', 'youtube-showcase');
	$reasons[6] = __('It\'s a temporary deactivation. I\'m just debugging an issue', 'youtube-showcase');
	$reasons[7] = __('Other', 'youtube-showcase');
	$feedback_vars['msg'] = __('If you have a moment, please let us know why you are deactivating', 'youtube-showcase');
	$feedback_vars['disclaimer'] = __('No private information is sent during your submission. Thank you very much for your help improving our plugin.', 'youtube-showcase');
	$feedback_vars['reasons'] = '';
	foreach ($reasons as $key => $reason) {
		$feedback_vars['reasons'].= '<li class="reason';
		if ($key == 2 || $key == 7 || $key == 8) {
			$feedback_vars['reasons'].= ' has-input';
		}
		$feedback_vars['reasons'].= '"';
		if ($key == 2 || $key == 7 || $key == 8) {
			$feedback_vars['reasons'].= 'data-input-type="textfield"';
			if ($key == 2) {
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('What\'s the plugin\'s name?', 'youtube-showcase') . '"';
			} elseif ($key == 8) {
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('What feature do you need?', 'youtube-showcase') . '"';
			}
		}
		$feedback_vars['reasons'].= '><label><span>
                                        <input type="radio" name="selected-reason" value="' . $key . '"/>
                                        </span><span>' . $reason . '</span></label></li>';
	}
	wp_enqueue_script('emd-plugin-feedback', YOUTUBE_SHOWCASE_PLUGIN_URL . 'assets/js/emd-plugin-feedback.js');
	wp_localize_script("emd-plugin-feedback", 'plugin_feedback_vars', $feedback_vars);
	wp_enqueue_script('youtube-showcase-feedback', YOUTUBE_SHOWCASE_PLUGIN_URL . 'assets/js/youtube-showcase-feedback.js');
	$youtube_showcase_vars['plugin'] = 'youtube_showcase';
	wp_localize_script("youtube-showcase-feedback", 'youtube_showcase_vars', $youtube_showcase_vars);
}
function youtube_showcase_send_deactivate_reason() {
	if (empty($_POST['deactivate_nonce']) || !isset($_POST['reason_id'])) {
		exit;
	}
	if (!wp_verify_nonce($_POST['deactivate_nonce'], 'youtube_showcase_deactivate_nonce')) {
		exit;
	}
	$reason_info = isset($_POST['reason_info']) ? sanitize_text_field($_POST['reason_info']) : '';
	$postfields['reason_id'] = intval($_POST['reason_id']);
	$postfields['plugin_name'] = sanitize_text_field($_POST['plugin_name']);
	if (!empty($reason_info)) {
		$postfields['reason_info'] = $reason_info;
	}
	$args = array(
		'body' => $postfields,
		'sslverify' => false,
		'timeout' => 15,
	);
	$resp = wp_remote_post('https://api.emarketdesign.com/deactivate_info.php', $args);
	echo 1;
	exit;
}
