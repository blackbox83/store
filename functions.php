<?php
/**
 * @package store
 */

/**
 * Assign the store version to a var
 */
$theme = wp_get_theme('store');
$store_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

/**
 * Initialize all the things.
 */
require 'inc/class-store.php';
require 'inc/jetpack/class-store-jetpack.php';
require 'inc/customizer/class-store-customizer.php';

require 'inc/store-functions.php';
require 'inc/store-template-hooks.php';
require 'inc/store-template-functions.php';

if ( is_woocommerce_activated() ) {
 	require 'inc/woocommerce/class-store-woocommerce.php';
 	require 'inc/woocommerce/store-woocommerce-template-hooks.php';
 	require 'inc/woocommerce/store-woocommerce-template-functions.php';
}

if ( is_admin() ) {
 	require 'inc/admin/class-store-admin.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woothemes/theme-customisations
 */
