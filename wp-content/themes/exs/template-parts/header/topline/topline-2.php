<?php
/**
 * The header template file
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

$exs_fluid              = exs_option( 'topline_fluid' ) ? '-fluid' : '';
$exs_topline_background = exs_option( 'topline_background', '' );
$exs_font_size          = exs_option( 'topline_font_size', '' );
?>
<div id="topline" class="topline <?php echo esc_attr( $exs_topline_background . ' ' . $exs_font_size ); ?>">
	<div class="container<?php echo esc_attr( $exs_fluid ); ?>">
		<?php if ( has_nav_menu( 'topline' ) ) : ?>
			<div id="topline_dropdown" class="dropdown">
				<button id="topline_dropdown_toggle" class="nav-btn type-dots"
						aria-controls="topline_dropdown"
						aria-expanded="false"
						aria-label="<?php esc_attr_e( 'Topline Menu Toggler', 'exs' ); ?>"
				>
					<span></span>
				</button>
				<div class="dropdown-menu dropdown-menu-md topline-menu-dropdown">
					<nav class="topline-navigation" aria-label="<?php esc_attr_e( 'Topline Menu', 'exs' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'topline',
								'menu_class'     => 'topline-menu',
								'container'      => false,
								'depth'          => 1,
							)
						);
						?>
					</nav><!-- .topline-navigation -->
				</div><!-- .site-meta -->
			</div><!-- #topline_dropdown -->
			<?php
		endif; //has_nav_menu

		exs_social_links();

		?>
	</div><!-- .container -->
</div><!-- #topline -->
