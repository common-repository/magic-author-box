<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MABOX_Author_Box
 * @subpackage MABOX_Author_Box/admin
 * @author     Weblineindia <info@weblineindia.com>
 */
class MABOX_Admin {

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

	/**
	 * Other important variables
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private $plugin_options_key = 'mabox-settings';
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		//Load options
	 	$this->options = get_option( 'mabox_options' );

		//Action to template restriction
		add_action( 'admin_init', array( $this, 'access_restriction' ), 10 );

	 	//Action to load settings
		add_action( 'admin_init', array( $this, 'settings_init' ) );

		// Admin footer text.
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
	}

	/**
	 * Handle role restriction
	 *
	 * @since    1.0.0
	 */
	function access_restriction() {

		global $pagenow, $current_user;

		//Check if user is not admin
		if( !empty( $current_user->roles ) && ! in_array( 'administrator', $current_user->roles ) ) {

			//Check if page author template page
			$restricted = false;
			if( in_array( $pagenow, array( 'edit.php', 'post-new.php' ) ) && !empty( $_GET['post_type'] )
				&& sanitize_text_field($_GET['post_type']) == 'mabox_template' ) {
				$restricted = true;
			}

			//Check if page author template edit page
			if( $pagenow == 'post.php' && !empty( $_GET['post'] ) && get_post_type( absint($_GET['post']) ) == 'mabox_template' ) {
				$restricted = true;
			}

			//Check if need restriction ?
			if( $restricted ) {
				wp_die( __( 'Sorry, you are not allowed to access this page.', 'magic-author-box' ), 403 );
			}
		}
	}

	/**
	 * Register the menu pages for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menus() {

		global $current_user;

		//Check if current user is not admin user
		if( ! empty( $current_user->roles ) && ! in_array( 'administrator', $current_user->roles ) ) {

			//Remove author templates
			remove_menu_page('edit.php?post_type=mabox_template');

		} else {

			//Add admin menu page
			add_submenu_page( 'edit.php?post_type=mabox_template', __( 'Settings', 'magic-author-box' ), __( 'Settings', 'magic-author-box' ), 'manage_options', $this->plugin_options_key, array (
					&$this,
					'plugin_options_page' 
			) );
		}
	}

	/**
	 * Plugin options page callback
	 *
	 * @since    1.0.0
	 */
	public function plugin_options_page(){

		//Add message
		if( isset( $_GET['settings-updated'] ) ) {
 			add_settings_error( 'mabox_messages', 'mabox_message', __( 'Settings Updated', 'magic-author-box' ), 'updated' );
		}

		//Show message
		settings_errors( 'mabox_messages' );
		?>
		<div id="mabox-settings" class="wrap">
			<div class="wrap-ma-box">
            	<div class="inner-ma-box">
                	<div class="left-box-ma-box">
						<h2><?php _e( 'Author Box Settings', 'magic-author-box' );?></h2>
						<form method="post" action="options.php">
							<?php settings_fields( $this->plugin_options_key ); ?>
							<?php do_settings_sections( $this->plugin_options_key ); ?>
							<?php submit_button(); ?>
						</form>
					</div>
					<div class="right-box-ma-box">
						<?php $this->general_section_callback(); ?>
					</div>
				</div>
			</div>
		</div><?php
	}

	/**
	 * Init function to initialize hooks & functions
	 *
	 * @since    1.0.0
	 */
	public function settings_init() {

	 	register_setting( 'mabox-settings', 'mabox_options' );

		/* Create settings section */
		add_settings_section(
		    'mabox_general', 
		    false,
		    null,
		    $this->plugin_options_key
		);

		//init all options
	 	add_settings_field(
	        'enable_ab',
	        __( 'Enable Author Box', 'magic-author-box' ),
	        array( $this, 'enable_ab_callback' ),
	        $this->plugin_options_key,
	        'mabox_general',
	        array( 'label_for' => 'enable_ab' )
	    );
	 	add_settings_field(
	        'manualinsert_ab',
	        __( 'Manually insert the Author Box', 'magic-author-box' ),
	        array( $this, 'manualinsert_ab_callback' ),
	        $this->plugin_options_key,
	        'mabox_general',
	        array( 'label_for' => 'manualinsert_ab' )
	    );
	 	add_settings_field(
	        'hide_on_archive',
	        __( 'Hide the author box on archives', 'magic-author-box' ),
	        array( $this, 'hide_on_archive_callback' ),
	        $this->plugin_options_key,
	        'mabox_general',
	        array( 'label_for' => 'hide_on_archive' )
	    );
	}

