<?php
/**
* Formats/displays email newsletters using mailpress
*/
include_once(NEOSHARED_PATH."/views/view-util.inc");
if (isset($this)) $myView = new MyView($this);
else throw new fProgrammerException('NO $this object available!');
$tpl=$myView->getHandle();
?>
                <table<?=$tpl->get('table-style','') ?>>
                    <tr>
                        <td style='float:left;margin:0 45px;padding:0;width:auto;text-align:left;color:#333333;font-family:Verdana,Sans-Serif;'>
                            <div style='margin:0pt 0pt 40px;text-align:justify;'>
                                <h2 style='margin:30px 0pt 0pt;text-decoration:none;color:#333333;font-size:1.1em;font-family:Verdana,Sans-Serif;font-weight:bold;'>
                                    Cool! You joined our subscription list!
                                </h2>
                                <small style='color:#777;font-family:Arial,Sans-Serif;font-size:0.7em;line-height:1.5em;'>
<?=$tpl->get('date','') ?>
                                </small>
                                <div style='font-size:.85em; line-height:1.2em;'>
                                    <p>
Thanks for confirming your subsription to <a <? $tpl->get('link-style','') ?> href="<?=$tpl->get('siteurl','http://neozine.org')?>"><?=$tpl->get('blogname','NeoZine');?></a>, the on-line, <i>collaborative</i> Zine from <a href="http://neoxenos.org">NeoXenos</a>.
                                    </p>
                                    <p>Change your subscription at any time at the <a style="color:#000;" href="http://neozine.org/service/subscribe">subscriptions page</a> -- there's more available!</p>
                                <h3 <?=$tpl->get('title2-style','')?>>
                                    Check it out...
                                </h2>
                                <? $recs = $tpl->get('content',"Enjoy!");
                                if(is_string($recs)) echo $recs;
                                else { ?>
                                    <p>A few articles to get you started:</p> <?
                                }
                                ?>
                                </div>
                            </div>
                        </td>
                            <td <?=$tpl->get('sb-cellstyle','')?>>
                            <? $tpl->place('enews-sidebar'); ?>
                            </td>
                    </tr>
                </table>