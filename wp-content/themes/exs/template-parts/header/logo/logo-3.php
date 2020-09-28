<?php
/**
 * The logo template file
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

$exs_custom_logo         = exs_option( 'custom_logo' );
$exs_logo_image_class    = ( ! empty( $exs_custom_logo ) ) ? 'with-image' : 'no-image';
$exs_logo_text_primary   = exs_option( 'logo_text_primary' );
$exs_logo_text_secondary = exs_option( 'logo_text_secondary' );
$exs_logo_background     = exs_option( 'logo_background' );
$exs_logo_x_padding      = exs_option( 'logo_padding_horizontal' );
$exs_logo_padding_class  = ( ! empty( $exs_logo_x_padding ) ) ? 'px' : '';

//if no text - get blog name for primary text
if ( empty( $exs_logo_text_primary ) && empty( $exs_logo_text_secondary ) && empty( $exs_custom_logo ) ) {
	$exs_logo_text_primary = get_bloginfo( 'name' );
}
?>
<a id="logo" class="logo logo-between <?php echo esc_attr( $exs_logo_image_class . ' ' . $exs_logo_background . ' ' . $exs_logo_padding_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url">
	<?php if ( ! empty( $exs_logo_text_primary ) ) : ?>
		<span class="logo-text-primary">
			<?php echo wp_kses_post( $exs_logo_text_primary ); ?>
		</span><!-- .logo-text-primary -->
		<?php
	endif;

	//image
	echo wp_get_attachment_image( $exs_custom_logo, 'full' );

	if ( ! empty( $exs_logo_text_secondary ) ) :
		?>
		<span class="logo-text-secondary">
		<?php echo wp_kses_post( $exs_logo_text_secondary ); ?>
	</span><!-- .logo-text-secondary -->
	<?php endif; ?>
</a><!-- .logo -->
