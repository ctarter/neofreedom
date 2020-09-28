<?php
/**
* Global HELP
*/

function neoHelp($cmd)
{
$help['krumo'] = array('title' => "Krumo Commands:",'type' => 'html', 'data' => '
backtrace , classes, conf, cookie, defines, disable, dump ( $data, mixed $data,...), enable, env, extensions, functions, get, headers, includes, ini (string $ini_file), interfaces, path, phpini, post, request, server, session
');
if(isset($help[$cmd])) return $help[$cmd];
else return array('title' => 'Help for:'.$cmd, 'type' => 'text', 'data' => 'NO HELP AVAILABLE FOR THAT COMMAND.');
}
?>
