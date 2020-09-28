<?php
/**
* test-view.tpl parsed at 10/13/09 2:10.31 pm
* Source: /media/Seagate500/repo-new/www/neoshared/views
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head profile="http://gmpg.org/xfn/11">
        <meta http-equiv="Content-Type" content="<?=$this->get('content-type','')?>" />
        <title></title>
    </head>
    <body>
        <div <?=$this->get('top-style','')?>>
            <small>
Having trouble reading this email? View it on our <a href='<?=$this->get('view-url','')?>' <?=$this->get('link-style','')?>>website</a>.
                </a>.
                <br />
            </small>
        </div>
        <div>
            <br/>
            <img src=<?=$this->get('logo-url','')?> alt='' <?=$this->get('img-style','')?>/>
        </div>
        <div <?=$this->get('masthead-style','')?>>
            <img src='<?=$this->get('masthead-img','')?>' <?=$this->get('masthead-img-style','')?> alt='' />
            <span {'masthead-left-style'}>
            <small <?=$this->get('small-style','')?>>
                        <a href='<?=$this->get('siteurl','')?>' <?=$this->get('link-style','')?>>
                            <?=$this->get('siteurl','')?>
                        </a>
            </small>
            </span>
            <small <?=$this->get('small-style','')?>>
            <span <?=$this->get('masthead-right-style','')?>>
                        10/13/09 2:10.31 pm
            </small>
            </span>
        </div>
        <br style='clear:both;'/>
        <br />
        <br />
<!-- end header -->
