<?php

if (!defined('ABSPATH')) exit;

class gdpol_grid_topic_votes extends d4p_grid {
    public $_sanitize_orderby_fields = array('v.vote_id', 'v.user_id', 'v.poll_id', 'v.answer_id', 'v.voted', 'l.component', 'l.logged');
    public $_table_class_name = 'gdpol-grid-votes';

    public $polls = array();

    public function __construct($args = array()) {
        parent::__construct(array(
            'singular'=> 'vote',
            'plural' => 'votes',
            'ajax' => false
        ));
    }

    private function _self($args, $getback = false) {
        $base_url = 'admin.php?page=gd-topic-polls-votes';
        $url = $base_url.'&'.$args;

        if ($getback) {
            $url.= '&_wpnonce='.wp_create_nonce('gdpol-admin-panel');
            $url.= '&gdpol_handler=getback';
            $url.= '&_wp_http_referer='.wp_unslash(self_admin_url($base_url));
        }

        return self_admin_url($url);
    }

    public function extra_tablenav($which) {
        if ($which == 'top') {
            $_sel_poll_id = isset($_GET['poll']) && !empty($_GET['poll']) ? absint($_GET['poll']) : '';
            $_sel_user_id = isset($_GET['user']) && !empty($_GET['user']) ? absint($_GET['user']) : '';
            $_sel_answer_id = isset($_GET['answer']) && !empty($_GET['answer']) ? absint($_GET['answer']) : '';

            echo '<div class="alignleft actions">';
            echo '<input name="poll" title="'.__("Poll ID", "gd-topic-polls").'" style="width: 120px;"  type="number" value="'.$_sel_poll_id.'" placeholder="'.__("Poll ID", "gd-topic-polls").'" />';
            echo '<input name="user" title="'.__("User ID", "gd-topic-polls").'" style="width: 120px;" type="number" value="'.$_sel_user_id.'" placeholder="'.__("User ID", "gd-topic-polls").'" />';
            echo '<input name="answer" title="'.__("Answer ID", "gd-topic-polls").'" style="width: 120px;" type="number" value="'.$_sel_answer_id.'" placeholder="'.__("answer ID", "gd-topic-polls").'" />';
            submit_button(__("Filter", "gd-topic-polls"), 'button', false, false, array('id' => 'gdpol-polls-submit'));
            echo '</div>';
        }
    }

    public function rows_per_page() {
        $per_page = get_user_meta(get_current_user_id(), 'gdpol_rows_per_page_votes', true);

        if (empty($per_page) || $per_page < 1) {
            $per_page = 25;
        }

        return $per_page;
    }

    public function get_columns() {
	return array(
            'cb' => '<input type="checkbox" />',
            'vote_id' => __("ID", "gd-topic-polls"),
            'user_id' => __("User", "gd-topic-polls"),
            'poll_id' => __("Poll & Topic", "gd-topic-polls"),
            'answer_id' => __("Response", "gd-topic-polls"),
            'voted' => __("Voted", "gd-topic-polls")
	);
    }

    function get_sortable_columns() {
	return array(
            'vote_id' => array('v.vote_id', false),
            'user_id' => array('v.user_id', false),
            'poll_id' => array('v.poll_id', false),
            'answer_id' => array('v.answer_id', false),
            'voted' => array('v.voted', false)
	);
    }

    function get_bulk_actions() {
        $bulk = array(
            'delete' => __("Delete", "gd-topic-polls")
        );

        return $bulk;
    }

    function column_cb($item){
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->vote_id);
    }

    public function column_poll_id($item){
        $poll = $this->polls[$item->poll_id];

        $actions = array(
            'view' => '<a href="'.$poll->url().'">'.__("View", "gd-topic-polls").'</a>',
            'edit' => '<a href="'.$poll->url_edit().'">'.__("Edit", "gd-topic-polls").'</a>',
            'votes' => '<a href="admin.php?page=gd-topic-polls-votes&poll='.$poll->id.'">'.__("Votes", "gd-topic-polls").'</a>'
        );

        return '<strong>'.$poll->question.'</strong><br/>'.__("for topic", "gd-topic-polls").' <em>'.bbp_get_topic_title($poll->topic_id).'</em>'.$this->row_actions($actions);
    }

    public function column_answer_id($item) {
        $poll = $this->polls[$item->poll_id];

        $actions = array(
            'votes' => '<a href="admin.php?page=gd-topic-polls-votes&poll='.$poll->id.'&answer='.$item->answer_id.'">'.__("Votes", "gd-topic-polls").'</a>',
            'delete' => '<a class="gdpol-button-delete-vote" href="'.$this->_self('single-action=delete&vote='.$item->vote_id, true).'">'.__("Delete", "gd-topic-polls").'</a>'
        );

        return '['.$item->answer_id.'] '.$poll->get_answer_by_id($item->answer_id).$this->row_actions($actions);
    }

    public function column_user_id($item){
        $render = get_avatar($item->user_email, 40);
        $render.= '<strong>'.$item->display_name.'</strong><br/>'.$item->user_email;

        return $render;
    }

    public function column_voted($item){
        $timestamp = gdpol()->datetime()->timestamp_gmt_to_local(strtotime($item->voted));

        return date('Y.m.d', $timestamp).'<br/>@ '.date('H:m:s', $timestamp);
    }

    public function prepare_items() {
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());

        $per_page = $this->rows_per_page();

        $sql = array(
            'select' => array('v.*',
                'u.user_login',
                'u.display_name',
                'u.user_email'),
            'from' => array(
                gdpol_db()->votes.' v', 
                'INNER JOIN '.gdpol_db()->wpdb()->users.' u ON u.ID = v.user_id', 
                'INNER JOIN '.gdpol_db()->wpdb()->posts.' p ON p.ID = v.poll_id'),
            'where' => array(
                "p.post_type = '".gdpol()->post_type_poll()."'")
        );

        $_sel_poll_id = isset($_GET['poll']) && !empty($_GET['poll']) ? absint($_GET['poll']) : '';
        $_sel_user_id = isset($_GET['user']) && !empty($_GET['user']) ? absint($_GET['user']) : '';
        $_sel_answer_id = isset($_GET['answer']) && !empty($_GET['answer']) ? absint($_GET['answer']) : '';

        if (!empty($_sel_poll_id) && $_sel_poll_id > 0) {
            $sql['where'][] = 'v.poll_id = '.$_sel_poll_id;
        }

        if (!empty($_sel_user_id) && $_sel_user_id > 0) {
            $sql['where'][] = 'v.user_id = '.$_sel_user_id;
        }

        if (!empty($_sel_answer_id) && $_sel_answer_id > 0) {
            $sql['where'][] = 'v.answer_id = '.$_sel_answer_id;
        }

        $orderby = !empty($_GET['orderby']) ? $this->sanitize_field('orderby', $_GET['orderby'], 'v.vote_id') : 'v.vote_id';
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

        foreach ($this->items as $item) {
            $_poll_id = $item->poll_id;

            if (!isset($this->polls[$_poll_id])) {
                $this->polls[$_poll_id] = gdpol_obj_poll::load($_poll_id);
            }
        }

        $this->set_pagination_args(array(
            'total_items' => $this->total,
            'total_pages' => ceil($this->total / $per_page),
            'per_page' => $per_page,
        ));
    }
}
