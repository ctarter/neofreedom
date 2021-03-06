<?php
/**
 * Latest CT Teachings layout.
 *
 * @package casttools
 */
function latest_CT_function(){
    if(is_page()){
        $ctid = get_term_by( 'name', 'Central Teachings', 'category' );
        $ctid =$ctid->term_id;
        $query = new WP_Query( array( 'posts_per_page' => 5, 'orderby' => 'post_date','order'   => 'DESC', 'suppress_filters' => true,'post__not_in' => get_option( 'sticky_posts' ),'cat' => $ctid) );
        $posts = $query->posts;
        $printer='<div class="container mt-1 mb-3">';
        $printer.='<h5 class="card-title">Latest Central Teachings</h5>';
        foreach($posts as $post){
            $my_post_categories = get_the_category($post->ID);
            $teachers = array();
            foreach ( $my_post_categories as $post_cat ) {
                $teachid = get_term_by( 'name', 'Teacher', 'category' );
                $teachid =$teachid->term_id;
                if ( $teachid == $post_cat->category_parent ) {
                    $teachers[] = $post_cat->cat_name;
                }
            }
            $printer2='<div class="latest-ct-each card"><div class="card-body">';
            $printer2.= '<h6 class="card-subtitle mb-2 text-muted ">'.'<a href="'.get_permalink($post->ID).'" class="card-link">'.$post->post_title.'</a>'.'</h6>';
            $newexcerpt = strip_tags ($post->post_content);
            if(strlen($newexcerpt)>140){
                $newexcerpt = substr($newexcerpt,0,137).'...';
            }
            $printer2.=  '<p class="card-text ">'.$newexcerpt.'</p>';
            foreach($teachers as $teacher){
                $printer2.='<span class="badge badge-secondary float-right ml-1 mr-1 ">'.ucwords($teacher).'</span>';
            }
            $printer2.= '</div></div>';
            $printer.=$printer2;
        }
        $printer.='</div>';
        return $printer;
    }
}
add_shortcode('latestct', 'latest_CT_function');
function cleanx($str) {
    //build an array we can re-use across several operations
    $badchar = array(
        // control characters
        chr(0),
        chr(1),
        chr(2),
        chr(3),
        chr(4),
        chr(5),
        chr(6),
        chr(7),
        chr(8),
        chr(9),
        chr(10),
        chr(11),
        chr(12),
        chr(13),
        chr(14),
        chr(15),
        chr(16),
        chr(17),
        chr(18),
        chr(19),
        chr(20),
        chr(21),
        chr(22),
        chr(23),
        chr(24),
        chr(25),
        chr(26),
        chr(27),
        chr(28),
        chr(29),
        chr(30),
        chr(31),
        // non-printing characters
        chr(127)
    );

//replace the unwanted chars
    return str_replace($badchar, '', $str);
}