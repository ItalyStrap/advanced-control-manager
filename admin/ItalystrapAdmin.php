<?php
/**
 * Class for admin panel
 *
 * This class add some functions for use in admin panel
 *
 * @link http://codex.wordpress.org/Adding_Administration_Menus
 *
 * @since 1.0.0
 *
 * @package ItalyStrap
 */
if ( !class_exists( 'ItalyStrapAdmin' ) ){

	class ItalyStrapAdmin{

		/**
		 * Definition of variables containing the configuration
		 * to be applied to the various function calls wordpress
		 */

		protected $capability      = 'manage_options';

		public function __construct(){

			/**
			 * Add Admin menù page
			 */
			add_action( 'admin_menu', array( $this, 'addMenuPage') );

			/**
			 * Add Admin sub menù page
			 */
			add_action( 'admin_menu', array( $this, 'addSubMenuPage') );

			// add_action( 'admin_menu', array( $this, 'italystrap_add_admin_menu') );

			// add_action( 'admin_init', array( $this, 'italystrap_settings_init') );

			/**
			 * Load script only if is ItalyStrap admin panel
			 */
			if (isset($_GET['page']) && ($_GET['page'] === 'italystrap-dashboard' || $_GET['page'] === 'italystrap-documentation')) {
			
				add_action('admin_enqueue_scripts', array( $this, 'ItalyStrap_admin_style_script' ));
			}

			/**
			 * Add link in plugin activation panel
			 */
			add_filter( 'plugin_action_links_' . ITALYSTRAP_BASENAME, array( $this, 'plugin_action_links' ) );
		
		}

		/**
		 * Add style for ItalyStrap admin page
		 */
		public function ItalyStrap_admin_style_script() {

			wp_enqueue_style('bootstrap', plugins_url('css/bootstrap.min.css', __FILE__));
			wp_enqueue_style('style', plugins_url('css/style.css', __FILE__));
			wp_register_style( 'openSans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300' );
			wp_enqueue_scripts( 'openSans' );

		}

		/**
		 * Add page for ItalyStrap admin page
		 */
		public function addMenuPage(){

			add_menu_page(
				__('ItalyStrap Dashboard', 'ItalyStrap'),
				'ItalyStrap',
				$this->capability,
				'italystrap-dashboard',
				array( $this, 'dashboard'),
				'dashicons-performance',
				NULL,
				NULL );
		}

		/**
		 *	The dashboard callback
		 */
		public function dashboard(){

			if ( !current_user_can( $this->capability ) )
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

			/**
			 * Require dashboard-page.php
			 */
			require_once(ITALYSTRAP_PLUGIN_PATH . 'admin/admin-template/dashboard-page.php');

		}


		/**
		 * Add sub menù page for ItalyStrap admin page
		 */
		public function addSubMenuPage(){

			add_submenu_page( 'italystrap-dashboard', __('Documentation', 'ItalyStrap'), __('Documentation', 'ItalyStrap'), $this->capability, 'italystrap-documentation', array( $this, 'documentation') );

			// add_submenu_page( 'italystrap-dashboard', __('Options', 'ItalyStrap'), __('Options', 'ItalyStrap'), $this->capability, 'italystrap-options', array( $this, 'options') );

		}


		/**
		 * The documentation call back
		 */
		public function documentation(){

			if ( !current_user_can( $this->capability ) )
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

			/**
			 * Require documentation-page.php
			 */
			require_once(ITALYSTRAP_PLUGIN_PATH . 'admin/admin-template/documentation-page.php');				

		}// documentation()

		/**
		 * the options call back
		 */
		public function options(){

			if ( !current_user_can( $this->capability ) )
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

			/**
			 * Require options-page.php
			 */
			require_once(ITALYSTRAP_PLUGIN_PATH . 'admin/admin-template/options-page.php');				

		}// options

		/**
		 * Add link in plugin activation panel
		 * @param  array $links Array of link in wordpress dashboard
		 * @return array        Array with my links
		 */
		public function plugin_action_links( $links ){

				array_unshift($links, '<a href="admin.php?page=italystrap-documentation">' . __('Documentation','ItalyStrap') . '</a>');

				array_unshift($links, '<a href="http://www.italystrap.it" target="_blank">ItalyStrap</a>');

			return $links;
		}// plugin_action_links()



function italystrap_add_admin_menu() { 

	add_options_page( 'ItalyStrap', 'ItalyStrap', 'manage_options', 'ItalyStrap', array( $this, 'italystrap_options_page') );

}

function italystrap_settings_init() { 

	register_setting( 'pluginPage', 'italystrap_settings' );

	add_settings_section(
		'italystrap_pluginPage_section', 
		__( 'Your section description', 'ItalyStrap' ), 
		array( $this, 'italystrap_settings_section_callback'), 
		'pluginPage'
	);

	add_settings_field( 
		'italystrap_checkbox_field_0', 
		__( 'Settings field description', 'ItalyStrap' ), 
		array( $this, 'italystrap_checkbox_field_0_render'), 
		'pluginPage', 
		'italystrap_pluginPage_section'
	);


}


function italystrap_checkbox_field_0_render() { 

	$options = get_option( 'italystrap_settings' );
	?>
	<input type='checkbox' name='italystrap_settings[italystrap_checkbox_field_0]' <?php checked( $options['italystrap_checkbox_field_0'], 1 ); ?> value='1'>
	<?php

}


function italystrap_settings_section_callback() { 

	echo __( 'This section description', 'ItalyStrap' );

}


function italystrap_options_page() { 

	?>
	<form action='options.php' method='post'>
		
		<h2>italystrap</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}








	}// class
}//endif