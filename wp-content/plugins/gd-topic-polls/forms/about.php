<?php

if (!defined('ABSPATH')) exit;

$panels = array(
    'index' => array(
        'title' => __("About Plugin", "gd-topic-polls"), 'icon' => 'info-circle', 
        'info' => __("Get more information about this plugin.", "gd-topic-polls")),
    'changelog' => array(
        'title' => __("Changelog", "gd-topic-polls"), 'icon' => 'file-text',
        'info' => __("Check out full changelog for this plugin.", "gd-topic-polls")),
    'translations' => array(
        'title' => __("Translations", "gd-topic-polls"), 'icon' => 'language',
        'info' => __("List of translations included for this plugin.", "gd-topic-polls")),
    'dev4press' => array(
        'title' => __("Dev4Press", "gd-topic-polls"), 'icon' => 'd4p-logo-dev4press',
        'info' => __("Check out other Dev4Press products.", "gd-topic-polls"))
);

include(GDPOL_PATH.'forms/shared/top.php');

?>

<div class="d4p-content-left">
    <div class="d4p-panel-title">
        <i aria-hidden="true" class="fa fa-info-circle"></i>
        <h3><?php _e("About", "gd-topic-polls"); ?></h3>
        <?php if ($_panel != 'index') { ?>
            <h4><i aria-hidden="true" class="<?php echo d4p_get_icon_class($panels[$_panel]['icon']); ?>"></i> <?php echo $panels[$_panel]['title']; ?></h4>
        <?php } ?>
    </div>
    <div class="d4p-panel-info">
        <?php echo $panels[$_panel]['info']; ?>
    </div>
    <?php if ($_panel == 'index') { ?>
    <div class="d4p-panel-links">
        <a href="admin.php?page=gd-topic-polls-about&panel=changelog"><i aria-hidden="true" class="fa fa-file-text fa-fw"></i> <?php _e("Changelog", "gd-topic-polls"); ?></a>
        <a href="admin.php?page=gd-topic-polls-about&panel=translations"><i aria-hidden="true" class="fa fa-language fa-fw"></i> <?php _e("Translations", "gd-topic-polls"); ?></a>
        <a href="admin.php?page=gd-topic-polls-about&panel=dev4press"><i aria-hidden="true" class="d4p-icon d4p-logo-dev4press d4pi-fw"></i> Dev4Press</a>
    </div>
    <?php } ?>
</div>
<div class="d4p-content-right">
    <?php

        if ($_panel == 'index') {
            include(GDPOL_PATH.'forms/panels/about.php');
        } else {
            include(GDPOL_PATH.'forms/panels/'.$_panel.'.php');
        }

    ?>
</div>

<?php 

include(GDPOL_PATH.'forms/shared/bottom.php');
