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
//options
$exs_fluid             = exs_option( 'header_fluid' ) ? '-fluid' : '';
$exs_transparent       = exs_option( 'header_transparent' ) ? 'transparent' : '';
$exs_absolute          = exs_option( 'header_absolute' ) ? 'absolute' : '';
$exs_header_top_tall   = exs_option( 'header_top_tall' ) ? 'header-tall' : '';
$exs_sticky            = exs_option( 'header_sticky' );
$exs_search            = exs_option( 'header_search' );
$exs_button_url        = exs_option( 'header_button_url' );
$exs_button_text       = exs_option( 'header_button_text' );
$exs_header_background = exs_option( 'header_background', '' );
$exs_header_toplogo_bg = exs_option( 'header_toplogo_background', 'l' );
$exs_font_size         = exs_option( 'header_font_size', '' );

$exs_border_top    = exs_option( 'header_border_top', '' );
$exs_border_bottom = exs_option( 'header_border_bottom', '' );

//meta
$exs_phone = exs_option( 'meta_phone' );
$exs_email = exs_option( 'meta_email' );

$exs_toggler_side_in_header = exs_option( 'header_toggler_menu_side', true );
$exs_toggler_main_in_header = exs_option( 'header_toggler_menu_main', true );

$exs_header_align_main_menu = exs_option( 'header_align_main_menu', '' );
?>
<div id="toplogo" class="toplogo <?php echo esc_attr( $exs_font_size . ' ' . $exs_header_toplogo_bg . ' ' . $exs_header_top_tall ); ?>">
	<div class="container<?php echo esc_attr( $exs_fluid ); ?> container-md-flex">
		<?php
			get_template_part( 'template-parts/header/logo/logo', exs_template_part( 'logo', '1' ) );

		if ( ! empty( $exs_phone ) ) :
			?>
			<span class="icon-inline">
				<?php exs_icon( 'phone-outline' ); ?>
				<span><?php echo wp_kses_post( $exs_phone ); ?></span>
			</span>
			<?php
		endif;
		if ( ! empty( $exs_email ) ) :
			?>
			<span class="icon-inline">
			<?php exs_icon( 'email-outline' ); ?>
				<a href="mailto:<?php echo esc_attr( $exs_email ); ?>"><?php echo wp_kses_post( $exs_email ); ?></a>
			</span>
			<?php
		endif;
		if ( 'button' === $exs_search ) :
			?>
		<div class="search-social-wrap d-flex">
		<?php else : ?>
		<div class="search-social-wrap flex-column">
			<?php
		endif; //button
		if ( ! empty( $exs_search ) ) :
			get_template_part( 'template-parts/header/header-search' );
		endif;

		if ( class_exists( 'WooCommerce' ) ) :
			get_template_part( 'template-parts/header/cart-dropdown' );
		endif; //woocommerce
		?>
			<div class="social-links-wrap">
				<?php exs_social_links(); ?>
			</div><!-- .social-links-wrap -->
		</div><!-- .search-social-wrap -->
	</div><!-- .container -->
</div><!-- #toplogo -->
<?php if ( ! empty( $exs_sticky ) ) : ?>
<div id="header-affix-wrap" class="header-wrap <?php echo esc_attr( $exs_header_background . ' ' . $exs_transparent . ' ' . $exs_absolute ); ?>">
	<?php endif; ?>
	<header id="header" class="header header-3 no-logo <?php echo esc_attr( $exs_header_background . ' ' . $exs_header_align_main_menu . ' ' . $exs_font_size . ' ' . $exs_sticky . ' ' . $exs_transparent . ' ' . $exs_absolute ); ?>">
		<?php
		if ( 'full' === $exs_border_top ) {
			echo wp_kses_post( '<hr class="section-hr">' );
		}
		if ( 'container' === $exs_border_top ) {
			echo wp_kses_post( '<hr class="section-hr container">' );
		}
		?>
		<div class="container<?php echo esc_attr( $exs_fluid ); ?>">
			<?php if ( ! empty( $exs_toggler_side_in_header ) && ( has_nav_menu( 'side' ) || is_active_sidebar( 'sidebar-side' ) ) ) : ?>
				<button id="nav_side_toggle" class="nav-btn"
						aria-controls="nav_side"
						aria-expanded="false"
						aria-label="<?php esc_attr_e( 'Side Menu Toggler', 'exs' ); ?>"
				>
					<span></span>
				</button>
				<?php
			endif; //toggler_side_in_header

			//this logo will be visible only on small screens
			get_template_part( 'template-parts/header/logo/logo', exs_template_part( 'logo', '1' ) );

			?>
			<div id="overlay"></div>
			<?php
			if ( has_nav_menu( 'primary' ) ) :
				?>
				<nav id="nav_top" class="top-nav" aria-label="<?php esc_attr_e( 'Top Menu', 'exs' ); ?>">
					<?php
					$exs_menu_css_class = exs_get_menu_class_based_on_top_items_count( 'primary' );
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'top-menu ' . $exs_menu_css_class,
							'container'      => false,
						)
					);
					if ( empty( $exs_toggler_main_in_header ) ) :
						?>
						<button id="nav_toggle" class="nav-btn"
								aria-controls="nav_top"
								aria-expanded="false"
								aria-label="<?php esc_attr_e( 'Top Menu Toggler', 'exs' ); ?>"
						>
							<span></span>
						</button>
						<?php
							//echo closing button if main button is inside header section
							else :
								?>
						<button id="nav_close" class="nav-btn"
								aria-controls="nav_top"
								aria-expanded="true"
								aria-label="<?php esc_attr_e( 'Top Menu Close', 'exs' ); ?>"
						>
							<span></span>
						</button>
					<?php endif; //toggler_main_in_header ?>
				</nav><!-- .top-nav -->
				<?php
			endif; //primary menu
			if ( ! empty( $exs_button_text ) || ! empty( $exs_button_url ) ) :
				?>
				<a class="wp-block-button__link header-button" href="<?php echo esc_url( $exs_button_url ); ?>">
					<?php echo wp_kses_post( $exs_button_text ); ?>
				</a>
				<?php
			endif; //header button
			if ( ! empty( $exs_toggler_main_in_header ) && has_nav_menu( 'primary' ) ) :
				?>
				<button id="nav_toggle" class="nav-btn"
						aria-controls="nav_top"
						aria-expanded="false"
						aria-label="<?php esc_attr_e( 'Top Menu Toggler', 'exs' ); ?>"
				>
					<span></span>
				</button>
			<?php endif; //toggler_main_in_header ?>
		</div><!-- .container -->
		<?php
		if ( 'container' === $exs_border_bottom ) {
			echo wp_kses_post( '<hr class="section-hr container">' );
		}
		if ( 'full' === $exs_border_bottom ) {
			echo wp_kses_post( '<hr class="section-hr">' );
		}
		?>
	</header><!-- #header -->
	<?php if ( ! empty( $exs_sticky ) ) : ?>
</div><!--#header-affix-wrap-->
<?php endif; ?>
