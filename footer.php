<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package store
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'store_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to store_footer action
			 *
			 * @hooked store_footer_widgets - 10
			 * @hooked store_credit         - 20
			 */
			do_action( 'store_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'store_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
