<?php
/**
* Debug Interface Class
*
*
*/
if (!defined("NEOSHARED_PATH")) die("Can't call directly.");

define('NEO_ERR_LOG',NEOSHARED_PATH."/outbox/neoerr_log.txt");
define('NEO_ERR_LOG_NOTIFIED',NEOSHARED_PATH."/outbox/errors_notified.txt");
define('KDB_FLAG','kdb');


##
## Error Logging
##
ini_set('log_errors',1);
ini_set('error_log',NEO_ERR_LOG);
error_reporting(E_ERROR|E_CORE_ERROR);
ini_set('xdebug.auto_trace',0);
ini_set('html_errors',1);
ini_set('xdebug.default_enable',0);
ini_set('display_errors',0);


##
## QUERY PARAMS - SETUP DEBUG CONDITIONS
##
include_once("neo-sess.inc");
//include_once('neo-message.inc');

if (!empty($_COOKIE[KDB_FLAG])) $_REQUEST[KDB_FLAG] = $_COOKIE[KDB_FLAG];
if (!empty($_GET[KDB_FLAG])) $_REQUEST[KDB_FLAG] = $_GET[KDB_FLAG];
if (isset($_REQUEST[KDB_FLAG])) {
    if ($_REQUEST[KDB_FLAG] =="false") $_REQUEST[KDB_FLAG]=false; ## UNSET DEBUG CONDITIONS
    if (!$_REQUEST[KDB_FLAG]) NeoSess::get(KDB_FLAG);
} else $_REQUEST[KDB_FLAG] = NeoSess::get(KDB_FLAG);

# TODO: Get rid of direct external dependancies
include_once("flourish/fException.php");
include_once("flourish/fExpectedException.php");
include_once("flourish/fUnexpectedException.php");
include_once("flourish/fProgrammerException.php");
include_once("flourish/fValidationException.php");
include_once("neo-msg.inc");

/**
* Find out if we're in debug-mode or perform other debug operations.
*
* @return boolean
*/
function isKDB($op='check')
{
    switch ($op)
    {
        case 'on':
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            ini_set('xdebug.auto_trace',1);
            ini_set('xdebug.default_enable',1);
            ini_set("xdebug.show_exception_trace",1);
            ini_set('xdebug.auto_trace',1);
            ini_set('error_log',$_SERVER['DOCUMENT_ROOT']."/neoshared/outbox/phperrors.txt");
            ini_set('log_errors','1');
            ini_set('implicit_flush',1);


            NeoSess::set(KDB_FLAG,date('n/j/y g:m.s a'));
            ##
            ## Create file-specific error logs
            ##
            $errFile=str_replace("/","-",$_SERVER['SCRIPT_NAME']);
            $ee=NEOSHARED_PATH."/outbox/errors-{$errFile}.txt";
            if (file_exists($ee)) unlink($ee);
            $ee=NEOSHARED_PATH."/outbox/err_log.txt";
            if (file_exists($ee)) unlink($ee);

            fCore::enableDebugging(true);
            fCore::enableErrorHandling($ee);

            $ee=NEOSHARED_PATH."/outbox/exceptions-{$errFile}.txt";
            if (file_exists($ee)) unlink($ee);
            //error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
            fCore::enableExceptionHandling($ee);
            error_log("Debug Mode Started.");
            include_once("krumo/class.krumo.php");

            return true;
        case 'off':
            if (ini_get('display_errors'))
            {
                ini_set('display_errors', 0);
            }
            error_log("Debug Mode STOPPED.".getMyCaller(__FILE__,__LINE__));
            error_reporting(E_ERROR);
            # UNSET COOKIE...
            if (isset($_COOKIE[KDB_FLAG])) setcookie(KDB_FLAG,NULL,time()-(60*60*24*30),'/');
            if (NeoSess::get(KDB_FLAG))     // Last session knew of debugging
            {
                NeoMsg::put('op','reset','NeoSiteButtons');
                NeoSess::delete(KDB_FLAG);
            }
            fCore::enableDebugging(false);
            return true;
        case 'check':
        default:
            return NeoSess::get(KDB_FLAG,false);
   }

}
NeoConfig::setOption('NEO-HANDLERS', 'kdb-status',array('handler' => 'isKDB'));

