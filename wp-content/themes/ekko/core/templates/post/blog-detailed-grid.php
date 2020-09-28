<?php
/**
 * Template part for displaying standard posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekko
 * by KeyDesign
 */

?>

<?php
	$without_image_class = '';

	if ( !has_post_thumbnail() ) {
		$without_image_class .= 'without-image';
	}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	<?php if (has_post_thumbnail()) : ?>
		<div class="entry-image">
			<a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('keydesign-grid-image'); ?></a>
		</div>
	<?php endif; ?>
	<div class="entry-wrapper <?php echo esc_attr($without_image_class); ?>">
		<h4 class="blog-single-title"><a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
		<div class="entry-meta">
			<?php if ( is_sticky() ) echo '<span class="fas fa-thumbtack"></span> Sticky <span class="blog-separator">|</span>  '; ?>
			<div class="post-meta-child">
				<span class="published"><span class="far fa-clock"></span><a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php  the_time( get_option('date_format') ); ?></a></span>
				<span class="blog-label"><span class="far fa-folder-open"></span><?php  the_category(', '); ?></span>
			</div>
			<div class="post-meta-child">
				<span class="author"><span class="far fa-keyboard"></span><?php the_author_posts_link(); ?></span>
				<span class="comment-count"><span class="far fa-comment"></span><?php comments_popup_link( esc_html__('No comments yet', 'ekko'), esc_html__('1 comment', 'ekko'), esc_html__('% comments', 'ekko') ); ?></span>
			</div>
		</div>
		<div class="entry-content">
			<?php the_excerpt(); ?>
			<?php wp_link_pages(); ?>
			<a class="post-link" href="<?php esc_url(the_permalink()); ?>"><?php esc_html_e( 'Read more', 'ekko' ); ?></a>
		</div>
	</div>
</article>