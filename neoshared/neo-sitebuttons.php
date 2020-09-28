<?php
/*
Plugin Name: Neo-Sitebuttons
Plugin URI: http://www.kmccallum.net/dev
Description: Format Sitebuttons
Author: KMcC
Version: 0.3.4
Author URI: http://kmccallum.net
*/
include_once('neo-base.inc');
include_once('neo-hooks.inc');
include_once('neo-msg.inc');
class NeoSiteButtons {
    static $butts  = array(
                        'Debug' => array('img' => 'kdb.gif', 'url' => ''),
                        'Login' => array('img' => 'login.png','url' => '/wp-login.php'),
                        //'Basecamp2' => array('img' => 'basecamp2.gif', 'url' => 'http://neoxenos.net/'),
                        'Basecamp' => array('img' => 'basecamp.gif', 'url' => 'http://neoxenos.net/'),
                        'Forums' => array('img' => 'forums.png','url' => 'http://neoxenos.net/forums/index.php?action=forum"'),
                        'Blogosphere' => array('img' => 'blogs.gif','url' => 'http://neoblogs.org'),
                        'NeoZine' => array('img' => 'neozine.gif','url' => 'http://neozine.org'),
                        'Gallery' => array('img' => 'gallery2.gif','url' => 'http://neonets.org/gallery'),
                        //'Sitemap' => array('img' => 'sitemap.gif','url' => 'http://neoxenos.info'),
                        'Podcasts' => array('img' => 'podcast.gif','url' => 'http://neoxenos.org/podcasts'),
                        //'Slumnet' => array('img' => 'go-net.gif','url' => 'http://neonets.org/go'),
                        'Admin' => array('img' => 'admin.gif','url' => '/wp-admin/'),
                        'Logout' => array('img' => 'logout.png','url' => '/wp-login.php?action=logout&redirect_to=%2Findex.php')
                        );

    static $img_url = '';

    static function getSiteNames()
    {
        return array_keys(self::$butts);
    }
    /**
    * Get site button list
    *
    * @param mixed $args - either 'sites' or 'exceptions'
    */
    static function makeButtons($args=null)
    {
        $sites=null;
        $op=NeoMsg::get('op','NeoSiteButtons');
        switch($op)
        {
            case 'reset':
                self::actionResetButtons();
                break;
            default:
        }
        $exceptions=array();
        if ($args) extract($args,EXTR_OVERWRITE);
        if (is_string($exceptions)) $exceptions = explode(',',$exceptions);
        $nexceptions=array();
        foreach( $exceptions as $vv ) {
            $nexceptions[] = trim($vv);
        }
        $exceptions=$nexceptions;
        $userid=NeoHooks::filter('neosess-get','userid');

        if (!isset($userid))
        {

            $exceptions[] = "Login";
        }
        else
        {
            $exceptions[] = "Logout";
            $exceptions[] = 'Admin';
        }

        if (NeoHooks::filter('neosess-get', 'is_admin')) $exceptions[] = "Admin";

        $img_url = NEOSHARED_URL.'/images/buttons';
        if (!$sites) $sites = self::getSiteNames();
        else $sites = explode(',',$sites);

        $absurl = NeoHooks::filter('neosess-get', 'wp_url');
        if($absurl) $tools="http://{$absurl}/wp-admin/tools.php?page=neo-formatting-class.php";
        if (NeoHooks::filter('neosess-get','kdb'))
        {
        $arr=self::$butts['Debug'];
        $arr['url']=NEOSHARED_URL."/testsess.php?debug=1";
        self::$butts['Debug']=$arr;
        }

        $rc = '';

        foreach(self::$butts as $key => $arr)
        {
            switch($key)
            {
                case 'Logout':
                {
                if(function_exists('wp_logout_url'))
                    NeoHooks::action('neosess-set', "LOGOUT_URL", wp_logout_url());
                $uri = NeoHooks::filter('neosess-get', 'LOGOUT_URL');
                break;
                }
                case 'Admin':
                {
                    $uri =  $absurl.$arr['url'];
                    break;
                }
                default:
                    $uri = $arr['url'];
                    break;
            }
                if (!$uri) continue;

            if (!in_array($key,$exceptions) && in_array($key,$sites))
            $rc .= "<span class=\"img\"><a href=\"{$uri}\" title=\"{$key}\"><img alt=\"{$key}\" border=\"0\" src=\"{$img_url}/{$arr['img']}\"></a></span>\n";
        }

        $rc = '<div class="sitebuttons" style="display:block; width: 100%; margin:auto; background-color:transparent"><center>'.$rc.'</center></div>';

        //if(in_array('Login',$exceptions)) $varName = 'SITEBUTTSLOGOUT';
        //else $varName='SITEBUTTSLOGIN';

        //NeoSess::set($varName,$rc);

        return $rc;
    }
    static function actionResetButtons()
    {
        NeoSess::delete('SITEBUTTSLOGIN');
        NeoSess::delete('SITEBUTTSLOGOUT');
    }
    /**
    * action
    */
    static function actionMakeButtons()
    {
        //$sb=null;
        //ini_set('display_errors', 1);
        //ini_set('log_errors',1);
        //ini_set('error_log',NEOSHARED_PATH."/inbox/err_log.txt");
        //if (!$sb)
        //if (NeoSess::get(KDB_FLAG))
        //$vv="<pre>".var_export($_SESSION,true)."</pre>";
        //die ($vv);
        //self::actionResetButtons();
        echo self::makeButtons();
        //phpinfo();
    }

}

NeoHooks::addAction('neo-sitebuttons','NeoSiteButtons::actionMakeButtons');
NeoHooks::addAction('neo-sitebuttons-reset','NeoSiteButtons::actionResetButtons');

?>
