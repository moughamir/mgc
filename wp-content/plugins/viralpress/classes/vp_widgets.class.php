<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

/**
 * most reacted widget
 */
class vp_most_react_widget extends WP_Widget 
{

	function __construct() {
		parent::__construct(
			'vp_most_react_widget', 
			__('Most Reacted Posts', 'viralpress'), 
			array( 'description' => __( 'Display the posts that were reacted most', 'viralpress' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		global $wpdb;
		$meta_table = $wpdb->prefix.'postmeta';
		$limit = (int)$instance[ 'post_count' ];
		
		$q = $wpdb->get_results( "SELECT post_id FROM $meta_table WHERE meta_key = 'vp_user_react_total' ORDER BY meta_value DESC LIMIT $limit", ARRAY_A );
		$post_ids = array();
		foreach( $q as $qq ) $post_ids[] = $qq['post_id'];
		if( !empty( $post_ids ) ) {
			$p = array(
				'post__in'		=>	$post_ids,
				'post_status' 	=>  'publish',
				'orderby' => 'post__in'
			);
			$query = new WP_Query( $p );	
		
			if( $query->have_posts() ) {
				$l = '';
				$l .= "<ul class='vp_popular_posts'>";
				while( $query->have_posts() ) {
					$query->the_post();
					$p = get_post();
					$ll = '';
					$ll .= "<li>";
					$ll .= '<a href="'.get_permalink().'">'.get_the_title().'</a>';
					$ll .= "</li>";
					
					$ll = apply_filters( 'viralpress_popular_posts_loop', $ll, $p );
					$l .= $ll;
				}
				$l .= "</ul>";
				echo apply_filters( 'viralpress_popular_posts', $l );
			}
			else {
				_e( 'No post to display right now', 'viralpress' );	
			}
			wp_reset_postdata();
		} 
		else {
			_e( 'No post to display right now', 'viralpress' );	
		}
		
		echo $args['after_widget'];
	}
		
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Most popular posts', 'viralpress' );
		}
		if ( isset( $instance[ 'post_count' ] ) ) {
			$post_count = $instance[ 'post_count' ];
		}
		else {
			$post_count = 5;
		}
		?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /><br/><br/>
            <label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Number of posts to display:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="text" value="<?php echo esc_attr( $post_count ); ?>" />
            </p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_count'] = ( ! empty( $new_instance['post_count'] ) ) ? (int)( $new_instance['post_count'] ) : 5;
		return $instance;
	}
}

/**
 * most voted poll widget
 */
class vp_most_voted_widget extends WP_Widget 
{

	function __construct() {
		parent::__construct(
			'vp_most_voted_widget', 
			__('Most Voted Polls', 'viralpress'), 
			array( 'description' => __( 'Display the polls that were most voted', 'viralpress' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		global $wpdb;
		$meta_table = $wpdb->prefix.'postmeta';
		$limit = (int)$instance[ 'post_count' ];
		
		$q = $wpdb->get_results( "SELECT post_id FROM $meta_table WHERE meta_key = 'vp_user_poll_votes_total' ORDER BY meta_value DESC LIMIT $limit", ARRAY_A );
		$post_ids = array();
		foreach( $q as $qq ) $post_ids[] = $qq['post_id'];
		if( !empty( $post_ids ) ) {
			$p = array(
				'post__in'		=>	$post_ids,
				'post_status' 	=>  'publish',
				'orderby' => 'post__in'
			);
			$query = new WP_Query( $p );	
		
			if( $query->have_posts() ) {
				$l = '';
				$l .= "<ul class='vp_popular_polls'>";
				while( $query->have_posts() ) {
					$query->the_post();
					$p = get_post();
					$ll = '';
					$ll .= "<li>";
					$ll .= '<a href="'.get_permalink().'">'.get_the_title().'</a>';
					$ll .= "</li>";
					
					$ll = apply_filters( 'viralpress_popular_polls_loop', $ll, $p );
					$l .= $ll;
				}
				$l .= "</ul>";
				echo apply_filters( 'viralpress_popular_polls', $l );
			}
			else {
				_e( 'No post to display right now', 'viralpress' );	
			}
			wp_reset_postdata();
		} 
		else {
			_e( 'No post to display right now', 'viralpress' );	
		}
		
		echo $args['after_widget'];
	}
		
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Most popular polls', 'viralpress' );
		}
		if ( isset( $instance[ 'post_count' ] ) ) {
			$post_count = $instance[ 'post_count' ];
		}
		else {
			$post_count = 5;
		}
		?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /><br/><br/>
            <label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Number of posts to display:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="text" value="<?php echo esc_attr( $post_count ); ?>" />
            </p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_count'] = ( ! empty( $new_instance['post_count'] ) ) ? (int)( $new_instance['post_count'] ) : 5;
		return $instance;
	}
}

/**
 * most reacted widget
 */
class vp_most_taken_quiz_widget extends WP_Widget 
{

	function __construct() {
		parent::__construct(
			'vp_most_taken_quiz_widget', 
			__('Most Taken Quizzes', 'viralpress'), 
			array( 'description' => __( 'Display the quizzes that were most taken', 'viralpress' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		global $wpdb;
		$meta_table = $wpdb->prefix.'postmeta';
		$limit = (int)$instance[ 'post_count' ];
		
		$q = $wpdb->get_results( "SELECT post_id FROM $meta_table WHERE meta_key = 'vp_total_quiz_taken' ORDER BY meta_value DESC LIMIT $limit", ARRAY_A );
		$post_ids = array();
		foreach( $q as $qq ) $post_ids[] = $qq['post_id'];
		if( !empty( $post_ids ) ) {
			$p = array(
				'post__in'		=>	$post_ids,
				'post_status' 	=>  'publish',
				'orderby' => 'post__in'
			);
			$query = new WP_Query( $p );	
		
			if( $query->have_posts() ) {
				$l = '';
				$l .= "<ul class='vp_popular_quizzes'>";
				while( $query->have_posts() ) {
					$query->the_post();
					$p = get_post();
					$ll = '';
					$ll .= "<li>";
					$ll .= '<a href="'.get_permalink().'">'.get_the_title().'</a>';
					$ll .= "</li>";
					
					$ll = apply_filters( 'viralpress_popular_quiz_loop', $ll, $p );
					$l .= $ll;
				}
				$l .= "</ul>";
				echo apply_filters( 'viralpress_popular_quiz', $l );
			}
			else {
				_e( 'No post to display right now', 'viralpress' );	
			}
			wp_reset_postdata();
		} 
		else {
			_e( 'No post to display right now', 'viralpress' );	
		}
		
		echo $args['after_widget'];
	}
		
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Most popular quizzes', 'viralpress' );
		}
		if ( isset( $instance[ 'post_count' ] ) ) {
			$post_count = $instance[ 'post_count' ];
		}
		else {
			$post_count = 5;
		}
		?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /><br/><br/>
            <label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Number of posts to display:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="text" value="<?php echo esc_attr( $post_count ); ?>" />
            </p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_count'] = ( ! empty( $new_instance['post_count'] ) ) ? (int)( $new_instance['post_count'] ) : 5;
		return $instance;
	}
}

function vp_load_widget() {
	register_widget( 'vp_most_react_widget' );
	register_widget( 'vp_most_voted_widget' );
	register_widget( 'vp_most_taken_quiz_widget' );
}
add_action( 'widgets_init', 'vp_load_widget' );

?>