<?php
 include( "./class.TemplatePower.inc.php");

 //make a new TemplatePower object
  $tpl = new TemplatePower( "./simple.tpl" );

 //let TemplatePower do its thing, parsing etc.
  $tpl->prepare();

 //assign a value to {name}
  $tpl->assign("name", "RoVeL");

 //print the result
  $tpl->printToScreen();
?>