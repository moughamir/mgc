<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/

if ( ! defined('ABSPATH')) exit('No direct script access allowed'); 

$vp_instance = $attributes['vp_instance'];
require_once( $vp_instance->settings['CLASS_DIR']. '/vp_op_list.class.php' );

$view = 'pending';
if( @$_REQUEST['view'] == 'all'  ) $view = 'all';
if( @$_REQUEST['view'] == 'published'  ) $view = 'published';
		
$paged = @(int)$_REQUEST['paged'];
$orderby = @esc_html( $_REQUEST['orderby'] );
$order = @esc_html( $_REQUEST['order'] );
$from_author_o = $from_author = @esc_html( $_REQUEST['from_author'] );
$per_page = 20;

if( $from_author ) {
	$uu = get_user_by( 'slug', $from_author );
	if( $uu )$from_author = $uu->ID;
	else $from_author = 0;	
}

$all = vp_count_all_open_list( $from_author );
$pending = vp_count_pending_open_list( $from_author );
$published = $all - $pending;

echo '<div id="wpbody-content"><div class="wrap">
		<h2>'.__( 'Open lists', 'viralpress' ).'</h2>
		<ul class="subsubsub">
			<li class="all"><a href="admin.php?page=viralpress-openlists&view=all" '.( $view == 'all' ? 'class="current"' : '' ).'>'.__( 'All', 'viralpress' ).' <span class="count">('.$all.')</span></a> |</li>
			<li class="all"><a href="admin.php?page=viralpress-openlists&view=published" '.( $view == 'published' ? 'class="current"' : '' ).'>'.__( 'Published', 'viralpress' ).' <span class="count">('.$published.')</span></a> |</li>
			<li class="pending"><a href="admin.php?page=viralpress-openlists" '.( $view == 'pending' ? 'class="current"' : '' ).'>'.__( 'Pending', 'viralpress' ).' <span class="count">('.$pending.')</span></a></li>
		</ul>';

$table = new VP_Open_List_Item_Table();

if( !empty( $_REQUEST['action'] ) ) {
	$table->process_bulk_action();	
}

$table->from_author = $from_author;
$table->search_params = array( 'view' => $view, 'paged' => $paged, 'order' => $order, 'orderby' => $orderby, '_nonce' => wp_create_nonce( 'vp-admin-action-'.get_current_user_id() ) );

if( $view == 'pending' ) {
	$table->data = vp_get_pending_open_list( $from_author, $paged, $per_page, $orderby, $order );
	$table->total_items = $pending;
}
else if( $view == 'all' ) {
	$table->data = vp_get_all_open_list( $from_author, $paged, $per_page, $orderby, $order );
	$table->total_items = $all;
}
else if( $view == 'published' ) {
	$table->data = vp_get_all_open_list( $from_author, $paged, $per_page, $orderby, $order, 1 );
	$table->total_items = $published;
}

$table->prepare_items();

echo '<form method="" id="oof">
		<p class="search-box">
			<label class="screen-reader-text" for="post-search-input">'.__( 'Search posts from author', 'viralpress' ).':</label>
			<input id="post-search-input" name="from_author" value="'.( $from_author ? $from_author_o : '').'" type="search">
			<input id="search-submit" class="button" value="'.__( 'Search posts from author', 'viralpress' ).'" type="submit">
		</p>
		<input type="hidden" name="page" value="viralpress-openlists">
		<input type="hidden" name="paged" value="'.$paged.'">
		<input type="hidden" name="orderby" value="'.$orderby.'">
		<input type="hidden" name="view" value="'.$view.'">
		<input type="hidden" name="order" value="'.$order.'">';
$table->display();
wp_nonce_field( 'vp-admin-action-'.get_current_user_id(), '_nonce' );
echo '</form>
	</div></div>';

?>

<script>

jQuery( document ).ready( function( $ ) {
	
	$( document ).on( 'click', '#doaction', function( e ) {
		e.preventDefault();
		$('#oof').attr('method', 'post');
		$('#oof').submit();	
	} );
	
	var u = document.URL;
	u = updateQueryStringParameter( u, 'action', '' );
	u = updateQueryStringParameter( u, '_nonce', '' );
	u = u.replace('&&', '&');
	window.history.pushState( 'page', document.title, u );
});

function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, '$1' + '$2');
  }
  else {
    return uri;
  }
}
</script>