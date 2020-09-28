<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package casttools
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
$shredder = new Shredder;
$container = get_theme_mod( 'casttools_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,600&display=swap" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">

	<!-- ******************* The Navbar Area ******************* -->
	<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'casttools' ); ?></a>

		<nav class="navbar navbar-expand-md navbar-dark bg-primary">

		<?php if ( 'container' == $container ) : ?>
			<div class="container">
		<?php endif; ?>

					<!-- Your site title as branding in the menu -->
					<?php if ( ! has_custom_logo() ) { ?>

                        <style>
                            a.text-logo, a.text-logo:hover, a.text-logo:visited {
                                text-decoration: none;

                                color: #fff;
                                font-weight: 600;
                                text-transform: uppercase;
                            }
                        </style>

						<?php if ( is_front_page() && is_home() ) : ?>

<!--				<h1 class="navbar-brand mb-0"><a rel="home" href="<?php /*echo esc_url( home_url( '/' ) ); */?>" title="<?php /*echo esc_attr( get_bloginfo( 'name', 'display' ) ); */?>" itemprop="url"><?php /*bloginfo( 'name' ); */?></a></h1>-->
                            <a class="text-logo"  style="font-family: 'Open Sans',Helvetica,Arial,sans-serif;" data-type="group" data-dynamic-mod="true" href="<?php echo esc_url( home_url( '/' ) ); ?>">Freedom<span style="font-weight: 300;" class="span12"> Fellowship <i><Podcasts</i></span></a>
						<?php else : ?>

                            <a class="text-logo"  style="font-family: 'Open Sans',Helvetica,Arial,sans-serif;" data-type="group" data-dynamic-mod="true" href="<?php echo esc_url( home_url( '/' ) ); ?>">Freedom<span style="font-weight: 300;" class="span12"> Fellowship <i>Podcasts</i></span></a>

						<?php endif; ?>


					<?php } else {
						the_custom_logo();
					} ?><!-- end custom logo -->

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'casttools' ); ?>">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- The WordPress Menu goes here -->

				<?php wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container_class' => 'collapse navbar-collapse',
						'container_id'    => 'navbarNavDropdown',
						'menu_class'      => 'navbar-nav ml-auto',
						'fallback_cb'     => '',
						'menu_id'         => 'main-menu',
						'depth'           => 2,
						'walker'          => new casttools_WP_Bootstrap_Navwalker(),
					)
				); ?>
                <div id="navbarNavDropdown" class="collapse navbar-collapse">
                    <?php  echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
                </div>
			<?php if ( 'container' == $container ) : ?>
			</div><!-- .container -->
			<?php endif; ?>

		</nav><!-- .site-navigation -->

	</div><!-- #wrapper-navbar end -->
