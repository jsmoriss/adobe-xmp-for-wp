=== JSM's Adobe XMP / IPTC for WordPress ===
Plugin Name: JSM's Adobe XMP / IPTC for WordPress
Plugin Slug: adobe-xmp-for-wp
Text Domain: adobe-xmp-for-wp
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Donate Link: https://www.paypal.me/jsmoriss
Assets URI: https://jsmoriss.github.io/adobe-xmp-for-wp/assets/
Tags: adobe, xmp, xmpmeta, iptc, rdf, xml, lightroom, photoshop, media, library, nextgen, gallery, image, shortcode, function, method, meta data
Contributors: jsmoriss
Requires At Least: 3.7
Tested Up To: 4.7
Stable Tag: 1.3.0-1

Read Adobe XMP / IPTC information from Media Library and NextGEN Gallery images, using a Shortcode or PHP Class Method.

== Description ==

Retrieve the following Adobe XMP / IPTC information from images in the WordPress Media Library and NextGEN Galleries:

* Creator Email
* Owner Name
* Creation Date
* Modification Date
* Label
* Credit
* Source
* Headline
* City
* State
* Country
* Country Code
* Location
* Title
* Description
* Creator
* Keywords
* Hierarchical Keywords

The plugin reads image files *progressively* to extract the embeded XMP meta data, instead of reading the whole file into memory as other image management plugins do. The extracted XMP data is also *cached on disk* to improve performance and is refreshed only if/when the original image is modified. You can use the plugin in one of two ways; calling a method from the `$adobeXMP` global **class object** in your template(s) or using an `[xmp]` **shortcode** in your Posts or Pages.

<blockquote>
<p>There are no settings to update or adjust &mdash; simply install and activate the plugin.</p>
</blockquote>

= Retrieve XMP data as an array =

`
global $adobeXMP;

/*
 * Some default class properties can be changed.
 */
$adobeXMP->use_cache = true;	// default
$adobeXMP->max_size = 512000;	// default
$adobeXMP->chunk_size = 65536;	// default

/*
 * $id can be a WordPress Media Library image ID,
 * or NextGEN Gallery image ID in the form of ngg-##.
 */
$image_xmp = $adobeXMP->get_xmp( $id );

echo '<p>Photograph by '.$image_xmp['Creator'].'</p>';
`

= Include a shortcode in your Post or Page =

`
[xmp id="101,ngg-201"]
`

This shortcode prints all the XMP information for Media Library image ID "101" and NextGEN Gallery image ID "201". The XMP information is printed as a definition list `<dl>` with a class name of `xmp_shortcode` that you can style for your needs. Each `<dt>` and `<dd>` element also has a style corresponding to it's title - for example, the "Creator" list element has an `xmp_creator` class name. Here's an example of the definition list HTML:

`
<dl class="xmp_shortcode">
<dt class="xmp_credit">Credit</dt>
<dd class="xmp_credit">JS Morisset</dd>
<dt class="xmp_source">Source</dt>
<dd class="xmp_source">Underwater Focus</dd>
<dt class="xmp_hierarchical_keywords">Hierarchical Keywords</dt>
<dd class="xmp_hierarchical_keywords">What &gt; Photography &gt; 
	Field of View &gt; Wide-Angle &gt; Fish-Eye</dd>
</dl>
`

The shortcode can also take a few additional arguments:

* `include` (defaults to "all")

Define which XMP elements to include, for example:

`[xmp id="101" include="creator,creator email"]`

* `exclude` (defaults to none)

Exclude some XMP elements, for example to print all XMP elements, except for the "creator email":

`[xmp id="101" exclude="creator email"]`

* `show_title` (defaults to "yes")

Include / exclude the `<dt>` definition titles.

`[xmp id="101" show_title="no"]`

* `show_empty` (defaults to "no")

Include / exclude empty `<dd>` definition values.

* `not_keyword` (defaults to none)

Exclude a list of (case incensitive) keywords, for example:

`[xmp id="101" not_keyword="who,what,where"]`

To exclude a hierarchical keyword list, use hyphens between the keywords, for example:

`[xmp id="101" not_keyword="who,what,where,who-people-unknown"]`

== Installation ==

= Automated Install =

1. Go to the wp-admin/ section of your website.
1. Select the *Plugins* menu item.
1. Select the *Add New* sub-menu item.
1. In the *Search* box, enter the plugin name.
1. Click the *Search Plugins* button.
1. Click the *Install Now* link for the plugin.
1. Click the *Activate Plugin* link.

= Semi-Automated Install =

1. Download the plugin archive file.
1. Go to the wp-admin/ section of your website.
1. Select the *Plugins* menu item.
1. Select the *Add New* sub-menu item.
1. Click on *Upload* link (just under the Install Plugins page title).
1. Click the *Browse...* button.
1. Navigate your local folders / directories and choose the zip file you downloaded previously.
1. Click on the *Install Now* button.
1. Click the *Activate Plugin* link.

== Frequently Asked Questions ==

= Frequently Asked Questions =

* None

== Other Notes ==

= Additional Documentation =

* None

== Screenshots ==

== Changelog ==

= Repositories =

* [GitHub](https://github.com/jsmoriss/adobe-xmp-for-wp)
* [WordPress.org](https://wordpress.org/plugins/adobe-xmp-for-wp/developers/)

= Version Numbering Scheme =

Version components: `{major}.{minor}.{bugfix}-{stage}{level}`

* {major} = Major code changes / re-writes or significant feature changes.
* {minor} = New features / options were added or improved.
* {bugfix} = Bugfixes or minor improvements.
* {stage}{level} = dev &lt; a (alpha) &lt; b (beta) &lt; rc (release candidate) &lt; # (production).

Note that the production stage level can be incremented on occasion for simple text revisions and/or translation updates. See [PHP's version_compare()](http://php.net/manual/en/function.version-compare.php) documentation for additional information on "PHP-standardized" version numbering.

= Changelog / Release Notes =

**Version 1.3.0-1 (2016/12/16)**

* *New Features*
	* None
* *Improvements*
	* Added a new shortcode "show_empty" attribute.
	* The shortcode "include" and "exclude" attribute values are now case insensitive.
* *Bugfixes*
	* None
* *Developer Notes*
	* Refactored much of the plugin code.

**Version 1.2.1-1 (2016/08/02)**

* *New Features*
	* None
* *Improvements*
	* Maintenance release.
* *Bugfixes*
	* None
* *Developer Notes*
	* None

== Upgrade Notice ==

= 1.3.0-1 =

(2016/12/16) Added a new shortcode "show_empty" attribute. The shortcode "include" and "exclude" attribute values are now case insensitive. Refactored much of the plugin code.

