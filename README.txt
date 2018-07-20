=== GeoTargeting Pro ===
Contributors: timersys
Donate link: https://geotargetingwp.com/
Tags: geotargeting, wordpress geotargeting, geolocation, geo target, geo targeting, ip geo detect
Requires at least: 3.6
Tested up to: 4.9.7
Stable tag: 2.3.4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

GeoTargeting for WordPress will let you country-target your content based on users IP's and GeotargetinWP API

== Description ==

Geo Targeting plugin for WordPress will let you create dynamic content based on your users country.

With a simple shortcode you will be able to specify which countries are capable of seing the content or which countries are not allowed.

E.g:
`[geot country="Argentina"] Messi is the best! [/geot]`
`[geot country="Portugal"] Cristiano ronaldo is the best! [/geot]`

More info and docs on ([https://geotargetingwp.com/docs/geotargeting-pro/](https://geotargetingwp.com/docs/geotargeting-pro/))

== Installation ==

1. Unzip and Upload the directory 'geo-targeting' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to the editor and use as many shortcodes as needed


== Frequently Asked Questions ==

= None yet =


== Changelog ==
= 2.3.4.4 =
* Fix issue with ACF latest version
* Clean up database of old wp_session records

= 2.3.4.3 =
* Core update that fix headers sent error and exclude geolocation feature not working

= 2.3.4.2 =
* Fixed core bug that on certain php version geo target function won't return results
* Added cache bust for admin assets

= 2.3.4.1 =
* Fixed issue with ACF free version that was breaking javascript

= 2.3.4 =
* Updated Settings page to improve performance
* Fixed error with timezone function

= 2.3.3 =
* Fixed minor errors
* Update core sessions library

= 2.3.2 =
* Moved all js to footer
* Fixed bug with locales and cache mode that could lead into fatal error

= 2.3.1 =
* Fix bug introduced with locales detection

= 2.3 =
* Added locale option for shortcodes
* Also results locale now it's changed automatically with wordpress language
* Fixed bug where geotargeted posts not working with custom queries inside a post
* Improved debug page
* Core updates

= 2.2.1 =
* Core updates
* Fixed bug on geo posts when used with geo redirects

= 2.2.0.1 =
* Fixed issue with WpRocket cache
* Region names are now slugs

= 2.2 =
* Improved shortcodes generator popup codebase
* Fixed debug mode showing on ajax mode when disabled
* Minor bugfixes
* Improved compatibility with geo blocker
* Core update

= 2.1.2.1 =
* Fixed issues with subscription databases

= 2.1.2 =
* Geo flags new addon
* Minor bugfix
* Update core files. Sessions are now DB stored

= 2.1.1 =
* Updated core files
* Visual composer components updated
* WpEngine Support of Geoip (enterprise and business accounts)

= 2.1.0.1 =
* Plugin didn't pack core updates

= 2.1 - Sept 12 =
* Filter by zip function and shortcodes
* Time zone, lat and lng shortcodes
* Admin access roles can be edited now
* Updated core files
* Minor bugfixes

= 2.0.4.5 - August 30 =
* Updated core files
* Minor bugfixes

= 2.0.4.4 - July 5 =
* Updated core files
* Minor bugfixes

= 2.0.4.3 - Jun 27  =
* Fixed multiple undefined errors and warnings
* Fixed debug with query string
* Update core packages for compatibility with Wp Rocket

= 2.0.4.2 - May 23  =
* Fixed warning showing on posts pages
* Preparing plugin for compatibility with WpRocket Cache plugin
* Small bugfixes

= 2.0.4.1 - Apr 26  =
* Fix bug with ajax mdoe introduced in 2.0.4

= 2.0.4 - Apr 26  =
* Changed how settings work
* Reordered admin for upcoming GeoRedirects plugin
* Improved how cache mode works to save more credits
* Updated core files
* Fixed bug with check license admin

= 2.0.3 - Apr 26  =
* Hotfix, dropdown widget was not working

= 2.0.2 - Apr 25  =
* Fixed bug with cache mode on certain configurations
* Debug data not working on Ajax mode
* Make it clear that widget integration don't work in ajax mode

= 2.0.1 - Apr 19  =
* Different bugfixes, preparing release

= 2.0.0 - Apr 14  =
* Plugin recoded for new API
