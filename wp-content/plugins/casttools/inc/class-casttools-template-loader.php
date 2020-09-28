<?php

/**
 * Template loader for Cast Tools.
 *
 * Only need to specify class properties here.
 *
 * @package casttools
 * @author  Bryan Bassett
 */
class Shredder extends Gamajo_Template_Loader {
    /**
     * Prefix for filter names.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $filter_prefix = 'casttools';

    /**
     * Directory name where custom templates for this plugin should be found in the theme.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $theme_template_directory = 'casttools';

    /**
     * Reference to the root directory path of this plugin.
     *
     * Can either be a defined constant, or a relative reference from where the subclass lives.
     *
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $plugin_directory = MY_PLUGIN_DIR;

    /**
     * Directory name where templates are found in this plugin.
     *
     * Can either be a defined constant, or a relative reference from where the subclass lives.
     *
     *
     * @since 1.1.0
     *
     * @var string
     */
    protected $plugin_template_directory = 'inc/templates';
}