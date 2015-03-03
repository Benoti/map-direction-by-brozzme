<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/02/15
 * Time: 14:26
 */

$myposttype2 ='stores'; //id du custom post

add_action('init', 'stores_init');
function stores_init()
{
    global $myposttype2;

    $labels_stores = array(
        'name'                       => __('Stores','brozzme-map-direction'),
        'singular_name'              => __('Store','brozzme-map-direction'),
        'search_items'               => __('Search stores','brozzme-map-direction'),
        'all_items'                  => __('All stores','brozzme-map-direction'),
        'edit_item'                  => __('Edit store','brozzme-map-direction'),
        'update_item'                => __('Update store','brozzme-map-direction'),
        'add_new_item'               => __('Add new store','brozzme-map-direction'),
        'new_item'              	 => __('New store','brozzme-map-direction'),
        'not_found'                  => __('No store found.','brozzme-map-direction'),

    );

    register_post_type($myposttype2, array(
        'label' => 'stores',
        'labels' => $labels_stores,
        'singular_label' => __('Store','brozzme-map-direction'),
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_name'                  => __('Stores','brozzme-map-direction'),
        'taxonomies' => array('category'),
        'menu_position'				 => '5',
        'menu_icon' 				 => 'dashicons-id-alt',
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'adresses'),
        'supports' => array('title', 'thumbnail', 'post-formats',  'custom-fields', 'editor' )

    ));


    /********** pour les checkbox Ã  choix multiples : le plus simple est de dÃ©clarer une taxonomie hiÃ©rarchique (= catÃ©gorie)  ****************/
    //register_taxonomy( 'Mots clés', $myposttype, array( 'hierarchical' => true, 'label' => 'Mots clés', 'query_var' => true, 'rewrite' => true ) );
    $labels_motscles =  array(
        'name'                       => __('Tags','brozzme-map-direction'),
        'singular_name'              => __('Tag','brozzme-map-direction'),
        'search_items'               => __('Search tags','brozzme-map-direction'),
        'popular_items'              => __( 'Popular tags' ),
        'all_items'                  => __('All tags','brozzme-map-direction'),
        'edit_item'                  => __('Edit tag','brozzme-map-direction'),
        'update_item'                => __( 'Update tag' ),
        'add_new_item'               => __('Add a new tag','brozzme-map-direction'),
        'new_item_name'              => __( 'New tag name' ),
        'separate_items_with_commas' => __( 'Separate tags with commas' ),
        'add_or_remove_items'        => __('Add or remove tags','brozzme-map-direction'),
        'choose_from_most_used'      => __('Choose from the most used tags','brozzme-map-direction'),
        'not_found'                  => __('No tag found.','brozzme-map-direction'),
        'menu_name'                  => __('Tags','brozzme-map-direction'),
    );
    register_taxonomy( 'mots-cles', $myposttype2,
        array( 'hierarchical' => true,
            'labels' => $labels_motscles,
            'query_var' => true,
            'show_admin_column'=> true,
            'rewrite' => true )
    );

    $labels_commerce =  array(
        'name'                       => __('Details','brozzme-map-direction'),
        'singular_name'              => __('Detail','brozzme-map-direction'),
        'search_items'               => __('Search detail','brozzme-map-direction'),
        'popular_items'              => __('Popular detail','brozzme-map-direction'),
        'all_items'                  => __('All details','brozzme-map-direction'),
        'edit_item'                  => __('Edit detail','brozzme-map-direction'),
        'update_item'                => __( 'Update detail','brozzme-map-direction' ),
        'add_new_item'               => __('Add new detail','brozzme-map-direction'),
        'new_item_name'              => __( 'New detail Name','brozzme-map-direction' ),
        'separate_items_with_commas' => __( 'Separate details with commas','brozzme-map-direction' ),
        'add_or_remove_items'        => __( 'Add or remove details' ,'brozzme-map-direction'),
        'choose_from_most_used'      => __( 'Choose from the most used details','brozzme-map-direction' ),
        'not_found'                  => __( 'No detail found.','brozzme-map-direction' ),
        'menu_name'                  => __('Details','brozzme-map-direction'),
    );
    register_taxonomy( 'details', $myposttype2,
        array( 'hierarchical' => true,
            'labels' => $labels_commerce,
            'query_var' => true,
            'show_admin_column'=> true,
            'rewrite' => array('slug' => 'details') )
    );

}