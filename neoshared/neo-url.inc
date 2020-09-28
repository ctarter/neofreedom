<?php
include_once("neo-base.inc");
include_once(NEOSHARED_PATH."/flourish/fUrl.php");

class NeoUrl {
    static function getDomain() {
        return fUrl::getDomain();
    }
}
?>