<?php
/**
 * Plugin Name: Attachment Manager
 * Plugin URI: http://xavisys.com/wordpress-attachment-manager-plugin/
 * Description: This will allow you to better manage how your plugins are handled.
 * Version: 1.0.1
 * Author: Aaron D. Campbell
 * Author URI: http://xavisys.com/
 *
 * @todo Comment better
 */

/**
 * Changelog:
 * 05/12/2008: 1.0.1
 * 		- Fixed problem with plugin causing custom excerpts to not be shown
 *
 * 01/14/2008: 1.0.0
 * 		- Added to the wordpress.org repository
 *
 * 06/28/2007: 0.1.3
 * 		- Added support for WP 2.2.x-s
 *
 * 01/10/2007: 0.1.2
 * 		- Added the option to not show file lists on excerpts
 *
 * 01/07/2007: 0.1.1
 * 		- Added the option to not show file lists on category pages
 *
 * 01/05/2007: 0.1
 * 		- Original Version
 */

/*  Copyright 2006  Aaron D. Campbell  (email : wp_plugins@xavisys.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//If this is set to true, extra info will be dumped to the browser.
//ONLY do this if you really need it
define('WAM_DEBUG', false);

/**
 * wpAttachmentManager is the class that handles ALL of the plugin functionality.
 * It helps us avoid name collisions
 * http://codex.wordpress.org/Writing_a_Plugin#Avoiding_Function_Name_Collisions
 */
class wpAttachmentManager {
	/**
	 * This function is set as a filter on post content, and adds the attachment
	 * list if needed.
	 *
	 * @param string $post_content - Content of the current post
	 * @return string - Modified post content
	 */
	function attach_to_post($post_content) {
		global $post;
		$list_on_option = get_option('wam_list_on_posts');
		$dont_show_on_cats = (get_option('wam_dont_show_on_cat_page') == 'true');
		$this_post = (get_post_meta($post->ID, '_wam_show_attachments', true) == 'true');
		$dont_show_on_excerpts = (get_option('wam_dont_show_on_excerpts') == 'true');
		$is_excerpt = wpAttachmentManager::excerpt_static();
		//check if we exclude excerpts

		if ( ($list_on_option == 'all' || ($list_on_option == 'some' && $this_post)) &&
				(!$dont_show_on_cats || !is_category()) &&
				(!$dont_show_on_excerpts || !$is_excerpt) ) {
			$post_content .= wpAttachmentManager::get_attachments();
		}
		if (WAM_DEBUG) {
			var_dump(wpAttachmentManager::excerpt_static());
		}
		return $post_content;
	}

	/**
	 * Finds the attachments that belong to the current post, and creates an
	 * unordered list of them
	 *
	 * @return string - unordered list of attachments
	 */
	function get_attachments() {
		global $wpdb, $post;
		$dont_show_linked = (get_option('wam_dont_show_files_already_linked') == 'true');
		$attachment_str = '';
		$debug = '';
		$query = "SELECT `guid`, `post_content`, `post_title` FROM {$wpdb->posts} WHERE (post_status = 'attachment' || post_type = 'attachment') AND post_parent = '{$post->ID}'";
		$attachments = $wpdb->get_results($query);
		if (count($attachments) > 0) {
			$attachment_list = array();
			foreach ( $attachments as $attachment ) {
				if (!$dont_show_linked || (strpos($post->post_content, $attachment->guid)===false)) {
					$link = wpAttachmentManager::get_attachment_link($attachment);
					$attachment_list[] = "{$link}<p>{$attachment->post_content}</p>";
					if (WAM_DEBUG) {
						$debug .= '<pre>'.print_r($attachment, true).'</pre>';
					}
				}
			}
			if (count($attachment_list) > 0) {
				$attachment_str = '<div class="wam_wrap"><h4 class="wam">'.__('Attached Files:').'</h4><ul class="wam_ul"><li>'.implode('</li><li>', $attachment_list).'</li></ul></div>';
			}
		}
		return $attachment_str.$debug;
	}

