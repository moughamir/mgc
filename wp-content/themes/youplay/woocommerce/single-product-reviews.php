<?php
/**
 * Display single product reviews (comments)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.2
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

?>
<div class="reviews-block">
	<h2><?php
		printf( // WPCS: XSS OK
			'Reviews' . (have_comments() ? ' <small>(%s)</small>' : ''),
			number_format_i18n( get_comments_number() )
		);
	?></h2>

	<div class="reviews-list">

		<?php if ( have_comments() ) : ?>

			<ul class="reviews-list">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array(
					'callback'   => 'woocommerce_comments',
					'short_ping' => true,
					'avatar_size'=> 90
				) ) ); ?>
			</ul>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php _e( 'There are no reviews yet.', 'youplay' ); ?></p>

		<?php endif; ?>
	</div>

    <div class="clear"></div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->id ) ) : ?>

		<?php
			$commenter = wp_get_current_commenter();

			$comment_form = array(
				'title_reply'          => have_comments() ? __( 'Add a review', 'youplay' ) : __( 'Be the first to review', 'youplay' ) . ' &ldquo;' . get_the_title() . '&rdquo;',
				'class_submit'         => 'btn btn-default pull-right',
				'comment_field'        => '<div class="youplay-textarea"><textarea id="comment" name="comment" rows="5" aria-required="true" placeholder="' . __( 'Your Review', 'youplay' ) . '"></textarea></div>',
				'logged_in_as'         => '',
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'type'                 => 'review'
			);

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
				$comment_form['comment_field'] .= '<div class="youplay-rating pull-right mb-0">
          <input type="radio" id="rating-5" name="rating" value="5">
          <label for="rating-5"><i class="fa fa-star"></i></label>
          <input type="radio" id="rating-4" name="rating" value="4">
          <label for="rating-4"><i class="fa fa-star"></i></label>
          <input type="radio" id="rating-3" name="rating" value="3">
          <label for="rating-3"><i class="fa fa-star"></i></label>
          <input type="radio" id="rating-2" name="rating" value="2">
          <label for="rating-2"><i class="fa fa-star"></i></label>
          <input type="radio" id="rating-1" name="rating" value="1">
          <label for="rating-1"><i class="fa fa-star"></i></label>
        </div>
        <div class="clearfix"></div>';
			}

			youplay_comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
		?>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', 'youplay' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
