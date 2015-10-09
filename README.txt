=== GeoTargeting Pro ===
Contributors: timersys
Donate link: http://wp.timersys.com/geotargeting/
Tags: geotargeting, wordpress geotargeting, geolocation, geo target, geo targeting, ip geo detect
Requires at least: 3.6
Tested up to: 4.3.1
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

GeoTargeting for WordPress will let you country-target your content based on users IP's and Geocountry Ip database

== Description ==

Based on Maxmind GeoIP2Lite data Geo Targeting plugin for WordPress will let you create dynamic content based on your users country.

With a simple shortcode you will be able to specify which countries are capable of seing the content or which countries are not allowed.

E.g:
`[geot country="Argentina"] Messi is the best! [/geot]`
`[geot country="Portugal"] Cristiano ronaldo is the best! [/geot]`


= Plugin's Official Site =

Geotargeting ([http://wp.timersys.com/free-plugins/geotargeting/](http://wp.timersys.com/free-plugins/geotargeting/))

= Wordpress Popups  =

Best popups plugin ever ([http://wp.timersys.com/popups/](http://wp.timersys.com/popups/?utm_source=wsi-free-plugin&utm_medium=readme))

= Install Multiple plugins at once with WpFavs  =

Bulk plugin installation tool, import WP favorites and create your own lists ([http://wordpress.org/extend/plugins/wpfavs/](http://wordpress.org/extend/plugins/wpfavs/))

= Increase your twitter followers  =

Increase your Twitter followers with Twitter likebox Plugin ([http://wordpress.org/extend/plugins/twitter-like-box-reloaded/](http://wordpress.org/extend/plugins/twitter-like-box-reloaded/))

= Wordpress Social Invitations  =

Enhance your site by letting your users send Social Invitations ([http://wp.timersys.com/wordpress-social-invitations/](http://wp.timersys.com/wordpress-social-invitations/?utm_source=social-popup&utm_medium=readme))

== Installation ==

1. Unzip and Upload the directory 'geo-targeting' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to the editor and use as many shortcodes as needed



== Frequently Asked Questions ==

= None yet =


== Changelog ==

= 1.4.1 - 9 Oct =
* Updated db
* Fixed some css issues

= 1.4.0.1 - 13 Aug =
* Hotfix bug breaking site on site redirections feature

= 1.4 - 13 Aug =
* Update database
* Improved performance
* Now posts / pages can be entirely geotargeted
* Now users can create redirections and redirect users to another websites based on countries
* Shortcodes can use a fallback in case nothing is detected
* Advanced custom fields 5 support

= 1.3.3.1 - 8 Jun =
* Hotfix for states targeting and popups

= 1.3.3 - 8 Jun =
* Fixed error emails when run out of queries
* Added support for the three available maxmin webservices (city, country and insigths)
* Fixed error with state geotargeting
* API function returns now return more data
* Updated Maxmind db

= 1.3.2 - 25 May =

* Added better error exception handling
* If premium maxmind user run out of queries, it will fallback to free version instead of throwing error
* Added the ability to target by states

= 1.3.1 - 22 May =

* Fixed bug when IP is not found
* Added falback country in settings page in case IP is not found
* Minor bugfixes


= 1.3 - 27 Apr =

* Added cloudflare geolocation support
* Updated maxmind API to 1.0.3
* Updated Maxmind database

= 1.2 - 24 Feb =

* Added multisite support
* Added Wordpress Popups plugin support to create geotargeted popups

= 1.1 - 11 Feb =
* Added maxmind queries API Support
* Change database for Maxmind api database to improve performance and free mysql
* Added city geolocation
* Added support for maxmind premium databases
* Added session to cache country calculation and improve performance
* removed calculation in backend


= 1.0.1 - 9 Dec =

* Fixed error uploading data on activation or certain servers
* Fixed error in php functions
* Updated IP database
* Fixed undefinied notice

= 1.0.0 = 

* Plugin launched!