	/**
	 * Returns a link to a file, including an icon if needed.
	 *
	 * @param object $attachment - wp-attachment object
	 * @return string - link to attachment
	 */
	function get_attachment_link($attachment) {
		$link = "<a href='{$attachment->guid}'";
		if (get_option('wam_show_file_icons') == 'true') {
			$ext = wpAttachmentManager::get_extension($attachment->guid);
			$exts = wpAttachmentManager::get_exts();
			$img = (isset($exts[$ext]))? $exts[$ext]:get_option('wam_default_icon');
			if (!empty($img)) {
				$icon_dir = wpAttachmentManager::get_icon_dir().'/';
				$image_size = getimagesize("{$icon_dir}/{$img}");
				$lp = $image_size[0]+4;
				$height = $image_size[1];
				$siteurl = get_settings('siteurl').'/';
				$link .= ' class="wam_icon_link"';
				$link .= " style='padding-left:{$lp}px; line-height:{$height}px; background-image:url(\"{$siteurl}{$icon_dir}{$img}\");'";
			}
		}
		$link .= ">{$attachment->post_title}</a>";
		return $link;
	}

	/**
	 * This attaches to init, and was needed so we could use wp_redirect.  It
	 * checks to see if an icon is has been requested to be removed.  Then it
	 * removes it, and redirects back to the options page, and gives a succes or
	 * error message
	 */
	function handle_actions() {
		if (isset($_GET['page']) && $_GET['page'] == 'attachment_manager') {
			if ( isset($_GET['action']) ) {
				if ('remove' == $_GET['action']) {
					$exts = wpAttachmentManager::get_exts();
					$exts = array_diff($exts, array($_GET['icon']));
					update_option('wam_exts', $exts);
					$icon_dir = wpAttachmentManager::get_icon_dir().'/';
					if (is_writable(ABSPATH.$icon_dir.$_GET['icon']) && @unlink(ABSPATH.$icon_dir.$_GET['icon'])) {
						wp_redirect('options-general.php?page=attachment_manager&remove=true');
					} else {
						wp_redirect('options-general.php?page=attachment_manager&remove=false');
					}
				}
				exit;
			} elseif (isset($_FILES['wam_add_icon'])) {
				$file = ABSPATH.wpAttachmentManager::get_icon_dir().'/'.basename($_FILES['wam_add_icon']['name']);
				if (move_uploaded_file($_FILES['wam_add_icon']['tmp_name'], $file)) {
					wp_redirect('options-general.php?page=attachment_manager&upload=true');
				} else {
					wp_redirect('options-general.php?page=attachment_manager&upload=false');
				}
			}
		}
	}

	/**
	 * This is used to attach the JS and CSS needed for the options page
	 */
	function config_head() {
		if (isset($_GET['page']) && $_GET['page'] == 'attachment_manager') {
			wpAttachmentManager::css();
?>
<script type="text/javascript">
// <![CDATA[
function check_show_icons() {
	var show_icon_cb = document.getElementById('wam_show_file_icons');
	if (show_icon_cb.checked == true) {
		document.getElementById('wam_icons').style.display = '';
	} else {
		document.getElementById('wam_icons').style.display = 'none';
	}
}
addLoadEvent(check_show_icons);
// ]]>
</script>
<?php
		}
	}

