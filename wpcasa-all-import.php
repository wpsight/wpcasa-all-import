<?php
/**
 * WPCasa All Import
 *
 * @package           WPCasaAllImport
 * @author            WPSight
 * @copyright         2024 Kybernetik Services GmbH
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WPCasa All Import
 * Plugin URI:        https://wpcasa.com/downloads/wpcasa-all-import
 * Description:       Add-on for the WP All Import plugin to import any XML or CSV File to WPCasa
 * Version:           1.1.1
 * Requires at least: 4.0
 * Requires PHP:      5.6
 * Requires Plugins:  wpcasa
 * Author:            WPSight
 * Author URI:        https://wpcasa.com
 * Text Domain:       wpcasa-all-import
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

######################################################################

// Include WP All Import Rapid library
include( 'rapid-addon.php' );

// Create and name new add-on
$all_import = new WPSight_RapidAddon( 'WPCasa All Import', 'wpcasa_all_import' );

// Set up central import fields array
$import_fields = array();

// Get WPCasa options
$wpsight_options = get_option( 'wpcasa' );

######################################################################

// Create custom image import section
$all_import->import_images( 'wpsight_all_import_gallery', 'WPCasa Listing Images' );

######################################################################

// Set up details array and add listing ID
$wpsight_details = array( 'listing_id' => array( 'label' => 'Listing ID' ) );

// Loop through options and grab details
foreach( $wpsight_options as $key => $option ) {
	
	if( false !== strpos( $key, 'details_' ) )
		$wpsight_details[ $key ] = $option;
	
}

// Loop through details and set fields
foreach( $wpsight_details as $key => $detail ) {
	
	// Use underscore for post meta
	$key = '_' . $key;
	
	$label = $detail['label'];
	
	// Optionally add unit to detail label	
	if( isset( $detail['unit'] ) && ! empty( $detail['unit'] ) )
		$label .= ' (' . $detail['unit'] . ')';
	
	// Create field for detail
	$all_import->add_field( $key, $label, 'text' );
	
	// Add field to central import fields
	$import_fields[] = $key;
	
}

######################################################################

// Set up periods array
$wpsight_periods = array();

// Loop through options to grab rental periods
foreach( $wpsight_options as $key => $option ) {
	
	if( false !== strpos( $key, 'rental_period_' ) )
		$wpsight_periods[ $key ] = $option;
	
}

// Add section title
$all_import->add_title( 'Listing Price' );

// Add price field with settings for offer and rental period
$all_import->add_options(
	
		// Add price field
        $all_import->add_field( '_price', 'Price', 'text', null, 'In currency defined in WPCasa settings' ),
        'Price Settings',
        array(
	        	// Add price offer (sale, rent etc.)
	        	$all_import->add_field( '_price_offer', 'Offer', 'radio',
					array(
						''		=> 'None',
						'sale'	=> 'Sale',
						'rent'	=> 'Rent'
					)
				),
				
				// Add rental period
				$all_import->add_field( '_price_period', 'Period', 'radio',  array_merge( array( '' => 'None' ), $wpsight_periods ) ),
				
				// Add custom price before text (for upcoming WPCasa version)
                // $all_import->add_field( '_price_before', 'Price Before', 'text' ),
                
                // Add custom price after text (for upcoming WPCasa version)
                // $all_import->add_field( '_price_after', 'Price After', 'text' ),
        )
);

// Add fields of this section to import fields
$import_fields[] = '_price';
$import_fields[] = '_price_offer';
$import_fields[] = '_price_period';
// $import_fields[] = '_price_before';
// $import_fields[] = '_price_after';

######################################################################

// Add section title
$all_import->add_title( 'Listing Location' );

// Add location fields with address and geo code settings
$all_import->add_field(
	'location_settings',
	'Search Method',
	'radio', 
	array(
		'search_by_address' => array(
			'Search by Address',
			$all_import->add_options( 
				$all_import->add_field(
					'_map_address',
					'Address',
					'text'
				),
				'Google Geocode API Settings', 
				array(
					$all_import->add_field(
						'address_geocode',
						null,
						'radio',
						array(
							'address_no_key' => array(
								'No API Key',
								'Limited number of requests'
							),
							'address_google_developers' => array(
								'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key" target="_blank">Get free API key</a>',
								$all_import->add_field(
									'address_google_developers_api_key', 
									'API Key', 
									'text'
								),
								'Up to 2,500 requests per day and 5 requests per second'
							),
							'address_google_for_work' => array(
								'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business" target="_blank">Sign up for Google for Work</a>',
								$all_import->add_field(
									'address_google_for_work_client_id', 
									'Google for Work Client ID', 
									'text'
								), 
								$all_import->add_field(
									'address_google_for_work_digital_signature', 
									'Google for Work Digital Signature', 
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Request Method options array
					), // end Request Method nested radio field

				) // end Google Geocode API Settings fields
			) // end Google Gecode API Settings options panel
		), // end Search by Address radio field
		'search_by_coordinates' => array(
			'Search by Coordinates',
			$all_import->add_field(
				'_geolocation_lat', 
				'Latitude', 
				'text', 
				null, 
				'Example: 34.0194543'
			),
			$all_import->add_options( 
				$all_import->add_field(
					'_geolocation_long', 
					'Longitude', 
					'text', 
					null, 
					'Example: -118.4911912'
				), 
				'Google Geocode API Settings', 
				array(
					$all_import->add_field(
						'coord_geocode',
						null,
						'radio',
						array(
							'coord_no_key' => array(
								'No API Key',
								'Limited number of requests.'
							),
							'coord_google_developers' => array(
								'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key" target="_blank">Get free API key</a>',
								$all_import->add_field(
									'coord_google_developers_api_key', 
									'API Key', 
									'text'
								),
								'Up to 2500 requests per day and 5 requests per second.'
							),
							'coord_google_for_work' => array(
								'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business" target="_blank">Sign up for Google for Work</a>',
								$all_import->add_field(
									'coord_google_for_work_client_id', 
									'Google for Work Client ID', 
									'text'
								), 
								$all_import->add_field(
									'coord_google_for_work_digital_signature', 
									'Google for Work Digital Signature', 
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Geocode API options array
					), // end Geocode nested radio field
					
				) // end Geocode settings
			) // end coordinates Option panel
		) // end Search by Coordinates radio field
	) // end Location radio field
);

// Add additional map settings
$all_import->add_options( null, 'Map Settings', array(

	$all_import->add_field( '_map_note', 'Public Note', 'text', null, 'e.g. "Location is not the exact address of the listing."' ),
	
	$all_import->add_field( '_map_secret', 'Secret Note', 'text', null, 'Will not be displayed on the website (e.g. complete address)' ),
	
	$all_import->add_field( '_map_hide', 'Hide Map', 'radio', 
		array(
			''		=> 'No',
			'on'	=> 'Yes'
		)
	),

) );

// Add fields of this section to import fields
$import_fields[] = '_map_note';
$import_fields[] = '_map_secret';
$import_fields[] = '_map_hide';

######################################################################

$agent_fields = array();

// Add section title
$all_import->add_title( 'Listing Agent' );

// Get all site users
$all_users = get_users();

// Set up some arrays
$users = array();
$_users = array();

// Loop through all users
foreach( $all_users as $user ) {
	
	// Save array( ID => $user->display_name )
	$users[ $user->ID ]		= $user->display_name;
	
	// Save array( ID => $user->data )
	$_users[ $user->ID ]	= $user->data;
}

// Add agent fields
$all_import->add_field(
	'agent_settings',
	'Agent Settings',
	'radio', 
	array(
		'assign_agent' => array(
			'Assign Existing Agent',
			$all_import->add_field( 'agent', 'Agent', 'radio', $users ),
		),
		'add_agent_info' => array(
			'Add Agent Information',
			$all_import->add_field( '_agent_name', 'Name', 'text' ),
			$all_import->add_field( '_agent_company', 'Company', 'text' ),
			$all_import->add_field( '_agent_description', 'Description', 'text' ),
			$all_import->add_field( '_agent_phone', 'Phone', 'text' ),
			$all_import->add_field( '_agent_website', 'Website', 'text' ),
			$all_import->add_field( '_agent_twitter', 'Twitter', 'text' ),
			$all_import->add_field( '_agent_facebook', 'Facebook', 'text' ),
			$all_import->add_field( '_agent_logo', 'Logo', 'image' ),
		)
	)
);

// Add fields of this section to import fields
$agent_fields[] = '_agent_name';
$agent_fields[] = '_agent_company';
$agent_fields[] = '_agent_description';
$agent_fields[] = '_agent_phone';
$agent_fields[] = '_agent_website';
$agent_fields[] = '_agent_twitter';
$agent_fields[] = '_agent_facebook';

######################################################################

// Set up labels array
$wpsight_labels = array();

// Set up labels field
$all_import_labels = null;

// Exclude some label options
$exclude_labels = array( 'labels_format', 'labels_type' );

// Loop through options to grab labels
foreach( $wpsight_options as $key => $option ) {
	
	if( false !== strpos( $key, 'labels_' ) && ! in_array( $key, $exclude_labels ) )
		$wpsight_labels[ $key ] = $option;
	
}

$listing_attributes = array(

	$all_import->add_field( '_listing_not_available', 'Availability', 'radio', array(
		''		=> 'Item available',
		'on'	=> 'Item not available'
	), 'An item is not available when it has been sold or rented but should still be displayed on the website.' ),
	
	// $all_import_labels,
	
	$all_import->add_field( '_listing_sticky', 'Sticky', 'radio', array(
		''		=> 'No',
		'on'	=> 'Yes',
	), sprintf( 'Only available when %s add-on is activated.', 'WPCasa Featured Listings' ) ),
	
	$all_import->add_field( '_listing_featured', 'Featured', 'radio', array(
		''		=> 'No',
		'on'	=> 'Yes',
	), sprintf( 'Only available when %s add-on is activated.', 'WPCasa Featured Listings' ) ),

);

// Add labels field when labels exist in options
if( ! empty( $wpsight_labels ) ) {
	
	// Add none option to array
	$wpsight_labels = array_merge( array( '' => 'None' ), $wpsight_labels );
	
	// Create labels field
	$listing_attributes[] = $all_import->add_field(
		'_listing_label', 'Label', 'radio', $wpsight_labels,
		sprintf( 'Only available when %s add-on is activated.', 'WPCasa Listing Labels' )
	);

}

// Create listing attributes section
$all_import->add_options( null, 'Listing Attributes', $listing_attributes );

######################################################################

// Set import function to handle added fields
$all_import->set_import_function( 'wpsight_all_import_function' );

######################################################################

// Disable default images import section
$all_import->disable_default_images();

######################################################################
		
// Display admin notice if WP All Import or WPCasa is not active
$all_import->admin_notice( 'The WPCasa All Import add-on requires <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">WP All Import</a> and <a href="http://wordpress.org/plugins/wpcasa" target="_blank">WPCasa</a>.', array(
	'plugins' => array( 'wpcasa/wpcasa.php' ),
) );

######################################################################

// Finally run our add-on
$all_import->run( array(
	'post_types'	=> array( 'listing' ),
	'plugins'		=> array( 'wpcasa/wpcasa.php' ),
	
) );

######################################################################

/**
 *	wpsight_all_import_function()
 *	
 *	Handle import of all the fields.
 *
 *	@param	integer	$post_id
 *	@param	array	$data
 *	@param	array	$import_options
 *	@uses	$all_import->can_update_meta()
 *	@uses	$all_import->can_update_image()
 *	@uses	$all_import->log()
 *	@uses	update_post_meta()
 *	@uses	delete_post_meta()
 *	@uses	wp_get_attachment_url()
 *	@uses	rawurlencode()
 *	@uses	curl_setopt()
 *	@uses	curl_exec()
 *	@uses	curl_close()
 *	@uses	json_decode()
 *
 *	@since 1.0.0
 */
