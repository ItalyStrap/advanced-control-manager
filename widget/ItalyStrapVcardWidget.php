<?php
/**
 * @link http://codex.wordpress.org/Function_Reference/the_widget
 * @link https://core.trac.wordpress.org/browser/tags/3.9.2/src/wp-includes/default-widgets.php#L0
 */

/**
 * vCard Widget Local Business
 * @todo Controllare i vari link Schema.org, qualcuno potrebbe dare errore
 * @todo Mettere lista opzioni in ordine alfabetico con jquery
 * @todo Aggiornare la lista completa delle attività di local business
 * @todo Aggiungere la lista delle attività al file readme
 * @todo Ordinare la lista per dipendenze
 *       store
 *       store - musicstore
 * @todo creare array nome -> valore con lista di tutte le attività
 * @todo https://wordpress.org/plugins/mdp-local-business-seo/
 * @todo https://wordpress.org/plugins/local-business-microdata-widget/
 * @todo https://wordpress.org/plugins/local-search-seo-contact-page/
 * @todo search local seo
 *
 * Added upload media library for image
 * @link http://www.paulund.co.uk/add-upload-media-library-widgets
 * 
 */
if ( ! class_exists( 'ItalyStrapVcardWidget' ) ){

	class ItalyStrapVcardWidget extends WP_Widget {

		/**
		 * Array with widget fields
		 * @var array
		 */
		private $fields = array();

		private $schema = array();

		function __construct() {

			$widget_ops = array(
				'classname'		=> 'widget_italystrap_vcard',
				'description'	=> __( 'Use this widget to add a vCard Local Business', 'ItalyStrap' )
				);

			parent::__construct('widget_italystrap_vcard', __('ItalyStrap: vCard Local Business', 'ItalyStrap'), $widget_ops);

			$this->fields = array(
				'schema'			=> __( 'Local or Organization?', 'ItalyStrap' ),
				'title'				=> __( 'Widget Title (optional)', 'ItalyStrap' ),
				'company_name'		=> __( 'Company name', 'ItalyStrap' ),
				'logo_url'			=> __( 'Logo URL', 'ItalyStrap' ),
				'show_logo'			=> __( 'Show Logo', 'ItalyStrap' ),
				'street_address'	=> __( 'Street Address', 'ItalyStrap' ),
				'postal_code'		=> __( 'Zipcode/Postal Code', 'ItalyStrap' ),
				'locality'			=> __( 'City/Locality', 'ItalyStrap' ),
				'region'			=> __( 'State/Region', 'ItalyStrap' ),
				'country'			=> __( 'Country', 'ItalyStrap' ),
				'tel'				=> __( 'Telephone number', 'ItalyStrap' ),
				'mobile'			=> __( 'Mobile number', 'ItalyStrap' ),
				'fax'				=> __( 'Fax number', 'ItalyStrap' ),
				'email'				=> __( 'Email', 'ItalyStrap' ),
				'taxID'				=> __( 'TaxID', 'ItalyStrap' ),
				'facebook'			=> __( 'Facebook page (hidden)', 'ItalyStrap' ),
				'twitter'			=> __( 'Twitter page (hidden)', 'ItalyStrap' ),
				'googleplus'		=> __( 'Googleplus page (hidden)', 'ItalyStrap' ),
				'pinterest'			=> __( 'Pinterest page (hidden)', 'ItalyStrap' ),
				'instagram'			=> __( 'Instagram page (hidden)', 'ItalyStrap' ),
				'youtube'			=> __( 'Youtube page (hidden)', 'ItalyStrap' ),
				'linkedin'			=> __( 'Linkedin page (hidden)', 'ItalyStrap' )
			);

			$this->alt_option_name = 'widget_italystrap_vcard';

			add_action('save_post', array(&$this, 'flush_widget_cache'));
			add_action('deleted_post', array(&$this, 'flush_widget_cache'));
			add_action('switch_theme', array(&$this, 'flush_widget_cache'));

			$this->schema = array(
				'LocalBusiness'					=>	'Local Business - (Default)',
				'Organization'					=>	'Organization - (For services and home offices)',
				'AccountingService'				=>	'Accounting Service',
				'AutoBodyShop'					=>	'Auto Body Shop',
				'AutoDealer'					=>	'Auto Dealer',
				'AutoPartsStore'				=>	'Auto Parts Store',
				'AutoRental'					=>	'Auto Rental',
				'AutoRepair'					=>	'Auto Repair',
				'AutoWash'						=>	'Auto Wash',
				'Attorney'						=>	'Attorney',
				'Bakery'						=>	'Bakery',
				'BarOrPub'						=>	'Bar Or Pub',
				'BeautySalon'					=>	'Beauty Salon',
				'BedAndBreakfast'				=>	'Bed &amp; Breakfast',
				'BikeStore'						=>	'Bicycle Store',
				'BookStore'						=>	'Book Store',
				'CafeOrCoffeeShop'				=>	'Cafe Or Coffee Shop',
				'ChildCare'						=>	'Child Care',
				'ClothingStore'					=>	'Clothing Store',
				'ComputerStore'					=>	'Computer Store',
				'DaySpa'						=>	'Day Spa',
				'Dentist'						=>	'Dentist',
				'DryCleaningOrLaundry'			=>	'Dry Cleaning Or Laundry',
				'Electrician'					=>	'Electrician',
				'ElectronicsStore'				=>	'Electronics Store',
				'EmergencyService'				=>	'Emergency Service',
				'EntertainmentBusiness'			=>	'Entertainment Business',
				'EventVenue'					=>	'Event Venue',
				'ExerciseGym'					=>	'Exercise Gym',
				'FinancialService'				=>	'Financial Service',
				'Florist'						=>	'Florist',
				'FurnitureStore'				=>	'Furniture Store',
				'FoodEstablishment'				=>	'Food Establishment',
				'GardenStore'					=>	'Garden Store',
				'GeneralContractor'				=>	'General Contractor',
				'GolfCourse'					=>	'Golf Course',
				'HairSalon'						=>	'Hair Salon',
				'HardwareStore'					=>	'Hardware Store',
				'HealthAndBeautyBusiness'		=>	'Health And Beauty Business',
				'HomeAndConstructionBusiness'	=>	'Home And Construction Business',
				'HobbyShop'						=>	'Hobby Shop',
				'HomeGoodsStore'				=>	'Home Goods Store',
				'Hospital'						=>	'Hospital',
				'Hotel'							=>	'Hotel',
				'HousePainter'					=>	'House Painter',
				'HVACBusiness'					=>	'HVAC Business',
				'InsuranceAgency'				=>	'Insurance Agency',
				'JewelryStore'					=>	'Jewelry Store',
				'LiquorStore'					=>	'Liquor Store',
				'Locksmith'						=>	'Locksmith',
				'LodgingBusiness'				=>	'Lodging Business',
				'MedicalClinic'					=>	'Medical Clinic',
				'MensClothingStore'				=>	'Mens Clothing Store',
				'MobilePhoneStore'				=>	'Mobile Phone Store',
				'Motel'							=>	'Motel',
				'MotorcycleDealer'				=>	'Motorcycle Dealer',
				'MotorcycleRepair'				=>	'Motorcycle Repair',
				'MovingCompany'					=>	'Moving Company',
				'MusicStore'					=>	'Music Store',
				'NailSalon'						=>	'Nail Salon',
				'NightClub'						=>	'Night Club',
				'Notary'						=>	'Notary Public',
				'OfficeEquipmentStore'			=>	'Office Equipment Store',
				'Optician'						=>	'Optician',
				'PetStore'						=>	'PetStore',
				'Physician'						=>	'Physician',
				'Plumber'						=>	'Plumber',
				'ProfessionalService'			=>	'Professional Service',
				'RealEstateAgent'				=>	'Real Estate Agent',
				'Residence'						=>	'Residence',
				'Restaurant'					=>	'Restaurant',
				'RoofingContractor'				=>	'Roofing Contractor',
				'RVPark'						=>	'RV Park',
				'School'						=>	'School',
				'SelfStorage'					=>	'Self Storage',
				'ShoeStore'						=>	'ShoeStore',
				'SkiResort'						=>	'Ski Resort',
				'SportingGoodsStore'			=>	'Sporting Goods Store',
				'SportsClub'					=>	'Sports Club',
				'Store'							=>	'Store',
				'TattooParlor'					=>	'Tattoo Parlor',
				'Taxi'							=>	'Taxi',
				'TennisComplex'					=>	'Tennis Complex',
				'TireShop'						=>	'Tire Shop',
				'ToyStore'						=>	'Toy Store',
				'TravelAgency'					=>	'Travel Agency',
				'VeterinaryCare'				=>	'Veterinary Care',
				'WholesaleStore'				=>	'Wholesale Store',
				'Winery'						=>	'Winery',
				);

			add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

		}

		function widget( $args, $instance ) {

			$cache = wp_cache_get('widget_italystrap_vcard', 'widget');

			if ( !is_array( $cache ) )
				$cache = array();


			if ( !isset( $args[ 'widget_id' ] ) )
				$args['widget_id'] = null;


			if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {

				echo $cache[ $args[ 'widget_id' ] ];
				return;

			}

			ob_start();

			foreach( $args as $key => $value )
				$$key = $value;

			$title = apply_filters(
						'ItalyStrapVcardWidget_title',
						empty( $instance['title'] ) ? '' : $instance['title'],
						$instance,
						$this->id_base
						);

			foreach( $this->fields as $name => $label )
				if ( !isset( $instance[ $name ] ) )
					$instance[ $name ] = '';

			echo $before_widget;

			/**
			 * Print the optional widget title
			 */
			if ($title)
				echo $before_title . $title . $after_title;

		?>

<ul itemscope itemtype="http://schema.org/<?php esc_attr_e( $instance[ 'schema' ] ); ?>" class="list-unstyled schema" id="schema">

	<?php if ( $instance[ 'show_logo' ] ): ?>

		<img src="<?php echo esc_url( $instance['logo_url'] );?>" alt="<?php echo esc_html( $instance['company_name'] ); ?>" itemprop="logo" />

	<?php else: ?>

		<meta  itemprop="logo" content="<?php echo esc_url( $instance['logo_url'] );?>"/>

	<?php endif; ?>

	<li>
		<strong>
			<a itemprop="url" href="<?php echo home_url('/'); ?>">
				<span itemprop="name">
					<?php

					if ( $instance['company_name'] )
						echo esc_html( $instance['company_name'] );

					else
						echo bloginfo('name');

					?>
				</span>
			</a>
		</strong>
	</li>
	<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
		<li itemprop="streetAddress">
			<?php echo esc_html($instance['street_address'] ); ?>
		</li>
		<li>
			<span itemprop="postalCode"><?php echo esc_html( $instance['postal_code'] ) . ' ';?></span>
			<span itemprop="addressLocality"><?php echo esc_html( $instance['locality'] ); ?></span>
		</li>
		<li itemprop="addressRegion"><?php echo esc_html( $instance['region'] ); ?></li>
		<li itemprop="addressCountry"><?php echo esc_html( $instance['country'] ); ?></li>
	</div>
	<li itemprop="telephone"><?php echo esc_html( $instance['tel'] ); ?></li>
	<li itemprop="telephone"><?php echo esc_html( $instance['mobile'] ); ?></li>
	<li itemprop="faxNumber"><?php echo esc_html( $instance['fax'] ); ?></li>
	<li itemprop="email">
		<a href="mailto:<?php echo antispambot( esc_html( $instance['email'], 1 ) ); ?>"><?php echo antispambot( esc_html( $instance['email'] ) ); ?></a>
	</li>
	<li itemprop="taxID"><?php echo esc_html( $instance['taxID'] ); ?></li>

<?php
	
	if( $instance['facebook'] )
		echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['facebook'] ) . '"/>';
	
	if( $instance['twitter'] )
		echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['twitter'] ) . '"/>';
	
	if( $instance['googleplus'] )
		echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['googleplus'] ) . '"/>';

	if( $instance['pinterest'] )
		echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['pinterest'] ) . '"/>';

	if( $instance['instagram'] )
		echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['instagram'] ) . '"/>';

	if( $instance['youtube'] )
		echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['youtube'] ) . '"/>';

	if( $instance['linkedin'] )
		echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['linkedin'] ) . '"/>';

