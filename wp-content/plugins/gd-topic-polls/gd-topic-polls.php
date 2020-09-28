<?php

/*
Plugin Name: GD Topic Polls
Plugin URI: https://plugins.dev4press.com/gd-topic-polls/
Description: Implements polls system for bbPress powered forums, where users can add polls to topics, with settings to control voting, poll closing, display of results and more.
Version: 1.4
Author: Milan Petrovic
Author URI: https://www.dev4press.com/
Text Domain: gd-topic-polls

== Copyright ==
Copyright 2008 - 2019 Milan Petrovic (email: milan@gdragon.info)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>
*/

$gdpol_dirname_basic = dirname(__FILE__).'/';
$gdpol_urlname_basic = plugins_url('/gd-topic-polls/');

define('GDPOL_PATH', $gdpol_dirname_basic);
define('GDPOL_URL', $gdpol_urlname_basic);
define('GDPOL_D4PLIB', $gdpol_dirname_basic.'d4plib/');

define('GDPOL_TPL_PATH', $gdpol_dirname_basic.'templates/default/');
define('GDPOL_TPL_URL', $gdpol_urlname_basic.'templates/default/');

require_once(GDPOL_D4PLIB.'d4p.core.php');

d4p_includes(array(
    array('name' => 'plugin', 'directory' => 'plugin'), 
    array('name' => 'errors', 'directory' => 'plugin'), 
    array('name' => 'datetime', 'directory' => 'core'),
    array('name' => 'wpdb', 'directory' => 'core'), 
    array('name' => 'settings', 'directory' => 'plugin'), 
    array('name' => 'cache-wordpress', 'directory' => 'functions'), 
    'functions', 
    'sanitize', 
    'access', 
    'wp'
), GDPOL_D4PLIB);

require_once(GDPOL_PATH.'core/version.php');

require_once(GDPOL_PATH.'core/objects/core.db.php');
require_once(GDPOL_PATH.'core/objects/core.poll.php');

require_once(GDPOL_PATH.'core/functions/bbpress.php');
require_once(GDPOL_PATH.'core/functions/internal.php');
require_once(GDPOL_PATH.'core/functions/integration.php');

require_once(GDPOL_PATH.'core/settings.php');
require_once(GDPOL_PATH.'core/plugin.php');

global $_gdpol_db, $_gdpol_core, $_gdpol_settings, $_gdpol_poll;

$_gdpol_db = new gdpol_core_db();
$_gdpol_core = new gdpol_core_plugin();
$_gdpol_settings = new gdpol_settings();

/* @return gdpol_core_db */
function gdpol_db() {
    global $_gdpol_db;
    return $_gdpol_db;
}

/* @return gdpol_core_plugin */
function gdpol() {
    global $_gdpol_core;
    return $_gdpol_core;
}

/* @return gdpol_settings */
function gdpol_settings() {
    global $_gdpol_settings;
    return $_gdpol_settings;
}

if (D4P_ADMIN) {
    d4p_includes(array(
        array('name' => 'admin', 'directory' => 'plugin'),
        array('name' => 'functions', 'directory' => 'admin')
    ), GDPOL_D4PLIB);

    require_once(GDPOL_PATH.'core/admin.php');
}
