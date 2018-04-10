<?php
/**
 * Getting Started
 *
 * @package YOUTUBE_SHOWCASE
 * @since WPAS 5.3
 */
if (!defined('ABSPATH')) exit;
add_action('youtube_showcase_getting_started', 'youtube_showcase_getting_started');
/**
 * Display getting started information
 * @since WPAS 5.3
 *
 * @return html
 */
function youtube_showcase_getting_started() {
	global $title;
	list($display_version) = explode('-', YOUTUBE_SHOWCASE_VERSION);
?>
<style>
#emd-about ul li:before{
    content: "\f522";
    font-family: dashicons;
    font-size:25px;
 }
#emd-about .top{
text-decoration:none;
}
#emd-about h3,
#emd-about h2{
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0.6em;
    margin-left: 0px;
}
#emd-about .top:after{
content: "\f342";
    font-family: dashicons;
    font-size:25px;
text-decoration:none;
}
#emd-about li,
#emd-about a{
vertical-align: top;
}
#emd-about .quote{
    background: #fff;
    border-left: 4px solid #088cf9;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    margin: 5px 15px 2px;
    padding: 1px 12px;
}
</style>
<div id="emd-about" class="wrap about-wrap">
<div id="emd-header" style="padding:10px 0" class="wp-clearfix">
<div style="float:right"><img src="http://emd-plugins.s3.amazonaws.com/youtubesc-icon-128x128.png"></div>
<div style="margin: .2em 200px 0 0;padding: 0;color: #32373c;line-height: 1.2em;font-size: 2.8em;font-weight: 400;">
<?php printf(__('Welcome to YouTube Showcase %s', 'youtube-showcase') , $display_version); ?>
</div>

<p class="about-text">
<?php printf(__('YouTube Showcase is a powerful but simple-to-use YouTube video gallery plugin with responsive frontend.', 'youtube-showcase') , $display_version); ?>
</p>

