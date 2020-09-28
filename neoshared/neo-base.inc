<?php

if (!defined('NEOSHARED_URL')) define('NEOSHARED_URL',"http://".$_SERVER['SERVER_NAME']."/neoshared");
if (!defined('NEOSHARED_PATH')) define('NEOSHARED_PATH',dirname(__FILE__));

//set_include_path(get_include_path().":".NEOSHARED_PATH.":".NEOSHARED_PATH."/flourish:");
include_once("neo-config.inc");
include_once("flourish/fCore.php");
include_once("flourish/fSession.php");
include_once("flourish/fMessaging.php");
include_once("flourish/fFilesystem.php");
include_once("neo-timestamp.php");
include_once("neo-debug.php");


class NEO
{
    const OK = 0;
    const INFO = 1;
    const WARN = 2;
    const ERR = 3;
    const CRITICAL=100;

    static $kdb=false;
    static $myQ=array();
    static $fulltrace=false;
    static $status=array(self::OK =>'OK',self::INFO => 'Info',self::WARN => 'Warning!', self::ERR => 'ERROR!', self::CRITICAL => "**CRITICAL**");
}

class NeoBase extends NEO
{
    //static $config=null;

    static function init()
    {
        return;
    }
    static function logit($msg, $status=NEO::INFO, $file=null, $line=null)
    {
        if (!$file || !$line) $caller = getMyCaller(__FILE__,__LINE__);
        else $caller="$file:$line";
        if ($status>NEO::INFO) kdbErr($msg,$file,$line);
    }
    static function getLog($before="\n",$after="\n",$sep="\n")
    {
        return $before . implode($sep,NEO::$myQ) . $after;
    }
}

include_once('neo-sess.inc');
include_once('neo-msg.inc');
include_once('neo-hooks.inc');

function neoDumpVars($keyVals)
{

        echo '<table class="neo-table">';
        ##echo "<tr><th>NeoMagStyle Key:</th><th>Wordpress DB Value:</th></tr>";
        //$myOptions=self::getOption();
        if (is_string($keyVals)) $keyVals=array($keyVals);
        foreach($keyVals as $kk => $vv) {
            if (is_array($vv)) {
                echo "<tr><td>$kk</td><td>";
                neoDumpVars($vv);
                echo "</td></tr>";
            } else echo "<tr><td>$kk</td><td>$vv</td></tr>";
        }
        echo "</table>";
}


function neoRC($id,$rc,$status=NEO::OK,$logMsg="no log message",$receiver=null)
{
    if (is_object($id)) $id=get_class($id);
    if ($logMsg) NeoBase::logit("{$id} - {$logMsg}",$status);

    //if ($status >= NEO::ERR || (NEO::$kdb && $status >= NEO::WARN) || NEO::$fulltrace)
    $arr=getMyCaller(__FILE__,__LINE__,'array');
    if ($status >= NEO::ERR) {
        kdbErr("Error From $id, Mesage: $logMsg",$arr[0],$arr[1]);
        NeoMsg::put('err',$logMsg);
        if ($receiver) NeoMsg::put('err',$logMsg,$receiver);
    };
    NeoMsg::put('rc',$logMsg);
    return $rc;
}

NeoBase::init();

NeoHooks::addAction('neo-logit','NeoBase::logit');

?>
