<?php
if (!defined('IN_TEST')) {
    include_once('header.inc');
}
include_once('../neo-msg.inc');

function tstMsg($whichOne='Basic Messaging')
{
    $testID = $testName= $whichOne;
    switch($testID)
    {
        case 'Neo-Alerts':
            NeoHooks::action($testName,array('from' => $testID, 'msg' => "WARNING: new errors appearing in <a href='{$fileNotifiedURL}'>{$outBase}</a>"));
            break;
        case 'Neo-Send':
            NeoHooks::action($testName,array('from' => $testID, 'msg' => "WARNING: new errors appearing in <a href='{$fileNotifiedURL}'>{$outBase}</a>"));
            break;
        case 'Basic-Messaging':
            NeoMsg::put('TESTMSG',"This is the message");
            NeoMsg::put('TESTMSG2',"This is the message #2");
            echo "<br>TESTMSG->".NeoMsg::get('TESTMSG');
            echo "<br>TESTMSG2->".NeoMsg::get('TESTMSG2');
            break;
        default:
            return kdbErr("PROGRAM ERROR: Unknown Test ID: $testID");
    }
    return true;
}

NeoHooks::addAction('TEST-Alerts','tstMsg');
NeoHooks::addAction('TEST-Send','tstMsg');
NeoHooks::addAction('TEST-Messaging','tstMsg');

if (!defined('IN_TEST')) {
    echo "Current Messaging Index: ".NeoMsg::get('*');
    tstMsg($_GET['test']);
    include_once('footer.inc');
}

?>

