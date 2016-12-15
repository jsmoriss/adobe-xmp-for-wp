<h1>JSM&#039;s Adobe XMP / IPTC for WordPress</h1>

<table>
<tr><th align="right" valign="top" nowrap>Plugin Name</th><td>JSM&#039;s Adobe XMP / IPTC for WordPress</td></tr>
<tr><th align="right" valign="top" nowrap>Summary</th><td>Read Adobe XMP / IPTC information, using a Shortcode or PHP Class, from Media Library and NextGEN Gallery images.</td></tr>
<tr><th align="right" valign="top" nowrap>Stable Version</th><td>1.3.0-1</td></tr>
<tr><th align="right" valign="top" nowrap>Requires At Least</th><td>WordPress 3.7</td></tr>
<tr><th align="right" valign="top" nowrap>Tested Up To</th><td>WordPress 4.7</td></tr>
<tr><th align="right" valign="top" nowrap>Contributors</th><td>jsmoriss</td></tr>
<tr><th align="right" valign="top" nowrap>Website URL</th><td><a href="https://www.paypal.me/jsmoriss">https://www.paypal.me/jsmoriss</a></td></tr>
<tr><th align="right" valign="top" nowrap>License</th><td><a href="https://www.gnu.org/licenses/gpl.txt">GPLv3</a></td></tr>
<tr><th align="right" valign="top" nowrap>Tags / Keywords</th><td>adobe, xmp, xmpmeta, iptc, rdf, xml, lightroom, photoshop, media, library, nextgen, gallery, image, shortcode, function, method, meta data</td></tr>
</table>

<h2>Description</h2>

<p>Retrieve the following Adobe XMP / IPTC information from images in the WordPress Media Library and NextGEN Galleries:</p>

<ul>
<li>Creator Email</li>
<li>Owner Name</li>
<li>Creation Date</li>
<li>Modification Date</li>
<li>Label</li>
<li>Credit</li>
<li>Source</li>
<li>Headline</li>
<li>City</li>
<li>State</li>
<li>Country</li>
<li>Country Code</li>
<li>Location</li>
<li>Title</li>
<li>Description</li>
<li>Creator</li>
<li>Keywords</li>
<li>Hierarchical Keywords</li>
</ul>

<p>The plugin reads image files <em>progressively</em> (small chunks at a time) to extract the embeded XMP meta data, instead of reading the whole file into memory as other image management plugins do. The extracted XMP data is also <em>cached on disk</em> to improve performance and is refreshed only if/when the original image is modified. You can use the plugin in one of two ways; calling a method from the <code>$adobeXMP</code> global <strong>class object</strong> in your template(s) or using an <code>[xmp]</code> <strong>shortcode</strong> in your Posts or Pages.</p>

<blockquote>
<p>There are no settings to update or adjust &mdash; simply install and activate the plugin.</p>
</blockquote>

<h4>Retrieve XMP data as an array</h4>

<pre><code>global $adobeXMP;

/*
 * Some default class properties can be changed.
 */
$adobeXMP-&gt;use_cache = true;    // default
$adobeXMP-&gt;max_size = 512000;   // default
$adobeXMP-&gt;chunk_size = 65536;  // default

/*
 * $id can be a WordPress Media Library image ID,
 * or NextGEN Gallery image ID in the form of ngg-##.
 */
$image_xmp = $adobeXMP-&gt;get_xmp( $id );

echo '&lt;p&gt;Photograph by '.$image_xmp['Creator'].'&lt;/p&gt;';
</code></pre>

<h4>Include a shortcode in your Post or Page</h4>

<pre><code>[xmp id="101,ngg-201"]
</code></pre>

<p>This shortcode prints all the XMP information for Media Library image ID "101" and NextGEN Gallery image ID "201". The XMP information is printed as a definition list <code>&lt;dl&gt;</code> with a class name of <code>xmp_shortcode</code> that you can style for your needs. Each <code>&lt;dt&gt;</code> and <code>&lt;dd&gt;</code> element also has a style corresponding to it's title - for example, the "Creator" list element has an <code>xmp_creator</code> class name. Here's an example of the definition list HTML:</p>

