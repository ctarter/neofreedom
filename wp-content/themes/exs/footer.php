<?php
/**
 * The template for displaying the footer
 *
 * Contains the footer.
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

if ( is_front_page() && is_active_sidebar( 'sidebar-home-after-content' ) ) : ?>
	<div class="sidebar-home sidebar-home-after sidebar-home-after-content">
		<?php dynamic_sidebar( 'sidebar-home-after-content' ); ?>
	</div><!-- .sidebar-home-after-content -->
	<?php
endif; //is_front_page

if (
	//no need to close container class if they was not opened
	//see header.php file
	! is_page_template( 'page-templates/full-width.php' )
	&& !
	is_404()
) :
	/**
	 * Fires at the bottom of main column.
	 *
	 * @since ExS 0.0.1
	 */
	do_action( 'exs_action_bottom_of_main_column' );

	?>
	</main><!-- #main -->
	<?php get_sidebar( 'sidebar-1' ); ?>
	</div><!-- #columns -->
	<?php
	//full width widget area before columns for home page
	if ( is_front_page() && is_active_sidebar( 'sidebar-home-after-columns' ) ) :
		?>
		<div class="sidebar-home sidebar-home-after sidebar-home-after-columns">
			<?php dynamic_sidebar( 'sidebar-home-after-columns' ); ?>
		</div><!-- .sidebar-home-after-columns -->
	<?php endif; //home.php ?>
	</div><!-- .container -->
	</div><!-- #main -->
	<?php

endif; //full-width

get_template_part( 'template-parts/footer-top/section', exs_template_part( 'footer_top', '' ) );

get_template_part( 'template-parts/footer/footer', exs_template_part( 'footer', '1' ) );

get_template_part( 'template-parts/copyright/copyright', exs_template_part( 'copyright', '1' ) );

?>
</div><!-- #box -->

<div id="search_dropdown">
	<?php get_search_form(); ?>
</div><!-- #search_dropdown -->
<button
	id="search_modal_close"
	class="nav-btn"
	aria-controls="search_dropdown"
	aria-expanded="true"
	aria-label="<?php esc_attr_e( 'Search Toggler', 'exs' ); ?>"
>
	<span></span>
</button>

<?php
//if there is no header chosen  we need to show #overlay here for side menu overlay
$exs_header = exs_option( 'header', '' );
if ( empty( $exs_header ) ) :
	?>
	<div id="overlay"></div>
	<?php
endif; //header

$exs_to_top = exs_option( 'totop', '' );
//page totop button
if ( ! empty( $exs_to_top ) ) :
	?>
	<a id="to-top" href="#body">
		<span class="screen-reader-text">
			<?php esc_html_e( 'Go to top', 'exs' ); ?>
		</span>
	</a>
	<?php
endif; //totop_enabled

/**
 * Fires at the bottom of whole web page before the wp_footer function.
 *
 * @since ExS 0.0.4
 */
do_action( 'exs_action_before_wp_footer' );

wp_footer();
?>
</body>
</html>
