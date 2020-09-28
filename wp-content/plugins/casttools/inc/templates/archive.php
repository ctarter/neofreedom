<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package casttools
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;get_header();
$shredder = new Shredder;

$container = get_theme_mod( 'casttools_container_type' );
?>

<div class="wrapper" id="archive-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php $shredder->get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">

				<?php if ( have_posts() ) : ?>

					<header class="page-header">
						<?php

                        add_filter('get_the_archive_title', function ($title) {
                            return preg_replace('/^\w+: /', '', $title);
                        });
                        the_archive_title('<h1 class="page-title">', '</h1>');
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
						?>
					</header><!-- .page-header -->
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
/*
                        $link = get_post_field('post_content', $post->ID);
                        $link2 = explode('<a href="',$link);
                        $link3 = explode('">',$link2[1]);
                        $pptlink = $link3[0];
                        $pptlink2 = explode('.',$pptlink);
                        $link4 = explode ('</a>',$link2[1]);
                        $newpost = $link4[1];
                        if(in_array('pptx',$pptlink2) || in_array('ppt',$pptlink2)){
                            $post_id=$post->ID;
                            $meta_key = 'ppt_file';
                            $meta_value= $pptlink;
                            $my_post = array();
                            $my_post['ID'] = $post->ID;
                            $my_post['post_content'] = $newpost;
                            wp_update_post( $my_post );
                            update_post_meta( $post_id, $meta_key, $meta_value);
                        }
*/
                        $shredder-> get_template_part( 'loop-templates/content', get_post_format() );
						?>

					<?php endwhile; ?>

				<?php else : ?>

					<?php $shredder->get_template_part( 'loop-templates/content', 'none' ); ?>

				<?php endif; ?>

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php casttools_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php $shredder->get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div> <!-- .row -->

	</div><!-- #content -->

	</div><!-- #archive-wrapper -->

<?php get_footer(); ?>
