<?php

/*
Plugin Name: Neo Teaching Api
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: tsmith
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/
/*
 * curl --request GET \
--url  \
--header 'api-key: YOUR-API-KEY-HERE'
 *
 */
add_action('rest_api_init', function () {

      register_rest_route('neo-teaching-api/v1', '/teachings', array(
          'methods' => 'GET',
          'callback' => 'neo_teaching_api',
          'permission_callback' => function () {
            return TRUE;
          },
        )
      );
    register_rest_route('neo-teaching-api/v1', '/teachings2.json', array(
            'methods' => 'GET',
            'callback' => 'neo_teaching_api2',
            'permission_callback' => function () {
                return TRUE;
            },
        )
    );
        register_rest_route('neo-teaching-api/v1', '/teachings/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => 'neo_teaching_api_lookup',
                'permission_callback' => function () {
                    return TRUE;
                },
            )
        );
    register_rest_route('neo-teaching-api/v1', '/teachings/(?P<id>\d+)/page/(?P<page>\d+)', array(
            'methods' => 'GET',
            'callback' => 'neo_teaching_api_lookup_paged',
            'permission_callback' => function () {
                return TRUE;
            },
        )
    );
    register_rest_route('neo-teaching-api/v1', '/passage/(?P<passage>[a-zA-Z0-9-_\.]+)', array(
            'methods' => 'GET',
            'callback' => 'neo_teaching_api_passage',
            'permission_callback' => function () {
                return TRUE;
            },
        )
    );
    register_rest_route('neo-teaching-api/v1', '/feedback/(?P<pageid>\d+)', array(
            'methods' => 'GET',
            'callback' => 'neo_teaching_api_feedback',
            'permission_callback' => function () {
                return TRUE;
            },
        )
    );
});

