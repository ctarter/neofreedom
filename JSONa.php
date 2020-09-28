<?php
// include our wordpress functions
// change relative path to find your WP dir

define('WP_USE_THEMES', false);
require('./wp-blog-header.php');
header('Content-type: application/json');

// set header for json mime type

class Error {
    public $teachingID;
    public $title;
    public $date;
    public $url;
    public $catagories;
    public $author;
    public $extra;
    public function __construct(){
    	$teachingID = '';
    	$title = '';
    	$teachingDate  = '';
    	$url  = '';
    	$catagories  = '';
    	$author  = '';
    	$extra = '';
    }
    public function toJSON(){
    	$json = array(
    		'teachingId' => $this->teachingID,
        	'title' => $this->title,
        	'teachingDate' => $this->teachingDate,
        	'url' => $this->url,
        	'catagories' => $this->catagories,
        	'author' => $this->author,
        	'extra' => $this->extra,
        	);

    return json_encode($json);
    }

}
// get latest single post
// exclude a category (#5)
$args = array( 'numberposts' => 500);
$myresponse = get_posts( $args);
$stack = array();

foreach ($myresponse as $res): setup_postdata($res);

$keys = get_post_custom($res->ID);
$keys = $keys['enclosure']['0'];
$help = explode('/', trim($keys));
$help = explode('.mp3', trim($help['6']));
$final = $help['0'];
$temp3 = "http://repo.neoxenos.org/public/audio/podcasts/" . $final . ".mp3" ; 

   	    $teachingJson = new Error();
   	    $teachingJson->teachingID = $final; 
   	    
		$teachingJson->title = $res->post_title; 
		$teachingJson->teachingDate = substr($res->post_date, 0, 10); 
		$teachingJson->author =	get_the_author();
		$teachingJson->url = $final. ".mp3" ;
		array_push($stack, $teachingJson);

endforeach; 

echo json_encode($stack);
?>