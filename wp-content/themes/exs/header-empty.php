<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section without any other markup
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

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="profile" href="https://gmpg.org/xfn/11"/>
	<?php wp_head(); ?>
</head>

<body id="body" <?php body_class(); ?> itemtype="https://schema.org/WebPage" itemscope="itemscope"
	<?php exs_animated_elements_markup(); ?>
>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

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

get_template_part( 'template-parts/header/messages' );
