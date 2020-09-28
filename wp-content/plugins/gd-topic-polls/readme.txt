=== GD Topic Polls for bbPress ===
Contributors: GDragoN
Donate link: https://plugins.dev4press.com/gd-topic-polls/
Version: 1.4
Tags: bbpress, topic, poll, polls, forum, forums, topic poll, bbpress poll
Requires at least: 4.7
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Implements polls system for bbPress powered forums, where users can add polls to topics, with settings to control voting, poll closing, display of results and more.

== Description ==
GD Topic Polls is a plugin for WordPress and bbPress, and it works with bbPress topics. Users can create polls for new topics, or add a poll for existing topics. Each topic can have one poll.

= Overview of plugin features =
GD Topic Polls is easy to use, it doesn't require any coding, but it offers a lot of customization potential to developers.

Here is the list of most important plugin features:

* Create polls with new topics
* Add polls to the existing topics
* Edit poll while editing topic
* Control user roles allowed to create new polls
* Disable polls for selected forums
* Optional description field in the poll
* Add two or more answers to the poll
* Reorder poll answers in the poll edit mode
* Set poll to allow one answer only for each voter
* Set poll to allow unlimited answers for each voter
* Set poll to limit the number of answers for each voter
* Auto close the poll when topic is closed
* Control when the poll results can be displayed

And on the administration side, the plugin has many more useful features:

* Panel with the list of all polls
* List of polls panel: disable or enable any poll
* List of polls panel: delete any poll
* List of polls panel: remove all votes from the poll
* Panel with the list of votes
* List of votes panel: filter by poll, user or answer
* List of votes panel: delete votes
* Panel with the plugin settings
* Panel with the import, export and removal tools

Developers can customize the plugin look and feel by changing the templates or styling. Everything plugin displays in the front end are rendered in form of template, and each template can be overridden through the theme. And several functions can be also overridden via theme functions.php file to allow for more customizations.

= Upgrade to GD Topic Polls Pro =
Pro version contains many more great features:

* Widget to show list of polls with forums filtering
* Auto close the poll: when selected date is reached
* Auto close the poll: when number of voters is reached
* Require users to respond to topic before answering poll
* Option to allow users to remove and change their vote
* Display list of voters in the poll results
* Settings to control default values of some poll options
* Settings to control display of voters list in the poll results
* Instant votes Notifications (v2.0)
* Daily Digest votes Notifications (v2.0)
* bbPress Topics View: All topics with polls
* bbPress Topics View: Logged in user topics with polls
* Integration with BuddyPress activity stream

With more features on the roadmap exclusively for Pro version.

* More information about [GD Topic Polls Pro](https://plugins.dev4press.com/gd-topic-polls/)
* Compare [Free vs. Pro Plugin](https://plugins.dev4press.com/gd-topic-polls/free-vs-pro-plugin/)

= More free dev4Press.com plugins for bbPress =
* [GD bbPress Attachments](https://wordpress.org/plugins/gd-bbpress-attachments/) - attachments for topics and replies
* [GD bbPress Tools](https://wordpress.org/plugins/gd-bbpress-tools/) - various expansion tools for forums

= Premium dev4Press.com plugins for bbPress =
* [GD bbPress Toolbox Pro](https://plugins.dev4press.com/gd-bbpress-toolbox/) - collection of features for bbPress
* [GD Topic Polls Pro](https://plugins.dev4press.com/gd-topic-polls/) - add polls to the bbPress topics
* [GD Topic Prefix Pro](https://plugins.dev4press.com/gd-topic-prefix/) - add customizable bbPress topic prefixes
* [GD Content Tools Pro](https://plugins.dev4press.com/gd-content-tools/) - meta box for the topic and reply form

= Documentation and Support =
You need to register for free account on [Dev4Press](https://www.dev4press.com/):

* [Frequently Asked Questions](https://support.dev4press.com/kb/product/gd-topic-polls/faqs/)
* [Knowledge Base Articles](https://support.dev4press.com/kb/product/gd-topic-polls/articles/)
* Support Forum: [Free](https://support.dev4press.com/forums/forum/plugins-lite/gd-topic-polls/) & [Pro](https://support.dev4press.com/forums/forum/plugins/gd-topic-polls/)

== Installation ==
= General Requirements =
* PHP: 5.6 or newer
* bbPress 2.5 or newer
* mySQL: 5.5 or newer
* WordPress: 4.7 or newer

= PHP Notice =
* The plugin should work with PHP 5.3 to 5.5, but these versions are no longer used for testing, and they are no longer supported.
* The plugin doesn't work with PHP 5.2 or older versions.

= WordPress Notice =
* The plugin doesn't work with WordPress 4.6 or older versions.

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `gd-topic-polls`.
* Upload `gd-topic-polls` folder to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress
* Check all the plugin settings before using the plugin.

== Frequently Asked Questions ==
= Does plugin works with WordPress MultiSite installations? =
Yes. Each website in the network can activate and use the plugin on it's on.

= Can I translate plugin to my language? =
Yes. POT file is provided as a base for translation. Translation files should go into Languages directory.

== Translations ==
* English

== Upgrade Notice ==
= 1.3 =
New actions and few more improvements. XSS vulnerabilities fixes.

== Changelog ==
= 1.4 - 2019.06.05. =
* New: filter for poll icon displayed in the topics lists
* Edit: few minor styling updates and improvements
* Fix: icon for the topics is missing due to the bug in GD bbPress Toolbox Pro
* Fix: regression issue with the loading of admin side JavaScript

= 1.3.2 - 2019.01.02. =
* Edit: few minor updates to the admin panels
* Edit: d4pLib 2.5.2

= 1.3.1 - 2018.02.22. =
* Fix: wrong method call on the admin side polls list
* Fix: wrong method call on the admin side votes list

= 1.3 - 2018.01.11. =
* New: action fired when the poll is saved
* New: actions fired when the poll vote is saved or removed
* Edit: d4pLib 2.2.4
* Fix: admin side atempt to load missing JavaScript file
* Fix: XSS vulnerability: query string panel was not sanitized
* Fix: XSS vulnerability: panel variable for some pages was not verified

= 1.2 - 2017.10.12. =
* Edit: minor updates to the plugin readme file
* Edit: several settings related updates and changes
* Edit: d4pLib 2.1.2
* Fix: missing several translation strings

= 1.1 - 2017.08.05. =
* New: core poll object: use the static cache for polls
* New: core poll object: additional public methods
* New: show back to voting action when viewing the results
* Edit: hide view results button if poll in results mode

= 1.0 - 2017.08.01. =
* First official release

== Screenshots ==
1. Example poll results
2. Example poll open for voting
3. Create topic poll form
4. Admin side list of polls
5. Admin side list of poll votes
6. Example poll results
