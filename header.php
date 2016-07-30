<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package store
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php
	do_action( 'store_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php store_header_styles(); ?>">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked into store_header action
			 *
			 * @hooked store_skip_links                       - 0
			 * @hooked store_social_icons                     - 10
			 * @hooked store_site_branding                    - 20
			 * @hooked store_secondary_navigation             - 30
			 * @hooked store_product_search                   - 40
			 * @hooked store_primary_navigation_wrapper       - 42
			 * @hooked store_primary_navigation               - 50
			 * @hooked store_header_cart                      - 60
			 * @hooked store_primary_navigation_wrapper_close - 68
			 */
			do_action( 'store_header' ); ?>

		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to store_before_content
	 *
	 * @hooked store_header_widget_region - 10
	 */
	do_action( 'store_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		/**
		 * Functions hooked in to store_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'store_content_top' );
