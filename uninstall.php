<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/02/15
 * Time: 12:50
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

function brozzme_map_direction_plugin_uninstall(){
$option_name = 'b_map_direction_settings';

delete_option($option_name);
}

?>