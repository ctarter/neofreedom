/**
* E-Mail Body - the main view
*/
{include:enews-header}
                <table {table-style}>
                    <tr>
                        <td {td-content-style}>
                            <div {post-style}>
                                <h2 style='margin:30px 0pt 0pt;text-decoration:none;color:#333333;font-size:1.1em;font-family:Verdana,Sans-Serif;font-weight:bold;'>
                                    Cool! You joined our subscription list!
                                </h2>
                                <small style='color:#777;font-family:Arial,Sans-Serif;font-size:0.7em;line-height:1.5em;'>
<?=$tpl->get('date','') ?>
                                </small>
                                <div {content-style}>
                                    <p>
Thanks for confirming your subsription to <a {link-style} href="{siteurl}">{blogname}</a>, the on-line, <i>collaborative</i> Zine from <a href="{siteurl}">NeoXenos</a>.
                                    </p>
                                    <p>Change your subscription at any time at the <a {link-style} href="{subscribe-url}">subscriptions page</a> -- there's more available!</p>
                                <h3 {title2-style}>
                                    Check it out...
                                </h2>

                                {begin:recs}
                                <ul {post-style}>
                                <li><h3 {title3-style}>{fields:title}</h3></li>
                                <li {meta-style}>By {field:author} on {field:date}</li>
                                <li {excerpt-style}>{field:excerpt}</li>
                                </ul>
                                {end:recs}

                                {content}
                                </div>
                            </div>
                        </td>{begin:here}
                            <td {sb-cellstyle}>
                            {include:enews-sidebar}
                            </td>
                    </tr>
                </table>
{include:enews-footer}