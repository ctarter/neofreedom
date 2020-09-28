<?php
/**
*test run
*/
?>

<?
include_once("header.inc");
NeoHooks::action('kdb',array('kdbdata' => 'includes' , 'kdbmsg' => 'includes'));
?>

<p>Help: <a href="http://neoxenos.net/neoshared/krumo/docs/index.html">Read Help</a></p>
<div>

<?
if(!empty($_REQUEST['clearit']))
{
    NeoHooks::action('neosess-delete',$_REQUEST['clearit']);
    echo "CLEARING RESULTS:";
    NeoHooks::action('kdb','request');
}
if(!empty($_REQUEST['addit']))
{
    NeoHooks::action('neosess-set', $_REQUEST['nameit'],$_REQUEST['addit']);
    echo "ADDING RESULTS:";
    NeoHooks::action('kdb','request');
}
if(!empty($_REQUEST['krumoit']))
{
    NeoHooks::action('kdb', $_REQUEST['krumoit']);
    echo "KRUMO Command Executed";

}

    //NeoHooks::addFilter('neo-sitebutt',array(&$neoSitebuttons,'makeButtons'));
    NeoSess::set('HI','herenow with this');

    //if (!($rc=NeoHooks::filter('neosess-get','HI'))) $msg = "Session Var HI is empty";
    //else $msg=$rc;
    //NeoHooks::action('kdb',$msg);

    $rc=NeoHooks::filter('neosess-get','HI');
    if ($rc) NeoHooks::action('neo-logit',"Got an RC");
    //NeoHooks::action('kdb','session');
    var_dump($_SESSION);

    NeoHooks::action('kdb','server');
    ?></div>
    <p><?=isset($_SERVER['HTTP_REFERER']) ? '<a href="' . $_SERVER['HTTP_REFERER'] . '">Return >></a>' : "No HTTP_REFERER"; ?></p>
    <form action="test-sess.php">
    <p>Delete Session Value: <input name='clearit' id='clearit' value="" /> <input type="submit" value="clearsess" /></p>
    </form>
    <form action="test-sess.php">
    <p>Create Session Value: <input name='addit' id='addit' value="" /> NAME IT: <input name='nameit' id='nameit' value="" /> <input type="submit" value="addsess" /></p>
    </form>
    <form action="test-sess.php">
    <p>Issue Krumo Command: <input name='krumoit' id='krumoit' value="" />  <input type="submit" value="krumocmd" /></p>
    </form>
<?
echo NeoBase::getLog();

if (!defined('IN_TEST')) {
    include_once('footer.inc');
}
?>
