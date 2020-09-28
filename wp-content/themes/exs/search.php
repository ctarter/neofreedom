<?php
/**
 * The search template file
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

$exs_special_cats = exs_get_special_categories_from_options();
$exs_show_title   = ! exs_option( 'title_show_title', '' ) && get_the_title();

get_header();

?>
	<div id="layout" class="layout-search">
		<?php if ( ! empty( $exs_show_title ) ) : ?>
			<h1><?php get_template_part( 'template-parts/title/title-text' ); ?></h1>
			<?php
		endif; //show_title
		?>
		<div class="mb-3">
			<?php get_search_form(); ?>
		</div>
		<?php
		if ( have_posts() ) {

			// Load posts loop.
			while ( have_posts() ) :
				the_post();
				if ( 'product' === get_post_type() && function_exists( 'wc_get_template' ) ) :
					?>
					<div class="woo woocommerce columns-1">
						<ul class="products search-results">
						<?php
							wc_get_template( 'content-product.php' );
						?>
						</ul>
					</div>
					<?php
				elseif ( 'job_listing' === get_post_type() && function_exists( 'job_listing_class' ) ) :

					global $post;
					?>
					<article>
						<ul class="job_listings">
							<li <?php job_listing_class(); ?> data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>">
								<a href="<?php the_job_permalink(); ?>">
									<?php the_company_logo(); ?>
									<div class="position">
										<h3><?php wpjm_the_job_title(); ?></h3>
										<div class="company">
											<?php the_company_name( '<strong>', '</strong> ' ); ?>
											<?php the_company_tagline( '<span class="tagline">', '</span>' ); ?>
										</div>
									</div>
									<div class="location">
										<?php the_job_location( false ); ?>
									</div>
									<ul class="meta">
										<?php do_action( 'job_listing_meta_start' ); ?>

										<?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
											<?php $types = wpjm_get_the_job_types(); ?>
											<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
												<li class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></li>
											<?php endforeach; endif; ?>
										<?php } ?>

										<li class="date"><?php the_job_publish_date(); ?></li>

										<?php do_action( 'job_listing_meta_end' ); ?>
									</ul>
								</a>
							</li>
						</ul>
					</article>
					<?php

				elseif ( 'jobpost' === get_post_type() && class_exists( 'Simple_Job_Board' ) ) :
					?>
					<article class="sjb-page entry-content">
				<?php
					get_simple_job_board_template('content-job-listing-list-view.php');
					?>
					</article><!-- .sjb-page -->
				<?php
				else :
					?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php

					the_title( '<header class="entry-header"><h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2></header>' );

					if (
						'post' === get_post_type()
						&&
						//exclude special categories
						! in_category( $exs_special_cats, get_the_ID() )
					) :
						?>
						<footer class="entry-footer"><?php exs_entry_meta(); ?></footer><!-- .entry-footer -->
						<?php
					endif; //'post'

					the_excerpt();
					?>
				</article><!-- #post-<?php the_ID(); ?> -->
					<?php
				endif;
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination(
				exs_get_the_posts_pagination_atts()
			);

		} else {

			// If no content, include the "No posts found" template.
			get_template_part( 'template-parts/content', 'none' );

		}
		?>
	</div><!-- #layout -->
<?php

get_footer();
