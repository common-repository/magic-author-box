<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class handle all helper functions
 */
class MABOX_Helper {

	public static $fonts = array();
	public static $template_options = array();

	public static $social_icons = array(
		'addthis'       => 'Add This',
		'behance'       => 'Behance',
		'delicious'     => 'Delicious',
		'deviantart'    => 'Deviantart',
		'digg'          => 'Digg',
		'discord'		=> 'Discord',
		'dribbble'      => 'Dribbble',
		'facebook'      => 'Facebook',
		'whatsapp'      => 'WhatsApp',
		'flickr'        => 'Flickr',
		'github'        => 'Github',
		'google'        => 'Google',
		'googleplus'    => 'Google Plus',
		'html5'         => 'Html5',
		'instagram'     => 'Instagram',
		'linkedin'      => 'Linkedin',
		'pinterest'     => 'Pinterest',
		'reddit'        => 'Reddit',
		'rss'           => 'Rss',
		'sharethis'     => 'Sharethis',
		'skype'         => 'Skype',
		'soundcloud'    => 'Soundcloud',
		'spotify'       => 'Spotify',
		'stackoverflow' => 'Stackoverflow',
		'steam'         => 'Steam',
		'stumbleUpon'   => 'StumbleUpon',
		'tumblr'        => 'Tumblr',
		'twitter'       => 'Twitter',
		'vimeo'         => 'Vimeo',
		'windows'       => 'Windows',
		'wordpress'     => 'WordPress',
		'yahoo'         => 'Yahoo',
		'youtube'       => 'Youtube',
		'xing'          => 'Xing',
		'mixcloud'      => 'MixCloud',
		'goodreads'     => 'Goodreads',
		'twitch'        => 'Twitch',
		'vk'            => 'VK',
		'medium'        => 'Medium',
		'quora'         => 'Quora',
		'meetup'        => 'Meetup',
		'user_email'    => 'Email',
		'snapchat'      => 'Snapchat',
		'500px'         => '500px',
		'mastodont'     => 'Mastodon',
		'telegram'      => 'Telegram',
		'phone'			=> 'Phone'
	);

	public static function get_social_icons() {
		return apply_filters( 'mabox_social_icons', MABOX_Helper::$social_icons );
	}

	public static function get_mabox_social_icon( $template_id, $url, $icon_name ) {

		$template_options = self::get_template_option( $template_id, 'mabox_options' );

		if ( '0' != $template_options['mabox_link_target'] && 'user_email' != $icon_name ) {
			$mabox_blank = '_blank';
		} else {
			$mabox_blank = '_self';
		}

		if ( '1' == $template_options['mabox_colored'] ) {
			$mabox_color = 'mabox-icon-color';
		} else {
			$mabox_color = 'mabox-icon-grey';
		}

		$type = 'simple';
		if ( '1' == $template_options['mabox_colored'] ) {
			if ( '1' == $template_options['mabox_icons_style'] ) {
				$type = 'circle';
			}else{
				$type = 'square';
			}
		}

		$url = ('skype' != $icon_name) ? esc_url($url) : esc_attr($url);

		$svg_icon = MABOX_Social::icon_to_svg( $icon_name, $type );
		return '<a target="' . esc_attr( $mabox_blank ) . '" href="' .  $url . '" rel="nofollow" class="' . esc_attr( $mabox_color ) . '">' . $svg_icon . '</span></a>';

	}

	public static function get_user_social_links( $userd_id, $show_email = false ) {

		$social_icons = MABOX_Helper::get_social_icons();
		$social_links = get_user_meta( $userd_id, 'mabox_social_links', true );

		if ( ! is_array( $social_links ) ) {
			$social_links = array();
		}

		if ( $show_email ) {
			$social_links['user_email'] = get_the_author_meta( 'user_email', $userd_id );
		}

		return $social_links;

	}

	public static function get_google_font_subsets() {
		return array(
			'none'         => 'None',
			'latin'        => 'Latin',
			'latin-ext'    => 'Latin Extended',
			'cyrillic'     => 'Cyrillic',
			'cyrillic-ext' => 'Cyrillic Extended',
			'devanagari'   => 'Devanagari',
			'greek'        => 'Greek',
			'greek-ext'    => 'Greek Extended',
			'vietnamese'   => 'Vietnamese',
			'khmer'        => 'Khmer',
		);
	}

	public static function strip_prot($url) {
		$url = str_ireplace("http://", "", $url);
		$url = str_ireplace("https://", "", $url);
		return $url;
	}

