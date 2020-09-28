<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$exs_body_itemtype = exs_get_body_schema_itemtype();

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js-disabled<?php echo is_customize_preview() ? ' customize-preview' : ''; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="profile" href="https://gmpg.org/xfn/11"/>
	<?php wp_head(); ?>
</head>
<body id="body" <?php body_class(); ?> itemtype="https://schema.org/<?php echo esc_attr( $exs_body_itemtype ); ?>" itemscope="itemscope"
	<?php exs_animated_elements_markup(); ?>
>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

?>
<a class="screen-reader-text skip-link" href="#main"><?php echo esc_html__( 'Skip to content', 'exs' ); ?></a>
<?php
//page preloader
$exs_preloader = exs_option( 'preloader', '' );

if ( ! empty( $exs_preloader ) ) :
	?>
	<!-- preloader -->
	<div id="preloader" class="preloader <?php echo esc_attr( $exs_preloader ); ?>">
		<div class="preloader_css"></div>
	</div>
	<?php
endif; //preloader_enabled

/**
 * Fires at the top of whole web page before the header.
 *
 * @since ExS 0.0.1
 */
do_action( 'exs_action_before_header' );

$exs_box_fade_in_class = exs_option( 'box_fade_in', '' ) ? 'box-fade-in' : 'box-normal';
?>
<div id="box" class="<?php echo esc_attr( $exs_box_fade_in_class ); ?>">
	<?php

	$exs_intro_position = exs_option( 'intro_position', '' );
	//intro section on front page after header
	if ( exs_is_front_page() && 'before' === $exs_intro_position ) :

		get_template_part( 'template-parts/header/intro' );

	endif;

	$exs_header_image_url = get_header_image();
	if ( ! empty( $exs_header_image_url ) ) :
		$exs_background_image = exs_section_background_image_array( 'header_image', true );
		?>
	<div id="header-image"
		class="i <?php echo esc_attr( $exs_background_image['class'] ); ?>"
		style="background-image: url('<?php echo esc_url( $exs_background_image['url'] ); ?> ');"
	>
		<?php
		endif; //header_image_url

		$exs_header_absolute = exs_option( 'header_absolute', '' );
	if ( ! empty( $exs_header_absolute ) ) :
		?>
		<div class="header-absolute-wrap">
			<div class="header-absolute-content">
			<?php
			endif; //$exs_header_absolute

		//topline header section
		get_template_part( 'template-parts/header/topline/topline', exs_template_part( 'topline', '' ) );

		//header section
		get_template_part( 'template-parts/header/header', exs_template_part( 'header', '1' ) );

		//title section not on front page
	if ( exs_is_title_section_is_shown() ) :
		get_template_part( 'template-parts/title/title', exs_template_part( 'title', '1' ) );
		//front page text
	else :
		//TODO homepage fullwidth image
		$exs_display_header_text = display_header_text();
		if ( ! empty( $exs_display_header_text ) ) :
			?>
			<h1 class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php echo wp_kses_post( get_bloginfo( 'name', 'display' ) ); ?>
				</a>
			</h1>
			<?php
			$exs_description = get_bloginfo( 'description', 'display' );

			if ( $exs_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo wp_kses_post( $exs_description ); ?></p>
				<?php
			endif; //description
		endif; //display_header_text
	endif; //exs_is_front_page

	if ( ! empty( $exs_header_absolute ) ) :
		?>
		</div><!-- .header-absolute-content -->
		</div><!-- .header-absolute-wrap -->
		<?php
		endif; //$exs_header_absolute

	/**
	 * Fires after the header.
	 *
	 * @since ExS 0.0.1
	 */
	do_action( 'exs_action_after_header' );

	//intro section on front page after header
	if ( exs_is_front_page() && 'after' === $exs_intro_position ) :
		get_template_part( 'template-parts/header/intro' );
	endif;

	if ( ! empty( $exs_header_image_url ) ) :
		?>
	</div><!--#header-image-->
		<?php
	endif; //$exs_header_image_url

	//intro teasers section on front page
	if ( exs_is_front_page() ) :
		get_template_part( 'template-parts/header/intro-teasers' );
	endif;

	//not opening container for 404 page or for full-width page template
	if ( ! is_page_template( 'page-templates/full-width.php' ) && ! is_404() ) :
		$exs_main_sidebar_width   = exs_option( 'main_sidebar_width', '' );
		$exs_main_gap_width       = exs_option( 'main_gap_width', 'default' );
		$exs_extra_padding_top    = exs_option( 'main_extra_padding_top', '' );
		$exs_extra_padding_bottom = exs_option( 'main_extra_padding_bottom', '' );
		$exs_font_size            = exs_option( 'main_font_size', '' );
		$exs_main_css_classes     = exs_get_page_main_section_css_classes();
		$exs_css_classes          = exs_get_layout_css_classes();

		if ( empty( $exs_extra_padding_top ) && ! exs_is_title_section_is_shown() ) {
			$exs_extra_padding_top = 'pt-5';
		}
		//no top padding for page template without title and padding
		if ( is_page_template( 'page-templates/no-sidebar-no-title.php' ) ) {
			$exs_extra_padding_top    = 'pt-0';
			$exs_extra_padding_bottom = 'pb-0';
		}
		?>
	<div id="main" class="main <?php echo esc_attr( 'sidebar-' . $exs_main_sidebar_width . ' sidebar-gap-' . $exs_main_gap_width . ' ' . $exs_main_css_classes ); ?>">
		<div class="container <?php echo esc_attr( $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
			<?php
			//full width widget area before columns for home page
			if ( exs_is_front_page() && is_active_sidebar( 'sidebar-home-before-columns' ) ) :
				?>
				<div class="sidebar-home sidebar-home-before sidebar-home-before-columns">
					<?php dynamic_sidebar( 'sidebar-home-before-columns' ); ?>
				</div><!-- .sidebar-home-before-columns -->
			<?php endif; ?>
			<div id="columns" class="main-columns">
				<main id="col" class="<?php echo esc_attr( $exs_css_classes['main'] . ' ' . $exs_font_size ); ?>">
					<?php
					/**
					 * Fires at the top of main column.
					 *
					 * @since ExS 0.0.1
					 */
					do_action( 'exs_action_top_of_main_column' );

					endif; //full-width & 404
	if ( exs_is_front_page() && is_active_sidebar( 'sidebar-home-before-content' ) ) :
		?>
		<div class="sidebar-home sidebar-home-before sidebar-home-before-content">
			<?php dynamic_sidebar( 'sidebar-home-before-content' ); ?>
		</div><!-- .sidebar-home-before-content -->
		<?php
		endif; //exs_is_front_page
