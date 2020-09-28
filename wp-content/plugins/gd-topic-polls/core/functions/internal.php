<?php

if (!defined('ABSPATH')) exit;

function gdpol_get_topic_poll_id($topic_id = 0) {
    $topic_id = bbp_get_topic_id($topic_id);

    $poll_id = get_post_meta($topic_id, '_bbp_topic_poll_id', true);

    return empty($poll_id) ? 0 : absint($poll_id);
}

function gdpol_topic_has_poll($topic_id = 0) {
    return gdpol_get_topic_poll_id($topic_id) > 0;
}

function gdpol_user_can_create_poll($user_id = 0) {
    $user_id = $user_id == 0 ? bbp_get_current_user_id() : $user_id;

    return user_can($user_id, 'gdpol_create_poll');
}

function gdpol_render_dropdown($values, $args = array(), $data = array(), $echo = true) {
    $defaults = array('selected' => '', 'name' => '', 'id' => '', 'class' => '');

    $args = wp_parse_args($args, $defaults);
    extract($args);

    $render = '';
    $attributes = array(
        'class="'.$class.'"',
        'id="'.$id.'"',
        'name="'.$name.'"'
    );

    foreach ($data as $key => $value) {
        $attributes[] = 'data-'.$key.'="'.$value.'"';
    }

    $selected = is_null($selected) ? array_keys($values) : (array)$selected;
    $associative = !d4p_is_array_associative($values);

    $render.= '<select '.join(' ', $attributes).'>';

    foreach ($values as $value => $display) {
        $real_value = $associative ? $display : $value;

        $sel = in_array($real_value, $selected) ? ' selected="selected"' : '';
        $render.= '<option value="'.$value.'"'.$sel.'>'.$display.'</option>';
    }

    $render.= '</select>';

    if ($echo) {
        echo $render;
    } else {
        return $render;
    }
}

function gdpol_init_empty_poll() {
    global $_gdpol_poll;

    $_gdpol_poll = new gdpol_obj_poll();
}

function gdpol_init_poll($poll_id) {
    global $_gdpol_poll;

    $_gdpol_poll = gdpol_obj_poll::load($poll_id);
}

/** @return gdpol_obj_poll */
function gdpol_get_poll() {
    global $_gdpol_poll;

    return $_gdpol_poll;
}
