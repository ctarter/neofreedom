<?php
/**
 * The 404 page template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

?>
	<div id="main" class="main section-404 container-720">
		<div class="container pt-5 pb-5">
			<main>
				<div id="layout" class="text-center">
					<div class="wrap-404">
						<h2 class="not_found mb-0 animate an__fadeInDown">
							<span class="has-huge-font-size"><?php esc_html_e( '404', 'exs' ); ?></span>
						</h2>

						<h5 class=" animate an__fadeInDown">
							<?php esc_html_e( 'Oops, page not found!', 'exs' ); ?>
						</h5>

						<p class="animate an__fadeInLeft">
							<?php esc_html_e( 'You can search what interested:', 'exs' ); ?>
						</p>

						<div class="widget widget_search mb-1 animate an__fadeInLeft">
							<?php get_search_form(); ?>
						</div>

						<p class="mb-1 animate an__fadeInUp">
							<?php esc_html_e( 'Or', 'exs' ); ?>
						</p>

						<p class="mb-0 animate an__fadeInUp">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
								<?php esc_html_e( 'Go To Home', 'exs' ); ?>
							</a>
						</p>
					</div>

				</div><!-- #layout -->
			</main>
		</div><!-- .container -->
	</div><!-- #main -->
<?php
get_footer();