function wpsight_all_import_function( $post_id, $data, $import_options ) {
	global $all_import, $import_fields, $agent_fields, $_users;

	// Update our central $import_fields array

	foreach( $import_fields as $field ) {

		if ( $all_import->can_update_meta( $field, $import_options ) ) {
			
			update_post_meta( $post_id, $field, $data[ $field ] );
			$all_import->log( sprintf( '- Updated field: <em>%s</em>', $field ) );
		
		}

	}
	
	// Clear image fields

	$image_fields = array(
		'_gallery',
		'_gallery_2',
		'_gallery_plans',
		'_agent_logo',
		'_agent_logo_id',
	);

	if ( $all_import->can_update_image( $import_options ) ) {

		foreach ( $image_fields as $field )
	    	delete_post_meta( $post_id, $field );

	}
	
	// Assign existing agent
	
	if ( $data['agent_settings'] == 'assign_agent' && ! empty( $data['agent'] ) ) {
		
		// Get data from existing user
		
		$user_id = $data['agent'];
	
		$agent_fields = array(
			'_agent_name'			=> $_users[ $user_id ]->display_name,
			'_agent_company'		=> get_user_meta( $user_id, 'company', true ),
			'_agent_phone'			=> get_user_meta( $user_id, 'phone', true ),
			'_agent_description'	=> get_user_meta( $user_id, 'description', true ),
			'_agent_website'		=> $_users[ $user_id ]->user_url,
			'_agent_twitter'		=> get_user_meta( $user_id, 'twitter', true ),
			'_agent_facebook'		=> get_user_meta( $user_id, 'facebook', true ),
			'_agent_logo'			=> get_user_meta( $user_id, 'agent_logo', true ),
			'_agent_logo_id'		=> get_user_meta( $user_id, 'agent_logo_id', true ),
		);
		
		// Loop through agent fields and set user values
		
		foreach( $agent_fields as $field => $value ) {
		
			if ( $all_import->can_update_meta( $field, $import_options ) ) {
				
				update_post_meta( $post_id, $field, $value );
				$all_import->log( sprintf( '- Updated field: <em>%s</em> with user information of %s', $field, $_users[ $user_id ]->display_name ) );
			
			}
		
		}
		
		// Set post author
		wp_update_post( array( 'ID' => $post_id, 'post_author' => $user_id ) );
		$all_import->log( sprintf( '- Updated author: %s', $_users[ $user_id ]->display_name ) );
	
	// Add agent information
	
	} else {
		
		// Update our central $agent_fields array
		
		foreach( $agent_fields as $field ) {
		
			if ( $all_import->can_update_meta( $field, $import_options ) ) {
				
				update_post_meta( $post_id, $field, $data[ $field ] );
				$all_import->log( sprintf( '- Updated agent field: <em>%s</em>', $field ) );
			
			}
		
		}
		
		// Update agent logo
		
		$field = '_agent_logo';

		if ( $all_import->can_update_image( $import_options ) && $all_import->can_update_meta( $field, $import_options ) ) {
		
			$image_url = wp_get_attachment_url( $data[ $field ]['attachment_id'] );
		
			update_post_meta( $post_id, $field, $image_url );
			update_post_meta( $post_id, $field . '_id', $data[ $field ]['attachment_id'] );
			
			$all_import->log( sprintf( '- Updated agent logo: <em>%s</em>', $field ) );
		
		}
		
		// Get site admin
		$admin_email = get_option( 'admin_email' );
		$user = get_user_by( 'email', $admin_email );
		
		// Set post author
		if( is_object( $user ) )
			wp_update_post( array( 'ID' => $post_id, 'post_author' => $user->ID ) );
		
	}
	
	// Update listing location

	$field   = '_map_address';	
	$address = $data[ $field ];
	
	$lat  = $data['_geolocation_lat'];		
	$long = $data['_geolocation_long'];
	
	// Build search query

	if ( $data['location_settings'] == 'search_by_address' ) {	
		$search = ( ! empty( $address ) ? 'address=' . rawurlencode( $address ) : null );	
	} else {	
		$search = ( ! empty( $lat ) && ! empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );	
	}
	
	// Build API key

	if ( $data['location_settings'] == 'search_by_address' ) {
	
		if ( $data['address_geocode'] == 'address_google_developers' && ! empty( $data['address_google_developers_api_key'] ) ) {	    
	        $api_key = '&key=' . $data['address_google_developers_api_key'];	    
	    } elseif ( $data['address_geocode'] == 'address_google_for_work' && ! empty( $data['address_google_for_work_client_id'] ) && ! empty( $data['address_google_for_work_signature'] ) ) {	        
	        $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];	
	    }
	
	} else {
	
		if ( $data['coord_geocode'] == 'coord_google_developers' && ! empty( $data['coord_google_developers_api_key'] ) ) {	    
	        $api_key = '&key=' . $data['coord_google_developers_api_key'];	    
	    } elseif ( $data['coord_geocode'] == 'coord_google_for_work' && ! empty( $data['coord_google_for_work_client_id'] ) && ! empty( $data['coord_google_for_work_signature'] ) ) {	        
	        $api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];	
	    }
	
	}
	
	// If all fields are updateable and $search has a value

	if ( $all_import->can_update_meta( $field, $import_options ) && $all_import->can_update_meta( '_map_address', $import_options ) && ! empty ( $search ) ) {
	    
	    // Build $request_url for API call
	    $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;

		$response = wp_remote_get( $request_url );

	    // Parse API response
	    if ( ! empty( $response ) ) {
			
			$details = json_decode( wp_remote_retrieve_body( $response ), true );
	
	        $address_data = array();
	
			foreach( $details['results'][0]['address_components'] as $type ) {
	
				// Parse Google Maps output into an array we can use
				$address_data[ $type['types'][0] ] = $type['long_name'];
	
			}
	
			$lat		= $details['results'][0]['geometry']['location']['lat'];		
			$long		= $details['results'][0]['geometry']['location']['lng'];		
			$address	= $address_data['street_number'] . ' ' . $address_data['route'];		
			$city		= $address_data['locality'];
			$country	= $address_data['country'];
			$zip		= $address_data['postal_code'];		
			$state		= $address_data['administrative_area_level_1'];
			$county		= $address_data['administrative_area_level_2'];
			
			// Log some warnings if elements are empty
			
			if ( empty( $zip ) ) {		
			    $all_import->log( '<b>WARNING:</b> Google Maps has not returned a Postal Code for this listing location.' );
			}
			
			if ( empty( $country ) ) {
			    $all_import->log( '<b>WARNING:</b> Google Maps has not returned a Country for this listing location.' );
			}
			
			if ( empty( $city ) ) {
			    $all_import->log( '<b>WARNING:</b> Google Maps has not returned a City for this listing location.' );
			}
			
			if ( empty( $address_data['street_number'] ) ) {
			    $all_import->log( '<b>WARNING:</b> Google Maps has not returned a Street Number for this listing location.' );
			}
			
			if ( empty( $address_data['route'] ) ) {
			    $all_import->log( '<b>WARNING:</b> Google Maps has not returned a Street Name for this listing location.' );
			}
	
	    }
	    
	}
	
	// Update location fields
	$fields = array(
		'_map_address'				=> $address,
		'_geolocation_lat'			=> $lat,
		'_geolocation_long'			=> $long,
		'_geolocation_city'			=> $city,
		'_geolocation_country_long'	=> $country,
		'_geolocation_postcode'		=> $zip,
		'_geolocation_state_long'	=> $state
	);
	
	// Loop through location fields and update
	foreach ( $fields as $key => $value ) {
		
		if ( $all_import->can_update_meta( $key, $import_options ) ) {

			update_post_meta( $post_id, $key, $value );
			$all_import->log( sprintf( '- Updated location field: <em>%s</em>', $key ) );
		
		}

	}
    
}

######################################################################

/**
 *	wpsight_all_import_gallery()
 *	
 *	Import listing image gallery.
 *
 *	@param	integer	$post_id
 *	@param	integer	$attachment_id
 *	@param	string	$image_filepath
 *	@param	array	$import_options
 *	@uses	get_post_meta()
 *	@uses	wp_get_attachment_url()
 *	@uses	update_post_meta()
 *
 *	@since 1.0.0
 */
function wpsight_all_import_gallery( $post_id, $attachment_id, $image_filepath, $import_options ) {
	global $all_import;

	// Grab current gallery images
	$current_images = get_post_meta( $post_id, '_gallery', true );

	$images_array = array();

	// Add current images to array
	foreach ( $current_images as $id => $url )
		$images_array[ $id ] = $url;

	// Get URL of import image
	$image_url = wp_get_attachment_url( $attachment_id );
	
	// Add import image to array
	$images_array[ $attachment_id ] = $image_url;

	// Update gallery with all images
	update_post_meta( $post_id, '_gallery', $images_array );	
	$all_import->log( '- Updated image gallery' );
    
}
