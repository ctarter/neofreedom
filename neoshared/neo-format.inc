<?php
/**
* Generic Formatting Functionality
*/

function neoSharedCSS()
{
    ##if (is_admin())
    ##$name="neo-format-admin";
    ##else
    $name="neo-format";
    echo "
    <link rel='stylesheet' href='" . NEOSHARED_URL . "/{$name}.css' type='text/css' />
    ";
#    global $shortcode_tags;
#    $arr['neocode'] = 'neoCode';
#    $shortcode_tags = array_merge($arr,$shortcode_tags);
#    do_action('debug-dump', $shortcode_tags,  __FILE__, __LINE__);
}
function neoJavascript()
{
    echo '
<script type="text/javascript">
function showhide(n) {

        detail=document.getElementById(n);

    if (detail==null) return false;
    if (detail.style.display=="none")
    {
       detail.style.display="inline";
    } else {
       detail.style.display="none";
    }
    }
</script>';

}

NeoHooks::addAction('neo-css','neoSharedCSS');
NeoHooks::addAction('neo-js','neoJavascript');
?>