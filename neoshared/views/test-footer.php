<?php
/**
* test-view.tpl parsed at 10/13/09 2:10.31 pm
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<!-- start footer -->
        <div <?=$this->get('footer-style','')?>>
            <br />
            <small>
                From <a href="<?=$this->get('siteurl','')?>"><?=$this->get('blogname','')?></a>.
                <br /> 
            </small>
        </div>
    </body>
</html>


