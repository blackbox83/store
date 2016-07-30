<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package store
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to store_page add_action
	 *
	 * @hooked store_page_header          - 10
	 * @hooked store_page_content         - 20
	 * @hooked store_init_structured_data - 30
	 */
	do_action( 'store_page' );
	?>
</article><!-- #post-## -->
