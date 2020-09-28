<?php

if (!defined('ABSPATH')) exit;

$_classes = array('d4p-wrap', 'wpv-'.GDPOL_WPV, 'd4p-page-install');

?>
<div class="<?php echo join(' ', $_classes); ?>">
    <div class="d4p-header">
        <div class="d4p-plugin">
            GD Topic Polls
        </div>
    </div>
    <div class="d4p-content">
        <div class="d4p-content-left">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-magic"></i>
                <h3><?php _e("Installation", "gd-topic-polls"); ?></h3>
            </div>
            <div class="d4p-panel-info">
                <?php _e("Before you continue, make sure plugin installation was successful.", "gd-topic-polls"); ?>
            </div>
        </div>
        <div class="d4p-content-right">
            <div class="d4p-update-info">
                <?php

                    include(GDPOL_PATH.'forms/setup/database.php');

                    gdpol_settings()->set('install', false, 'info');
                    gdpol_settings()->set('update', false, 'info', true);

                    wp_flush_rewrite_rules();

                ?>

                <h3><?php _e("All Done", "gd-topic-polls"); ?></h3>
                <?php _e("Installation completed.", "gd-topic-polls"); ?>

                <br/><br/><a class="button-primary" href="<?php echo gdpol_admin()->current_url(true); ?>"><?php _e("Click here to continue", "gd-topic-polls"); ?></a>

            </div>
        </div>
    </div>
</div>