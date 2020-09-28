<?php

if (!defined('ABSPATH')) exit;

class gdpol_admin_postback {
    public function __construct() {
        $page = isset($_POST['option_page']) ? $_POST['option_page'] : false;

        if ($page !== false) {
            if ($page == 'gd-topic-polls-tools') {
                $this->tools();
            }

            if ($page == 'gd-topic-polls-settings') {
                $this->settings();
            }

            do_action('gdpol_admin_postback_handler', $page);
        }
    }

    private function tools() {
        check_admin_referer('gd-topic-polls-tools-options');

        if (gdpol_admin()->panel == 'remove') {
            $this->remove();
        } else if (gdpol_admin()->panel == 'import') {
            $this->import();
        }
    }

    private function settings() {
        check_admin_referer('gd-topic-polls-settings-options');

        $this->save_settings(gdpol_admin()->panel);

        wp_redirect(gdpol_admin()->current_url().'&message=saved');
        exit;
    }

    private function save_settings($panel) {
        gdpol_admin()->load_options();

        $options = new gdpol_admin_settings();
        $settings = $options->settings($panel);

        $processor = new d4pSettingsProcess($settings);
        $processor->base = 'gdpolvalue';

        $data = $processor->process();

        foreach ($data as $group => $values) {
            if (!empty($group)) {
                foreach ($values as $name => $value) {
                    $value = apply_filters('gdpol_settings_save_settings_value', $value, $name, $group);

                    gdpol_settings()->set($name, $value, $group);
                }

                gdpol_settings()->save($group);
            }
        }
    }

    private function remove() {
        $data = $_POST['gdpoltools'];

        $remove = isset($data['remove']) ? (array)$data['remove'] : array();

        if (empty($remove)) {
            $message = '&message=nothing-removed';
        } else {
            if (isset($remove['settings']) && $remove['settings'] == 'on') {
                gdpol_settings()->remove_plugin_settings_by_group('settings');
            }

            if (isset($remove['objects']) && $remove['objects'] == 'on') {
                gdpol_settings()->remove_plugin_settings_by_group('objects');
            }

            if (isset($remove['drop']) && $remove['drop'] == 'on') {
                require_once(GDPOL_PATH.'core/admin/install.php');

                gdpol_drop_database_tables();

                if (!isset($remove['disable'])) {
                    gdpol_settings()->mark_for_update();
                }
            } else if (isset($remove['truncate']) && $remove['truncate'] == 'on') {
                require_once(GDPOL_PATH.'core/admin/install.php');

                gdpol_truncate_database_tables();
            }

            if (isset($remove['disable']) && $remove['disable'] == 'on') {
                if (isset($remove['settings']) && $remove['settings'] == 'on' && isset($remove['objects']) && $remove['objects'] == 'on') {
                    gdpol_settings()->remove_plugin_settings_by_group('core');
                    gdpol_settings()->remove_plugin_settings_by_group('info');
                }

                gdpol()->deactivate();

                wp_redirect(admin_url('plugins.php'));
                exit;
            }
        }

        wp_redirect(gdpol_admin()->current_url().$message);
        exit;
    }

    private function import() {
        $url = gdpol_admin()->current_url(true);

        $message = 'import-failed';

        if (is_uploaded_file($_FILES['import_file']['tmp_name'])) {
            $raw = file_get_contents($_FILES['import_file']['tmp_name']);
            $data = maybe_unserialize($raw);

            if (is_object($data)) {
                gdpol_settings()->import_from_object($data);

                $message = 'imported';
            }
        }

        wp_redirect($url.'&message='.$message);
        exit;
    }
}
