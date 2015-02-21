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
        'name'                       => __('Lieux','brozzme-map-direction'),
        'singular_name'              => __('Lieu','brozzme-map-direction'),
        'search_items'               => __('Rechercher lieux','brozzme-map-direction'),
        'all_items'                  => __('Tout les lieux','brozzme-map-direction'),
        'edit_item'                  => __('Editer lieu','brozzme-map-direction'),
        'update_item'                => __('Mettre à jour le lieu','brozzme-map-direction'),
        'add_new_item'               => __('Ajouter un nouveau lieu','brozzme-map-direction'),
        'new_item'              	 => __('Nouveau lieu','brozzme-map-direction'),
        'not_found'                  => __('Aucun lieu trouvé.','brozzme-map-direction'),

    );

    register_post_type($myposttype2, array(
        'label' => 'stores',
        'labels' => $labels_stores,
        'singular_label' => __('Lieu','brozzme-map-direction'),
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_name'                  => __('Lieux','brozzme-map-direction'),
        'menu_position'				 => '5',
        'menu_icon' 				 => 'dashicons-id-alt',
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'adresses'),
        'supports' => array('title', 'thumbnail', 'post-formats',  'custom-fields', 'editor' )
        //'supports' => array('title', 'thumbnail', 'post-formats')
    ));


    /********** pour les checkbox Ã  choix multiples : le plus simple est de dÃ©clarer une taxonomie hiÃ©rarchique (= catÃ©gorie)  ****************/
    //register_taxonomy( 'Mots clés', $myposttype, array( 'hierarchical' => true, 'label' => 'Mots clés', 'query_var' => true, 'rewrite' => true ) );
    $labels_motscles =  array(
        'name'                       => __('Mots clés','brozzme-map-direction'),
        'singular_name'              => __('Mot clé','brozzme-map-direction'),
        'search_items'               => __('Rechercher Mots clés','brozzme-map-direction'),
        'popular_items'              => __( 'Mots clés Populaire' ),
        'all_items'                  => __('Tout les Mots clés','brozzme-map-direction'),
        'edit_item'                  => __('Editer Mot clé','brozzme-map-direction'),
        'update_item'                => __( 'Update Mot clé' ),
        'add_new_item'               => __('Ajouter un nouvel Mot clé','brozzme-map-direction'),
        'new_item_name'              => __( 'New Mot clé Name' ),
        'separate_items_with_commas' => __( 'Separate Mots clés with commas' ),
        'add_or_remove_items'        => __('Ajouter ou retirer Mots clés','brozzme-map-direction'),
        'choose_from_most_used'      => __('Choose from the most used Mots clés','brozzme-map-direction'),
        'not_found'                  => __('Aucun Mots clés trouvé.','brozzme-map-direction'),
        'menu_name'                  => __('Mots clés','brozzme-map-direction'),
    );
    register_taxonomy( 'mots-cles', $myposttype2,
        array( 'hierarchical' => true,
            'labels' => $labels_motscles,
            'query_var' => true,
            'show_admin_column'=> true,
            'rewrite' => true )
    );
    //register_taxonomy( 'Agents', $myposttype, array( 'hierarchical' => true, 'label' => 'Agents', 'query_var' => true, 'rewrite' => true ) );
    $labels_commerce =  array(
        'name'                       => __('Détails','brozzme-map-direction'),
        'singular_name'              => __('Détail','brozzme-map-direction'),
        'search_items'               => __('Rechercher détail','brozzme-map-direction'),
        'popular_items'              => __('Détail populaire','brozzme-map-direction'),
        'all_items'                  => __('Tout les détails','brozzme-map-direction'),
        'edit_item'                  => __('Editer détail','brozzme-map-direction'),
        'update_item'                => __( 'Update détail' ),
        'add_new_item'               => __('Ajouter un nouveau détail','brozzme-map-direction'),
        'new_item_name'              => __( 'New detail Name' ),
        'separate_items_with_commas' => __( 'Separate details with commas' ),
        'add_or_remove_items'        => __( 'Add or remove details' ),
        'choose_from_most_used'      => __( 'Choose from the most used details' ),
        'not_found'                  => __( 'Aucun détail trouvé.' ),
        'menu_name'                  => __('Détails','brozzme-map-direction'),
    );
    register_taxonomy( 'details', $myposttype2,
        array( 'hierarchical' => true,
            'labels' => $labels_commerce,
            'query_var' => true,
            'show_admin_column'=> true,
            'rewrite' => array('slug' => 'details') )
    );

}