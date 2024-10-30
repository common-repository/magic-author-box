<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Function to Add the author box to the end of your single post.
 */
if ( ! function_exists( 'mabox_author_box' ) ) {

	function mabox_author_box( $maboxhtml = null, $user_id = null) {

		//Get if need display
		$show = mabox_check_if_show();

		//Check if need showing
		if ( $show ) {

			global $post, $mabox_used_templates;

			ob_start();

			//Check if author template exists
			$mabox_author_id    = $user_id ? $user_id : $post->post_author;
			$author_template_id = MABOX_Helper::get_author_template_id( $mabox_author_id );
			if( !empty( $author_template_id ) ) {

				//prepare all details
				$mabox_options 	 = MABOX_Helper::get_template_option( $author_template_id, 'mabox_options' );
				$template        = MABOX_Helper::get_template();
				$show_post_author_box = apply_filters( 'mabox_check_if_show_post_author_box', true, $mabox_options );

				do_action( 'mabox_before_author_box', $mabox_options );

				if ( $show_post_author_box ) {
					echo '<div class="mabox-plus-item mabox-template-'. $author_template_id .'">';
					include( $template );
					echo '</div>';

					//Assigned template as used
					$mabox_used_templates[] = $author_template_id;
				}

				do_action( 'mabox_after_author_box', $mabox_options );
			}

			$mabox  = ob_get_clean();
			$return = $maboxhtml . $mabox;

			// Filter returning HTML of the Author Box
			$maboxhtml = apply_filters( 'mabox_return_html', $return, $mabox, $maboxhtml );

		}

		return $maboxhtml;
	}
}

//Function to return if need to show author box
function mabox_check_if_show() {

	$show = ( is_single() || is_page() || is_author() || is_archive() );

	/**
	 * Hook: mabox_check_if_show.
	 *
	 * @hooked MABOX_Public::check_if_show_archive - 10
	 */

	return apply_filters( 'mabox_check_if_show', $show );
}

//Display notice if user hasn't filled Info
add_action( 'admin_notices', 'mabox_user_profile_complete_notice' );
function mabox_user_profile_complete_notice() {

	//Get details
	$user_id         = get_current_user_id();
	$user            = get_userdata( $user_id );
	$user_descrition = $user->description;
	$user_roles      = $user->roles;
	$user_social 	 = get_user_meta( $user_id, 'mabox_social_links', true );
	$mabox_template 	 = get_user_meta( $user_id, 'mabox_profile_template', true );

	if( in_array( 'author', $user_roles ) ) {

		$profile_link = '<a href="'. get_edit_user_link() .'"> '. esc_html__( 'Edit profile', 'magic-author-box' ) .'</a>';;

		//Display message for Bio
		if ( empty( $user_descrition ) ) {
			echo '<div class="notice notice-info is-dismissible">
	            <p>'. __( 'Please complete your Biographical Info ', 'magic-author-box' ). $profile_link .'</p>
	        </div>';
		}

		//Display message for social links
		if ( empty( $user_social ) ) {
			echo '<div class="notice notice-info is-dismissible">
	            <p>'. __( 'Please enter your social profile ', 'magic-author-box' ). $profile_link .'</p>
	        </div>';
		}

		//Display message for author box template
		if ( empty( $mabox_template ) ) {
			echo '<div class="notice notice-info is-dismissible">
	            <p>'. __( 'Please choose your author box template ', 'magic-author-box' ). $profile_link .'</p>
	        </div>';
		}
	}
}