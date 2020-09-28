<?php
/*
Plugin Name: Use TinyMCE with bbPress
Plugin URI: https://wordpress.org/plugins/bbp-tinymce/
Description: TinyMCE editor designed specifically for bbPress forums
Author: Michael Glenn
Version: 1.0
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/ 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once( __DIR__ . '/includes/shortcodes.php' );

if ( ! class_exists( 'TMCE_For_Bbp' ) ) {
    class TMCE_For_Bbp {

        /*
        * This tells bbPress to load TinyMCE. 
        * It also specifies which buttons and plugins to include.
        */
        public function __construct() {

            add_filter( 'bbp_after_get_the_content_parse_args', array( $this, 'tiny_bbp_enable_visual_editor' ) );

            add_filter( 'bbp_after_get_the_content_parse_args', array( $this, 'tiny_bbp_visual_editor_buttons' ) );

            add_filter( 'mce_buttons', array( $this, 'tiny_bbp_visual_editor_buttons' ) );

            add_filter( 'tiny_mce_before_init', array( $this, 'tiny_bbp_link_dialog'), 1000 );

            add_filter( 'mce_external_plugins', array( $this, 'tiny_bbp_visual_editor_plugins') );

            add_filter('bbp_kses_allowed_tags', array( $this, 'tiny_bbp_allowed_tags' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
          
        }

        /*
        * Activates TinyMCE
        */
        public function tiny_bbp_enable_visual_editor( $args = array() ) {
            $args['tinymce']    = true; // This turns on the tinyMCE
            $args['teeny']      = false; // This makes it possible to change the available buttons
            $args['quicktags'] = false; // This turns OFF the html editor

            return $args;
        }

        /*
        * Adds the buttons
        */
        public static function tiny_bbp_visual_editor_buttons( $buttons = array() ) {
            $buttons['tinymce'] = array(

                'toolbar1' => "bold italic strikethrough blockquote spoiler bullist numlist table link image emoticons fullscreen",

                'toolbar2' => "",

                'toolbar3' => "",

            );

            return $buttons;
        }

        /*
        * Changes the settings for link target to open in a new window
        */

        public static function tiny_bbp_link_dialog( $settings ) {

            // Set the link target to blank
        
            $settings['default_link_target'] = "_blank";
            $settings['link_assume_external_targets'] = true;
            return $settings;
        }


        /*
        * Loads the necessary plugins
        */
        public static function tiny_bbp_visual_editor_plugins( $plugin_array ) {           
            $plugin_array['autoresize']  = plugins_url( '/mce/autoresize/plugin.min.js', __FILE__ );
            $plugin_array['autosave']  = plugins_url( '/mce/autosave/plugin.min.js', __FILE__ );
            $plugin_array['emoticons']  = plugins_url( '/mce/emoticons/plugin.min.js', __FILE__ );
            $plugin_array['fullscreen'] = plugins_url( '/mce/fullscreen/plugin.min.js', __FILE__ );
            $plugin_array['link']       = plugins_url( '/mce/link/plugin.min.js', __FILE__ );
            $plugin_array['paste']       = plugins_url( '/mce/paste/plugin.min.js', __FILE__ );
            $plugin_array['spoiler']    = plugins_url( '/mce/spoiler/plugin.js', __FILE__ );
            $plugin_array['table']    = plugins_url( '/mce/table/plugin.js', __FILE__ );
           
            return $plugin_array;
        }

        public function tiny_bbp_allowed_tags() {
            return array(

                // Structure
                'div'   => array(
                    'class' => true,
                ),

                'span' => array(
                    'class' => true,
                ),

                // Headings
                'h1' => array(
                    'class' => true,
                ),

                'h2' => array(
                    'class' => true,
                ),

                'h3' => array(
                    'class' => true,
                ),

                'h4' => array(
                    'class' => true,
                ),

                'h5' => array(
                    'class' => true,
                ),

                'h6' => array(
                    'class' => true,
                ),

                // Lists
                'ul' => array(),
                'ol' => array(),
                'li' => array(),

                // Formatting
                'em' => array(),
                'strong' => array(),
                'del' => array(),

                'blockquote' => array(
                    'cite' => true,
                ),

                'cite' => array(
                    'class' => true,
                ),


                // Links
                'a' => array(
                    'href'   => true,
                    'title'  => true,
                    'target' => true,
                    'rel'    => true,
                    'class'  => true,
                ),

                // Images
                'img' => array(
                    'src'    => true,
                    'alt'    => true,
                    'height' => true,
                    'width'  => true,
                    'class'  => true,
                    'border' => true,
                ),

                // Tables

                'caption' => array(),

                'table' => array(
                    'class'         => true,
                    'style'         => true,
                    'border'        => true,
                    'cellpadding'   => true,
                    'cellspacing'   => true,
                ),

                'thead' => array(),

                'tbody' => array(),

                'tr' => array(),

                'td' => array(),              

                'th' => array(),
            );
        }

        public static function enqueue_scripts() {
            //Make the dialog boxes responsive
            wp_enqueue_style( 'mce-responsive-css', plugins_url( '/includes/css/mce-responsive.css', __FILE__ ) );
        }

}
}

$tmce = new TMCE_For_Bbp;