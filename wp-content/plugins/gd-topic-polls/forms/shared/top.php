<?php

do_action('gdpol_admin_panel_top');

$pages = gdpol_admin()->menu_items;
$_page = gdpol_admin()->page;
$_panel = gdpol_admin()->panel;

if (!empty($panels)) {
    if ($_panel === false || empty($_panel)) {
        $_panel = 'index';
    }

    $_available = array_keys($panels);

    if (!in_array($_panel, $_available)) {
        $_panel = 'index';
        gdpol_admin()->panel = false;
    }
}

$_classes = array('d4p-wrap', 'wpv-'.GDPOL_WPV, 'd4p-page-'.$_page);

if ($_panel !== false) {
    $_classes[] = 'd4p-panel';
    $_classes[] = 'd4p-panel-'.$_panel;
}

$_message = '';
$_color = '';

if (isset($_GET['message']) && $_GET['message'] != '') {
    $msg = d4p_sanitize_slug($_GET['message']);

    switch ($msg) {
        case 'vote-delete-failed':
            $_message = __("Deletion operation failed.", "gd-topic-polls");
            $_color = 'error';
            break;
        case 'poll-disable-failed':
        case 'poll-enable-failed':
        case 'poll-delete-failed':
        case 'poll-empty-failed':
            $_message = __("Operation failed, specified poll is invalid.", "gd-topic-polls");
            $_color = 'error';
            break;
        case 'poll-disable-ok':
            $_message = __("Poll is disabled.", "gd-topic-polls");
            break;
        case 'poll-enable-ok':
            $_message = __("Poll is enabled.", "gd-topic-polls");
            break;
        case 'poll-delete-ok':
            $_message = __("Poll is deleted.", "gd-topic-polls");
            break;
        case 'poll-empty-ok':
            $_message = __("Poll votes are all removed.", "gd-topic-polls");
            break;
        case 'vote-delete-ok':
            $_message = __("Votes deletion completed.", "gd-topic-polls");
            break;
        case 'invalid':
            $_message = __("Requested operation is invalid.", "gd-topic-polls");
            $_color = 'error';
            break;
        case 'import-failed':
            $_message = __("Import operation failed.", "gd-topic-polls");
            $_color = 'error';
            break;
        case 'saved':
            $_message = __("Settings are saved.", "gd-topic-polls");
            break;
        case 'imported':
            $_message = __("Import operation completed.", "gd-topic-polls");
            break;
        case 'nothing':
            $_message = __("Nothing done.", "gd-topic-polls");
            break;
        case 'nothing-removed':
            $_message = __("Nothing removed.", "gd-topic-polls");
            break;
        case 'removed':
            $_message = __("Removal operation completed.", "gd-topic-polls");
            break;
        default:
            $_color = apply_filters('gdpol_admin_operation_notice_color', '', $msg);
            $_message = apply_filters('gdpol_admin_operation_notice', ucwords(str_replace('-', ' ', $msg)), $msg);
            break;
    }
}

?>
<div class="<?php echo join(' ', $_classes); ?>">
    <div class="d4p-header">
        <div class="d4p-navigator">
            <ul>
                <li class="d4p-nav-button">
                    <a href="#"><i aria-hidden="true" class="<?php echo d4p_get_icon_class($pages[$_page]['icon']); ?>"></i> <?php echo $pages[$_page]['title']; ?></a>
                    <ul>
                        <?php

                        foreach ($pages as $page => $obj) {
                            if ($page != $_page) {
                                echo '<li><a href="admin.php?page=gd-topic-polls-'.$page.'"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</a></li>';
                            } else {
                                echo '<li class="d4p-nav-current"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</li>';
                            }
                        }

                        ?>
                    </ul>
                </li>
                <?php if (!empty($panels)) { ?>
                <li class="d4p-nav-button">
                    <a href="#"><i aria-hidden="true" class="<?php echo d4p_get_icon_class($panels[$_panel]['icon']); ?>"></i> <?php echo $panels[$_panel]['title']; ?></a>
                    <ul>
                        <?php

                        foreach ($panels as $panel => $obj) {
                            if ($panel != $_panel) {
                                $extra = $panel != 'index' ? '&panel='.$panel : '';

                                echo '<li><a href="admin.php?page=gd-topic-polls-'.$_page.$extra.'"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</a></li>';
                            } else {
                                echo '<li class="d4p-nav-current"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</li>';
                            }
                        }

                        ?>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="d4p-plugin">
            GD Topic Polls
        </div>
    </div>
    <?php

    if ($_message != '') {
        echo '<div class="updated '.$_color.'">'.$_message.'</div>';
    }

    ?>
    <div class="d4p-content">
