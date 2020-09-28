<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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

$exs_show_title = exs_get_feed_shot_title();
$exs_layout     = exs_get_feed_layout();
$exs_layout_gap = exs_get_feed_gap();

//layout may contain columns count separated by space and 'masonry' word after columns count
$exs_layout         = explode( ' ', $exs_layout );
$exs_columns_number = ( ! empty( $exs_layout[1] ) ) ? absint( $exs_layout[1] ) : '';
$exs_masonry        = ( ! empty( $exs_layout[2] ) && 'masonry' === $exs_layout[2] ) ? true : false;
$exs_grid_class     = ( ! empty( $exs_masonry ) ) ? 'masonry' : 'grid-wrapper';
$exs_layout         = $exs_layout[0];
$exs_columns        = ( ! empty( $exs_columns_number ) ) ? true : false;

//additional css classes for #layout div element
$exs_layout_class  = 'layout-' . $exs_layout;
$exs_layout_class .= ! empty( $exs_columns ) ? ' layout-cols-' . $exs_columns_number : ' layout-cols-1';
$exs_layout_class .= ! empty( $exs_layout_gap ) ? ' layout-gap-' . $exs_layout_gap : ' layout-gap-default';

if ( ! empty( $exs_masonry ) ) {
	exs_enqueue_masonry_action();
}

get_header();

if ( have_posts() ) :
	//check if no file with selected layout - using default layout
	$exs_layout = file_exists( EXS_THEME_PATH . '/template-parts/blog/' . $exs_layout . '/content.php' ) ? $exs_layout : 'default';
	?>
	<div id="layout" class="<?php echo esc_attr( $exs_layout_class ); ?>">
		<?php if ( ! empty( $exs_show_title ) ) : ?>
			<h1 class="archive-title">
				<span><?php get_template_part( 'template-parts/title/title-text' ); ?></span>
			</h1>
			<?php
		endif; //show_title

		if ( is_category() ) :
			$exs_category_description = category_description();
			if ( ! empty( $exs_category_description ) ) {
				echo '<div class="category-description">' . wp_kses_post( $exs_category_description ) . '</div><!-- .category-description -->';
			}
		endif; //is_category

		if ( ! empty( $exs_columns ) ) :
			// read about masonry layout here:
			// https://masonry.desandro.com/options.html
			// https://github.com/desandro/masonry/issues/549
			?>
		<div class="grid-columns-wrapper">
			<div class="<?php echo esc_attr( $exs_grid_class ); ?>">
				<div class="grid-sizer"></div>
			<?php
			endif; //columns

				// Load posts loop.
		while ( have_posts() ) :

			the_post();
			get_template_part( 'template-parts/blog/' . $exs_layout . '/content' );

		endwhile;

		if ( ! empty( $exs_columns ) ) :
			?>
			</div><!-- .<?php echo esc_html( $exs_grid_class ); ?>-->
		</div><!-- .grid-columns-wrapper -->
			<?php
		endif; //columns

		// Previous/next page navigation.
		the_posts_pagination(
			exs_get_the_posts_pagination_atts()
		);

	?>
	</div><!-- #layout -->
	<?php
	else :

		// If no content, include the "No posts found" template.
		get_template_part( 'template-parts/content', 'none' );

endif; //have_posts

	get_footer();
