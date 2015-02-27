<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/02/15
 * Time: 15:14
 */


add_action( 'admin_init', 'brozzme_map_direction_settings_init' );

function brozzme_map_direction_settings_init(  ) {
    global $map_direction_class_array;
    register_setting( 'brozzmeMapDirectionCss', 'b_map_direction_settings' );


    add_settings_section(
        'bmd_brozzmeMapDirection_section',
        __( 'Make Map Direction by Brozzme a part of your WordPress website', 'brozzme-map_direction' ),
        'brozzme_map_direction_settings_section_callback',
        'brozzmeMapDirectionCss'
    );
    add_settings_field(
        'bmd_enable_map',
        __( 'Enable Map Direction with post-types', 'brozzme-map_direction' ),
        'bmd_enable_map_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_enable_automatic_map_direction',
        __( 'Enable automatic Map Direction', 'brozzme-map_direction' ),
        'bmd_enable_automatic_map_direction_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_direction_start_point',
        __( 'Map Direction starting location', 'brozzme-map_direction' ),
        'bmd_direction_start_point_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_jquery_loading',
        __( 'Jquery loading', 'brozzme-map_direction' ),
        'bmd_jquery_loading_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );

    add_settings_field(
        'bmd_origine_adresse',
        __( 'Starting point', 'brozzme-map_direction' ),
        'bmd_origine_adresse_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_adresse_title',
        __( 'Starting point title', 'brozzme-map_direction' ),
        'bmd_adresse_title_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_adresse_link',
        __( 'Starting point url', 'brozzme-map_direction' ),
        'bmd_adresse_url_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_transport_mode',
        __( 'Travel mode', 'brozzme-map_direction' ),
        'bmd_transport_mode_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_custom_templates',
        __( 'Custom templates', 'brozzme-map_direction' ),
        'bmd_custom_templates_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_single_template',
        __( 'Template for single', 'brozzme-map_direction' ),
        'bmd_single_template_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_archive_template',
        __( 'Template for archive', 'brozzme-map_direction' ),
        'bmd_archive_template_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    add_settings_field(
        'bmd_archive_zoom',
        __( 'Zoom for archive map', 'brozzme-map_direction' ),
        'bmd_archive_zoom_render',
        'brozzmeMapDirectionCss',
        'bmd_brozzmeMapDirection_section'
    );
    //add_settings_field(
    //    'bmd_markers_copy',
    //   __( 'Copy markers in uploads folder', 'brozzme-map_direction' ),
    //    'bmd_markers_copy_render',
    //    'brozzmeMapDirectionCss',
    //'bmd_brozzmeMapDirection_section'
    //);
}

// form rendering



function bmd_enable_map_render(  ) {

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <select name="b_map_direction_settings[bmd_enable_map]">
        <option value="1" <?php if ( $options['bmd_enable_map'] == 1 ) echo 'selected="selected"'; ?>><?php _e( 'Store', 'brozzme-map_direction' );?></option>
        <option value="2" <?php if ( $options['bmd_enable_map'] == 2 ) echo 'selected="selected"'; ?>><?php _e( 'Post', 'brozzme-map_direction' );?></option>
        <option value="3" <?php if ( $options['bmd_enable_map'] == 3 ) echo 'selected="selected"'; ?>><?php _e( 'Page', 'brozzme-map_direction' );?></option>
        <option value="4" <?php if ( $options['bmd_enable_map'] == 4 ) echo 'selected="selected"'; ?>><?php _e( 'Store + Post', 'brozzme-map_direction' );?></option>
        <option value="5" <?php if ( $options['bmd_enable_map'] == 5 ) echo 'selected="selected"'; ?>><?php _e( 'Post + Page', 'brozzme-map_direction' );?></option>
        <option value="6" <?php if ( $options['bmd_enable_map'] == 6 ) echo 'selected="selected"'; ?>><?php _e( 'Page + Store', 'brozzme-map_direction' );?></option>
        <option value="7" <?php if ( $options['bmd_enable_map'] == 7 ) echo 'selected="selected"'; ?>><?php _e( 'All post type', 'brozzme-map_direction' );?></option>

    </select>
<?php

}
function bmd_archive_zoom_render( ) {

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <select name="b_map_direction_settings[bmd_archive_zoom]">
        <option value="6" <?php if ( $options['bmd_archive_zoom'] == 6 ) echo 'selected="selected"'; ?>>6</option>
        <option value="7" <?php if ( $options['bmd_archive_zoom'] == 7 ) echo 'selected="selected"'; ?>>7</option>
        <option value="8" <?php if ( $options['bmd_archive_zoom'] == 8 ) echo 'selected="selected"'; ?>>8</option>
        <option value="9" <?php if ( $options['bmd_archive_zoom'] == 9 ) echo 'selected="selected"'; ?>>9</option>
        <option value="10" <?php if ( $options['bmd_archive_zoom'] == 10 ) echo 'selected="selected"'; ?>>10</option>
        <option value="11" <?php if ( $options['bmd_archive_zoom'] == 11 ) echo 'selected="selected"'; ?>>11</option>
        <option value="12" <?php if ( $options['bmd_archive_zoom'] == 12 ) echo 'selected="selected"'; ?>>12</option>
        <option value="13" <?php if ( $options['bmd_archive_zoom'] == 13 ) echo 'selected="selected"'; ?>>13</option>
        <option value="14" <?php if ( $options['bmd_archive_zoom'] == 14 ) echo 'selected="selected"'; ?>>14</option>
        <option value="15" <?php if ( $options['bmd_archive_zoom'] == 15 ) echo 'selected="selected"'; ?>>15</option>
        <option value="16" <?php if ( $options['bmd_archive_zoom'] == 16 ) echo 'selected="selected"'; ?>>16</option>
        <option value="17" <?php if ( $options['bmd_archive_zoom'] == 17 ) echo 'selected="selected"'; ?>>17</option>
        <option value="18" <?php if ( $options['bmd_archive_zoom'] == 18 ) echo 'selected="selected"'; ?>>18</option>

    </select>
<?php
}
function bmd_direction_start_point_render(  ){

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <select name='b_map_direction_settings[bmd_direction_start_point]'>
        <option value='2' <?php selected( $options['bmd_direction_start_point'], 2 ); ?>><?php _e( 'Main adresse', 'brozzme-map_direction' );?></option>
        <option value='1' <?php selected( $options['bmd_direction_start_point'], 1 ); ?>><?php _e( 'Geolocalisation', 'brozzme-map_direction' );?></option>
    </select>
<?php

}
function bmd_jquery_loading_render(  ){

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <select name='b_map_direction_settings[bmd_jquery_loading]'>
        <option value='2' <?php selected( $options['bmd_jquery_loading'], 2 ); ?>><?php _e( 'Default (header)', 'brozzme-map_direction' );?></option>
        <option value='1' <?php selected( $options['bmd_jquery_loading'], 1 ); ?>><?php _e( 'Choosen (footer)', 'brozzme-map_direction' );?></option>
    </select>
<?php

}

function bmd_origine_adresse_render(  ){

    $options = get_option( 'b_map_direction_settings' );
    $id = 'bmd_origine_adresse';
//    wp_enqueue_script( 'pw_google_maps_api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places', array('jquery'), '', false );
//    wp_enqueue_script( 'pw_google_maps_init', plugin_dir_url(BMDPATH).'js/script_pw_map.js', array( 'pw_google_maps_api' ), '', false );
   wp_enqueue_style( 'pw_google_maps_css', plugin_dir_url(BMDPATH) .'css/style_pw_option_map.css', array(), null );
    //wp_enqueue_style_script('');

  //  echo '<input type="text" class="map-search" id="bmd_adresse" />';
   // echo '<div class="cmb-type-pw_map map pam-admin"></div>';
    echo '<input type="text_small" class="latitude" name="b_map_direction_settings[bmd_origine_adresse][latitude]" value="' . ( isset( $options['bmd_origine_adresse']['latitude'] ) ? $options['bmd_origine_adresse']['latitude'] : '' ) . '" />';
    echo '<input type="text_small" class="longitude" name="b_map_direction_settings[bmd_origine_adresse][longitude]" value="' . ( isset( $options['bmd_origine_adresse']['longitude'] ) ? $options['bmd_origine_adresse']['longitude'] : '' ) . '" />';
    echo '<div style="clear:both;"></div><input size="50" type="text" id="b_map_direction_settings[bmd_origine_adresse][completed_adresse]" class="completed_adresse" name="b_map_direction_settings[bmd_origine_adresse][completed_adresse]" value="' . ( isset( $options['bmd_origine_adresse']['completed_adresse'] ) ? $options['bmd_origine_adresse']['completed_adresse'] : '' ) . '"/>';

   // var_dump($options);

}

function bmd_enable_automatic_map_direction_render(  ){

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <select name='b_map_direction_settings[bmd_enable_automatic_map_direction]'>
        <option value='1' <?php selected( $options['bmd_enable_automatic_map_direction'], 1 ); ?>><?php _e( 'Yes', 'brozzme-map_direction' );?></option>
        <option value='2' <?php selected( $options['bmd_enable_automatic_map_direction'], 2 ); ?>><?php _e( 'No', 'brozzme-map_direction' );?></option>
    </select>
<?php

}
function bmd_adresse_title_render(  ) {

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <input size="50" type='text' name='b_map_direction_settings[bmd_automatic_style_targets]' value='<?php echo $options['bmd_automatic_style_targets']; ?>'>
<?php

}

function bmd_adresse_url_render(  ) {

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <input size="50" type='text' name='b_map_direction_settings[bmd_adresse_link]' value='<?php echo $options['bmd_adresse_link']; ?>'>
<?php

}

function bmd_markers_copy_render(  ) {

    $options = get_option( 'b_map_direction_settings' );
    ?>
<p><?php __('Copy on activation','brozzme-map-direction');?></p>
    <select name="b_map_direction_settings[bmd_markers_copy]">
        <option value="1" <?php if ( $options['bmd_markers_copy'] == 1 ) echo 'selected="selected"'; ?>><?php _e( 'Yes', 'brozzme-map_direction' );?></option>
        <option value="2" <?php if ( $options['bmd_markers_copy'] == 2 ) echo 'selected="selected"'; ?>><?php _e( 'No', 'brozzme-map_direction' );?></option>
    </select>
<?php

}

function bmd_transport_mode_render(  ){

    $options = get_option( 'b_map_direction_settings' ); // BICYCLING DRIVING WALKING DRIVING
    ?>
    <select name='b_map_direction_settings[bmd_transport_mode]'>
        <option value='DRIVING' <?php selected( $options['bmd_transport_mode'], 'DRIVING' ); ?>><?php _e( 'DRIVING', 'brozzme-map_direction' );?></option>
        <option value='BICYCLING' <?php selected( $options['bmd_transport_mode'], 'BICYCLING' ); ?>><?php _e( 'BICYCLING', 'brozzme-map_direction' );?></option>
        <option value='WALKING' <?php selected( $options['bmd_transport_mode'], 'WALKING' ); ?>><?php _e( 'WALKING', 'brozzme-map_direction' );?></option>
    </select>
<?php

}
function bmd_custom_templates_render(  ){

    $options = get_option( 'b_map_direction_settings' );
    ?>
    <select name='b_map_direction_settings[bmd_custom_templates]'>
        <option value='1' <?php selected( $options['bmd_custom_templates'], 1 ); ?>><?php _e( 'Yes', 'brozzme-map_direction' );?></option>
        <option value='2' <?php selected( $options['bmd_custom_templates'], 2 ); ?>><?php _e( 'No', 'brozzme-map_direction' );?></option>
    </select>

<?php

}
function bmd_single_template_render(){
    $options = get_option( 'b_map_direction_settings' );
    ?>
    <input type='text' name='b_map_direction_settings[bmd_single_template]' value='<?php echo $options['bmd_single_template']; ?>'>
<?php

}
function bmd_archive_template_render(){
    $options = get_option( 'b_map_direction_settings' );
    ?>
    <input type='text' name='b_map_direction_settings[bmd_archive_template]' value='<?php echo $options['bmd_archive_template']; ?>'>
<?php

}
function brozzme_map_direction_settings_section_callback(  ) {

    echo __( 'Manage your Map Direction settings for ', 'brozzme-map_direction' ).' '.get_bloginfo('name');

}


function bmd_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h2><?php _e( 'Brozzme Map direction', 'brozzme-map_direction' );?></h2>

        <?php
        settings_fields( 'brozzmeMapDirectionCss' );
        do_settings_sections( 'brozzmeMapDirectionCss' );
        submit_button();
        ?>

    </form>
    <?php
    brozzme_map_direction_welcome_page();
}

