=== Adobe XMP for WP ===
Plugin Name: Adobe XMP for WP
Plugin Slug: adobe-xmp-for-wp
Contributors: jsmoriss
Tags: adobe, xmp, xmpmeta, iptc, rdf, xml, lightroom, photoshop, media, library, nextgen, gallery, image, shortcode, function, method, meta data
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Requires At Least: 3.0
Tested Up To: 4.4
Stable Tag: 1.2

Access Adobe XMP / IPTC information from Media Library and NextGEN Gallery images using a Shortcode or PHP Class.

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

The *Adobe XMP for WP* plugin reads image files *progressively* (small chunks at a time) to extract the embeded XMP meta data, instead of reading the whole file into memory as other image management plugins do. The extracted XMP data is also *cached on disk* to improve performance and is refreshed only if/when the original image is modified. You can use the plugin in one of two ways; calling a method from the `$adobeXMP` global **class object** in your template(s), or using an `[xmp]` **shortcode** in your Posts or Pages.

<!--more-->

= Retrieve XMP data as an array =

`
global $adobeXMP;

// $id can be media library image id, or nextgen gallery 
// image id in the form of 'ngg-#'.
$xmp = $adobeXMP->get_xmp( $id );

echo 'Taken by ', $xmp['Creator'], "\n";
`

[You can read more about Adobe XMP for WP's class methods here](http://surniaulula.com/2013/04/09/read-adobe-xmp-xml-in-php/).

= Include a shortcode in your Post or Page =

`
[xmp id="101,ngg-201"]
`

This shortcode prints all the XMP information for Media Library image ID "101" and NextGEN Gallery image ID "201". The XMP information is printed as a definition list `<dl>` with a class name of `xmp_shortcode` that you can style for your needs. Each `<dt>` and `<dd>` element also has a style corresponding to it's title - for example, the "Creator" list element has an `xmp_creator` class name. Here's an example of the definition list HTML:

`
<dl class="xmp_shortcode">
<dt class="xmp_credit">Credit</dt>
<dd class="xmp_credit">Jean-Sebastien Morisset</dd>
<dt class="xmp_source">Source</dt>
<dd class="xmp_source">Underwater Focus</dd>
<dt class="xmp_hierarchical_keywords">Hierarchical Keywords</dt>
<dd class="xmp_hierarchical_keywords">What &gt; Photography &gt; 
	Field of View &gt; Wide-Angle &gt; Fish-Eye</dd>
</dl>
`

The shortcode can also take a few additional arguments:

* `include` (defaults to "all")

Define which XMP elements to include, for example `[xmp id="101" include="Creator,Creator Email"]`. 

Please note that the `include` values are **case sensitive**.

* `exclude` (defaults to none)

Exclude some XMP elements, for example `[xmp id="101" exclude="Creator Email"]` to print all XMP elements, except for the "Creator Email".

* `show_title` (defaults to "yes")

Toggle printing of the XMP element title, for example `[xmp id="101" show_title="no"]` only prints the `<dd>` values, not the `<dt>` titles.

* `not_keyword` (defaults to none)

Exclude a list of (case incensitive) keywords, for example `[xmp id="101" not_keyword="who,what,where"]`. 

To exclude a hierarchical keyword list, use hyphens between the keywords, for example `[xmp id="101" not_keyword="who,what,where,who-people-unknown"]`.

== Installation ==

*Using the WordPress Dashboard*

1. Login to your weblog
1. Go to Plugins
1. Select Add New
1. Search for *Adobe XMP for WP*
1. Select Install
1. Select Install Now
1. Select Activate Plugin

*Manual*

1. Download and unzip the plugin
1. Upload the entire `adobe-xmp-for-wp/` folder to the `wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress

== Frequently Asked Questions ==

== Changelog ==

= v1.2 =

* Files missing variable declaration before appending a value.

