<?php
$myStartTime=microtime(true);

define("IN_TEST",true);

include_once("../neo-base.inc");

if (!NeoSess::get('kdb')) {
    die('Sorry Houston, no kdb - you should know better.');
}

if ($_GET['test']) NeoSess::set('test',$_GET['test']);
else NeoSess::delete('test');

if (isset($_REQUEST['pluginsurl'])) {
    NeoSess::set('NeoPluginsUrl',$_REQUEST['pluginsurl']);
    NeoSess::set('test','NEOTEST-NEOPOST');
};

/**
* Main method, baby...
*
*/
function runTest()
{
    $tests=getTest();

    $idx=@NeoSess::get('test');
    $handl=@$tests[$idx];
    if ($handl)
    {
        $parms=@$handl[2];
        NEO::$fulltrace=true;
        NeoHooks::addHandler($idx,array('filepath' => $handl[1]));
        NeoHooks::action($idx,$parms);
        //NeoHooks::action('kdb',array('kdbdata' => NEO::$myQ , 'kdbmsg' => 'NEO::$myQ'));
        displayHelp("** $idx DONE **");
        include_once("footer.inc");
    }
    else
    {
        echo "<h2>No Test Selected...$idx</h2>";
        displayHelp('hint: use '.basename(__FILE__).'?test=TESTNAME');
    }

}
function getTest($which=null)
{
    $myPath=dirname(__FILE__);
    $tests = array
    (
        //"NEOVIEWS-TEST4" => array('Views: Format a string',$myPath.'/test-views.inc'),
        "Errors...",
        'NEOTEST-ERRDISPLAY' => array('Display Errors', __FILE__),
        'NEOTEST-CLEARERRS' => array('Clear Errors', __FILE__),
        'NEOTEST-NEOPOST' => array('Post via WP Back Door', __FILE__),
        ##"Views..." ,
        "Views...",
        "NEOVIEWS-TEST2a" => array('NeoTPL.parse() SHORT test',$myPath.'/test-views.inc','short'),
        ##"NEOVIEWS-TEST2" => array('NeoTPL.parse() test',$myPath.'/test-views.inc'),
        ##"NEOVIEWS-TEST3" => array('Display PHP results',$myPath.'/test-views.inc'),
        ##"NEOVIEWS-TEST1" => array('NeoViews.run() test',"$myPath/test-views.inc"),
        ##"NEOVIEWS-TEST5" => array('NeoViews HOOK Basic test',"$myPath/test-views.inc"),
        ##"NEOVIEWS-TEST6" => array('NeoViews Advanced test',"$myPath/test-views.inc"),
        'Messaging...',
        'TEST-Messaging' => array('NeoMsg - test basic messaging', $myPath.'/test-msg.php','Basic-Messaging'),
        'TEST-Send' => array('NeoMsg - test sending', $myPath.'/test-msg.php','Neo-Send'),
        'TEST-Alerts' => array('NeoMsg - send alerts.', $myPath.'/test-msg.php','Neo-Alerts'),
        'Misc...',
        "neo-sitebuttons"  => array("Sitebuttons",NEOSHARED_PATH . '/neo-sitebuttons.php'),
        "MY-KDB"  => array("KDB Debug Test",__FILE__),
        'NEOSESS-TEST1' => array('NeoSess - test basic session support', $myPath.'/test-sess.php',),
        'NEOTEST-PHPINFO' => array('Phpinfo', __FILE__),
        'NEOTEST-LOGIN' => array('Login Data Display', __FILE__),
        'NEOTEST-HELP' => array('Help' , __FILE__),
    );
    if (!$which) return $tests;
    else return @$tests[$which];
}
function clearErrs()
{
    include_once(NEOSHARED_PATH."/flourish/fFile.php");
    include_once(NEOSHARED_PATH."/flourish/fDirectory.php");
    include_once(NEOSHARED_PATH."/flourish/fImage.php");

    $fdir=new fDirectory(NEOSHARED_PATH."/outbox");
    $fdir->delete(); ##NEOSHARED_PATH."/outbox.old",true);
    fDirectory::create(NEOSHARED_PATH."/outbox");
}
NeoHooks::addAction('NEOTEST-CLEARERRS','clearErrs');
function testWP()
{
    if (NeoSess::set('shortcodes')) $codes=NeoSess::set('shortcodes');
    else $codes="Enter Shortcodes...";
    if (!NeoSess::get('NeoPluginsUrl')) { ?>
    <form action="index.php">
    <p>Need Plugins URL: <input name='pluginsurl' id='pluginsurl' value="" /> <input type="submit" value="Submit" /></p>
    </form> <?
    } else {
        $postTo=NeoSess::get('NeoPluginsUrl')."/neopost.php"; ?>
    <form action="<?=$postTo?>">
    <p>Process shortcodes:<br /><textarea name='do_short' id='do_short' rows="13" cols="57"/><?=$codes?></textarea>
    <input type="hidden" name="shortcode" value="shortcode" id="shortcode" /><br />
     <input type="submit" value="Run" /></p>
    </form>  <?
    }
}
NeoHooks::addAction('NEOTEST-NEOPOST','testWP');
function errDisplays()
{
    include_once(NEOSHARED_PATH."/flourish/fURL.php");
    fURL::redirect(NEOSHARED_URL."/outbox");
}
NeoHooks::addAction('NEOTEST-ERRDISPLAY','errDisplays');
function myKDB()
{
    include_once("neo-debug.php");
    NeoHooks::action('kdb',array('kdbdata' => getTest() , 'kdbmsg' => 'display getTest()'));
}
NeoHooks::addAction('MY-KDB','myKDB');
function displayHelp($msg='')
{
    static $displayed=false;
    if ($displayed) return;
    $tt=getTest();
    $txt="";
    foreach ($tt as $kk => $vv)
    {
        if (is_array($vv))
        {
            $linker=NEOSHARED_URL.'/testbed/?test='.$kk;
            $txt .= "<li><a href='$linker'>{$vv[0]}</a></li>\n";
        } else $txt .= "<li class='title'><h2>$vv</h2></li>";

    }
    if (empty($msg)) $msg = "done";
    $msg="<p>$msg</p>";
    include_once("header.inc");
    ?>
    <h1>Available tests:</h1>
    <ul><?=$txt;?></ul>
    <?=$msg;

    NeoHooks::action('kdb',array('kdbdata' => 'session' , 'kdbmsg' => 'session'));

    include_once("footer.inc");
    $displayed=true;
}

