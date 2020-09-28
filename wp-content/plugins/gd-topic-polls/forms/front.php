<?php

if (!defined('ABSPATH')) exit;

$panels = array();

include(GDPOL_PATH.'forms/shared/top.php');

$pages = gdpol_admin()->menu_items;

$_url_vs = d4p_url_campaign_tracking('https://plugins.dev4press.com/gd-topic-polls/articles/free-vs-pro-plugin/', 'gd-topic-poll-to-pro', 'wordpress', 'lite-plugin-admin');
$_url_buy = d4p_url_campaign_tracking('https://plugins.dev4press.com/gd-topic-polls/buy/', 'gd-topic-poll-to-pro', 'wordpress', 'lite-plugin-admin');

$_url_tox = d4p_url_campaign_tracking('https://plugins.dev4press.com/gd-topic-prefix/', 'gd-topic-poll-lite', 'wordpress', 'lite-plugin-admin');
$_url_bbx = d4p_url_campaign_tracking('https://plugins.dev4press.com/gd-bbpress-toolbox/', 'gd-topic-poll-lite', 'wordpress', 'lite-plugin-admin');
$_url_pos = d4p_url_campaign_tracking('https://plugins.dev4press.com/gd-power-search-for-bbpress/', 'gd-topic-poll-lite', 'wordpress', 'lite-plugin-admin');

$_url_demo = d4p_url_campaign_tracking('https://www.dev4press.com/request-demo/', 'gd-topic-poll-lite', 'wordpress', 'lite-plugin-admin');
$_url_cnt = d4p_url_campaign_tracking('https://www.dev4press.com/contact/', 'gd-topic-poll-lite', 'wordpress', 'lite-plugin-admin');

?>

<div class="d4p-front-left">
    <div class="d4p-front-title">
        <h1>GD TOPIC POLLS</h1>
        <h5>
            Lite <em style="font-weight: 100; font-style: normal;"><?php _e("Edition", "gd-topic-polls"); ?></em>
            <span><?php 

            _e("Version", "gd-topic-polls");
            echo': '.gdpol_settings()->info->version;

            if (gdpol_settings()->info->status != 'stable') {
                echo ' - <span style="color: red; font-weight: bold;">'.strtoupper(gdpol_settings()->info->status).'</span>';
            }

            ?></span></h5>
    </div>
    <div class="d4p-front-title d4p-front-upgrade">
        <?php _e("Upgrade to Pro version", "gd-topic-polls"); ?>
        <p style="margin: 10px 0 0;">
            <?php echo sprintf(__("Pro version of this plugin includes many additional features (including more poll options, bbPress topic views support, BuddyPress activity stream integration...). To learn more about the advantages of Pro version, check out this %s article.", "gd-topic-polls"),
                '<a target="_blank" href="'.$_url_vs.'">'.__("free vs pro version", "gd-topic-polls").'</a>'); ?>
        </p>
        <p style="margin: 10px 0 0;">
            <?php echo sprintf(__("To buy the Pro version visit the %s. And, to get 10&percnt; discount, on check out use this coupon: %s.", "gd-topic-polls"),
                '<a target="_blank" href="'.$_url_buy.'">'.__("buy pro version page", "gd-topic-polls").'</a>',
                '<strong>BBFREETOPRO</strong>'); ?></p>
    </div>
    <div class="d4p-front-title d4p-front-upgrade" style="background: #28c; height: auto; margin-top: 15px; text-align: center; font-size: 18px; font-weight: bold;">
        <?php _e("More plugins for bbPress", "gd-topic-polls"); ?>
        <p style="margin: 10px 0 0;">
            <?php echo sprintf(__("Dev4Press plugins for WordPress include more premium plugins made for bbPress: %s, %s, %s.", "gd-topic-polls"),
                '<a target="_blank" href="'.$_url_bbx.'">'.__("GD bbPress Toolbox Pro", "gd-topic-polls").'</a>',
                '<a target="_blank" href="'.$_url_tox.'">'.__("GD Topic Prefix Pro", "gd-topic-polls").'</a>',
                '<a target="_blank" href="'.$_url_pos.'">'.__("GD Power Search Pro", "gd-topic-polls").'</a>'); ?>
        </p><p style="margin: 10px 0 0;">
            <?php echo sprintf(__("If you want to test our Pro plugins, you can request demo website with access to plugin administration. Click here to %s, or click here to %s if you have additional questions.", "gd-topic-polls"),
                '<a target="_blank" href="'.$_url_demo.'">'.__("Request Demo", "gd-topic-polls").'</a>',
                '<a target="_blank" href="'.$_url_cnt.'">'.__("Contact Us", "gd-topic-polls").'</a>'); ?>
        </p>
    </div>
    <div class="d4p-front-dev4press">
        &copy; 2008 - 2018. Dev4Press - <a target="_blank" href="https://www.dev4press.com/">www.dev4press.com</a> &middot; 
                                        <a target="_blank" href="https://plugins.dev4press.com/gd-topic-polls/">plugins.dev4press.com/gd-topic-polls</a>
    </div>
</div>
<div class="d4p-front-right">
    <div class="d4p-front-title" style="max-width: 654px; height: auto; min-height: 137px; margin-bottom: 15px; text-align: center; font-size: 18px; font-weight: bold;">
        <?php _e("Knowledge Base and Support Forums", "gd-topic-polls"); ?>
        <p style="font-size: 15px; font-weight: normal; margin: 10px 0 0;">
            <?php echo sprintf(__("To learn more about the plugin, check out plugin %s user guides, articles, references and FAQ. To get additional help, you can use %s or %s.", "gd-topic-polls"),
                '<a target="_blank" href="https://support.dev4press.com/kb/product/gd-topic-polls/">'.__("knowledge base", "gd-topic-polls").'</a>',
                '<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins-lite/gd-topic-polls-lite/">'.__("lite edition support forum", "gd-topic-polls").'</a>',
                '<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins/gd-topic-polls/">'.__("pro edition support forum", "gd-topic-polls").'</a>'); ?>
        </p>
    </div>
    <?php

    foreach ($pages as $page => $obj) {
        if ($page == 'front') {
            continue;
        }

        $url = 'admin.php?page=gd-topic-polls-'.$page;

        ?>

            <div class="d4p-options-panel">

                <i aria-hidden="true" class="<?php echo d4p_get_icon_class($obj['icon']); ?>"></i>
                <h5><?php echo $obj['title']; ?></h5>
                <div>
                    <a class="button-primary" href="<?php echo $url; ?>"><?php _e("Open", "gd-topic-polls"); ?></a>
                </div>
            </div>

        <?php
    }

    ?>
    
</div>

<?php 

include(GDPOL_PATH.'forms/shared/bottom.php');
