<?php
/**
* Automatically parsed at 9/22/09 2:09.15 am
*/
        if (!isset($this) || ! $this instanceof NeoTPL) throw new fValidationException("no NeoTPL object available...");
?>
<?php
/**
* Full-page HTML view
*/
?>
<? $this->place("header")?>
<?=$this->get("vars","")?>
<p>From inside of PAGE: looks good!</p>
<? $this->place("body")?>
<? $this->place("footer")?>


