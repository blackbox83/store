<?php
/**
 * Store hooks
 *
 * @package store
 */

/**
 * General
 *
 * @see  store_header_widget_region()
 * @see  store_get_sidebar()
 */
add_action( 'store_before_content', 'store_header_widget_region', 10 );
add_action( 'store_sidebar',        'store_get_sidebar',          10 );

/**
 * Header
 *
 * @see  store_skip_links()
 * @see  store_secondary_navigation()
 * @see  store_site_branding()
 * @see  store_primary_navigation()
 */
add_action( 'store_header', 'store_skip_links',                       0 );
add_action( 'store_header', 'store_site_branding',                    20 );
add_action( 'store_header', 'store_secondary_navigation',             30 );
add_action( 'store_header', 'store_primary_navigation_wrapper',       42 );
add_action( 'store_header', 'store_primary_navigation',               50 );
add_action( 'store_header', 'store_primary_navigation_wrapper_close', 68 );

/**
 * Footer
 *
 * @see  store_footer_widgets()
 * @see  store_credit()
 */
add_action( 'store_footer', 'store_footer_widgets', 10 );
add_action( 'store_footer', 'store_credit',         20 );

/**
 * Homepage
 *
 * @see  store_homepage_content()
 * @see  store_product_categories()
 * @see  store_recent_products()
 * @see  store_featured_products()
 * @see  store_popular_products()
 * @see  store_on_sale_products()
 * @see  store_best_selling_products()
 */
add_action( 'homepage', 'store_homepage_content',      10 );
add_action( 'homepage', 'store_product_categories',    20 );
add_action( 'homepage', 'store_recent_products',       30 );
add_action( 'homepage', 'store_featured_products',     40 );
add_action( 'homepage', 'store_popular_products',      50 );
add_action( 'homepage', 'store_on_sale_products',      60 );
add_action( 'homepage', 'store_best_selling_products', 70 );

/**
 * Posts
 *
 * @see  store_post_header()
 * @see  store_post_meta()
 * @see  store_post_content()
 * @see  store_init_structured_data()
 * @see  store_paging_nav()
 * @see  store_single_post_header()
 * @see  store_post_nav()
 * @see  store_display_comments()
 */
add_action( 'store_loop_post',         'store_post_header',          10 );
add_action( 'store_loop_post',         'store_post_meta',            20 );
add_action( 'store_loop_post',         'store_post_content',         30 );
add_action( 'store_loop_post',         'store_init_structured_data', 40 );
add_action( 'store_loop_after',        'store_paging_nav',           10 );
add_action( 'store_single_post',       'store_post_header',          10 );
add_action( 'store_single_post',       'store_post_meta',            20 );
add_action( 'store_single_post',       'store_post_content',         30 );
add_action( 'store_single_post',       'store_init_structured_data', 40 );
add_action( 'store_single_post_after', 'store_post_nav',             10 );
add_action( 'store_single_post_after', 'store_display_comments',     20 );

/**
 * Pages
 *
 * @see  store_page_header()
 * @see  store_page_content()
 * @see  store_init_structured_data()
 * @see  store_display_comments()
 */
add_action( 'store_page',       'store_page_header',          10 );
add_action( 'store_page',       'store_page_content',         20 );
add_action( 'store_page',       'store_init_structured_data', 30 );
add_action( 'store_page_after', 'store_display_comments',     10 );
