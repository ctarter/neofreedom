<?php
/*
* This adds the spoiler shortcode, and tells bbPress to accept the shortcode from all logged in users.
* If other shortcodes or BBCodes are included in future versions, they'll also go here.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists('TMCE_For_Bbp_Shortcode') ) {
    class TMCE_For_Bbp_Shortcode {

        public function __construct() {

            add_shortcode( 'spoiler', array( $this, 'spoiler_shortcode' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            add_filter( 'bbp_get_reply_content', array( $this, 'bb_sc_render'), 99, 2);

            add_filter( 'bbp_get_topic_content', array( $this, 'bb_sc_render'), 99, 2);
        }

        public static function spoiler_shortcode( $atts , $content = null ) {
            $atts = shortcode_atts(
                array(
                    'title' => 'spoiler',
                ),
                $atts,
                'spoiler'
            );

            return 
            '<div class="tmc4bpp-spoiler">
                <div class="tmc4bpp-spoiler-head">
                    <i class="fas fa-plus"></i>
                    <span class="tmc4bpp-spoiler-title">' . esc_attr($atts['title']) . '</span>
                </div>
                <div class="tmc4bpp-spoiler-body">' . do_shortcode($content) . '</div>
            </div><!-- end .tmc4bpp-spoiler -->';
        }

        public static function enqueue_scripts() {

            wp_enqueue_style( 'shortcodes-css', plugins_url( '/css/shortcodes.css', __FILE__ ) );
            
            wp_enqueue_script( 'shortcodes-js', plugins_url( '/js/shortcodes.js', __FILE__ ), array('jquery'), 0.1 );
        }

        /*
        * Makes 'spoiler' shortcode available for all users
        */

        public static function bb_sc_render( $content, $reply_id ) {

            // Once 'Spoiler' shortcode is included, change $reply_author line to: 
            // return spoiler_shortcode( $content );

            $reply_author = bbp_get_reply_author_id( $reply_id );
            if( user_can( $reply_author, 'publish_forums' ) )
                return do_shortcode( $content );
            return $content;
        }

}
}
/*
* Load this after other plugins that might be using spoiler shortcode
*/

if ( ! function_exists( tmce_shortcodes ) ) {
    
    function tmce_shortcodes() {
        
        $tmce_shortcode = new TMCE_For_Bbp_Shortcode;
    
    }
    
    add_action('plugins_loaded', 'tmce_shortcodes', 99);

}

tmce_shortcodes();

// That's all, folks!