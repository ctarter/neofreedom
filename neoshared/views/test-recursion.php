<?php
/**
* test-recursion.tpl parsed at 10/13/09 2:10.56 pm
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<?php
/**
* Unknown view
*/
?>
<p>Blog Name: <?=$this->get('blogname','')?></p>
<p>
<?=$this->get('content','')?>
</p>

