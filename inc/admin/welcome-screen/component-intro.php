h<?php
/**
 * Welcome screen intro template
 */

?>
<?php
global $store_version;
?>

<h1 class="sf-title"><?php echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/admin/welcome-screen/store.png" alt="Store" width="250" /> <sup class="version">' . esc_attr( $store_version ) . '</sup>'; ?></h1>

<section class="sf-review">
	<p><?php echo sprintf( esc_html__( '%sEnjoying %s?%s Why not %sleave a review%s on WordPress.org? We\'d really appreciate it!', 'store' ), '<strong>', 'Store', '</strong>', '<a href="https://wordpress.org/themes/store">', '</a>' ); ?></p>
</section>

<div class="boxes">
	<div class="boxed enrich">
		<h2><?php printf( esc_html__( 'Built to enrich your %s store', 'store' ), 'WooCommerce' ); ?></h2>

		<p><?php printf( esc_html__( 'Although %s works fine as a regular WordPress theme, it really shines when used for an online store. Install %s and start selling now.', 'store' ), 'Store', 'WooCommerce' ); ?></p>

		<?php if ( class_exists( 'WooCommerce' ) ) { ?>
			<p><span class="activated"><span class="dashicons dashicons-yes"></span> <?php printf( esc_html__( '%s is activated', 'store' ), 'WooCommerce' ); ?></span></p>
		<?php } else { ?>
			<p><a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' ) ); ?>" class="button button-primary"><?php printf( esc_html__( 'Install %s', 'store' ), 'WooCommerce' ); ?></a></p>
		<?php } ?>
	</div>

	<div class="boxed news">
		<h2><?php printf( esc_html__( 'Latest %s news', 'store' ), 'Store' ); ?></h2>
		<div class="col2-set">
			<div class="col-1 news">
				<h4><?php esc_html_e( 'Recent News', 'store' ); ?></h4>
				<?php
				$rss		= fetch_feed( 'https://woocommerce.com/blog/product-news/store/feed/' );
				$rss_items	= array();

				if ( ! is_wp_error( $rss ) ) {
					$maxitems 	= $rss->get_item_quantity( 1 );
					$rss_items 	= $rss->get_items( 0, $maxitems );
				}

				foreach ( $rss_items as $item ) : ?>
					<h5>
						<a href="<?php echo esc_url( $item->get_permalink() ); ?>">
							<?php echo esc_html( $item->get_title() ); ?>
						</a>
					</h5>
					<span class="date"><?php echo esc_attr( $item->get_date( 'j F Y' ) ); ?></span>
				<?php endforeach; ?>
			</div>
			<div class="col-2 docs">
				<h4><?php esc_html_e( 'Featured Docs', 'store' ); ?></h4>
				<ul>
					<li><a href="http://docs.woothemes.com/document/installation-configuration/"><?php esc_html_e( 'Installation &amp; configuration', 'store' ); ?></a></li>
					<li><a href="http://docs.woothemes.com/document/store-faq/"><?php esc_html_e( 'FAQ', 'store' ); ?></a></li>
					<li><a href="http://docs.woothemes.com/document/customizer-settings/"><?php esc_html_e( 'Customizer settings', 'store' ); ?></a></li>
				</ul>
			</div>
		</div>

		<hr />

		<p>
			<span class="dashicons dashicons-hammer"></span> <?php printf( esc_html__( 'Stay up to date with Store developments on the %sdev blog%s.', 'store' ), '<a href="https://store.wordpress.com/">', '</a>' ); ?>
		</p>
	</div>
