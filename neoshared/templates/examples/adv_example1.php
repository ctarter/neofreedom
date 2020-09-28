<?php
 include( "./class.TemplatePower.inc.php");

 //make a new TemplatePower object
  $tpl = new TemplatePower( "./adv_example1.tpl" );

 //let TemplatePower do its thing, parsing etc.
  $tpl->prepare();

  for( $i=1; $i <= 10; $i++ )
  {
     //create a new number_row block
      $tpl->newBlock("number_row");

     //assign values
      $tpl->assign("number"   , $i );
      $tpl->assign("bignumber", $i * 10 );
  }

 //print the result
  $tpl->printToScreen();
?>