	public static function get_google_fonts() {
		$fonts = array(
			'None',
			'ABeeZee',
			'Abel',
			'Abril Fatface',
			'Aclonica',
			'Acme',
			'Actor',
			'Adamina',
			'Advent Pro',
			'Aguafina Script',
			'Akronim',
			'Aladin',
			'Aldrich',
			'Alef',
			'Alegreya',
			'Alegreya SC',
			'Alegreya Sans',
			'Alegreya Sans SC',
			'Alex Brush',
			'Alfa Slab One',
			'Alice',
			'Alike',
			'Alike Angular',
			'Allan',
			'Allerta',
			'Allerta Stencil',
			'Allura',
			'Almendra',
			'Almendra Display',
			'Almendra SC',
			'Amarante',
			'Amaranth',
			'Amatic SC',
			'Amethysta',
			'Anaheim',
			'Andada',
			'Andika',
			'Angkor',
			'Annie Use Your Telescope',
			'Anonymous Pro',
			'Antic',
			'Antic Didone',
			'Antic Slab',
			'Anton',
			'Arapey',
			'Arbutus',
			'Arbutus Slab',
			'Architects Daughter',
			'Archivo Black',
			'Archivo Narrow',
			'Arimo',
			'Arizonia',
			'Armata',
			'Artifika',
			'Arvo',
			'Asap',
			'Asset',
			'Astloch',
			'Asul',
			'Atomic Age',
			'Aubrey',
			'Audiowide',
			'Autour One',
			'Average',
			'Average Sans',
			'Averia Gruesa Libre',
			'Averia Libre',
			'Averia Sans Libre',
			'Averia Serif Libre',
			'Bad Script',
			'Balthazar',
			'Bangers',
			'Basic',
			'Battambang',
			'Baumans',
			'Bayon',
			'Belgrano',
			'Belleza',
			'BenchNine',
			'Bentham',
			'Berkshire Swash',
			'Bevan',
			'Bigelow Rules',
			'Bigshot One',
			'Bilbo',
			'Bilbo Swash Caps',
			'Bitter',
			'Black Ops One',
			'Bokor',
			'Bonbon',
			'Boogaloo',
			'Bowlby One',
			'Bowlby One SC',
			'Brawler',
			'Bree Serif',
			'Bubblegum Sans',
			'Bubbler One',
			'Buda',
			'Buenard',
			'Butcherman',
			'Butterfly Kids',
			'Cabin',
			'Cabin Condensed',
			'Cabin Sketch',
			'Caesar Dressing',
			'Cagliostro',
			'Calligraffitti',
			'Cambo',
			'Candal',
			'Cantarell',
			'Cantata One',
			'Cantora One',
			'Capriola',
			'Cardo',
			'Carme',
			'Carrois Gothic',
			'Carrois Gothic SC',
			'Carter One',
			'Caudex',
			'Cedarville Cursive',
			'Ceviche One',
			'Changa One',
			'Chango',
			'Chau Philomene One',
			'Chela One',
			'Chelsea Market',
			'Chenla',
			'Cherry Cream Soda',
			'Cherry Swash',
			'Chewy',
			'Chicle',
			'Chivo',
			'Cinzel',
			'Cinzel Decorative',
			'Clicker Script',
			'Coda',
			'Coda Caption',
			'Codystar',
			'Combo',
			'Comfortaa',
			'Coming Soon',
			'Concert One',
			'Condiment',
			'Content',
			'Contrail One',
			'Convergence',
			'Cookie',
			'Copse',
			'Corben',
			'Courgette',
			'Cousine',
			'Coustard',
			'Covered By Your Grace',
			'Crafty Girls',
			'Creepster',
			'Crete Round',
			'Crimson Text',
			'Croissant One',
			'Crushed',
			'Cuprum',
			'Cutive',
			'Cutive Mono',
			'Damion',
			'Dancing Script',
			'Dangrek',
			'Dawning of a New Day',
			'Days One',
			'Delius',
			'Delius Swash Caps',
			'Delius Unicase',
			'Della Respira',
			'Denk One',
			'Devonshire',
			'Didact Gothic',
			'Diplomata',
			'Diplomata SC',
			'Domine',
			'Donegal One',
			'Doppio One',
			'Dorsa',
			'Dosis',
			'Dr Sugiyama',
			'Droid Sans',
			'Droid Sans Mono',
			'Droid Serif',
			'Duru Sans',
			'Dynalight',
			'EB Garamond',
			'Eagle Lake',
			'Eater',
			'Economica',
			'Ek Mukta',
			'Electrolize',
			'Elsie',
			'Elsie Swash Caps',
			'Emblema One',
			'Emilys Candy',
			'Engagement',
			'Englebert',
			'Enriqueta',
			'Erica One',
			'Esteban',
			'Euphoria Script',
			'Ewert',
			'Exo',
			'Exo 2',
			'Expletus Sans',
			'Fanwood Text',
			'Fascinate',
			'Fascinate Inline',
			'Faster One',
			'Fasthand',
			'Fauna One',
			'Federant',
			'Federo',
			'Felipa',
			'Fenix',
			'Finger Paint',
			'Fira Mono',
			'Fira Sans',
			'Fjalla One',
			'Fjord One',
			'Flamenco',
			'Flavors',
			'Fondamento',
			'Fontdiner Swanky',
			'Forum',
			'Francois One',
			'Freckle Face',
			'Fredericka the Great',
			'Fredoka One',
			'Freehand',
			'Fresca',
			'Frijole',
			'Fruktur',
			'Fugaz One',
			'GFS Didot',
			'GFS Neohellenic',
			'Gabriela',
			'Gafata',
			'Galdeano',
			'Galindo',
			'Gentium Basic',
			'Gentium Book Basic',
			'Geo',
			'Geostar',
			'Geostar Fill',
			'Germania One',
			'Gilda Display',
			'Give You Glory',
			'Glass Antiqua',
			'Glegoo',
			'Gloria Hallelujah',
			'Goblin One',
			'Gochi Hand',
			'Gorditas',
			'Goudy Bookletter 1911',
			'Graduate',
			'Grand Hotel',
			'Gravitas One',
			'Great Vibes',
			'Griffy',
			'Gruppo',
			'Gudea',
			'Habibi',
			'Hammersmith One',
			'Hanalei',
			'Hanalei Fill',
			'Handlee',
			'Hanuman',
			'Happy Monkey',
			'Headland One',
			'Henny Penny',
			'Herr Von Muellerhoff',
			'Hind',
			'Holtwood One SC',
			'Homemade Apple',
			'Homenaje',
			'IM Fell DW Pica',
			'IM Fell DW Pica SC',
			'IM Fell Double Pica',
			'IM Fell Double Pica SC',
			'IM Fell English',
			'IM Fell English SC',
			'IM Fell French Canon',
			'IM Fell French Canon SC',
			'IM Fell Great Primer',
			'IM Fell Great Primer SC',
			'Iceberg',
			'Iceland',
			'Imprima',
			'Inconsolata',
			'Inder',
			'Indie Flower',
			'Inika',
			'Irish Grover',
			'Istok Web',
			'Italiana',
			'Italianno',
			'Jacques Francois',
			'Jacques Francois Shadow',
			'Jim Nightshade',
			'Jockey One',
			'Jolly Lodger',
			'Josefin Sans',
			'Josefin Slab',
			'Joti One',
			'Judson',
			'Julee',
			'Julius Sans One',
			'Junge',
			'Jura',
			'Just Another Hand',
			'Just Me Again Down Here',
			'Kalam',
			'Kameron',
			'Kantumruy',
			'Karla',
			'Karma',
			'Kaushan Script',
			'Kavoon',
			'Kdam Thmor',
			'Keania One',
			'Kelly Slab',
			'Kenia',
			'Khmer',
			'Kite One',
			'Knewave',
			'Kotta One',
			'Koulen',
			'Kranky',
			'Kreon',
			'Kristi',
			'Krona One',
			'La Belle Aurore',
			'Lancelot',
			'Lato',
			'League Script',
			'Leckerli One',
			'Ledger',
			'Lekton',
			'Lemon',
			'Libre Baskerville',
			'Life Savers',
			'Lilita One',
			'Lily Script One',
			'Limelight',
			'Linden Hill',
			'Lobster',
			'Lobster Two',
			'Londrina Outline',
			'Londrina Shadow',
			'Londrina Sketch',
			'Londrina Solid',
			'Lora',
			'Love Ya Like A Sister',
			'Loved by the King',
			'Lovers Quarrel',
			'Luckiest Guy',
			'Lusitana',
			'Lustria',
			'Macondo',
			'Macondo Swash Caps',
			'Magra',
			'Maiden Orange',
			'Mako',
			'Marcellus',
			'Marcellus SC',
			'Marck Script',
			'Margarine',
			'Marko One',
			'Marmelad',
			'Marvel',
			'Mate',
			'Mate SC',
			'Maven Pro',
			'McLaren',
			'Meddon',
			'MedievalSharp',
			'Medula One',
			'Megrim',
			'Meie Script',
			'Merienda',
			'Merienda One',
			'Merriweather',
			'Merriweather Sans',
			'Metal',
			'Metal Mania',
			'Metamorphous',
			'Metrophobic',
			'Michroma',
			'Milonga',
			'Miltonian',
			'Miltonian Tattoo',
			'Miniver',
			'Miss Fajardose',
			'Modern Antiqua',
			'Molengo',
			'Molle',
			'Monda',
			'Monofett',
			'Monoton',
			'Monsieur La Doulaise',
			'Montaga',
			'Montez',
			'Montserrat',
			'Montserrat Alternates',
			'Montserrat Subrayada',
			'Moul',
			'Moulpali',
			'Mountains of Christmas',
			'Mouse Memoirs',
			'Mr Bedfort',
			'Mr Dafoe',
			'Mr De Haviland',
			'Mrs Saint Delafield',
			'Mrs Sheppards',
			'Muli',
			'Mystery Quest',
			'Neucha',
			'Neuton',
			'New Rocker',
			'News Cycle',
			'Niconne',
			'Nixie One',
			'Nobile',
			'Nokora',
			'Norican',
			'Nosifer',
			'Nothing You Could Do',
			'Noticia Text',
			'Noto Sans',
			'Noto Serif',
			'Nova Cut',
			'Nova Flat',
			'Nova Mono',
			'Nova Oval',
			'Nova Round',
			'Nova Script',
			'Nova Slim',
			'Nova Square',
			'Numans',
			'Nunito',
			'Odor Mean Chey',
			'Offside',
			'Old Standard TT',
			'Oldenburg',
			'Oleo Script',
			'Oleo Script Swash Caps',
			'Open Sans',
			'Open Sans Condensed',
			'Oranienbaum',
			'Orbitron',
			'Oregano',
			'Orienta',
			'Original Surfer',
			'Oswald',
			'Over the Rainbow',
			'Overlock',
			'Overlock SC',
			'Ovo',
			'Oxygen',
			'Oxygen Mono',
			'PT Mono',
			'PT Sans',
			'PT Sans Caption',
			'PT Sans Narrow',
			'PT Serif',
			'PT Serif Caption',
			'Pacifico',
			'Paprika',
			'Parisienne',
			'Passero One',
			'Passion One',
			'Pathway Gothic One',
			'Patrick Hand',
			'Patrick Hand SC',
			'Patua One',
			'Paytone One',
			'Peralta',
			'Permanent Marker',
			'Petit Formal Script',
			'Petrona',
			'Philosopher',
			'Piedra',
			'Pinyon Script',
			'Pirata One',
			'Plaster',
			'Play',
			'Playball',
			'Playfair Display',
			'Playfair Display SC',
			'Podkova',
			'Poiret One',
			'Poller One',
			'Poly',
			'Pompiere',
			'Pontano Sans',
			'Port Lligat Sans',
			'Port Lligat Slab',
			'Prata',
			'Preahvihear',
			'Press Start 2P',
			'Princess Sofia',
			'Prociono',
			'Prosto One',
			'Puritan',
			'Purple Purse',
			'Quando',
			'Quantico',
			'Quattrocento',
			'Quattrocento Sans',
			'Questrial',
			'Quicksand',
			'Quintessential',
			'Qwigley',
			'Racing Sans One',
			'Radley',
			'Rajdhani',
			'Raleway',
			'Raleway Dots',
			'Rambla',
			'Rammetto One',
			'Ranchers',
			'Rancho',
			'Rationale',
			'Redressed',
			'Reenie Beanie',
			'Revalia',
			'Ribeye',
			'Ribeye Marrow',
			'Righteous',
			'Risque',
			'Roboto',
			'Roboto Condensed',
			'Roboto Slab',
			'Rochester',
			'Rock Salt',
			'Rokkitt',
			'Romanesco',
			'Ropa Sans',
			'Rosario',
			'Rosarivo',
			'Rouge Script',
			'Rubik Mono One',
			'Rubik One',
			'Ruda',
			'Rufina',
			'Ruge Boogie',
			'Ruluko',
			'Rum Raisin',
			'Ruslan Display',
			'Russo One',
			'Ruthie',
			'Rye',
			'Sacramento',
			'Sail',
			'Salsa',
			'Sanchez',
			'Sancreek',
			'Sansita One',
			'Sarina',
			'Satisfy',
			'Scada',
			'Schoolbell',
			'Seaweed Script',
			'Sevillana',
			'Seymour One',
			'Shadows Into Light',
			'Shadows Into Light Two',
			'Shanti',
			'Share',
			'Share Tech',
			'Share Tech Mono',
			'Shojumaru',
			'Short Stack',
			'Siemreap',
			'Sigmar One',
			'Signika',
			'Signika Negative',
			'Simonetta',
			'Sintony',
			'Sirin Stencil',
			'Six Caps',
			'Skranji',
			'Slabo 13px',
			'Slabo 27px',
			'Slackey',
			'Smokum',
			'Smythe',
			'Sniglet',
			'Snippet',
			'Snowburst One',
			'Sofadi One',
			'Sofia',
			'Sonsie One',
			'Sorts Mill Goudy',
			'Source Code Pro',
			'Source Sans Pro',
			'Source Serif Pro',
			'Special Elite',
			'Spicy Rice',
			'Spinnaker',
			'Spirax',
			'Squada One',
			'Stalemate',
			'Stalinist One',
			'Stardos Stencil',
			'Stint Ultra Condensed',
			'Stint Ultra Expanded',
			'Stoke',
			'Strait',
			'Sue Ellen Francisco',
			'Sunshiney',
			'Supermercado One',
			'Suwannaphum',
			'Swanky and Moo Moo',
			'Syncopate',
			'Tangerine',
			'Taprom',
			'Tauri',
			'Teko',
			'Telex',
			'Tenor Sans',
			'Text Me One',
			'The Girl Next Door',
			'Tienne',
			'Tinos',
			'Titan One',
			'Titillium Web',
			'Trade Winds',
			'Trocchi',
			'Trochut',
			'Trykker',
			'Tulpen One',
			'Ubuntu',
			'Ubuntu Condensed',
			'Ubuntu Mono',
			'Ultra',
			'Uncial Antiqua',
			'Underdog',
			'Unica One',
			'UnifrakturCook',
			'UnifrakturMaguntia',
			'Unkempt',
			'Unlock',
			'Unna',
			'VT323',
			'Vampiro One',
			'Varela',
			'Varela Round',
			'Vast Shadow',
			'Vibur',
			'Vidaloka',
			'Viga',
			'Voces',
			'Volkhov',
			'Vollkorn',
			'Voltaire',
			'Waiting for the Sunrise',
			'Wallpoet',
			'Walter Turncoat',
			'Warnes',
			'Wellfleet',
			'Wendy One',
			'Wire One',
			'Yanone Kaffeesatz',
			'Yellowtail',
			'Yeseva One',
			'Yesteryear',
			'Zeyada',
		);

		if ( empty( MABOX_Helper::$fonts ) ) {
			foreach ( $fonts as $font ) {
				MABOX_Helper::$fonts[ $font ] = $font;
			}
		}

		return MABOX_Helper::$fonts;

	}

