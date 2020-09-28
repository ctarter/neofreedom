<?php
/**
 * Right sidebar check.
 *
 * @package casttools
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
$shredder = new Shredder;
?>

</div><!-- #closing the primary container from /global-templates/left-sidebar-check.php -->

<?php $sidebar_pos = get_theme_mod( 'casttools_sidebar_position' ); ?>

<?php if ( 'right' === $sidebar_pos || 'both' === $sidebar_pos ) : ?>

	<?php $shredder->get_template_part( 'sidebar-templates/sidebar', 'right' ); ?>

<?php endif; ?>
