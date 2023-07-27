<?php

/**
 * Array definition for vCard default options
 *
 * @package ItalyStrap
 */

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return [
    /**
     * The type of schema.
     */
    'schema'                => ['label'     => __('Select your business', 'italystrap'), 'desc'      => __('Select your kind of activity.', 'italystrap'), 'id'        => 'schema', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'LocalBusiness', 'options'   => ['LocalBusiness'                 => __('Local Business - (Default)', 'italystrap'), 'Organization'                  => __('Organization - (For services and home offices)', 'italystrap'), 'AccountingService'             => __('Accounting Service', 'italystrap'), 'AutoBodyShop'                  => __('Auto Body Shop', 'italystrap'), 'AutoDealer'                    => __('Auto Dealer', 'italystrap'), 'AutoPartsStore'                => __('Auto Parts Store', 'italystrap'), 'AutoRental'                    => __('Auto Rental', 'italystrap'), 'AutoRepair'                    => __('Auto Repair', 'italystrap'), 'AutoWash'                      => __('Auto Wash', 'italystrap'), 'Attorney'                      => __('Attorney', 'italystrap'), 'Bakery'                        => __('Bakery', 'italystrap'), 'BarOrPub'                      => __('Bar Or Pub', 'italystrap'), 'BeautySalon'                   => __('Beauty Salon', 'italystrap'), 'BedAndBreakfast'               => __('Bed &amp; Breakfast', 'italystrap'), 'BikeStore'                     => __('Bicycle Store', 'italystrap'), 'BookStore'                     => __('Book Store', 'italystrap'), 'CafeOrCoffeeShop'              => __('Cafe Or Coffee Shop', 'italystrap'), 'ChildCare'                     => __('Child Care', 'italystrap'), 'ClothingStore'                 => __('Clothing Store', 'italystrap'), 'ComputerStore'                 => __('Computer Store', 'italystrap'), 'DaySpa'                        => __('Day Spa', 'italystrap'), 'Dentist'                       => __('Dentist', 'italystrap'), 'DryCleaningOrLaundry'          => __('Dry Cleaning Or Laundry', 'italystrap'), 'Electrician'                   => __('Electrician', 'italystrap'), 'ElectronicsStore'              => __('Electronics Store', 'italystrap'), 'EmergencyService'              => __('Emergency Service', 'italystrap'), 'EntertainmentBusiness'         => __('Entertainment Business', 'italystrap'), 'EventVenue'                    => __('Event Venue', 'italystrap'), 'ExerciseGym'                   => __('Exercise Gym', 'italystrap'), 'FinancialService'              => __('Financial Service', 'italystrap'), 'Florist'                       => __('Florist', 'italystrap'), 'FurnitureStore'                => __('Furniture Store', 'italystrap'), 'FoodEstablishment'             => __('Food Establishment', 'italystrap'), 'GardenStore'                   => __('Garden Store', 'italystrap'), 'GeneralContractor'             => __('General Contractor', 'italystrap'), 'GolfCourse'                    => __('Golf Course', 'italystrap'), 'HairSalon'                     => __('Hair Salon', 'italystrap'), 'HardwareStore'                 => __('Hardware Store', 'italystrap'), 'HealthAndBeautyBusiness'       => __('Health And Beauty Business', 'italystrap'), 'HomeAndConstructionBusiness'   => __('Home And Construction Business', 'italystrap'), 'HobbyShop'                     => __('Hobby Shop', 'italystrap'), 'HomeGoodsStore'                => __('Home Goods Store', 'italystrap'), 'Hospital'                      => __('Hospital', 'italystrap'), 'Hotel'                         => __('Hotel', 'italystrap'), 'HousePainter'                  => __('House Painter', 'italystrap'), 'HVACBusiness'                  => __('HVAC Business', 'italystrap'), 'InsuranceAgency'               => __('Insurance Agency', 'italystrap'), 'JewelryStore'                  => __('Jewelry Store', 'italystrap'), 'LiquorStore'                   => __('Liquor Store', 'italystrap'), 'Locksmith'                     => __('Locksmith', 'italystrap'), 'LodgingBusiness'               => __('Lodging Business', 'italystrap'), 'MedicalClinic'                 => __('Medical Clinic', 'italystrap'), 'MensClothingStore'             => __('Mens Clothing Store', 'italystrap'), 'MobilePhoneStore'              => __('Mobile Phone Store', 'italystrap'), 'Motel'                         => __('Motel', 'italystrap'), 'MotorcycleDealer'              => __('Motorcycle Dealer', 'italystrap'), 'MotorcycleRepair'              => __('Motorcycle Repair', 'italystrap'), 'MovingCompany'                 => __('Moving Company', 'italystrap'), 'MusicStore'                    => __('Music Store', 'italystrap'), 'NailSalon'                     => __('Nail Salon', 'italystrap'), 'NightClub'                     => __('Night Club', 'italystrap'), 'Notary'                        => __('Notary Public', 'italystrap'), 'OfficeEquipmentStore'          => __('Office Equipment Store', 'italystrap'), 'Optician'                      => __('Optician', 'italystrap'), 'PetStore'                      => __('PetStore', 'italystrap'), 'Physician'                     => __('Physician', 'italystrap'), 'Plumber'                       => __('Plumber', 'italystrap'), 'ProfessionalService'           => __('Professional Service', 'italystrap'), 'RealEstateAgent'               => __('Real Estate Agent', 'italystrap'), 'Residence'                     => __('Residence', 'italystrap'), 'Restaurant'                    => __('Restaurant', 'italystrap'), 'RoofingContractor'             => __('Roofing Contractor', 'italystrap'), 'RVPark'                        => __('RV Park', 'italystrap'), 'School'                        => __('School', 'italystrap'), 'SelfStorage'                   => __('Self Storage', 'italystrap'), 'ShoeStore'                     => __('ShoeStore', 'italystrap'), 'SkiResort'                     => __('Ski Resort', 'italystrap'), 'SportingGoodsStore'            => __('Sporting Goods Store', 'italystrap'), 'SportsClub'                    => __('Sports Club', 'italystrap'), 'Store'                         => __('Store', 'italystrap'), 'TattooParlor'                  => __('Tattoo Parlor', 'italystrap'), 'Taxi'                          => __('Taxi', 'italystrap'), 'TennisComplex'                 => __('Tennis Complex', 'italystrap'), 'TireShop'                      => __('Tire Shop', 'italystrap'), 'ToyStore'                      => __('Toy Store', 'italystrap'), 'TravelAgency'                  => __('Travel Agency', 'italystrap'), 'VeterinaryCare'                => __('Veterinary Care', 'italystrap'), 'WholesaleStore'                => __('Wholesale Store', 'italystrap'), 'Winery'                        => __('Winery', 'italystrap')], 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * CSS class for the container of this widget.
     */
    'container_class'           => ['label'     => __('Container CSS class', 'italystrap'), 'desc'      => __('CSS class for the container of this widget.', 'italystrap'), 'id'        => 'container_class', 'type'      => 'text', 'class'     => 'widefat container_class', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Your company name.
     */
    'company_name'          => ['label'     => __('Company name', 'italystrap'), 'desc'      => __('Your company name.', 'italystrap'), 'id'        => 'company_name', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Check if you want to show the logo in the widget section.
     */
    'show_logo'             => [
        'label'     => __('Show Logo image', 'italystrap'),
        'desc'      => __('Check if you want to show the logo in the widget section.', 'italystrap'),
        'id'        => 'show_logo',
        'type'      => 'checkbox',
        // 'class'      => 'widefat',
        'default'   => '',
        'sanitize'  => 'esc_attr',
        'section'   => 'logo',
    ],
    /**
     * Logo size.
     */
    'logo_size'         => [
        'label'     => __('Logo size', 'italystrap'),
        'desc'      => __('Select the thumbnail size to display in posts list.', 'italystrap'),
        'id'        => 'logo_size',
        'type'      => 'select',
        'class'     => 'widefat logo_size',
        'default'   => 'thumbnail',
        'options'   => ( ( is_admin() ) ? \ItalyStrap\Core\get_image_size_array() : null ),
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'logo',
    ],
    /**
     * Logo class.
     */
    'logo_class'                => ['label'     => __('Logo css class', 'italystrap'), 'desc'      => __('Enter logo css class.', 'italystrap'), 'id'        => 'logo_class', 'type'      => 'text', 'class'     => 'widefat logo_class', 'class-p'   => 'logo_class', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'logo'],
    /**
     * The url of your logo.
     */
    'logo_url'              => ['label'     => __('Logo URL (DEPRECATED)', 'italystrap'), 'desc'      => __('The url of your logo. (DEPRECATED)', 'italystrap'), 'id'        => 'logo_url', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'logo'],
    /**
     * The ID of your logo.
     */
    'logo_id'               => [
        'label'     => __('Logo image ID', 'italystrap'),
        'desc'      => __('Add your logo image.', 'italystrap'),
        'id'        => 'logo_id',
        'type'      => 'media',
        'class'     => 'widefat ids',
        //      'class-p'   => 'hidden',
        'default'   => '',
        'validate'  => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'logo',
    ],
    /**
     * Your street address.
     */
    'street_address'        => ['label'     => __('Street Address', 'italystrap'), 'desc'      => __('Your street address.', 'italystrap'), 'id'        => 'street_address', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your postal code.
     */
    'postal_code'           => ['label'     => __('Zipcode/Postal Code', 'italystrap'), 'desc'      => __('Your postal code.', 'italystrap'), 'id'        => 'postal_code', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'is_numeric', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your city or locality.
     */
    'locality'              => ['label'     => __('City/Locality', 'italystrap'), 'desc'      => __('Your city or locality.', 'italystrap'), 'id'        => 'locality', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your State / Region.
     */
    'region'                => ['label'     => __('State/Region', 'italystrap'), 'desc'      => __('Your State / Region.', 'italystrap'), 'id'        => 'region', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your Country.
     */
    'country'               => ['label'     => __('Country', 'italystrap'), 'desc'      => __('Your Country.', 'italystrap'), 'id'        => 'country', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your telephone number.
     */
    'tel'                   => ['label'     => __('Telephone number', 'italystrap'), 'desc'      => __('Your telephone number.', 'italystrap'), 'id'        => 'tel', 'type'      => 'tel', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'is_numeric', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your mobile number.
     */
    'mobile'                => ['label'     => __('Mobile number', 'italystrap'), 'desc'      => __('Your mobile number.', 'italystrap'), 'id'        => 'mobile', 'type'      => 'tel', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'is_numeric', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your fax number.
     */
    'fax'                   => ['label'     => __('Fax number', 'italystrap'), 'desc'      => __('Your fax number.', 'italystrap'), 'id'        => 'fax', 'type'      => 'tel', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'is_numeric', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your email.
     */
    'email'                 => [
        'label'     => __('Email', 'italystrap'),
        'desc'      => __('Your email.', 'italystrap'),
        'id'        => 'email',
        'type'      => 'email',
        'class'     => 'widefat',
        'default'   => '',
        // 'validate'   => 'email',
        'sanitize'  => 'is_email',
        'section'   => 'info',
    ],
    /**
     * Your taxID.
     */
    'taxID'                 => ['label'     => __('TaxID', 'italystrap'), 'desc'      => __('Your taxID.', 'italystrap'), 'id'        => 'taxID', 'type'      => 'text', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'is_numeric', 'sanitize'  => 'sanitize_text_field', 'section'   => 'info'],
    /**
     * Your Facebook page url (hidden).
     */
    'facebook'              => ['label'     => __('Facebook page (hidden)', 'italystrap'), 'desc'      => __('Your Facebook page url (hidden).', 'italystrap'), 'id'        => 'facebook', 'type'      => 'url', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'strip_tags|esc_url', 'section'   => 'social'],
    /**
     * Your twitter page url (hidden).
     */
    'twitter'               => ['label'     => __('Twitter page (hidden)', 'italystrap'), 'desc'      => __('Your twitter page url (hidden).', 'italystrap'), 'id'        => 'twitter', 'type'      => 'url', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'strip_tags|esc_url', 'section'   => 'social'],
    /**
     * Your googleplus page url (hidden).
     */
    'googleplus'                => ['label'     => __('Googleplus page (hidden)', 'italystrap'), 'desc'      => __('Your googleplus page url (hidden).', 'italystrap'), 'id'        => 'googleplus', 'type'      => 'url', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'strip_tags|esc_url', 'section'   => 'social'],
    /**
     * Your pinterest page url (hidden).
     */
    'pinterest'             => ['label'     => __('Pinterest page (hidden)', 'italystrap'), 'desc'      => __('Your pinterest page url (hidden).', 'italystrap'), 'id'        => 'pinterest', 'type'      => 'url', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'strip_tags|esc_url', 'section'   => 'social'],
    /**
     * Your instagram page url (hidden).
     */
    'instagram'             => ['label'     => __('Instagram page (hidden)', 'italystrap'), 'desc'      => __('Your instagram page url (hidden).', 'italystrap'), 'id'        => 'instagram', 'type'      => 'url', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'strip_tags|esc_url', 'section'   => 'social'],
    /**
     * Your youtube page url (hidden).
     */
    'youtube'               => ['label'     => __('YouTube page (hidden)', 'italystrap'), 'desc'      => __('Your youtube page url (hidden).', 'italystrap'), 'id'        => 'youtube', 'type'      => 'url', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'strip_tags|esc_url', 'section'   => 'social'],
    /**
     * Your linkedin page url (hidden).
     */
    'linkedin'              => ['label'     => __('Linkedin page (hidden)', 'italystrap'), 'desc'      => __('Your linkedin page url (hidden).', 'italystrap'), 'id'        => 'linkedin', 'type'      => 'url', 'class'     => 'widefat', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'strip_tags|esc_url', 'section'   => 'social'],
];
