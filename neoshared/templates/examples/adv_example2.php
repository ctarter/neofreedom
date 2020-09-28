<?php
 include( "./class.TemplatePower.inc.php");

 //make a new TemplatePower object
  $tpl = new TemplatePower( "./adv_example2.tpl" );

 //tell TemplatePower which tplfile to use
  $tpl->assignInclude( "header", "./adv2_header.tpl" );

 //let TemplatePower do its thing, parsing etc.
  $tpl->prepare();

 //assign values for header
  $tpl->assign( "chapter_num" , "7." );
  $tpl->assign( "chapter_name", "Variables" );
  $tpl->assign( "subject"     , "Basics" );

  for( $i=1; $i <= 10; $i++ )
  {
     //create a new text_row block
      $tpl->newBlock("text_row");

     //assign value
      $tpl->assign("number", $i );
  }

 //print the result
  $tpl->printToScreen();
?>