function neo_teaching_api(WP_REST_Request $request) {

    $data = get_transient('neo_teaching_cache');

  if (!$data) {
	wp_reset_query();
	// Only staff members
	$args = array(
	  'posts_per_page' => -1
	  // Set to high number to override default posts limit
	);

	$query = new WP_Query($args);
	$teachings = array();
	if ($query->have_posts()) {
	  while ($query->have_posts()) : $query->the_post();
		$the_id = get_the_ID();

		// Get the media information
          $pptfile='';
		$keys = get_post_custom($the_id);
		$Teaching_enclosure = $keys['enclosure']['0'];
		$Teaching_enclosure_split = explode(PHP_EOL, trim($Teaching_enclosure));
          if(isset($keys['ppt_file'][0])){
              $pptfile = trim($keys['ppt_file'][0]);
          }
		$Teaching_Link = $Teaching_enclosure_split[0];


		// get the cats
		$post_categories = wp_get_post_categories($the_id);
		$cats = array();
		foreach ($post_categories as $c) {
		  $cat = get_category($c);
		  $cats[] = $cat->name;
		}

		array_push($teachings,
		  array(
			'site' => 'neoxenos',
			'title' => get_the_title(),
			'mp3' => clean($Teaching_Link),
            'powerpoint' => $pptfile,
			'permalink' => get_the_permalink(),
			'categories' => $cats
		  ));
	  endwhile;
	}
	// 55 minutes
	set_transient('neo_teaching_cache', $teachings, 3300);

	return $teachings;
  }
  return $data;
}function neo_teaching_api2(WP_REST_Request $request) {

    $data = get_transient('neo_teaching_cache2');

    if (!$data) {
        wp_reset_query();
        // Only staff members
        $args = array(
            'posts_per_page' => -1
            // Set to high number to override default posts limit
        );

        $query = new WP_Query($args);
        $teachings = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
                $the_id = get_the_ID();

                // Get the media information
                $pptfile='';
                $keys = get_post_custom($the_id);
                $Teaching_enclosure = $keys['enclosure']['0'];
                $Teaching_enclosure_split = explode(PHP_EOL, trim($Teaching_enclosure));
                if(isset($keys['ppt_file'][0])){
                    $pptfile = trim($keys['ppt_file'][0]);
                }
                $Teaching_Link = $Teaching_enclosure_split[0];


                // get the cats
                $post_categories = wp_get_post_categories($the_id);
                $cats = '';
                foreach ($post_categories as $c) {
                    $cat = get_category($c);
                    $cats.=$cat->name.', ';
                }

                array_push($teachings,
                    array(
                        'site' => 'neoxenos',
                        'title' => get_the_title(),
                        'mp3' => clean($Teaching_Link),
                        'powerpoint' => $pptfile,
                        'permalink' => get_the_permalink(),
                        'categories' => $cats
                    ));
            endwhile;
        }
        // 55 minutes
        set_transient('neo_teaching_cache2', $teachings, 3300);

        return $teachings;
    }
    return $data;
}
function neo_teaching_api_lookup($request) {

    if(isset($request['group'])){
        $darf=$request['group'];
        $pager = $request['page'];
        $data = get_transient('neo_teaching_cache_l_2_'.$darf.$pager);
    }else{
        $darf = $request->get_params();
        $darf = $darf['id'];
        $data = get_transient('neo_teaching_cache_l_2_'.$darf);
    }


    if (!$data) {
        wp_reset_query();
        // Only staff members
        if(isset($request["page"])){
            $paged = $pager;
        }else{
            $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
        }

        $args = array(
            'posts_per_page' => 10,
            'cat' => $darf,
            'paged' => $paged,
            // Set to high number to override default posts limit
        );
        //$query = new WP_Query( array( 'cat' => 4 ) );

        $query = new WP_Query($args);
        if(sizeof($query->posts)==0){
            if(isset($request["page"])){
                $paged = $pager;
            }else{
                $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
            }
            $categname = get_term( $darf,'series')->name;
            get_term( $darf,'series')->count > 10 ? $categcount = ceil((get_term( $darf,'series')->count)/10) : $categcount = 1;

            $posts_array = get_posts(
                array(
                    'paged' => $paged,
                    'posts_per_page' => 10,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'series',
                            'field' => 'term_id',
                            'terms' => $darf,
                        )
                    )
                )
            );

        }else{
            $posts_array = $query->posts;
            $categname = get_cat_name( $darf);
        }
        $teachings = array();

        if (sizeof($posts_array)>0) {
           // $nextlink = next_posts_link( 'Older Entries',$posts_array->max_num_pages );
           // $prevlink = previous_posts_link( 'Next Entries &raquo;' );
            $paginator = paginate_links( array(
                'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'total'        => $query->max_num_pages>0 ? $query->max_num_pages : $categcount,
                'current'      => max( 1, $paged ),
                'format'       => '?paged=%#%',
                'show_all'     => false,
                'type'         => 'plain',
                'end_size'     => 2,
                'mid_size'     => 1,
                'prev_next'    => false,
                'prev_text'    => sprintf( '<i></i> %1$s', __( '<', 'text-domain' ) ),
                'next_text'    => sprintf( '%1$s <i></i>', __( '>', 'text-domain' ) ),
                'add_args'     => false,
                'add_fragment' => '',
            ) );
            foreach($posts_array as $key => $postx){
                $the_id=$postx->ID;
                $postcontent = wp_strip_all_tags($postx->post_content);
                $post_id = $the_id;
                $post_categories = wp_get_post_categories($post_id);
                $post_categories = get_categories(array('include' => $post_categories,'orderby' => 'name','order' => 'ASC',));
                $postteachers=array();
                $postcats=array();
                $teachid = get_term_by( 'name', 'Teacher', 'category' );
                $teachid =$teachid->term_id;
                foreach($post_categories as $pc){
                    if(!($pc->term_id==$darf)){
                        if($pc->parent==$teachid) {
                            $postteachers[] = ucwords($pc->name);
                        }elseif($pc->term_id==$teachid){
                            continue;
                        }else{
                            $postcats[]=ucwords($pc->name);
                        }
                    }
                }
                if(strlen($postcontent)>100){
                    $postcontent=substr($postcontent,0,96).'...';
                }elseif(strlen($postcontent)<10){
                    $postcontent = ' ';
                }
                $posttitle = ucwords($postx->post_title);
                $postlink = get_permalink($the_id);
                array_push($teachings,
                    array(
                        'title' => $posttitle,
                        'link' => clean($postlink),
                        'content' => $postcontent,
                        'cattitle' => ucwords($categname),
                        'othercats' => $postcats,
                        'teachers' => $postteachers,
                    ));
            }
            array_push($teachings,$paginator);
        //    endwhile;
        }
        if(isset($pager)){
            set_transient('neo_teaching_cache_l_2_' . $darf.$pager, $teachings, 86400);
        }else {
            set_transient('neo_teaching_cache_l_2_' . $darf, $teachings, 86400);
        }
        return $teachings;
    }
    return $data;
}
function neo_teaching_api_lookup_paged(WP_REST_Request $request) {
    $darf = $request->get_params();
    $pagenum = $darf['page'];
    $group = $darf['id'];
    $info = array(
        'page' => $pagenum,
        'group' => $group
    );
    return neo_teaching_api_lookup($info);
}
function neo_teaching_api_feedback(WP_REST_Request $request) {
    $darf = $request->get_params();
    $darf = $darf['pageid'];
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedbackbox';
    $results = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE url = {$darf}", OBJECT );
    //$count = 1;
    if(!empty($results)){
        $wpdb->update(
            $table_name,
            array(
                'count' => $results[0]->count+1,
                'url' => $darf,
            ),
            array(
                'id' => $results[0]->id,
            )
        );
    }else{
        $wpdb->insert(
            $table_name,
            array(
                'count' => 1,
                'url' => $darf,
            )
        );
    }
    return 'Thanks!';
}

function neo_teaching_api_passage(WP_REST_Request $request) {
    $darf = $request->get_params();
    $pagenum = $darf['passage'];
    $passage = str_replace('.',' ',$pagenum);
    $passage = str_replace('_',':',$passage);
    $data = get_transient('neo_passage_'.$passage);
    if(!$data){
        $service_url = 'https://api.scripture.api.bible/v1/bibles/9879dbb7cfe39e4d-01/search?query='.$pagenum;
        $apiKey = '8dea7b61e2dcabd99d0359276a79416b';
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'api-key: ' . $apiKey,
        ));
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additional info: ' . var_export($info));
        }else{
            $ddd = json_decode($curl_response);
            $passage_arr = array(
                'passage_html' => $ddd->data->passages[0]->content,
                'passage_title' => $ddd->data->passages[0]->reference,
            );
            set_transient('neo_passage_'.$passage, $passage_arr, 999999986400);
            return $passage_arr;
        }
    }
    return $data;
}

function clean($str) {
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