?>

</ul>

		<?php
			echo $after_widget;

			$cache[$args['widget_id']] = ob_get_flush();
				wp_cache_set('widget_italystrap_vcard', $cache, 'widget');

		} // End $this->widget()


		/**
		 * Update widget data
		 * @param  array $new_instance
		 * @param  array $old_instance
		 * @return array               Return the sanitized array
		 */
		function update($new_instance, $old_instance) {

			/**
			 * Sanitizzo l'array con array_map
			 * @var array
			 */
			$instance = array_map('strip_tags', $new_instance);

			$this->flush_widget_cache();

			$alloptions = wp_cache_get('alloptions', 'options');

			if ( isset( $alloptions[ 'widget_italystrap_vcard' ] ) )
				delete_option( 'widget_italystrap_vcard' );

			return $instance;
		}

		function flush_widget_cache(){

			wp_cache_delete('widget_italystrap_vcard', 'widget');
		}


		/**
		 * Form imput in widget admin panel
		 * @param  array  $instance Array of input field
		 * @return string			Return form HTML
		 */
		function form( $instance ) {

			$form = '';

			foreach( $this->fields as $name => $label ){

				${$name} = isset( $instance[$name] ) ? esc_attr( $instance[ $name ] ) : '';

				/**
				 * Save select in widget
				 * @link https://wordpress.org/support/topic/wordpress-custom-widget-select-options-not-saving
				 * Display select only if is schema
				 */
				if ( $name === 'schema' ) {
					$form .= '';
				?>
				<p>
					<label for="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>">
						<?php echo $label; ?>
					</label>
					<select name="<?php esc_attr_e( $this->get_field_name( $name ) ); ?>" id="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>" style="width:100%;" id="selectSchema" class="selectSchema">

						<?php
						$option = '';
						foreach ( $this->schema as $key => $value )
							$option .= '<option ' . ( $selected = ( $key === ${$name} ) ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
						echo $option;
						?>
					</select>
				</p>
				<?php
				} elseif ( $name === 'show_logo' ) {

					$active = ( isset( $instance[$name] ) ) ? $instance[$name] : '' ;
				?>
				<p>
					<input id="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( $name ) ); ?>" type="checkbox" <?php checked( $active, 1 ); ?> value="1" placeholder="<?php echo $label; ?>">
					<label for="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>">
						<?php echo $label; ?>
					</label>
				</p>
				<?php
				} else {
				?>
				<p>
					<label for="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>">
						<?php echo $label; ?>
					</label>
					<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( $name ) ); ?>" type="text" value="<?php echo ${$name}; ?>" placeholder="<?php echo $label; ?>">
					<?php if ( $name === 'logo_url' ) :?>
					<input class="upload_image_button button button-primary" type="button" value="Upload Image" />
					<?php endif; ?>
				</p>
				<?php } //!- else
			}

		} // End $this->form()

		/**
		 * Upload the Javascripts for the media uploader in widget config
		 */
		public function upload_scripts(){

			$js_file = ( WP_DEBUG ) ? 'admin/js/src/widget.js' : 'admin/js/widget.min.js';

			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_script(
				'upload_media_widget',
				ITALYSTRAP_PLUGIN_URL . $js_file,
				array('jquery')
				);
			wp_enqueue_style('thickbox');

		}

	}
}// if class_exist