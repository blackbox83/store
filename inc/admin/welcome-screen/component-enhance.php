<?php
/**
 * Welcome screen enhance template
 *
 * @package store
 */

?>
<?php

/**
 * Get the extension data
 */
$store_admin 	= new Store_Admin();
$extensions 		= $store_admin::get_store_product_data( 'http://d3t0oesq8995hv.cloudfront.net/store-extensions.json', 'store_extensions' );
$child_themes 		= $store_admin::get_store_product_data( 'http://d3t0oesq8995hv.cloudfront.net/store-child-themes.json', 'store_child_themes' );
?>

	<div class="boxed enhance">
		<h2><?php printf( esc_html__( 'Enhance %s', 'store' ), 'Store' ); ?></h2>
		<p>
			<?php printf( esc_html__( 'Take a look at our range of extensions that extend and enhance %s functionality.', 'store' ), 'Store\'s' ); ?>
		</p>

		<ul class="extensions">
			<?php
			if ( $extensions ) {
				foreach ( $extensions as $extension ) {
					foreach ( $extension as $product ) {
						$price 	= $product->price;
						$title 	= str_replace( 'Store', '', $product->title );

						if ( '&#36;0.00' != $price ) {
							echo '<li><a href="' . esc_url( $product->link ) . '">' . esc_attr( $title ) . ' - <span class="price">' . esc_attr( $product->price ) . '</span></a><p>' . wp_kses_post( $product->excerpt ) . '</p></li>';
						}
					}
				}
			} else {
				echo '<div class="store-notice">' . esc_attr__( 'We\'re currently unable to retrieve these products. Please double check your internet connection or try back later.', 'store' ) . '</div>';
			}
			?>
		</ul>

		<div class="more-button">
			<a href="http://www.woothemes.com/product-category/store-extensions?utm_source=product&utm_medium=upsell&utm_campaign=storeaddons" class="button button-primary">
				<?php printf( esc_html__( 'View all %s extensions &rarr;', 'store' ), 'Store' ); ?>
			</a>
		</div>
	</div>

	<div class="boxed child-themes">
		<h2><?php esc_html_e( 'Child themes', 'store' ); ?></h2>
		<p><?php printf( esc_html__( 'Take a look at our range of child themes for %s that allow you to easily change the look of your store to suit a specific industry.', 'store' ), 'Store' ); ?></p>

		<?php
		if ( $child_themes ) {
			foreach ( $child_themes as $child_theme ) {
				foreach ( $child_theme as $product ) {
					$price 				= $product->price;

					if ( '&#36;0.00' == $price ) {
						$price = __( 'Free!', 'store' );
					}

					$link 				= $product->link;
					$image 				= $product->image;
					$excerpt			= $product->excerpt;
					$title 				= $product->title; ?>

					<div class="child-theme">
						<a href="<?php echo esc_url( $link ); ?>">
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
							<span class="child-theme-title"><?php echo esc_attr( $title ); ?></span>
							<span class="price"><?php echo esc_attr( $price ); ?></span>
						</a>
					</div>
				<?php
				}
			}
		} else {
			echo '<div class="store-notice">' . esc_attr__( 'We\'re currently unable to retrieve these products. Please double check your internet connection or try back later.', 'store' ) . '</div>';
		}
		?>
		<div class="more-button">
			<a href="http://www.woothemes.com/product-category/themes/store-child-theme-themes?utm_source=product&utm_medium=upsell&utm_campaign=storeaddons" class="button button-primary">
				<?php printf( esc_html__( 'View all %s child themes &rarr;', 'store' ), 'Store' ); ?>
			</a>
		</div>
	</div>

	<div class="boxed free-plugins">
		<h2><?php esc_html_e( 'Install free plugins', 'store' ); ?></h2>
		<p>
			<?php echo sprintf( esc_html__( 'There are a number of free plugins available for %s on the WordPress.org %splugin repository%s. Here\'s a few we made earlier:', 'store' ), 'Store', '<a href="https://wordpress.org/plugins/search.php?q=store">', '</a>' ); ?>
		</p>
		<ul class="extensions">
			<?php
			if ( $extensions ) {
				foreach ( $extensions as $extension ) {
					foreach ( $extension as $product ) {
						$price            = $product->price;
						$lower_case_title = strtolower( str_replace( ' ', '-', $product->title ) );
						$title            = str_replace( 'Store', '', $product->title );

						if ( '&#36;0.00' == $price ) {
							echo '<li><a class="thickbox" href="' . esc_url( wp_nonce_url( self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . esc_attr( $lower_case_title ) . '&TB_iframe=true&width=744&height=800' ), 'install-plugin_' . esc_attr( $lower_case_title ) ) ) . '">' . esc_attr( $title ) . ' - <span class="price">' . esc_attr__( 'Free!', 'store' ) . '</span></a><p>' . wp_kses_post( $product->excerpt ) . '</p></li>';
						}
					}
				}
			} else {
				echo '<div class="store-notice">' . esc_attr__( 'We\'re currently unable to retrieve these products. Please double check your internet connection or try back later.', 'store' ) . '</div>';
			}
			?>
		</ul>
	</div>
