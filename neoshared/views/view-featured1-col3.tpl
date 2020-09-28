<?
/**
* 3-column with 1st post as a spread
* Caller is in control of the loop: while (have_posts()) : the_post(); {...
* $x is our page #
*/
?>
{view-top}

{begin:POSTS}

{if:COUNTER:1}

<!--Post #1 Formatting...-->

    <div class="{featured-class}">
        <h1><a href="{POSTS:guid}">{POSTS:post_title}</a></h1>
        <div class="{meta-class}">
            {if:dates:yes}
            <div class="date">{POSTS:post_modified}</div>
            {end-if}
            {if:authors:yes}
                {POSTS:author}
            {end-if}
        </div>

        <div class="{content-block}">
            {POSTS:thumbnail}
            {POSTS:post_content}
        </div>
{end-if}
{if:COUNTER:2}
       {set:i:1}
        </div>
        <div id="threecol">
        <div id="threecol2">
{else}
        {set:i:3}
{end-if}
{if:i:7}
    {set:i:4}
{end-if}
        <div class="threepost threepost{i}">
            <h1><a href="{POSTS:guid}">{POSTS:post_title}</a></h1>
            <div class="meta">
                {if:dates:yes}
                <div class="date">{POSTS:post_modified} {POSTS:post_author}</div>
                {end-if}
            </div>

            <div class="{content-block}">
                {POSTS:post_excerpt}
                {POSTS:more-link}
            </div>
         </div>
{if:COUNTER>4}
    </div>
{end-if}
    </div>
    </div>
{end:POSTS}

