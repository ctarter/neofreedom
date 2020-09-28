<?php
/*
Plugin Name: Dashboard Widget & Tool For Feedback
*/

global $feedback_db_version;
$feedback_db_version = '1.0';

function feedback_install() {
    global $wpdb;
    global $feedback_db_version;

    $table_name = $wpdb->prefix . 'feedbackbox';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		count mediumint(9) NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    add_option( 'feedback_db_version', $feedback_db_version );
}

function feedback_install_data() {
    global $wpdb;
    $count = 1;
    $table_name = $wpdb->prefix . 'feedbackbox';
    $wpdb->insert(
        $table_name,
        array(
            'count' => $count,
            'url' => '2951',
        )
    );
}




add_action( 'wp_dashboard_setup', 'dw_dashboard_add_widgets' );
function dw_dashboard_add_widgets() {
    wp_add_dashboard_widget( 'dw_dashboard_widget_news', __( 'Freedom Feedback', 'dw' ), 'dw_dashboard_widget_news_handler', 'dw_dashboard_widget_news_config_handler' );
}

function dw_dashboard_widget_news_handler() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedbackbox';
    $results = $wpdb->get_results( "SELECT * FROM {$table_name} ", OBJECT );
    $table = '<table><tr><td>TITLE</td><td>COUNT</td><td>LINK</td></tr>';
    foreach($results as $page){
        $table.='<tr>';
        $ID = $page->url;
        $args = array(
            'p'         => $ID, // ID of a page, post, or custom type
            'post_type' => 'any'
        );
        $my_posts = new WP_Query($args);
        $d = 'd';
        $title = $my_posts->posts[0]->post_title;
        $link = get_permalink($ID);
        $count = $page->count;
        $table.='<td>'.$title.'</td>';
        $table.='<td>'.$count.'</td>';
        $table.='<td><a href="'.$link.'">link</a></td>';
        $table.='</tr>';
    }
    $table .= '</table>';
    _e( $table, 'dw' );
}

function dw_dashboard_widget_news_config_handler() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedbackbox';
    if ( isset( $_POST['submit'] ) ) {
        if ( isset( $_POST['vehicle'] )  ) {
            foreach($_POST["vehicle"] as $postdelete){
                $wpdb->delete( $table_name, array( 'url' => $postdelete ) );
            }

        }
    }

    $results = $wpdb->get_results( "SELECT * FROM {$table_name} ", OBJECT );
    $table='';
    foreach($results as $page){
        $table.='<input type="checkbox" name="vehicle[]" value="';
        $ID = $page->url;
        $args = array(
            'p'         => $ID, // ID of a page, post, or custom type
            'post_type' => 'any'
        );
        $my_posts = new WP_Query($args);
        $title = $my_posts->posts[0]->post_title;
        $table.=$ID;
        $table.='">'.$title.'<br>';

    }
    ?>
    <p>
        <?php echo $table ?>
    </p>
    <?php
}
