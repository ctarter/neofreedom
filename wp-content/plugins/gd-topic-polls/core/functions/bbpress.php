<?php

if (!defined('ABSPATH')) exit;

function gdpol_has_bbpress() {
    if (function_exists('bbp_version')) {
        $version = bbp_get_version();
        $version = intval(substr(str_replace('.', '', $version), 0, 2));

        return $version > 24;
    } else {
        return false;
    }
}

function gdpol_bbpress_version($ret = 'code') {
    if (!gdpol_has_bbpress()) {
        return null;
    }

    $version = bbp_get_version();

    if (isset($version)) {
        if ($ret == 'code') {
            return substr(str_replace('.', '', $version), 0, 2);
        } else {
            return $version;
        }
    }

    return null;
}

function gdpol_is_bbpress() {
    $is = gdpol_has_bbpress() ? is_bbpress() : false;

    return apply_filters('gdpol_is_bbpress', $is);
}

function gdpol_get_user_roles() {
    $roles = array();

    $dynamic_roles = bbp_get_dynamic_roles();

    foreach ($dynamic_roles as $role => $obj) {
        $roles[$role] = $obj['name'];
    }

    return $roles;
}

function gdpol_bbpress_forums_list() {
    $_base_forums = get_posts(array(
        'post_type' => bbp_get_forum_post_type(),
        'numberposts' => -1,
    ));

    $forums = array();

    foreach ($_base_forums as $forum) {
        $forums[$forum->ID] = (object)array(
            'id' => $forum->ID,
            'url' => get_permalink($forum->ID),
            'parent' => $forum->post_parent,
            'title' => $forum->post_title
        );
    }

    return $forums;
}
