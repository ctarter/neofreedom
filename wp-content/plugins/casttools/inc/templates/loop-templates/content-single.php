<?php
/**
 * Single post partial template.
 *
 * @package casttools
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">



	<div class="entry-content">
        <div class="jumbotron">

            <header class="entry-header">

                <?php

                the_title('<h1 class="display-4">', '</h1>');
                ?>
                <div class="entry-meta">
                   <h5>Posted on:  <?php casttools_posted_on(); ?></h5>
                    <div class="alert alert-primary" id="cator" role="alert">
                    <?php
                    ?>

                        <?php

                    $post_id = get_the_ID();
                    $post_categories = wp_get_post_categories($post_id);
                    $ctid = get_term_by( 'name', 'Central Teachings', 'category' );
                    $ctid =$ctid->term_id;
                    $teachid = get_term_by( 'name', 'Teacher', 'category' );
                    $teachid =$teachid->term_id;
                    if(empty($post_categories) || count($post_categories)<2 ){
                        echo '<small ><em>Needs Categorized</em></small>';
                    }else{
                        $get_terms_default_attributes = array(
                            'taxonomy' => 'category', //empty string(''), false, 0 don't work, and return empty array
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => true, //can be 1, '1' too
                            'include' => $post_categories, //empty string(''), false, 0 don't work, and return empty array
                            'exclude' => 'all', //empty string(''), false, 0 don't work, and return empty array
                            'exclude_tree' => 'all', //empty string(''), false, 0 don't work, and return empty array
                            'number' => false, //can be 0, '0', '' too
                            'offset' => '',
                            'fields' => 'all',
                            'name' => '',
                            'slug' => '',
                            'hierarchical' => true, //can be 1, '1' too
                            'search' => '',
                            'name__like' => '',
                            'description__like' => '',
                            'pad_counts' => false, //can be 0, '0', '' too
                            'get' => '',
                            'child_of' => false, //can be 0, '0', '' too
                            'childless' => false,
                            'cache_domain' => 'core',
                            'update_term_meta_cache' => true, //can be 1, '1' too
                            'meta_query' => '',
                            'meta_key' => array(),
                            'meta_value' => '',
                        );
                        $d = get_terms($get_terms_default_attributes);
                        foreach ($d as $cat) {
                            $x[$cat->term_id] = $cat;
                        }
                        foreach ($x as $key => $cat) {
                            //MAKE TEACHER ARRAY
                            if ($cat->parent == $teachid) {
                                $teachers[$cat->name] = get_object_vars($cat);
                                unset($x[$cat->term_id]);
                            }elseif ($cat->term_id == $teachid){
                                unset($x[$cat->term_id]);
                                //GET TOP CATEGORIES
                            } elseif ($cat->parent == 0) {
                                $topcats[$cat->name] = get_object_vars($cat);
                                unset($x[$cat->term_id]);
                            } elseif ($cat->parent == $ctid) {
                                $topcats['Central Teachings']['name'] = 'Central Teachings';
                                $topcats['Central Teachings']['term_id'] = $ctid;
                                $topcats['Central Teachings']['parent'] = 0;
                            }
                        }
                        //GET FIRST LEVEL CHILDREN
                        foreach ($x as $key3 => $cat3) {
                            foreach ($topcats as $key => $top) {
                                if ($top['term_id'] == $cat3->parent) {
                                    $topcats[$key]['children'][$cat3->name] = get_object_vars($cat3);
                                    unset($x[$cat3->term_id]);
                                }
                            }
                        }
                        //GET SECOND LEVEL CHILDREN
                        foreach ($x as $key4 => $cat4) {
                            foreach ($topcats as $key => $top) {
                                if (!empty($top['children'])) {
                                    foreach ($top['children'] as $key7 => $children) {
                                        if ($cat4->parent == $children['term_id']) {
                                            $topcats[$key]['children'][$key7]['children'][$cat4->name] = get_object_vars($cat4);
                                            unset($x[$cat4->term_id]);
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($teachers)) {

                            $teachstring = '<small><nav aria-label="breadcrumb"><ol class="breadcrumb teachers">';
                            $teachstring.= '<li class="breadcrumb-item"><strong><a href="#" data-whatever="5063" class="modalopen">Teachers</a></strong></li>';

                            foreach ($teachers as $teacher) {
                                $teachstring .= '<li class="breadcrumb-item"><a href="#" data-whatever="' . $teacher['term_id'] . '" class="modalopen">' . ucwords($teacher['name']) . '</a></li>';
                            }
                            echo $teachstring . '</ol></nav></small>';
                        }
                        if (isset($topcats)) {
                            foreach ($topcats as $key => $topcat) {
                                $catsxx = '<small><nav aria-label="breadcrumb">  <ol class="breadcrumb">';
                                if (isset($topcat['children'])) {

                                    $catsxx.= '<li class="breadcrumb-item"><strong><a href="#" data-whatever="' . $topcat['term_id'] . '" class="modalopen">' . ucwords($topcat['name']).'</a></strong></li>';
                                    foreach ($topcat['children'] as $key2 => $firstlevelchildren) {
                                        $catsxx .= '<li class="breadcrumb-item"><a href="#" data-whatever="' . $firstlevelchildren['term_id'] . '" class="modalopen">' . ucwords($firstlevelchildren['name']).' </a></li>';
                                        foreach ($firstlevelchildren['children'] as $key3 => $secondlevelchildren) {
                                            $catsxx.= '<li class="breadcrumb-item"><a href="#" data-whatever="' . $secondlevelchildren['term_id'] . '" class="modalopen">' . ucwords($secondlevelchildren['name']) . '</a></li>';
                                        }
                                        $catsxx .= '</ol></nav></small>';
                                        echo $catsxx;
                                    }
                                } else {
                                    $catsxx.= '<li class="breadcrumb-item"><strong><a href="#" data-whatever="' . $topcat['term_id'] . '" class="modalopen">' . ucwords($topcat['name']) . '</a></strong></li>';
                                    $catsxx.= '</ol></nav></small>';
                                    echo $catsxx;
                                }

                            }
                        }
                    }


                    ?>
                    </div>
                    <?php
                    //SERIES INFO
                    $term_list = wp_get_post_terms($post->ID, 'series', array("fields" => "all"));
                    if(isset($term_list) && !empty($term_list)){
                        $series['name']=$term_list[0]->name;
                        $series['term_id']=$term_list[0]->term_id;
                        $series['count']=$term_list[0]->count;
                        $series['part'] = wp_series_part($post_id, $series['term_id']);
                        $series['prev'] = wp_series_nav($series['term_id'], $next = FALSE, $customtext = 'deprecated', $display = FALSE, $calc = false) ;
                        $series['next'] = wp_series_nav($series['term_id'], $next = TRUE, $customtext = 'deprecated', $display = FALSE, $calc = false) ;
                        if($series['count'] == $series['part']){
                            $series['last'] = TRUE;
                        }else{
                            $series['last'] = FALSE;
                        }
                        if(!empty($series['prev'])){
                            $fixer=explode('"',$series['prev']);
                            $series['prev']= $fixer[1];
                        }
                        if(!empty($series['next'])){
                            $fixer=explode('"',$series['next']);
                            $series['next']= $fixer[1];
                        }
                    }

                    if(isset($series) && !empty($series['name'])){
                        $seriesbox = '<div class="alert alert-secondary" id="seriesbox" role="alert"><small>';
                        $serieslink = '<a href="#" class=" modalopen" data-whatever="'.$series['term_id'].'">'.$series['name'].'</a>';
                        if($series['last']==FALSE){
                            if($series['part']==1){
                                $seriesbox .= 'This teaching is part '.$series['part'].' of '.$series['count'].' of the '.$serieslink.' series.';
                                $seriesbox.=' <a class="badge badge-primary" href="'.$series['next'].'">Next <i class="fa fa-angle-right"></i></a> ';
                            }elseif(empty($series['part'])){
                                $seriesbox .= 'This teaching is part of the '.$serieslink.' series.';
                            }else{
                                $seriesbox .= 'This teaching is part '.$series['part'].' of '.$series['count'].' of the '.$serieslink.' series.';
                                $seriesbox.=' <a class="badge badge-primary" href="'.$series['prev'].'"><i class="fa fa-angle-left"></i> Previous</a> <a class="badge badge-primary" href="'.$series['next'].'">Next <i class="fa fa-angle-right"></i></a> ';

                            }
                        }else{
                                $seriesbox .= 'This teaching is the last part of the '.$serieslink.' series.';
                                $seriesbox.=' <a class="badge badge-primary" href="'.$series['prev'].'"><i class="fa fa-angle-left"></i> Previous</a>';
                        }
                        $seriesbox .= '</small></div>';
                        echo $seriesbox;
                    }
                    ?>
                </div><!-- .entry-meta -->

            </header><!-- .entry-header -->
		<?php //the_content();
        $desc = get_post_field('post_content', $post->ID);
        $pplink = get_post_field('ppt_file', $post->ID);
        $mp3 = get_post_field('enclosure', $post->ID);
        $mp3 = preg_split('/\r\n|\r|\n/', $mp3)[0];
        $title = get_post_field('post_title', $post->ID);
        $title = trim(preg_replace('<\W+>', "_", $title), "_");
        $verse = get_post_field('bible_passage', $post->ID);
        if(!empty($desc) && strlen($desc)>10){
            print '<hr class="my-4"><p class="lead">'.$desc.'</p>';
        }
        if(!empty($verse)){
            $verse=str_replace(' ','.',$verse);
            $verse=str_replace(':','_',$verse);
            $endpoint = get_rest_url(null, 'neo-teaching-api/v1/passage/'.$verse);
            $request = wp_remote_get($endpoint);
            $body = wp_remote_retrieve_body( $request );
                $data = json_decode( $body );
            print '<button class="btn btn-secondary btn-lg btn-block" type="button" data-toggle="collapse" data-target="#collapsePassage" aria-expanded="false" aria-controls="collapsePassage">Read '.$data->passage_title.'</button><div class="collapse" id="collapsePassage"><div class="card card-body"><div class="bible"><p class="lead bible">'.$data->passage_html.'</p></div></div></div>';
            ?>
            <style>
                div.bible .p .v {
                    font-size: 50%;
                    vertical-align: super;
                }
            </style>
            <?php

        }
        if(!empty($pplink) || !empty($mp3)){
            print '<hr class="my-4">';
        if(!empty($mp3)) {
            print '<audio controls><source src="' . $mp3 . '" type="audio/mpeg" /><a href="' . $mp3 . '"></a></audio>';
            print '<hr class="my-4">';
        }
            print '<p>The following are the available downloads related to this teaching:</p><p class="lead">';
        }
        if(!empty($mp3)){
            print '<a href="'.$mp3.'"  download="'.$title.'.mp3" class="btn btn-warning btn-lg btn-block">Download MP3</a>';
        }
        if(!empty($pplink)){
            print '<a href="'.$pplink.'"  download="'.$title.'.pptx" class="btn btn-primary btn-lg btn-block">Download Powerpoint</a>';
        }
        if(!empty($pplink) || !empty($mp3)){
            print '</p>';

        }
        ?>
            <div id="feedbackchong" class="alert m-1 small text-right"><small>Let us know if the page looks wrong (categories, weird text, links are broken): </small><button id="chonger" data-whenever="<?php echo $post_id; ?>" type="button" class="btn btn-primary btn-sm"><i data-whenever="<?php echo $post_id; ?>"  class="fa fa-thumbs-down"></i></button></div>
        </div>
		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'casttools' ),
				'after'  => '</div>',
			)
		);
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">



	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
