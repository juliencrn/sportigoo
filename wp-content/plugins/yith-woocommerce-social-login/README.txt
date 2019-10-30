=== YITH WooCommerce Social Login ===

Contributors: yithemes
Tags: social login, login, social provider, social authentication, authentication, connect with social, woocommerce connect, facebook, google, register, social networks, twitter
Requires at least: 4.0
Tested up to: 5.3
Stable tag: 1.3.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

YITH WooCommerce Social login is a plugin that allows you to login to your e-commerce site through your Facebook or Twitter or Google account.

== Description ==

YITH WooCommerce Social Login, a plugin that allows your potential customers to access your e-commerce site through their Facebook, Twitter or Google+ account.
A simple action, that yet makes your life and your shop management much easier: your users feel more comfortable in your site, login is just a click far and their propensity
to purchase becomes much higher. Research prove that three quarters of users prefer social login to registration forms and this increases conversion rates and therefore also sales.

**Features:**

Login buttons are automatically added in the following pages:

* "My Account" page
* Checkout page
* Wordpress Login

From option panel, you can change the text of the labels displayed above login buttons and the text in checkout page.

Please, read the the **[official plugin documentation](http://yithemes.com/docs-plugins/yith-woocommerce-social-login/)** to know all plugin features.

== Installation ==
Important: First of all, you have to download and activate WooCommerce plugin, which is mandatory for YITH WooCommerce Social Login to be working.

1. Unzip the downloaded zip file.
2. Upload the plugin folder into the `wp-content/plugins/` directory of your WordPress site.
3. Activate `YITH WooCommerce Social Login` from Plugins page.


= Configuration =
YITH WooCommerce Social Login will add a new tab called "Social Login" in "YIT Plugins" menu item. There, you will find all YITH plugins with quick access to plugin setting page.

== Frequently Asked Questions ==

= I would like to allow only Facebook login, what should I do?  =
Go to Yit Plugins->Social Login->Settings: there you can enable only Facebook as Social Network available for login.

= What are the main changes in plugin translation? =
Recently YITH WooCommerce Social Login has been selected to be included in the "translate.wordpress.org" translate programme.
In order to import correctly the plugin strings in the new system, we had to change the text domain form 'ywsl' to 'yith-woocommerce-social-login'.
Once the plugin will be imported in the translate.wordpress.org system, the translations of other languages will be downloaded directly from WordPress, without using any .po and .mo files. Moreover, users will be able to participate in a more direct way to plugin translations, suggesting texts in their languages in the dedicated tab on translate.wordpress.org.
During this transition step, .po and .mo files will be used as always, but in order to be recognized by WordPress, they will need to have a new nomenclature, renaming them in:

* yith-woocommerce-social-login-[WORDPRESS LOCALE].po
* yith-woocommerce-social-login-[WORDPRESS LOCALE].mo

== Screenshots ==

1. Setting Options
2. YITH WooCommerce Social Login in "My Account" page
3. YITH WooCommerce Social Login in Checkout page
4. YITH WooCommerce Social Login in WordPress Login


== Changelog ==

= 1.3.6 - Released on 30 October 2019 =

* Update: Plugin framework

= 1.3.5 - Released on 29 October 2019 =

* New: Support for WordPress 5.3
* New: Support for WooCommerce 3.8
* Update: Plugin framework

= 1.3.4 - Released on 24 July 2019 =

* New: Support for WooCommerce 3.7
* Update: Plugin framework

= 1.3.3 - Released on 12 June 2019 =

* Update: Plugin framework

= 1.3.2 - Released on 29 April 2019 =

* Update: Facebook icon

= 1.3.1 - Released on 23 April 2019 =

* Update: Plugin framework

= 1.3.0 - Released on 05 April 2019 =

* New: Support for WooCommerce 3.6
* Update: Plugin framework

= 1.2.9 - Released on 11 February 2019 =

* Update: Plugin framework
* Update: Hybrid Library to 2.13.0
* Update: Google Library to remove Google+
* Update: Google icon

= 1.2.8 - Released on 28 January 2019 =

* Update: Plugin framework

= 1.2.7 - Released on 10 December 2018 =

* New: Support for WordPress 5.0
* Update: Plugin framework

= 1.2.6 - Released on 06 December 2018 =

* New: Support for WordPress 5.0
* Update: Plugin framework

= 1.2.5 - Released on 24 October 2018 =

* Update: Plugin framework

= 1.2.3 - Released on 18 October 2018 =

* New: Support for WooCommerce 3.5
* Tweak: Twitter callback URL
* Update: Plugin framework

= 1.2.2 - Released on 27 September 2018 =

* Update: Plugin framework
* Fix: Fixed warning for session_destroy()
* Fix: Removed public_actions from Facebook
* Fix: Google+ login scopes

= 1.2.1 - Released on 16 May 2018 =

* New: Support for WordPress 4.9.6
* New: Support for WooCommerce 3.4
* Dev: Added Classes YITH_WC_Social_Login_Session
* Updated: Hybrid Library 2.10.0
* Update: Plugin framework
* Fix: Facebook login flow

= 1.2.0 - Released on 31 March 2017 =

* New: Support for WooCommerce 3.0
* Update: Plugin framework

= 1.1.2 - Released on 16 February 2017 =

* Fix: Issue when are called functions include/require in hybridauth library
* Update: Plugin framework

= 1.1.1 - Released on 28 December 2016 =

* New: Action to send email to customer after the registration
* Update: Hybrid Library 2.8.2


= 1.1.0 =
* Tweak: Clear the session when a user logged out

= 1.0.9 - Released on 14 December 2015 =
* New: Support for Wordpress 4.4
* Fix: Catch the Exceptions when the login with provider is cancelled
* Update: Plugin framework

= 1.0.8 - Released on 07 December 2015 =

* Fix: YIT panel script not enqueue in admin

= 1.0.7 - Released on 04 December 2015 =

* New: Swedish translation
* Update: Hybrid Library 2.6.0
* Update: Changed Text Domain from 'ywsl' to 'yith-woocommerce-social-login'

= 1.0.6 - Released on 14 September 2015 =

* Fix: Template social buttons
* Fix: Removed "read_stream" Facebook scope
* Update: Plugin framework

= 1.0.5 - Released on 12 August 2015 =

* New: Support for WooCommerce 2.4.2
* Update: Plugin framework

= 1.0.4 - Released on 19 June 2015 =

* Fix: Security issues

= 1.0.3 - Released on 14 May 2015 =

* Fix: Refeal link

= 1.0.2 - Released on 12 May 2015 =

* Fix: Removed "read_friendlists" scope

= 1.0.1 - Released on 24 April 2015 =

* New: Wordpress 4.2 compatibility
* Fix: Wp security issue

= 1.0.0 - Released on 12 March 2015 =
Initial release

== Suggestions ==
If you have any suggestions concerning how to improve YITH WooCommerce Social Login, you can [write to us](mailto:plugins@yithemes.com "Your Inspiration Themes"), so that we can improve YITH WooCommerce Social Login.

