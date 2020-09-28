<?php

if (!defined('ABSPATH')) exit;

class gdpol_core_objects {
    public function __construct() {
        add_action('gdpol_register_objects', array($this, 'register'));
    }

    public function register() {
        $this->_register_poll();
    }

    private function _register_poll() {
        $reg = array(
            'labels' => $this->_post_type_labels(
                    gdpol_settings()->get('label_poll_singular', 'objects'), 
                    gdpol_settings()->get('label_poll_plural', 'objects')),
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'public' => false,
            'rewrite' => false,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'has_archive' => false,
            'query_var' => false,
            'supports' => array(
                'title', 
                'author', 
                'revisions'),
            'show_ui' => false,
            'can_export' => true,
            'delete_with_user' => false,
            'show_in_rest' => false,
            'show_in_nav_menus' => false
        );

        $data = apply_filters('gdpol_posttype_registration_poll', $reg);

        register_post_type(gdpol()->post_type_poll(), $data);
    }

    public function get_base_slug() {
        return bbp_maybe_get_root_slug();
    }

    private function _post_type_slug_single() {
        return $this->get_base_slug().gdpol_settings()->get('poll_archive_slug', 'objects');
    }

    private function _post_type_slug_archive() {
        return $this->get_base_slug().gdpol_settings()->get('poll_archive_archive', 'objects');
    }

    private function _post_type_query_var() {
        return gdpol_settings()->get('poll_archive_query_var', 'objects');
    }

    private function _post_type_labels($singular, $plural) {
        $labels = array(
            'name' => $plural,
            'singular_name' => $singular
        );

        $labels['add_new'] = __("Add New", "gd-topic-polls").' '.$singular;
        $labels['add_new_item'] = __("Add New", "gd-topic-polls").' '.$singular;
        $labels['edit_item'] = __("Edit", "gd-topic-polls").' '.$singular;
        $labels['new_item'] = __("New", "gd-topic-polls").' '.$singular;
        $labels['view_item'] = __("View", "gd-topic-polls").' '.$singular;
        $labels['view_items'] = __("View", "gd-topic-polls").' '.$plural;
        $labels['search_items'] = __("Search", "gd-topic-polls").' '.$plural;
        $labels['not_found'] = __("No", "gd-topic-polls").' '.$plural.' '.__("Found", "gd-topic-polls");
        $labels['not_found_in_trash'] = __("No", "gd-topic-polls").' '.$plural.' '.__("Found In Trash", "gd-topic-polls");
        $labels['parent_item_colon'] = __("Parent", "gd-topic-polls").' '.$plural.':';
        $labels['all_items'] = __("All", "gd-topic-polls").' '.$plural;
        $labels['archives'] = $singular.' '.__("Archives", "gd-topic-polls");
        $labels['attributes'] = $singular.' '.__("Attributes", "gd-topic-polls");
        $labels['insert_into_item'] = __("Insert into", "gd-topic-polls").' '.$singular;
        $labels['uploaded_to_this_item'] = __("Uploaded to this", "gd-topic-polls").' '.$singular;
        $labels['featured_image'] = __("Featured image", "gd-topic-polls");
        $labels['set_featured_image'] = __("Set featured image", "gd-topic-polls");
        $labels['remove_featured_image'] = __("Remove featured image", "gd-topic-polls");
        $labels['use_featured_image'] = __("Use featured image", "gd-topic-polls");
        $labels['menu_name'] = $plural;
        $labels['filter_items_list'] = __("Filter", "gd-topic-polls").' '.$plural.' '.__("list", "gd-topic-polls");
        $labels['items_list_navigation'] = $plural.' '.__("list navigation", "gd-topic-polls");
        $labels['items_list'] = $plural.' '.__("list", "gd-topic-polls");

        $labels['name_admin_bar'] = $singular;

        return $labels;
    }
}
