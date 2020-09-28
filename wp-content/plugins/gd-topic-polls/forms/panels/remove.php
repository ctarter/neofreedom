<div class="d4p-group d4p-group-reset d4p-group-important">
    <h3><?php _e("Important", "gd-topic-polls"); ?></h3>
    <div class="d4p-group-inner">
        <?php _e("This tool can remove plugin settings saved in the WordPress options table added by the plugin and you can remove polls votes table and all logged data.", "gd-topic-polls"); ?><br/><br/>
        <?php _e("Removal of polls from WordPress posts and posmeta tables is not possible using these tools. To remove polls use Polls panel.", "gd-topic-polls"); ?><br/><br/>
        <?php _e("Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.", "gd-topic-polls"); ?> 
        <?php _e("If you choose to remove plugin settings, once that is done, all settings will be reinitialized to default values if you choose to leave plugin active.", "gd-topic-polls"); ?>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Remove plugin settings", "gd-topic-polls"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdpoltools[remove][settings]" value="on" /> <?php _e("Main Settings", "gd-topic-polls"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdpoltools[remove][objects]" value="on" /> <?php _e("Objects Settings", "gd-topic-polls"); ?>
        </label>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Remove database data and tables", "gd-topic-polls"); ?></h3>
    <div class="d4p-group-inner">
        <p style="font-weight: bold"><?php _e("This will remove all votes you might have for the polls.", "gd-topic-polls"); ?></p>
        <label>
            <input type="checkbox" class="widefat" name="gdpoltools[remove][drop]" value="on" /> <?php _e("Remove plugins database table and all data in them", "gd-topic-polls"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdpoltools[remove][truncate]" value="on" /> <?php _e("Remove all data from database table", "gd-topic-polls"); ?>
        </label><br/>
        <hr/>
        <p><?php _e("Database tables that will be affected", "gd-topic-polls"); ?>:</p>
        <ul style="list-style: inside disc;">
            <li><?php echo gdpol_db()->votes; ?></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Disable Plugin", "gd-topic-polls"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdpoltools[remove][disable]" value="on" /> <?php _e("Disable plugin", "gd-topic-polls"); ?>
        </label>
    </div>
</div>
