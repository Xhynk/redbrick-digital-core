=== Redbrick Digital Core - Plugin ===
Contributors: Trey Rivers
Tags: Review Engine
Requires at least: 3.5.1
Tested up to: 4.4.4
Stable tag: 0.8.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Bring your Review Engine reviews into your WordPress website via shortcodes and widgets. 

== Description ==

This plugins allows you to bring Review Engine reviews into your WordPress site via shortcodes and widget. 

You **must** have a Review Engine *lite* or *paid* account to use this plugin.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to the RBD Core menu item and place your review engine URL into provided text input.
4. Now you may use the Review Engine Shortcode on any page, post, or custom post type with a content editor, or the Review Slider and Social Proof widgets in any widget area.


== Changelog ==

= 0.8.2.1 =
* Fixed RBD Core menu item not showing up on non-multisite installations.

= 0.8.2 =
* Enabled image assets, included Social Proof widget.
* TODO: Make all Review items (Shortcodes and Widgets) follow Schema.

= 0.8.1 =
* Official Beta Release
* Prevented unpublished URLs from being returned in API calls.
* Converted to Review Engine API v2
* Unlocked more options in the Shortcode and Widget

== Plugin Initialization
 ==
Plugin Setup

To enable the Review Slider widget and Review Engine Display Shortcode button, first go to the RBD Core link that's in the left-hand admin menu. Place the URL of your Review Engine in the Review Engine URL box, and then hit the Save Changes button.

Configuring the Review Engine Display Shortcode

Now go into any Page, Post, or Custom Post Type that has a content editor. You'll now have an Add Review Engine Display button. Click on that to access the shortcode editing popup. Here you can adjust the URL, display a title for the Reviews section, and more.

URL: Adjust your threshold to 3, 4, or 5 stars. Note: This will only grab published reviews.
Title: An option H2 tag to preface your reviews section with.
Threshold: Set this to 3, 4, or 5 stars to grab published reviews at and above that threshold.
Service: Choose which service category to grab reviews from, default "All".
Staff: Query against reviews with this staff member, default "All".
Location: Query against reviews from this location, default "All".
Limit Characters: Choose how many characters will show before the Read More link appears (if above the character limit).
Reviews Per Page: Choose how many reviews show before the Show More Reviews button appears (if you have more reviews than are returned).
Max Columns: Determine how many columns of reviews there are. Note: 3 often works best for full width pages, and 2 for pages with sidebars.
Hide X: Choose to hide some Meta Data. Note: City, Location, Staff, and Category are currently inoperable.
Advanced Options:
Enable Ajax: Currently inoperable.
Hide Overview: Hide the overview and aggregate score.
Disable CSS: Will remove some non-structural CSS rules.
Click the Insert Into {Post Type} button and then click publish or update.

Configuring the Review Slider Widget

In your Appearance > Widgets page, there is a Review Slider widget. Drag that into the widget area you'd like to display the slider on. You may have more than one slider on a page if you wish.

Title: Give the widget an optional title.
Review Engine URL: Your Review Engine's URL. Note: This is predefined, but can be changed.
Threshold: Set this to 3, 4, or 5 stars to grab published reviews at and above that threshold.
Review Count: Choose how many reviews will be in the slider.
Character Count: Choose how many characters will show before the Read More link appears (if above the character limit).
Service: Choose which service category to grab reviews from, default "All".
Staff: Query against reviews with this staff member, default "All".
Location: Query against reviews from this location, default "All".
Hide Reviewer: Hide the reviewer's name.
Hide Meta: Hide the meta data under the review.
Disable CSS: Will remove some non-structural CSS rules.
Slider Speed: Will adjust the slider's speed, OFF will prevent all scrolling, and thus show only one review.