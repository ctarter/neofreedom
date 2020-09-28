<?php
/**
* enews-sidebar.tpl parsed at 10/5/09 10:10.45 am
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<!-- start footer -->
        <div <?=$this->get('footer-style','')?>>
            <br />
            <small <?=$this->get('small-style','')?>>
                <?=$this->get('sponsor-msg','')?><br />
                <?=$this->get('footer-msg','')?><br />
            </small>
        </div>
    </body>
</html>


