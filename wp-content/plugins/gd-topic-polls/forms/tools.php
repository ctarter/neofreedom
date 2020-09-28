<?php

if (!defined('ABSPATH')) exit;

$panels = array(
    'index' => array(
        'title' => __("Tools Index", "gd-topic-polls"), 'icon' => 'wrench', 
        'info' => __("All plugin tools are split into several panels, and you access each starting from the right.", "gd-topic-polls")),
    'export' => array(
        'title' => __("Export Settings", "gd-topic-polls"), 'icon' => 'download', 
        'button' => 'button', 'button_text' => __("Export", "gd-topic-polls"),
        'info' => __("Export all plugin settings into file.", "gd-topic-polls")),
    'import' => array(
        'title' => __("Import Settings", "gd-topic-polls"), 'icon' => 'upload', 
        'button' => 'submit', 'button_text' => __("Import", "gd-topic-polls"),
        'info' => __("Import all plugin settings from export file.", "gd-topic-polls")),
    'remove' => array(
        'title' => __("Reset / Remove", "gd-topic-polls"), 'icon' => 'remove', 
        'button' => 'submit', 'button_text' => __("Remove", "gd-topic-polls"),
        'info' => __("Remove selected plugin settings and optionally disable plugin.", "gd-topic-polls"))
);

include(GDPOL_PATH.'forms/shared/top.php');

?>

<form method="post" action="" enctype="multipart/form-data" id="gdpol-tools-form">
    <?php settings_fields('gd-topic-polls-tools'); ?>
    <input type="hidden" value="<?php echo $_panel; ?>" name="gdpoltools[panel]" />
    <input type="hidden" value="postback" name="gdpol_handler" />

    <div class="d4p-content-left">
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-wrench"></i>
                <h3><?php _e("Tools", "gd-topic-polls"); ?></h3>
                <?php if ($_panel != 'index') { ?>
                <h4><i aria-hidden="true" class="fa fa-<?php echo $panels[$_panel]['icon']; ?>"></i> <?php echo $panels[$_panel]['title']; ?></h4>
                <?php } ?>
            </div>
            <div class="d4p-panel-info">
                <?php echo $panels[$_panel]['info']; ?>
            </div>
            <?php if ($_panel != 'index' && $panels[$_panel]['button'] != 'none') { ?>
                <div class="d4p-panel-buttons">
                    <input id="gdpol-tool-<?php echo $_panel; ?>" class="button-primary" type="<?php echo $panels[$_panel]['button']; ?>" value="<?php echo $panels[$_panel]['button_text']; ?>" />
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

                ?>

                <div class="d4p-options-panel">
                    <i aria-hidden="true" class="fa fa-<?php echo $obj['icon']; ?>"></i>
                    <h5><?php echo $obj['title']; ?></h5>
                    <div>
                        <a class="button-primary" href="<?php echo $url; ?>"><?php _e("Tools Panel", "gd-topic-polls"); ?></a>
                    </div>
                </div>

                <?php
            }
        } else {
            include(GDPOL_PATH.'forms/panels/'.$_panel.'.php');
        }

        ?>
    </div>
</form>

<?php 

include(GDPOL_PATH.'forms/shared/bottom.php');