	/**
	 * General section callback function.
	 *
	 * @since    1.0.0
	 */
	public function general_section_callback() {
		?>
		<div class="ma-box-plugin-cta">
		<h2 class="ma-box-heading">Thank you for downloading our plugin - Magic Author Box.</h2>
			<h2 class="ma-box-heading">We're here to help !</h2>
			<p>Our plugin comes with free, basic support for all users. We also provide plugin customization in case you want to customize our plugin to suit your needs.</p>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Author%20Box&utm_campaign=Free%20Support" target="_blank" class="button">Need help?</a>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Author%20Box&utm_campaign=Plugin%20Customization" target="_blank" class="button">Want to customize plugin?</a>
		</div>
		<?php
		$all_plugins = get_plugins();
		if (!(isset($all_plugins['xml-sitemap-for-google/xml-sitemap-for-google.php']))) {
			?>
				<div class="ma-box-plugin-cta show-other-plugin" id="xml-plugin-banner">
					<h2 class="ma-box-heading">Want to Rank Higher on Google?</h2>
					<h3 class="ma-box-heading">Install <span>XML Sitemap for Google</span> Plugin</h3>
					<hr>
					<p>Our plugin comes with free, basic support for all users.</p>
					<ul class="custom-bullet">
						<li>Easy Setup and Effortless Integration</li>	
						<li>Automatic Updates</li>	
						<li>Improve Search Rankings</li>	
						<li>SEO Best Practices</li>
						<li>Optimized for Performance</li>
					</ul>						
					<br>
					<button id="open-install-mab" class="button-install">Install Plugin</button>
				</div>
			<?php 
		}	
	}

	/**
	 * Settings callback function.
	 *
	 * @since    1.0.0
	 */
	public function enable_ab_callback() {

		//Get option
	 	$enable_ab = !empty( $this->options['enable_ab'] ) ? $this->options['enable_ab'] : '';
		?>		
		<input type='checkbox' name='mabox_options[enable_ab]' <?php checked( $enable_ab, 1, 1 ); ?> value='1'>
		<?php
	}

