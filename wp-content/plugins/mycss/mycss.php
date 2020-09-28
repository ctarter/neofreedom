<?php
/*
Plugin Name: MyCSS
Plugin URI: http://www.channel-ai.com/blog/plugins/mycss/
Description: Add theme independent CSS stylesheet to your blog, supports direct editing via admin panel.
Version: 1.1
Author: Yaosan Yeo
Author URI: http://www.channel-ai.com/blog/
*/

/*  Copyright 2008  Yaosan Yeo  (email : eyn@channel-ai.com)

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

// Add options page under Presentation in the admin menu
function mycss_add_options() {
	add_submenu_page('themes.php', 'MyCSS Editor', 'MyCSS', 8, basename(__FILE__), 'mycss_options_subpanel');
}

add_action('admin_menu', 'mycss_add_options');

// Content of option panel
function mycss_options_subpanel() {
	$file = 'my.css';
	$css = '../wp-content/plugins/mycss/' . $file;

	if (isset($_POST['info_update'])) {
		if (is_writeable($css)) {
			$f = fopen($css, 'w+');
			$newcontent = stripslashes($_POST['newcontent']);
			fwrite($f, $newcontent);
			fclose($f);
			echo '<div id="message" class="updated fade"><p>File edited successfully.</p></div>';
		}
		elseif (is_file($css))
			echo '<div id="message" class="error"><p>' . $file . ' is not writable! Please check the file permissions and try again.</p></div>';
	}

	if (!is_file($css))
		$error = 1;

	if (!$error && filesize($css) > 0) {
		$f = fopen($css,'r');
		$content = fread($f, filesize($css));
		$content = htmlspecialchars($content);
		fclose($f);
	}

	if(!$error) { ?>
<div class="wrap">
	<form method="post" id="template">
		<h2>MyCSS Editor</h2>
		
			<div class="tablenav" style="width: 99%">
				<big><strong>Theme Independent Stylesheet</strong> (my.css)</big>
			</div>
			
			<br class="clear" />
			
			<textarea name="newcontent" style="width: 99%;" class="code" cols="70" rows="25" tabindex="1"><?php echo $content;?></textarea>

	<?php if ( is_writeable($css) ) : ?>
		<p class="submit">
	<?php
		echo '<input type="submit" name="info_update" value="Update CSS" tabindex="2" />';
	?>
		</p>
	<?php else : ?>
		<p><em><?php _e('You need to make this file writable before you can save your changes. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information.'); ?></em></p>
	<?php endif; ?>

	</form>
</div>
	<?php } else {
		echo '<div class="error"><p>Oops, ' . $file . ' cannot be found! Please check the plugin directory and try again.' . '</p></div>';
	}
}

// Import theme independent external stylesheet using Wordpress header
function mycss_header() {
	echo '<link rel="stylesheet" type="text/css" media="screen" href="' . trailingslashit(get_settings('siteurl')) . 'wp-content/plugins/mycss/my.css" />';
	echo "\n";
}

add_action('wp_head', 'mycss_header');
//add_action('admin_head', 'mycss_header');
?>