class NeoException extends fUnexpectedException
{
    public function formatTrace()
    {
        $str=parent::formatTrace();
        return str_replace('{doc_root}',"\n",$str);
    }
    public function printMessage()
    {
        echo "Thrown by:".$this->file . " in line:".$this->line;
        parent::printMessage();
        if (true) $this->printTrace();
    }
}
##
## 1 PHP Err Log / Day, and remember most-recent-error-time
##
if (file_exists(NEO_ERR_LOG))
{
    $lastErrTime=date('Y-n-j.g.m.s.a',filemtime(NEO_ERR_LOG));
    if (date('j',filemtime(NEO_ERR_LOG)) != date('j'))
    {
        $dt=date('j',filemtime(NEO_ERR_LOG));
        $newname=NEO_ERR_LOG."-day-$dt";
        if (file_exists($newname) ) unlink($newname);
        rename(NEO_ERR_LOG,$newname);
    }
    NeoSess::set(NeoSess::keyLastErrTS,$lastErr);        ## Most-recent Error Time
}
else $lastErrTime=NeoSess::get(NeoSess::keyLastErrTS);

##
## PRODUCTION ENVIRONMENT SETTINGS
##
if(!empty($_REQUEST[KDB_FLAG]))
{
     isKDB('on');
}
else isKDB('off');

##
## DEBUG ENVIRONMENT SETTINGS
##
function kdbDebugMode()
{
    isKDB('on');
}

/**
* Record-an-error: always available
*
* @param string $errInfo
* @param mixed $file originator
* @param mixed $line origination
* @deprecated mixed $context
*/
function kdbErr($errInfo,$file=null,$line=null,$context=null)
{
    $lastErrTS = date('Y-n-j.g.m.s.a');

    if (is_array($errInfo)) $errInfo = implode("\n",$errInfo);
    $errInfo="\n{$errInfo}\n";

    fCore::handleError(E_USER_ERROR,$errInfo,$file,$line,$context);      # Flourish Err Logging
    error_log($errInfo,E_NOTICE);                                    # PHP Err Logging
    if (isKDB()) kdbAudit($errInfo);
}

function getMyCallerShortcode($args)
{
    $skipThese=array('neo-debug'); ##plugin.php','neo-hooks','neo-debug','neo-base.inc');
    $file="unknown";
    $output='string';
    $line=0;

    //if (!empty($args)) extract($args);

    static $xDebug=false;
    if (false && $xDebug===null)
    {
        if (function_exists('xdebug_call_file')) $xDebug=true;
        else $xDebug=false;
    }

    if (false) { ## eliminate $xDebug
        $file=xdebug_call_file();
        $line=xdebug_call_line();
    }
    else
    {
        $callStack = debug_backtrace();
        $trace='';

        foreach( $callStack as $caller) {
            $skipit=false;
            $file=basename(@$caller['file']);
            if (empty($file)) $file=var_export($caller,true)."<br>\n";

            $line=@$caller['line'];
            $trace .= " $file:$line\n";
            foreach($skipThese as $skip) {
                if(strpos($caller['file'],$skip)) {
                    $skipit=true;
                    break;
                }
            }
            if ($skipit || (strpos($args['file'],$file) && $args['line']==$line)) continue;
            else
            {
                break;
            }
        }
        if (empty($file)) $file=$trace;
    }


    $rc = ($output=='array') ? array($file,$line) : "{$file}:{$line}";
    return $rc;
}

function getMyCaller($file,$line,$output='string')
{
    return getMyCallerShortcode(array('file' => $file, 'line' => $line, 'output' => $output));
}
NeoConfig::setOption('NEO-HANDLERS', 'neo-backtrace',array('handler' => 'getMyCaller', 'args' => 2));

