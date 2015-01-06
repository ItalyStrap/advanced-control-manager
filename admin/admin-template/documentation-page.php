<div class="wrap">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="jumbotron">
					<h1 class='text-center'><?php _e( 'Documentation', 'ItalyStrap' ) ?></h1>
				</div>
				<h3>How to use Breadcrumbs</h3>
By default the breadcrumbs is developed with <a href="http://schema.org/BreadcrumbList" target="_blank">Schema.org markups</a> for Google Rich snippets and <a href="http://getbootstrap.com/components/#breadcrumbs" target="_blank">Bootstrap style</a>.

No problem if you don't have Bootstrap in your site, this class accepts your personal customization for your purpose.

Put the code below in your theme files (single.php and page.php, optional in archive.php, 404.php <span id="result_box" class="short_text" lang="en"><span class="hps">or</span> <span class="hps">where you want to</span> <span class="hps">show it</span></span>)
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
<strong>Example to show Bootstrap Glyphicon instead of Home:</strong>
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

			</div>
		</div>
	</div>
</div>