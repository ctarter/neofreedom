<input type="hidden" value="<?php echo admin_url('admin.php?page=gd-topic-polls-tools&gdpol_handler=getback&run=export&_ajax_nonce='.wp_create_nonce('dev4press-plugin-export')); ?>" id="gdpol-export-url" />

<div class="d4p-group d4p-group-export d4p-group-tools">
    <h3><?php _e("Important", "gd-topic-polls"); ?></h3>
    <div class="d4p-group-inner">
        <p><?php _e("With this tool you export plugin settings into plain text file (PHP serialized content). Do not modify export file, any change you make can make it unusable.", "gd-topic-polls"); ?></p>
        <p><?php _e("This tool doesn't export the topic polls and voting data. Topic polls are stored as posts, and votes are saved in the separate database table.", "gd-topic-polls"); ?></p>
    </div>
</div>