	/**
	 * This is used to display the options page for this plugin
	 */
	function config() {
		$debug = '';
		if (isset($_POST['am_submit'])) {
			$_POST['wam_plugin_dir'] = trim($_POST['wam_plugin_dir'], ' /\\');
			update_option('wam_plugin_dir', $_POST['wam_plugin_dir']);

			$_POST['wam_icon_dir'] = trim($_POST['wam_icon_dir'], ' /\\');
			update_option('wam_icon_dir', $_POST['wam_icon_dir']);

			$_POST['icon_file_types'] = explode(',', strtolower($_POST['icon_file_types']));
			foreach ($_POST['icon_file_types'] as $key=>$ext) {
				$_POST['icon_file_types'][$key] = trim(preg_replace('/[^\w-]/', '', $ext));
			}
			update_option('icon_file_types', $_POST['icon_file_types']);

			$_POST['wam_list_on_posts'] = strtolower($_POST['wam_list_on_posts']);
			update_option('wam_list_on_posts', $_POST['wam_list_on_posts']);

			$_POST['wam_dont_show_files_already_linked'] = (isset($_POST['wam_dont_show_files_already_linked']) && strtolower($_POST['wam_dont_show_files_already_linked']) == 'true')? 'true':'false';
			update_option('wam_dont_show_files_already_linked', $_POST['wam_dont_show_files_already_linked']);

			$_POST['wam_dont_show_on_excerpts'] = (isset($_POST['wam_dont_show_on_excerpts']) && strtolower($_POST['wam_dont_show_on_excerpts']) == 'true')? 'true':'false';
			update_option('wam_dont_show_on_excerpts', $_POST['wam_dont_show_on_excerpts']);

			$_POST['wam_dont_show_on_cat_page'] = (isset($_POST['wam_dont_show_on_cat_page']) && strtolower($_POST['wam_dont_show_on_cat_page']) == 'true')? 'true':'false';
			update_option('wam_dont_show_on_cat_page', $_POST['wam_dont_show_on_cat_page']);

			$_POST['wam_show_file_icons'] = (isset($_POST['wam_show_file_icons']) && strtolower($_POST['wam_show_file_icons']) == 'true')? 'true':'false';
			update_option('wam_show_file_icons', $_POST['wam_show_file_icons']);

			$_POST['wam_default_icon'] = (!isset($_POST['wam_default_icon']))? '':$_POST['wam_default_icon'];
			update_option('wam_default_icon', $_POST['wam_default_icon']);

			$ext_setting = array();
			foreach ($_POST['icons'] as $icon_info) {
				if (!empty($icon_info['exts'])) {
					$ext_array = explode(',', $icon_info['exts']);
					foreach ($ext_array as $ext) {
						$ext = trim(strtolower(preg_replace('/[^\w-]/', '', $ext)));
						$ext_setting[$ext] = $icon_info['icon'];
					}
				}
			}
			update_option('wam_exts', $ext_setting);
		}

		$siteurl = get_settings('siteurl').'/';
		$plugin_dir = wpAttachmentManager::get_plugin_dir();
		$icon_dir = wpAttachmentManager::get_icon_dir();
		$icon_file_types = wpAttachmentManager::get_icon_filetypes();
		$debug .= "<pre>icon_dir: ".print_r(ABSPATH.$icon_dir, true)."\r\nIs icon_dir writable: ".print_r(is_writable(ABSPATH.$icon_dir), true).'</pre>';
		$debug .= "<pre>\$_POST:\r\n".print_r($_POST, true).'</pre>';
		$debug .= "<pre>\$_FILES:\r\n".print_r($_FILES, true).'</pre>';
		if ( is_writable(ABSPATH.$icon_dir) ) {
			$writable = true;
		} else {
			$writable = false;
		}

		if (isset($_GET['remove'])) {
			switch ($_GET['remove']) {
				case 'true':
					$message = __('Icon <strong>removed</strong>.');
					$class = 'updated';
					break;
				default:
					$message = __('Problem removing icon.');
					$class = 'error';
			}
			echo "<div id='message' class='{$class} fade'><p>{$message}</p></div>";
		}
		if (isset($_GET['upload'])) {
			switch ($_GET['upload']) {
				case 'true':
					$message = __('Icon <strong>uploaded</strong>.');
					$class = 'updated';
					break;
				default:
					$message = __('Problem uploading icon.');
					$class = 'error';
			}
			echo "<div id='message' class='{$class} fade'><p>{$message}</p></div>";
		}
		$exts = wpAttachmentManager::get_exts();
		$wam_list_on_posts = get_option('wam_list_on_posts');
		$wam_show_file_icons = get_option('wam_show_file_icons');
		$wam_dont_show_files_already_linked = get_option('wam_dont_show_files_already_linked');
		$wam_dont_show_on_excerpts = get_option('wam_dont_show_on_excerpts');
		$wam_dont_show_on_cat_page = get_option('wam_dont_show_on_cat_page');

		$icon_files = array();
		$h = opendir(ABSPATH.$icon_dir);
		while (($filename = readdir($h)) !== false) {
			if (in_array(wpAttachmentManager::get_extension($filename), $icon_file_types)) {
				$icon_files[] = $filename;
			}
		}
   sort($icon_files);
   $icons[0] = array_slice($icon_files, 0, ceil(count($icon_files)/2));
   $icons[1] = array_slice($icon_files, ceil(count($icon_files)/2));

?>
		<div class="wrap">
			<h2><?php _e('Attachment Manager Options') ?></h2>
<?php if (!$writable) { ?>
			<div class="error fade"><p><?php _e('If your icon directory were <a href="http://codex.wordpress.org/Make_a_Directory_Writable">writable</a>, you would be able to add and remove icons!') ?></p></div>
<?php } ?>
			<form action="" method="post" name="attachment_manager_conf">
					<table class="optiontable">
						<tbody>
							<tr>
								<th scope="row"><?php _e('Plugin Directory <small>(Relative to the Wordpress install directory)</small>:'); ?></th>
								<td><input type="text" name="wam_plugin_dir" value="<?php echo $plugin_dir; ?>" style="width:95%;" /></td>
							</tr>
							<tr>
								<th scope="row"><?php _e('Icons Directory <small>(Relative to the Wordpress install directory)</small>:'); ?></th>
								<td><input type="text" name="wam_icon_dir" value="<?php echo $icon_dir; ?>" style="width:95%;" /></td>
							</tr>
							<tr>
								<th scope="row"><?php _e('Allowed Icon File Extensions <small>(Comma Seperated)</small>:'); ?></th>
								<td><input type="text" name="icon_file_types" value="<?php echo implode(',', $icon_file_types); ?>" style="width:95%;" /></td>
							</tr>
						</tbody>
					</table>
				<p class="submit">
					<input type="submit" name="am_submit" value="Update Options &raquo;" />
				</p>
				<fieldset class="options">
					<legend>Options</legend>
					<h4 style="margin:0;">Show file lists on:</h4>
					<label for="wam_list_on_all_posts"><input type="radio" name="wam_list_on_posts" value="all" id="wam_list_on_all_posts" <?php if ($wam_list_on_posts == 'all') { echo ' checked="checked"';} ?> /> All posts</label><br />
					<label for="wam_list_on_some_posts"><input type="radio" name="wam_list_on_posts" value="some" id="wam_list_on_some_posts" <?php if ($wam_list_on_posts == 'some') { echo ' checked="checked"';} ?> /> Some posts</label><br />
					<label for="wam_list_on_no_posts"><input type="radio" name="wam_list_on_posts" value="none" id="wam_list_on_no_posts" <?php if ($wam_list_on_posts == 'none') { echo ' checked="checked"';} ?> /> No posts</label><br />
					<br />
					<label for="wam_dont_show_files_already_linked"><input type="checkbox" name="wam_dont_show_files_already_linked" value="true" id="wam_dont_show_files_already_linked" <?php if ($wam_dont_show_files_already_linked == 'true') { echo ' checked="checked"';} ?> /> Don't show files that are already linked in the post</label><br />
					<label for="wam_dont_show_on_excerpts"><input type="checkbox" name="wam_dont_show_on_excerpts" value="true" id="wam_dont_show_on_excerpts" <?php if ($wam_dont_show_on_excerpts == 'true') { echo ' checked="checked"';} ?> /> Don't show files on excerpts</label><br />
					<label for="wam_dont_show_on_cat_page"><input type="checkbox" name="wam_dont_show_on_cat_page" value="true" id="wam_dont_show_on_cat_page" <?php if ($wam_dont_show_on_cat_page == 'true') { echo ' checked="checked"';} ?> /> Don't show files on category pages</label><br />
					<label for="wam_show_file_icons"><input type="checkbox" name="wam_show_file_icons" value="true" id="wam_show_file_icons" <?php if ($wam_show_file_icons == 'true') { echo ' checked="checked"';} ?> onclick="check_show_icons();" /> Show icons for files<br /><small>The default icon set is <a href="http://famfamfam.com/lab/icons/silk/">"Silk" by famfamfam</a></small></label><br />
				</fieldset>
				<br />
				<fieldset class="options" id="wam_icons">
					<legend>Icons</legend>
					<table class="wam_ruled" style="width:100%; border:0;" cellpadding="1" cellspacing="0">
						<thead>
							<tr>
								<td class="wam_icon_td">Icon</td>
								<td class="wam_settings_td">Options</td>
								<td>Associated File Types (comma seperated)</td>
								<td>&nbsp;</td>
								<td class="wam_icon_td">Icon</td>
								<td class="wam_settings_td">Options</td>
								<td>Associated File Types (comma seperated)</td>
							</tr>
						</thead>
						<tbody>
<?php
for ($i=0; $i<count($icons[0]); $i++) {
	echo "							<tr>\r\n";

	$ta_name = preg_replace('/[^\w-]/', '', $icons[0][$i]);
	$img_src = "{$siteurl}{$icon_dir}/{$icons[0][$i]}";
	$img_size = getimagesize(ABSPATH.$icon_dir.'/'.$icons[0][$i]);
	$img_alt = htmlentities($icons[0][$i]);
	$extensions = array_keys($exts, $icons[0][$i]);
	sort($extensions);
	$ta_content = implode(',', $extensions);
	$default = (get_option('wam_default_icon') == $icons[0][$i])?' checked="checked"':'';
	echo "								<td><img src='{$img_src}' {$img_size[3]} alt='{$img_alt}' title='{$img_alt}' /></td>\r\n";
	echo "								<td>";
	echo "<label for='wam_default_icon_{$ta_name}'><input type='radio' name='wam_default_icon' id='wam_default_icon_{$ta_name}' value='{$icons[0][$i]}'{$default} /> Default</label><br />";
	if ($writable) {
		echo "<a href='options-general.php?page=attachment_manager&amp;action=remove&amp;icon=".urlencode($icons[0][$i])."' title='".__('Remove this icon')."' class='delete'>".__('Remove')."</a>";
	}
	echo "</td>\r\n";
	echo "								<td><textarea name='icons[{$ta_name}][exts]'>{$ta_content}</textarea><input type='hidden' value='{$icons[0][$i]}' name='icons[{$ta_name}][icon]' /></td>\r\n";
	echo "								<td>&nbsp;</td>\r\n";

	if (isset($icons[1][$i])) {
		$ta_name = preg_replace('/[^\w-]/', '', $icons[1][$i]);
		$img_src = "{$siteurl}{$icon_dir}/{$icons[1][$i]}";
		$img_size = getimagesize(ABSPATH.$icon_dir.'/'.$icons[1][$i]);
		$img_alt = htmlentities($icons[1][$i]);
		$extensions = array_keys($exts, $icons[1][$i]);
		sort($extensions);
		$ta_content = implode(',', $extensions);
		$default = (get_option('wam_default_icon') == $icons[1][$i])?' checked="checked"':'';
		echo "								<td><img src='{$img_src}' {$img_size[3]} alt='{$img_alt}' title='{$img_alt}' /></td>\r\n";
		echo "								<td>";
		echo "<label for='wam_default_icon_{$ta_name}'><input type='radio' name='wam_default_icon' id='wam_default_icon_{$ta_name}' value='{$icons[1][$i]}'{$default} /> Default</label><br />";
		if ($writable) {
			echo "<a href='options-general.php?page=attachment_manager&amp;action=remove&amp;icon=".urlencode($icons[1][$i])."' title='".__('Remove this icon')."' class='delete'>".__('Remove')."</a>";
		}
		echo "</td>\r\n";
		echo "								<td><textarea name='icons[{$ta_name}][exts]'>{$ta_content}</textarea><input type='hidden' value='{$icons[1][$i]}' name='icons[{$ta_name}][icon]' /></td>\r\n";
	} else {
		echo "								<td>&nbsp;</td>\r\n";
		echo "								<td>&nbsp;</td>\r\n";
		echo "								<td>&nbsp;</td>\r\n";
	}
	echo "							</tr>\r\n";
}
$default = (get_option('wam_default_icon') == '')?' checked="checked"':'';
?>
							<tr>
								<td colspan="7"><label for="wam_default_icon_none"><input type='radio' name='wam_default_icon' id='wam_default_icon_none' value=''<?php echo $default; ?> /> No Default</label></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<p class="submit">
					<input type="submit" name="am_submit" value="Update Options &raquo;" />
				</p>
			</form>
<?php
	if ($writable) {
?>
		<form enctype="multipart/form-data" action="" method="post" name="attachment_manager_upload" id="attachment_manager_upload">
			<fieldset class="profile">
				<legend>Add Icon</legend>
				<label for="wam_add_icon"><?php _e('Select Icon File'); ?> <input type="file" name="wam_add_icon" id="wam_add_icon" /></label>
				<p class="submit">
					<input type="submit" name="am_upload_icon" value="Upload Icon" />
				</p>
			</fieldset>
		</form>
<?php
	}
	if (WAM_DEBUG) {
		echo $debug;
	}
?>
		</div>
<?php
	}

