=== JSM's Adobe XMP / IPTC for WordPress ===
Plugin Name: JSM's Adobe XMP / IPTC for WordPress
Plugin Slug: adobe-xmp-for-wp
Text Domain: adobe-xmp-for-wp
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://jsmoriss.github.io/adobe-xmp-for-wp/assets/
Tags: adobe, xmp, xmpmeta, iptc, rdf, xml, lightroom, photoshop, media, library, nextgen, gallery, image, shortcode, function, method, meta data
Contributors: jsmoriss
Requires PHP: 5.6
Requires At Least: 4.2
Tested Up To: 5.4.1
Stable Tag: 1.3.3

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

The extracted XMP / IPTC data is *cached on disk* to improve performance and is refreshed if / when the original image is modified.

You can use the plugin in one of two ways; calling a method in your theme template(s) or using the `[xmp]` shortcode in your content.

There are no plugin settings &mdash; simply *install* and *activate* the plugin.

= Retrieve XMP Data as an Array =

`
$adobeXMP =& adobeXMPforWP::get_instance();

/**
 * Some default class properties can be modified.
 */
$adobeXMP->use_cache = true;	// default
$adobeXMP->max_size = 512000;	// default
$adobeXMP->chunk_size = 65536;	// default

/**
 * The $id can be a WordPress Media Library image ID,
 * or NextGEN Gallery image ID in the form of ngg-##.
 */
$image_xmp = $adobeXMP->get_xmp( $id );

echo '<p>Photograph by '.$image_xmp['Creator'].'</p>';
`

You can also hook the 'adobe_xmp_cache_dir' filter to modify the default cache directory.

= Include a Shortcode in your Post or Page =

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

1. Download the plugin ZIP file.
1. Go to the wp-admin/ section of your website.
1. Select the *Plugins* menu item.
1. Select the *Add New* sub-menu item.
1. Click on *Upload* link (just under the Install Plugins page title).
1. Click the *Browse...* button.
1. Navigate your local folders / directories and choose the ZIP file you downloaded previously.
1. Click on the *Install Now* button.
1. Click the *Activate Plugin* link.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Repositories</h3>

* [GitHub](https://jsmoriss.github.io/adobe-xmp-for-wp/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/adobe-xmp-for-wp/)

<h3>Changelog / Release Notes</h3>

**Version 1.3.3 (2019/04/07)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Maintenance release - minor code formatting changes.

== Upgrade Notice ==

= 1.3.3 =

(2019/04/07) Maintenance release - minor code formatting changes.

