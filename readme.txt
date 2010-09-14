=== MobilePress ===
Contributors: mattgeri, tylerreed, voyagerfan5761
Tags: mobile, iphone, android, mobilepress, cell phone, cellphone, handset, nokia, motorola, att, sprint, verizon, blackberry, palm, windows ce, opera, operamini, opera mini, google, yahoo, plugin, comments, posts
Requires at least: 2.3
Tested up to: 2.8.6
Stable tag: 1.1.5

The MobilePress plugin renders your WordPress blog on mobile handsets, with customized mobile themes, analytics and ad serving.

== Description ==

MobilePress is a WordPress plugin that will render your WordPress blog on mobile handsets, with the ability to use customized themes. Built into MobilePress is the ability to track your mobile sites analytics and serve ads with [Aduity.com](http://aduity.com).

Integration with [Aduity.com](http://aduity.com) enables you to serve ads from some of the biggest mobile ad networks such as Admob, Quattro Wireless, Buzzcity and InMobi. You can
also serve you own managed ads. You will also be able to track your visitors every move with Aduity analytics.

The plugin allows WordPress theme developers to create custom mobile themes for MobilePress enabled blogs. Theme designers can create specific iPhone themes or generic mobile themes for other mobile devices.

MobilePress is SEO enabled and detects Google, Yahoo and MSN mobile search bots. These mobile search engine bots will see the mobile version of your blog and get your mobile site indexed in the mobile search results.

For more information and a full set of docs, please visit the official MobilePress website at [MobilePress.co.za](http://mobilepress.co.za/)

== Installation ==

Upgrading or installing MobilePress for the first time is a very simple process. There are just a few steps below which you will easily be able to follow. Lets go!

= Important Note About Auto Upgrading =

If you are using the WordPress plugin auto updater you will need to back up your themes folder (if you have made custom changes to the default MobilePress themes!). If you have not made changes to the themes or if you have not uploaded your own custom themes, you do not have to worry.

The reason for backing up your themes is when the WordPress auto upgrader upgrades the plugins, it removes all the files in the MobilePress directory, thus removing any custom changes you have made to the MobilePress themes. It will also remove any custom themes you have uploaded.

= For Upgrading Manually =

If you are upgrading MobilePress, delete all your old MobilePress files from the mobilepress plugin directory. If you have a custom theme or have modified the default theme, you do not have to delete the `/themes` folder.

= Installation Steps =

1. Upload the mobilepress directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the ‘Plugins’ menu in WordPress
1. If you are using a caching plugin (e.g. wp-supercache), clear your cache now.
1. If you plan on using custom themes, or wish to specify a custom title/description for your mobile blog, you can set these in the MobilePress admin panel.

That’s all folks! You’re now good to go!

== Config & Settings ==

Once successfully installed, you will be presented with a few options.

= Blog Title =

Changing this will replace the title of your blog when being viewed on a mobile device.  So, instead of "My cool blog", you could replace it with something like "My mobile blog".  This will not affect your site when being viewed on a regular web browser.

= Blog Description =

This is the same as changing your blog title, but will modify your blog's description instead.

= Force Mobile Site =

This option will force your site to be rendered as mobile, regardless of where it is being viewed from. Regular web browsers will see the mobile version.

== Frequently Asked Questions ==

FAQ available at [MobilePress.co.za](http://mobilepress.co.za/support)


== Screenshots ==

Screenshots are currently available at [MobilePress.co.za](http://mobilepress.co.za/)

== Changelog ==

= Version 1.1.5 (2010-07-20) =
* Updated the Aduity libraries to support the new mediation layer from Aduity.com

= Version 1.1.4 (2009-12-17) =
* Removed the XML MIME type as it was throwing way to many errors on MobilePress blogs
* Added CSS image resizing code which correctly resizes all images larger than 300px wide and does not resize images less than 300px wide
* Cleaned up some default theme code

= Version 1.1.3 (2009-12-09) =
* Fixed a bug whereby the title and description that was set for the mobile blog was not being switched with the title and description set for the WordPress blog. Thus, on the mobile blog the WordPress blogs title and description was being displayed.

= Version 1.1.2 (2009-12-08) =
* Fixed some major errors in the default themes. Comments were not displaying and users were unable to post comments on default theme. On the iPhone theme, single posts were not viewable. 1.1.2 is a critical upgrade.

= Version 1.1.1 (2009-12-07) =
* Moved the folder for custom themes to ‘/mobile-themes’ in ‘/wp-content’. Also added an option to change this folder if you want. This solves the issue of themes being overwritten when the plugin updates
* Cleaned up the default themes HTML code and CSS. Now scores a 5/5 on ready.mobi
* Added an option to the ‘Ads & Analytics’ page which allows you to test ads in a web browser using ‘debug’ mode from Aduity.com
* Removed the CSS image resizing code. Next MobilePress release 1.2 will include a proper image resizing script

= Version 1.1 (2009-11-24) =
* Full ad and analytics integration with Aduity.com
* Added new code libraries for Aduity integration
* Removed confusing options such as “treat device as web browser”
* Modified mobile detection script. Optimized and also removed eregi references. Added detection for more user-agents (Thanks StarTech)
* Adding new structural code to easily load “views” for the admin area
* General cleaning up of code

= Version 1.0.4 (2009-06-15) =
* Reworked MobilePress file structure
* Added DocBlock to the plugin classes and functions
* Added in a delete/uninstall hook
* Added post paging to the Default and iPhone themes
* Removed wp_head() and wp_foot() from mobile themes
* Changed theme default background color to a lighter color so that Disqus comments show
* Cleaned up the options interface to make it integrate better with the WordPress interface
* Fixed a bug that was created by WordPress 2.8 which duplicated the options menu
* Added a MobilePress Icon to the WordPress Admin Sidebar
* Updated the readme file to reflect changes to the new website

= Version 1.0.3 (2008-11-20) =
* Added pages support to the default theme and iPhone theme
* Added Google and Yahoo mobile bot detection
* Added a uninstall.php file for WordPress 2.7+
* Fixed conflicting plugin function errors and restructured a lot of the codebase (thanks darb)
* Added new user agent strings for Palm and Blackberry handsets
* Fixed CheckTable() function bug which resulted in plugin not being updated automatically and also added in an upgrade fix

= Version 1.0.2 (2008-10-27) =
* Replaced $_GET reference in search.php

= Version 1.0.1 (2008-10-24) =
* Theme Modification (Comments link now goes to comments page)
* HTC added to list of mobile handsets
* Thanks to Denham Coote for contributing