	/**
	 * This adds the options page for this plugin to the Options page
	 */
	function admin_menu() {
		add_options_page(__('Attachment Manager Setup'), __('Attachment Manager Options'), 'manage_options', 'attachment_manager', array('wpAttachmentManager', 'config'));
	}

	/**
	 * Used to find the file extension of any file (given a filename.ext,
	 * path/to/filename.ext, or even http://example.com/path/to/filename.ext
	 *
	 * @param string $file_name
	 * @return string - lowercase file extension
	 */
	function get_extension($file_name) {
		return strtolower(array_pop(explode('.', basename($file_name))));
	}

	/**
	 * Either returns the plugin location from the plugin options, or attempts to
	 * locate the plugin directory.
	 *
	 * @return string - plugin directory
	 */
	function get_plugin_dir() {
		$plugin_loc = get_option('wam_plugin_dir');
		if ($plugin_loc == false) {
			$plugin_loc = 'wp-content/plugins/attachment_manager';
			if (function_exists('get_plugins')) {
				foreach (get_plugins() as $plugin_file=>$plugin) {
					if ($plugin['Name'] == 'Attachment Manager' && strip_tags($plugin['Author']) == 'Aaron D. Campbell') {
						$plugin_loc .= dirname($plugin_file);
						break;
					}
				}
			}
			update_option('wam_plugin_dir', $plugin_loc);
		}
		return $plugin_loc;
	}

