<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/

if ( ! defined('ABSPATH')) exit('No direct script access allowed'); 
/**
 * Create a new table class that will extend the WP_List_Table
 */
if( ! class_exists( 'WP_List_Table' ) ) {
	$admin_path = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() );
	require_once( $admin_path . '/includes/class-wp-list-table.php' );
}
class VP_Open_List_Item_Table extends WP_List_Table {  

	public $data;
	public $total_items;
	public $from_author;
	public $search_params;

    function __construct(){       
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'item',     //singular name of the listed records
            'plural'    => 'items',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );        
    }
	
	
	function extra_tablenav( $which ) {
	}

    function column_default($item, $column_name){
        switch($column_name){
            case 'post_title':
            case 'post_content':
			case 'post_type':
            case 'author':
            case 'parent_post':
            case 'parent_author':
			case 'post_date':
			case 'actions': 
                return $item[$column_name];
            default:
                //return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
	
	function column_post_title($item) {
		
		global $view, $from_author, $paged;
		
		$u = sprintf( "?page=%s&post_id[]=%s&view=%s&paged=%s&from_author=%s&order=%s&orderby=%s&_nonce=%s", $_REQUEST['page'], $item['ID'], $this->search_params['view'], $this->search_params['paged'], $this->from_author, $this->search_params['order'], $this->search_params['orderby'], $this->search_params['_nonce'] );
		
		if($item['post_status'] == 'vp_open_list_pending'){
			$actions = array(
				'edit' => sprintf( '<a href="%s&action=%s">'.__( 'Approve', 'viralpress' ).'</a>', $u, 'approve'  ),
				'delete' => sprintf( '<a href="%s&action=%s">'.__( 'Delete', 'viralpress' ).'</a>', $u, 'delete'  )
			);
		}
		else {
			$actions = array(
				'delete' => sprintf( '<a href="%s&action=%s">'.__( 'Delete', 'viralpress' ).'</a>', $u, 'delete'  )
			);
		}
		return sprintf('%1$s %2$s', $item['post_title'], $this->row_actions($actions) );
	}
	
	function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'post_title'    => __( 'Title', 'viralpress' ),
			'post_content' => __( 'Description', 'viralpress' ),
			'post_type' => __( 'Post type', 'viralpress' ),
			'author'  => __( 'Author', 'viralpress' ),
			'parent_post'  => __( 'Submitted to', 'viralpress' ),
			'parent_author'  => __( 'Parent post author', 'viralpress' ),
			'post_date' => __( 'Date', 'viralpress' ),
			'actions' => __( 'Actions', 'viralpress' )
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
			'post_date' => array( 'post_date', false )
        );
        return $sortable_columns;
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ 'post_id',  //Let's simply repurpose the table's singular label ("plugin")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    function get_bulk_actions() {
        $actions = array(
            'approve'    => __( 'Approve', 'viralpress' ),
			'delete'    => __( 'Delete', 'viralpress' )
        );
        return $actions;
    }

    function process_bulk_action() {  
	
		global $wpdb;      
        
		if( !is_admin() ){
			echo '<div class="error"><p>Invalid action</p> <a href="javascript:void(0)" onclick="window.history.back()"><< Go back</a></div>';
			return false;	
		}
		
		if( !wp_verify_nonce( @$_REQUEST['_nonce'], 'vp-admin-action-'.get_current_user_id() ) ) {
			echo '<div class="error"><p>'.__( 'Request validation failed' , 'viralpress' ). '</p></div>';
			return false;	
		}
	
		$requested = 0;
		$success = 0;
		$failed = array();
				
		if( 'approve' === $this->current_action() ) {
			
			if(empty($_REQUEST['post_id'])){
				echo '<div class="error"><p>'.__( 'No post selected' , 'viralpress' ). '</p></div>';
				return false;	
			}
			
			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );
			$wpdb->query( 'SET autocommit = 0;' );
			
			foreach($_REQUEST['post_id'] as $id) {
				$requested++;
				$post_id = (int)$id;
				
				$a = approve_open_list( $post_id );
				
				if( is_wp_error( $a ) || $a === false ) $failed[] = $id;
				else $success++;
			}
			
			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );
			$wpdb->query( 'SET autocommit = 1;' );
			$wpdb->query( 'COMMIT;' );
			
			if(!empty($failed))echo '<div class="error"><p>Posts with ID '.implode(', ', $failed).' could not be saved</p></div><br/>';
			if(!empty($success))echo '<div class="updated"><p>'.$success.' out of '.$requested.' posts saved</p></div><br/>';
        }
		else if( 'delete' === $this->current_action() ) {
			
			if(empty($_REQUEST['post_id'])){
				echo '<div class="error"><p>'.__( 'No post selected' , 'viralpress' ). '</p></div>';
				return false;	
			}
			
			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );
			$wpdb->query( 'SET autocommit = 0;' );
			
			foreach($_REQUEST['post_id'] as $id) {
				$requested++;
				$post_id = (int)$id;
				
				$a = delete_open_list( $post_id );
				
				if( is_wp_error( $a ) || $a === false ) $failed[] = $id;
				else $success++;
			}

			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );
			$wpdb->query( 'SET autocommit = 1;' );
			$wpdb->query( 'COMMIT;' );
			
			if(!empty($failed))echo '<div class="error"><p>Posts with ID '.implode(', ', $failed).' could not be deleted</p></div><br/>';
			if(!empty($success))echo '<div class="updated"><p>'.$success.' out of '.$requested.' posts deleted</p></div><br/>';
        }       
    }


    function prepare_items() {
        $data = $this->data;
		$per_page = 10;
        $columns = $this->get_columns();
		$sortable = $this->get_sortable_columns();
        $hidden = array();
        $this->_column_headers = array($columns, $hidden, $sortable);                    
        $current_page = $this->get_pagenum();       
        $total_items = $this->total_items; 
		$author = $this->from_author;  
        $this->items = $data;        
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,                    
            'total_pages' => ceil($total_items/$per_page)
        ) );
    }
}
?>