<?php

if (!defined('ABSPATH')) exit;

include(GDPOL_PATH.'forms/shared/top.php');

?>

<div class="d4p-content-right d4p-content-full">
    <a href="<?php echo admin_url('admin.php?page=gd-topic-polls-polls'); ?>" class="button-primary"><i aria-hidden="true" class="fa fa-file-text-o fa-fw"></i> <?php _e("List of polls", "gd-topic-polls"); ?></a>
    <a href="<?php echo admin_url('admin.php?page=gd-topic-polls-settings'); ?>" class="button-secondary" style="float: right;"><i aria-hidden="true" class="fa fa-cogs fa-fw"></i> <?php _e("Settings", "gd-topic-polls"); ?></a>

    <form method="get" action="">
        <input type="hidden" name="page" value="gd-topic-polls-votes" />
        <input type="hidden" value="getback" name="gdpol_handler" />

        <?php

            require_once(GDPOL_PATH.'core/grids/votes.php');

            $_grid = new gdpol_grid_topic_votes();
            $_grid->prepare_items();
            $_grid->display();

        ?>
    </form>
</div>

<?php 

include(GDPOL_PATH.'forms/shared/bottom.php');
include(GDPOL_PATH.'forms/dialogs/votes.php');