##
## BEGIN DEBUG-ONLY CODE
##
    ##  include_once("neo-hooks.inc");

    //include_once("neo-help.inc");
    static $kdbQ=array();

    ##
    ## kmcc new debug interface see http://flourishlib.com/api/fCore#expose
    ##

    //fCore::enableDebugging(true);
    ## fCore::disableContext();


    //error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    //error_reporting(E_ALL);


    /**
    * dump raw value (var_export)
    *
    * @param mixed $var
    * @example do_action('kdb-raw',$var)
    */
    function kdbRaw($var)
    {
        if (!isKDB()) return;
        #echo '<div class="krumo-root" style="clear:both;margin:20px;"><ul><li>';
        #echo "<pre>";
        fCore::expose($var);
        #echo "</pre>";
        #echo "</li><li>
        #Backtrace:</li>
        #<li>";
        #$str=fCore::backtrace(3);
        #$str=str_replace("{doc_root}","<br>{doc_root}",$str);
        #echo $str;
        #echo "</li><ul></div>";
    }
    NeoConfig::setOption('NEO-HANDLERS', 'kdb-raw',array('handler' => 'kdbRaw'));
    function kdbMsg($msg,$opts=null)
    {
        if (!isKDB()) return;
        if(!is_string($msg)) $msg=var_export($msg,true);
        $msg=htmlspecialchars($msg,ENT_QUOTES);
        $caller=getMyCaller(__FILE__,__LINE__);
        kdbAudit("Message: $msg");
    }
    NeoConfig::setOption('NEO-HANDLERS', 'kdb-msg',array('handler' => 'kdbMsg'));
    Function kdbDump($args)
    {
        if (!isKDB()) return;
        //kdbAudit("HERE");
        // 2 Options in given $args are:
        $kdbdata=null;
        $kdbmsg=null;

        $caller=getMyCaller(__FILE__,__LINE__);

        krumo::$kdbCaller=$caller;
        krumo::$kdbMsg=null;


        if(is_array($args) && isset($args['kdbdata']))
        {
            extract($args,EXTR_OVERWRITE);
            $data=$kdbdata;
            #krumo::dump($data);
            #return;
            $msg=$kdbmsg;
        } else $data=$args;


        $audit=(is_string($data)) ? "Commmand: ".$data : var_export($data,true);

        kdbAudit($audit);


        if(is_string($data))
        {
            # = $args;
                switch($data)
                {
                    case 'help':
                    {
                        $rc=neoHelp('krumo');
                        echo "<h2>{$rc['title']}</h2>
                        <p>{$rc['data']}</p>";
                        return;
                    }
                    case 'errors-on':
                        error_reporting(E_ALL);
                        return;
                    case 'errors-off':
                        error_reporting(E_NONE);
                        return;
                    default:
                }

                if($data=='enable' && !isKDB())
                {
                    NeoHooks::removeAction('kdb-echo','kdbMsg');
                    NeoHooks::addAction('kdb-echo','kdbMsg');
                    NeoHooks::removeAction('kdb','kdbDump');
                    NeoHooks::addAction('kdb','kdbDump');
                }
                if($data=='disable' && isKDB())
                {
                   // print_r(debug_backtrace());
                   // NeoHooks::removeAction('kdb-echo','kdbMsg');
                   // NeoHooks::removeAction('kdb','kdbDump');
                   //$ve=var_export(debug_backtrace(),true);
                   //die("<code>$ve</code>");
/* ?>
<!--div class="krumo-title">
action kdb,disable - Disable krumo at < ?=krumo::$kdbCaller=$caller? >
</div --><? */
                }
                if (method_exists("krumo",$data))
                {
                    krumo::$kdbMsg="-Krumo Call: {$data}";
                    call_user_func_array(array('krumo', $data),array());
                    return true;
                }
        }

        if (empty($msg))
        {
            krumo::$kdbMsg="";
        } else krumo::$kdbMsg=$msg;
        //echo "<div class='krumo-title'>action kdb</div>";
        if ($data) {
            krumo($data);
        } elseif (false) { /* ?>
        <div class="krumo-root"><div class='krumo-title' style='float: right;'><b><?="$msg"?></b></div>
        <div class="krumo-call" style="white-space:nowrap;">Called from <code><?=$caller?></code>
        </div></div>
        <? */
        }
        return true;

    }
    NeoConfig::setOption('NEO-HANDLERS', 'kdb',array('handler' => 'kdbDump'));
    function kdbAudit($what)
    {
    if (!isKDB()) return;
        global $kdbQ;
        if (is_string($kdbQ) && $kdbQ=="STOP") return;
        if (function_exists('xdebug_time_index')) $idx=xdebug_time_index();
        //else
        $idx=microtime();
        if (!is_string($what)) $what=var_export($what,true);
        $what=nl2br($what);
        $kdbQ[$idx]=array(getMyCaller(__FILE__,__LINE__), $what);
    }
    /**
    * Called at the footer of the page
    *
    */
    function kdbAuditDump()
    {
    if (!isKDB()) return;
        global $kdbQ;
        $toDump['PEAK MEMORY USAGE']=memory_get_peak_usage();
        $toDump['OS INFO']=php_uname();
        $toDump=$kdbQ;
        $kdbQ="STOP";
        #die("Count:".count($kdbQ));
        NeoHooks::action('kdb',array('kdbdata' => $toDump , 'kdbmsg' => 'kdbAuditDump'));
/*        echo "<table class='neo-table'>";
        if (function_exists("xdebug_peak_memory_usage"))
        {
            echo "<tr>
            <td>PEAK MEM USAGE:</td>
            <td>";
            xdebug_peak_memory_usage();
            echo "</td>
            </tr>
            ";
        }
        foreach($kdbQ as $kk => $vv)
        {
            echo "<tr>
            <td>$kk<br />From {$vv[0]}</td>
            <td>";
            if (is_string($vv[1])) echo $vv[1];
            elseif (function_exists("fCore::dump")) fCore::dump($vv[1]);
            elseif (function_exists('xdebug_var_dump')) xdebug_var_dump($vv[1]);
            else var_dump($vv[1]);
            echo "&nbsp;
            </td>
            </tr>\n";
        }
        echo "</table>";
        $kdbQ=array();
*/
        NeoHooks::action('kdb',array('kdbdata' => 'session' , 'kdbmsg' => 'session'));
        //phpinfo();
    }




?>
