<?php
if (!defined('IN_TEST')) {
    include_once('header.inc');
}
echo "we are here";
include_once(NEOSHARED_PATH . '/neo-sitebuttons.php');
function tstSiteButtons()
{
    NeoHooks::action('neo-sitebuttons');
}
NeoHooks::addAction('NEOTEST-SITEBUTTONS','tstSiteButtons');

if (!defined('IN_TEST')) {
    tstSiteButtons();
    NeoHooks::action('kdb',array('kdbdata' => 'session' , 'kdbmsg' => 'session'));
    include_once('footer.inc');
}


?>