	public static function get_custom_post_type() {
		$post_types = get_post_types(
			array(
				'publicly_queryable' => true,
				'_builtin'           => false,
			)
		);

		$post_types['post'] = __( 'Post', 'magic-author-box' );
		$post_types['page'] = __( 'Page', 'magic-author-box' );

		return $post_types;
	}

	public static function get_template( $template_name = 'template-mabox.php' ) {

		$template = '';

		if ( ! $template ) {
			$template = locate_template( array( 'author-box/' . $template_name ) );
		}

		if ( ! $template && file_exists( MABOX_PLUGIN_PATH . '/templates/' . $template_name ) ) {
			$template = MABOX_PLUGIN_PATH . '/includes/templates/' . $template_name;
		}

		if ( ! $template ) {
			$template = MABOX_PLUGIN_PATH . '/includes/templates/template-mabox.php';
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'mabox_get_template_part', $template, $template_name );
		if ( $template ) {
			return $template;
		}

	}

	public static function reset_options() {
		self::$template_options = array();
	}

	public static function get_template_option( $post_id, $key, $force = false ) {

		$defaults = apply_filters( 'mabox_meta_data_defaults', array(
			'mabox_options' => array(
		        'mabox_autoinsert'         => '0',
		        'mabox_no_description'     => '0',
		        'mabox_email'              => '0',
		        'mabox_link_target'        => '0',
		        'mabox_hide_socials'       => '0',
		        'mabox_hide_on_archive'    => '0',
		        'mabox_box_border_width'   => '1',
		        'mabox_avatar_style'       => '0',
		        'mabox_avatar_hover'       => '0',
		        'mabox_web'                => '0',
		        'mabox_web_target'         => '0',
		        'mabox_web_rel'            => '0',
		        'mabox_web_position'       => '0',
		        'mabox_colored'            => '0',
		        'mabox_icons_style'        => '0',
		        'mabox_social_hover'       => '0',
		        'mabox_box_long_shadow'    => '0',
		        'mabox_box_thin_border'    => '0',
		        'mabox_box_author_color'   => '0',
		        'mabox_box_web_color'      => '0',
		        'mabox_box_border'         => '',
		        'mabox_box_icons_back'     => '',
		        'mabox_box_author_back'    => '',
		        'mabox_box_author_p_color' => '',
		        'mabox_box_author_a_color' => '0',
		        'mabox_box_icons_color'    => '0',
                'mabox_footer_inline_style' => '0',
		    ),
		    'mabox_box_margin_top'         => '0',
		    'mabox_box_margin_bottom'      => '0',
		    'mabox_box_padding_top_bottom' => '0',
		    'mabox_box_padding_left_right' => '0',
		    'mabox_box_subset'             => 'none',
		    'mabox_box_name_font'          => 'None',
		    'mabox_box_web_font'           => 'None',
		    'mabox_box_desc_font'          => 'None',
		    'mabox_box_name_size'          => '18',
		    'mabox_box_web_size'           => '14',
		    'mabox_box_desc_size'          => '14',
		    'mabox_box_icon_size'          => '18',
		    'mabox_desc_style'             => '0',
		    'mabox_box_customcss'          => '',
		) );

		//Return default if empty post id
		if( empty( $post_id ) && !empty( $key ) ) return $defaults[ $key ];

		if ( 'mabox_options' == $key ) {

			if( !empty( $post_id ) &&  empty( self::$template_options[$post_id]['mabox_options'] ) ) {
				self::$template_options[$post_id]['mabox_options'] = get_post_meta( $post_id, 'mabox_options', true );
			}

			return wp_parse_args( self::$template_options[$post_id]['mabox_options'], $defaults['mabox_options'] );

		} else {

			//Check if already requested then just return
			if( !empty( $key ) && !empty( $post_id ) && isset( self::$template_options[$post_id][ $key ] ) ) {

				return self::$template_options[$post_id][ $key ];

			} elseif ( !empty( $key ) && !empty( $post_id ) ) {

				//Get post option
				$option = get_post_meta( $post_id, $key, true );
				$option = $option != '' ? $option : false;

				if ( false === $option && isset( $defaults[ $key ] ) ) {
					return $defaults[ $key ];
				} elseif ( false !== $option ) {
					self::$template_options[$post_id][ $key ] = $option;
					return self::$template_options[$post_id][ $key ];
				}

			} else {
				return $defaults[ $key ]; //Return default
			}
		}

		return false;
	}

