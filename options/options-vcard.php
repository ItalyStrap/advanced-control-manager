<?php
/**
 * Array definition for vCard default options
 *
 * @package ItalyStrap
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) die();

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return array(

	/**
	 * The type of schema.
	 */
	'schema'				=> array(
				'name'		=> __( 'Select your business', 'ItalyStrap' ),
				'desc'		=> __( 'Select your kind of activity.', 'ItalyStrap' ),
				'id'		=> 'schema',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'LocalBusiness',
				'options'	=> array(
						'LocalBusiness'					=> __( 'Local Business - (Default)', 'ItalyStrap' ),
						'Organization'					=> __( 'Organization - (For services and home offices)', 'ItalyStrap' ),
						'AccountingService'				=> __( 'Accounting Service', 'ItalyStrap' ),
						'AutoBodyShop'					=> __( 'Auto Body Shop', 'ItalyStrap' ),
						'AutoDealer'					=> __( 'Auto Dealer', 'ItalyStrap' ),
						'AutoPartsStore'				=> __( 'Auto Parts Store', 'ItalyStrap' ),
						'AutoRental'					=> __( 'Auto Rental', 'ItalyStrap' ),
						'AutoRepair'					=> __( 'Auto Repair', 'ItalyStrap' ),
						'AutoWash'						=> __( 'Auto Wash', 'ItalyStrap' ),
						'Attorney'						=> __( 'Attorney', 'ItalyStrap' ),
						'Bakery'						=> __( 'Bakery', 'ItalyStrap' ),
						'BarOrPub'						=> __( 'Bar Or Pub', 'ItalyStrap' ),
						'BeautySalon'					=> __( 'Beauty Salon', 'ItalyStrap' ),
						'BedAndBreakfast'				=> __( 'Bed &amp; Breakfast', 'ItalyStrap' ),
						'BikeStore'						=> __( 'Bicycle Store', 'ItalyStrap' ),
						'BookStore'						=> __( 'Book Store', 'ItalyStrap' ),
						'CafeOrCoffeeShop'				=> __( 'Cafe Or Coffee Shop', 'ItalyStrap' ),
						'ChildCare'						=> __( 'Child Care', 'ItalyStrap' ),
						'ClothingStore'					=> __( 'Clothing Store', 'ItalyStrap' ),
						'ComputerStore'					=> __( 'Computer Store', 'ItalyStrap' ),
						'DaySpa'						=> __( 'Day Spa', 'ItalyStrap' ),
						'Dentist'						=> __( 'Dentist', 'ItalyStrap' ),
						'DryCleaningOrLaundry'			=> __( 'Dry Cleaning Or Laundry', 'ItalyStrap' ),
						'Electrician'					=> __( 'Electrician', 'ItalyStrap' ),
						'ElectronicsStore'				=> __( 'Electronics Store', 'ItalyStrap' ),
						'EmergencyService'				=> __( 'Emergency Service', 'ItalyStrap' ),
						'EntertainmentBusiness'			=> __( 'Entertainment Business', 'ItalyStrap' ),
						'EventVenue'					=> __( 'Event Venue', 'ItalyStrap' ),
						'ExerciseGym'					=> __( 'Exercise Gym', 'ItalyStrap' ),
						'FinancialService'				=> __( 'Financial Service', 'ItalyStrap' ),
						'Florist'						=> __( 'Florist', 'ItalyStrap' ),
						'FurnitureStore'				=> __( 'Furniture Store', 'ItalyStrap' ),
						'FoodEstablishment'				=> __( 'Food Establishment', 'ItalyStrap' ),
						'GardenStore'					=> __( 'Garden Store', 'ItalyStrap' ),
						'GeneralContractor'				=> __( 'General Contractor', 'ItalyStrap' ),
						'GolfCourse'					=> __( 'Golf Course', 'ItalyStrap' ),
						'HairSalon'						=> __( 'Hair Salon', 'ItalyStrap' ),
						'HardwareStore'					=> __( 'Hardware Store', 'ItalyStrap' ),
						'HealthAndBeautyBusiness'		=> __( 'Health And Beauty Business', 'ItalyStrap' ),
						'HomeAndConstructionBusiness'	=> __( 'Home And Construction Business', 'ItalyStrap' ),
						'HobbyShop'						=> __( 'Hobby Shop', 'ItalyStrap' ),
						'HomeGoodsStore'				=> __( 'Home Goods Store', 'ItalyStrap' ),
						'Hospital'						=> __( 'Hospital', 'ItalyStrap' ),
						'Hotel'							=> __( 'Hotel', 'ItalyStrap' ),
						'HousePainter'					=> __( 'House Painter', 'ItalyStrap' ),
						'HVACBusiness'					=> __( 'HVAC Business', 'ItalyStrap' ),
						'InsuranceAgency'				=> __( 'Insurance Agency', 'ItalyStrap' ),
						'JewelryStore'					=> __( 'Jewelry Store', 'ItalyStrap' ),
						'LiquorStore'					=> __( 'Liquor Store', 'ItalyStrap' ),
						'Locksmith'						=> __( 'Locksmith', 'ItalyStrap' ),
						'LodgingBusiness'				=> __( 'Lodging Business', 'ItalyStrap' ),
						'MedicalClinic'					=> __( 'Medical Clinic', 'ItalyStrap' ),
						'MensClothingStore'				=> __( 'Mens Clothing Store', 'ItalyStrap' ),
						'MobilePhoneStore'				=> __( 'Mobile Phone Store', 'ItalyStrap' ),
						'Motel'							=> __( 'Motel', 'ItalyStrap' ),
						'MotorcycleDealer'				=> __( 'Motorcycle Dealer', 'ItalyStrap' ),
						'MotorcycleRepair'				=> __( 'Motorcycle Repair', 'ItalyStrap' ),
						'MovingCompany'					=> __( 'Moving Company', 'ItalyStrap' ),
						'MusicStore'					=> __( 'Music Store', 'ItalyStrap' ),
						'NailSalon'						=> __( 'Nail Salon', 'ItalyStrap' ),
						'NightClub'						=> __( 'Night Club', 'ItalyStrap' ),
						'Notary'						=> __( 'Notary Public', 'ItalyStrap' ),
						'OfficeEquipmentStore'			=> __( 'Office Equipment Store', 'ItalyStrap' ),
						'Optician'						=> __( 'Optician', 'ItalyStrap' ),
						'PetStore'						=> __( 'PetStore', 'ItalyStrap' ),
						'Physician'						=> __( 'Physician', 'ItalyStrap' ),
						'Plumber'						=> __( 'Plumber', 'ItalyStrap' ),
						'ProfessionalService'			=> __( 'Professional Service', 'ItalyStrap' ),
						'RealEstateAgent'				=> __( 'Real Estate Agent', 'ItalyStrap' ),
						'Residence'						=> __( 'Residence', 'ItalyStrap' ),
						'Restaurant'					=> __( 'Restaurant', 'ItalyStrap' ),
						'RoofingContractor'				=> __( 'Roofing Contractor', 'ItalyStrap' ),
						'RVPark'						=> __( 'RV Park', 'ItalyStrap' ),
						'School'						=> __( 'School', 'ItalyStrap' ),
						'SelfStorage'					=> __( 'Self Storage', 'ItalyStrap' ),
						'ShoeStore'						=> __( 'ShoeStore', 'ItalyStrap' ),
						'SkiResort'						=> __( 'Ski Resort', 'ItalyStrap' ),
						'SportingGoodsStore'			=> __( 'Sporting Goods Store', 'ItalyStrap' ),
						'SportsClub'					=> __( 'Sports Club', 'ItalyStrap' ),
						'Store'							=> __( 'Store', 'ItalyStrap' ),
						'TattooParlor'					=> __( 'Tattoo Parlor', 'ItalyStrap' ),
						'Taxi'							=> __( 'Taxi', 'ItalyStrap' ),
						'TennisComplex'					=> __( 'Tennis Complex', 'ItalyStrap' ),
						'TireShop'						=> __( 'Tire Shop', 'ItalyStrap' ),
						'ToyStore'						=> __( 'Toy Store', 'ItalyStrap' ),
						'TravelAgency'					=> __( 'Travel Agency', 'ItalyStrap' ),
						'VeterinaryCare'				=> __( 'Veterinary Care', 'ItalyStrap' ),
						'WholesaleStore'				=> __( 'Wholesale Store', 'ItalyStrap' ),
						'Winery'						=> __( 'Winery', 'ItalyStrap' ),
					),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your company name.
	 */
	'company_name'			=> array(
				'name'		=> __( 'Company name', 'ItalyStrap' ),
				'desc'		=> __( 'Your company name.', 'ItalyStrap' ),
				'id'		=> 'company_name',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * The url of your logo.
	 */
	'logo_url'				=> array(
				'name'		=> __( 'Logo URL', 'ItalyStrap' ),
				'desc'		=> __( 'The url of your logo.', 'ItalyStrap' ),
				'id'		=> 'logo_url',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Check if you want to show the logo in the widget section.
	 */
	'show_logo'				=> array(
				'name'		=> __( 'Show Logo', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show the logo in the widget section.', 'ItalyStrap' ),
				'id'		=> 'show_logo',
				'type'		=> 'checkbox',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'general',
				 ),

	/**
	 * Your street address.
	 */
	'street_address'		=> array(
				'name'		=> __( 'Street Address', 'ItalyStrap' ),
				'desc'		=> __( 'Your street address.', 'ItalyStrap' ),
				'id'		=> 'street_address',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your postal code.
	 */
	'postal_code'			=> array(
				'name'		=> __( 'Zipcode/Postal Code', 'ItalyStrap' ),
				'desc'		=> __( 'Your postal code.', 'ItalyStrap' ),
				'id'		=> 'postal_code',
				'type'		=> 'number',
				'class'		=> 'widefat',
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your city or locality.
	 */
	'locality'				=> array(
				'name'		=> __( 'City/Locality', 'ItalyStrap' ),
				'desc'		=> __( 'Your city or locality.', 'ItalyStrap' ),
				'id'		=> 'locality',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your State / Region.
	 */
	'region'				=> array(
				'name'		=> __( 'State/Region', 'ItalyStrap' ),
				'desc'		=> __( 'Your State / Region.', 'ItalyStrap' ),
				'id'		=> 'region',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your Country.
	 */
	'country'				=> array(
				'name'		=> __( 'Country', 'ItalyStrap' ),
				'desc'		=> __( 'Your Country.', 'ItalyStrap' ),
				'id'		=> 'country',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your telephone number.
	 */
	'tel'					=> array(
				'name'		=> __( 'Telephone number', 'ItalyStrap' ),
				'desc'		=> __( 'Your telephone number.', 'ItalyStrap' ),
				'id'		=> 'tel',
				'type'		=> 'tel',
				'class'		=> 'widefat',
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your mobile number.
	 */
	'mobile'				=> array(
				'name'		=> __( 'Mobile number', 'ItalyStrap' ),
				'desc'		=> __( 'Your mobile number.', 'ItalyStrap' ),
				'id'		=> 'mobile',
				'type'		=> 'tel',
				'class'		=> 'widefat',
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your fax number.
	 */
	'fax'					=> array(
				'name'		=> __( 'Fax number', 'ItalyStrap' ),
				'desc'		=> __( 'Your fax number.', 'ItalyStrap' ),
				'id'		=> 'fax',
				'type'		=> 'tel',
				'class'		=> 'widefat',
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your email.
	 */
	'email'					=> array(
				'name'		=> __( 'Email', 'ItalyStrap' ),
				'desc'		=> __( 'Your email.', 'ItalyStrap' ),
				'id'		=> 'email',
				'type'		=> 'email',
				'class'		=> 'widefat',
				'validate'	=> 'email',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your taxID.
	 */
	'taxID'					=> array(
				'name'		=> __( 'TaxID', 'ItalyStrap' ),
				'desc'		=> __( 'Your taxID.', 'ItalyStrap' ),
				'id'		=> 'taxID',
				'type'		=> 'number',
				'class'		=> 'widefat',
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Your Facebook page url (hidden).
	 */
	'facebook'				=> array(
				'name'		=> __( 'Facebook page (hidden)', 'ItalyStrap' ),
				'desc'		=> __( 'Your Facebook page url (hidden).', 'ItalyStrap' ),
				'id'		=> 'facebook',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'social',
				 ),

	/**
	 * Your twitter page url (hidden).
	 */
	'twitter'				=> array(
				'name'		=> __( 'Twitter page (hidden)', 'ItalyStrap' ),
				'desc'		=> __( 'Your twitter page url (hidden).', 'ItalyStrap' ),
				'id'		=> 'twitter',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'social',
				 ),


	/**
	 * Your googleplus page url (hidden).
	 */
	'googleplus'				=> array(
				'name'		=> __( 'Googleplus page (hidden)', 'ItalyStrap' ),
				'desc'		=> __( 'Your googleplus page url (hidden).', 'ItalyStrap' ),
				'id'		=> 'googleplus',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'social',
				 ),

	/**
	 * Your pinterest page url (hidden).
	 */
	'pinterest'				=> array(
				'name'		=> __( 'Pinterest page (hidden)', 'ItalyStrap' ),
				'desc'		=> __( 'Your pinterest page url (hidden).', 'ItalyStrap' ),
				'id'		=> 'pinterest',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'social',
				 ),

	/**
	 * Your instagram page url (hidden).
	 */
	'instagram'				=> array(
				'name'		=> __( 'Instagram page (hidden)', 'ItalyStrap' ),
				'desc'		=> __( 'Your instagram page url (hidden).', 'ItalyStrap' ),
				'id'		=> 'instagram',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'social',
				 ),

	/**
	 * Your youtube page url (hidden).
	 */
	'youtube'				=> array(
				'name'		=> __( 'YouTube page (hidden)', 'ItalyStrap' ),
				'desc'		=> __( 'Your youtube page url (hidden).', 'ItalyStrap' ),
				'id'		=> 'youtube',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'social',
				 ),

	/**
	 * Your linkedin page url (hidden).
	 */
	'linkedin'				=> array(
				'name'		=> __( 'Linkedin page (hidden)', 'ItalyStrap' ),
				'desc'		=> __( 'Your linkedin page url (hidden).', 'ItalyStrap' ),
				'id'		=> 'linkedin',
				'type'		=> 'url',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_url',
				'section'	=> 'social',
				 ),

);
