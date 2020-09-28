<?
/**
* E-Mail Body - the main view
*/
?>
{include:enews-header}
                <table {table-style}>
                    <tr>
                        <td {td-content-style}>
<div {post-style}>
    <h2 {title2-style}>
        Cool! You joined our subscription list!
    </h2>
    <small {small-style}>
{pubdate}
    </small>
    <div {content-style}>
        <p>
Thanks for confirming your subsription! You are now a subscriber of <a {link-style} href="{siteurl}">{blogname}</a>, the on-line, <i>collaborative</i> Zine from <a href="{siteurl}">NeoXenos</a>.
        </p>
        <p>Change your subscription at any time at the <a {link-style} href="{subscribe-url}">subscriptions page</a> -- there's more available!</p>
        <p>{content}</p>
    <h3 {title2-style}> 
        Where to start?
    </h2>
<p>Read about the new features of the NeoZine:</p>
    {begin:recs}
    <ul {post-block-style}>
    <li {post-title-style}>{item:title}</li>
    <li {post-excerpt-style}>{item:excerpt}</li>
    </ul>
    {end:recs}

    </div>
</div>
    </td>
        <td {td-sidebar-style}>
        {include:enews-sidebar}
        </td>
</tr>
</table>
{include:enews-footer}