	/**
	 * Either returns the icon directory from the plugin options, or gets the
	 * plugin directory and adds /icons (the default)
	 *
	 * @return string - icon directory
	 */
	function get_icon_dir() {
		$icon_loc = get_option('wam_icon_dir');
		if ($icon_loc == false) {
			$icon_loc = wpAttachmentManager::get_plugin_dir().'/icons';
			update_option('wam_icon_dir', $icon_loc);
		}
		return $icon_loc;
	}

	/**
	 * Either returns the icon file types from the plugin options, or returns the
	 * default (jpg, jpeg, gif, and png)
	 *
	 * @return array - icon file types
	 */
	function get_icon_filetypes() {
		$icon_file_types = get_option('icon_file_types');
		if ($icon_file_types == false) {
			$icon_file_types = array('jpg', 'jpeg', 'gif', 'png');
			update_option('icon_file_types', $icon_file_types);
		}
		return $icon_file_types;
	}

	/**
	 * Returns the extentions array from the plugin options.  Formatted like:
	 * $arr[ext] = 'icon_file_name.png';
	 *
	 * @return array - extensions array
	 */
	function get_exts() {
		$exts = get_option('wam_exts');
		if (!is_array($exts)) {
			$exts = wpAttachmentManager::get_default_ext_array();
			update_option('wam_exts', $exts);
		}
		if (WAM_DEBUG) {
			echo '<pre>',var_dump($exts),'</pre>';
		}
		return $exts;
	}

