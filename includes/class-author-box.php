<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.weblineindia.com
 * @since      1.0.0
 *
 * @package    MABOX_Author_Box
 * @subpackage MABOX_Author_Box/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MABOX_Author_Box
 * @subpackage MABOX_Author_Box/includes
 * @author     Weblineindia <info@weblineindia.com>
 */
class MABOX_Author_Box {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MABOX_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $settings;

	protected $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'magic-author-box';
		$this->version = MABOX_VERSION;

		//Get global option
		$this->options = get_option( 'mabox_options' );

		$this->load_dependencies();
		$this->set_locale();

		//Check if is admin
		if( is_admin() ) {
			$this->define_admin_hooks();
		}

		//Check if author box enabled
		if( !empty( $this->options['enable_ab'] ) ) {

			//Filter to change WordPresss default avatar
			add_filter( 'get_avatar', array( $this, 'replace_gravatar_image' ), 10, 6 );

			//Action to load public hooks
			$this->define_public_hooks();
		}

		// Future use for Upgrade of plugin
        /*if(version_compare(get_option(MABOX_OPTION_NAME), '1.0.2') == '-1') {
            $this->upgradeTo102();
        }*/
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MABOX_Loader. Orchestrates the hooks of the plugin.
	 * - MABOX_i18n. Defines internationalization functionality.
	 * - MABOX_Admin. Defines all hooks for the admin area.
	 * - MABOX_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-author-box-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-author-box-i18n.php';

		/**
		 * The file responsible for defining all functions required for plugin open functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/author-box-functions.php';

		/**
		 * The file responsible for defining all actions that occur in the custom post type for author box template.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/author-box-post-types.php';

		/**
		 * The class responsible for defining all actions that occur in the admin user profile area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-author-box-helper.php';

		/**
		 * The class responsible for defining all actions that occur in the template social area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-author-box-social.php';

		//Check if is admin
		if( is_admin() ) {

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-author-box-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the template preview area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-author-box-previewer.php';

			/**
			 * The class responsible for defining all actions that occur in the template meta box area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-author-box-meta-box.php';

			/**
			 * The class responsible for defining all actions that occur in the admin user profile area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-author-box-user-profile.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-author-box-public.php';

		$this->loader = new MABOX_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MABOX_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MABOX_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new MABOX_Admin( $this->get_plugin_name(), $this->get_version() );

		//Added preload hooks
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menus' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'add_settings_link', 10, 2);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function define_public_hooks() {

		global $mabox_used_templates;

		$plugin_public = new MABOX_Public( $this->get_plugin_name(), $this->get_version() );

		//Load public scripts
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//add_action( 'wp_enqueue_scripts', array( $this, 'mabox_author_box_style' ), 10 );
		add_shortcode( 'mabox-author-box', array( $plugin_public, 'shortcode' ) );
		//add_filter( 'mabox_hide_social_icons', array( $plugin_public, 'show_social_media_icons' ), 10, 2 );
		add_filter( 'mabox_check_if_show', array( $plugin_public, 'check_if_show_archive' ), 10 );

		//Check if manual insert not active
		if ( empty( $this->options['manualinsert_ab'] ) ) {
			add_filter( 'the_content', 'mabox_author_box' );
		}

		//Load all inline styles in footer
		add_action( 'wp_footer', array( $plugin_public, 'inline_style' ), 13 );
	}

	/**
	 * See this: https://codex.wordpress.org/Plugin_API/Filter_Reference/get_avatar
	 *
	 * function to overwrite WordPress's get_avatar function
	 *
	 * @param [type] $avatar
	 * @param [type] $id_or_email
	 * @param [type] $size
	 * @param [type] $default
	 * @param [type] $alt
	 * @param [type] $args
	 *
	 * @return void
	 */
	public function replace_gravatar_image( $avatar, $id_or_email, $size, $default, $alt, $args = array() ) {

		// Process the user identifier.
		$user = false;
		if ( is_numeric( $id_or_email ) ) {
			$user = get_user_by( 'id', absint( $id_or_email ) );
		} elseif ( is_string( $id_or_email ) ) {

			$user = get_user_by( 'email', $id_or_email );

		} elseif ( $id_or_email instanceof WP_User ) {
			// User Object
			$user = $id_or_email;
		} elseif ( $id_or_email instanceof WP_Post ) {
			// Post Object
			$user = get_user_by( 'id', (int) $id_or_email->post_author );
		} elseif ( $id_or_email instanceof WP_Comment ) {

			if ( ! empty( $id_or_email->user_id ) ) {
				$user = get_user_by( 'id', (int) $id_or_email->user_id );
			}
		}

		if ( ! $user || is_wp_error( $user ) ) {
			return $avatar;
		}

		$custom_profile_image = get_user_meta( $user->ID, 'mabox-profile-image', true );
		$class                = array( 'avatar', 'avatar-' . (int) $args['size'], 'photo' );

		if ( ! $args['found_avatar'] || $args['force_default'] ) {
			$class[] = 'avatar-default';
		}

		if ( $args['class'] ) {
			if ( is_array( $args['class'] ) ) {
				$class = array_merge( $class, $args['class'] );
			} else {
				$class[] = $args['class'];
			}
		}

		$class[] = 'mabox-custom-avatar';

		if ( '' !== $custom_profile_image && true !== $args['force_default'] ) {

			$avatar = sprintf(
				"<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>",
				esc_attr( $args['alt'] ),
				esc_url( $custom_profile_image ),
				esc_url( $custom_profile_image ) . ' 2x',
				esc_attr( join( ' ', $class ) ),
				(int) $args['height'],
				(int) $args['width'],
				$args['extra_attr']
			);
		}

		return $avatar;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MABOX_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Store plugin version in options.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function update_version($version = '') {
    	
    	if($version == '') {
    		$version = MABOX_VERSION;
    	}

    	update_option(MABOX_OPTION_NAME, $version);
    }


    /**
	 * Upgrade plugin to 1.0.2.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    public function upgradeTo102() {
    	$this->update_version('1.0.2');
    }
}