<?php

if (!defined('ABSPATH')) exit;

/** @global wpdb $wpdb */
function gdpol_list_database_tables() {
    global $wpdb;

    return array(
        $wpdb->prefix.'gdpol_votes' => 5
    );
}

/** @global wpdb $wpdb */
function gdpol_install_database() {
    global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)) {
        $charset_collate = "default CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)) {
        $charset_collate.= " COLLATE $wpdb->collate";
    }

    $tables = array(
        'votes' => $wpdb->prefix.'gdpol_votes'
    );

    $query = "CREATE TABLE ".$tables['votes']." (
vote_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
user_id bigint(20) unsigned NOT NULL default '0',
poll_id bigint(20) unsigned NOT NULL default '0',
answer_id smallint(5) unsigned NOT NULL default '0',
voted datetime NULL default '0000-00-00 00:00:00' COMMENT 'gmt',
PRIMARY KEY  (vote_id),
KEY user_id (user_id),
KEY poll_id (poll_id),
KEY answer_id (answer_id)
) ".$charset_collate.";";

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

    return dbDelta($query);
}

/** @global wpdb $wpdb */
function gdpol_check_database() {
    global $wpdb;

    $result = array();
    $tables = gdpol_list_database_tables();

    foreach ($tables as $table => $count) {
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $columns = $wpdb->get_results("SHOW COLUMNS FROM $table");

            if ($count != count($columns)) {
                $result[$table] = array("status" => "error", "msg" => __("Some columns are missing.", "gd-topic-polls"));
            } else {
                $result[$table] = array("status" => "ok");
            }
        } else {
            $result[$table] = array("status" => "error", "msg" => __("Table missing.", "gd-topic-polls"));
        }
    }

    return $result;
}

/** @global wpdb $wpdb */
function gdpol_truncate_database_tables() {
    global $wpdb;

    $tables = array_keys(gdpol_list_database_tables());

    foreach ($tables as $table) {
        $wpdb->query("TRUNCATE TABLE ".$table);
    }
}

/** @global wpdb $wpdb */
function gdpol_drop_database_tables() {
    global $wpdb;

    $tables = array_keys(gdpol_list_database_tables());

    foreach ($tables as $table) {
        $wpdb->query("DROP TABLE IF EXISTS ".$table);
    }
}
