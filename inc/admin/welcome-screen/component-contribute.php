<?php
/**
 * Welcome screen contribute template
 *
 * @package store
 */

?>
	<div class="boxed customise">
		<h2><?php esc_html_e( 'Customise Store', 'store' ); ?></h2>
		<p><?php printf( esc_html__( 'We highly encourage you to make %s all yours. If you need to make a CSS tweak or add some custom JS or PHP, please use our Theme Customisations Plugin. Simply Click the button below, install as normal, and start Customizing today!', 'store' ), 'Store', 'WooCommerce' ); ?></p>

		<div class="more-button">
			<a href="https://github.com/woothemes/theme-customisations/archive/master.zip" class="button button-primary">
				<?php printf( esc_html__( 'Download Theme Customisations', 'store' ), 'Store' ); ?>
			</a>
		</div>
	</div>

	<div class="boxed contribute">
		<h2><?php esc_html_e( 'Contribute to Store', 'store' ); ?></h2>
		<p><?php printf( esc_html__( 'Found a bug? Want to contribute a patch or create a new feature? %sGitHub is the place to go!%s Or would you like to translate %s into your language? %sGet involved on WordPress.org%s.', 'store' ), '<a href="https://github.com/woothemes/store">', '</a>', 'Store', '<a href="https://translate.wordpress.org/projects/wp-themes/store">', '</a>' ); ?></p>
	</div>

	<div class="boxed suggest">
		<h2><?php esc_html_e( 'Suggest a feature', 'store' ); ?></h2>
		<p><?php printf( esc_html__( 'Please suggest and vote on ideas at the %s%s Ideas board%s. The most popular ideas will see prioritised development.', 'store' ), '<a href="http://ideas.woothemes.com/forums/275029-store">', 'Store', '</a>' ); ?></p>
	</div>

	<div class="boxed support">
		<h2><?php esc_html_e( 'Get support', 'store' ); ?></h2>
		<p><?php printf( esc_html__( 'You can find a wide range of information on how to use and customise %s in our %sdocumentation%s. If you\'re a customer you can get support in our %sHelpdesk%3$s. Otherwise you can try posting on the WordPress.org %ssupport forums%3$s.', 'store' ), 'Store', '<a href="https://docs.woothemes.com/documentation/themes/store/">', '</a>', '<a href="https://support.woothemes.com/">', '<a href="https://wordpress.org/support/theme/store">' ); ?></p>
	</div>
</div><!--/boxes-->

<div class="automattic">
	<p>
	<?php printf( esc_html__( 'An %s project', 'store' ), '<a href="https://automattic.com/"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/admin/welcome-screen/automattic.png" alt="Automattic" /></a>' ); ?>
	</p>
</div>
