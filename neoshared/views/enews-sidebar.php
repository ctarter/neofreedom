<?php
/**
* enews-sidebar.tpl parsed at 10/2/09 4:10.46 am
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?php
/**
* prints links, etc
*/
$tpl=$this;
?>
                                <div <?=$tpl->get('sb-divstyle','')?>>
                                <h3 <?=$tpl->get('title2-style','')?>><?=$tpl->get('sb-title','New at the NeoZine') ?></h3>
                                <?=$tpl->get('sb-content','Visit us soon!') ?>
                                </div>

