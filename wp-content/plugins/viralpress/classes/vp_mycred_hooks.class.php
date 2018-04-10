<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'vp_mycred_hooks' ) && class_exists( 'myCRED_Hook' ) ) {
	class vp_mycred_hooks extends myCRED_Hook {
		/**
		 * Construct
		 */
		function __construct( $hook_prefs, $type ) {
			
			$defaults = 
				array(
					'vp_liked' => array(
						'creds'   => 1,
						'log'     => '%plural% for like on your post',
						'limit'	  => '0/x'
					),
					'vp_like_w' => array(
						'creds'   => -1,
						'log'     => '%plural% for like withdrawn from your post',
						'limit'	  => '0/x'
					),
					'vp_disliked' => array(
						'creds'   => -1,
						'log'     => '%plural% for dislike on your post',
						'limit'	  => '0/x'
					),
					'vp_dislike_w' => array(
						'creds'   => 1,
						'log'     => '%plural% for dislike withdrawn from your post',
						'limit'	  => '0/x'
					),
					'vp_upvoted' => array(
						'creds'   => 1,
						'log'     => '%plural% for upvote on your post',
						'limit'	  => '0/x'
					),
					'vp_upvote_w' => array(
						'creds'   => -1,
						'log'     => '%plural% for upvote withdrawn from your post',
						'limit'	  => '0/x'
					),
					'vp_downvoted' => array(
						'creds'   => -1,
						'log'     => '%plural% for downvote on your post',
						'limit'	  => '0/x'
					),
					'vp_downvote_w' => array(
						'creds'   => 1,
						'log'     => '%plural% for downvote withdrawn from post',
						'limit'	  => '0/x'
					),
					'vp_reacted' => array(
						'creds'   => 1,
						'log'     => '%plural% for reaction on post',
						'limit'	  => '0/x'
					),
					'vp_react_w' => array(
						'creds'   => -1,
						'log'     => '%plural% for reaction withdrawn from post',
						'limit'	  => '0/x'
					),
					'vp_quiz_taken' => array(
						'creds'   => 1,
						'log'     => '%plural% for quiz participation',
						'limit'	  => '0/x'
					),
					'vp_poll_voted' => array(
						'creds'   => 1,
						'log'     => '%plural% for poll participation',
						'limit'	  => '0/x'
					),
					'vp_openlist' => array(
						'creds'   => 1,
						'log'     => '%plural% for approved openlist',
						'limit'	  => '0/x'
					)
				);					
			
			//if ( isset( $hook_prefs['vp_mycred_hooks'] ) ) $defaults = $hook_prefs['vp_mycred_hooks'];
			
			parent::__construct( array(
					'id'       => 'vp_mycred_hooks',
					'defaults' => $defaults
					), 
				$hook_prefs, $type );
		}

		/**
		 * Hook into WordPress
		 */
		public function run() {
			add_action( 'viralpress_list_liked',  array( $this, 'list_liked' ), 10, 5 );
			add_action( 'viralpress_list_disliked', array( $this, 'list_disliked' ), 10, 5 );
			
			add_action( 'viralpress_list_like_withdrawn',  array( $this, 'list_like_withdrawn' ), 10, 4 );
			add_action( 'viralpress_list_dislike_withdrawn',  array( $this, 'list_dislike_withdrawn' ), 10, 4 );
			
			add_action( 'viralpress_list_upvoted',  array( $this, 'list_upvoted' ), 10, 5 );
			add_action( 'viralpress_list_downvoted', array( $this, 'list_downvoted' ), 10, 5  );
			
			add_action( 'viralpress_list_upvote_withdrawn',  array( $this, 'list_upvote_withdrawn' ), 10, 4 );
			add_action( 'viralpress_list_downvote_withdrawn', array( $this, 'list_downvote_withdrawn', 10, 4 ) );
			
			add_action( 'viralpress_post_reacted', array( $this, 'post_reacted' ), 10, 4 );
			add_action( 'viralpress_post_reaction_withdrawn', array( $this, 'post_react_w'), 10, 3  );
			
			
			add_action( 'viralpress_poll_voted', array( $this, 'poll_voted' ), 10, 4 );
			add_action( 'viralpress_quiz_taken', array( $this, 'quiz_taken' ), 10, 3 );
			
			add_action( 'viralpress_open_list_approve', array( $this, 'op_approved' ), 10, 3 );
			
			
		}

		public function list_liked( $post_id, $user_id, $u, $d, $retake ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( !$this->over_hook_limit( 'vp_liked', 'vp_liked', $user_id ) ) {			
				$this->core->add_creds(
					'vp_liked',
					$user_id,
					$this->prefs['vp_liked']['creds'],
					$this->prefs['vp_liked']['log'],
					0,
					'',
					'mycred_default'
				);
			}
			
			if( $retake ) {
				
				if ( $this->over_hook_limit( 'vp_dislike_w', 'vp_dislike_w', $user_id ) ) return;
				
				$this->core->add_creds(
					'vp_dislike_w',
					$user_id,
					$this->prefs['vp_dislike_w']['creds'],
					$this->prefs['vp_dislike_w']['log'],
					0,
					'',
					'mycred_default'
				);	
			}
		}
		
		public function list_like_withdrawn( $post_id, $user_id, $u, $d ) {
			
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( $this->over_hook_limit( 'vp_like_w', 'vp_like_w', $user_id ) ) return;
						
			$this->core->add_creds(
				'vp_like_w',
				$user_id,
				$this->prefs['vp_like_w']['creds'],
				$this->prefs['vp_like_w']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function list_disliked( $post_id, $user_id, $u, $d, $retake ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( !$this->over_hook_limit( 'vp_disliked', 'vp_disliked', $user_id ) ) {			
				$this->core->add_creds(
					'vp_disliked',
					$user_id,
					$this->prefs['vp_disliked']['creds'],
					$this->prefs['vp_disliked']['log'],
					0,
					'',
					'mycred_default'
				);
			}
			
			if( $retake ) {
				
				if ( $this->over_hook_limit( 'vp_like_w', 'vp_like_w', $user_id ) ) return;
				
				$this->core->add_creds(
					'vp_like_w',
					$user_id,
					$this->prefs['vp_like_w']['creds'],
					$this->prefs['vp_like_w']['log'],
					0,
					'',
					'mycred_default'
				);	
			}
		}
		
		public function list_dislike_withdrawn( $post_id, $user_id, $u, $d ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( $this->over_hook_limit( 'vp_dislike_w', 'vp_dislike_w', $user_id ) ) return;
			
			$this->core->add_creds(
				'vp_dislike_w',
				$user_id,
				$this->prefs['vp_dislike_w']['creds'],
				$this->prefs['vp_dislike_w']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function list_upvoted( $post_id, $user_id, $u, $d, $retake ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( !$this->over_hook_limit( 'vp_upvoted', 'vp_upvoted', $user_id ) ) {			
				$this->core->add_creds(
					'vp_upvoted',
					$user_id,
					$this->prefs['vp_upvoted']['creds'],
					$this->prefs['vp_upvoted']['log'],
					0,
					'',
					'mycred_default'
				);
			}
			
			if( $retake ) {
				
				if ( $this->over_hook_limit( 'vp_downvote_w', 'vp_downvote_w', $user_id ) ) return;
				
				$this->core->add_creds(
					'vp_downvote_w',
					$user_id,
					$this->prefs['vp_downvote_w']['creds'],
					$this->prefs['vp_downvote_w']['log'],
					0,
					'',
					'mycred_default'
				);	
			}
		}
		
		public function list_upvote_withdrawn( $post_id, $user_id, $u, $d ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( $this->over_hook_limit( 'vp_upvote_w', 'vp_upvote_w', $user_id ) ) return;
			
			$this->core->add_creds(
				'vp_upvote_w',
				$user_id,
				$this->prefs['vp_upvote_w']['creds'],
				$this->prefs['vp_upvote_w']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function list_downvoted( $post_id, $user_id, $u, $d, $retake ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( !$this->over_hook_limit( 'vp_downvoted', 'vp_downvoted', $user_id ) ) {
				$this->core->add_creds(
					'vp_downvoted',
					$user_id,
					$this->prefs['vp_downvoted']['creds'],
					$this->prefs['vp_downvoted']['log'],
					0,
					'',
					'mycred_default'
				);
			}
			
			if( $retake ) {
				
				if ( $this->over_hook_limit( 'vp_upvote_w', 'vp_upvote_w', $user_id ) ) return;
				
				$this->core->add_creds(
					'vp_upvote_w',
					$user_id,
					$this->prefs['vp_upvote_w']['creds'],
					$this->prefs['vp_upvote_w']['log'],
					0,
					'',
					'mycred_default'
				);	
			}
		}
		
		public function list_downvote_withdrawn( $post_id, $user_id, $u, $d ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( $this->over_hook_limit( 'vp_downvote_w', 'vp_downvote_w', $user_id ) ) return;
			
			$this->core->add_creds(
				'vp_downvote_w',
				$user_id,
				$this->prefs['vp_downvote_w']['creds'],
				$this->prefs['vp_downvote_w']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function post_reacted( $post_id, $user_id, $type, $new ) {
			
			if( !$new ) return;
			
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( $this->over_hook_limit( 'vp_reacted', 'vp_reacted', $user_id ) ) return;

			$this->core->add_creds(
				'vp_reacted',
				$user_id,
				$this->prefs['vp_reacted']['creds'],
				$this->prefs['vp_reacted']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function post_react_w( $post_id, $user_id, $type ) {
			if ( $this->core->exclude_user( $user_id ) ) return;
			if ( $this->over_hook_limit( 'vp_react_w', 'vp_react_w', $user_id ) ) return;
			
			$this->core->add_creds(
				'vp_react_w',
				$user_id,
				$this->prefs['vp_react_w']['creds'],
				$this->prefs['vp_react_w']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function poll_voted( $poll_id, $user_id, $poll_votes )
		{
			$post_author = get_post_field( 'post_author', $poll_id );
			if( $user_id == $post_author ) return;
			
			if ( !$this->core->exclude_user( $user_id ) && !$this->over_hook_limit( 'vp_poll_voted', 'vp_poll_voted', $user_id ) ) {			
				$this->core->add_creds(
					'vp_poll_voted',
					$user_id,
					$this->prefs['vp_poll_voted']['creds'],
					$this->prefs['vp_poll_voted']['log'],
					0,
					'',
					'mycred_default'
				);
			}
			
			
			if ( $this->core->exclude_user( $post_author ) || $this->over_hook_limit( 'vp_poll_voted', 'vp_poll_voted', $post_author ) ) return;
			
			$this->core->add_creds(
				'vp_poll_voted',
				$post_author,
				$this->prefs['vp_poll_voted']['creds'],
				$this->prefs['vp_poll_voted']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function quiz_taken( $post_id, $user_id, $cval )
		{
			$post_author = get_post_field( 'post_author', $post_id );
			if( $user_id == $post_author ) return;
			
			if ( !$this->core->exclude_user( $user_id ) && !$this->over_hook_limit( 'vp_quiz_taken', 'vp_quiz_taken', $user_id )  ) {
				$this->core->add_creds(
					'vp_quiz_taken',
					$user_id,
					$this->prefs['vp_quiz_taken']['creds'],
					$this->prefs['vp_quiz_taken']['log'],
					0,
					'',
					'mycred_default'
				);
			}
			
			
			if ( $this->core->exclude_user( $post_author ) || $this->over_hook_limit( 'vp_quiz_taken', 'vp_quiz_taken', $post_author )  ) return;
			
			$this->core->add_creds(
				'vp_quiz_taken',
				$post_author,
				$this->prefs['vp_quiz_taken']['creds'],
				$this->prefs['vp_quiz_taken']['log'],
				0,
				'',
				'mycred_default'
			);
		}
		
		public function op_approved( $ok, $this_post, $that_post ) 
		{
			$user_id = $this_post->post_author;
			$t_user = $that_post->post_author;
			
			if ( !$this->core->exclude_user( $user_id ) && !$this->over_hook_limit( 'vp_openlist', 'vp_openlist', $user_id ) ) {			
				$this->core->add_creds(
					'vp_openlist',
					$user_id,
					$this->prefs['vp_openlist']['creds'],
					$this->prefs['vp_openlist']['log'],
					0,
					'',
					'mycred_default'
				);
			}
			
			
			if ( $this->core->exclude_user( $t_user ) || $t_user == $user_id ||  $this->over_hook_limit( 'vp_openlist', 'vp_openlist', $t_user )  ) return;
			
			$this->core->add_creds(
				'vp_openlist',
				$t_user,
				$this->prefs['vp_openlist']['creds'],
				$this->prefs['vp_openlist']['log'],
				0,
				'',
				'mycred_default'
			);	
		}
	
		/**
		 * Add Settings
		 */
		 public function preferences() {
			// Our settings are available under $this->prefs
			$prefs = $this->prefs; 
			?>
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for likes on their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_liked' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_liked' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_liked']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_liked' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_liked' => 'limit' ) ), $this->field_id( array( 'vp_liked' => 'limit' ) ), $prefs['vp_liked']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_liked' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_liked' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_liked' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_liked']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for likes withdrawn from their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_like_w' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_like_w' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_like_w']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_like_w' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_like_w' => 'limit' ) ), $this->field_id( array( 'vp_like_w' => 'limit' ) ), $prefs['vp_like_w']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_like_w' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_like_w' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_like_w' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_like_w']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for dislikes on their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_disliked' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_disliked' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_disliked']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_disliked' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_disliked' => 'limit' ) ), $this->field_id( array( 'vp_disliked' => 'limit' ) ), $prefs['vp_disliked']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_disliked' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_disliked' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_disliked' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_disliked']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for dislikes withdrawn from their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_dislike_w' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_dislike_w' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_dislike_w']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_dislike_w' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_dislike_w' => 'limit' ) ), $this->field_id( array( 'vp_dislike_w' => 'limit' ) ), $prefs['vp_dislike_w']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_dislike_w' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_dislike_w' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_dislike_w' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_dislike_w']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for upvotes on their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_upvoted' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_upvoted' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_upvoted']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_upvoted' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_upvoted' => 'limit' ) ), $this->field_id( array( 'vp_upvoted' => 'limit' ) ), $prefs['vp_upvoted']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_upvoted' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_upvoted' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_upvoted' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_upvoted']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for upvotes withdrawn from their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_upvote_w' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_upvote_w' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_upvote_w']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_upvote_w' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_upvote_w' => 'limit' ) ), $this->field_id( array( 'vp_upvote_w' => 'limit' ) ), $prefs['vp_upvote_w']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_upvote_w' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_upvote_w' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_upvote_w' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_upvote_w']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for downvotes on their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_downvoted' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_downvoted' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_downvoted']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_downvoted' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_downvoted' => 'limit' ) ), $this->field_id( array( 'vp_downvoted' => 'limit' ) ), $prefs['vp_downvoted']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_downvoted' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_downvoted' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_downvoted' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_downvoted']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for downvotes withdrawn from their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_downvote_w' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_downvote_w' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_downvote_w']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_downvote_w' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_downvote_w' => 'limit' ) ), $this->field_id( array( 'vp_downvote_w' => 'limit' ) ), $prefs['vp_downvote_w']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_downvote_w' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_downvote_w' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_downvote_w' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_downvote_w']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for reactions on their posts', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_reacted' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_reacted' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_reacted']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_reacted' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_reacted' => 'limit' ) ), $this->field_id( array( 'vp_reacted' => 'limit' ) ), $prefs['vp_reacted']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_reacted' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_reacted' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_reacted' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_reacted']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for reaction withdrawn from their list items', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_react_w' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_react_w' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_react_w']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_react_w' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_react_w' => 'limit' ) ), $this->field_id( array( 'vp_react_w' => 'limit' ) ), $prefs['vp_react_w']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_react_w' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_react_w' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_react_w' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_react_w']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for quiz participation (both owner and participant)', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_quiz_taken' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_quiz_taken' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_quiz_taken']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_quiz_taken' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_quiz_taken' => 'limit' ) ), $this->field_id( array( 'vp_quiz_taken' => 'limit' ) ), $prefs['vp_quiz_taken']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_quiz_taken' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_quiz_taken' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_quiz_taken' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_quiz_taken']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for poll participation (both owner and participant)', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_poll_voted' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_poll_voted' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_poll_voted']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_poll_voted' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_poll_voted' => 'limit' ) ), $this->field_id( array( 'vp_poll_voted' => 'limit' ) ), $prefs['vp_poll_voted']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_poll_voted' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_poll_voted' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_poll_voted' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_poll_voted']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
            <label class="subheader"><?php echo $this->core->template_tags_general( __( '%plural% for open list approval (both owner and submitter)', 'viralpress' ) ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_openlist' => 'creds' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_openlist' => 'creds' ) ); ?>" value="<?php echo $this->core->number( $prefs['vp_openlist']['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'vp_openlist' => 'limit' ) ); ?>"><?php _e( 'Limit', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( array( 'vp_openlist' => 'limit' ) ), $this->field_id( array( 'vp_openlist' => 'limit' ) ), $prefs['vp_openlist']['limit'] ); ?>
                </li>
                <li>
                	<label for="<?php echo $this->field_id( array( 'vp_openlist' => 'log' ) ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'vp_openlist' => 'log' ) ); ?>" id="<?php echo $this->field_id( array( 'vp_openlist' => 'log' ) ); ?>" value="<?php echo esc_attr( $prefs['vp_openlist']['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            
		<?php
		}
	
		/**
		 * Sanitize Preferences
		 */
		public function sanitise_preferences( $data ) {
						
			if ( isset( $data['vp_liked']['limit'] ) && isset( $data['vp_liked']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_liked']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_liked']['limit'] = $limit . '/' . $data['vp_liked']['limit_by'];
				unset( $data['vp_liked']['limit_by'] );
			}
			
			if ( isset( $data['vp_like_w']['limit'] ) && isset( $data['vp_like_w']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_like_w']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_like_w']['limit'] = $limit . '/' . $data['vp_like_w']['limit_by'];
				unset( $data['vp_like_w']['limit_by'] );
			}
			
			if ( isset( $data['vp_disliked']['limit'] ) && isset( $data['vp_disliked']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_disliked']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_disliked']['limit'] = $limit . '/' . $data['vp_disliked']['limit_by'];
				unset( $data['vp_disliked']['limit_by'] );
			}
			
			if ( isset( $data['vp_dislike_w']['limit'] ) && isset( $data['vp_dislike_w']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_dislike_w']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_dislike_w']['limit'] = $limit . '/' . $data['vp_dislike_w']['limit_by'];
				unset( $data['vp_dislike_w']['limit_by'] );
			}
			
			if ( isset( $data['vp_upvoted']['limit'] ) && isset( $data['vp_upvoted']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_upvoted']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_upvoted']['limit'] = $limit . '/' . $data['vp_upvoted']['limit_by'];
				unset( $data['vp_upvoted']['limit_by'] );
			}
			
			if ( isset( $data['vp_upvote_w']['limit'] ) && isset( $data['vp_upvote_w']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_upvote_w']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_upvote_w']['limit'] = $limit . '/' . $data['vp_upvote_w']['limit_by'];
				unset( $data['vp_upvote_w']['limit_by'] );
			}
			
			if ( isset( $data['vp_downvoted']['limit'] ) && isset( $data['vp_downvoted']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_downvoted']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_downvoted']['limit'] = $limit . '/' . $data['vp_downvoted']['limit_by'];
				unset( $data['vp_downvoted']['limit_by'] );
			}
			
			if ( isset( $data['vp_downvote_w']['limit'] ) && isset( $data['vp_downvote_w']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_downvote_w']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_downvote_w']['limit'] = $limit . '/' . $data['vp_downvote_w']['limit_by'];
				unset( $data['vp_downvote_w']['limit_by'] );
			}
			
			if ( isset( $data['vp_reacted']['limit'] ) && isset( $data['vp_reacted']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_reacted']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_reacted']['limit'] = $limit . '/' . $data['vp_reacted']['limit_by'];
				unset( $data['vp_reacted']['limit_by'] );
			}
			
			if ( isset( $data['vp_react_w']['limit'] ) && isset( $data['vp_react_w']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_react_w']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_react_w']['limit'] = $limit . '/' . $data['vp_react_w']['limit_by'];
				unset( $data['vp_react_w']['limit_by'] );
			}
			
			if ( isset( $data['vp_quiz_taken']['limit'] ) && isset( $data['vp_quiz_taken']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_quiz_taken']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_quiz_taken']['limit'] = $limit . '/' . $data['vp_quiz_taken']['limit_by'];
				unset( $data['vp_quiz_taken']['limit_by'] );
			}
			
			if ( isset( $data['vp_poll_voted']['limit'] ) && isset( $data['vp_poll_voted']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_poll_voted']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_poll_voted']['limit'] = $limit . '/' . $data['vp_poll_voted']['limit_by'];
				unset( $data['vp_poll_voted']['limit_by'] );
			}
			
			if ( isset( $data['vp_openlist']['limit'] ) && isset( $data['vp_openlist']['limit_by'] ) ) {
				$limit = sanitize_text_field( $data['vp_openlist']['limit'] );
				if ( $limit == '' ) $limit = 0;
				$data['vp_openlist']['limit'] = $limit . '/' . $data['vp_openlist']['limit_by'];
				unset( $data['vp_openlist']['limit_by'] );
			}

			return $data;
		}
	}
}

?>