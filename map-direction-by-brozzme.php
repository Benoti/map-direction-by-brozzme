<?php
/**
 * Plugin Name: Map direction by Brozzme
 * Plugin URL: http://brozzme.com/map-direction/
 * Description: store locator with direction powered by google api v3
 * Version: 1.0.0
 * Author: Benoît Faure
 * Author URL: http://brozzme.com
 * Github: https://github.com/Benoti/
 *
 * Created by PhpStorm.
 * User: benoti
 * Date: 12/02/15
 * Time: 14:16
 *
 * settings options created : b_map_direction_settings
 */
ini_set('display_errors', 1);
defined( 'ABSPATH' ) OR exit;

(@__DIR__ == '__DIR__') && define('__DIR__', realpath(dirname(__FILE__)));

define("BMDPATH", __FILE__)  ;
//require_once __DIR__ .'/includes/hook_mce.php';

register_activation_hook(   __FILE__, 'brozzme_map_direction_plugin_activation' );
register_deactivation_hook( __FILE__, 'brozzme_map_direction_plugin_deactivation' );
register_uninstall_hook(    __DIR__ .'/uninstall.php', 'brozzme_map_direction_plugin_uninstall' );

require_once( 'cmb-bmd_functions.php' );
require_once __DIR__ .'/includes/brozzme_map_direction_init.php';
require_once( __DIR__ .'/includes/bmd_options_settings.php' );
require_once( __DIR__ .'/includes/bmd_shortcode.php' );

function brozzme_map_direction_plugin_deactivation(){
    $option_name = 'b_map_direction_settings';
    delete_option($option_name);
   // delete_option('brozzme_map_direction_settings');
}


/**
 * Creates the options
 */
function brozzme_map_direction_plugin_activation() {
    //check if tss option setting is already present

    if(!get_option('b_map_direction_settings')) {
        //not present, so add
        $options = array(
            'bmd_enable_map'=> 1,
            'bmd_automatic_shortcode_post'=> 1,
            'bmd_enable_automatic_map_direction'=> 1,
            'bmd_direction_start_point'=> 1,
            'bmd_jquery_loading'=> true,
            'bmd_origine_adresse'=> array('longitude'=>'0.16260520000002998', 'latitude'=>'49.66396659999999', 'completed_adresse'=>'La Villa Flore, 17 Rue Roger Dumont, Saint-Jouin-Bruneval'),
            'bmd_adresse_title'=> 'Map direction by Brozzme',
            'bmd_adresse_link'=> home_url(),
            'bmd_transport_mode'=>'DRIVING',
            'bmd_single_template'=> 'single.php',
            'bmd_archive_template'=> 'archive.php',
            'bmd_archive_zoom'=>'12'
        );

        add_option('b_map_direction_settings', $options);
    }
}

// add menu for configuration

add_action( 'admin_menu', 'brozzme_map_direction_add_admin_menu' );

function brozzme_map_direction_add_admin_menu(  ) {

    add_options_page('Brozzme Map', __('Brozzme Map Settings', 'brozzme-map'), 'manage_options', 'map-direction-by-brozzme', 'bmd_options_page');

}

add_action( 'plugins_loaded', 'bmd_load_textdomain' );

/**
 * Load plugin textdomain.
 */
function bmd_load_textdomain() {
    load_plugin_textdomain( 'brozzme-map-direction', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
/**
 * plugin settings links
 */
add_filter('plugin_action_links', 'brozzme_map_direction_plugin_action_links', 10, 2);

function brozzme_map_direction_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        // The &quot;page&quot; query string value must be equal to the slug
        // of the Settings admin page we defined earlier, which in
        // this case equals &quot;myplugin-settings&quot;.
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=map-direction-by-brozzme">'.__('Settings', 'brozzme-map-direction').'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}
if(strpos($_SERVER['PHP_SELF'],'options-general.php') !== FALSE){
    add_action('init', 'register_options_script');
}

//
function register_options_script(){
    wp_enqueue_script('pw_google_maps_api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places', array('jquery'), '', true);
    wp_enqueue_script('pw_google_maps_init', plugin_dir_url(BMDPATH) . 'js/script_pw_option_map.js', array('pw_google_maps_api'), '', true);
    wp_enqueue_style('pw_google_maps_css', plugin_dir_url(BMDPATH) . 'css/style_pw_map.css', array('style'), null);

    // localize script
    //  $option = get_option('b_map_direction_settings');
    //  $options_args = array(
    //    'bmd_origine_adresse_latitude'=> $option['bmd_origine_adresse']['latitude'],
    //    'bmd_origine_adresse_longitude' => $option['bmd_origine_adresse']['longitude']
    // );
    // wp_localize_script('pw_google_maps_init', 'bmdOptions', $options_args);
    // wp_enqueue_script( 'pw_google_maps_init' );
}

add_action( 'wp_enqueue_scripts', 'register_front_end_styles' );

/**
 * Register style sheet.
 */
function register_front_end_styles() {
    wp_register_style( 'bmd-front-css',plugin_dir_url(BMDPATH) . 'css/bmd_style.css' );
    wp_enqueue_style( 'bmd-front-css' );
}
function brozzme_map_direction_welcome_page(){

    ?>
    <div class="notice"><h3><b>Brozzme Map direction</b> <?php _e('', 'brozzme-map-direction');?></h3>
        <p><?php _e('Available options', 'brozzme-map-direction');?>:
        <ul><li><b><?php _e('Enable Map Direction with post-types', 'brozzme-map-direction');?></b>: <?php _e('Choose wich post-types you want to have Map Direction by Brozzme', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Enable automatic Map Direction with shortcode', 'brozzme-map-direction');?></b>: <?php _e('Add Map Direction with all shortcodes', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Map Direction starting location', 'brozzme-map-direction');?></b>: <?php _e('Starting point of the map direction service', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Jquery loading', 'brozzme-map-direction');?></b>: <?php _e('Load Jquery in header or footer', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Origin adresse', 'brozzme-map-direction');?></b>: <?php _e('Starting point adresse', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Origin title', 'brozzme-map-direction');?></b>: <?php _e('Title for the starting point', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Default travelling mode', 'brozzme-map-direction');?></b>: <?php _e('If the travelling mode are not enable', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Template for single', 'brozzme-map-direction');?></b>: <?php _e('Templates must be in the plugin folder : templates', 'brozzme-map-direction');?></li>
            <li><b><?php _e('Template for archive', 'brozzme-map-direction');?></b>: <?php _e('Templates must be in the plugin folder : templates', 'brozzme-map-direction');?></li>

        </ul>
        </p>
    </div>
<?php
}

/* Filter the single_template with our custom functions */

function bmd_single_template($single) {
    global $wp_query, $post;

    /* Checks for single template by post type */
    if ($post->post_type == "stores"){
       // if(file_exists(dirname( __FILE__ ) . '/templates/single-stores.php'))
         //   return dirname( __FILE__ )  . '/templates/single-stores.php';
       if(file_exists(dirname( __FILE__ ) . '/templates/single.php'))
           return dirname( __FILE__ )  . '/templates/single.php';

    }
    return $single;
}
// archive template
function bmd_archive_template( $archive_template ) {
    global $post;

    if ( is_post_type_archive ( 'stores' ) ) {
       // $archive_template = dirname( __FILE__ ) . '/templates/archive-stores.php';
        $archive_template = dirname( __FILE__ ) . '/templates/archive.php';
    }
    return $archive_template;
}

add_filter('single_template', 'bmd_single_template');
add_filter('stores_template', 'bmd_single_template');
add_filter( 'archive_template', 'bmd_archive_template' ) ;




