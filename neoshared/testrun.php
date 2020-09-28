<?php
/**
*test run
*/
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
<title>Untitled</title>
<?
error_reporting(E_ALL);
include_once("neo-base.inc");

?>
</head>
<body>
<p>Help: <a href="http://neoxenos.net/neoshared/krumo/docs/index.html">Read Help</a>
<?
include_once("neo-hooks.inc");
include_once("neo-mysql.inc");

$sql=new NeoMYSQL();
$rc=myResults($sql->querySQL('featurific','featurific'));
//NeoHooks::action('neosess-save','HI','THERE');
NeoHooks::action('kdb','session');

$arr['uid']='all';
$arr['id']='featurific';
$arr['data']='THEDATA';
//$rc=$sql->insertSQL($arr);
//myResults($rc);

//$rc=$sql->deleteSQL(array('id' => 'featurific'));
//myResults($rc);

function myResults($rc)
{
    global $sql;
    if (!$rc) {
        #echo "ERRORS: ".implode('<br />',$sql->errs);
        NeoHooks::action('kdb',array('data' => $sql->errs, 'msg' => 'ERRORS?'));
    } else {
        #echo "GOT IT: ".var_export($rc,true);
        NeoHooks::action('kdb',array('data' => $rc, 'msg' => 'GOOD RC!'));
    }
}
#echo "Here now....:".$_SESSION['featurific_data_xml_filename'];
#print_r($rc);
?>

</body>
</html>
