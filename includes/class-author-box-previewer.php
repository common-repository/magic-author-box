<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class handle author box preview functionality
 */
class MABOX_Previewer {

	// These are fields that we need to listen to see if we need to change the preview.
	private $fields = array(
		'mabox_email',
		'mabox_hide_socials',
		'mabox_box_padding_top_bottom',
		'mabox_box_padding_left_right',
		'mabox_box_border_width',
		'mabox_avatar_style',
		'mabox_avatar_hover',
		'mabox_web',
		'mabox_web_position',
		'mabox_colored',
		'mabox_icons_style',
		'mabox_social_hover',
		'mabox_box_long_shadow',
		'mabox_box_thin_border',
		'mabox_box_author_color',
		'mabox_box_web_color',
		'mabox_box_border',
		'mabox_box_icons_back',
		'mabox_box_author_back',
		'mabox_box_author_p_color',
		'mabox_box_author_a_color',
		'mabox_box_icons_color',
		'mabox_box_name_font',
		'mabox_box_web_font',
		'mabox_box_desc_font',
		'mabox_box_name_size',
		'mabox_box_web_size',
		'mabox_box_desc_size',
		'mabox_box_icon_size',
		'mabox_desc_style'
	);

	private $template_options;

	function __construct() {

		// Output Author Box
		add_action( 'mabox_admin_preview', array( $this, 'output_author_box' ), 10, 1 );

		// Enqueue previewer js
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_style_and_scripts' ) );
	}

	public function admin_style_and_scripts( $hook ) {

		global $post_type;

		// loaded only on template page
		if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && 'mabox_template' == $post_type ) {

			wp_enqueue_script( 'mabox-webfont', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', array(), false, true );
			wp_enqueue_script( 'mabox-previewer', MABOX_PLUGIN_URL . '/admin/js/author-box-preview.js', array(
				'jquery',
				'backbone',
				'mabox-webfont'
			), false, true );
		}

	}

