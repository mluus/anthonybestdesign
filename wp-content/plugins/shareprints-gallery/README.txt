=== SharePrints Gallery ===
Author URI: http://freakplugins.com
Plugin URI: http://freakplugins.com/shareprints/
Contributors: jr
Tags: images, galleries
Requires at least: 3.6
Tested up to: 4.0

Stable tag: 1.0.4.5

License: GPLv3 or later

SharePrints is a total gallery solution for WordPress that makes it easy for anyone to create beautiful responsive galleries that display perfectly across all devices and screen sizes.

== Description ==

SharePrints is a total gallery solution for WordPress that makes it easy for anyone to create beautiful responsive galleries that display perfectly across all devices and screen sizes.

SharePrints is designed to look and feel like a natural part of WordPress; there’s no setup required and it works with the images you’ve already uploaded to your site. Highlights include the ability to create and manage galleries directly from a page or post edit screen, and an advanced lightbox that provides a beautiful and compelling way for visitors to interact with your content.

== Installation ==

1. Upload 'shareprints-gallery' to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Enter your license key and start creating galleries!

== Frequently Asked Questions ==

= What version of WordPress is required to use SharePrints? =

SharePrints requires at least WordPress version 3.6 or higher.

= Do I have to re-upload my images to use SharePrints? =

SharePrints works with existing images in your Media Library. You don’t have to re-upload them. SharePrints automatically resamples your images and creates the appropriate size thumbnails as needed.

= Are my images too big or too small to use with SharePrints? =

SharePrints works best with images that are uploaded in their original resolution and file size, but it will work with existing images of any size.

= Which Web browsers do you support? =

We support the current and prior major release of Chrome, Firefox, Internet Explorer and Safari on a rolling basis. Each time a new version of these browsers is released, we’ll begin supporting the update and stop supporting the third-oldest version.

For mobile and tablet devices we support the current and prior major release of Safari for iOS and Chrome for Android.

= Will SharePrints slow down my website/blog? =

SharePrints was built with efficiency and speed in mind. SharePrints will not load anything on pages/posts that do not include galleries. On pages/posts with galleries, SharePrints only loads the smallest file sizes necessary to display each gallery.

= Can I upload images in bulk to SharePrints galleries? =

Yes, simply hold the Shift key and you can select multiple images to be inserted into a gallery from the WordPress Media Library.

== Changelog ==

= Version 1.0.4.5 =

* Tweak: Added a new tinyMCE js file for users running wordpress 4.0 and higher.
* Fix: Resolved issue where tinyMCE plugins that load before SharePrints were being removed from the $plugin_array.

= Version 1.0.4.4 =

* Fix: Fixed bug that was causing image size selection not to save correctly.
* Fix: Added missing translation strings.
* Fix: Fixed bug that caused "uploaded to" in media library to display incorrect location.
* Fix: CSS calc error that prevented galleries from floating alongside eachother.

= Version 1.0.4.3 =

* New: Retina versions of images now load on devices and browsers that support the new <img srcset> tags. ( Coming to iPhone/iPad in iOS 8 )
* Fix: Bootstrap assets were namespaced to avoid conflicts with visual composer and similar plugins/themes.
* Fix: Fixed a bug that was causing some sizes of squares galleries to break when certain hover effects are used.
* Fix: Sliders now better allow for portrait and landscape images in the same slider. Slider height now adjusts for each image.
* Change: Gallery markup has changed - ul.shareprints > li.shareprints_li > a.shareprints_thumb to div.shareprints > div.shareprints_li > div.shareprints_thumb 
* Tweak: Slider Galleries now have greatly improved responsive design. Control arrows and thumbnails now resize correctly with the gallery. 
* Tweak: Greatly improved loading performance for all gallery types. Galleries now load 2-3 times faster than before.
* Tweak: Updated the SlideScroll styles and control calculations.

= Version 1.0.4.2 =

* Fix: Resolved a conflict that occurs between Next-Gen and SharePrints.
* New: Added How-To Videos in the SharePrints Admin Settings Panel

= Version 1.0.4.1 =

* Tweak: Changed the media button text from Add Gallery to SharePrints

= Version 1.0.4 =

* Tweak: Updates to the SharePrints Editor.
* Fix: Minor Bug Fixes