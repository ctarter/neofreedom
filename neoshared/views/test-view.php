<?php
/**
* test-view.tpl parsed at 10/13/09 2:10.31 pm
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?php
/**
* E-Mail Body - the main view
*/
?>
<? $this->place("test-header") ?>
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
Thanks for confirming your subsription to <a <?=$this->get('link-style','')?> href="<?=$this->get('siteurl','')?>"><?=$this->get('blogname','')?></a>, the on-line, <i>collaborative</i> Zine from <a href="<?=$this->get('siteurl','')?>">NeoXenos</a>.
        </p>
        <p>Change your subscription at any time at the <a <?=$this->get('link-style','')?> href="<?=$this->get('subscribe-url','')?>">subscriptions page</a> -- there's more available!</p>
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
    <li <?=$this->get('post-meta-style','')?>><?=$__item['author'] ?> on <?=$__item['date'] ?></li>
    <li <?=$this->get('post-excerpt-style','')?>><?=$__item['excerpt'] ?></li>
    </ul>
    
<? }
## $recs loop end ?>


    <?=$this->get('content','')?>
    </div>
</div>
                        </td>
                            <td <?=$this->get('td-sidebar-style','')?>>
                            <? $this->place("test-sidebar") ?>
                            </td>
                    </tr>
                </table>
<? $this->place("test-footer") ?>
