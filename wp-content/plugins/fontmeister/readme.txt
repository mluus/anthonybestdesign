=== FontMeister - The Font Management Plugin ===
Contributors: sayontan
Donate link: http://aquoid.com/news/plugins/fontmeister/
Tags: Google Web Fonts, Typekit, Fontdeck, Font Manager, Font Squirrel
Requires at least: WP 3.1
Tested up to: WP 3.5
Stable tag: trunk
License: GPL v3 or later

A one stop plugin for all your fonts, FontMeister pulls fonts from Google Web Fonts, Typekit, Fontdeck and Font Squirrel for you to use in any theme.

== Description ==

FontMeister lets you preview, add and use fonts from the most common sources of fonts on the web. The plugin supports fonts from:

*   <a href='http://www.google.com/webfonts'>Google Web Fonts</a>
*   <a href='http://typekit.com'>Typekit</a>
*   <a href='http://fontdeck.com'>Fontdeck</a>
*   <a href='http://fontsquirrel.com'>Font Squirrel</a>

Using FontMeister is easy. Enter your details for the individual providers in the "Font Sources" section of the plugin. Then go to the "Fonts"
section, and pick the fonts you want to use. You can add your own selectors to the fonts, and the plugin will include them on your site.

= More Sources =

Work is underway to support the following:

*	<a href='http://fonts.com/'>Fonts.com</a>
*	Manually uploaded @font-face kits

= Bonus Features =

FontMeister has been written keeping themes in mind. Currently fonts selected in FontMeister are added automatically to font
selection drop-downs in the Suffusion theme. If you are a theme author and you wish to have the capability to do the same for
your theme, contact the plugin author for instructions.

== Installation ==

You can install the plugin through the WordPress installer under <strong>Plugins &rarr; Add New</strong> by searching for it,
or by uploading the file downloaded from here. Alternatively you can download the file from here, unzip it and move the unzipped
contents to the <code>wp-content/plugins</code> folder of your WordPress installation. You will then be able to activate the plugin.

== Screenshots ==

1.	A menu is created for FontMeister with 2 pages - one for setting up your sources and the other for picking the fonts.
2.	Enter the details for the sources you want to pull fonts from.
3.	See the list of fonts available from each source in the Fonts section.
4.  Preview individual fonts. Vary the weights and the base font size to pick the right mix for yoru site.
5.  Fine tune your selections by picking a smaller set of variants or subsets, and assign selectors to each font.

== Frequently Asked Questions ==

= What about other font providers? =

Support is planned for Fonts.com and other providers. If you have specific suggestions do post them.

= Can the fonts defined in FontMeister be added to the font list in my theme? =

FontMeister provides a function <code>add_more_fonts</code> that can be used by the theme authors to augment their
list. Alternatively they can define their own hook and provide it to me and I can add the support natively in the plugin.

= Are there any known issues? =

Yes, for Font Squirrel. In some cases while downloading a font from Font Squirrel the resultant zip file is empty (0KB). In most cases this
happens for larger zip archives. The issue resolves itself if a download is reattempted after an interval of time. Alternatively you can manually
download the zip file and unzip the contents to the "fontmeister" directory in your "uploads" folder.

== Changelog ==

= 1.04 =

*   Fixed a bug with Font Squirrel fonts that had only one variant. The CSS selector for them was not showing up.

= 1.03 =

*   Added support for Font Squirrel.
*	Made a change to the TinyMCE module so as to append to the CSS if it already exists, instead of overwriting it.

= 1.02 =

*   Added TinyMCE support - fonts now show up in the TinyMCE font dropdown.

= 1.01 =

*   Fixed a minor problem that was affecting some users, preventing them from adding new fonts.

= 1.00 =

*   New version created.

== Upgrade Notice ==

No upgrade notices at this point.