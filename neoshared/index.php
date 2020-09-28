<?php
if (!isset($_REQUEST['kdb']) && !isset($_COOKIE['kdb'])) die('Houston, we have problems.');
else {
    chdir('testbed');
    include_once('testbed/index.php');
}
?>