function runPhpInfo()
{
    include_once("header.inc");
    var_dump($_SESSION);
    phpinfo();
    displayHelp();
    include_once("footer.inc");
}
function testBegin()
{
    if (NeoSess::get('test')) echo "<hr>\n<p>Begin test: ".NeoSess::get('test');
    echo "</p>\n<hr>";
    return true;
}
function testEnd()
{
    global $myStartTime;
    $diff=microtime(true)-$myStartTime;
    $rc = NeoMsg::get('*');
    $tst=NeoSess::get('test');

    echo "<p>" . implode("</p><p>", is_array($rc) ? $rc : array($rc) ) . "</p>";

    if ($tst) echo "<p><center>Test $tst completed in $diff seconds.</p>";
    return true;
}
function runLoginTest()
{
    include_once("header.inc");
    include_once(NEOSHARED_PATH."/neo-login.php");
    var_dump(NeoLogin::getLogin(true));
}

NeoHooks::addAction("TEST-BEGIN",'testBegin');
NeoHooks::addAction("TEST-END",'testEnd');
NeoHooks::addAction("NEOTEST-PHPINFO",'runPhpInfo');
NeoHooks::addAction("NEOTEST-LOGIN",'runLoginTest');
NeoHooks::addAction("NEOTEST-HELP",'displayHelp');

runTest();



?>
</body>
</html>

