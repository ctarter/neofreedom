<?php
/**
 * Left sidebar check.
 *
 * @package casttools
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
$shredder = new Shredder;
$sidebar_pos = get_theme_mod( 'casttools_sidebar_position' );
?>

<?php if ( 'left' === $sidebar_pos || 'both' === $sidebar_pos ) : ?>
	<?php $shredder->get_template_part( 'sidebar-templates/sidebar', 'left' ); ?>
<?php endif; ?>

<div class="col-md content-area" id="primary">
