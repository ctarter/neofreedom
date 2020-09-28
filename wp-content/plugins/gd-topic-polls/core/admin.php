<?php

if (!defined('ABSPATH')) exit;

class gdpol_admin_core extends d4p_admin_core {
    public $plugin = 'gd-topic-polls';

    public function __construct() {
        parent::__construct();

        $this->url = GDPOL_URL;

        add_action('gdpol_plugin_init', array($this, 'core'));
        add_filter('set-screen-option', array($this, 'screen_options_grid_rows_save'), 10, 3);
    }

    public function load_options() {
        d4p_includes(array(
            array('name' => 'functions', 'directory' => 'admin'), 
            array('name' => 'walkers', 'directory' => 'admin'), 
            array('name' => 'settings', 'directory' => 'admin')
        ), GDPOL_D4PLIB);

        include(GDPOL_PATH.'core/admin/options.php');
    }

    public function screen_options_grid_rows_save($status, $option, $value) {
        if (in_array($option, array('gdpol_rows_per_page_polls', 'gdpol_rows_per_page_votes'))) {
            return absint($value);
        }

        return $status;
    }

    public function screen_options_grid_rows_polls() {
        $args = array(
            'label' => __("Rows", "gd-topic-polls"),
            'default' => 10,
            'option' => 'gdpol_rows_per_page_polls'
        );

        add_screen_option('per_page', $args);

        require_once(GDPOL_PATH.'core/grids/polls.php');

        $load_table = new gdpol_grid_topic_polls();
    }

    public function screen_options_grid_rows_votes() {
        $args = array(
            'label' => __("Rows", "gd-topic-polls"),
            'default' => 50,
            'option' => 'gdpol_rows_per_page_votes'
        );

        add_screen_option('per_page', $args);

        require_once(GDPOL_PATH.'core/grids/votes.php');

        $load_table = new gdpol_grid_topic_votes();
    }

    public function file($type, $name, $d4p = false, $min = true, $url = null) {
        $get = is_null($url) ? $this->url : $url;

        if ($d4p) {
            $get.= 'd4plib/resources/';
        }

        if ($name == 'font') {
            $get.= 'font/styles.css';
        } else if ($name == 'flags') {
            $get.= 'flags/flags.css';
        } else {
            $get.= $type.'/'.$name;

            if (!$this->is_debug && $type != 'font' && $min) {
                $get.= '.min';
            }

            $get.= '.'.$type;
        }

        return $get;
    }

    public function current_url($with_panel = true) {
        $page = 'admin.php?page='.$this->plugin.'-';

        $page.= $this->page;

        if ($with_panel && $this->panel !== false && $this->panel != '') {
            $page.= '&panel='.$this->panel;
        }

        return self_admin_url($page);
    }

    public function title() {
        return 'GD Topic Polls Lite';
    }

    public function core() {
        parent::core();

        add_action('admin_menu', array($this, 'admin_menu'));

        $this->init_ready();

        if (gdpol_settings()->is_install()) {
            add_action('admin_notices', array($this, 'install_notice'));
        }

        if (gdpol_settings()->is_update()) {
            add_action('admin_notices', array($this, 'update_notice'));
        }

        add_filter('plugin_action_links', array(&$this, 'plugin_actions'), 10, 2);
        add_filter('plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);
    }

    public function plugin_actions($links, $file) {
        if ($file == 'gd-topic-polls/gd-topic-polls.php' ){
            $settings_link = '<a href="admin.php?page=gd-topic-polls-front">'.__("Panel", "gd-topic-polls").'</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    function plugin_links($links, $file) {
        if ($file == 'gd-topic-polls/gd-topic-polls.php' ){
            $links[] = '<a target="_blank" style="color: #cc0000; font-weight: bold;" href="https://plugins.dev4press.com/gd-topic-polls/">'.__("Upgrade to GD Topic Polls Pro", "gd-topic-polls").'</a>';
        }

        return $links;
    }

    public function update_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="updated"><p>';
            echo __("GD Topic Polls is updated, and you need to review the update process.", "gd-topic-polls");
            echo ' <a href="admin.php?page=gd-topic-polls-front">'.__("Click Here", "gd-topic-polls").'</a>.';
            echo '</p></div>';
        }
    }

