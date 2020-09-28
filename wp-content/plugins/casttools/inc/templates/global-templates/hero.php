<?php
/**
 * Hero setup.
 *
 * @package casttools
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
$shredder = new Shredder;
?>

<?php if ( is_active_sidebar( 'hero' ) || is_active_sidebar( 'statichero' ) || is_active_sidebar( 'herocanvas' ) ) : ?>

	<div class="wrapper" id="wrapper-hero">

		<?php $shredder->get_template_part( 'sidebar-templates/sidebar', 'hero' ); ?>

		<?php $shredder->get_template_part( 'sidebar-templates/sidebar', 'herocanvas' ); ?>

		<?php $shredder->get_template_part( 'sidebar-templates/sidebar', 'statichero' ); ?>

	</div>

<?php endif; ?>
