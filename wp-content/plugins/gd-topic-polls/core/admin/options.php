<?php

if (!defined('ABSPATH')) exit;

class gdpol_admin_settings {
    private $settings;

    public $button;

    function __construct() {
        $this->init();
    }

    public function get($panel, $group = '') {
        if ($group == '') {
            return $this->settings[$panel];
        } else {
            return $this->settings[$panel][$group];
        }
    }

    public function settings($panel) {
        $list = array();

        foreach ($this->settings[$panel] as $obj) {
            foreach ($obj['settings'] as $o) {
                $list[] = $o;
            }
        }

        return $list;
    }

    private function init() {
        $this->settings = array(
            'basic' => array(
                'basic_user' => array('name' => __("Users allowed to create polls", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('', '', __("Info", "gd-topic-polls"), __("Poll creation is allowed to users with the 'gdpol_create_poll' capability. This capability will be added to user roles you select here. But, you can allow or deny this role to any user account.", "gd-topic-polls"), d4pSettingType::INFO),
                    new d4pSettingElement('settings', 'global_user_roles', __("User Roles", "gd-topic-polls"), __("Users belonging to selected user roles will get capability to create polls.", "gd-topic-polls"), d4pSettingType::CHECKBOXES, gdpol_settings()->get('global_user_roles', 'settings'), 'array', gdpol_get_user_roles())
                )),
                'basic_forums' => array('name' => __("Disable polls for forums", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('', '', __("Info", "gd-topic-polls"), __("By default, all polls are enabled in all forums. If you want to disable polls for some of the forums, here you can select such forums here.", "gd-topic-polls"), d4pSettingType::INFO),
                    new d4pSettingElement('settings', 'global_disable_forums', __("Select Forums", "gd-topic-polls"), __("Polls will not be available in the forums selected here.", "gd-topic-polls"), d4pSettingType::CHECKBOXES_HIERARCHY, gdpol_settings()->get('global_disable_forums', 'settings'), 'array', gdpol_bbpress_forums_list())
                ))
            ),
            'integration' => array(
                'int_topic' => array('name' => __("Single Topic", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('settings', 'global_auto_embed_poll', __("Embed Poll", "gd-topic-polls"), __("Automatically embed poll on top of the single topic page before the lead topic.", "gd-topic-polls"), d4pSettingType::BOOLEAN, gdpol_settings()->get('global_auto_embed_poll'))
                )),
                'int_form' => array('name' => __("Topic Edit Form", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('settings', 'global_auto_embed_form', __("Embed Form", "gd-topic-polls"), __("Automatically embed poll form at the end of the new topic form.", "gd-topic-polls"), d4pSettingType::BOOLEAN, gdpol_settings()->get('global_auto_embed_form')),
                    new d4pSettingElement('settings', 'global_auto_embed_form_priority', __("Priority", "gd-topic-polls"), '', d4pSettingType::ABSINT, gdpol_settings()->get('global_auto_embed_form_priority'))
                )),
                'int_topics' => array('name' => __("Topics List", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('settings', 'global_auto_embed_icon', __("Poll Icon", "gd-topic-polls"), __("Show poll icon before the topic title in various topics lists (forums, views).", "gd-topic-polls"), d4pSettingType::BOOLEAN, gdpol_settings()->get('global_auto_embed_icon'))
                ))
            ),
            'fields' => array(
                'fields_description' => array('name' => __("Description", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('settings', 'poll_field_description', __("Include description field", "gd-topic-polls"), '', d4pSettingType::BOOLEAN, gdpol_settings()->get('poll_field_description'))
                )),
                'fields_responses' => array('name' => __("Responses", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('settings', 'poll_field_responses_allow_html', __("Allow HTML in responses", "gd-topic-polls"), __("By default, each response will be stripped of all HTML. With this option, you can allow HTML in responses. Each response will be filtered using WordPress KSES functions.", "gd-topic-polls"), d4pSettingType::BOOLEAN, gdpol_settings()->get('poll_field_responses_allow_html'))
                ))
            ),
            'display' => array(
                'display_calc' => array('name' => __("Poll results calculations", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('settings', 'calculate_multi_method', __("Multi vote polls", "gd-topic-polls"), __("Calculate the percentages for each response for multi-choice polls.", "gd-topic-polls"), d4pSettingType::SELECT, gdpol_settings()->get('calculate_multi_method'), 'array', array('votes' => __("Based on total number of votes", "gd-topic-polls"), 'voters' => __("Based on number of voters", "gd-topic-polls")))
                )),
                'display_sort' => array('name' => __("Poll results sorting", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('settings', 'sort_results_by_votes', __("Sort results by votes", "gd-topic-polls"), __("When displaying results, responses will be sorted by the number of votes.", "gd-topic-polls"), d4pSettingType::BOOLEAN, gdpol_settings()->get('sort_results_by_votes'))
                ))
            ),
            'rewrite' => array(
                'rewrite_post_type_poll' => array('name' => __("Poll", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('objects', 'poll_archive_slug', __("Single Slug", "gd-topic-polls"), __("This slug will be added to the bbPress forum root if used.", "gd-topic-polls"), d4pSettingType::SLUG, gdpol_settings()->get('poll_archive_slug', 'objects')),
                    new d4pSettingElement('objects', 'poll_archive_archive', __("Archive Slug", "gd-topic-polls"), __("This slug will be added to the bbPress forum root if used.", "gd-topic-polls"), d4pSettingType::SLUG, gdpol_settings()->get('poll_archive_archive', 'objects')),
                    new d4pSettingElement('objects', 'poll_archive_query_var', __("Query var", "gd-topic-polls"), '', d4pSettingType::SLUG, gdpol_settings()->get('poll_archive_query_var', 'objects'))
                ))
            ),
            'labels' => array(
                'labels_post_type_poll' => array('name' => __("Poll", "gd-topic-polls"), 'settings' => array(
                    new d4pSettingElement('objects', 'label_poll_singular', __("Singular", "gd-topic-polls"), '', d4pSettingType::TEXT, gdpol_settings()->get('label_poll_singular', 'objects')),
                    new d4pSettingElement('objects', 'label_poll_plural', __("Plural", "gd-topic-polls"), '', d4pSettingType::TEXT, gdpol_settings()->get('label_poll_plural', 'objects'))
                ))
            )
        );
    }
}
