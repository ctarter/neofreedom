<?php
/**
* view-simple.tpl parsed at 10/1/09 6:10.29 am
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?php
/**
* Unknown view
*/
include_once("header.inc");
?>
<p><?=$this->get('content','')?></p>
<?
include_once("footer.inc");
?>
