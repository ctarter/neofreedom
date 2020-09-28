<?php
/**
* enews-view-confirm.tpl parsed at 10/5/09 1:10.08 pm
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?
/**
* E-Mail Body - the main view
*/
?>
<? $this->place("enews-header") ?>
                <table <?=$this->get('table-style','')?>>
                    <tr>
                        <td <?=$this->get('td-content-style','')?>>
<div <?=$this->get('post-style','')?>>
    <h2 <?=$this->get('title2-style','')?>>
        Cool! You joined our subscription list!
    </h2>
    <small <?=$this->get('small-style','')?>>
<?=$this->get('pubdate','')?>
    </small>
    <div <?=$this->get('content-style','')?>>
        <p>
Thanks for confirming your subsription! You are now a subscriber of <a <?=$this->get('link-style','')?> href="<?=$this->get('siteurl','')?>"><?=$this->get('blogname','')?></a>, the on-line, <i>collaborative</i> Zine from <a href="<?=$this->get('siteurl','')?>">NeoXenos</a>.
        </p>
        <p>Change your subscription at any time at the <a <?=$this->get('link-style','')?> href="<?=$this->get('subscribe-url','')?>">subscriptions page</a> -- there's more available!</p>
        <p><?=$this->get('content','')?></p>
    <h3 <?=$this->get('title2-style','')?>> 
        Where to start?
    </h2>
<p>Read about the new features of the NeoZine:</p>
    
<? 
## $recs Begin loop 
$__arr = $this->get('recs',array());
foreach($__arr as $__item) { ?> 
    <ul <?=$this->get('post-block-style','')?>>
    <li <?=$this->get('post-title-style','')?>><?=$__item['title'] ?></li>
    <li <?=$this->get('post-excerpt-style','')?>><?=$__item['excerpt'] ?></li>
    </ul>
    
<? }
## $recs loop end ?>


    </div>
</div>
    </td>
        <td <?=$this->get('td-sidebar-style','')?>>
        <? $this->place("enews-sidebar") ?>
        </td>
</tr>
</table>
<? $this->place("enews-footer") ?>

