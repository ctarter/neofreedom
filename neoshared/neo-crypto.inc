<?php
include_once(NEOSHARED_PATH."/flourish/fCryptography.php");
function neoEncrypt($what)
{
    return $what;
}
function neoDecrypt($what)
{
    return $what;
}
NeoHooks::addFilter('neo-encrypt','neoEncrypt');
NeoHooks::addFilter('neo-decrypt','neoDecrypt');
?>