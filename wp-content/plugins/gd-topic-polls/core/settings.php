<?php

if (!defined('ABSPATH')) exit;

class gdpol_settings extends d4p_settings_core {
    public $base = 'gdpol';

    public $settings = array(
        'core' => array(
            'activated' => 0
        ),
        'settings' => array(
            'global_user_roles' => array('bbp_keymaster', 'bbp_moderator', 'bbp_participant'),
            'global_disable_forums' => array(),
            'global_auto_embed_poll' => true,
            'global_auto_embed_icon' => true,
            'global_auto_embed_form' => true,
            'global_auto_embed_form_priority' => 10,

            'sort_results_by_votes' => false,
            'calculate_multi_method' => 'voters',

            'poll_field_description' => true,
            'poll_field_responses_allow_html' => false
        ),
        'objects' => array(
            'label_poll_singular' => 'Topic Poll',
            'label_poll_plural' => 'Topic Polls',

            'poll_archive_query_var' => 'topic-poll',
            'poll_archive_slug' => 'topic-poll',
            'poll_archive_archive' => 'topic-polls'
        )
    );

    public function __construct() {
        $this->info = new gdpol_core_info();

        add_action('gdpol_load_settings', array($this, 'init'), 2);
    }

    public function set($name, $value, $group = 'settings', $save = false) {
        $old = isset($this->current[$group][$name]) ? $this->current[$group][$name] : null;

        if ($old != $value) {
            do_action('gdpol_settings_value_changed', $name, $group, $old, $value);
        }

        parent::set($name, $value, $group, $save);
    }

    public function file_version() {
        return $this->info_version.'.'.$this->info_build;
    }

    public function remove_plugin_settings_by_group($group) {
        $this->_settings_delete($group);
    }

    protected function _db() {
        require_once(GDPOL_PATH.'core/admin/install.php');

        gdpol_install_database();
    }
}