	public function output_author_box( $post_id ) {

		$this->template_options = MABOX_Helper::get_template_option( $post_id, 'mabox_options' );

		echo '<style type="text/css">';
		echo $this->generate_inline_css( $post_id );
		echo '</style>';

		echo '<div class="mabox-wrap" style="max-width:656px;margin:0 auto;width:100%;">'; // start mabox-wrap div

		// author box gravatar
		$avatar_classes = 'mabox-gravatar';
		if ( '1' == $this->template_options['mabox_avatar_hover'] ) {
			$avatar_classes .= ' mabox-rotate-img';
		}

		if ( '1' == $this->template_options['mabox_avatar_style'] ) {
			$avatar_classes .= ' mabox-round-image';
		}
		echo '<div class="' . $avatar_classes . '">';
		echo get_avatar( 1, 100, 'mystery', '', array( 'force_default' => true ) );

		echo '</div>';

		// author box name
		echo '<div class="mabox-authorname">';
		echo apply_filters( 'mabox_preview_author_html', '<a href="#" class="vcard author"><span class="fn">Martin Luis</span></a>' );
		echo '</div>';


		// author box description
		echo '<div class="mabox-desc">';
		echo '<div>';
		echo wp_kses_post( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.' );
		echo '</div>';
		echo '</div>';

		echo '<div class="mabox-web' . ( '1' == $this->template_options['mabox_web_position'] ? ' mabox-web-position' : '' ) . '" style="' . ( '1' == $this->template_options['mabox_web'] ? '' : 'display:none;' ) . '">';
		echo '<a href="https://www.weblineindia.com/" target="_blank">www.weblineindia.com</a>';
		echo '</div>';

		// author box clearfix
		echo '<div class="clearfix"></div>';

		$social_links               = MABOX_Helper::$social_icons;
		$social_links['user_email'] = '#';

		$extra_class = ' mabox-show-simple';
		if ( '1' == $this->template_options['mabox_colored'] ) {
			if ( '1' == $this->template_options['mabox_icons_style'] ) {
				$extra_class = ' mabox-show-circle';
			} else {
				$extra_class = ' mabox-show-square';
			}
		}

		if ( '1' != $this->template_options['mabox_box_long_shadow'] ) {
			$extra_class .= ' without-long-shadow';
		}

		if ( '1' == $this->template_options['mabox_social_hover'] ) {
			$extra_class .= ' mabox-rotate-icons';
		}

		if ( '1' == $this->template_options['mabox_box_thin_border'] ) {
			$extra_class .= ' mabox-icons-with-border';
		}

		echo '<div class="mabox-socials mabox-colored' . $extra_class . '" style="' . ( '1' == $this->template_options['mabox_hide_socials'] ? 'display:none;' : '' ) . '">';
		$simple_icons_html = '';
		$circle_icons_html = '';
		$square_icons_html = '';
		$link              = '<a href="#" class="%s">%s</a>';

		foreach ( $social_links as $social_platform => $social_link ) {

			$simple_icons_html .= sprintf( $link, 'mabox-icon-grey', MABOX_Social::icon_to_svg( $social_platform, 'simple' ) );
			$circle_icons_html .= sprintf( $link, 'mabox-icon-color', MABOX_Social::icon_to_svg( $social_platform, 'circle' ) );
			$square_icons_html .= sprintf( $link, 'mabox-icon-color', MABOX_Social::icon_to_svg( $social_platform, 'square' ) );
		}

		echo '<div class="mabox-simple-icons">' . $simple_icons_html . '</div>';
		echo '<div class="mabox-circle-icons">' . $circle_icons_html . '</div>';
		echo '<div class="mabox-square-icons">' . $square_icons_html . '</div>';


		echo '</div>';
		echo '</div>'; // end of mabox-wrap div
		echo '<div class="note"><strong>Note:</strong> By default our Author Box will take the current font family and color from your theme. Basically if you don\'t select a font or a color from the template\'s settings the font and color of the Author Box will be different on the front-end than in the previewer.</div>';
	}

	private function generate_inline_css( $post_id ) {

		$padding_top_bottom = MABOX_Helper::get_template_option( $post_id, 'mabox_box_padding_top_bottom' );
		$padding_left_right = MABOX_Helper::get_template_option( $post_id, 'mabox_box_padding_left_right' );
		$mabox_name_size    = MABOX_Helper::get_template_option( $post_id, 'mabox_box_name_size' );
		$mabox_desc_size    = MABOX_Helper::get_template_option( $post_id, 'mabox_box_desc_size' );
		$mabox_icon_size    = MABOX_Helper::get_template_option( $post_id, 'mabox_box_icon_size' );
		$template_options   = MABOX_Helper::get_template_option( $post_id, 'mabox_options' );
		$mabox_web_size     = MABOX_Helper::get_template_option( $post_id, 'mabox_box_web_size' );
		$mabox_customcss     = MABOX_Helper::get_template_option( $post_id, 'mabox_box_customcss' );

		$style = '.mabox-wrap{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;box-sizing:border-box;border:1px solid #eee;width:100%;clear:both;display:block;overflow:hidden;word-wrap:break-word;position:relative}.mabox-wrap .mabox-gravatar{float:left;padding:20px}.mabox-wrap .mabox-gravatar img{max-width:100px;height:auto;}.mabox-wrap .mabox-authorname{font-size:18px;line-height:1;margin:20px 0 0 20px;display:block}.mabox-wrap .mabox-authorname a{text-decoration:none}.mabox-wrap .mabox-authorname a:focus{outline:0}.mabox-wrap .mabox-desc{display:block;margin:5px 20px}.mabox-wrap .mabox-desc a{text-decoration:underline}.mabox-wrap .mabox-desc p{margin:5px 0 12px}.mabox-wrap .mabox-web{margin:0 20px 15px;text-align:left}.mabox-wrap .mabox-web-position{text-align:right}.mabox-wrap .mabox-web a{color:#ccc;text-decoration:none}.mabox-wrap .mabox-socials{position:relative;display:block;background:#fcfcfc;padding:5px;border-top:1px solid #eee}.mabox-wrap .mabox-socials a svg{width:20px;height:20px}.mabox-wrap .mabox-socials a svg .st2{fill:#fff; transform-origin:center center;}.mabox-wrap .mabox-socials a svg .st1{fill:rgba(0,0,0,.3)}.mabox-wrap .mabox-socials a:hover{opacity:.8;-webkit-transition:opacity .4s;-moz-transition:opacity .4s;-o-transition:opacity .4s;transition:opacity .4s;box-shadow:none!important;-webkit-box-shadow:none!important}.mabox-wrap .mabox-socials .mabox-icon-color{box-shadow:none;padding:0;border:0;-webkit-transition:opacity .4s;-moz-transition:opacity .4s;-o-transition:opacity .4s;transition:opacity .4s;display:inline-block;color:#fff;font-size:0;text-decoration:inherit;margin:5px;-webkit-border-radius:0;-moz-border-radius:0;-ms-border-radius:0;-o-border-radius:0;border-radius:0;overflow:hidden}.mabox-wrap .mabox-socials .mabox-icon-grey{text-decoration:inherit;box-shadow:none;position:relative;display:-moz-inline-stack;display:inline-block;vertical-align:middle;zoom:1;margin:10px 5px;color:#444; fill:#444;}.clearfix:after,.clearfix:before{content:\' \';display:table;line-height:0;clear:both}.ie7 .clearfix{zoom:1}.mabox-socials.mabox-colored .mabox-icon-color .mabox-twitch{border-color:#38245c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-addthis{border-color:#e91c00}.mabox-socials.mabox-colored .mabox-icon-color .mabox-behance{border-color:#003eb0}.mabox-socials.mabox-colored .mabox-icon-color .mabox-delicious{border-color:#06c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-deviantart{border-color:#036824}.mabox-socials.mabox-colored .mabox-icon-color .mabox-digg{border-color:#00327c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-dribbble{border-color:#ba1655}.mabox-socials.mabox-colored .mabox-icon-color .mabox-facebook{border-color:#1e2e4f}.mabox-socials.mabox-colored .mabox-icon-color .mabox-flickr{border-color:#003576}.mabox-socials.mabox-colored .mabox-icon-color .mabox-github{border-color:#264874}.mabox-socials.mabox-colored .mabox-icon-color .mabox-google{border-color:#0b51c5}.mabox-socials.mabox-colored .mabox-icon-color .mabox-googleplus{border-color:#96271a}.mabox-socials.mabox-colored .mabox-icon-color .mabox-html5{border-color:#902e13}.mabox-socials.mabox-colored .mabox-icon-color .mabox-instagram{border-color:#1630aa}.mabox-socials.mabox-colored .mabox-icon-color .mabox-linkedin{border-color:#00344f}.mabox-socials.mabox-colored .mabox-icon-color .mabox-pinterest{border-color:#5b040e}.mabox-socials.mabox-colored .mabox-icon-color .mabox-reddit{border-color:#992900}.mabox-socials.mabox-colored .mabox-icon-color .mabox-rss{border-color:#a43b0a}.mabox-socials.mabox-colored .mabox-icon-color .mabox-sharethis{border-color:#5d8420}.mabox-socials.mabox-colored .mabox-icon-color .mabox-skype{border-color:#00658a}.mabox-socials.mabox-colored .mabox-icon-color .mabox-soundcloud{border-color:#995200}.mabox-socials.mabox-colored .mabox-icon-color .mabox-spotify{border-color:#0f612c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-stackoverflow{border-color:#a95009}.mabox-socials.mabox-colored .mabox-icon-color .mabox-steam{border-color:#006388}.mabox-socials.mabox-colored .mabox-icon-color .mabox-user_email{border-color:#b84e05}.mabox-socials.mabox-colored .mabox-icon-color .mabox-stumbleUpon{border-color:#9b280e}.mabox-socials.mabox-colored .mabox-icon-color .mabox-tumblr{border-color:#10151b}.mabox-socials.mabox-colored .mabox-icon-color .mabox-twitter{border-color:#0967a0}.mabox-socials.mabox-colored .mabox-icon-color .mabox-vimeo{border-color:#0d7091}.mabox-socials.mabox-colored .mabox-icon-color .mabox-windows{border-color:#003f71}.mabox-socials.mabox-colored .mabox-icon-color .mabox-wordpress{border-color:#0f3647}.mabox-socials.mabox-colored .mabox-icon-color .mabox-yahoo{border-color:#14002d}.mabox-socials.mabox-colored .mabox-icon-color .mabox-youtube{border-color:#900}.mabox-socials.mabox-colored .mabox-icon-color .mabox-xing{border-color:#000202}.mabox-socials.mabox-colored .mabox-icon-color .mabox-mixcloud{border-color:#2475a0}.mabox-socials.mabox-colored .mabox-icon-color .mabox-vk{border-color:#243549}.mabox-socials.mabox-colored .mabox-icon-color .mabox-medium{border-color:#00452c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-quora{border-color:#420e00}.mabox-socials.mabox-colored .mabox-icon-color .mabox-meetup{border-color:#9b181c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-goodreads{border-color:#000}.mabox-socials.mabox-colored .mabox-icon-color .mabox-snapchat{border-color:#999700}.mabox-socials.mabox-colored .mabox-icon-color .mabox-500px{border-color:#00557f}.mabox-socials.mabox-colored .mabox-icon-color .mabox-mastodont{border-color:#185886}.mabox-plus-item{margin-bottom:20px}@media screen and (max-width:480px){.mabox-wrap{text-align:center}.mabox-wrap .mabox-gravatar{float:none;padding:20px 0;text-align:center;margin:0 auto;display:block}.mabox-wrap .mabox-gravatar img{float:none;display:inline-block;display:-moz-inline-stack;vertical-align:middle;zoom:1}.mabox-wrap .mabox-desc{margin:0 10px 20px;text-align:center}.mabox-wrap .mabox-authorname{text-align:center;margin:10px 0 20px}}body .mabox-authorname a,body .mabox-authorname a:hover{box-shadow:none;-webkit-box-shadow:none}a.mabox-profile-edit{font-size:16px!important;line-height:1!important}.mabox-edit-settings a,a.mabox-profile-edit{color:#0073aa!important;box-shadow:none!important;-webkit-box-shadow:none!important}.mabox-edit-settings{margin-right:15px;position:absolute;right:0;z-index:2;bottom:10px;line-height:20px}.mabox-edit-settings i{margin-left:5px}.mabox-socials{line-height:1!important}.rtl .mabox-wrap .mabox-gravatar{float:right}.rtl .mabox-wrap .mabox-authorname{display:flex;align-items:center}.rtl .mabox-wrap .mabox-authorname .mabox-profile-edit{margin-right:10px}.rtl .mabox-edit-settings{right:auto;left:0}img.mabox-custom-avatar{max-width:75px;}';

		// Border color of Author Box
		if ( isset( $template_options['mabox_box_border'] ) && '' != $template_options['mabox_box_border'] ) {
			$style .= '.mabox-wrap {border-color:' . esc_html( $template_options['mabox_box_border'] ) . ';}';
			$style .= '.mabox-wrap .mabox-socials {border-color:' . esc_html( $template_options['mabox_box_border'] ) . ';}';
		}
		// Border width of Author Box
		if ( isset( $template_options['mabox_box_border_width'] ) && '1' != $template_options['mabox_box_border_width'] ) {
			$style .= '.mabox-wrap, .mabox-wrap .mabox-socials{ border-width: ' . esc_html( $template_options['mabox_box_border_width'] ) . 'px; }';
		}
		// Avatar image style
		$style .= '.mabox-wrap .mabox-gravatar.mabox-round-image img {-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;}';

		// Avatar hover effect
		$style .= '.mabox-wrap .mabox-gravatar.mabox-round-image.mabox-rotate-img img {-webkit-transition:all .5s ease;-moz-transition:all .5s ease;-o-transition:all .5s ease;transition:all .5s ease;}';
		$style .= '.mabox-wrap .mabox-gravatar.mabox-round-image.mabox-rotate-img img:hover {-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg);}';

		// Background color of social icons bar
		if ( isset( $template_options['mabox_box_icons_back'] ) && '' != $template_options['mabox_box_icons_back'] ) {
			$style .= '.mabox-wrap .mabox-socials{background-color:' . esc_html( $template_options['mabox_box_icons_back'] ) . ';}';
		}
		// Background color of author box
		if ( isset( $template_options['mabox_box_author_back'] ) && '' != $template_options['mabox_box_author_back'] ) {
			$style .= '.mabox-preview .mabox-wrap {background-color:' . esc_html( $template_options['mabox_box_author_back'] ) . ';}';
		}
		// Color of author box paragraphs
		if ( isset( $template_options['mabox_box_author_p_color'] ) && '' != $template_options['mabox_box_author_p_color'] ) {
			$style .= '.mabox-wrap .mabox-desc p, .mabox-wrap .mabox-desc  {color:' . esc_html( $template_options['mabox_box_author_p_color'] ) . ';}';
		}
		// Color of author box paragraphs
		if ( isset( $template_options['mabox_box_author_a_color'] ) && '' != $template_options['mabox_box_author_a_color'] ) {
			$style .= '.mabox-wrap .mabox-desc a {color:' . esc_html( $template_options['mabox_box_author_a_color'] ) . ';}';
		}

		// Author name color
		if ( isset( $template_options['mabox_box_author_color'] ) && '' != $template_options['mabox_box_author_color'] ) {
			$style .= '.mabox-wrap .mabox-authorname a,.mabox-wrap .mabox-authorname span {color:' . esc_html( $template_options['mabox_box_author_color'] ) . ';}';
		}

		// Author web color
		if ( isset( $template_options['mabox_web'] ) && '1' == $template_options['mabox_web'] && '' != $template_options['mabox_box_web_color'] ) {
			$style .= '.mabox-wrap .mabox-web a {color:' . esc_html( $template_options['mabox_box_web_color'] ) . ';}';
		}

		// Author name font family
		$mabox_box_name_font = MABOX_Helper::get_template_option( $post_id, 'mabox_box_name_font' );
		if ( 'None' != $mabox_box_name_font ) {
			$style .= '.mabox-wrap .mabox-authorname {font-family:"' . esc_html( $mabox_box_name_font ) . '";}';
		}

		// Author description font family
		$mabox_box_desc_font = MABOX_Helper::get_template_option( $post_id, 'mabox_box_desc_font' );
		if ( 'None' != $mabox_box_name_font ) {
			$style .= '.mabox-wrap .mabox-desc {font-family:' . esc_html( $mabox_box_desc_font ) . ';}';
		}

		// Author web font family
		$mabox_box_web_font = MABOX_Helper::get_template_option( $post_id, 'mabox_box_web_font' );
		if ( '1' == $template_options['mabox_web'] && 'None' != $mabox_box_web_font ) {
			$style .= '.mabox-wrap .mabox-web {font-family:"' . esc_html( $mabox_box_web_font ) . '";}';
		}

		// Author description font style
		if ( isset( $template_options['mabox_desc_style'] ) && '1' == $template_options['mabox_desc_style'] ) {
			$style .= '.mabox-wrap .mabox-desc {font-style:italic;}';
		}
		// Margin top & bottom, Padding
		$style .= '.mabox-wrap {padding: ' . absint( $padding_top_bottom ) . 'px ' . absint( $padding_left_right ) . 'px }';
		// Author name text size
		$style .= '.mabox-wrap .mabox-authorname {font-size:' . absint( $mabox_name_size ) . 'px; line-height:' . absint( $mabox_name_size + 7 ) . 'px;}';
		// Author description font size
		$style .= '.mabox-wrap .mabox-desc p, .mabox-wrap .mabox-desc {font-size:' . absint( $mabox_desc_size ) . 'px; line-height:' . absint( $mabox_desc_size + 7 ) . 'px;}';
		// Author website text size
		$style .= '.mabox-wrap .mabox-web {font-size:' . absint( $mabox_web_size ) . 'px;}';

		/* Icons */

		// Color of social icons (for symbols only):
		if ( '' != $template_options['mabox_box_icons_color'] ) {
			$style .= '.mabox-wrap .mabox-socials .mabox-icon-grey {color:' . esc_html( $template_options['mabox_box_icons_color'] ) . ';fill:' . esc_html( $template_options['mabox_box_icons_color'] ) . ';}';
		}

		// Rotate
		$style .= '.mabox-wrap .mabox-socials.mabox-show-circle.mabox-rotate-icons .mabox-icon-color {-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;-o-transition: all 0.3s ease-in-out;-ms-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out;}.mabox-wrap .mabox-socials.mabox-show-circle.mabox-rotate-icons .mabox-icon-color:hover {-webkit-transform: rotate(360deg);-moz-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);}';

		// Thin border
		$style .= '.mabox-wrap .mabox-socials.mabox-icons-with-border .mabox-icon-color svg {border-width: 1px;border-style:solid;}';
		$style .= '.mabox-wrap .mabox-socials.mabox-show-circle.mabox-icons-with-border .mabox-icon-color svg {border-radius:50%}';

		// Long Shadow
		$style .= '.mabox-wrap .mabox-socials.without-long-shadow .mabox-icon-color .st1 {display: none;}';

		// Icons size
		$icon_size    = absint( $mabox_icon_size );
		$icon_size_2x = absint( $mabox_icon_size ) * 2;

		$style .= '.mabox-wrap .mabox-socials a.mabox-icon-grey svg {width:' . absint( $icon_size ) . 'px;height:' . absint( $icon_size ) . 'px;}';
		$style .= '.mabox-wrap .mabox-socials a.mabox-icon-color svg {width:' . absint( $icon_size_2x ) . 'px;height:' . absint( $icon_size_2x ) . 'px;}';
		$style .= '.mabox-simple-icons,.mabox-circle-icons,.mabox-square-icons{display:none;}.mabox-show-simple .mabox-simple-icons{ display:block; }.mabox-show-circle .mabox-circle-icons{ display:block; }.mabox-show-square .mabox-square-icons{ display:block; }';
		$style .= '.mabox-wrap a{cursor:not-allowed;}';

		//Added custom CSS to preview
		$style .= $mabox_customcss;

		$style = apply_filters( 'mabox-previewer-css', $style, $template_options );

		return $style;

	}
}

new MABOX_Previewer();