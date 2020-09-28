<?
/**
* E-Mail Body - the SINGLE view
*/

?>
{include:enews-header}
            <div>
                <table {table-style}>
{begin:recs}
                    <tr>
                        <td {td-content-style}>
                            <div {post-block-style}>
                                <h2 {post-title-style}>
<a href="{item:permalink}" {link-style} rel="bookmark" title="Permanent Link to {item:attribute}">{item:title}</a>
                                </h2>
                                <small {post-meta-style}>{item:time}</small>
                                <div {post-excerpt-style}>
                                    <p {p-style}>
{item:content}
                                    </p>
                                </div>
                            </div>
                        </td>
                            <td {td-sidebar-style}>
                            {include:sidebar}
                            </td>
                    </tr>
{end:recs}
                </table>
            </div>

{include:enews-footer}