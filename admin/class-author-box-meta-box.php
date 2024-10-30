<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Class manage template meta fields
 */
class MABOX_Meta_Box {

	private $tab;
	private $options;
	private $sections;
	private $settings;

	function __construct() {

		$tmplt_id = !empty( $_GET['post'] ) ? absint($_GET['post']) : 0;

		$default_sections = array(
			'general-options'       => array(
				'label' => __( 'Settings', 'magic-author-box' ),
			),
			'appearance-options'    => array(
				'label' => __( 'Appearance', 'magic-author-box' ),
			),
			'color-options'         => array(
				'label' => __( 'Colors', 'magic-author-box' ),
			),
			'typography-options'    => array(
				'label' => __( 'Typography', 'magic-author-box' ),
			),
			'customcss-options'    => array(
				'label' => __( 'Custom CSS', 'magic-author-box' ),
			),
		);

		$settings = array(
			'general-options'       => array(
				'mabox_no_description' => array(
					'label'       => __( 'Hide the author box if author description is empty', 'magic-author-box' ),
					'description' => __( 'When turned ON, the author box will not appear for users without a description', 'magic-author-box' ),
					'type'        => 'toggle',
					'group'       => 'mabox_options',
				),
				'mabox_email'        => array(
					'label'       => __( 'Show author email', 'magic-author-box' ),
					'description' => __( 'When turned ON, the plugin will add an email option next to the social icons.', 'magic-author-box' ),
					'type'        => 'toggle',
					'group'       => 'mabox_options',
				),
				'mabox_link_target'  => array(
					'label'       => __( 'Open social icon links in a new tab', 'magic-author-box' ),
					'description' => __( 'When turned ON, the author’s social links will open in a new tab.', 'magic-author-box' ),
					'type'        => 'toggle',
					'group'       => 'mabox_options',
				),
				'mabox_hide_socials' => array(
					'label'       => __( 'Hide the social icons on author box', 'magic-author-box' ),
					'description' => __( 'When turned ON, the author’s social icons will be hidden.', 'magic-author-box' ),
					'type'        => 'toggle',
					'group'       => 'mabox_options',
				)
			),
			'appearance-options'    => array(
				'mabox_box_margin_top'         => array(
					'label'       => __( 'Top margin of author box', 'magic-author-box' ),
					'description' => __( 'Choose how much space to add above the author box', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 0,
						'max'       => 100,
						'increment' => 1,
					),
					'default'     => '0',
				),
				'mabox_box_margin_bottom'      => array(
					'label'       => __( 'Bottom margin of author box', 'magic-author-box' ),
					'description' => __( 'Choose how much space to add below the author box', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 0,
						'max'       => 100,
						'increment' => 1,
					),
					'default'     => '0',
				),
				'mabox_box_padding_top_bottom' => array(
					'label'       => __( 'Padding top and bottom of author box', 'magic-author-box' ),
					'description' => __( 'This controls the padding top & bottom of the author box', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 0,
						'max'       => 100,
						'increment' => 1,
					),
					'default'     => '0',
				),
				'mabox_box_padding_left_right' => array(
					'label'       => __( 'Padding left and right of author box', 'magic-author-box' ),
					'description' => __( 'This controls the padding left & right of the author box', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 0,
						'max'       => 100,
						'increment' => 1,
					),
					'default'     => '0',
				),
				'mabox_box_border_width'       => array(
					'label'       => __( 'Border Width', 'magic-author-box' ),
					'description' => __( 'This controls the border width of the author box', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 0,
						'max'       => 100,
						'increment' => 1,
					),
					'default'     => '1',
					'group'       => 'mabox_options',
				),
				'mabox_avatar_style'           => array(
					'label'       => __( 'Author avatar image style', 'magic-author-box' ),
					'description' => __( 'Change the shape of the author’s avatar image', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => array(
						0 => __( 'Square', 'magic-author-box' ),
						1 => __( 'Circle', 'magic-author-box' ),
					),
					'default'     => '0',
					'group'       => 'mabox_options',
				),
				'mabox_avatar_hover'           => array(
					'label'       => __( 'Rotate effect on author avatar hover', 'magic-author-box' ),
					'description' => __( 'When turned ON, this adds a rotate effect when hovering over the author\'s avatar', 'magic-author-box' ),
					'type'        => 'toggle',
					'group'       => 'mabox_options',
					'condition'   => 'mabox_avatar_style',
				),
				'mabox_web'                    => array(
					'label'       => __( 'Show author website', 'magic-author-box' ),
					'description' => __( 'When turned ON, the box will include the author\'s website', 'magic-author-box' ),
					'type'        => 'toggle',
					'group'       => 'mabox_options',
				),

				'mabox_web_target' => array(
					'label'       => __( 'Open author website link in a new tab', 'magic-author-box' ),
					'description' => __( 'If you check this the author\'s link will open in a new tab', 'magic-author-box' ),
					'type'        => 'toggle',
					'condition'   => 'mabox_web',
					'group'       => 'mabox_options',
				),
				'mabox_web_rel'    => array(
					'label'       => __( 'Add "nofollow" attribute on author website link', 'magic-author-box' ),
					'description' => __( 'Toggling this to ON will make the author website have the no-follow parameter added.', 'magic-author-box' ),
					'type'        => 'toggle',
					'condition'   => 'mabox_web',
					'group'       => 'mabox_options',
				),

				'mabox_web_position'    => array(
					'label'       => __( 'Author website position', 'magic-author-box' ),
					'description' => __( 'Select where you want to show the website ( left or right )', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => array(
						0 => __( 'Left', 'magic-author-box' ),
						1 => __( 'Right', 'magic-author-box' ),
					),
					'default'     => '0',
					'condition'   => 'mabox_web',
					'group'       => 'mabox_options',
				),
				'mabox_colored'         => array(
					'label'       => __( 'Social icons type', 'magic-author-box' ),
					'description' => __( 'Colored background adds a background behind the social icon symbol', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => array(
						0 => __( 'Symbols', 'magic-author-box' ),
						1 => __( 'Colored', 'magic-author-box' ),
					),
					'default'     => '0',
					'group'       => 'mabox_options',
				),
				'mabox_icons_style'     => array(
					'label'       => __( 'Social icons style', 'magic-author-box' ),
					'description' => __( 'Select the shape of social icons\' container', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => array(
						0 => __( 'Squares', 'magic-author-box' ),
						1 => __( 'Circle', 'magic-author-box' ),
					),
					'default'     => '0',
					'condition'   => 'mabox_colored',
					'group'       => 'mabox_options',
				),
				'mabox_social_hover'    => array(
					'label'       => __( 'Rotate effect on social icons hover (works only for circle icons)', 'magic-author-box' ),
					'description' => __( 'Add a rotate effect when you hover on social icons hover', 'magic-author-box' ),
					'type'        => 'toggle',
					'condition'   => 'mabox_colored',
					'group'       => 'mabox_options',
				),
				'mabox_box_long_shadow' => array(
					'label'       => __( 'Use flat long shadow effect', 'magic-author-box' ),
					'description' => __( 'Check this if you want a flat shadow for social icons', 'magic-author-box' ),
					'type'        => 'toggle',
					'condition'   => 'mabox_colored',
					'group'       => 'mabox_options',
				),
				'mabox_box_thin_border' => array(
					'label'       => __( 'Show a thin border on colored social icons', 'magic-author-box' ),
					'description' => __( 'Add a border to social icons container.', 'magic-author-box' ),
					'type'        => 'toggle',
					'condition'   => 'mabox_colored',
					'group'       => 'mabox_options',
				),
			),
			'color-options'         => array(
				'mabox_box_author_color'   => array(
					'label'       => __( 'Author name color', 'magic-author-box' ),
					'description' => __( 'Select the color for author\'s name text', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
				),
				'mabox_box_web_color'      => array(
					'label'       => __( 'Author website link color', 'magic-author-box' ),
					'description' => __( 'Select the color for author\'s website link', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
					'condition'   => 'mabox_web',
				),
				'mabox_box_border'         => array(
					'label'       => __( 'Border color', 'magic-author-box' ),
					'description' => __( 'Select the color for author box border', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
				),
				'mabox_box_icons_back'     => array(
					'label'       => __( 'Background color of social icons bar', 'magic-author-box' ),
					'description' => __( 'Select the color for the social icons bar background', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
				),
				'mabox_box_author_back'    => array(
					'label'       => __( 'Background color of author box', 'magic-author-box' ),
					'description' => __( 'Select the color for the author box background', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
				),
				'mabox_box_author_p_color' => array(
					'label'       => __( 'Color of author box paragraphs', 'magic-author-box' ),
					'description' => __( 'Select the color for the author box paragraphs', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
				),
				'mabox_box_author_a_color' => array(
					'label'       => __( 'Color of author box links', 'magic-author-box' ),
					'description' => __( 'Select the color for the author box links', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
				),
				'mabox_box_icons_color'    => array(
					'label'       => __( 'Social icons color (for symbols only)', 'magic-author-box' ),
					'description' => __( 'Select the color for social icons when using the symbols only social icon type', 'magic-author-box' ),
					'type'        => 'color',
					'group'       => 'mabox_options',
				),
			),
			'typography-options'    => array(
				'mabox_box_subset'    => array(
					'label'       => __( 'Google font characters subset', 'magic-author-box' ),
					'description' => __( 'Note - Some Google Fonts do not support particular subsets', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => MABOX_Helper::get_google_font_subsets(),
					'default'     => 'none',
				),
				'mabox_box_name_font' => array(
					'label'       => __( 'Author name font family', 'magic-author-box' ),
					'description' => __( 'Select the font family for the author\'s name', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => MABOX_Helper::get_google_fonts(),
					'default'     => 'None',
				),
				'mabox_box_web_font'  => array(
					'label'       => __( 'Author website font family', 'magic-author-box' ),
					'description' => __( 'Select the font family for the author\'s website', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => MABOX_Helper::get_google_fonts(),
					'default'     => 'None',
					'condition'   => 'mabox_web',
				),
				'mabox_box_desc_font' => array(
					'label'       => __( 'Author description font family', 'magic-author-box' ),
					'description' => __( 'Select the font family for the author\'s description', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => MABOX_Helper::get_google_fonts(),
					'default'     => 'None',
				),
				'mabox_box_name_size' => array(
					'label'       => __( 'Author name font size', 'magic-author-box' ),
					'description' => __( 'Default font size for author name is 18px.', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 10,
						'max'       => 50,
						'increment' => 1,
					),
					'default'     => '18',
				),
				'mabox_box_web_size'  => array(
					'label'       => __( 'Author website font size', 'magic-author-box' ),
					'description' => __( 'Default font size for author website is 14px.', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 10,
						'max'       => 50,
						'increment' => 1,
					),
					'default'     => '14',
					'condition'   => 'mabox_web',
				),
				'mabox_box_desc_size' => array(
					'label'       => __( 'Author description font size', 'magic-author-box' ),
					'description' => __( 'Default font size for author description is 14px.', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 10,
						'max'       => 50,
						'increment' => 1,
					),
					'default'     => '14',
				),
				'mabox_box_icon_size' => array(
					'label'       => __( 'Size of social icons', 'magic-author-box' ),
					'description' => __( 'Default font size for social icons is 18px.', 'magic-author-box' ),
					'type'        => 'slider',
					'choices'     => array(
						'min'       => 10,
						'max'       => 50,
						'increment' => 1,
					),
					'default'     => '18',
				),
				'mabox_desc_style'    => array(
					'label'       => __( 'Author description font style', 'magic-author-box' ),
					'description' => __( 'Select the font style for the author\'s description', 'magic-author-box' ),
					'type'        => 'select',
					'choices'     => array(
						0 => __( 'Normal', 'magic-author-box' ),
						1 => __( 'Italic', 'magic-author-box' ),
					),
					'default'     => '0',
					'group'       => 'mabox_options',
				),
			),
			'customcss-options'    => array(
				'mabox_box_customcss'    => array(
					'label'       => __( 'Custom CSS', 'magic-author-box' ),
					'description' => __( 'Add your Custom CSS.', 'magic-author-box' ),
					'type'        => 'textarea',
					'default'	  => '',
					'inline_description' => __( 'Use this wrapper class if you want to apply this CSS only on this template, Class: ', 'magic-author-box' ) .'<strong>".mabox-template-'. $tmplt_id .'"</strong>',
				)
			)
		);

		$this->settings = apply_filters( 'mabox_admin_settings', $settings );
		$this->sections = apply_filters( 'mabox_admin_sections', $default_sections );

		$this->get_all_options();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_data' ) );
	}

	private function get_all_options() {

		//Get post id
		$post_id = !empty( $_GET['post'] ) ? absint($_GET['post']) : 0;

		$this->options = MABOX_Helper::get_template_option( $post_id, 'mabox_options' );

		$mabox_box_margin_top = MABOX_Helper::get_template_option( $post_id, 'mabox_box_margin_top' );
		if ( $mabox_box_margin_top ) {
			$this->options['mabox_box_margin_top'] = $mabox_box_margin_top;
		}

		$mabox_box_margin_bottom = MABOX_Helper::get_template_option( $post_id, 'mabox_box_margin_bottom' );
		if ( $mabox_box_margin_bottom ) {
			$this->options['mabox_box_margin_bottom'] = $mabox_box_margin_bottom;
		}

		$mabox_box_icon_size = MABOX_Helper::get_template_option( $post_id, 'mabox_box_icon_size' );
		if ( $mabox_box_icon_size ) {
			$this->options['mabox_box_icon_size'] = $mabox_box_icon_size;
		}

		$mabox_box_author_font_size = MABOX_Helper::get_template_option( $post_id, 'mabox_box_name_size' );
		if ( $mabox_box_author_font_size ) {
			$this->options['mabox_box_name_size'] = $mabox_box_author_font_size;
		}

		$mabox_box_web_size = MABOX_Helper::get_template_option( $post_id, 'mabox_box_web_size' );
		if ( $mabox_box_web_size ) {
			$this->options['mabox_box_web_size'] = $mabox_box_web_size;
		}

		$mabox_box_name_font = MABOX_Helper::get_template_option( $post_id, 'mabox_box_name_font' );
		if ( $mabox_box_name_font ) {
			$this->options['mabox_box_name_font'] = $mabox_box_name_font;
		}

		$mabox_box_subset = MABOX_Helper::get_template_option( $post_id, 'mabox_box_subset' );
		if ( $mabox_box_subset ) {
			$this->options['mabox_box_subset'] = $mabox_box_subset;
		}

		$mabox_box_desc_font = MABOX_Helper::get_template_option( $post_id, 'mabox_box_desc_font' );
		if ( $mabox_box_desc_font ) {
			$this->options['mabox_box_desc_font'] = $mabox_box_desc_font;
		}

		$mabox_box_web_font = MABOX_Helper::get_template_option( $post_id, 'mabox_box_web_font' );
		if ( $mabox_box_web_font ) {
			$this->options['mabox_box_web_font'] = $mabox_box_web_font;
		}

		$mabox_box_desc_size = MABOX_Helper::get_template_option( $post_id, 'mabox_box_desc_size' );
		if ( $mabox_box_desc_size ) {
			$this->options['mabox_box_desc_size'] = $mabox_box_desc_size;
		}

		$this->options['mabox_box_padding_top_bottom'] = MABOX_Helper::get_template_option( $post_id, 'mabox_box_padding_top_bottom' );
		$this->options['mabox_box_padding_left_right'] = MABOX_Helper::get_template_option( $post_id, 'mabox_box_padding_left_right' );

		$mabox_box_customcss = MABOX_Helper::get_template_option( $post_id, 'mabox_box_customcss' );
		if ( $mabox_box_customcss ) {
			$this->options['mabox_box_customcss'] = $mabox_box_customcss;
		}
	}

	public function add_meta_boxes() {

		//Get setting slug
		$template_screen = 'mabox_template';

		//Template option meta box
		add_meta_box( 'mabox-template-metabox', __( 'Author Box Options', 'magic-author-box' ), array( $this, 'mabox_template_metabox_callback' ), $template_screen );

		//Profile options meta box
		add_meta_box( 'mabox-template-side-metabox', __( 'Profile Options', 'magic-author-box' ), array( $this, 'mabox_template_side_metabox_callback' ), $template_screen, 'side' );
	}

	public function mabox_template_metabox_callback() {

		//Get post id
		$post_id = !empty( $_GET['post'] ) ? absint($_GET['post']) : '';
		?>
        <div class="mabox-preview-wrap">
            <div class="mabox-preview mabox-template-<?php echo $post_id;?>">
				<?php do_action( 'mabox_admin_preview', $post_id ) ?>
            </div>

            <h2 class="epfw-tab-wrapper nav-tab-wrapper wp-clearfix">
				<?php foreach ( $this->sections as $id => $section ) { ?>
					<?php
					$class = 'epfw-tab nav-tab';

					if ( isset( $section['link'] ) ) {
						$url   = $section['link'];
						$class .= ' epfw-tab-link';
					} else {
						$url = '#' . $id;
					}

					if ( isset( $section['class'] ) ) {
						$class .= ' ' . $section['class'];
					}

					?>
                    <a class="<?php echo esc_attr( $class ); ?>"
                       href="<?php echo esc_url( $url ); ?>"><?php echo wp_kses_post( $section['label'] ); ?></a>
				<?php } ?>
            </h2>

            <div id="mabox-container">
				<?php

				//wp_nonce_field( 'mabox-plugin-settings', 'mabox_plugin_settings_page' );

				foreach ( $this->settings as $tab_name => $fields ) {
					echo '<div class="epfw-turn-into-tab" id="' . esc_attr( $tab_name ) . '-tab">';
					echo '<table class="form-table mabox-table">';
					foreach ( $fields as $field_name => $field ) {
						$this->generate_meta_field( $field_name, $field );
					}
					echo '</table>';
					echo '</div>';
				}
				?>
            </div>
        </div>
		<?php
	}

	public function mabox_template_side_metabox_callback() {

		//Get edit user link
		$user_edit_link = get_edit_user_link();
		?>
		<div class="mabox-profile-topbar">
			<ul>
				<li>
					<a href="<?php echo $user_edit_link; ?>#your-profile" class="button button-secondary" target="_blank">
						<i class="dashicons dashicons-edit"></i><?php echo esc_html__( 'Edit Author Profile', 'magic-author-box' ); ?>
            		</a>
            	</li>
            	<li>
            		<a href="<?php echo $user_edit_link; ?>#mabox-custom-profile-image" class="button button-secondary" target="_blank">
            			<i class="dashicons dashicons-admin-users"></i><?php echo esc_html__( 'Change Author Avatar', 'magic-author-box' ); ?>
            		</a>
            	</li>
            	<li>
            		<a href="<?php echo $user_edit_link; ?>#mabox-social-table" class="button button-secondary" target="_blank">
            			<i class="dashicons dashicons-networking"></i><?php echo esc_html__( 'Add/Edit Social Media Icons', 'magic-author-box' ); ?>
            		</a>
            	</li>
            	<li>
            		<a href="<?php echo $user_edit_link; ?>#mabox-custom-profile-fields" class="button button-secondary" target="_blank">
            			<i class="dashicons dashicons-art"></i><?php echo esc_html__( 'Choose Your Author Box', 'magic-author-box' ); ?>
            		</a>
            	</li>
			</ul>
        </div><!--/.mabox-profile-topbar-->
		<?php
	}

	public function save_meta_data( $post_id ) {

	  	if ( !current_user_can( 'edit_post', $post_id )) return;

		if ( !empty( $_POST['mabox-settings'] ) ) {
			$settings = isset( $_POST['mabox-settings'] ) ? $_POST['mabox-settings'] : array();
			$groups   = array();

			foreach ( $this->settings as $tab => $setting_fields ) {
				foreach ( $setting_fields as $key => $setting ) {
					if ( isset( $setting['group'] ) ) {

						if ( ! isset( $groups[ $setting['group'] ] ) ) {
							$group_option = get_post_meta( $post_id, $setting['group'], true );
							$groups[ $setting['group'] ] = !empty( $group_option ) ? $group_option : array();
						}

						if ( ! isset( $settings[ $setting['group'] ][ $key ] ) && isset( $groups[ $setting['group'] ][ $key ] ) ) {
							$groups[ $setting['group'] ][ $key ] = '0';
						}

						if ( isset( $settings[ $setting['group'] ][ $key ] ) ) {
							$groups[ $setting['group'] ][ $key ] = $this->sanitize_fields( $setting, $settings[ $setting['group'] ][ $key ] );
						}
					} else {

						$current_value = get_post_meta( $post_id, $key, true );
						if ( isset( $settings[ $key ] ) ) {
							$value = $this->sanitize_fields( $setting, $settings[ $key ] );
							if ( $current_value != $value ) {
								update_post_meta( $post_id, $key, $value );
							}
						}
					}
				}
			}

			//Update all options
			foreach ( $groups as $key => $values ) {
				update_post_meta( $post_id, $key, $values );
			}

			//Allow third party action
			do_action( 'mabox_save_meta_data' );

			MABOX_Helper::reset_options();
			$this->get_all_options();
		}
	}

	private function sanitize_fields( $setting, $value ) {
		$default_sanitizers = array(
			'toggle' => 'absint',
			'slider' => 'absint',
			'color'  => 'sanitize_hex_color',
		);

		if ( isset( $setting['sanitize'] ) && function_exists( $setting['sanitize'] ) ) {
			$value = call_user_func( $setting['sanitize'], $value );
		} elseif ( isset( $default_sanitizers[ $setting['type'] ] ) && function_exists( $default_sanitizers[ $setting['type'] ] ) ) {
			$value = call_user_func( $default_sanitizers[ $setting['type'] ], $value );
		} elseif ( 'select' == $setting['type'] ) {
			if ( isset( $setting['choices'][ $value ] ) ) {
				$value = $value;
			} else {
				$value = $setting['default'];
			}
		} elseif ( 'multiplecheckbox' == $setting['type'] ) {
			foreach ( $value as $key ) {
				if ( ! isset( $setting['choices'][ $key ] ) ) {
					unset( $value[ $key ] );
				}
			}
		} else {
			$value = sanitize_text_field( $value );
		}

		return $value;

	}

	private function generate_meta_field( $field_name, $field ) {
		$class = '';
		$name  = 'mabox-settings[';
		if ( isset( $field['group'] ) ) {
			$name .= $field['group'] . '][' . esc_attr( $field_name ) . ']';
		} else {
			$name .= esc_attr( $field_name ) . ']';
		}
		if ( isset( $field['condition'] ) ) {
			$class = 'show_if_' . $field['condition'] . ' hide';
		}
		echo '<tr valign="top" class="' . esc_attr( $class ) . '">';
		echo '<th scope="row">';
		if ( isset( $field['description'] ) ) {
			echo '<span class="epfw-tooltip tooltip-right" data-tooltip="' . esc_html( $field['description'] ) . '"><i class="dashicons dashicons-info"></i></span>';
		}
		echo esc_html( $field['label'] );
		echo '</th>';
		echo '<td>';
		switch ( $field['type'] ) {
			case 'toggle':
				$value = isset( $this->options[ $field_name ] ) ? $this->options[ $field_name ] : '0';
				echo '<div class="checkbox_switch">';
				echo '<div class="onoffswitch">';
				echo '<input type="checkbox" id="' . esc_attr( $field_name ) . '" name="' . esc_attr( $name ) . '" class="onoffswitch-checkbox maboxfield" ' . checked( 1, $value, false ) . ' value="1">';
				echo '<label class="onoffswitch-label" for="' . esc_attr( $field_name ) . '"></label>';
				echo '</div>';
				echo '</div>';
				break;
			case 'select':
				$value = isset( $this->options[ $field_name ] ) ? $this->options[ $field_name ] : $field['default'];
				echo '<select id="' . esc_attr( $field_name ) . '" name="' . esc_attr( $name ) . '" class="maboxfield">';
				foreach ( $field['choices'] as $key => $choice ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value, false ) . '>' . esc_html( $choice ) . '</option>';
				}
				echo '</select>';
				break;
			case 'textarea':
				$value = isset( $this->options[ $field_name ] ) ? $this->options[ $field_name ] : $field['default'];
				echo '<textarea rows="3" cols="50"  id="' . esc_attr( $field_name ) . '" value="' . esc_attr( $value ) . '" name="' . esc_attr( $name ) . '" class="maboxfield">' . $value . '</textarea>';
				if( !empty( $field['inline_description'] ) ) {
					echo '<p class="description">'. $field['inline_description'] .'</p>';
				}
				break;
			case 'readonly':
				echo '<textarea clas="regular-text" rows="3" cols="50" onclick="this.focus();this.select();" readonly="readonly">' . esc_attr( $field['value'] ) . '</textarea>';
				break;
			case 'slider':
				$value = isset( $this->options[ $field_name ] ) ? $this->options[ $field_name ] : $field['default'];
				echo '<div class="mabox-slider-container slider-container">';
				echo '<input type="text" id="' . esc_attr( $field_name ) . '" class="maboxfield" name="' . esc_attr( $name ) . '" data-min="' . absint( $field['choices']['min'] ) . '" data-max="' . absint( $field['choices']['max'] ) . '" data-step="' . absint( $field['choices']['increment'] ) . '" value="' . esc_attr( $value ) . 'px">';
				echo '<div class="mabox-slider"></div>';
				echo '</div>';
				break;
			case 'color':
				$value = isset( $this->options[ $field_name ] ) ? $this->options[ $field_name ] : '';
				echo '<div class="mabox-colorpicker">';
				echo '<input id="' . esc_attr( $field_name ) . '" class="maboxfield mabox-color" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
				echo '</div>';
				break;
			case 'multiplecheckbox':
				echo '<div class="mabox-multicheckbox">';
				if ( ! isset( $field['choices'] ) && isset( $field['handle'] ) && is_array( $field['handle'] ) ) {
					if ( class_exists( $field['handle'][0] ) ) {
						$class            = $field['handle'][0];
						$method           = $field['handle'][1];
						$field['choices'] = $class::$method();
					}
				}

				if ( ! isset( $field['default'] ) ) {
					$field['default'] = array_keys( $field['choices'] );
				}

				$values = isset( $this->options[ $field_name ] ) ? $this->options[ $field_name ] : $field['default'];

				if ( is_array( $values ) ) {
					$checked = $values;
				} else {
					$checked = array();
				}

				foreach ( $field['choices'] as $key => $choice ) {
					echo '<div>';
					echo '<input id="' . $key . '-' . $field_name . '" type="checkbox" value="' . $key . '" ' . checked( 1, in_array( $key, $checked ), false ) . ' name="' . esc_attr( $name ) . '[]"><label for="' . $key . '-' . $field_name . '" class="checkbox-label">' . $choice . '</label>';
					echo '</div>';
				}
				echo '</div>';
				break;
			case 'radio-group':
				echo '<div class="mabox-radio-group">';
				echo '<fieldset>';
				foreach ( $field['choices'] as $key => $choice ) {
					echo '<input type="radio" id="' . esc_attr( $field_name . '_' . $key ) . '" name="' . esc_attr( $name ) . '" class="maboxfield" ' . checked( $key, $this->options[ $field_name ], false ) . ' value="' . esc_attr( $key ) . '">';
					echo '<label for="' . esc_attr( $field_name . '_' . $key ) . '">' . esc_attr( $choice ) . '</label>';
				}
				echo '</fieldset>';
				echo '</div>';
				break;
			default:
				do_action( "mabox_field_{$field['type']}_output", $field_name, $field );
				break;
		}
		echo '</td>';
		echo '</tr>';
	}
}

new MABOX_Meta_Box();