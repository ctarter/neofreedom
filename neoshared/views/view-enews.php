<?php
        if (!isset($this) || ! $this instanceof NeoTPL) throw new fValidationException("no NeoTPL object available...");
        ?>
/**
* E-Mail Body - the main view
*/
<? $this->place("enews-header")?>
                <table <?=$this->get("table-style","")?>>
                    <tr>
                        <td <?=$this->get("td-content-style","")?>>
                            <div <?=$this->get("post-style","")?>>
                                <h2 style='margin:30px 0pt 0pt;text-decoration:none;color:#333333;font-size:1.1em;font-family:Verdana,Sans-Serif;font-weight:bold;'>
                                    Cool! You joined our subscription list!
                                </h2>
                                <small style='color:#777;font-family:Arial,Sans-Serif;font-size:0.7em;line-height:1.5em;'>
<?=$tpl->get('date','') ?>
                                </small>
                                <div <?=$this->get("content-style","")?>>
                                    <p>
Thanks for confirming your subsription to <a <?=$this->get("link-style","")?> href="<?=$this->get("siteurl","")?>"><?=$this->get("blogname","")?></a>, the on-line, <i>collaborative</i> Zine from <a href="<?=$this->get("siteurl","")?>">NeoXenos</a>.
                                    </p>
                                    <p>Change your subscription at any time at the <a <?=$this->get("link-style","")?> href="<?=$this->get("subscribe-url","")?>">subscriptions page</a> -- there's more available!</p>
                                <h3 {title2-style}>
                                    Check it out...
                                </h2>

                                {begin:recs}
                                <ul <?=$this->get("post-style","")?>>
                                <li><h3 {title3-style}>{fields:title}</h3></li>
                                <li <?=$this->get("meta-style","")?>>By {field:author} on {field:date}</li>
                                <li <?=$this->get("excerpt-style","")?>>{field:excerpt}</li>
                                </ul>
                                {end:recs}

                                <?=$this->get("content","")?>
                                </div>
                            </div>
                        </td>{begin:here}
                            <td <?=$this->get("sb-cellstyle","")?>>
                            <? $this->place("enews-sidebar")?>
                            </td>
                    </tr>
                </table>
<? $this->place("enews-footer")?>
