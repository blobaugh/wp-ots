<?php

add_action( 'init', 'maybe_create_secret' );
function maybe_create_secret() {
	
	if ( ! isset( $_POST['create'] ) || 'hello world' != $_POST['hello'] || empty( $_POST['secret'] ) ) {
		return; // Nothing to create
	}
 
	$data = [];

	/*
	 * Lets create us some datas!
	 *
	 * Expiration defaults to 3 days if not selected
	 * Password defaults to empty
	 */
	$data['expires'] = ( isset( $_POST['expires'] ) && is_numeric( $_POST['expires'] ) )? $_POST['expires']: 259200;

	$data['password'] = ( isset( $_POST['password'] ) )? sanitize_text_field( $_POST['password'] ): '';

	$data['secret'] = esc_html( $_POST['secret'] );
	
	$secret = create_secret( $data );
	var_dump( $secret );
	echo ( get_permalink( $secret ) );
}


/**
 * Creates a secret entry in the database.
 *
 * Data should be *clean* when this function is called.
 *
 * Param accepts the following fields:
 * - expires - As seconds to be calculated for future
 * - password - Can be empty
 * - secret - The secret text to share
 *
 * @param array $data
 */
function create_secret( $data ) {

	// Calculate future expiration time
//	$post_data['meta_input'] = [ 'expires' => current_time( 'U' )  ];
	$post_data['meta_input'] = [ 'expires' => current_time( 'U' ) + $data['expires'] ];

	// Set post content
	$post_data['post_content'] = $data['secret'];

	// Set post password
	$post_data['post_password'] = $data['password'];

	// Post defaults
	$post_data['post_type'] = 'secretz';
	$post_data['post_title'] = bin2hex ( random_bytes( 8 ) );
	$post_data['comment_status'] = 'closed';
	$post_data['ping_status'] = 'closed';	
	$post_data['post_status'] = 'publish';

	$post = wp_insert_post( $post_data );

	return $post;
}

add_action( 'init', 'remove_expired_secrets' );

/**
 * Remove the expired secrets from the database.
 *
 */
function remove_expired_secrets() {
	global $wpdb;

	$now = current_time( 'U' );

	$sql = "SELECT post_id FROM `" . $wpdb->postmeta . "` WHERE meta_key='expires' AND meta_value <= '" . $now . "'";

	$expired = $wpdb->get_results( $sql );
	
	foreach( $expired AS $post ) {
		wp_delete_post( $post->post_id );
	}
}

add_action( 'init', 'register_secret_posttype' );
function register_secret_posttype() {
	register_post_type( 'secretz', [
		'public'		=> true,
		'publicly_queryable'	=> true,
		//'show_ui'		=> true,
		'rewrite'		=> [ 'with_front' => false, 'feeds' => false, 'slug' => 'secret' ]
	] );

}
