<h3 style="margin-top: 0;"><?php _e("Additional database tables", "gd-topic-polls"); ?></h3>
<?php

    require_once(GDPOL_PATH.'core/admin/install.php');

    $list_db = gdpol_install_database();

    if (!empty($list_db)) {
        echo '<h4>'.__("Database Upgrade Notices", "gd-topic-polls").'</h4>';
        echo join('<br/>', $list_db);
    }

    echo '<h4>'.__("Database Tables Check", "gd-topic-polls").'</h4>';
    $check = gdpol_check_database();

    $msg = array();
    foreach ($check as $table => $data) {
        if ($data['status'] == 'error') {
            $_proceed = false;
            $_error_db = true;
            $msg[] = '<span class="gdpc-error">['.__("ERROR", "gd-topic-polls").'] - <strong>'.$table.'</strong>: '.$data['msg'].'</span>';
        } else {
            $msg[] = '<span class="gdpc-ok">['.__("OK", "gd-topic-polls").'] - <strong>'.$table.'</strong></span>';
        }
    }

    echo join('<br/>', $msg);
