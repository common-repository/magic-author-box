<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    MABOX_Author_Box
 * @subpackage MABOX_Author_Box/includes
 * @author     Weblineindia <info@weblineindia.com>
 */
class MABOX_Activator {

	/**
	 * Pre-built templates
	 *
	 * Insert pre-built template on plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		//Templates arguments for predefined template
		$default_templates = array(
			0 => array(
				'id' 	=> 'maboxtmpl-1',
				'name' 	=> 'Simple Template',
				'mabox_box_subset' 	=> 'none',
				'mabox_box_name_font' => 'Baumans',
				'mabox_box_web_font' 	=> '',
				'mabox_box_desc_font' => 'Baumans',
				'mabox_box_name_size' => 22,
				'mabox_box_web_size' 	=> 14,
				'mabox_box_desc_size' => 14,
				'mabox_box_icon_size' => 16,
				'mabox_box_margin_top' 	=> 10,
				'mabox_box_margin_bottom' => 10,
				'mabox_box_customcss' => '',
				'options' => array(
					'mabox_box_border_width' => 4,
					'mabox_avatar_style' 	=> 1,
					'mabox_web_position' 	=> 1,
					'mabox_colored' 		=> 0,
					'mabox_icons_style' 	=> 1,
					'mabox_box_author_color' => '#4c5ead',
					'mabox_box_web_color' 	=> '',
					'mabox_box_border' 		=> '#f7f7f7',
					'mabox_box_icons_back'	=> '',
					'mabox_box_author_back' 	=> '',
					'mabox_box_author_p_color'=> '#7a7a7a',
					'mabox_box_author_a_color'=> '',
					'mabox_box_icons_color' 	=> '',
					'mabox_desc_style'	=> 0,
					'mabox_email' 		=> 1,
					'mabox_social_hover' 	=> 0,
					'mabox_web' 	=> 1,
					'mabox_web_target' 	=> 1,
					'mabox_box_long_shadow' => 0,
					'mabox_box_thin_border' => 0,
				)
			),
			1 => array(
				'id' 	=> 'maboxtmpl-2',
				'name' 	=> 'Classic Template',
				'mabox_box_subset' 	=> 'none',
				'mabox_box_name_font' => 'Yesteryear',
				'mabox_box_web_font' 	=> 'Cabin Condensed',
				'mabox_box_desc_font' => 'Cabin Condensed',
				'mabox_box_name_size' => 26,
				'mabox_box_web_size' 	=> 16,
				'mabox_box_desc_size' => 14,
				'mabox_box_icon_size' => 18,
				'mabox_box_margin_top' 	=> 10,
				'mabox_box_margin_bottom' => 10,
				'mabox_box_customcss' => '',
				'options' => array(
					'mabox_box_border_width' => 1,
					'mabox_avatar_style' 	=> 1,
					'mabox_web_position' 	=> 1,
					'mabox_colored' 		=> 0,
					'mabox_icons_style' 	=> 1,
					'mabox_box_author_color' => '#232323',
					'mabox_box_web_color' 	=> '#232323',
					'mabox_box_border' 		=> '#757575',
					'mabox_box_icons_back'	=> '#757575',
					'mabox_box_author_back' 	=> '',
					'mabox_box_author_p_color'=> '#232323',
					'mabox_box_author_a_color'=> '',
					'mabox_box_icons_color' 	=> '#ededed',
					'mabox_desc_style'	=> 0,
					'mabox_email' 		=> 1,
					'mabox_social_hover' 	=> 0,
					'mabox_web' 	=> 1,
					'mabox_web_target' 	=> 1,
					'mabox_box_long_shadow' => 1,
					'mabox_box_thin_border' => 0,
				)
			),
			2 => array(
				'id' 	=> 'maboxtmpl-3',
				'name' 	=> 'Modern Template',
				'mabox_box_subset' 	=> 'none',
				'mabox_box_name_font' => 'Damion',
				'mabox_box_web_font' 	=> 'Courgette',
				'mabox_box_desc_font' => 'Courgette',
				'mabox_box_name_size' => 28,
				'mabox_box_web_size' 	=> 18,
				'mabox_box_desc_size' => 14,
				'mabox_box_icon_size' => 14,
				'mabox_box_margin_top' 	=> 10,
				'mabox_box_margin_bottom' => 10,
				'mabox_box_customcss' => '',
				'options' => array(
					'mabox_box_border_width' => 2,
					'mabox_avatar_style' 	=> 0,
					'mabox_web_position' 	=> 1,
					'mabox_colored' 		=> 1,
					'mabox_icons_style' 	=> 1,
					'mabox_box_author_color' => '#383838',
					'mabox_box_web_color' 	=> '#383838',
					'mabox_box_border' 		=> '#eeeeee',
					'mabox_box_icons_back'	=> '',
					'mabox_box_author_back' 	=> '#fcfcfc',
					'mabox_box_author_p_color'=> '#4c4c4c',
					'mabox_box_author_a_color'=> '',
					'mabox_box_icons_color' 	=> '#0c0c0c',
					'mabox_desc_style'	=> 0,
					'mabox_email' 		=> 1,
					'mabox_social_hover' 	=> 1,
					'mabox_web' 	=> 1,
					'mabox_web_target' 	  => 1,
					'mabox_box_long_shadow' => 1,
					'mabox_box_thin_border' => 0,
				)
			)
		);

		//Loop for template data
		foreach ( $default_templates as $template_data ) {

			$template_exists = get_posts(array(
				'post_type'  => 'mabox_template',
				'meta_key' 	 => '_mabox_template_id',
				'meta_value' => $template_data['id']
			));

			//Check if template already exists
			if( !empty( $template_exists[0] ) ) continue;

			//Args for insert template
			$args = array(
				'post_type'   => 'mabox_template',
				'post_title'  => $template_data['name'],
				'post_status' => 'publish',
			);

			// Insert the post into the database
			$inserted = wp_insert_post( $args );

			//Check if template inserted
			if( $inserted ) {

				//Update initial options
				update_post_meta( $inserted, 'mabox_box_subset', $template_data['mabox_box_subset'] );
				update_post_meta( $inserted, 'mabox_box_name_font', $template_data['mabox_box_name_font'] );
				update_post_meta( $inserted, 'mabox_box_web_font', $template_data['mabox_box_web_font'] );
				update_post_meta( $inserted, 'mabox_box_desc_font', $template_data['mabox_box_desc_font'] );
				update_post_meta( $inserted, 'mabox_box_name_size', $template_data['mabox_box_name_size'] );
				update_post_meta( $inserted, 'mabox_box_web_size', $template_data['mabox_box_web_size'] );
				update_post_meta( $inserted, 'mabox_box_desc_size', $template_data['mabox_box_desc_size'] );
				update_post_meta( $inserted, 'mabox_box_icon_size', $template_data['mabox_box_icon_size'] );
				update_post_meta( $inserted, 'mabox_box_margin_top', $template_data['mabox_box_margin_top'] );
				update_post_meta( $inserted, 'mabox_box_margin_bottom', $template_data['mabox_box_margin_bottom'] );

				//Update all other option
				update_post_meta( $inserted, 'mabox_options', array(
					'mabox_box_border_width' => $template_data['options']['mabox_box_border_width'],
					'mabox_avatar_style' 	=> $template_data['options']['mabox_avatar_style'],
					'mabox_web_position' 	=> $template_data['options']['mabox_web_position'],
					'mabox_colored' 		=> $template_data['options']['mabox_colored'],
					'mabox_icons_style' 	=> $template_data['options']['mabox_icons_style'],
					'mabox_box_author_color'  => $template_data['options']['mabox_box_author_color'],
					'mabox_box_web_color' 	=> $template_data['options']['mabox_box_web_color'],
					'mabox_box_border' 		=> $template_data['options']['mabox_box_border'],
					'mabox_box_icons_back'	=> $template_data['options']['mabox_box_icons_back'],
					'mabox_box_author_back' 	=> $template_data['options']['mabox_box_author_back'],
					'mabox_box_author_p_color'=> $template_data['options']['mabox_box_author_p_color'],
					'mabox_box_author_a_color'=> $template_data['options']['mabox_box_author_a_color'],
					'mabox_box_icons_color' 	=> $template_data['options']['mabox_box_icons_color'],
					'mabox_desc_style'	=> $template_data['options']['mabox_desc_style'],
					'mabox_email' 		=> $template_data['options']['mabox_email'],
					'mabox_social_hover' 	=> $template_data['options']['mabox_social_hover'],
					'mabox_web' 	=> $template_data['options']['mabox_web'],
					'mabox_web_target' 	  => $template_data['options']['mabox_web_target'],
					'mabox_box_long_shadow' => $template_data['options']['mabox_box_long_shadow'],
					'mabox_box_thin_border' => $template_data['options']['mabox_box_thin_border'],
				));

				//Update template as predefined
				update_post_meta( $inserted, '_mabox_predefined_template', true );
				update_post_meta( $inserted, '_mabox_template_id', $template_data['id'] );
			}
		}

		update_option(MABOX_OPTION_NAME, MABOX_VERSION);
	}
}