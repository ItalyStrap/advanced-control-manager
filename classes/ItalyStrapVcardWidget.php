<?php
/**
* Customize your widget here
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
*/
if ( ! class_exists( 'ItalyStrapVcardWidget' ) ){

	class ItalyStrapVcardWidget extends WP_Widget {

		private $fields = array();

		function __construct() {

			$widget_ops = array(
				'classname' => 'widget_italystrap_vcard',
				'description' => __( 'Use this widget to add a vCard Local Business', 'ItalyStrap' )
				);

			parent::__construct('widget_italystrap_vcard', __('ItalyStrap: vCard Local Business', 'ItalyStrap'), $widget_ops);

			$this->fields = array(

				'schema'			=> __( 'Local or Organization?', 'ItalyStrap' ),
				'title'				=> __( 'Widget Title (optional)', 'ItalyStrap' ),
				'company_name'		=> __( 'Company name', 'ItalyStrap' ),
				'logo_url'			=> __( 'Logo URL', 'ItalyStrap' ),
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

		}

		function widget( $args, $instance ) {

			$cache = wp_cache_get('widget_italystrap_vcard', 'widget');

			if (!is_array($cache))
				$cache = array();


			if ( !isset( $args[ 'widget_id' ] ) )
				$args['widget_id'] = null;


			if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {

				echo $cache[ $args[ 'widget_id' ] ];
				return;

			}

			ob_start();

			extract($args, EXTR_SKIP);

			$title = apply_filters(
						'ItalyStrapVcardWidget_title',
						empty( $instance['title'] ) ? '' : $instance['title'],
						$instance,
						$this->id_base
						);

			foreach($this->fields as $name => $label)
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
	<meta  itemprop="logo" content="<?php echo esc_url( $instance['logo_url'] );?>"/>
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
		<li itemprop="streetAddress"><?php echo esc_html($instance['street_address'] ); ?></li>
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
</ul>

		<?php
			echo $after_widget;

			$cache[$args['widget_id']] = ob_get_flush();
				wp_cache_set('widget_italystrap_vcard', $cache, 'widget');

		}

		function update($new_instance, $old_instance) {

			$instance = array_map('strip_tags', $new_instance);

			$this->flush_widget_cache();

			$alloptions = wp_cache_get('alloptions', 'options');

			if (isset($alloptions['widget_italystrap_vcard']))
				delete_option('widget_italystrap_vcard');

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

			foreach($this->fields as $name => $label) {
				${$name} = isset( $instance[$name] ) ? esc_attr( $instance[ $name ] ) : '';

				/**
				 * Save select in widget
				 * @link https://wordpress.org/support/topic/wordpress-custom-widget-select-options-not-saving
				 */
				if ( $name === 'schema') {

					$selected = 'selected="selected"';
			?>
				<p>
					<label for="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>">
						<?php echo $label; ?>
					</label>
					<select name="<?php esc_attr_e( $this->get_field_name( $name ) ); ?>" id="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>" style="width:100%;" id="selectSchema" class="selectSchema">

						<option <?php if ( 'LocalBusiness' == ${$name} ) echo $selected; ?> value="LocalBusiness">Local Business - (Default)</option>

						<option <?php if ( 'Organization' == ${$name} ) echo $selected; ?> value="Organization">Organization - (For services and home offices)</option>

						<option <?php if ( 'AccountingService' == ${$name} ) echo $selected; ?> value="AccountingService">Accounting Service</option>

						<option <?php if ( 'AutoBodyShop' == ${$name} ) echo $selected; ?> value="AutoBodyShop">Auto Body Shop</option>

						<option <?php if ( 'AutoDealer' == ${$name} ) echo $selected; ?> value="AutoDealer">Auto Dealer</option>

						<option <?php if ( 'AutoPartsStore' == ${$name} ) echo $selected; ?> value="AutoPartsStore">Auto Parts Store</option>
						>

						<option <?php if ( 'AutoRental' == ${$name} ) echo $selected; ?> value="AutoRental">Auto Rental</option>

						<option <?php if ( 'AutoRepair' == ${$name} ) echo $selected; ?> value="AutoRepair">Auto Repair</option>

						<option <?php if ( 'AutoWash' == ${$name} ) echo $selected; ?> value="AutoWash">Auto Wash</option>

						<option <?php if ( 'Attorney' == ${$name} ) echo $selected; ?> value="Attorney">Attorney</option>

						<option <?php if ( 'Bakery' == ${$name} ) echo $selected; ?> value="Bakery">Bakery</option>

						<option <?php if ( 'BarOrPub' == ${$name} ) echo $selected; ?> value="BarOrPub">Bar Or Pub</option>

						<option <?php if ( 'BeautySalon' == ${$name} ) echo $selected; ?> value="BeautySalon">Beauty Salon</option>

						<option <?php if ( 'BedAndBreakfast' == ${$name} ) echo $selected; ?> value="BedAndBreakfast">Bed &amp; Breakfast</option>

						<option <?php if ( 'BikeStore' == ${$name} ) echo $selected; ?> value="BikeStore">Bicycle Store</option>


						<option <?php if ( 'BookStore' == ${$name} ) echo $selected; ?> value="BookStore">Book Store</option>

						<option <?php if ( 'CafeOrCoffeeShop' == ${$name} ) echo $selected; ?> value="CafeOrCoffeeShop">Cafe Or Coffee Shop</option>

						<option <?php if ( 'ChildCare' == ${$name} ) echo $selected; ?> value="ChildCare">Child Care</option>

						<option <?php if ( 'ClothingStore' == ${$name} ) echo $selected; ?> value="ClothingStore">Clothing Store</option>

						<option <?php if ( 'ComputerStore' == ${$name} ) echo $selected; ?> value="ComputerStore">Computer Store</option>

						<option <?php if ( 'DaySpa' == ${$name} ) echo $selected; ?> value="DaySpa">Day Spa</option>

						<option <?php if ( 'Dentist' == ${$name} ) echo $selected; ?> value="Dentist">Dentist</option>

						<option <?php if ( 'DryCleaningOrLaundry' == ${$name} ) echo $selected; ?> value="DryCleaningOrLaundry">Dry Cleaning Or Laundry</option>

						<option <?php if ( 'Electrician' == ${$name} ) echo $selected; ?> value="Electrician">Electrician</option>

						<option <?php if ( 'ElectronicsStore' == ${$name} ) echo $selected; ?> value="ElectronicsStore">Electronics Store</option>

						<option <?php if ( 'EmergencyService' == ${$name} ) echo $selected; ?> value="EmergencyService">Emergency Service</option>

						<option <?php if ( 'EntertainmentBusiness' == ${$name} ) echo $selected; ?> value="EntertainmentBusiness">Entertainment Business</option>

						<option <?php if ( 'EventVenue' == ${$name} ) echo $selected; ?> value="EventVenue">EventVenue</option>

						<option <?php if ( 'ExerciseGym' == ${$name} ) echo $selected; ?> value="ExerciseGym">ExerciseGym</option>

						<option <?php if ( 'FinancialService' == ${$name} ) echo $selected; ?> value="FinancialService">Financial Service</option>

						<option <?php if ( 'Florist' == ${$name} ) echo $selected; ?> value="Florist">Florist</option>

						<option <?php if ( 'FurnitureStore' == ${$name} ) echo $selected; ?> value="FurnitureStore">Furniture Store</option>

						<option <?php if ( 'FoodEstablishment' == ${$name} ) echo $selected; ?> value="FoodEstablishment">Food Establishment</option>

						<option <?php if ( 'GardenStore' == ${$name} ) echo $selected; ?> value="GardenStore">Garden Store</option>

						<option <?php if ( 'GeneralContractor' == ${$name} ) echo $selected; ?> value="GeneralContractor">General Contractor</option>

						<option <?php if ( 'GolfCourse' == ${$name} ) echo $selected; ?> value="GolfCourse">Golf Course</option>

						<option <?php if ( 'HairSalon' == ${$name} ) echo $selected; ?> value="HairSalon">Hair Salon</option>

						<option <?php if ( 'HardwareStore' == ${$name} ) echo $selected; ?> value="HardwareStore">Hardware Store</option>

						<option <?php if ( 'HealthAndBeautyBusiness' == ${$name} ) echo $selected; ?> value="HealthAndBeautyBusiness">Health And Beauty Business</option>

						<option <?php if ( 'HomeAndConstructionBusiness' == ${$name} ) echo $selected; ?> value="HomeAndConstructionBusiness">Home And Construction Business</option>

						<option <?php if ( 'HobbyShop' == ${$name} ) echo $selected; ?> value="HobbyShop">HobbyShop</option>

						<option <?php if ( 'HomeGoodsStore' == ${$name} ) echo $selected; ?> value="HomeGoodsStore">Home Goods Store</option>

						<option <?php if ( 'Hospital' == ${$name} ) echo $selected; ?> value="Hospital">Hospital</option>

						<option <?php if ( 'Hotel' == ${$name} ) echo $selected; ?> value="Hotel">Hotel</option>

						<option <?php if ( 'HousePainter' == ${$name} ) echo $selected; ?> value="HousePainter">House Painter</option>

						<option <?php if ( 'HVACBusiness' == ${$name} ) echo $selected; ?> value="HVACBusiness">Air Conditioning</option>

						<option <?php if ( 'InsuranceAgency' == ${$name} ) echo $selected; ?> value="InsuranceAgency">Insurance Agency</option>

						<option <?php if ( 'JewelryStore' == ${$name} ) echo $selected; ?> value="JewelryStore">Jewelry Store</option>

						<option <?php if ( 'LiquorStore' == ${$name} ) echo $selected; ?> value="LiquorStore">Liquor Store</option>

						<option <?php if ( 'Locksmith' == ${$name} ) echo $selected; ?> value="Locksmith">Locksmith</option>

						<option <?php if ( 'LodgingBusiness' == ${$name} ) echo $selected; ?> value="LodgingBusiness">Lodging Business</option>

						<option <?php if ( 'MedicalClinic' == ${$name} ) echo $selected; ?> value="MedicalClinic">Medical Clinic</option>

						<option <?php if ( 'MensClothingStore' == ${$name} ) echo $selected; ?> value="MensClothingStore">Men's Clothing Store</option>

						<option <?php if ( 'MobilePhoneStore' == ${$name} ) echo $selected; ?> value="MobilePhoneStore">Cell Phone Store</option>

						<option <?php if ( 'Motel' == ${$name} ) echo $selected; ?> value="Motel">Motel</option>

						<option <?php if ( 'MotorcycleDealer' == ${$name} ) echo $selected; ?> value="MotorcycleDealer">Motorcycle Dealer</option>

						<option <?php if ( 'MotorcycleRepair' == ${$name} ) echo $selected; ?> value="MotorcycleRepair">Motorcycle Repair Shop</option>

						<option <?php if ( 'MovingCompany' == ${$name} ) echo $selected; ?> value="MovingCompany">Moving &amp; Storage Service</option>

						<option <?php if ( 'MusicStore' == ${$name} ) echo $selected; ?> value="MusicStore">Musical Store</option>

						<option <?php if ( 'NailSalon' == ${$name} ) echo $selected; ?> value="NailSalon">Nail Salon</option>

						<option <?php if ( 'NightClub' == ${$name} ) echo $selected; ?> value="NightClub">Night Club</option>

						<option <?php if ( 'Notary' == ${$name} ) echo $selected; ?> value="Notary">Notary Public</option>

						<option <?php if ( 'OfficeEquipmentStore' == ${$name} ) echo $selected; ?> value="OfficeEquipmentStore">Office Supply Store</option>

						<option <?php if ( 'Optician' == ${$name} ) echo $selected; ?> value="Optician">Optician</option>

						<option <?php if ( 'PetStore' == ${$name} ) echo $selected; ?> value="PetStore">Pet Store</option>

						<option <?php if ( 'Physician' == ${$name} ) echo $selected; ?> value="Physician">Physician</option>

						<option <?php if ( 'Plumber' == ${$name} ) echo $selected; ?> value="Plumber">Plumber</option>

						<option <?php if ( 'ProfessionalService' == ${$name} ) echo $selected; ?> value="ProfessionalService">Professional Service</option>

						<option <?php if ( 'RealEstateAgent' == ${$name} ) echo $selected; ?> value="RealEstateAgent">Real Estate Agent</option>

						<option <?php if ( 'Residence' == ${$name} ) echo $selected; ?> value="Residence">Residence</option>

						<option <?php if ( 'Restaurant' == ${$name} ) echo $selected; ?> value="Restaurant">Restaurant</option>

						<option <?php if ( 'RoofingContractor' == ${$name} ) echo $selected; ?> value="RoofingContractor">Roofing Contractor</option>

						<option <?php if ( 'RVPark' == ${$name} ) echo $selected; ?> value="RVPark">RV Park</option>

						<option <?php if ( 'School' == ${$name} ) echo $selected; ?> value="School">School</option>

						<option <?php if ( 'SelfStorage' == ${$name} ) echo $selected; ?> value="SelfStorage">Self-Storage Facility</option>

						<option <?php if ( 'ShoeStore' == ${$name} ) echo $selected; ?> value="ShoeStore">Shoe Store</option>

						<option <?php if ( 'SkiResort' == ${$name} ) echo $selected; ?> value="SkiResort">Ski Resort</option>

						<option <?php if ( 'SportingGoodsStore' == ${$name} ) echo $selected; ?> value="SportingGoodsStore">Sporting Goods Store</option>

						<option <?php if ( 'SportsClub' == ${$name} ) echo $selected; ?> value="Sports Club">Sports Club</option>

						<option <?php if ( 'Store' == ${$name} ) echo $selected; ?> value="Store">Store</option>

						<option <?php if ( 'TattooParlor' == ${$name} ) echo $selected; ?> value="TattooParlor">Tattoo Parlor</option>

						<option <?php if ( 'Taxi' == ${$name} ) echo $selected; ?> value="Taxi">Taxi Service</option>

						<option <?php if ( 'TennisComplex' == ${$name} ) echo $selected; ?> value="TennisComplex">Tennis Club</option>

						<option <?php if ( 'TireShop' == ${$name} ) echo $selected; ?> value="TireShop">Tire Shop</option>

						<option <?php if ( 'ToyStore' == ${$name} ) echo $selected; ?> value="ToyStore">Toy Store</option>

						<option <?php if ( 'TravelAgency' == ${$name} ) echo $selected; ?> value="TravelAgency">Travel Agency</option>

						<option <?php if ( 'VeterinaryCare' == ${$name} ) echo $selected; ?> value="VeterinaryCare">Veterinary Care</option>

						<option <?php if ( 'WholesaleStore' == ${$name} ) echo $selected; ?> value="WholesaleStore">Wholesale Store</option>

						<option <?php if ( 'Winery' == ${$name} ) echo $selected; ?> value="Winery">Winery</option>

					</select>
				</p>
			<?php

				} else {
				
			?>
				<p>
					<label for="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>">
						<?php echo $label; ?>
					</label>
					<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( $name ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( $name ) ); ?>" type="text" value="<?php echo ${$name}; ?>" placeholder="<?php echo $label; ?>">
				</p>
			<?php } //!- else
			}

		}
	}
}