<pre><code>&lt;dl class="xmp_shortcode"&gt;
&lt;dt class="xmp_credit"&gt;Credit&lt;/dt&gt;
&lt;dd class="xmp_credit"&gt;JS Morisset&lt;/dd&gt;
&lt;dt class="xmp_source"&gt;Source&lt;/dt&gt;
&lt;dd class="xmp_source"&gt;Underwater Focus&lt;/dd&gt;
&lt;dt class="xmp_hierarchical_keywords"&gt;Hierarchical Keywords&lt;/dt&gt;
&lt;dd class="xmp_hierarchical_keywords"&gt;What &amp;gt; Photography &amp;gt; 
    Field of View &amp;gt; Wide-Angle &amp;gt; Fish-Eye&lt;/dd&gt;
&lt;/dl&gt;
</code></pre>

<p>The shortcode can also take a few additional arguments:</p>

<ul>
<li><code>include</code> (defaults to "all")</li>
</ul>

<p>Define which XMP elements to include, for example:</p>

<pre><code>[xmp id="101" include="creator,creator email"]
</code></pre>

<ul>
<li><code>exclude</code> (defaults to none)</li>
</ul>

<p>Exclude some XMP elements, for example to print all XMP elements, except for the "creator email":</p>

<pre><code>[xmp id="101" exclude="creator email"]
</code></pre>

<ul>
<li><code>show_title</code> (defaults to "yes")</li>
</ul>

<p>Include / exclude the <code>&lt;dt&gt;</code> definition titles.</p>

<pre><code>[xmp id="101" show_title="no"]
</code></pre>

<ul>
<li><code>show_empty</code> (defaults to "no")</li>
</ul>

<p>Include / exclude empty <code>&lt;dd&gt;</code> definition values.</p>

<ul>
<li><code>not_keyword</code> (defaults to none)</li>
</ul>

<p>Exclude a list of (case incensitive) keywords, for example:</p>

<pre><code>[xmp id="101" not_keyword="who,what,where"]
</code></pre>

<p>To exclude a hierarchical keyword list, use hyphens between the keywords, for example:</p>

<pre><code>[xmp id="101" not_keyword="who,what,where,who-people-unknown"]
</code></pre>


<h2>Installation</h2>

<h4>Automated Install</h4>

<ol>
<li>Go to the wp-admin/ section of your website.</li>
<li>Select the <em>Plugins</em> menu item.</li>
<li>Select the <em>Add New</em> sub-menu item.</li>
<li>In the <em>Search</em> box, enter the plugin name.</li>
<li>Click the <em>Search Plugins</em> button.</li>
<li>Click the <em>Install Now</em> link for the plugin.</li>
<li>Click the <em>Activate Plugin</em> link.</li>
</ol>

<h4>Semi-Automated Install</h4>

<ol>
<li>Download the plugin archive file.</li>
<li>Go to the wp-admin/ section of your website.</li>
<li>Select the <em>Plugins</em> menu item.</li>
<li>Select the <em>Add New</em> sub-menu item.</li>
<li>Click on <em>Upload</em> link (just under the Install Plugins page title).</li>
<li>Click the <em>Browse...</em> button.</li>
<li>Navigate your local folders / directories and choose the zip file you downloaded previously.</li>
<li>Click on the <em>Install Now</em> button.</li>
<li>Click the <em>Activate Plugin</em> link.</li>
</ol>


<h2>Frequently Asked Questions</h2>

<h4>Frequently Asked Questions</h4>

<ul>
<li>None</li>
</ul>


<h2>Other Notes</h2>

<h3>Other Notes</h3>
<h4>Additional Documentation</h4>

<ul>
<li>None</li>
</ul>

