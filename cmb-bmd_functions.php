<?php
/**
 * Include and setup custom metaboxes and fields for the stores custom post type.
 *
 * @category store locator evolution
 * @author   Benoît Faure fork of cmb
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_happystore_post_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_happystore_post_metaboxes( array $meta_boxes ) {
    // load options
    $option = get_option('b_map_direction_settings');
    if($option['bmd_enable_map']==1){
    $pages_array = array('stores',);
    }
    if($option['bmd_enable_map']==2){
        $pages_array = array('post',);
    }
    if($option['bmd_enable_map']==3){
        $pages_array = array('page',);
    }
    if($option['bmd_enable_map']==4){
        $pages_array = array('stores','post');
    }
    if($option['bmd_enable_map']==5){
        $pages_array = array('post','page');
    }
    if($option['bmd_enable_map']==6){
        $pages_array = array('stores', 'page');
    }
    if($option['bmd_enable_map']==7){
        $pages_array = array('stores','post','page');
    }
	// Start with an underscore to hide fields from custom fields list
	$prefix = 'store_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['pe_post_map_metabox'] = array(
		'id'         => 'post_pe_metabox',
		'title'      => __( 'Map Direction by Brozzme', 'cmb' ),
		'pages'      => $pages_array, // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(

            array(
                'name' => 'Adresse',
                'desc' => __('Move the marker for the precise position','cmb'),
                'id' => $prefix . 'adresse',
                'type' => 'pw_map',
                'sanitization_cb' => 'pw_map_sanitise',
            ),

            array(
                'name'         => __( 'Marker', 'cmb' ),
                'desc'         => __( 'Upload or add file.', 'cmb' ),
                'id'           => $prefix . 'url_marker',
                'type'         => 'file_list',
                'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
            ),
            array(
                'name'    => 'Zoom',
                'desc'    => __( 'Select zoom range','cmb' ),
                'id'      => $prefix . 'zoom',
                'type'    => 'select',
                'options' => array(
                    '6' =>'6',
                    '7' =>'7',
                    '8' =>'8',
                    '9' =>'9',
                    '10' =>'10',
                    '11' =>'11',
                    '12' =>'12',
                    '13' =>'13',
                    '14' =>'14',
                    '15' =>'15',
                    '16' =>'16',
                    '17' =>'17',
                    '18' =>'18',
                ),
                'default' => '12',
            )
			
		),


    );


	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'cmb/init.php';

}
// https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Adding-your-own-field-types
// add google map fonctionnality
// add to css
/**
 * Render field
 */
function pw_map_field( $field, $meta ) {
    wp_enqueue_script( 'pw_google_maps_api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places', array('jquery'), '', true );
    wp_enqueue_script( 'pw_google_maps_init', plugin_dir_url( __FILE__ ) .'js/script_pw_map.js', array( 'pw_google_maps_api' ), true );
    wp_enqueue_style( 'pw_google_maps_css', plugin_dir_url( __FILE__ ) .'css/style_pw_map.css', array(), null );

    $option = get_option('b_map_direction_settings');

    // localize script
    $options_args = array(
        'bmd_origine_adresse_latitude'=> $option['bmd_origine_adresse']['latitude'],
        'bmd_origine_adresse_longitude' => $option['bmd_origine_adresse']['longitude']
    );
    wp_localize_script('pw_google_maps_init', 'bmdOptions', $options_args);

    wp_enqueue_script( 'pw_google_maps_init' );
    //wp_enqueue_style_script('');

    echo '<input type="text" class="map-search" id="' . $field['id'] . '" />';
    echo '<div class="map pam-admin cmb-type-pw_map"></div>';
    echo '<input type="text_small" class="latitude" name="' . $field['id'] . '[latitude]" value="' . ( isset( $meta['latitude'] ) ? $meta['latitude'] : '' ) . '" />';
    echo '<input type="text_small" class="longitude" name="' . $field['id'] . '[longitude]" value="' . ( isset( $meta['longitude'] ) ? $meta['longitude'] : '' ) . '" />';
    echo '<input type="text_small" class="rtzoom" name="' . $field['id'] . '_zoom_real_time" value="" />';
    echo '<input type="text" id="' . $field['id'] . '[completed_adresse]" class="completed_adresse" name="' . $field['id'] . '[completed_adresse]" value="' . ( isset( $meta['completed_adresse'] ) ? $meta['completed_adresse'] : '' ) . '"/>';

    if ( ! empty( $field['desc'] ) ) echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
}
add_filter( 'cmb_render_pw_map', 'pw_map_field', 10, 2 );

/**
 * Split latitude/longitude values into two meta fields
 */
function pw_map_sanitise( $meta_value, $field ) {
    $latitude = $meta_value['latitude'];
    $longitude = $meta_value['longitude'];

    if ( ! empty( $latitude ) ) {
        update_post_meta( get_the_ID(), $field['id'] . '_latitude', $latitude );
    }

    if ( ! empty( $longitude ) ) {
        update_post_meta( get_the_ID(), $field['id'] . '_longitude', $longitude );
    }

    return $meta_value;
}


