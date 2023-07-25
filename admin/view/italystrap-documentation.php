<div class="wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron">
                    <h1 class='text-center'><?php _e('Documentation', 'italystrap') ?></h1>
                </div>
                <h3>How to use Breadcrumbs</h3>
<!-- Start text -->
By default the breadcrumbs is developed with <a href="http://schema.org/BreadcrumbList" target="_blank">Schema.org markups</a> for Google Rich snippets and <a href="http://getbootstrap.com/components/#breadcrumbs" target="_blank">Bootstrap style</a>.

No problem if you don't have Bootstrap in your site, this class accepts your personal customization for your purpose or you can put this code in your style css:

<pre>
    <code>.breadcrumb{padding:8px 15px;margin-bottom:20px;list-style:none;background-color:#f5f5f5;border-radius:4px}.breadcrumb&gt;li{display:inline-block}.breadcrumb&gt;li+li:before{content:"/\00a0";padding:0 5px;color:#ccc}.breadcrumb&gt;.active{color:#777}</code>
</pre>

For add ItalyStrap Breadcrumbs put the code below in your theme files (single.php and page.php, optional in archive.php, 404.php <span id="result_box" class="short_text" lang="en"><span class="hps">or</span> <span class="hps">where you want to</span> <span class="hps">show it</span></span>)
<pre>&lt;?php if ( class_exists('ItalyStrapBreadcrumbs') ) {
    
        new ItalyStrapBreadcrumbs();
    
    } ?&gt;</pre>
This will show breadcrumbs like this (if you have Bootstrap css):
<ol class="breadcrumb">
    <li><a href="#">Blog info name</a></li>
    <li><a title="Senza categoria" href="#">Category</a></li>
    <li>Breadcrumbs</li>
</ol>
If you don't have Bootstrap CSS the breadcrumbs will show as a list (you will have to develop your own style, remember, add separator in CSS style, <a href="http://getbootstrap.com/components/#breadcrumbs" target="_blank">read this</a>):
<ol>
    <li><a href="#">Blog info name</a></li>
    <li><a href="#">Category</a></li>
    <li class="active">Breadcrumbs</li>
</ol>
This is the HTML code for basic breadcrumbs:
<div class="highlight">
<pre><code class="language-html" data-lang="html"><span class="nt">&lt;ol</span> <span class="na">class=</span><span class="s">"breadcrumb"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;li&gt;&lt;a</span> <span class="na">href=</span><span class="s">"#"</span><span class="nt">&gt;</span>Blog info name<span class="nt">&lt;/a&gt;&lt;/li&gt;</span>
  <span class="nt">&lt;li&gt;&lt;a</span> <span class="na">href=</span><span class="s">"#"</span><span class="nt">&gt;</span>Library<span class="nt">&lt;/a&gt;&lt;/li&gt;</span>
  <span class="nt">&lt;li</span> <span class="na">class=</span><span class="s">"active"</span><span class="nt">&gt;</span>Data<span class="nt">&lt;/li&gt;</span>
<span class="nt">&lt;/ol&gt;</span></code></pre>
</div>
And this is code with Schema.org markup:
<pre>&lt;ol itemtype="http://schema.org/BreadcrumbList" itemscope="" class="breadcrumb"&gt;
    &lt;li itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement"&gt;
        &lt;a title="ItalyStrap" href="http://192.168.1.10/italystrap" itemprop="item"&gt;
            &lt;span itemprop="name"&gt;Blog info name&lt;/span&gt;
            &lt;meta content="ItalyStrap" itemprop="name"&gt;
        &lt;/a&gt;
        &lt;meta content="1" itemprop="position"&gt;
    &lt;/li&gt;
    &lt;li itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement"&gt;
        &lt;a title="Senza categoria" href="http://192.168.1.10/italystrap/category/senza-categoria" itemprop="item"&gt;
            &lt;span itemprop="name"&gt;Senza categoria&lt;/span&gt;
        &lt;/a&gt;
        &lt;meta content="2" itemprop="position"&gt;
    &lt;/li&gt;
    &lt;li itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement"&gt;
        &lt;span itemprop="name"&gt;Breadcrumbs&lt;/span&gt;
        &lt;meta content="3" itemprop="position"&gt;
    &lt;/li&gt;
&lt;/ol&gt;</pre>
The breadcrumbs class accepts an optional array for your personal customizations with this default parameters:
<pre>$defaults = array(
 'home' =&gt; $bloginfo_name,
 'open_wrapper' =&gt; '&lt;ol class="breadcrumb"  itemscope itemtype="http://schema.org/BreadcrumbList"&gt;',
 'closed_wrapper' =&gt; '&lt;/ol&gt;',
 'before_element' =&gt; '&lt;li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"&gt;',
 'before_element_active'    =&gt;  '&lt;li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"&gt;',
 'after_element' =&gt; '&lt;/li&gt;',
 'wrapper_name' =&gt; '&lt;span itemprop="name"&gt;',
 'close_wrapper_name' =&gt; '&lt;/span&gt;'
 );</pre>
<strong>Example to show Bootstrap Glyphicon instead of Home (If you have it):</strong>
<pre>&lt;?php if ( class_exists('ItalyStrapBreadcrumbs') ) {
    
        $defaults = array(
            'home'    =&gt;  '&lt;span class="glyphicon glyphicon-home" aria-hidden="true"&gt;&lt;/span&gt;'
        );
    
        new ItalyStrapBreadcrumbs( $defaults );
    
    }?&gt;</pre>
It will show breadcrumbs like this:
<ol class="breadcrumb">
    <li><a href="#"><i class="glyphicon glyphicon-home"></i>­</a></li>
    <li><a title="Senza categoria" href="#">Category</a></li>
    <li>Breadcrumbs</li>
</ol>

<!-- Gallery Carouse documentation -->

<h3>How Bootstrap Carousel works with Gallery Shortcode</h3>
(This functionality is forked from <a href="https://github.com/andrezrv/agnosia-bootstrap-carousel" target="_blank">Agnosa Bootstrap Carousel</a>)

Add attribute <code>type="carousel"</code> at gallery shortcode, this will show Bootstrap Carousel based on the selected images and their titles and descriptions, otherwise It will show standard WordPress Gallery.

For more informations about WordPress Gallery see these links: <a href="http://codex.WordPress.org/The_WordPress_Gallery" target="_blank">http://codex.WordPress.org/The_WordPress_Gallery</a> <a href="http://codex.WordPress.org/Gallery_Shortcode" target="_blank">http://codex.WordPress.org/Gallery_Shortcode</a>
<div class="alert alert-warning">This plugin assumes either your theme includes the necessary Bootstrap javascript and CSS files to display the carousel properly, or that you have included those files on your own. It will not include the files for you, so if they are not present, the carousel will not work and you will only obtain its bare HTML.</div>
The Carousel adds Schema.org markup for ImageObject and exifData.

If you want show different image size in different device (tablet or phone) you can add sizetablet and/or sizephone attribute to shortcode, see below for more informations.
<h4>Example:</h4>
[gallery type="carousel" ids="1,2,3,4,5"]

You can add a <code>type</code> parameter directly from the Insert Media screen on the gallery tab.

You can also select an image size only for standard carousel, for responsive size add it directly in the code, see below or see screenshot n°5 in <a href="https://WordPress.org/plugins/italystrap/screenshots/" target="_blank">plugin screenshot page</a>.
<h4>Optional attributes:</h4>
<ul class="task-list">
    <li><code>name</code>: any name. String will be sanitize to be used as an HTML ID. Recommended when you want to have more than one carousel in the same page. Default: <em>italystrap-bootstrap-carousel</em>.
Example:[gallery type="carousel" ids="61,60,59" name="myCarousel"]</li>
    <li><code>indicators</code>: indicators position. Accepted values: <em>before-inner</em>, <em>after-inner</em>, <em>after-control</em>, <em>false</em> (hides indicators). Default: <em>before-inner</em>.
Example:[gallery type="carousel" ids="61,60,59" indicators="after-inner"]</li>
    <li><code>width</code>: carousel container width, in <code>px</code> or <code>%</code>. Default: not set.
Example:[gallery type="carousel" ids="61,60,59" width="800px"]</li>
    <li><code>height</code>: carousel item height, in <code>px</code> or <code>%</code>. Default: not set.
Example:[gallery type="carousel" ids="61,60,59" height="400px"]</li>
    <li><code>titletag</code>: define HTML tag for image title. Default: <em>h4</em>.
Example:[gallery type="carousel" ids="61,60,59" titletag="h2"]</li>
    <li><code>wpautop</code>: auto-format text. Default: <em>true</em>.
Example:[gallery type="carousel" ids="61,60,59" wpautop="false"]</li>
    <li><code>title</code>: show or hide image title. Set <em>false</em> to hide. Default: <em>true</em>.
Example:[gallery type="carousel" ids="61,60,59" title="false"]</li>
    <li><code>text</code>: show or hide image text. Set <em>false</em> to hide. Default: <em>true</em>.
Example:[gallery type="carousel" ids="61,60,59" text="false"]</li>
    <li><code>containerclass</code>: extra class for carousel container. Default: not set.
Example:[gallery type="carousel" ids="61,60,59" containerclass="container"]</li>
    <li><code>itemclass</code>: extra class for carousel item. Default: not set.
Example:[gallery type="carousel" ids="61,60,59" itemclass="container"]</li>
    <li><code>captionclass</code>: extra class for item caption. Default: not set.
Example:[gallery type="carousel" ids="61,60,59" captionclass="container"]</li>
    <li><code>control</code>: control arrows display. Accepted values: <em>true</em> (to show), <em>false</em> (to hide). Default: <em>true</em>.
Example:[gallery type="carousel" ids="61,60,59" control="false"]</li>
    <li><code>interval</code>: the amount of time to delay between automatically cycling an item in milliseconds. Example 5000 = 5 seconds. If <em>0</em>, carousel will not automatically cycle. Default: <em>0</em>. (<a href="http://www.smashingmagazine.com/2015/02/09/carousel-usage-exploration-on-mobile-e-commerce-websites/" target="_blank">In this link yuou find why is set to 0 by default</a>)
Example:[gallery type="carousel" ids="61,60,59" interval="2000"]</li>
    <li><code>pause</code>: pauses the cycling of the carousel on mouseenter and resumes the cycling of the carousel on mouseleave. Default: <em>"hover"</em>.
Example:[gallery type="carousel" ids="61,60,59" interval="hover"]</li>
    <li><code>size</code>: size for image attachment. Accepted values: <em>thumbnail</em>, <em>medium</em>, <em>large</em>, <em>full</em>. Default: <em>full </em>or your own custom name added in add_image_size function. See <a href="http://codex.WordPress.org/Function_Reference/wp_get_attachment_image_src">wp_get_attachment_image_src()</a> for further reference.
Example:[gallery type="carousel" ids="61,60,59" size="full"]</li>
    <li><code>responsive</code>: Activate responsive image. Accepted values: true, false. Default false. It works only if you add <code>sizetablet</code> and <code>sizephone</code> attribute. See below.
Example:[gallery type="carousel" ids="61,60,59" responsive="true" size="medium"]</li>
    <li><code>sizetablet</code>: Size image for tablet device. Accepted values: thumbnail, medium, large, full or your own custom name added in add_image_size function. Default: large.
Example:[gallery type="carousel" ids="61,60,59" responsive="true" size="full" sizetablet="large"]</li>
    <li><code>sizephone</code>: Size image for phone device. Accepted values: thumbnail, medium, large, full or your own custom name added in add_image_size function. Default: medium.
Example:[gallery type="carousel" ids="61,60,59" responsive="true" size="full" sizephone="medium"]</li>
</ul>
<h4><a id="user-content-native-supported-attributes" class="anchor" href="https://github.com/andrezrv/agnosia-bootstrap-carousel#native-supported-attributes"></a>Native supported attributes:</h4>
<ul class="task-list">
    <li><code>orderby</code>: Alternative order for your images.
Example:[gallery type="carousel" ids="61,60,59" orderby="rand"]</li>
    <li><code>link</code>: where your image titles will link to. Accepted values: <em>file</em>, <em>none</em> and empty. An empty value will link to your attachment's page.
Example:[gallery type="carousel" ids="61,60,59" link="file"]</li>
</ul>

<!-- Lazy Load documentation -->
<h3>Lazy Load Documentation</h3>

<h4>How to activate Lazy Load for images</h4>
For activate Lazy Load there is new page "Option" in ItalyStrap panel, in that page there is a checkbox, check on LazyLoad and that the magic begin :-P
<h4>How do I change the placeholder image in Lazy Load functionality</h4>
<pre>add_filter( 'ItalyStrapLazyload_placeholder_image', 'my_custom_lazyload_placeholder_image' );
 function my_custom_lazyload_placeholder_image( $image ) {
 return 'http://url/to/image';
 }</pre>
<h4>How do I lazy load other images in my theme?</h4>
You can use the italystrap_get_apply_lazyload helper function:
<pre>if ( function_exists( 'italystrap_get_apply_lazyload' ) )
    $content = italystrap_get_apply_lazyload( $content );</pre>
Or, you can add an attribute called "data-src" with the source of the image URL and set the actual image URL to a transparent 1x1 pixel.

You can also use italystrap_apply_lazyload helper function for print content:
<pre>if ( function_exists( 'italystrap_apply_lazyload' ) )
    italystrap_apply_lazyload( $content );</pre>
Otherwise you can also use output buffering, though this isn't recommended:
<pre>if ( function_exists( 'italystrap_get_apply_lazyload' ) )
 ob_start( 'italystrap_get_apply_lazyload' );</pre>
This will lazy load <em>all</em> your images.
<h4>This plugin is using JavaScript. What about visitors without JS?</h4>
No worries. They get the original element in a noscript element. No Lazy Loading for them, though.
<h4>I'm using a CDN. Will this plugin interfere?</h4>
Lazy loading works just fine. The images will still load from your CDN. If you have any problem please <a href="https://WordPress.org/support/plugin/italystrap" target="_blank">open a ticket</a> :-)
<h4>How can I verify that the plugin is working?</h4>
Check your HTML source or see the magic at work in Web Inspector, FireBug or similar.
<h4>I'm using my custom Bootstrap Carousel, why doesn't the second image appear?</h4>
Put the code below in your file js and type your Bootstrap Carousell ID in place of "#YOURCAROUSELID"
<pre>var cHeight = 0;$("#YOURCAROUSELID").on("slide.bs.carousel", function(){var $nextImage = $(".active.item", this).next(".item").find("img");var src = $nextImage.data("src");if (typeof src !== "undefined" &amp;&amp; src !== ""){$nextImage.attr("src", src);$nextImage.data("src", "");}});</pre>
<h4>I'm using an external carousel, will Lazy Load work with it?</h4>
I tried only with ItalyStrap Bootstrap Carousel, please send me a feedback if have any issue with other carousel, however I can't guarantee to solve the issue.


