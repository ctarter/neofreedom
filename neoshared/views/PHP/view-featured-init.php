<?php
/**
* Default settings for view-featured...
*/
function viewFeaturedInit($defaults)
{

    $defaults['fields'] = 'id,title,permalink,date,thumbnail,subtitle,contents,excerpt,more-link,author';
    $defaults['featured-block'] = 'ind-post';
    $defaults['meta-block'] = 'meta';
    $defaults['content-block'] = 'storycontent';
    $defaults['columns-class'] = 'threecol';
    $defaults['column-ids'] ='threecol2';
    $defaults['POSTS'] = array();
    $defaults['view-top'] = '';
    $defaults['view-bottom'] = '';
    $defaults['counter'] = 1;
    $defaults['dates'] = "no";
    $defaults['authors'] = "no";
    return $defaults;
}
NeoHooks::addFilter('neo-view-defaults','viewFeaturedInit',2);

?>