<?php
	$tabs['getting-started'] = __('Getting Started', 'youtube-showcase');
	$tabs['whats-new'] = __('What\'s New', 'youtube-showcase');
	$tabs['resources'] = __('Resources', 'youtube-showcase');
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'getting-started';
	echo '<h2 class="nav-tab-wrapper wp-clearfix">';
	foreach ($tabs as $ktab => $mytab) {
		$tab_url[$ktab] = esc_url(add_query_arg(array(
			'tab' => $ktab
		)));
		$active = "";
		if ($active_tab == $ktab) {
			$active = "nav-tab-active";
		}
		echo '<a href="' . esc_url($tab_url[$ktab]) . '" class="nav-tab ' . $active . '" id="nav-' . $ktab . '">' . $mytab . '</a>';
	}
	echo '</h2>';
	echo '<div class="tab-content" id="tab-getting-started"';
	if ("getting-started" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div style="height:25px" id="ptop"></div><h3 style="color:#0073AA;text-align:left;">Quickstart</h3><ul><li><a href="#gs-sec-6">Using Setup assistant</a></li>
<li><a href="#gs-sec-5">How to find your YouTube Video ID</a></li>
<li><a href="#gs-sec-3">How to create your first video</a></li>
<li><a href="#gs-sec-20">How to resolve theme related issues</a></li>
</ul><div class="quote">
<p class="about-description">The secret of getting ahead is getting started - Mark Twain</p>
</div>
<div class="changelog getting-started getting-started-6" style="margin:0"><div style="height:40px" id="gs-sec-6"></div><h2>Using Setup assistant</h2><div class="sec-img"><img src="https://emd-plugins.s3.amazonaws.com/video_gallery_540.png"></div><div class="sec-desc"><p>Setup assistant creates the gallery pages automatically.</p></div></div><a href="#ptop" class="top">Go to top</a><hr style="margin-top:40px"><div class="changelog getting-started getting-started-5" style="margin:0"><div style="height:40px" id="gs-sec-5"></div><h2>How to find your YouTube Video ID</h2><div class="sec-img"><img src="https://emd-plugins.s3.amazonaws.com/video_id_540.png"></div><div class="sec-desc"><p>It is very simple to find your YouTube video ID. First, go to the YouTube webpage. Look at the URL of that page, and at the end of it, you should see a combination of numbers and letters after an equal sign (=). This is the code you need to enter into the video key field.</p></div></div><a href="#ptop" class="top">Go to top</a><hr style="margin-top:40px"><div class="changelog getting-started getting-started-3" style="margin:0"><div style="height:40px" id="gs-sec-3"></div><h2>How to create your first video</h2><div class="sec-img"><img src="https://emd-plugins.s3.amazonaws.com/video_edit_540.png"></div><div class="sec-desc"><ol>
  <li>Log in to your Administration Panel.</li>
  <li>Click the 'Videos' tab.</li>
  <li>Click the 'Add New' sub-tab or the “Add New” button in the video list page.</li>
  <li>Start filling in your video fields. You must fill all required fields. All required fields have red star after their labels.</li>
  <li>As needed, set video taxonomies and relationships. All required relationships or taxonomies must be set.</li>
  <li>When you are ready, click Publish. If you do not have publish privileges, the "Submit for Review" button is displayed.</li>
  <li>After the submission is completed, the video status changes to "Published"</li>
<li>Click on the permalink to see the video page</li>
</ol></div></div><a href="#ptop" class="top">Go to top</a><hr style="margin-top:40px"><div class="changelog getting-started getting-started-20" style="margin:0"><div style="height:40px" id="gs-sec-20"></div><h2>How to resolve theme related issues</h2><div class="sec-desc"><p>If your theme is not coded based on WordPress theme coding standards and does have an unorthodox markup, you will see some things on your site such as sidebars not getting displayed where they are supposed to or random text getting displayed on headers etc. The good news is you may fix all of theme related conflicts following the steps in the documentation.</p>
<p>Please note that if you’re unfamiliar with code/templates and resolving potential conflicts, we strongly suggest to <a href="https://emdplugins.com/open-a-support-ticket/?pk_campaign=ytsccom-hireme">hire us</a>  or a developer to complete the project for you.</p>
<p>
<a href="https://docs.emdplugins.com/docs/youtube-showcase-community-documentation/#section1474">YouTube Showcase Community Edition Documentation - Resolving theme related conflicts</a>
</p></div></div><a href="#ptop" class="top">Go to top</a><hr style="margin-top:40px">

<?php echo '</div>';
	echo '<div class="tab-content" id="tab-whats-new"';
	if ("whats-new" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<p class="about-description">YouTube Showcase V2.5.1 offers many new features, bug fixes and improvements.</p>

<div class="wp-clearfix"><div class="changelog whats-new whats-new-15" style="margin:0"><h2 class="fix"><div  style="font-size:110%;color:black"><span class="dashicons dashicons-admin-tools"></span> FIX</div>
Plugin deactivation issue when “Remove All Data” checked in plugin settings</h2><div ></a><p>When the plugin was deleted from WordPress Plugin page, the plugin deactivation process was deleting WordPress category and tags if “Remove All Data” checked under Video Settings > Tools.</p></div></div></div><hr style="margin:30px 0">
<?php echo '</div>';
	echo '<div class="tab-content" id="tab-resources"';
	if ("resources" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div class="changelog resources resources-4" style="margin:0"><h2>Extensive documentation is available</h2><div class="sec-desc"><a href="https://docs.emdplugins.com/docs/youtube-showcase-community-documentation">YouTube Showcase Community Edition Documentation</a></div></div><hr style="margin:30px 0"><div class="changelog resources resources-8" style="margin:0"><h2>EMD CSV Import Export Extension allows getting your videos in and out of WordPress quickly</h2><div class="sec-img"><img src="https://emd-plugins.s3.amazonaws.com/video_impexp.png"></div><div class="sec-desc"><p>EMD CSV Import Export Extension helps bulk import, export, update video information from CSV files. You can also reset(delete) all data and start over again without modifying database.</p>
<p><a href="https://emdplugins.com/plugins/emd-csv-import-export-extension/?pk_campaign=emdimpexp-buybtn&pk_kwd=ytsc-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></p></div></div><hr style="margin:30px 0"><div class="changelog resources resources-7" style="margin:0"><h2>YouTube Showcase Pro WordPress plugin helps you keep more visitors on your site longer
-</h2><div class="sec-img"><img src="https://emd-plugins.s3.amazonaws.com/video_pro.png"></div><div class="sec-desc"><p>- EMD CSV Import Export Extension is included</p>
<p>- Visual Shortcode Builder is included </p>
<div style="margin:25px"><a href="https://emdplugins.com/plugins/youtube-showcase-professional/?pk_campaign=ytscpro-buybtn&pk_kwd=ytsc-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a>
</div></div></div><hr style="margin:30px 0">
<?php echo '</div></div>';
}
