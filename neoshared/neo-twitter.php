<?php
/**
* PHP-Pear Twitter API
*
*    1.
       require_once 'Services/Twitter.php';
   2.

   3.
       $username = 'Your_Username';
   4.
       $password = 'Your_Password';
   5.

   6.
       try {
   7.
           $twitter = new Services_Twitter($username, $password);
   8.
           $msg = $twitter->statuses->update("I'm coding with PEAR right now!");
   9.
           print_r($msg);
  10.
       } catch (Services_Twitter_Exception $e) {
  11.
           echo $e->getMessage();
  12.
       }

*/
require_once 'Services/Twitter.php';
$username = 'bibleteachings';
$password = 'agape-65';
try {
$twitter = new Services_Twitter($username, $password);
           $msg = $twitter->statuses->update("Testing 1,2,3...");
           print_r($msg);
} catch (Services_Twitter_Exception $e) {
           echo $e->getMessage();
}

?>
