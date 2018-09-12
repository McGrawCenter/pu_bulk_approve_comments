<?php 
    /*
    Plugin Name: Princeton Bulk Approve Comments
    Plugin URI: http://www.princeton.edu
    Description: Adds the ability to bulk approve all comments on selected Posts in the Posts page in the Dashboard. Place a checkmark next to the posts you would like to approve and select 'Approve comments' from the Bulk Actions dropdown menu.
    Author: Ben Johnston - benj@princeton.edu
    Version: 1.0
    */



add_filter( 'bulk_actions-edit-post', 'register_pu65786_my_bulk_actions' );
 
function register_pu65786_my_bulk_actions($bulk_actions) {
  $bulk_actions['approve_comments'] = __( 'Approve comments', 'approve_comments');
  return $bulk_actions;
}


add_filter( 'handle_bulk_actions-edit-post', 'pu65786_bulk_action_handler', 10, 3 );
 
function pu65786_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
print_r($post_ids);

  if ( $doaction !== 'approve_comments' ) {
    return $redirect_to;
  }

  foreach ( $post_ids as $post_id ) {
    $id = $post_id;
    echo $id;
    echo $post_id;
    if($comments = get_comments( array( 'post_id' => $post_id ) )) {
      foreach($comments as $comment) {
	wp_set_comment_status($comment->comment_ID, 'approve');
      }
    }
  }

  $redirect_to = add_query_arg( 'approved_comments', count( $post_ids ), $redirect_to );
  return $redirect_to;
}


add_action( 'admin_notices', 'pu65786_bulk_action_admin_notice' );
 
function pu65786_bulk_action_admin_notice() {
  if ( ! empty( $_REQUEST['approved_comments'] ) ) {
    echo '<div id="message" class="updated fade">Comments approved</div>';
  }
}
