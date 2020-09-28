<?php
/**
* view-featured1-col3.tpl parsed at 10/29/09 12:10.35 am
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?
/**
* 3-column with 1st post as a spread
* Caller is in control of the loop: while (have_posts()) : the_post(); {...
* $x is our page #
*/
?>
<?=$this->get('view-top','')?>


<? 
## $POSTS Begin loop 
$POSTS = $this->get('POSTS',array());
foreach($POSTS as $live_rec) { $this->set('COUNTER',1); $this->set('live-rec','$live_rec'); ?> 

<? if ($this->get('COUNTER') == '1') { ?>


<!--Post #1 Formatting...-->

    <div class="<?=$this->get('featured-class','')?>">
        <h1><a href="<?=$this->getElem('live-rec','guid') ?>"><?=$this->getElem('live-rec','post_title') ?></a></h1>
        <div class="<?=$this->get('meta-class','')?>">
            <? if ($this->get('dates') == 'yes') { ?>

            <div class="date"><?=$this->getElem('live-rec','post_modified') ?></div>
            <? } ?>
            <? if ($this->get('authors') == 'yes') { ?>

                <?=$this->getElem('live-rec','author') ?>
            <? } ?>
        </div>

        <div class="<?=$this->get('content-block','')?>">
            <?=$this->getElem('live-rec','thumbnail') ?>
            <?=$this->getElem('live-rec','post_content') ?>
            ?>
        </div>
<? } ?>
<? if ($this->get('COUNTER') == '2') { ?>

       <? $this->set('i','1') ?>
        </div>
        <div id="threecol">
        <div id="threecol2">
<?=$this->get('else','')?>
        <? $this->set('i','3') ?>
<? } ?>
<? if ($this->get('i') == '7') { ?>
<? $this->set('i','4') ?><? } ?>
        <div class="threepost threepost<?=$this->get('i','')?>">
            <h1><a href="<?=$this->getElem('live-rec','guid') ?>"><?=$this->getElem('live-rec','post_title') ?></a></h1>
            <div class="meta">
                <? if ($this->get('dates') == 'yes') { ?>

                <div class="date"><?=$this->getElem('live-rec','post_modified') ?> <?=$this->getElem('live-rec','post_author') ?></div>
                <? } ?>
            </div>

            <div class="<?=$this->get('content-block','')?>">
                <?=$this->getElem('live-rec','post_excerpt') ?>
                <?=$this->getElem('live-rec','more-link') ?>
            </div>
         </div>
<? } ?>
<? if ($this->get('COUNTER') > '4') { ?>

    </div>
<? } ?>
    </div>
    </div>
<?=$this->get('endposts','')?>