<!-- Local Busines docs -->
<h3>ItalyStrap vCard Local Business documentation</h3>


<h4>How can I use Local Business widget</h4>
Simply activate functionality from ItalyStrap option page, add ItalyStrap vCard Local Business in your widgetozed area and then fill in the fields input of ItalyStrap vCard Local Business
<h4>Available Local Business Types for now (list from schema.org):</h4>
<ul>
    <li>AccountingService</li>
    <li>AutoDealer</li>
    <li>AutoRental</li>
    <li>AutoRepair</li>
    <li>AutoWash</li>
    <li>Attorney</li>
    <li>Bakery</li>
    <li>BarOrPub</li>
    <li>ChildCare</li>
    <li>ClothingStore</li>
    <li>Dentist</li>
    <li>ElectronicsStore</li>
    <li>EmergencyService</li>
    <li>EntertainmentBusiness</li>
    <li>EventVenue</li>
    <li>ExerciseGym</li>
    <li>FinancialService</li>
    <li>FurnitureStore</li>
    <li>GardenStore</li>
    <li>GeneralContractor</li>
    <li>GolfCourse</li>
    <li>HardwareStore</li>
    <li>HealthAndBeautyBusiness</li>
    <li>HomeAndConstructionBusiness</li>
    <li>HobbyShop</li>
    <li>HomeGoodsStore</li>
    <li>Hotel</li>
    <li>HVACBusiness</li>
    <li>InsuranceAgency</li>
    <li>LodgingBusiness</li>
    <li>MedicalClinic</li>
    <li>MensClothingStore</li>
    <li>MotorcycleDealer</li>
    <li>MovingCompany</li>
    <li>PetStore</li>
    <li>Physician</li>
    <li>ProfessionalService</li>
    <li>RealEstateAgent</li>
    <li>Residence</li>
    <li>Restaurant</li>
    <li>School</li>
    <li>SportingGoodsStore</li>
    <li>Store</li>
    <li>TattooParlor</li>
    <li>TravelAgency</li>
    <li>VeterinaryCare</li>
</ul>
<h4>Available Fields:</h4>
<ul>
    <li>Business Name</li>
    <li>Logo URL</li>
    <li>Street Address</li>
    <li>Zipcode/Postal Code</li>
    <li>City/Locality</li>
    <li>State/Region</li>
    <li>Country</li>
    <li>Telephone number</li>
    <li>Mobile number</li>
    <li>Fax number</li>
    <li>Email</li>
    <li>TaxID</li>
    <li>Facebook page (hidden)</li>
    <li>Twitter page (hidden)</li>
    <li>Googleplus page (hidden)</li>
    <li>Pinterest page (hidden)</li>
    <li>Instagram page (hidden)</li>
    <li>Youtube page (hidden)</li>
    <li>Linkedin page (hidden)</li>
</ul>

<!-- / End text -->
            </div>
        </div>
    </div>
</div>