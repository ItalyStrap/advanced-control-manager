<div class="wrap">
	<h1><?php _e( 'ItalyStrap Dashboard', 'italystrap' ); ?></h1>
	<div class="welcome-panel" id="welcome-panel">
		<div class="welcome-panel-content">
			<h2><?php _e( 'Welcome to ItalyStrap', 'italystrap' ); ?></h2>
			<p class="about-description"><?php _e( 'Here you can find some link to get you started', 'italystrap' ); ?></p>
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<h3><?php _e( 'Get Started', 'italystrap' ); ?></h3>
					<a href="<?php echo get_admin_url(NULL, 'admin.php?page=italystrap-settings'); ?>" class="button button-primary button-hero load-customize hide-if-no-customize"><?php _e( 'Go to the settings page', 'italystrap' ); ?></a>
					<h4 style="font-size:24px;"><?php _e( 'But first of all you can read the ', 'italystrap' ); ?><a href="http://docs.italystrap.com/" target="_blank"><?php _e( 'documentation', 'italystrap' ); ?></a></h4>
				</div>
<!-- 				<div class="welcome-panel-column">
					<h3>Passi successivi</h3>
					<ul>
						<li><a class="welcome-icon welcome-edit-page" href="http://192.168.1.10/italystrap/wp-admin/post.php?post=701&amp;action=edit">Modificare la pagina iniziale</a></li>
						<li><a class="welcome-icon welcome-add-page" href="http://192.168.1.10/italystrap/wp-admin/post-new.php?post_type=page">Aggiungere altre pagine</a></li>
						<li><a class="welcome-icon welcome-write-blog" href="http://192.168.1.10/italystrap/wp-admin/post-new.php">Aggiungere un articolo</a></li>
						<li><a class="welcome-icon welcome-view-site" href="http://192.168.1.10/italystrap/">Visualizza il tuo sito</a></li>
					</ul>
				</div>
				<div class="welcome-panel-column welcome-panel-last">
					<h3>Altre azioni</h3>
					<ul>
						<li><div class="welcome-icon welcome-widgets-menus">Gestione <a href="http://192.168.1.10/italystrap/wp-admin/widgets.php">widget</a> o <a href="http://192.168.1.10/italystrap/wp-admin/nav-menus.php">menu</a></div></li>
						<li><a class="welcome-icon welcome-comments" href="http://192.168.1.10/italystrap/wp-admin/options-discussion.php">Attiva o disattiva i commenti</a></li>
						<li><a class="welcome-icon welcome-learn-more" href="https://codex.wordpress.org/First_Steps_With_WordPress ">Maggiori informazioni su come iniziare</a></li>
					</ul>
				</div> -->
			</div>
			<div style="clear:both;">
				<?php 
				$parsedown = new Parsedown();

				$readme = ItalyStrap\Core\get_file_content( ITALYSTRAP_PLUGIN_PATH . 'README.md' );

				echo $parsedown->text( $readme );
				?>
			</div>
		</div>
	</div>


	<!-- <div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<a href="<?php echo get_admin_url(NULL, 'admin.php?page=italystrap-documentation'); ?>"><?php _e( 'Documentation', 'ItalyStrap' ); ?></a>
				<div class="jumbotron">
					<h1 class='text-center'>ItalyStrap</h1>
					<h3 class='text-center h2'><?php _e( 'Make your website more powerful', 'ItalyStrap' ) ?></h3>
				</div>
				<div class="page-header">
					<h3 class="text-center h1"><?php _e( 'New Entry', 'ItalyStrap' ); ?></h3>
				</div>

				<div class="col-md-4 col-md-push-4">
					<div class="thumbnail">
						<div class="image local"></div>
						<div class="caption">
							<h4><span class="dashicons dashicons-store"></span> ItalyStrap vCard for Local Business</h4>
							<h5>Add Schema.org Local business in your site</h5>
							<p><?php _e( 'A simple Widget to add Schema.org Local business in your widgetized themes', 'ItalyStrap' ); ?></p>
							<p class="submit"><a href="<?php echo get_admin_url(NULL, 'admin.php?page=italystrap-documentation'); ?>" class="button button-primary"><?php _e( 'Read the documentation', 'ItalyStrap' ); ?></a></p>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="page-header">
				<h3 class="text-center h1"><?php _e( 'Coming soon', 'ItalyStrap' ); ?></h3 class="text-center">
			</div>
			<div class="col-md-12">
				<div class="col-md-4 new-md-4">
					<div class="thumbnail">
						<div class="caption">
							<h4><span class="dashicons dashicons-editor-code"></span> Shortcode</h4>
							<p><?php _e( 'Add shortcode in admin panel, only for Bootstrap Style', 'ItalyStrap' ); ?></p>
						</div>
					</div>
				</div>
				<div class="col-md-4 new-md-4">
					<div class="thumbnail">
						<div class="caption">
							<h4><span class="dashicons dashicons-share"></span> Social</h4>
							<p><?php _e( 'Social Button and Markup', 'ItalyStrap' ); ?></p>
						</div>
					</div>
				</div>
				<div class="col-md-4 new-md-4">
					<div class="thumbnail">
						<div class="caption">
							<h4><span class="dashicons dashicons-id"></span> OpenGraph and Twitter Cards</h4>
							<p><?php _e( 'OpenGraph and Twitter Cards for your social share', 'ItalyStrap' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h3 class="text-center h1"><?php _e( 'Currently present', 'ItalyStrap' ); ?></h3>
				</div>



				<div class="col-md-4">
					<div class="thumbnail">
						<div class="image local"></div>
						<div class="caption">
							<h4><span class="dashicons dashicons-store"></span> ItalyStrap vCard for Local Business</h4>
							<h5>Add Schema.org Local business in your site</h5>
							<p><?php _e( 'A simple Widget to add Schema.org Local business in your widgetized themes', 'ItalyStrap' ); ?></p>
							<p class="submit"><a href="<?php echo get_admin_url(NULL, 'admin.php?page=italystrap-documentation'); ?>" class="button button-primary"><?php _e( 'Read the documentation', 'ItalyStrap' ); ?></a></p>
						</div>
					</div>
				</div>


				<div class="col-md-4 new-md-4">
					<div class="thumbnail">
						<div class="image lazyimage"></div>
						<div class="caption">
							<h4><span class="dashicons dashicons-performance"></span> Lazy Load</h4>
							<h5>Lazy Load for images and Carousel</h5>
							<p><?php _e( 'Activate Lazy Load from Option Page and let the magic begin :-)', 'ItalyStrap' ); ?></p>
							<p class="submit"><a href="<?php echo get_admin_url(NULL, 'admin.php?page=italystrap-documentation'); ?>" class="button button-primary"><?php _e( 'Read the documentation', 'ItalyStrap' ); ?></a></p>
						</div>
					</div>
				</div>

				<div class="col-md-4 new-md-4">
					<div class="thumbnail">
						<div class="image carouimage"></div>
						<div class="caption">
							<h4><span class="dashicons dashicons-images-alt"></span> Bootstrap Carousel</h4>
							<h5>With Schema.org and responsive images</h5>
							<p><?php _e( 'Add attribute <code>type="carousel"</code> at gallery shortcode, this will show Bootstrap Carousel based on the selected images and their titles and descriptions, otherwise It will show standard WordPress Gallery.', 'ItalyStrap' ); ?></p>
							<p class="submit"><a href="<?php echo get_admin_url(NULL, 'admin.php?page=italystrap-documentation'); ?>" class="button button-primary"><?php _e( 'Read the documentation', 'ItalyStrap' ); ?></a></p>
						</div>
					</div>
				</div>

				<div class="col-md-4 new-md-4">
					<div class="thumbnail">
						<div class="image breadimage"></div>
						<div class="caption">
							<h4><span class="dashicons dashicons-networking"></span> Breadcrumbs</h4>
							<p><?php _e( 'Add breadcrumbs to your site in a simple and fast way and get Google rich snippet with Schema.org markup', 'ItalyStrap' ); ?></p>
							<p class="submit"><a href="<?php echo get_admin_url(NULL, 'admin.php?page=italystrap-documentation'); ?>" class="button button-primary"><?php _e( 'Read the documentation', 'ItalyStrap' ); ?></a></p>
						</div>
					</div>
				</div>

			</div>
		</div>


	</div> -->
</div>
