<?php

if (!defined('ABSPATH')) exit;

$panels = array(
    'index' => array(
        'title' => __("Settings Index", "gd-topic-polls"), 'icon' => 'cogs', 
        'info' => __("All plugin settings are split into several panels, and you access each starting from the right.", "gd-topic-polls")),
    'basic' => array(
        'title' => __("Basics", "gd-topic-polls"), 'icon' => 'tasks', 
        'break' => __("Standard", "gd-topic-polls"),
        'info' => __("These settings control some plugin basics.", "gd-topic-polls")),
    'fields' => array(
        'title' => __("Poll Fields", "gd-topic-polls"), 'icon' => 'bar-chart', 
        'info' => __("These settings control poll fields in the edit form.", "gd-topic-polls")),
    'display' => array(
        'title' => __("Poll Display", "gd-topic-polls"), 'icon' => 'paint-brush', 
        'info' => __("These settings control some elements of the polls display.", "gd-topic-polls")),
    'integration' => array(
        'title' => __("Integration", "gd-topic-polls"), 'icon' => 'd4p-logo-bbpress', 
        'info' => __("These settings control integration with bbPress.", "gd-topic-polls")),
    'rewrite' => array(
        'title' => __("Rewrite", "gd-topic-polls"), 'icon' => 'external-link-square', 
        'break' => __("Objects", "gd-topic-polls"),
        'info' => __("These settings control how the plugin creates additional URL's.", "gd-topic-polls")),
    'labels' => array(
        'title' => __("Objects Labels", "gd-topic-polls"), 'icon' => 'book', 
        'info' => __("These settings control labels used by objects added by the plugin.", "gd-topic-polls"))
);

include(GDPOL_PATH.'forms/shared/top.php');

?>

<form method="post" action="">
    <?php settings_fields('gd-topic-polls-settings'); ?>
    <input type="hidden" value="postback" name="gdpol_handler" />

    <div class="d4p-content-left">
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-cogs"></i>
                <h3><?php _e("Settings", "gd-topic-polls"); ?></h3>
                <?php if ($_panel != 'index') { ?>
                <h4><i aria-hidden="true" class="fa fa-<?php echo $panels[$_panel]['icon']; ?>"></i> <?php echo $panels[$_panel]['title']; ?></h4>
                <?php } ?>
            </div>
            <div class="d4p-panel-info">
                <?php echo $panels[$_panel]['info']; ?>
            </div>
            <?php if ($_panel != 'index') { ?>
                <div class="d4p-panel-buttons">
                    <input type="submit" value="<?php _e("Save Settings", "gd-topic-polls"); ?>" class="button-primary" />
                </div>
                <div class="d4p-return-to-top">
                    <a href="#wpwrap"><?php _e("Return to top", "gd-topic-polls"); ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="d4p-content-right">
        <?php

        if ($_panel == 'index') {
            foreach ($panels as $panel => $obj) {
                if ($panel == 'index') continue;

                $url = 'admin.php?page=gd-topic-polls-'.$_page.'&panel='.$panel;
                
                if (isset($obj['break'])) { ?>

                    <div style="clear: both"></div>
                    <div class="d4p-panel-break d4p-clearfix">
                        <h4><?php echo $obj['break']; ?></h4>
                    </div>
                    <div style="clear: both"></div>

                <?php } ?>

                <div class="d4p-options-panel">
                    <i aria-hidden="true" class="<?php echo d4p_get_icon_class($obj['icon']); ?>"></i>
                    <h5><?php echo $obj['title']; ?></h5>
                    <div>
                        <a class="button-primary" href="<?php echo $url; ?>"><?php _e("Settings Panel", "gd-topic-polls"); ?></a>
                    </div>
                </div>
        
                <?php
            }
        } else {
            gdpol_admin()->load_options();

            $options = new gdpol_admin_settings();

            $panel = gdpol_admin()->panel;
            $groups = $options->get($panel);

            $render = new d4pSettingsRender($panel, $groups);
            $render->base = 'gdpolvalue';
            $render->render();

            ?>

            <div class="clear"></div>
            <div style="padding-top: 15px; border-top: 1px solid #777; max-width: 800px;">
                <input type="submit" value="<?php _e("Save Settings", "gd-topic-polls"); ?>" class="button-primary">
            </div>

            <?php

        }

        ?>
    </div>
</form>

<?php 

include(GDPOL_PATH.'forms/shared/bottom.php');
