<?php
/**
* test-view.tpl parsed at 10/13/09 2:10.31 pm
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?php
/**
* prints links, etc
*/
?>
                                <div <?=$this->get('sb-divstyle','')?>>
                                <h3 <?=$this->get('title2-style','')?>><?=$this->get('sb-title','')?></h3>
                                <?=$this->get('sb-content','')?>
                                </div>

