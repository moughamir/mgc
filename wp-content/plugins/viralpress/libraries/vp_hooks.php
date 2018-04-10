<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

/**
 * custom viralpress hooks
 */
add_action( 'viralpress_list_liked', 'vp_list_like_sort', 10, 7 );
add_action( 'viralpress_list_disliked', 'vp_list_like_sort', 10, 7 );
add_action( 'viralpress_list_upvoted', 'vp_list_vote_sort', 10, 7 );
add_action( 'viralpress_list_downvoted', 'vp_list_vote_sort', 10, 7 );

add_action( 'viralpress_list_upvote_withdrawn', 'vp_list_vote_sort', 10, 7 );
add_action( 'viralpress_list_downvote_withdrawn', 'vp_list_vote_sort', 10, 7 );
add_action( 'viralpress_list_like_withdrawn', 'vp_list_like_sort', 10, 7 );
add_action( 'viralpress_list_dislike_withdrawn', 'vp_list_like_sort', 10, 7 );

/**
 * functions for hooks
 */
function vp_list_like_sort( $post_id, $a, $b, $c, $d, $parent, $open )
{
	if( empty( $open ) || empty( $parent) ) return;
	resort_vp_post( $parent, 'like' );
}

function vp_list_vote_sort( $post_id, $a, $b, $c, $d, $parent, $open )
{
	if( empty( $open ) || empty( $parent) ) return;
	resort_vp_post( $parent, 'vote' );
}

?>