    public function install_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="updated"><p>';
            echo __("GD Topic Polls is activated and it needs to finish installation.", "gd-topic-polls");
            echo ' <a href="admin.php?page=gd-topic-polls-front">'.__("Click Here", "gd-topic-polls").'</a>.';
            echo '</p></div>';
        }
    }

    public function init_ready() {
        $this->menu_items = array(
            'front' => array('title' => __("Overview", "gd-topic-polls"), 'icon' => 'home'),
            'about' => array('title' => __("About", "gd-topic-polls"), 'icon' => 'info-circle'),
            'polls' => array('title' => __("Polls", "gd-topic-polls"), 'icon' => 'bar-chart'),
            'votes' => array('title' => __("Votes", "gd-topic-polls"), 'icon' => 'users'),
            'settings' => array('title' => __("Settings", "gd-topic-polls"), 'icon' => 'cogs'),
            'tools' => array('title' => __("Tools", "gd-topic-polls"), 'icon' => 'wrench')
        );
    }

    public function admin_init() {
        d4p_include('grid', 'admin', GDPOL_D4PLIB);

        do_action('gdpol_admin_init');
    }

    public function admin_menu() {
        $parent = 'gd-topic-polls-front';

        $this->page_ids[] = add_menu_page(
                        'GD Topic Polls', 
                        'Topic Polls', 
                        'activate_plugins', 
                        $parent, 
                        array($this, 'panel_general'), 
                        gdpol()->svg_icon);

        foreach($this->menu_items as $item => $data) {
            $this->page_ids[] = add_submenu_page($parent, 
                            'GD Topic Polls: '.$data['title'], 
                            $data['title'], 
                            'activate_plugins', 
                            'gd-topic-polls-'.$item, 
                            array($this, 'panel_general'));
        }

        $this->admin_load_hooks();
    }

    public function admin_load_hooks() {
        foreach ($this->page_ids as $id) {
            add_action('load-'.$id, array($this, 'load_admin_page'));
        }

        add_action('load-topic-polls_page_gd-topic-polls-polls', array($this, 'screen_options_grid_rows_polls'));
        add_action('load-topic-polls_page_gd-topic-polls-votes', array($this, 'screen_options_grid_rows_votes'));
    }

    public function load_admin_page() {
        $this->help_tab_sidebar();

        do_action('gdpol_load_admin_page_'.$this->page);

        if ($this->panel !== false && $this->panel != '') {
            do_action('gdpol_load_admin_page_'.$this->page.'_'.$this->panel);
        }

        $this->help_tab_getting_help();
    }

    public function current_screen($screen) {
        if (isset($screen->post_type) && !empty($screen->post_type)) {
            $this->post_type = $screen->post_type;
        }

        if (isset($_GET['panel']) && $_GET['panel'] != '') {
            $this->panel = d4p_sanitize_slug($_GET['panel']);
        }

        $id = $screen->id;

        if ($id == 'toplevel_page_gd-topic-polls-front') {
            $this->page = 'front';
        } else if (substr($id, 0, 32) == 'topic-polls_page_gd-topic-polls-') {
            $this->page = substr($id, 32);
        }

        if (isset($_POST['gdpol_handler']) && $_POST['gdpol_handler'] == 'postback') {
            require_once(GDPOL_PATH.'core/admin/postback.php');

            $postback = new gdpol_admin_postback();
        } else if (isset($_GET['gdpol_handler']) && $_GET['gdpol_handler'] == 'getback') {
            require_once(GDPOL_PATH.'core/admin/getback.php');

            $getback = new gdpol_admin_getback();
        }
    }

    public function enqueue_scripts($hook) {
        $load_admin_data = false;

        if ($this->page !== false) {
            d4p_admin_enqueue_defaults();

            wp_enqueue_script('jquery-ui-sortable');

            wp_enqueue_style('fontawesome', GDPOL_URL.'d4plib/resources/fontawesome/css/font-awesome.min.css', array(), gdpol()->fontawesome_version);

            wp_enqueue_style('d4plib-font', $this->file('css', 'font', true), array(), D4P_VERSION.'.'.D4P_BUILD);
            wp_enqueue_style('d4plib-shared', $this->file('css', 'shared', true), array(), D4P_VERSION.'.'.D4P_BUILD);
            wp_enqueue_style('d4plib-admin', $this->file('css', 'admin', true), array('d4plib-shared'), D4P_VERSION.'.'.D4P_BUILD);

            wp_enqueue_script('d4plib-shared', $this->file('js', 'shared', true), array('jquery', 'wp-color-picker'), D4P_VERSION.'.'.D4P_BUILD, true);
            wp_enqueue_script('d4plib-admin', $this->file('js', 'admin', true), array('d4plib-shared'), D4P_VERSION.'.'.D4P_BUILD, true);

            wp_enqueue_style('gdpol-admin', $this->file('css', 'admin-core'), array('d4plib-admin'), gdpol_settings()->file_version());
            wp_enqueue_script('gdpol-admin', $this->file('js', 'admin-core'), array('d4plib-admin', 'jquery-ui-sortable'), gdpol_settings()->file_version(), true);

            do_action('gdpol_admin_enqueue_scripts', $this->page);

            $_data = array(
                'nonce' => wp_create_nonce('gdpol-admin-internal'),
                'wp_version' => GDPOL_WPV,
                'page' => $this->page,
                'panel' => $this->panel,
                'spinner' => '<i class="fa fa-spinner fa-spin"></i> ',
                'button_icon_ok' => '<i class="fa fa-check fa-fw" aria-hidden="true"></i> ',
                'button_icon_cancel' => '<i class="fa fa-times fa-fw" aria-hidden="true"></i> ',
                'button_icon_delete' => '<i class="fa fa-trash fa-fw" aria-hidden="true"></i> ',
                'button_icon_disable' => '<i class="fa fa-ban fa-fw" aria-hidden="true"></i> ',
                'button_icon_empty' => '<i class="fa fa-eraser fa-fw" aria-hidden="true"></i> ',
                'dialog_button_ok' => __("OK", "gd-topic-polls"),
                'dialog_button_cancel' => __("Cancel", "gd-topic-polls"),
                'dialog_button_delete' => __("Delete", "gd-topic-polls"),
                'dialog_button_disable' => __("Disable", "gd-topic-polls"),
                'dialog_button_empty' => __("Empty", "gd-topic-polls"),
                'dialog_title_areyousure' => __("Are you sure you want to do this?", "gd-topic-polls"),
                'dialog_content_pleasewait' => __("Please Wait...", "gd-topic-polls"),
                'display_name_is_required' => __("The name is required!", "gd-topic-polls"),
                'display_prefix_in_list' => __("This prefix is in the list already", "gd-topic-polls"),
                'events_show_details' => __("Show Details", "gd-topic-polls"),
                'events_hide_details' => __("Hide Details", "gd-topic-polls")
            );

            wp_localize_script('gdpol-admin', 'gdpol_data', $_data);

            $load_admin_data = true;
        }

        if ($load_admin_data) {
            wp_localize_script('d4plib-shared', 'd4plib_admin_data', array(
                'string_media_image_remove' => __("Remove", "gd-topic-polls"),
                'string_media_image_preview' => __("Preview", "gd-topic-polls"),
                'string_media_image_title' => __("Select Image", "gd-topic-polls"),
                'string_media_image_button' => __("Use Selected Image", "gd-topic-polls"),
                'string_are_you_sure' => __("Are you sure you want to do this?", "gd-topic-polls"),
                'string_image_not_selected' => __("Image not selected.", "gd-topic-polls")
            ));
        }

        do_action('gdpol_admin_enqueue_scripts');
    }

    public function install_or_update() {
        $install = gdpol_settings()->is_install();
        $update = gdpol_settings()->is_update();

        if ($install) {
            include(GDPOL_PATH.'forms/install.php');
        } else if ($update) {
            include(GDPOL_PATH.'forms/update.php');
        }

        return $install || $update;
    }

    public function panel_general() {
        if (!$this->install_or_update()) {
            $panel_based = array();
            $form_based = array();

            $_current_page = $this->page;
            $_current_panel = $this->panel;

            $path = GDPOL_PATH.'forms/shared/invalid.php';

            if (in_array($_current_page, $panel_based) && $_current_panel != '') {
                $path = GDPOL_PATH.'forms/'.$_current_page.'/'.$_current_panel.'.php';
                $path = apply_filters('gdpol_admin_panel_'.$_current_page.'_'.$_current_panel, $path);
            } else if (in_array($_current_page, $form_based) && $_current_panel != '') {
                $path = GDPOL_PATH.'forms/'.$_current_page.'/form.php';

                $path = apply_filters('gdpol_admin_panel_'.$_current_page.'_'.$_current_panel, $path);
            } else {
                $path = GDPOL_PATH.'forms/'.$_current_page.'.php';
                $path = apply_filters('gdpol_admin_panel_'.$_current_page, $path);
            }

            include($path);
        }
    }
}

global $_gdpol_core_admin;
$_gdpol_core_admin = new gdpol_admin_core();

function gdpol_admin() {
    global $_gdpol_core_admin;
    return $_gdpol_core_admin;
}