	/**
	 * Echos the link to the plugin stylesheet
	 */
	function css() {
		$siteurl = get_option('siteurl');
		$css_file = "{$siteurl}/".wpAttachmentManager::get_plugin_dir().'/css/wp-attachment-manager.css';
		echo "<link rel=\"stylesheet\" href=\"{$css_file}\" type=\"text/css\" />";
	}

	/**
	 * Used to get the default extensions array
	 */
	function get_default_ext_array() {
		return unserialize('a:99:{s:3:"css";s:7:"css.png";s:3:"eml";s:9:"email.png";s:3:"rss";s:8:"feed.png";s:1:"h";s:16:"page_white_h.png";s:3:"avi";s:8:"film.png";s:3:"mov";s:8:"film.png";s:3:"mp4";s:8:"film.png";s:3:"mpg";s:8:"film.png";s:2:"qt";s:8:"film.png";s:2:"rm";s:8:"film.png";s:3:"wmv";s:8:"film.png";s:3:"chm";s:8:"help.png";s:3:"mdb";s:18:"page_white_key.png";s:3:"htm";s:8:"html.png";s:4:"html";s:8:"html.png";s:3:"sht";s:8:"html.png";s:4:"shtm";s:8:"html.png";s:5:"shtml";s:8:"html.png";s:3:"aac";s:9:"music.png";s:3:"aif";s:9:"music.png";s:3:"mid";s:9:"music.png";s:4:"midi";s:9:"music.png";s:3:"mp3";s:9:"music.png";s:3:"mpa";s:9:"music.png";s:2:"ra";s:9:"music.png";s:3:"ram";s:9:"music.png";s:3:"wav";s:9:"music.png";s:3:"wma";s:9:"music.png";s:4:"flac";s:9:"music.png";s:3:"ogg";s:9:"music.png";s:3:"pdf";s:22:"page_white_acrobat.png";s:2:"as";s:27:"page_white_actionscript.png";s:1:"c";s:16:"page_white_c.png";s:3:"raw";s:21:"page_white_camera.png";s:3:"inc";s:18:"page_white_php.png";s:3:"php";s:18:"page_white_php.png";s:4:"php4";s:18:"page_white_php.png";s:4:"php5";s:18:"page_white_php.png";s:4:"phps";s:18:"page_white_php.png";s:5:"phtml";s:18:"page_white_php.png";s:3:"tpl";s:18:"page_white_php.png";s:3:"bmp";s:22:"page_white_picture.png";s:3:"gif";s:22:"page_white_picture.png";s:4:"jpeg";s:22:"page_white_picture.png";s:3:"jpg";s:22:"page_white_picture.png";s:3:"png";s:22:"page_white_picture.png";s:3:"psd";s:22:"page_white_picture.png";s:2:"js";s:23:"page_white_code_red.png";s:3:"ppt";s:25:"page_white_powerpoint.png";s:3:"cfm";s:25:"page_white_coldfusion.png";s:4:"cfml";s:25:"page_white_coldfusion.png";s:2:"bz";s:25:"page_white_compressed.png";s:3:"bz2";s:25:"page_white_compressed.png";s:3:"cab";s:25:"page_white_compressed.png";s:4:"gtar";s:25:"page_white_compressed.png";s:2:"gz";s:25:"page_white_compressed.png";s:4:"gzip";s:25:"page_white_compressed.png";s:3:"rar";s:25:"page_white_compressed.png";s:3:"tar";s:25:"page_white_compressed.png";s:6:"tar-gz";s:25:"page_white_compressed.png";s:3:"tgz";s:25:"page_white_compressed.png";s:3:"war";s:25:"page_white_compressed.png";s:3:"zip";s:25:"page_white_compressed.png";s:2:"rb";s:19:"page_white_ruby.png";s:3:"rbs";s:19:"page_white_ruby.png";s:5:"rhtml";s:19:"page_white_ruby.png";s:3:"cpp";s:24:"page_white_cplusplus.png";s:2:"cs";s:21:"page_white_csharp.png";s:5:"class";s:18:"page_white_cup.png";s:3:"jad";s:18:"page_white_cup.png";s:3:"jar";s:18:"page_white_cup.png";s:3:"jav";s:18:"page_white_cup.png";s:4:"java";s:18:"page_white_cup.png";s:3:"rdf";s:19:"page_white_text.png";s:3:"txt";s:19:"page_white_text.png";s:3:"sql";s:23:"page_white_database.png";s:4:"conf";s:18:"page_white_tux.png";s:2:"ai";s:21:"page_white_vector.png";s:3:"svg";s:21:"page_white_vector.png";s:3:"xls";s:20:"page_white_excel.png";s:3:"doc";s:19:"page_white_word.png";s:3:"fla";s:20:"page_white_flash.png";s:3:"swf";s:20:"page_white_flash.png";s:2:"fh";s:23:"page_white_freehand.png";s:4:"fh10";s:23:"page_white_freehand.png";s:3:"fh3";s:23:"page_white_freehand.png";s:3:"fh4";s:23:"page_white_freehand.png";s:3:"fh5";s:23:"page_white_freehand.png";s:3:"fh6";s:23:"page_white_freehand.png";s:3:"fh7";s:23:"page_white_freehand.png";s:3:"fh8";s:23:"page_white_freehand.png";s:3:"fh9";s:23:"page_white_freehand.png";s:3:"dtd";s:19:"page_white_gear.png";s:3:"tld";s:19:"page_white_gear.png";s:4:"wsdl";s:19:"page_white_gear.png";s:3:"xml";s:19:"page_white_gear.png";s:3:"xsd";s:19:"page_white_gear.png";s:3:"xsl";s:19:"page_white_gear.png";s:5:"xhtml";s:9:"xhtml.png";}');
	}

