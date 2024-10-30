<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MABOX_Author_Box
 * @subpackage MABOX_Author_Box/public
 * @author     Weblineindia <info@weblineindia.com>
 */
class MABOX_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->options = get_option( 'mabox_options' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MABOX_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MABOX_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, MABOX_PLUGIN_URL .'css/author-box-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MABOX_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MABOX_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

	/**
	 * Check if display on archive page.
	 *
	 * @since    1.0.0
	 */
	public function check_if_show_archive() {

		if ( ! is_single() && ! is_author() && ! is_archive() ) {
			return false;
		}

		if ( isset( $this->options['hide_on_archive'] ) && 1 == $this->options['hide_on_archive'] && ! is_single() ) {
			return false;
		}

		return true;

	}

	public function shortcode( $atts ) {

		//Check if not display then return
		if ( ! mabox_check_if_show() ) {
			return;
		}

		global $mabox_used_templates;

		$defaults = array(
			'ids' => '',
		);

		$atts = wp_parse_args( $atts, $defaults );

		if ( '' != $atts['ids'] ) {


			if ( 'all' != $atts['ids'] ) {
				$ids = explode( ',', $atts['ids'] );
			} else {
				$ids = get_users( array( 'fields' => 'ID' ) );
			}

			ob_start();
			if ( ! empty( $ids ) ) {
				foreach ( $ids as $user_id ) {

					//Check if valid user id
					$user_id = absint($user_id);
					if( empty( $user_id ) ) continue;

					//Check if author template exists
					$author_template_id = MABOX_Helper::get_author_template_id( $user_id );
					if( !empty( $author_template_id ) ) {
						$mabox_options = MABOX_Helper::get_template_option( $author_template_id, 'mabox_options' );
						$template        = MABOX_Helper::get_template();
						$mabox_author_id = $user_id;
						echo '<div class="mabox-plus-item mabox-template-'. $author_template_id .'">';
						include( $template );
						echo '</div>';

						//Assigned template as used
						$mabox_used_templates[] = $author_template_id;
					}
				}
			}

			$html = ob_get_clean();

		} else {
			$html = mabox_author_box();
		}

		return $html;
	}

	public function inline_style() {

		//Check if not display then return
		if ( ! mabox_check_if_show() ) {
			return;
		}

		global $mabox_used_templates;

		//Check if author box used on pages
		if( !empty( $mabox_used_templates ) ) {

			//Make array unique values
			$mabox_used_templates = array_unique( $mabox_used_templates );

			//Get fonts bundle url
			$google_fonts_url = MABOX_Helper::get_author_template_fonts($mabox_used_templates);

			$style = '';
			foreach ( $mabox_used_templates as $used_template_id ) {
				$styles = MABOX_Helper::generate_inline_css($used_template_id);
				$style .= $styles['template'];
				$style .= $styles['customcss'];
			}

			//Adding dynamic style based on each post
			echo '<style type="text/css">'. $styles['common'] . $style .'</style>';

			//Check if fonts need to load
			if( !empty( $google_fonts_url ) ) {

				//Enqueue fonts
				wp_enqueue_style( 'mabox-font-css', $google_fonts_url, array(), MABOX_VERSION );
			}
		}
	}
}