	/**
	 * Settings callback function.
	 *
	 * @since    1.0.0
	 */
	public function manualinsert_ab_callback() {

		//Get option
	 	$manualinsert_ab = !empty( $this->options['manualinsert_ab'] ) ? $this->options['manualinsert_ab'] : '';
		?>		
		<input id="manualinsert_ab" class="maboxfield" type='checkbox' name='mabox_options[manualinsert_ab]' <?php checked( $manualinsert_ab, 1, 1 ); ?> value='1'>
		<p class="description"><?php _e( 'When checked, the author box will no longer be automatically added to your post. You\'ll need to manually add it using shortcodes or a PHP function.', 'magic-author-box' );?></p>
		<table class="form-table show_if_manualinsert_ab <?php echo $manualinsert_ab != 1 ? 'hide' : ''; ?>">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'If you want to manually insert the Author Box in your template file (single post view), you can use the following code snippet', 'magic-author-box' );?></th>
					<td width="55%">
						<textarea clas="regular-text" rows="3" cols="50" onclick="this.focus();this.select();" readonly="readonly">&lt;?php if ( function_exists( 'mabox_author_box' ) ) echo mabox_author_box(); ?&gt;</textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'If you want to manually insert the Author Box in your post content, you can use the following shortcode, Parameter "ids" is optional.', 'magic-author-box' );?></th>
					<td width="55%">
						<textarea clas="regular-text" rows="3" cols="50" onclick="this.focus();this.select();" readonly="readonly">[mabox-author-box ids="{author id}"]</textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Settings callback function.
	 *
	 * @since    1.0.0
	 */
	public function hide_on_archive_callback() {

		//Get option
	 	$hide_on_archive = !empty( $this->options['hide_on_archive'] ) ? $this->options['hide_on_archive'] : '';
		?>		
		<input type='checkbox' name='mabox_options[hide_on_archive]' <?php checked( $hide_on_archive, 1, 1 ); ?> value='1'>
		<p class="description"><?php _e( 'When Checked, the author box will be removed on archives.', 'magic-author-box' );?></p>
		<?php
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {

		global $post_type;

		// loaded only on template page and profile page
		if( ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && 'mabox_template' == $post_type ) ||
			( 'mabox_template_page_mabox-settings' == $hook || 'profile.php' == $hook || 'user-edit.php' == $hook ) ) {

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

			wp_enqueue_style( $this->plugin_name, MABOX_PLUGIN_URL .'admin/css/author-box-admin.css', array(), $this->version, 'all' );
		}

		// Enqueue Admin Notices CSS
		wp_enqueue_style( 'ma-box-admin-notices', MABOX_PLUGIN_URL .'admin/css/ma-box-admin-notices.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		global $post_type;

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
		$assets_url = MABOX_PLUGIN_URL .'admin/';

		wp_enqueue_script( $this->plugin_name,  $assets_url .'js/install-plugin-mab.js', array( 'jquery' ), $this->version, false );
			add_thickbox();

		// loaded only on template page
		if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && 'mabox_template' == $post_type ) {

			// Styles
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'jquery-ui', $assets_url .'css/jquery-ui.min.css' );

			wp_enqueue_script(
				$this->plugin_name .'-options', $assets_url .'js/author-box-options.js', array(
					'jquery-ui-slider',
					'wp-color-picker',
				),
				$this->version,
				false
			);
		}

		// loaded only on profile page
		if ( 'mabox_template_page_mabox-settings' == $hook || 'profile.php' == $hook || 'user-edit.php' == $hook ) {

			//Enqueue scripts
			wp_enqueue_media();
			wp_enqueue_editor();
			wp_enqueue_script( 'author-box-admin-js',  $assets_url .'js/author-box-admin.js', array( 'jquery' ), $this->version, false );

			//Get social icons
			$social_icons    = MABOX_Helper::get_social_icons();
			unset( $social_icons['user_email'] );

			//Loacalize script data
	        wp_localize_script( $this->plugin_name, 'MABoxScriptsData', array(
	        	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        	'socialIcons' => $social_icons,
	        	'choose_image_title' => __( 'Choose an image', 'magic-author-box' ),
	        	'use_image_btn_text' => __( 'Use image', 'magic-author-box' ),
	        	'placeholder_img_src' => esc_js( __( 'Upload Image', 'magic-author-box' ) ),
	        ));
	    }
	}

	/**
	 * Register settings link on plugin page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_link($links, $file) {

    	$PluginFile = MABOX_PLUGIN_FILE;    	 
        if (basename($file) == $PluginFile) {

            $linkSettings = '<a href="' . admin_url("edit.php?post_type=mabox_template&page=mabox-settings") . '">'. __('Settings', 'magic-author-box' ) .'</a>';
            array_unshift($links, $linkSettings);
        }

        return $links;
    }

	/**
	 * Display footer text that graciously asks them to rate us.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {

		global $current_screen;

		//Check of relatd screen match
		if ( ! empty( $current_screen->id ) && in_array( $current_screen->id, array( 'mabox_template', 'edit-mabox_template', 'mabox_template_page_mabox-settings' ) ) ) {
			
			$url  = 'https://wordpress.org/support/plugin/magic-author-box/reviews/?filter=5#new-post';
			$wpdev_url  = 'https://www.weblineindia.com/wordpress-development.html?utm_source=WP-Plugin&utm_medium=Author%20Box&utm_campaign=Footer%20CTA';
			$text = sprintf(
				wp_kses(
					'Please rate our plugin %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the <a href="%4$s" target="_blank" rel="noopener noreferrer">WordPress development</a> team at WeblineIndia.',
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'<strong>"Magic Author Box"</strong>',
				$url,
				$url,
				$wpdev_url
			);
		}

		return $text;
	}
}