	public static function get_author_template_id( $user_id ) {

		//Check if user empty
		if( empty( $user_id ) ) return;

		return get_user_meta( $user_id, 'mabox_profile_template', true );
	}

	public static function check_allowed_user_roles( $roles = array() ) {

		//Get current user roles
		if( empty( $roles ) ) {

        	global $current_user;
        	$roles = !empty( $current_user->roles ) ? $current_user->roles : array();
		}

        //Get allowed roles for author template
        $allowed_roles = apply_filters( 'mabox_get_allowed_roles', array( 'administrator', 'author', 'editor' ) );
 
        //Check if roles match
        if( ! empty( array_intersect( $allowed_roles, $roles ) ) ) return true;

        return false;
	}

	public static function get_author_template_fonts( $post_ids ) {

		//Check if post ids exists
		$google_fonts_url = '';
		if( !empty( $post_ids ) ) {

			//Get details
			$mabox_protocol = is_ssl() ? 'https' : 'http';
			$google_fonts = array();
			$mabox_subsets  = array();

			foreach ($post_ids as $post_id ) {

				//Get subset
				$mabox_box_subset = MABOX_Helper::get_template_option( $post_id, 'mabox_box_subset' );

				/**
				 * Check for duplicate font families, remove duplicates & re-work the font enqueue procedure
				 */
				if ( 'none' != strtolower( $mabox_box_subset ) ) {
					$mabox_subsets[] = strtolower( $mabox_box_subset );
				} else {
					$mabox_subsets[] = 'latin';
				}

				//Get options
				$mabox_author_font = MABOX_Helper::get_template_option( $post_id, 'mabox_box_name_font' );
				$mabox_desc_font   = MABOX_Helper::get_template_option( $post_id, 'mabox_box_desc_font' );
				$mabox_web_font    = MABOX_Helper::get_template_option( $post_id, 'mabox_box_web_font' );

				if ( $mabox_author_font && 'none' != strtolower( $mabox_author_font ) ) {
					$google_fonts[] = str_replace( ' ', '+', esc_attr( $mabox_author_font ) );
				}

				if ( $mabox_desc_font && 'none' != strtolower( $mabox_desc_font ) ) {
					$google_fonts[] = str_replace( ' ', '+', esc_attr( $mabox_desc_font ) );
				}

				if ( isset( self::$template_options[$post_id]['mabox_options']['mabox_web'] ) && '1' == self::$template_options[$post_id]['mabox_options']['mabox_web'] && $mabox_web_font && 'none' != strtolower( $mabox_web_font ) ) {
					$google_fonts[] = str_replace( ' ', '+', esc_attr( $mabox_web_font ) );
				}

				$google_fonts = apply_filters( 'mabox_google_fonts', $google_fonts );

				$google_fonts = array_unique( $google_fonts );

				// Check fonts exists before loading
				if( ! empty( $google_fonts ) ) {
					$final_google_fonts = array();

					foreach ( $google_fonts as $v ) {
						$final_google_fonts[] = $v . ':400,700,400italic,700italic';
					}

					//Combine fonts
					$google_fonts_url .= implode( '|', $final_google_fonts );
				}
			}

			//Make full google fonts url
			$google_fonts_url = $mabox_protocol .'://fonts.googleapis.com/css?family='. $google_fonts_url .'&amp;subset='. implode( ',', array_unique($mabox_subsets) );

			return $google_fonts_url;
		}

		return false;
	}