	/**
	 * Displays the "show attachments" checkbox if wam_list_on_posts is set to some
	 */
	function post_form() {
		if (get_option('wam_list_on_posts') == 'some') {
			global $post;
			$checked = (get_post_meta($post->ID, '_wam_show_attachments', true) == 'true')? ' checked="checked"':'';
			echo "<label for='wam_show_attachments'><input type='checkbox' name='wam_show_attachments' value='true' id='wam_show_attachments'{$checked} /> Show attachments for this post</label><br /><br />";
		}
	}

	/**
	 * Adds or removes the show_attachments meta from the post
	 *
	 * @param int $pid - Post ID
	 */
	function handle_save_post($pid) {
		if (isset($_POST['wam_show_attachments']) && strtolower($_POST['wam_show_attachments']) == 'true') {
			add_post_meta($pid, '_wam_show_attachments', 'true', true);
		} else {
			delete_post_meta($pid, '_wam_show_attachments');
		}
	}

	/**
	 * This is used to set some default values when the plugin is activated.
	 */
	function on_activate() {
		update_option('wam_list_on_posts', 'all');
		update_option('wam_show_file_icons', 'true');
		update_option('wam_dont_show_on_excerpts', 'true');
		wpAttachmentManager::get_exts();
	}

	/**
	 * This is called if a post is in excerpt.  It uses self::excerpt_static to
	 * set a static variable to true, so we can test for it later.
	 */
	function check_excerpt($theExcerpt) {
		if (WAM_DEBUG) {
			echo "excerpt = true\r\n";
		}
		wpAttachmentManager::excerpt_static(true);
		return $theExcerpt;
	}

	/**
	 * This is a lame PHP4 hack since it doesn't support static class variables.
	 * One of these days I'm just going to say "screw people that won't upgrade"
	 * and do ONLY PHP5
	 *
	 * @param bool $set[optional] - What to set the static var to
	 * @return bool - value of static var
	 */
	function excerpt_static($set = null) {
		static $excerpt = false;
		if (!is_null($set)) {
			$excerpt = $set;
		}
		return $excerpt;
	}
}

/**
 * Add filters and actions
 */
//We have to set the priority of the excerpt check to one, or it will run AFTER the post handler
add_filter('get_the_excerpt', array('wpAttachmentManager','check_excerpt'), 1);
add_filter('the_content', array('wpAttachmentManager','attach_to_post'));
add_action('admin_menu', array('wpAttachmentManager','admin_menu'));
add_action('admin_head', array('wpAttachmentManager','config_head'));
add_action('init', array('wpAttachmentManager','handle_actions'));
add_action('wp_head', array('wpAttachmentManager','css'));
add_action('edit_form_advanced', array('wpAttachmentManager','post_form'));
add_action('save_post', array('wpAttachmentManager','handle_save_post'));
register_activation_hook(__FILE__, array('wpAttachmentManager','on_activate'));