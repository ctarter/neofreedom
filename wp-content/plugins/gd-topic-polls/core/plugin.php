<?php

if (!defined('ABSPATH')) exit;

class gdpol_core_plugin extends d4p_plugin_core {
    public $svg_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMC4xLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHdpZHRoPSIzMDBweCIgaGVpZ2h0PSIzMDBweCIgdmlld0JveD0iMCAwIDMwMCAzMDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMwMCAzMDA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM5RUEzQTg7fQ0KPC9zdHlsZT4NCjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0yNTkuMywxMS40SDQyLjVjLTE3LjEsMC0zMSwxMy45LTMxLDMxdjIxNi44YzAsMTcuMSwxMy45LDMxLDMxLDMxaDIxNi44YzE3LjEsMCwzMS0xMy45LDMxLTMxVjQyLjQNCglDMjkwLjMsMjUuMywyNzYuNCwxMS40LDI1OS4zLDExLjR6IE0xMDEuMywyNDcuNkg2Mi42VjExMi4xaDM4LjdWMjQ3LjZ6IE0xNzAuMywyNDcuNmgtMzguN1Y1NGgzOC43VjI0Ny42eiBNMjM5LjIsMjQ2LjhoLTM4LjcNCgl2LTc3LjRoMzguN1YyNDYuOHoiLz4NCjwvc3ZnPg0K';
    public $fontawesome = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
    public $fontawesome_version = '4.7';

    public $plugin = 'gd-topic-polls';

    private $_datetime = null;
    private $_objects = null;
    private $_bbpress = null;

    public function __construct() {
        parent::__construct();

        add_action('widgets_init', array($this, 'widgets_init'));

        $this->url = GDPOL_URL;

        $this->_datetime = new d4p_datetime_core();
    }

    public function add_caps_to_roles($caps, $role) {
        if (in_array($role, gdpol_settings()->get('global_user_roles'))) {
            $caps['gdpol_create_poll'] = true;
        }

        return $caps;
    }

    public function after_setup_theme() {
        require_once(GDPOL_PATH.'core/functions/templates.php');
    }

    public function plugins_loaded() {
        parent::plugins_loaded();

        define('GDPOL_WPV', intval($this->wp_version));
        define('GDPOL_WPV_MAJOR', substr($this->wp_version, 0, 3));

        if (GDPOL_WPV_MAJOR < 44) {
            add_action('admin_notices', array($this, 'system_requirements_problem'));
        }

        if (!gdpol_has_bbpress()) {
            add_action('admin_notices', array($this, 'bbpress_requirements_problem'));
        } else {
            do_action('gdpol_load_settings');

            require_once(GDPOL_PATH.'core/objects/core.objects.php');

            $this->_objects = new gdpol_core_objects();

            add_action('init', array($this, 'register_objects'), 2);
            add_action('init', array($this, 'plugin_init'), 20);

            add_filter('bbp_get_caps_for_role', array($this, 'add_caps_to_roles'), 10, 2);
        }
    }

    public function system_requirements_problem() {
        ?>

<div class="notice notice-error">
    <p><?php _e("GD Topic Polls requires WordPress 4.4 or newer. Plugin will now be disabled. To use this plugin, upgrade WordPress to 4.4 or newer version.", "gd-topic-polls"); ?></p>
</div>

        <?php

        $this->deactivate();
    }

    public function bbpress_requirements_problem() {
        ?>

<div class="notice notice-error">
    <p><?php _e("GD Topic Polls requires bbPress plugin for WordPress version 2.5 or newer. Plugin will now be disabled. To use this plugin, make sure you are using bbPress 2.5 or newer version.", "gd-topic-polls"); ?></p>
</div>

        <?php

        $this->deactivate();
    }

    public function register_objects() {
        do_action('gdpol_register_objects');
    }

    public function plugin_init() {
        if (!is_admin()) {
            require_once(GDPOL_PATH.'core/objects/core.bbpress.php');

            $this->_bbpress = new gdpol_core_bbpress();
        }

        do_action('gdpol_plugin_init');
    }

    public function file($type, $name, $d4p = false, $min = true, $base_url = null) {
        $get = is_null($base_url) ? $this->url : $base_url;

        if ($d4p) {
            $get.= 'd4plib/resources/';
        }

        if ($name == 'font') {
            $get.= 'font/styles.css';
        } else {
            $get.= $type.'/'.$name;

            if (!$this->is_debug && $type != 'font' && $min) {
                $get.= '.min';
            }

            $get.= '.'.$type;
        }

        return $get;
    }

    public function post_type_poll() {
        return apply_filters('gdpol_post_type_name_poll', 'gd-topic-poll');
    }

    public function deactivate() {
        deactivate_plugins('gd-topic-polls/gd-topic-polls.php', false);
    }

    /** @return gdpol_core_objects */
    public function objects() {
        return $this->_objects;
    }

    /** @return gdpol_core_bbpress */
    public function bbpress() {
        return $this->_bbpress;
    }

    /** @return d4p_datetime_core */
    public function datetime() {
        return $this->_datetime;
    }
}
