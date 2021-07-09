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
Requires PHP: 7.0
Requires At Least: 5.0
Tested Up To: 5.8
Stable Tag: 1.6.0

Provides Adobe XMP / IPTC information from Media Library or NextGEN Gallery images using a shortcode or PHP class method.

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
* Rights
* Keywords
* Hierarchical Keywords

The extracted XMP / IPTC data is cached on disk to improve performance and is refreshed if / when the original image is modified.

You can use the plugin in one of two ways; calling a method in your theme template(s) or using the `[xmp]` shortcode in your content.

There are no plugin settings - simply *install* and *activate* the plugin.

= Need a Boost to your Social and Search Ranking? =

Check out [the WPSSO Core plugin](https://wordpress.org/plugins/wpsso/) to rank higher and improve click-through-rates by presenting your content at its best on social sites and in search results - no matter how URLs are shared, re-shared, messaged, posted, embedded, or crawled.

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

= How do I retrieve XMP data as an array? =

<pre>$adobeXMP =& adobeXMPforWP::get_instance();

$xmp = $adobeXMP-&gt;get_xmp( $image_id );

echo '&lt;p&gt;Photograph by ' . $xmp[ 'Creator' ] . '&lt;/p&gt;';</pre>

= How do I include a shortcode in a post or page? =

<pre>[xmp id="101,ngg-201"]</pre>

This shortcode prints all the XMP information for Media Library image ID "101" and NextGEN Gallery image ID "201". The XMP information is printed as a definition list &lt;dl&gt; with a class name of "xmp_shortcode" that you can style for your needs. Each &lt;dt&gt; and &lt;dd&gt; element also has a style corresponding to it's title - for example, the "Creator" list element has an "xmp_creator" class name. Here's an example of the definition list HTML:

<pre>&lt;dl class="xmp_shortcode"&gt;
&lt;dt class="xmp_credit"&gt;Credit&lt;/dt&gt;
&lt;dd class="xmp_credit"&gt;JS Morisset&lt;/dd&gt;
&lt;dt class="xmp_source"&gt;Source&lt;/dt&gt;
&lt;dd class="xmp_source"&gt;Underwater Focus&lt;/dd&gt;
&lt;dt class="xmp_hierarchical_keywords"&gt;Hierarchical Keywords&lt;/dt&gt;
&lt;dd class="xmp_hierarchical_keywords"&gt;What &gt; Photography &gt; Field of View &gt; Wide-Angle &gt; Fish-Eye&lt;/dd&gt;
&lt;/dl&gt;</pre>

<p>The shortcode can also take a few additional arguments:</p>

* <code>include</code> (defaults to "all")

<p>Define which XMP elements to include, for example:</p>

<pre>[xmp id="101" include="creator,creator email"]</pre>

* <code>exclude</code> (defaults to none)

<p>Exclude some XMP elements, for example to print all XMP elements, except for the "creator email":</p>

<pre>[xmp id="101" exclude="creator email"]</pre>

* <code>show_title</code> (defaults to "yes")

<p>Include / exclude the &lt;dt&gt; definition titles.</p>

<pre>[xmp id="101" show_title="no"]</pre>

* <code>show_empty</code> (defaults to "no")

<p>Include / exclude empty &lt;dd&gt; definition values.</p>

* <code>not_keyword</code> (defaults to none)

<p>Exclude a list of (case incensitive) keywords, for example:</p>

<pre>[xmp id="101" not_keyword="who,what,where"]</pre>

<p>To exclude a hierarchical keyword list, use hyphens between the keywords, for example:</p>

<pre>[xmp id="101" not_keyword="who,what,where,who-people-unknown"]</pre>

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

**Version 1.6.0 (2020/11/21)**

* **New Features**
	* None.
* **Improvements**
	* Added support for the copyright &lt;dc:rights&gt;&lt;/dc:rights&gt; tag.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.

== Upgrade Notice ==

= 1.6.0 =

(2020/11/21) Added support for the copyright &lt;dc:rights&gt;&lt;/dc:rights&gt; tag.

