<?php
/**
* view-block.tpl parsed at 10/28/09 4:10.19 am
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?php
/**
* view-block.tpl
*/
?>
<p>Blog Name: <?=$this->get('blogname','')?></p>
<p>
<?=implode('<br>',$this->get('content',''))?>
</p>

