<?php
/**
 * Block patterns support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.1.0
 * @link https://developer.wordpress.org/block-editor/developers/block-api/block-patterns/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'register_block_pattern_category' ) ) {
	return;
}

if ( ! function_exists( 'exs_register_theme_block_patterns' ) ) {
	function exs_register_theme_block_patterns() {
		register_block_pattern_category(
			'exs',
			array( 'label' => esc_html__( 'ExS', 'exs' ) )
		);

		$exs_patterns = apply_filters(
			'exs_block_patterns',
			array(
				'exs/title-with-subtitle'             => array(
					'title'       => esc_html__( 'Title with subtitle', 'exs' ),
					'description' => esc_html__( 'Title heading with sub title and separator below it.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'title-with-subtitle' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'title', 'subtitle', 'heading' ),
				),
				'exs/cols-3-feature-image-boxes'      => array(
					'title'       => esc_html__( 'Three featured columns', 'exs' ),
					'description' => esc_html__( 'Three columns with image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-feature-image-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-feature-blocks'           => array(
					'title'       => esc_html__( 'Four featured columns', 'exs' ),
					'description' => esc_html__( 'Four columns with image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-feature-blocks' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-feature-blocks-left'      => array(
					'title'       => esc_html__( 'Four featured columns', 'exs' ),
					'description' => esc_html__( 'Four columns with left aligned image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-feature-blocks-left' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-3-feature-side-image-boxes' => array(
					'title'       => esc_html__( 'Three side featured columns', 'exs' ),
					'description' => esc_html__( 'Three columns with side image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-feature-side-image-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-6-feature-blocks'           => array(
					'title'       => esc_html__( 'Six featured columns', 'exs' ),
					'description' => esc_html__( 'Six columns with image boxes with titles.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-6-feature-blocks' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-progress'                 => array(
					'title'       => esc_html__( 'Four columns with progress', 'exs' ),
					'description' => esc_html__( 'Four columns with progress image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-progress' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'progress', 'image box' ),
				),
				'exs/cols-4-team-members'             => array(
					'title'       => esc_html__( 'Four columns with team', 'exs' ),
					'description' => esc_html__( 'Four columns with team members photo and description.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-team-members' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-contacts'                 => array(
					'title'       => esc_html__( 'Four columns with contacts', 'exs' ),
					'description' => esc_html__( 'Four columns with contact info.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-contacts' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'contact', 'contacts', 'image box' ),
				),
				'exs/cols-2-blockquotes'              => array(
					'title'       => esc_html__( 'Two columns with blockquotes', 'exs' ),
					'description' => esc_html__( 'Two columns with testimonials blockquotes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-blockquotes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'testimonials', 'blockquotes' ),
				),
				'exs/cover-call-to-action'            => array(
					'title'       => esc_html__( 'Cover call to action', 'exs' ),
					'description' => esc_html__( 'Call to action cover block with title and button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cover-call-to-action' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'cover', 'call', 'action' ),
				),
				'exs/cols-3-text-actions'            => array(
					'title'       => esc_html__( 'Text columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action columns block with text and button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-text-actions' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'call', 'action' ),
				),
				'exs/cols-2-call-to-action'           => array(
					'title'       => esc_html__( 'Two columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action text block with heading and button in two columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-call-to-action' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action', 'columns' ),
				),
			)
		);

		if ( ! empty( $exs_patterns ) ) {
			foreach ( $exs_patterns as $id => $pattern ) {
				register_block_pattern( $id, $pattern );
			}
		}
	}
}
add_action( 'after_setup_theme', 'exs_register_theme_block_patterns' );
