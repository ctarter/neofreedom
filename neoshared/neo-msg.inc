<?php
include_once("flourish/fMessaging.php");
include_once("flourish/fHTML.php");
class NeoMsg {
    const logDateFMT='Y-n-j.g.m.s.a';
    static function init()
    {
        NeoConfig::addOption('NEO-HANDLERS', array('Neo-Send',array('type' => 'action', 'handler' => 'NeoMsg::putRemote')));
        NeoConfig::addOption('NEO-HANDLERS', array('Neo-Alerts',array('type' => 'action', 'handler' => 'NeoMsg::notifyChanges')));
    }
    static function put($id,$msg,$for='')
    {
        if(is_object($id)) $id=get_class($id);
        //$msg = (empty($msg) ? 'no msg' : $msg);
        //if (is_string($msg)) $msg=array($msg);
        //$src=getMyCaller(__FILE__,__LINE__);
        //$ds=date('n/j/y g:m.s a');
        $msg=htmlspecialchars($msg);
        fMessaging::create($id,$for,$msg);
    }

    static function getOption($tag,$subTag=null)
    {

        static $opts = null;
        if (!$opts) {
            $opts['receivers']['Neo-Alerts'] = "\<alerts@neoxenos.info\>";
            $opts['senders']['Neo-Send'] = $opts['senders']['Neo-Alerts'] ="<webmaster@{$SERVER['HOST']}>";
        }
        return (!$subTag) ? @$opts[$tag] : @$opts[$tag][$subTag];
    }

    /**
    * Send remote message -
    *
    * @param mixed $for - recipient name REQUIRED
    * @param mixed $from - sender name (default=webmaster@$myServer)
    * @param mixed $msg - content to send
    */
    static function putRemote($args)
    {
        $forName=null;
        $fromName="NeoNotifier";
        $msg="Unknown Message...";
        $myServer=$_SERVER['host'];
        if (!$args) {
            return kdbErr("PROGRAM ERR: args missing here",__FILE__,__LINE__);
        }
        else extract($args);

        if (!$forName)
        {
            return kdbErr("PROGRAM ERR: recipient required here",__FILE__,__LINE__);
        }

        $for=self::getOption('receivers', $forName);
        if (!$for) {
            return kdbErr('CONFIG ERR: no recipient address');
        }
        else $for="{$forName} <$for>";
        $from=self::getOption('senders', $fromName);
        if (!$from) $from=$fromName;
        else $from="{$fromName} <$from>";
        $headers = "";
        $headers .= "From: $for\n \t <" . $for . ">\n";
        $headers .= "Reply-To: ".$from."\n";
        $headers .= "X-Sender: ".$from."\n";
        $headers .= "X-Mailer: PHP5\n";
        $headers .= "X-Priority: 3\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/plain\n";
        $headers .= "Return-Path: " . $from . "\r\n\r\n";

        // send email to site owner

        //$subject = "Neo-Monitor - $urlToMonitor may not be working";
        $subject="[$myServer} Message] from {$fromName}";

        if (!mail( $email, $subject, $msg, $headers )) return kdbErr("MAIL ERROR: cannot send to $forName");
        else return;
    }
    /**
    * Obtain queued message(s)
    *
    * @param string $id - msg author (or * for all authors)
    * @param mixed $for - recipient, if any
    * @return mixed array/string
    */
    static function get($id,$for='')
    {
        if(is_object($id)) $id=get_class($id);
        if ($id=='*') fMessaging::show($id,$for,'neo-msg');
        else {
            $aa=fMessaging::retrieve($id,$for);
            return $aa;
        }
    }

    /**
    * Notify subscribers this file was changed.
    *
    */
    function notifyChanges($theFile=NEO_ERR_LOG)
    {
        $outBase=basename($theFile)."out.txt";
        $fileNotifiedURL=NEOSHARED_URL."/outbox/{$outBase}";
        $fileNotifiedLog=NEOSHARED_PATH."/outbox/{$outBase}";
        $lastLogTS = file_exists($logFile) ? date(LogDateFMT,filemtime($theFile)) : 0;
        $lastNotifiedTS = ((file_exists($fileNotifiedLog)) ? date(self::logDateFMT,filemtime($fileNotifiedLog)) : 0);
        if ($thisErrTS > $lastNotifiedTS)
        {
            file_put_contents($fileNotifiedLog,"{$thisErrTS} - TRY TO NOTIFY ERRORS\n");
            NeoHooks::action('Neo-Send',array('from' => 'Neo-Alerts', 'msg' => "WARNING: new errors appearing in <a href='{$fileNotifiedURL}'>{$outBase}</a>"));
            ##
            ## Still more errors? Prevent recursion...
            ##
            $lastLogTS2 = file_exists($theFile) ? date(self::logDateFMT,filemtime($theFile)) : 0;
            if ($lastLogTS != $lastLogTS2)
                file_put_contents($fileNotifiedLog,"{$thisErrTS} - MULTIPLE ERRORS OCCURING HERE: ".__FILE__.":".__LINE__."\n");;
        }
    }


}
NeoMsg::init();
?>
