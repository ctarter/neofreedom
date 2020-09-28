<?php
/**
* Templating test
*/
#if (!defined('NEOSHARED_PATH')) {
#    die("Don't call directly:");
#}
include_once("../neo-base.inc");
include_once(NEOSHARED_PATH."/neo-templates.inc");

function testView()
{
    testViewHook();
}
/*
function testViewAdvanced()
{
    $args=array();
        //include_once(NEOSHARED_PATH.'/neo-views.inc');
    $defaults=array('view' => 'view-featured1-col3',
                    'content' => "Ye Olde Template",
                    'opts' => array('DEBUG' => true),
                    'blogname' => "My coolistical Blog",
                    'handlers' => array('neo-view-defaults' => 'view-featured-init.php'),
                    'query' => 'post_type=post&meta_key=Edition&meta_value=2009-07');
    if (empty($args)) $args=$defaults;
    else $args=array_merge($defaults,$args);
    if (empty($args['view'])) {
        echo "No View";
        return;
    }
    //echo "views got: ".implode('<br>',$args);
    //return;
    if (!empty($args['query']) && function_exists('get_posts'))
    {
        $myposts=get_posts($args['query']); ##apply_filters('query-posts',null,$args); #'neo-query-posts',null,$args); get_posts($args['QUERY']);
        if (!empty($myposts)) {
            $args['POSTS'] = $myposts;
        } else $args['ERRORS'][] = "No Records returned from query.";
    }
    $args['opts']['DEBUG']=true;
    NeoViews::run($args);
    //NeoHooks::action('neoviews-run',$args);
}
function testViewHook()
{
    include_once('header.inc');

    NeoHooks::loadHandler('neoviews-run');
    ##
    ## Primary Initialization
    ##

    //NeoHooks::initHandlers(array('neoviews-init','neoviews-run','neoviews-config'),NEOSHARED_PATH.'/neo-views.inc');

    $args=array();
    $args['content'] = "the_content() WOULD APPEAR HERE as a {:siteurl:}";
    $args[NeoViews::keyView] = 'enews-view-confirm';
    neoMailPressTest($args,true);
    return;

    $args[NeoViews::keyTemplates] = array('test-sidebar','test-header','test-footer');   ## view-parts

    ##
    ## Content
    ##

    $args['content-type'] = 'text/html; charset=iso-8859-1';
    $args['sb-content'] = '--coming soon-- stop in at {:site-url:} for more';
    $args['recs'][] = array('title' => 'Still Crazy After All These Years',
                                'author' => "Keith McCallum",
                                'date' => "9/22/56",
                                'excerpt' => 'This is my excerpt ready to rock & roll...');
    $args['recs'][] = array('title' => 'Still Crazy After 1 Year',
                                'author' => "Steve McCallum",
                                'date' => "9/22/2006",
                                'excerpt' => 'This is a second excerpt ready to rock & roll...');
    $args['recs'][] = array('title' => 'NOT Crazy After All These Years',
                                'author' => "Frank McCallum",
                                'date' => "10/22/2006",
                                'excerpt' => 'This is a THIRD excerpt ready to rock & roll...');
    $args['logo-url'] = '/images/xenos/xenos-logo-90-grey.png';
    $args['view-url'] = 'http://termans';
    $args['masthead-img'] = '/images/lines/degrade.jpg';

    ##
    ## Styles
    ##

    $args['p-style'] = 'line-height:1.4em;';
    $args['top-style'] = "style='text-align:center;color: rgb(153, 153, 153);font-family: verdana,geneva;'";
    $args['small-style'] = "style='color:#777;font-family:Arial,Sans-Serif;font-size:0.7em;line-height:1.5em;'";
    $args['masthead-style'] = "style='display:block;height=1.45em;'";
    $args['masthead-img-style'] = "style='width:100%;height:10px;border:none;padding:5px 0;'";
    $args['masthead-left-style'] = "style='float:left;font-family:verdana,geeva;sans-serif;padding:0;margin:0;'";
    $args['masthead-right-style'] = "style='float:right;font-family:verdana,geneva;sans-serif;color:#590000'";
    $args['content-style'] = "style='float:left;margin:0 45px;padding:0;width:auto;text-align:left;color:#333333;font-family:Verdana,Sans-Serif;'";

    $args['post-block-style'] = "style='display:block;margin:0pt 0pt 40px;text-align:justify;'";
    $args['post-title-style'] = "style='list-style:none;color:#D76716;font-size: 1.3em;'";
    $args['post-meta-style'] = "style='list-style:none;color:#777;font-family:Arial,Sans-Serif;font-size:0.7em;line-height:1.5em;'";
    $args['post-excerpt-style'] = "style='list-style:none;style='font-size:.85em; line-height:1.2em;'";
    $args['link-style'] = 'style="color:#000;"';
    $args['img-style'] = 'border:none;margin:0;padding:0';
    $args['content-style'] =  "style='font-size:.85em; line-height:1.2em;'";
    $args['td-content-style'] =
            "style='vertical-align:top; float:left;margin:0 45px;padding:0;width:auto;text-align:left;color:#333333;font-family:Verdana,Sans-Serif;'";
    $args['table-style'] = ' style="width:100%;border:none;"';
    $args['title-style'] = "style='margin:30px 0pt 0pt;text-decoration:none;color:#333333;font-size:1.1em;font-family:Verdana,Sans-Serif;font-weight:bold;'";
    $args['title2-style'] = "style='color:#D76716;font-size: 1.3em;'";
    $args['sb-divstyle'] = "style='margin:0;padding:0;width:100px;font-family:Verdana,Sans-Serif;font-size:1em;font-size-adjust:none;font-stretch:normal;'";#
    $args['sidebar-style'] = "style='font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;color:#D76716;'";
    $args['td-sidebar-style'] =  "style='vertical-align:top;border-left: 1px solid;padding:20px;width:120px;text-align:left;color:#333333;font-family:Verdana,Sans-Serif;'";

    ##
    ## Const
    ##

    $args['siteurl'] = "http://neozine.org";
    $args['blogname'] = "NeoZine";
    $args['pubdate'] = date('F j, Y');
    $args['sb-title'] = 'Useful Links at {:siteurl:}';

    ##
    ## GO!!
    ##

    NeoHooks::action('neoviews-run',$args);

}

*/

/**
* Test the template parser
*
* @todo VIEWS Testing
*
*/
function testTemplate($tname=null)
{
    //if (!$tname) {
        $tname="block.tpl"; #array("body.tpl","header.tpl","footer.tpl");
    //}
    $tpl=new NeoTPL($tname);
    $tpl->put('title=Test Template&book[0][col1]=Test1&book[0][col2]=This1');
#    $tpl->put(array('book' => array('col1'=>'Test','col2' => 'THIS')));
#    $tpl->put(array('book' => array('col1'=>'Test1','col2' => 'THIS1')));
    //$tpl->init('col1=hi&col2=there');
    //$tpl->put('book:col1=Test1');
    //$tpl->put('book:col2=Test2'); # &book:col2=THIS2
    $tpl->run();
}

testTemplate();
?>
