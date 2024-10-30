<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Function to registered author box template
function mabox_custom_post_type_template() {

	$labels = array(
			'name'               => _x( 'Author Box Template', 'post type general name', 'magic-author-box' ),
			'singular_name'      => _x( 'Template', 'post type singular name', 'magic-author-box' ),
			'add_new'            => _x( 'Add New', 'Template', 'magic-author-box' ),
			'add_new_item'       => __( 'Add New Template', 'magic-author-box' ),
			'edit_item'          => __( 'Edit Template', 'magic-author-box' ),
			'new_item'           => __( 'New Template', 'magic-author-box' ),
			'all_items'          => __( 'All Template', 'magic-author-box' ),
			'view_item'          => __( 'View Template', 'magic-author-box' ),
			'search_items'       => __( 'Search Template', 'magic-author-box' ),
			'not_found'          => __( 'No Template found', 'magic-author-box' ),
			'not_found_in_trash' => __( 'No Template found in the Trash', 'magic-author-box' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Author Box', 'magic-author-box' )
	);

	$args = array(
			'labels'        => $labels,
			'description'   => __( 'Holds author box template data', 'magic-author-box' ),
			'public'        => false,
			'show_ui' 		=> true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-desktop',
			'supports'      => array( 'title', 'author' ),
			'has_archive'   => true,
			//'exclude_from_search' => true,
			'rewrite' => array(
				'slug' => 'mabox_template',
				'with_front' => false
			),
	);

	//Register author box template
	register_post_type( 'mabox_template', $args );
	flush_rewrite_rules();
}

//Action to call register author box template post
add_action( 'init', 'mabox_custom_post_type_template' );
?>