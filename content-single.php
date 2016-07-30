<?php
/**
 * Template used to display post content on single pages.
 *
 * @package store
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked into store_single_post add_action
	 *
	 * @hooked store_post_header          - 10
	 * @hooked store_post_meta            - 20
	 * @hooked store_post_content         - 30
	 * @hooked store_init_structured_data - 40
	 */
	do_action( 'store_single_post' );
	?>

</article><!-- #post-## -->
