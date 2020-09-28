<?php

if (!defined('ABSPATH')) exit;

class gdpol_admin_getback {
    public function __construct() {
        if (gdpol_admin()->page === 'tools') {
            if (isset($_GET['run']) && $_GET['run'] == 'export') {
                $this->tools_export();
            }
        }

        if (gdpol_admin()->page === 'votes') {
            if (isset($_GET['single-action']) && $_GET['single-action'] == 'delete') {
                $this->vote_delete();
            }

            if ((isset($_GET['action']) && $_GET['action'] != '-1') || 
                (isset($_GET['action2']) && $_GET['action2'] != '-1')) {
                $this->vote_delete_bulk();
            }
        }

        if (gdpol_admin()->page === 'polls') {
            if (isset($_GET['single-action']) && $_GET['single-action'] == 'disable') {
                $this->poll_disable();
            }

            if (isset($_GET['single-action']) && $_GET['single-action'] == 'enable') {
                $this->poll_enable();
            }

            if (isset($_GET['single-action']) && $_GET['single-action'] == 'delete') {
                $this->poll_delete();
            }

            if (isset($_GET['single-action']) && $_GET['single-action'] == 'clear') {
                $this->poll_empty();
            }
        }

        do_action('gdpol_admin_getback_handler', gdpol_admin()->page);
    }

    private function poll_disable() {
        check_ajax_referer('gdpol-admin-panel');

        $poll_id = isset($_GET['poll']) ? absint($_GET['poll']) : 0;
        $poll = gdpol_obj_poll::load($poll_id);

        $msg = 'poll-disable-failed';

        if (!is_wp_error($poll)) {
            $poll->set_status('disable');
            $msg = 'poll-disable-ok';
        }

        $url = gdpol_admin()->current_url().'&message='.$msg;

        wp_redirect($url);
        exit;
    }

    private function poll_delete() {
        check_ajax_referer('gdpol-admin-panel');

        $poll_id = isset($_GET['poll']) ? absint($_GET['poll']) : 0;
        $poll = gdpol_obj_poll::load($poll_id);

        $msg = 'poll-delete-failed';

        if (!is_wp_error($poll)) {
            delete_post_meta($poll->topic_id, '_bbp_topic_poll_id');
            wp_delete_post($poll_id, true);
            gdpol_db()->empty_votes($poll_id);
            $msg = 'poll-delete-ok';
        }

        $url = gdpol_admin()->current_url().'&message='.$msg;

        wp_redirect($url);
        exit;
    }

    private function poll_empty() {
        check_ajax_referer('gdpol-admin-panel');

        $poll_id = isset($_GET['poll']) ? absint($_GET['poll']) : 0;
        $poll = gdpol_obj_poll::load($poll_id);

        $msg = 'poll-empty-failed';

        if (!is_wp_error($poll)) {
            gdpol_db()->empty_votes($poll_id);
            $msg = 'poll-empty-ok';
        }

        $url = gdpol_admin()->current_url().'&message='.$msg;

        wp_redirect($url);
        exit;
    }

    private function vote_delete() {
        check_ajax_referer('gdpol-admin-panel');

        $vote_id = isset($_GET['vote']) ? absint($_GET['vote']) : 0;

        $msg = 'vote-delete-failed';

        if ($vote_id > 0) {
            gdpol_db()->remove_vote_by_id($vote_id);
            $msg = 'vote-delete-ok';
        }

        $url = gdpol_admin()->current_url().'&message='.$msg;

        wp_redirect($url);
        exit;
    }

    public function vote_delete_bulk() {
        check_admin_referer('bulk-votes');

        $action = $this->_bulk_action();

        $msg = 'vote-delete-failed';

        if ($action != '') {
            $items = isset($_GET['vote']) ? (array)$_GET['vote'] : array();

            if (!empty($items)) {
                gdpol_db()->remove_votes_bulk($items);
                $msg = 'vote-delete-ok';
            }
        }

        $url = gdpol_admin()->current_url().'&message='.$msg;

        wp_redirect($url);
        exit;
    }

    private function tools_export() {
        @ini_set('memory_limit', '128M');
        @set_time_limit(360);

        check_ajax_referer('dev4press-plugin-export');

        if (!d4p_is_current_user_admin()) {
            wp_die(__("Only administrators can use export features.", "gd-topic-polls"));
        }

        $export_date = date('Y-m-d-H-m-s');

        header('Content-type: application/force-download');
        header('Content-Disposition: attachment; filename="gd_topic_polls_settings_'.$export_date.'.gdpol"');

        die(gdpol_settings()->serialized_export());
    }

    private function _bulk_action() {
        $action = isset($_GET['action']) && $_GET['action'] != '' && $_GET['action'] != '-1' ? $_GET['action'] : '';

        if ($action == '') {
            $action = isset($_GET['action2']) && $_GET['action2'] != '' && $_GET['action2'] != '-1' ? $_GET['action2'] : '';
        }

        return $action;
    }
}
