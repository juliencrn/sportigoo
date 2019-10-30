=== Head, Footer and Post Injections ===
Tags: header, footer, blog, page, single, post, head, tracking, facebook, og meta tag, open graph, ads, adsense, injections, analytics, amp, pixel
Requires at least: 3.0
Tested up to: 5.2
Stable tag: 3.1.3
Donate link: http://www.satollo.net/donations
Contributors: satollo

Header and Footer plugin let you to add html code to the head and footer sections of your blog... and more!

== Description ==

About WordPress SEO and Facebook Open Graph: I was very unpleased by Yoast invitation to
remove my plugin, and it's not the case. 
[Read more here](http://www.satollo.net/yoast-and-wordpress-seo-this-is-too-much-conflict-with-header-and-footer).

= Head and Footer Codes =

Why you have to install 10 plugins to add Google Analytics, Facebook Pixel, custom
tracking code, Google DFP code, Google Webmaster/Alexa/Bing/Tradedoubler verification code and so on...

With Header and Footer plugin you can just copy the code those services give you
in a centralized point to manage them all. And theme independent: you can change your theme
without loosing the code injected!

= Injection points and features =

* in the <head> page section where most if the codes are usually added
* just after the <body> tag as required by some JavaScript SDK (like Facebook)
* in the page footer (just before the </body> tag)
* recognize and execute PHP code to add logic to your injections
* distinct desktop and mobile injections

= AMP =

A new AMP dedicated section compatible with [AMP plugin](https://wordpress.org/plugins/amp) lets you to inject specific codes in
AMP pages.

= Post Top and Bottom Codes =

Do you need to inject a banner over the post content or after it? No problem. With Header and
Footer you can:

* Add codes on _top_, _bottom_ and in the _middle_ of posts and pages
* Differentiate between _mobile_ and _desktop_ (you don't display the same ad format on both, true?)
* Separate post and page configuration
* Native PHP code enabled
* Shortcodes enabled

= Special Injections =

* Just after the opening BODY tag
* In the middle of post content (using configurable rules)
* Everywhere on template (using placeholders)

= bbPress =

The specific bbPress injections are going to be removed. Switch to my
[Ads for bbPress](https://wordpress.org/ads-bbpress), which is more flexible and complete.

= Limits =

This plugin cannot change the menu or the footer layout, those features must be covered by your theme!

Official page: [Header and Footer](http://www.satollo.net/plugins/header-footer).

Other plugins by Stefano Lissa:

* [Hyper Cache](http://www.satollo.net/plugins/hyper-cache)
* [Newsletter](http://www.thenewsletterplugin.com)
* [Include Me](http://www.satollo.net/plugins/include-me)
* [Comment Plus](http://www.satollo.net/plugins/comment-plus)
* [Thumbnails](http://www.satollo.net/plugins/thumbnails)
* [Ads for bbPress](http://www.satollo.net/plugins/ads-bbpress)

== Installation ==

1. Put the plugin folder into [wordpress_dir]/wp-content/plugins/
2. Go into the WordPress admin interface and activate the plugin
3. Optional: go to the options page and configure the plugin

== Frequently Asked Questions ==

FAQs are answered on [Header and Footer](http://www.satollo.net/plugins/header-footer) page.

== Screenshots ==

1. Configuration panel for blog HEAD and footer sections
2. Configuration panel for post content
3. Configuration panel for Facebook "og" tags
4. Configurable snippets of code to be recalled on other configurations (to save time)

== Changelog ==

= 3.1.3 =

* General compatibility check with latest WP

= 3.1.2 =

* Fixed a debug notice

= 3.1.1 =

* Privacy section in the readme.txt

= 3.1.0 =

* Removed the Facebook setting (move to a specialized plugin to have the Facebook Open Graph Meta) 
* Removed bbPress setting (please use bbpress ads)
* Label fix
* Removed notices
* 5 post injections and 5 generic injections

== Privacy and GDPR ==

This plugin does not collect or process any personal user data.
