=== Google Reviews Widget ===
Contributors: richplugins
Donate link: https://richplugins.com/business-reviews-bundle-wordpress-plugin
Tags: Google, reviews, widget, testimonials, Google Places reviews
Requires at least: 2.8
Tested up to: 5.2
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Google reviews widget and shortcode! Shows Google reviews on your WordPress website to increase user confidence and SEO.

== Description ==

`To get more features we have <a href="https://richplugins.com/business-reviews-bundle-wordpress-plugin">Business version</a> of the plugin`

This plugin display Google Business Reviews on your websites in sidebar widget. A unique feature of the plugin is that it saves reviews in WordPress database and have no depend on any services like Google to show reviews in the widget.

[youtube https://www.youtube.com/watch?v=YccWFCkz6H4]

[Online demo](https://richplugins.com/demos/)

= Plugin Features =

* Free!
* SEO
* Refresh reviews
* Shortcode support!
* Trim long reviews with "read more" link
* Support page builders: Elementor, Page Origin, Beaver Builder, WPBakery, Divi
* Displays up to 5 Google business reviews per location
* Keep all reviews in  WordPress database
* Shows real reviews from G+ users to increase user confidence
* Easy search of place and instantly show reviews
* Custom business place photo
* Review list theme
* Pagination
* Support dark websites
* Nofollow, target="_blank" links
* Zero load time regardless of your site
* Works even if Google is unavailable

= Get More Features with Business version! =

[Upgrade to Business](https://richplugins.com/business-reviews-bundle-wordpress-plugin)

* Displays all Google reviews through Business API
* Merge reviews between each other from different platforms (Google, Facebook, Yelp) and places
* Google Rich Snippets (schema.org)
* Powerful <b>Collection Builder</b>
* Slider/Grid themes to show G+ reviews like testimonials
* Google Trust Badge (right/left fixed or embedded)
* 'Write a review' button to available leave Google review directly on your website
* Show/hide any elements (business, reviews, avatars, names, time and etc)
* Any Sorting: recent, oldest, rating, striped
* Include/Exclude words filter
* Minimum rating filter
* Priority support

= Additional Free Reviews Plugins =

Why limit your reviews to just Google Reviews? Check out our other free reviews plugins to add to your site as well:

* [Facebook Reviews Widget](https://wordpress.org/plugins/fb-reviews-widget/ "Facebook Reviews Widget")
* [Yelp Reviews Widget](https://wordpress.org/plugins/widget-yelp-reviews/ "Yelp Reviews Widget")

== Installation ==

1. Unpack archive to this archive to the 'wp-content/plugins/' directory inside of WordPress
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Google Reviews widget
2. Google Reviews badge
3. Google Reviews sidebar

== Changelog ==

= 1.8 =
* Improve: added advance options panel
* Bugfix: 404 link to all reviews page for some places

= 1.7.9 =
* Bugfix: is_admin checks for notice

= 1.7.8 =
* Improve: shortcode support
* Improve: added new locale bg_BG
* Improve: admin notie
* Bugfix: undefined widget property in Elementor

= 1.7.7 =
* Bugfix: some style fixes

= 1.7.6 =
* Bugfix: fix French, Dutch and German translations

= 1.7.5 =
* Update to WordPress 5.2
* Bugfix: conflict with a Bootstrap css in the widget

= 1.7.4 =
* Improve: added auto schedule for refreshing Google reviews
* Improve: added new locale fi_FI
* Improve: added new locale he_IL

= 1.7.3 =
* Improve: reduce reviewer avatars size
* Improve: added option for image lazy loading

= 1.7.2 =
* Update readme and links to the business version

= 1.7.1 =
* Improve: added hook to enqueue scripts and styles

= 1.7 =
* Update to WordPress 5.1
* Bugfix: issue with an empty language

= 1.6.9 =
* Improve: 'read more' link feature
* Improve: direct link to reviews on Google map
* Improve: language support of Google reviews
* Improve: added centered option
* Improve: update widget design
* Improve: update setting page design

= 1.6.8 =
* Update plugin to WordPress 5.0
* Improve: added a default sorting by recent
* Improve: added a detailed instruction how to create a Google Places API key

= 1.6.7 =
* Bugfix: fixed the issues with working on site builders (SiteOrigin, Elementor, Beaver Builder and etc)
* Bugfix: aseerts loaded with plugin's version to uncached

= 1.6.5 =
* Bugfix: fill hash in reviews database

= 1.6.4 =
* Important note: Google Places API now returns reviews with anonymous authors, we added support of this
* Improve: widget works in any page builders (SiteOrigin, Elementor, Beaver Builder and etc.)

= 1.6.3 =
* Important note: Google has changed the Places API and now this is limited to 1 request per day for new accounts, we have changed the plugin according to this limitation
* Improve: added feature to upload custom place photo

= 1.6.2 =
* Bugfix: remove deprecated function create_function()

= 1.6.1 =
* Improve: support of SiteOrigin builder
* Bugfix: fix css classes for the setting page

= 1.6 =
* Feature: Added pagination
* Feature: Get business photo for place
* Feature: Added maximum width and height options
* Improve: Added compatibility with WP multisite
* Improve: Added checking of Google API key
* Bugfix: change DB.google_review.language size to 10 characters
* Bugfix: corrected time ago messages

= 1.5.9 =
* Fixed incorrect messages in the time library
* Added Italian language (it_IT)

= 1.5.8 =
* Improve: Added language setting
* Added Polish language (pl_PL)
* Added Portuguese language (pt_PT)
* Update plugin to WP 4.9

= 1.5.7 =
* Widget options description corrected
* Bugfix: widget options loop
* Added Danish language (da_DK)

= 1.5.6 =
* Tested up to WordPress 4.8
* Improve: change permission from activate_plugins to manage_options for the plugin's settings
* Bugfix: CURLOPT_FOLLOWLOCATION for curl used only with open_basedir and safe_mode disable
* Bugfix: cURL proxy fix

= 1.5.5 =
* Update description
* Bugfix: use default json_encode if it's possible

= 1.5.4 =
* Bugfix: badge, available for old versions, not clickable

= 1.5.3 =
* Bugfix: set charset collate for plugin's tables
* Improve: extract inline init script of widget to separate js file (rplg.js), common for rich plugins

= 1.5.2 =
* Full refactoring of widget code
* Bugfix: widget options check
* Bugfix: SSL unverify connection
* Bugfix: remove line breaks to prevent wrapped it by paragraph editor's plugins
* Added debug information

= 1.5.1 =
* Added Catalan language (ca)
* Added Spanish language (es_ES)
* Added Turkish language (tr_TR)

= 1.5 =
* Remove 'Live Support' tab from setting page
* Added instruction and video how to get Google Places API Key
* Added Dutch language (nl_NL)

= 1.49 =
* Bugfix: time-ago on English by default, update readme, added fr_FR locale

= 1.48 =
* Bugfix, update readme

= 1.47 =
* Added localization for German (de_DE)

= 1.46 =
* Bugfix: remove unused variable

= 1.45 =
* Bugfix: auto-updating existing place rating

= 1.44 =
* Update readme

= 1.43 =
* Bugfix, Added search by Google Place ID

= 1.42 =
* Bugfix: update path images in reviews helper

= 1.4 =
* Bugfix: update path images

== Support ==

* Email support support@richplugins.com
* Live support https://richplugins.com/forum