	public static function generate_inline_css( $post_id ) {

		//Get options
		$padding_top_bottom  = self::get_template_option( $post_id, 'mabox_box_padding_top_bottom' );
		$padding_left_right  = self::get_template_option( $post_id, 'mabox_box_padding_left_right' );
		$mabox_top_margin    = self::get_template_option( $post_id, 'mabox_box_margin_top' );
		$mabox_bottom_margin = self::get_template_option( $post_id, 'mabox_box_margin_bottom' );
		$mabox_name_size     = self::get_template_option( $post_id, 'mabox_box_name_size' );
		$mabox_desc_size     = self::get_template_option( $post_id, 'mabox_box_desc_size' );
		$mabox_icon_size     = self::get_template_option( $post_id, 'mabox_box_icon_size' );
		$mabox_options       = self::get_template_option( $post_id, 'mabox_options' );
		$mabox_web_size      = self::get_template_option( $post_id, 'mabox_box_web_size' );
		$mabox_customcss    = self::get_template_option( $post_id, 'mabox_box_customcss' );

		//Initialize style
		$style = '';
		$styles = array(
			'common' 	=> '',
			'template' 	=> '',
			'customcss' => $mabox_customcss,
		);

		$styles['common'] = '.mabox-wrap{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;box-sizing:border-box;border:1px solid #eee;width:100%;clear:both;display:block;overflow:hidden;word-wrap:break-word;position:relative}.mabox-wrap .mabox-gravatar{float:left;padding:20px}.mabox-wrap .mabox-gravatar img{max-width:100px;height:auto;border-radius:0;}.mabox-wrap .mabox-authorname{font-size:18px;line-height:1;margin:20px 0 0 20px;display:block}.mabox-wrap .mabox-authorname a{text-decoration:none}.mabox-wrap .mabox-authorname a:focus{outline:0}.mabox-wrap .mabox-desc{display:block;margin:5px 20px}.mabox-wrap .mabox-desc a{text-decoration:underline}.mabox-wrap .mabox-desc p{margin:5px 0 12px}.mabox-wrap .mabox-web{margin:0 20px 15px;text-align:left}.mabox-wrap .mabox-web-position{text-align:right}.mabox-wrap .mabox-web a{color:#ccc;text-decoration:none}.mabox-wrap .mabox-socials{position:relative;display:block;background:#fcfcfc;padding:5px;border-top:1px solid #eee}.mabox-wrap .mabox-socials a svg{width:20px;height:20px}.mabox-wrap .mabox-socials a svg .st2{fill:#fff; transform-origin:center center;}.mabox-wrap .mabox-socials a svg .st1{fill:rgba(0,0,0,.3)}.mabox-wrap .mabox-socials a:hover{opacity:.8;-webkit-transition:opacity .4s;-moz-transition:opacity .4s;-o-transition:opacity .4s;transition:opacity .4s;box-shadow:none!important;-webkit-box-shadow:none!important}.mabox-wrap .mabox-socials .mabox-icon-color{box-shadow:none;padding:0;border:0;-webkit-transition:opacity .4s;-moz-transition:opacity .4s;-o-transition:opacity .4s;transition:opacity .4s;display:inline-block;color:#fff;font-size:0;text-decoration:inherit;margin:5px;-webkit-border-radius:0;-moz-border-radius:0;-ms-border-radius:0;-o-border-radius:0;border-radius:0;overflow:hidden}.mabox-wrap .mabox-socials .mabox-icon-grey{text-decoration:inherit;box-shadow:none;position:relative;display:-moz-inline-stack;display:inline-block;vertical-align:middle;zoom:1;margin:10px 5px;color:#444;fill:#444}.clearfix:after,.clearfix:before{content:\' \';display:table;line-height:0;clear:both}.ie7 .clearfix{zoom:1}.mabox-socials.mabox-colored .mabox-icon-color .mabox-twitch{border-color:#38245c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-addthis{border-color:#e91c00}.mabox-socials.mabox-colored .mabox-icon-color .mabox-behance{border-color:#003eb0}.mabox-socials.mabox-colored .mabox-icon-color .mabox-delicious{border-color:#06c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-deviantart{border-color:#036824}.mabox-socials.mabox-colored .mabox-icon-color .mabox-digg{border-color:#00327c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-dribbble{border-color:#ba1655}.mabox-socials.mabox-colored .mabox-icon-color .mabox-facebook{border-color:#1e2e4f}.mabox-socials.mabox-colored .mabox-icon-color .mabox-flickr{border-color:#003576}.mabox-socials.mabox-colored .mabox-icon-color .mabox-github{border-color:#264874}.mabox-socials.mabox-colored .mabox-icon-color .mabox-phone{fill:#000000;}.mabox-socials.mabox-colored .mabox-icon-color .mabox-google{border-color:#0b51c5}.mabox-socials.mabox-colored .mabox-icon-color .mabox-googleplus{border-color:#96271a}.mabox-socials.mabox-colored .mabox-icon-color .mabox-html5{border-color:#902e13}.mabox-socials.mabox-colored .mabox-icon-color .mabox-instagram{border-color:#1630aa}.mabox-socials.mabox-colored .mabox-icon-color .mabox-linkedin{border-color:#00344f}.mabox-socials.mabox-colored .mabox-icon-color .mabox-pinterest{border-color:#5b040e}.mabox-socials.mabox-colored .mabox-icon-color .mabox-reddit{border-color:#992900}.mabox-socials.mabox-colored .mabox-icon-color .mabox-rss{border-color:#a43b0a}.mabox-socials.mabox-colored .mabox-icon-color .mabox-sharethis{border-color:#5d8420}.mabox-socials.mabox-colored .mabox-icon-color .mabox-skype{border-color:#00658a}.mabox-socials.mabox-colored .mabox-icon-color .mabox-soundcloud{border-color:#995200}.mabox-socials.mabox-colored .mabox-icon-color .mabox-spotify{border-color:#0f612c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-stackoverflow{border-color:#a95009}.mabox-socials.mabox-colored .mabox-icon-color .mabox-steam{border-color:#006388}.mabox-socials.mabox-colored .mabox-icon-color .mabox-user_email{border-color:#b84e05}.mabox-socials.mabox-colored .mabox-icon-color .mabox-stumbleUpon{border-color:#9b280e}.mabox-socials.mabox-colored .mabox-icon-color .mabox-tumblr{border-color:#10151b}.mabox-socials.mabox-colored .mabox-icon-color .mabox-twitter{border-color:#0967a0}.mabox-socials.mabox-colored .mabox-icon-color .mabox-vimeo{border-color:#0d7091}.mabox-socials.mabox-colored .mabox-icon-color .mabox-windows{border-color:#003f71}.mabox-socials.mabox-colored .mabox-icon-color .mabox-whatsapp{border-color:#003f71}.mabox-socials.mabox-colored .mabox-icon-color .mabox-wordpress{border-color:#0f3647}.mabox-socials.mabox-colored .mabox-icon-color .mabox-yahoo{border-color:#14002d}.mabox-socials.mabox-colored .mabox-icon-color .mabox-youtube{border-color:#900}.mabox-socials.mabox-colored .mabox-icon-color .mabox-xing{border-color:#000202}.mabox-socials.mabox-colored .mabox-icon-color .mabox-mixcloud{border-color:#2475a0}.mabox-socials.mabox-colored .mabox-icon-color .mabox-vk{border-color:#243549}.mabox-socials.mabox-colored .mabox-icon-color .mabox-medium{border-color:#00452c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-quora{border-color:#420e00}.mabox-socials.mabox-colored .mabox-icon-color .mabox-meetup{border-color:#9b181c}.mabox-socials.mabox-colored .mabox-icon-color .mabox-goodreads{border-color:#000}.mabox-socials.mabox-colored .mabox-icon-color .mabox-snapchat{border-color:#999700}.mabox-socials.mabox-colored .mabox-icon-color .mabox-500px{border-color:#00557f}.mabox-socials.mabox-colored .mabox-icon-color .mabox-mastodont{border-color:#185886}.mabox-plus-item{margin-bottom:20px}@media screen and (max-width:480px){.mabox-wrap{text-align:center}.mabox-wrap .mabox-gravatar{float:none;padding:20px 0;text-align:center;margin:0 auto;display:block}.mabox-wrap .mabox-gravatar img{float:none;display:inline-block;display:-moz-inline-stack;vertical-align:middle;zoom:1}.mabox-wrap .mabox-desc{margin:0 10px 20px;text-align:center}.mabox-wrap .mabox-authorname{text-align:center;margin:10px 0 20px}}body .mabox-authorname a,body .mabox-authorname a:hover{box-shadow:none;-webkit-box-shadow:none}a.mabox-profile-edit{font-size:16px!important;line-height:1!important}.mabox-edit-settings a,a.mabox-profile-edit{color:#0073aa!important;box-shadow:none!important;-webkit-box-shadow:none!important}.mabox-edit-settings{margin-right:15px;position:absolute;right:0;z-index:2;bottom:10px;line-height:20px}.mabox-edit-settings i{margin-left:5px}.mabox-socials{line-height:1!important}.rtl .mabox-wrap .mabox-gravatar{float:right}.rtl .mabox-wrap .mabox-authorname{display:flex;align-items:center}.rtl .mabox-wrap .mabox-authorname .mabox-profile-edit{margin-right:10px}.rtl .mabox-edit-settings{right:auto;left:0}img.mabox-custom-avatar{max-width:75px;}';

		//Template class
		$templete_class = ".mabox-template-$post_id ";

		// Border color of Author Box
		if ( '' != $mabox_options['mabox_box_border'] ) {
			$style .= $templete_class .'.mabox-wrap {border-color:' . esc_html( $mabox_options['mabox_box_border'] ) . ';}';
			$style .= $templete_class .'.mabox-wrap .mabox-socials {border-color:' . esc_html( $mabox_options['mabox_box_border'] ) . ';}';
		}
		// Border width of Author Box
		if ( '1' != $mabox_options['mabox_box_border_width'] ) {
			$style .= $templete_class .'.mabox-wrap, '. $templete_class .'.mabox-wrap .mabox-socials{ border-width: ' . esc_html( $mabox_options['mabox_box_border_width'] ) . 'px; }';
		}
		// Avatar image style
		if ( '0' != $mabox_options['mabox_avatar_style'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-gravatar img {-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;}';
		}
		// Social icons style
		if ( '0' != $mabox_options['mabox_colored'] && '0' != $mabox_options['mabox_icons_style'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-socials .mabox-icon-color {-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;}';
		}
		// Long Shadow
		if ( '1' == $mabox_options['mabox_colored'] && '1' != $mabox_options['mabox_box_long_shadow'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-socials .mabox-icon-color .st1 {display: none;}';
		}
		// Avatar hover effect
		if ( '0' != $mabox_options['mabox_avatar_style'] && '1' == $mabox_options['mabox_avatar_hover'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-gravatar img {-webkit-transition:all .5s ease;-moz-transition:all .5s ease;-o-transition:all .5s ease;transition:all .5s ease;}';
			$style .= $templete_class .'.mabox-wrap .mabox-gravatar img:hover {-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg);}';
		}
		// Social icons hover effect
		if ( '1' == $mabox_options['mabox_icons_style'] && '1' == $mabox_options['mabox_social_hover'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-socials .mabox-icon-color {-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;-o-transition: all 0.3s ease-in-out;-ms-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out;}.mabox-wrap .mabox-socials .mabox-icon-color:hover,.mabox-wrap .mabox-socials .mabox-icon-grey:hover {-webkit-transform: rotate(360deg);-moz-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);}';
		}
		// Thin border
		if ( '1' == $mabox_options['mabox_colored'] && '1' == $mabox_options['mabox_box_thin_border'] ) {
			$css = 'border-width: 1px;border-style:solid;';
			if ( '1' == $mabox_options['mabox_icons_style'] ) {
				$css .= 'border-radius:50%';
			}
			$style .= $templete_class .'.mabox-wrap .mabox-socials .mabox-icon-color svg {' . $css . '}';
		}
		// Background color of social icons bar
		if ( '' != $mabox_options['mabox_box_icons_back'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-socials{background-color:' . esc_html( $mabox_options['mabox_box_icons_back'] ) . ';}';
		}
		// Background color of author box
		if ( '' != $mabox_options['mabox_box_author_back'] ) {
			$style .= $templete_class .'.mabox-wrap {background-color:' . esc_html( $mabox_options['mabox_box_author_back'] ) . ';}';
		}
		// Color of author box paragraphs
		if ( '' != $mabox_options['mabox_box_author_p_color'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-desc p, '. $templete_class .'.mabox-wrap .mabox-desc  {color:' . esc_html( $mabox_options['mabox_box_author_p_color'] ) . ' !important;}';
		}
		// Color of author box paragraphs
		if ( '' != $mabox_options['mabox_box_author_a_color'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-desc a {color:' . esc_html( $mabox_options['mabox_box_author_a_color'] ) . ' !important;}';
		}
		// Color of social icons (for symbols only):
		if ( '' != $mabox_options['mabox_box_icons_color'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-socials .mabox-icon-grey {color:' . esc_html( $mabox_options['mabox_box_icons_color'] ) . '; fill:' . esc_html( $mabox_options['mabox_box_icons_color'] ) . ';}';
		}
		// Author name color
		if ( '' != $mabox_options['mabox_box_author_color'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-authorname a,'. $templete_class .'.mabox-wrap .mabox-authorname span {color:' . esc_html( $mabox_options['mabox_box_author_color'] ) . ';}';
		}

		// Author web color
		if ( '1' == $mabox_options['mabox_web'] && '' != $mabox_options['mabox_box_web_color'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-web a {color:' . esc_html( $mabox_options['mabox_box_web_color'] ) . ';}';
		}

		// Author name font family
		$mabox_box_name_font = self::get_template_option( $post_id, 'mabox_box_name_font' );
		if ( 'None' != $mabox_box_name_font ) {
			$style           .= $templete_class .'.mabox-wrap .mabox-authorname, '. $templete_class .'.mabox-wrap .mabox-authorname a {font-family:"' . esc_html( $mabox_box_name_font ) . '";}';
		}

		// Author description font family
		$mabox_box_desc_font = self::get_template_option( $post_id, 'mabox_box_desc_font' );
		if ( 'None' != $mabox_box_name_font ) {
			$style           .= $templete_class .'.mabox-wrap .mabox-desc, '.$templete_class .'.mabox-wrap .mabox-desc p {font-family:' . esc_html( $mabox_box_desc_font ) . ';}';
		}

		// Author web font family
		$mabox_box_web_font = self::get_template_option( $post_id, 'mabox_box_web_font' );
		if ( '1' == $mabox_options['mabox_web'] && 'None' != $mabox_box_web_font ) {
			$style          .= $templete_class .'.mabox-wrap .mabox-web {font-family:"' . esc_html( $mabox_box_web_font ) . '";}';
		}

		// Author description font style
		if ( isset( $mabox_options['mabox_desc_style'] ) && '1' == $mabox_options['mabox_desc_style'] ) {
			$style .= $templete_class .'.mabox-wrap .mabox-desc {font-style:italic;}';
		}
		// Margin top & bottom, Padding
		$style .= $templete_class .'.mabox-wrap {margin-top:' . absint( $mabox_top_margin ) . 'px !important; margin-bottom:' . absint( $mabox_bottom_margin ) . 'px !important; padding: ' . absint( $padding_top_bottom ) . 'px ' . absint( $padding_left_right ) . 'px }';
		// Author name text size
		$style .= $templete_class .'.mabox-wrap .mabox-authorname {font-size:' . absint( $mabox_name_size ) . 'px; line-height:' . absint( $mabox_name_size + 7 ) . 'px;}';
		// Author description font size
		$style .= $templete_class .'.mabox-wrap .mabox-desc p, '. $templete_class .'.mabox-wrap .mabox-desc {font-size:' . absint( $mabox_desc_size ) . 'px !important; line-height:' . absint( $mabox_desc_size + 7 ) . 'px !important;}';
		// Author website text size
		$style .= $templete_class .'.mabox-wrap .mabox-web {font-size:' . absint( $mabox_web_size ) . 'px;}';
		// Icons size
		$icon_size = absint( $mabox_icon_size );
		if ( '1' == $mabox_options['mabox_colored'] ) {
			$icon_size = $icon_size * 2;
		}
		$style .= $templete_class .'.mabox-wrap .mabox-socials a svg {width:' . absint( $icon_size ) . 'px;height:' . absint( $icon_size ) . 'px;}';

		//Add template's option style
		$styles['template'] = $style;

		return apply_filters( 'mabox_inline_styles', $styles );
	}
}