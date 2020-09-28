<?php

if (!defined('ABSPATH')) exit;

class gdpol_grid_topic_polls extends d4p_grid {
    public $total = 0;
    public $_table_class_name = 'gdpol-grid-polls';

    public function __construct($args = array()) {
        parent::__construct(array(
            'singular'=> 'poll',
            'plural' => 'polls',
            'ajax' => false
        ));
    }

    private function _self($args, $getback = false) {
        $base_url = 'admin.php?page=gd-topic-polls-polls';
        $url = $base_url.'&'.$args;

        if ($getback) {
            $url.= '&_wpnonce='.wp_create_nonce('gdpol-admin-panel');
            $url.= '&gdpol_handler=getback';
            $url.= '&_wp_http_referer='.wp_unslash(self_admin_url($base_url));
        }

        return self_admin_url($url);
    }

    public function extra_tablenav($which) {
        if ($which == 'hide_it') {
            echo '<div class="alignleft actions">';
            submit_button(__("Filter", "gd-topic-polls"), 'button', false, false, array('id' => 'gdpol-polls-submit'));
            echo '</div>';
        }
    }

    public function rows_per_page() {
        $per_page = get_user_meta(get_current_user_id(), 'gdpol_rows_per_page_polls', true);

        if (empty($per_page) || $per_page < 1) {
            $per_page = 10;
        }

        return $per_page;
    }

    public function get_columns() {
	return array(
            'id' => __("ID", "gd-topic-polls"),
            'question' => __("Question & Description", "gd-topic-polls"),
            'topic' => __("Topic", "gd-topic-polls"),
            'responses' => __("Responses", "gd-topic-polls"),
            'settings' => __("Settings", "gd-topic-polls"),
            'posted' => __("Posted", "gd-topic-polls")
	);
    }

    /** @param gdpol_obj_poll $item */
    public function column_question($item){
        $actions = array(
            'view' => '<a href="'.$item->url().'">'.__("View", "gd-topic-polls").'</a>',
            'edit' => '<a href="'.$item->url_edit().'">'.__("Edit", "gd-topic-polls").'</a>',
            'votes' => '<a href="admin.php?page=gd-topic-polls-votes&poll='.$item->id.'">'.__("Votes", "gd-topic-polls").'</a>'
        );

        if ($item->status == 'enable') {
            $actions['disable'] = '<a class="gdpol-button-disable-poll" href="'.$this->_self('single-action=disable&poll='.$item->id, true).'">'.__("Disable", "gd-topic-polls").'</a>';
        } else {
            $actions['enable'] = '<a href="'.$this->_self('single-action=enable&poll='.$item->id, true).'">'.__("Enable", "gd-topic-polls").'</a>';
        }

        $actions['delete'] = '<a class="gdpol-button-delete-poll" href="'.$this->_self('single-action=delete&poll='.$item->id, true).'">'.__("Delete", "gd-topic-polls").'</a>';
        $actions['empty'] = '<a class="gdpol-button-empty-poll" href="'.$this->_self('single-action=clear&poll='.$item->id, true).'">'.__("Empty", "gd-topic-polls").'</a>';

        return '<strong>'.$item->question.'</strong><br/><em>'.$item->description.'</em>'.$this->row_actions($actions);
    }

    /** @param gdpol_obj_poll $item */
    public function column_topic($item){
        $forum = bbp_get_topic_forum_title($item->topic_id);

        return '<strong>'.$item->topic_title.'</strong><br/>'.__("in forum", "gd-topic-polls").' <em>'.$forum.'</em>';
    }

    /** @param gdpol_obj_poll $item */
    public function column_responses($item) {
        return $item->admin_render_results();
    }

    /** @param gdpol_obj_poll $item */
    public function column_settings($item){
        return $item->admin_render_settings();
    }

    /** @param gdpol_obj_poll $item */
    public function column_posted($item){
        return mysql2date('Y.m.d', $item->posted).'<br/>@ '.mysql2date('H:m:s', $item->posted);
    }

    public function prepare_items() {
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());

        $per_page = $this->rows_per_page();

        $sql = array(
            'select' => array('p.ID',
                't.post_title as topic_title'),
            'from' => array(
                gdpol_db()->wpdb()->posts.' p', 
                'INNER JOIN '.gdpol_db()->wpdb()->posts.' t ON t.ID = p.post_parent'),
            'where' => array(
                "p.post_type = '".gdpol()->post_type_poll()."'")
        );

        $orderby = !empty($_GET['orderby']) ? $this->sanitize_field('orderby', $_GET['orderby'], 'p.ID') : 'p.ID';
        $order = !empty($_GET['order']) ? $this->sanitize_field('order', $_GET['order'], 'DESC') : 'DESC';

        $sql['order'] = $orderby.' '.$order;

        $paged = !empty($_GET['paged']) ? esc_sql($_GET['paged']) : '';
        if (empty($paged) || !is_numeric($paged) || $paged <= 0 ){
            $paged = 1;
        }

        $offset = intval(($paged - 1) * $per_page);
        $sql['limit'] = $offset.', '.$per_page;

        $query = gdpol_db()->build_query($sql);

        $this->items = gdpol_db()->get_results($query);
        $this->total = gdpol_db()->get_found_rows();

        foreach ($this->items as &$item) {
            $poll_id = $item->ID;
            $topic_title = $item->topic_title;

            $item = gdpol_obj_poll::load($poll_id);
            $item->topic_title = $topic_title;
        }

        $this->set_pagination_args(array(
            'total_items' => $this->total,
            'total_pages' => ceil($this->total / $per_page),
            'per_page' => $per_page,
        ));
    }
}
