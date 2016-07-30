<?php
/**
 * Store Customizer Class
 *
 * @author   WooThemes
 * @package  store
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Store_Customizer' ) ) :

	/**
	 * The Store Customizer class
	 */
	class Store_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'customize_preview_init',          array( $this, 'customize_preview_js' ), 10 );
			add_action( 'customize_register',              array( $this, 'customize_register' ), 10 );
			add_filter( 'body_class',                      array( $this, 'layout_class' ) );
			add_action( 'wp_enqueue_scripts',              array( $this, 'add_customizer_css' ), 130 );
			add_action( 'after_setup_theme',               array( $this, 'custom_header_setup' ) );
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_custom_control_css' ) );
			add_action( 'customize_register',              array( $this, 'edit_default_customizer_settings' ), 99 );
			add_action( 'init',                            array( $this, 'default_theme_mod_values' ), 10 );

			add_action( 'after_switch_theme',              array( $this, 'set_store_style_theme_mods' ) );
			add_action( 'customize_save_after',            array( $this, 'set_store_style_theme_mods' ) );
		}

		/**
		 * Returns an array of the desired default Store Options
		 *
		 * @return array
		 */
		public static function get_store_default_setting_values() {
			return apply_filters( 'store_setting_default_values', $args = array(
				'store_heading_color'               => '#484c51',
				'store_text_color'                  => '#43454b',
				'store_accent_color'                => '#96588a',
				'store_header_background_color'     => '#2c2d33',
				'store_header_text_color'           => '#9aa0a7',
				'store_header_link_color'           => '#d5d9db',
				'store_footer_background_color'     => '#f0f0f0',
				'store_footer_heading_color'        => '#494c50',
				'store_footer_text_color'           => '#61656b',
				'store_footer_link_color'           => '#2c2d33',
				'store_button_background_color'     => '#96588a',
				'store_button_text_color'           => '#ffffff',
				'store_button_alt_background_color' => '#2c2d33',
				'store_button_alt_text_color'       => '#ffffff',
				'store_layout'                      => 'right',
				'background_color'                       => '#ffffff',
			) );
		}

		/**
		 * Adds a value to each Store setting if one isn't already present.
		 *
		 * @uses get_store_default_setting_values()
		 * @return void
		 */
		public function default_theme_mod_values() {
			foreach ( Store_Customizer::get_store_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, function( $setting ) use ( $val ) {
					return $setting ? $setting : $val;
				}, 10 );
			}
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter store_setting_default_values
		 *
		 * @param  array $wp_customize the Customizer object.
		 * @uses   get_store_default_setting_values()
		 * @return void
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( Store_Customizer::get_store_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}

		/**
		 * Setup the WordPress core custom header feature.
		 *
		 * @uses store_header_style()
		 * @uses store_admin_header_style()
		 * @uses store_admin_header_image()
		 */
		public function custom_header_setup() {
			add_theme_support( 'custom-header', apply_filters( 'store_custom_header_args', array(
				'default-image' => '',
				'header-text'   => false,
				'width'         => 1950,
				'height'        => 500,
				'flex-width'    => true,
				'flex-height'   => true,
			) ) );
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since  1.0.0
		 */
		public function customize_register( $wp_customize ) {
			$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
			$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

			// Move background color setting alongside background image.
			$wp_customize->get_control( 'background_color' )->section   = 'background_image';
			$wp_customize->get_control( 'background_color' )->priority  = 20;

			// Change background image section title & priority.
			$wp_customize->get_section( 'background_image' )->title     = __( 'Background', 'store' );
			$wp_customize->get_section( 'background_image' )->priority  = 30;

			// Change header image section title & priority.
			$wp_customize->get_section( 'header_image' )->title         = __( 'Header', 'store' );
			$wp_customize->get_section( 'header_image' )->priority      = 25;

			/**
			 * Custom controls
			 */
			require_once dirname( __FILE__ ) . '/class-store-customizer-control-radio-image.php';
			require_once dirname( __FILE__ ) . '/class-store-customizer-control-arbitrary.php';

			if ( apply_filters( 'store_customizer_more', true ) ) {
				require_once dirname( __FILE__ ) . '/class-store-customizer-control-more.php';
			}

			/**
			 * Add the typography section
		     */
			$wp_customize->add_section( 'store_typography' , array(
				'title'      			=> __( 'Typography', 'store' ),
				'priority'   			=> 45,
			) );

			/**
			 * Heading color
			 */
			$wp_customize->add_setting( 'store_heading_color', array(
				'default'           	=> apply_filters( 'store_default_heading_color', '#484c51' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_heading_color', array(
				'label'	   				=> __( 'Heading color', 'store' ),
				'section'  				=> 'store_typography',
				'settings' 				=> 'store_heading_color',
				'priority' 				=> 20,
			) ) );

			/**
			 * Text Color
			 */
			$wp_customize->add_setting( 'store_text_color', array(
				'default'           	=> apply_filters( 'store_default_text_color', '#43454b' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_text_color', array(
				'label'					=> __( 'Text color', 'store' ),
				'section'				=> 'store_typography',
				'settings'				=> 'store_text_color',
				'priority'				=> 30,
			) ) );

			/**
			 * Accent Color
			 */
			$wp_customize->add_setting( 'store_accent_color', array(
				'default'           	=> apply_filters( 'store_default_accent_color', '#96588a' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_accent_color', array(
				'label'	   				=> __( 'Link / accent color', 'store' ),
				'section'  				=> 'store_typography',
				'settings' 				=> 'store_accent_color',
				'priority' 				=> 40,
			) ) );

			$wp_customize->add_control( new Arbitrary_Store_Control( $wp_customize, 'store_header_image_heading', array(
				'section'  				=> 'header_image',
				'type' 					=> 'heading',
				'label'					=> __( 'Header background image', 'store' ),
				'priority' 				=> 6,
			) ) );

			/**
			 * Header Background
			 */
			$wp_customize->add_setting( 'store_header_background_color', array(
				'default'           	=> apply_filters( 'store_default_header_background_color', '#2c2d33' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_header_background_color', array(
				'label'	   				=> __( 'Background color', 'store' ),
				'section'  				=> 'header_image',
				'settings' 				=> 'store_header_background_color',
				'priority' 				=> 15,
			) ) );

			/**
			 * Header text color
			 */
			$wp_customize->add_setting( 'store_header_text_color', array(
				'default'           	=> apply_filters( 'store_default_header_text_color', '#9aa0a7' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_header_text_color', array(
				'label'	   				=> __( 'Text color', 'store' ),
				'section'  				=> 'header_image',
				'settings' 				=> 'store_header_text_color',
				'priority' 				=> 20,
			) ) );

			/**
			 * Header link color
			 */
			$wp_customize->add_setting( 'store_header_link_color', array(
				'default'           	=> apply_filters( 'store_default_header_link_color', '#d5d9db' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_header_link_color', array(
				'label'	   				=> __( 'Link color', 'store' ),
				'section'  				=> 'header_image',
				'settings' 				=> 'store_header_link_color',
				'priority' 				=> 30,
			) ) );

			/**
			 * Footer section
			 */
			$wp_customize->add_section( 'store_footer' , array(
				'title'      			=> __( 'Footer', 'store' ),
				'priority'   			=> 28,
				'description' 			=> __( 'Customise the look & feel of your web site footer.', 'store' ),
			) );

			/**
			 * Footer Background
			 */
			$wp_customize->add_setting( 'store_footer_background_color', array(
				'default'           	=> apply_filters( 'store_default_footer_background_color', '#f0f0f0' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_footer_background_color', array(
				'label'	   				=> __( 'Background color', 'store' ),
				'section'  				=> 'store_footer',
				'settings' 				=> 'store_footer_background_color',
				'priority'				=> 10,
			) ) );

			/**
			 * Footer heading color
			 */
			$wp_customize->add_setting( 'store_footer_heading_color', array(
				'default'           	=> apply_filters( 'store_default_footer_heading_color', '#494c50' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport' 			=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_footer_heading_color', array(
				'label'	   				=> __( 'Heading color', 'store' ),
				'section'  				=> 'store_footer',
				'settings' 				=> 'store_footer_heading_color',
				'priority'				=> 20,
			) ) );

			/**
			 * Footer text color
			 */
			$wp_customize->add_setting( 'store_footer_text_color', array(
				'default'           	=> apply_filters( 'store_default_footer_text_color', '#61656b' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_footer_text_color', array(
				'label'	   				=> __( 'Text color', 'store' ),
				'section'  				=> 'store_footer',
				'settings' 				=> 'store_footer_text_color',
				'priority'				=> 30,
			) ) );

			/**
			 * Footer link color
			 */
			$wp_customize->add_setting( 'store_footer_link_color', array(
				'default'           	=> apply_filters( 'store_default_footer_link_color', '#2c2d33' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
				'transport'				=> 'postMessage',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_footer_link_color', array(
				'label'	   				=> __( 'Link color', 'store' ),
				'section'  				=> 'store_footer',
				'settings' 				=> 'store_footer_link_color',
				'priority'				=> 40,
			) ) );

			/**
			 * Buttons section
			 */
			$wp_customize->add_section( 'store_buttons' , array(
				'title'      			=> __( 'Buttons', 'store' ),
				'priority'   			=> 45,
				'description' 			=> __( 'Customise the look & feel of your web site buttons.', 'store' ),
			) );

			/**
			 * Button background color
			 */
			$wp_customize->add_setting( 'store_button_background_color', array(
				'default'           	=> apply_filters( 'store_default_button_background_color', '#96588a' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_button_background_color', array(
				'label'	   				=> __( 'Background color', 'store' ),
				'section'  				=> 'store_buttons',
				'settings' 				=> 'store_button_background_color',
				'priority' 				=> 10,
			) ) );

			/**
			 * Button text color
			 */
			$wp_customize->add_setting( 'store_button_text_color', array(
				'default'           	=> apply_filters( 'store_default_button_text_color', '#ffffff' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_button_text_color', array(
				'label'	   				=> __( 'Text color', 'store' ),
				'section'  				=> 'store_buttons',
				'settings' 				=> 'store_button_text_color',
				'priority' 				=> 20,
			) ) );

			/**
			 * Button alt background color
			 */
			$wp_customize->add_setting( 'store_button_alt_background_color', array(
				'default'           	=> apply_filters( 'store_default_button_alt_background_color', '#2c2d33' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_button_alt_background_color', array(
				'label'	   				=> __( 'Alternate button background color', 'store' ),
				'section'  				=> 'store_buttons',
				'settings' 				=> 'store_button_alt_background_color',
				'priority' 				=> 30,
			) ) );

			/**
			 * Button alt text color
			 */
			$wp_customize->add_setting( 'store_button_alt_text_color', array(
				'default'           	=> apply_filters( 'store_default_button_alt_text_color', '#ffffff' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'store_button_alt_text_color', array(
				'label'	   				=> __( 'Alternate button text color', 'store' ),
				'section'  				=> 'store_buttons',
				'settings' 				=> 'store_button_alt_text_color',
				'priority' 				=> 40,
			) ) );

			/**
			 * Layout
			 */
			$wp_customize->add_section( 'store_layout' , array(
				'title'      			=> __( 'Layout', 'store' ),
				'priority'   			=> 50,
			) );

			$wp_customize->add_setting( 'store_layout', array(
				'default'    			=> apply_filters( 'store_default_layout', $layout = is_rtl() ? 'left' : 'right' ),
				'sanitize_callback' 	=> 'store_sanitize_choices',
			) );

			$wp_customize->add_control( new Store_Custom_Radio_Image_Control( $wp_customize, 'store_layout', array(
				'settings'				=> 'store_layout',
				'section'				=> 'store_layout',
				'label'					=> __( 'General Layout', 'store' ),
				'priority'				=> 1,
				'choices'				=> array(
											'right' => get_template_directory_uri() . '/assets/images/customizer/controls/2cr.png',
											'left'  => get_template_directory_uri() . '/assets/images/customizer/controls/2cl.png',
				),
			) ) );

			/**
			 * More
			 */
			if ( apply_filters( 'store_customizer_more', true ) ) {
				$wp_customize->add_section( 'store_more' , array(
					'title'      		=> __( 'More', 'store' ),
					'priority'   		=> 999,
				) );

				$wp_customize->add_setting( 'store_more', array(
					'default'    		=> null,
					'sanitize_callback' => 'sanitize_text_field',
				) );

				$wp_customize->add_control( new More_Store_Control( $wp_customize, 'store_more', array(
					'label'    			=> __( 'Looking for more options?', 'store' ),
					'section'  			=> 'store_more',
					'settings' 			=> 'store_more',
					'priority' 			=> 1,
				) ) );
			}
		}

		/**
		 * Get all of the Store theme mods.
		 *
		 * @return array $store_theme_mods The Store Theme Mods.
		 */
		public function get_store_theme_mods() {
			$store_theme_mods = array(
				'background_color'            => store_get_content_background_color(),
				'accent_color'                => get_theme_mod( 'store_accent_color' ),
				'header_background_color'     => get_theme_mod( 'store_header_background_color' ),
				'header_link_color'           => get_theme_mod( 'store_header_link_color' ),
				'header_text_color'           => get_theme_mod( 'store_header_text_color' ),
				'footer_background_color'     => get_theme_mod( 'store_footer_background_color' ),
				'footer_link_color'           => get_theme_mod( 'store_footer_link_color' ),
				'footer_heading_color'        => get_theme_mod( 'store_footer_heading_color' ),
				'footer_text_color'           => get_theme_mod( 'store_footer_text_color' ),
				'text_color'                  => get_theme_mod( 'store_text_color' ),
				'heading_color'               => get_theme_mod( 'store_heading_color' ),
				'button_background_color'     => get_theme_mod( 'store_button_background_color' ),
				'button_text_color'           => get_theme_mod( 'store_button_text_color' ),
				'button_alt_background_color' => get_theme_mod( 'store_button_alt_background_color' ),
				'button_alt_text_color'       => get_theme_mod( 'store_button_alt_text_color' ),
			);

			return apply_filters( 'store_theme_mods', $store_theme_mods );
		}

		/**
		 * Get Customizer css.
		 *
		 * @see get_store_theme_mods()
		 * @return array $styles the css
		 */
		public function get_css() {
			$store_theme_mods = $this->get_store_theme_mods();
			$brighten_factor       = apply_filters( 'store_brighten_factor', 25 );
			$darken_factor         = apply_filters( 'store_darken_factor', -25 );

			$styles                = '
			.main-navigation ul li a,
			.site-title a,
			ul.menu li a,
			.site-branding h1 a,
			.site-footer .store-handheld-footer-bar a:not(.button),
			button.menu-toggle,
			button.menu-toggle:hover {
				color: ' . $store_theme_mods['header_link_color'] . ';
			}

			button.menu-toggle,
			button.menu-toggle:hover {
				border-color: ' . $store_theme_mods['header_link_color'] . ';
			}

			.main-navigation ul li a:hover,
			.main-navigation ul li:hover > a,
			.site-title a:hover,
			a.cart-contents:hover,
			.site-header-cart .widget_shopping_cart a:hover,
			.site-header-cart:hover > li > a,
			.site-header ul.menu li.current-menu-item > a {
				color: ' . store_adjust_color_brightness( $store_theme_mods['header_link_color'], 50 ) . ';
			}

			table th {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -7 ) . ';
			}

			table tbody td {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -2 ) . ';
			}

			table tbody tr:nth-child(2n) td {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -4 ) . ';
			}

			.site-header,
			.secondary-navigation ul ul,
			.main-navigation ul.menu > li.menu-item-has-children:after,
			.secondary-navigation ul.menu ul,
			.store-handheld-footer-bar,
			.store-handheld-footer-bar ul li > a,
			.store-handheld-footer-bar ul li.search .site-search,
			button.menu-toggle,
			button.menu-toggle:hover {
				background-color: ' . $store_theme_mods['header_background_color'] . ';
			}

			p.site-description,
			.site-header,
			.store-handheld-footer-bar {
				color: ' . $store_theme_mods['header_text_color'] . ';
			}

			.store-handheld-footer-bar ul li.cart .count,
			button.menu-toggle:after,
			button.menu-toggle:before,
			button.menu-toggle span:before {
				background-color: ' . $store_theme_mods['header_link_color'] . ';
			}

			.store-handheld-footer-bar ul li.cart .count {
				color: ' . $store_theme_mods['header_background_color'] . ';
			}

			.store-handheld-footer-bar ul li.cart .count {
				border-color: ' . $store_theme_mods['header_background_color'] . ';
			}

			h1, h2, h3, h4, h5, h6 {
				color: ' . $store_theme_mods['heading_color'] . ';
			}

			.widget h1 {
				border-bottom-color: ' . $store_theme_mods['heading_color'] . ';
			}

			body,
			.secondary-navigation a,
			.onsale,
			.pagination .page-numbers li .page-numbers:not(.current), .woocommerce-pagination .page-numbers li .page-numbers:not(.current) {
				color: ' . $store_theme_mods['text_color'] . ';
			}

			.widget-area .widget a,
			.hentry .entry-header .posted-on a,
			.hentry .entry-header .byline a {
				color: ' . store_adjust_color_brightness( $store_theme_mods['text_color'], 50 ) . ';
			}

			a  {
				color: ' . $store_theme_mods['accent_color'] . ';
			}

			a:focus,
			.button:focus,
			.button.alt:focus,
			.button.added_to_cart:focus,
			.button.wc-forward:focus,
			button:focus,
			input[type="button"]:focus,
			input[type="reset"]:focus,
			input[type="submit"]:focus {
				outline-color: ' . $store_theme_mods['accent_color'] . ';
			}

			button, input[type="button"], input[type="reset"], input[type="submit"], .button, .added_to_cart, .widget a.button, .site-header-cart .widget_shopping_cart a.button {
				background-color: ' . $store_theme_mods['button_background_color'] . ';
				border-color: ' . $store_theme_mods['button_background_color'] . ';
				color: ' . $store_theme_mods['button_text_color'] . ';
			}

			button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, .button:hover, .added_to_cart:hover, .widget a.button:hover, .site-header-cart .widget_shopping_cart a.button:hover {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['button_background_color'], $darken_factor ) . ';
				border-color: ' . store_adjust_color_brightness( $store_theme_mods['button_background_color'], $darken_factor ) . ';
				color: ' . $store_theme_mods['button_text_color'] . ';
			}

			button.alt, input[type="button"].alt, input[type="reset"].alt, input[type="submit"].alt, .button.alt, .added_to_cart.alt, .widget-area .widget a.button.alt, .added_to_cart, .pagination .page-numbers li .page-numbers.current, .woocommerce-pagination .page-numbers li .page-numbers.current, .widget a.button.checkout {
				background-color: ' . $store_theme_mods['button_alt_background_color'] . ';
				border-color: ' . $store_theme_mods['button_alt_background_color'] . ';
				color: ' . $store_theme_mods['button_alt_text_color'] . ';
			}

			button.alt:hover, input[type="button"].alt:hover, input[type="reset"].alt:hover, input[type="submit"].alt:hover, .button.alt:hover, .added_to_cart.alt:hover, .widget-area .widget a.button.alt:hover, .added_to_cart:hover, .widget a.button.checkout:hover {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['button_alt_background_color'], $darken_factor ) . ';
				border-color: ' . store_adjust_color_brightness( $store_theme_mods['button_alt_background_color'], $darken_factor ) . ';
				color: ' . $store_theme_mods['button_alt_text_color'] . ';
			}

			#comments .comment-list .comment-content .comment-text {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -7 ) . ';
			}

			.site-footer {
				background-color: ' . $store_theme_mods['footer_background_color'] . ';
				color: ' . $store_theme_mods['footer_text_color'] . ';
			}

			.site-footer a:not(.button) {
				color: ' . $store_theme_mods['footer_link_color'] . ';
			}

			.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6 {
				color: ' . $store_theme_mods['footer_heading_color'] . ';
			}

			#order_review,
			#payment .payment_methods > li .payment_box {
				background-color: ' . $store_theme_mods['background_color'] . ';
			}

			#payment .payment_methods > li {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -5 ) . ';
			}

			#payment .payment_methods > li:hover {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -10 ) . ';
			}

			@media screen and ( min-width: 768px ) {
				.secondary-navigation ul.menu a:hover {
					color: ' . store_adjust_color_brightness( $store_theme_mods['header_text_color'], $brighten_factor ) . ';
				}

				.secondary-navigation ul.menu a {
					color: ' . $store_theme_mods['header_text_color'] . ';
				}

				.site-header-cart .widget_shopping_cart,
				.main-navigation ul.menu ul.sub-menu,
				.main-navigation ul.nav-menu ul.children {
					background-color: ' . store_adjust_color_brightness( $store_theme_mods['header_background_color'], -8 ) . ';
				}
			}';

			return apply_filters( 'store_customizer_css', $styles );
		}

		/**
		 * Get Customizer css associated with WooCommerce.
		 *
		 * @see get_store_theme_mods()
		 * @return array $woocommerce_styles the WooCommerce css
		 */
		public function get_woocommerce_css() {
			$store_theme_mods = $this->get_store_theme_mods();
			$brighten_factor       = apply_filters( 'store_brighten_factor', 25 );
			$darken_factor         = apply_filters( 'store_darken_factor', -25 );

			$woocommerce_styles    = '
			a.cart-contents,
			.site-header-cart .widget_shopping_cart a {
				color: ' . $store_theme_mods['header_link_color'] . ';
			}

			table.cart td.product-remove,
			table.cart td.actions {
				border-top-color: ' . $store_theme_mods['background_color'] . ';
			}

			.woocommerce-tabs ul.tabs li.active a,
			ul.products li.product .price,
			.onsale,
			.widget_search form:before,
			.widget_product_search form:before {
				color: ' . $store_theme_mods['text_color'] . ';
			}

			.woocommerce-breadcrumb a,
			a.woocommerce-review-link,
			.product_meta a {
				color: ' . store_adjust_color_brightness( $store_theme_mods['text_color'], 50 ) . ';
			}

			.onsale {
				border-color: ' . $store_theme_mods['text_color'] . ';
			}

			.star-rating span:before,
			.quantity .plus, .quantity .minus,
			p.stars a:hover:after,
			p.stars a:after,
			.star-rating span:before,
			#payment .payment_methods li input[type=radio]:first-child:checked+label:before {
				color: ' . $store_theme_mods['accent_color'] . ';
			}

			.widget_price_filter .ui-slider .ui-slider-range,
			.widget_price_filter .ui-slider .ui-slider-handle {
				background-color: ' . $store_theme_mods['accent_color'] . ';
			}

			.woocommerce-breadcrumb,
			#reviews .commentlist li .comment_container {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -7 ) . ';
			}

			.order_details {
				background-color: ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -7 ) . ';
			}

			.order_details li {
				border-bottom: 1px dotted ' . store_adjust_color_brightness( $store_theme_mods['background_color'], -28 ) . ';
			}

			.order_details:before,
			.order_details:after {
				background: -webkit-linear-gradient(transparent 0,transparent 0),-webkit-linear-gradient(135deg,' . store_adjust_color_brightness( $store_theme_mods['background_color'], -7 ) . ' 33.33%,transparent 33.33%),-webkit-linear-gradient(45deg,' . store_adjust_color_brightness( $store_theme_mods['background_color'], -7 ) . ' 33.33%,transparent 33.33%)
			}

			@media screen and ( min-width: 768px ) {
				.site-header-cart .widget_shopping_cart,
				.site-header .product_list_widget li .quantity {
					color: ' . $store_theme_mods['header_text_color'] . ';
				}
			}';

			return apply_filters( 'store_customizer_woocommerce_css', $woocommerce_styles );
		}

		/**
		 * Assign Store styles to individual theme mods.
		 *
		 * @return void
		 */
		public function set_store_style_theme_mods() {
			set_theme_mod( 'store_styles', $this->get_css() );
			set_theme_mod( 'store_woocommerce_styles', $this->get_woocommerce_css() );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {
			$store_styles             = get_theme_mod( 'store_styles' );
			$store_woocommerce_styles = get_theme_mod( 'store_woocommerce_styles' );

			if ( is_customize_preview() || ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) || ( false === $store_styles && false === $store_woocommerce_styles ) ) {
				wp_add_inline_style( 'store-style', $this->get_css() );
				wp_add_inline_style( 'store-woocommerce-style', $this->get_woocommerce_css() );
			} else {
				wp_add_inline_style( 'store-style', get_theme_mod( 'store_styles' ) );
				wp_add_inline_style( 'store-woocommerce-style', get_theme_mod( 'store_woocommerce_styles' ) );
			}
		}

		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 *
		 * @since  1.0.0
		 */
		public function customize_preview_js() {
			wp_enqueue_script( 'store-customizer', get_template_directory_uri() . '/assets/js/customizer/customizer.min.js', array( 'customize-preview' ), '1.16', true );
		}

		/**
		 * Layout classes
		 * Adds 'right-sidebar' and 'left-sidebar' classes to the body tag
		 *
		 * @param  array $classes current body classes.
		 * @return string[]          modified body classes
		 * @since  1.0.0
		 */
		public function layout_class( $classes ) {
			$left_or_right = get_theme_mod( 'store_layout' );

			$classes[] = $left_or_right . '-sidebar';

			return $classes;
		}

		/**
		 * Add CSS for custom controls
		 *
		 * This function incorporates CSS from the Kirki Customizer Framework
		 *
		 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
		 * is licensed under the terms of the GNU GPL, Version 2 (or later)
		 *
		 * @link https://github.com/reduxframework/kirki/
		 * @since  1.5.0
		 */
		public function customizer_custom_control_css() {
			?>
			<style>
			.customize-control-radio-image .image.ui-buttonset input[type=radio] {
				height: auto;
			}

			.customize-control-radio-image .image.ui-buttonset label {
				display: inline-block;
				width: 48%;
				padding: 1%;
				box-sizing: border-box;
			}

			.customize-control-radio-image .image.ui-buttonset label.ui-state-active {
				background: none;
			}

			.customize-control-radio-image .customize-control-radio-buttonset label {
				background: #f7f7f7;
				line-height: 35px;
			}

			.customize-control-radio-image label img {
				opacity: 0.5;
			}

			#customize-controls .customize-control-radio-image label img {
				height: auto;
			}

			.customize-control-radio-image label.ui-state-active img {
				background: #dedede;
				opacity: 1;
			}

			.customize-control-radio-image label.ui-state-hover img {
				opacity: 1;
				box-shadow: 0 0 0 3px #f6f6f6;
			}
			</style>
			<?php
		}
	}

endif;

return new Store_Customizer();
