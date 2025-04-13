=== WPCasa All Import ===
Contributors: wpsight, kybernetikservices, mrinal013, joehana
Donate link: https://www.paypal.com/donate/?hosted_button_id=SYJNVSP2BKTQ4
Tags: import, wp all import, wpallimport, property, wpcasa
Requires at least: 6.2
Tested up to: 6.8
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add-on for the WP All Import plugin to import any XML or CSV File to WPCasa

== Description ==

Use the WPCasa All Import add-on in combination with WPCasa and WP All Import to bulk import property information from anx XML or CSV file. The WPCasa All Import makes sure that the [WP All Import](https://wordpress.org/plugins/wp-all-import/) plugin displays all the WPCasa custom fields in plain English and imports image galleries in the correct format.

> Please notice that this plugin is an add-on for [WPCasa](https://wordpress.org/plugins/wpcasa/) and will NOT work without the core plugin.

WPCasa is a WordPress solution that provides an intuitive way to manage property listings and create first-class real estate websites.

* Website: [wpcasa.com](https://wpcasa.com)
* Demo: [demo.wpcasa.com](https://demo.wpcasa.com)
* Documentation: [docs.wpcasa.com](https://docs.wpcasa.com)

== Contributors ==
This is a list of contributors to WPCasa All Import.
Many thanks to all of them for contributing and making WPCasa All Import even better.

[Mrinal Haque](https://profiles.wordpress.org/mrinal013/)
[Kybernetik Services](https://www.kybernetik-services.com/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=wpcasa&utm_content=readme)
[Joe Hana](https://wordpress.org/support/users/joehana/)
[codestylist](https://wordpress.org/support/users/codestylist/)

== Installation ==

= Automatic Installation =

Automatic installation is the easiest way to install WPCasa All Import. Log into your WordPress admin and go to _WP-Admin > Plugins > Add New_.

Then type "WPCasa All Import" in the search field and click _Install Now_ once you've found the plugin.

= Manual Installation =

If you prefer to install the plugin manually, you need to download it to your local computer and upload the unzipped plugin folder to the `/wp-content/plugins/` directory of your WordPress installation. Then activate the plugin on _WP-Admin > Plugins_.

== Frequently Asked Questions ==

= Will this plugin work without WPCasa or WP All Import? =

No, this is an add-on plugin for the WPCasa real estate framework in combination with the WP All Import XML/CSV importer and will not work without the corresponding core plugins.

= Why do you call a Google Maps URL during import? =

To import addresses with the correct geographical coordinates, we call _maps.googleapis.com/maps/api/geocode/_ to request the coordinates based on the address. This works only if you enter a valid Google Maps API key.

== Screenshots ==

1. WP All Import import template
2. WP All Import run import
3. WP All Import import log

== Changelog ==
= 1.1.2 =
* Updated wpcasa.csv sample file

= 1.1.1 =
* Fixed guideline violation
* Updated rapid-addon.php 1.1.4 and fixed code violation

= 1.1.0 =
* Updated rapid-addon.php 1.1.0

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
* Initial release
