<?php
/**
* header html for tests
*/
if (!defined('IN_TEST')) {
    $_REQUEST['kdb']=true;
    include_once('../neo-base.inc');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="content-language" content="en">
<meta name="author" content="">
<meta http-equiv="Reply-to" content="@.com">
<meta name="generator" content="PhpED 5.2">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="creation-date" content="09/20/2007">
<meta name="revisit-after" content="15 days">
<title>Testing <? NeoSess::get('test')?></title>
<? NeoHooks::action('neo-css'); ?>
</head>
<body>
<?
## Body Follows...
NeoHooks::action('TEST-BEGIN');
?>

