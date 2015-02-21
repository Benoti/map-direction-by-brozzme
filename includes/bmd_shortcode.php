<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/02/15
 * Time: 15:16
 */

function retrieve_map_data_post_type(){
    $post = get_post( );
    $post_meta = get_post_meta($post->ID);
    //var_dump($post_meta);
    if($post_meta['store_adresse_latitude'][0] == ''){return false;}
    $post_map_datas = array(
        'latitude'=> $post_meta['store_adresse_latitude'][0],
        'longitude'=> $post_meta['store_adresse_longitude'][0],
        'marker_url'=>$post_meta['store_url_marker'][0],
        'zoom'=>$post_meta['store_zoom'][0]);
    // get post type by post
    $post_type = $post->post_type;

    return $post_map_datas;
}

function google_map_head(){
    if ( is_admin() ) {
        return;
    }

    echo "<!-- Google Maps API v3 --> \n";
    echo "<script src='http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script> \n \n";
    echo "<script src='".plugin_dir_url( BMDPATH )."js/js_maps_functions.js'></script> \n \n";
}
add_action('wp_head', 'google_map_head');

function google_simple_marker($params = array())
{
    $option = get_option('b_map_direction_settings');

    $map_datas = retrieve_map_data_post_type();
   // var_dump($map_datas);
    if ($map_datas == null) {
        return;
    }
    $latitude = $map_datas['latitude'];
    $longitude = $map_datas['longitude'];
    $marker_url= $map_datas['marker_url'];
    $marker_url = unserialize($marker_url);
        foreach ($marker_url as $key=>$url) {
            $icon_url = $url;
        }
    // marker url
        if($icon_url==null){
            $icon_url = plugin_dir_url(BMDPATH) . 'images/markers/reperes-activ.png';
        }
        else{
            $icon_url = $icon_url;
        }
   // zoom
        if($map_datas['zoom']==null){
            $zoom = '12';
        }
        else{
            $zoom = $map_datas['zoom'];
        }
    extract(shortcode_atts(array(

        'lat' => $latitude,
        'long' => $longitude,
        'marker' => $icon_url,
        'zoom' => $zoom,
        'title' => ''
    ), $params));
    // if(retrieve_map_data_post_type()== false){return;}

    $map = "<script type='text/javascript'>// <![CDATA[ \n";
    $map .= "function initialize() { \n";
    $map .= "var mapOptions = { \n";
    $map .= "zoom: " . $zoom . ",\n";
    $map .= "center: new google.maps.LatLng(" . $lat . "," . $long . ")\n";
    $map .= "} \n";
    $map .= "var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions); \n";
    $map .= "var myLatLng = new google.maps.LatLng(" . $lat . ", " . $long . ");\n";
    $map .= "var icon = '" . $icon_url . "';\n";
    $map .= "setMarkers_simple(map, myLatLng,icon)";
    $map .= "} \n";
    $map .= "google.maps.event.addDomListener(window, 'resize', initialize);\n";
    $map .= "google.maps.event.addDomListener(window, 'load', initialize);\n";

    if($option['bmd_enable_automatic_map_direction']== 1){
        $title = $option['bmd_adresse_title'];
        $start_position_adresse = $option['bmd_origine_adresse']['completed_adresse'];
        $transport_mode = $option['bmd_transport_mode'];

        $map .= "var directionsDisplay; \n";
        $map .= "var directionsService = new google.maps.DirectionsService(); \n";
        $map .= "var mapDirection; \n";
        $map .= "var start = '".$start_position_adresse."' ; \n";
        $map .= "var geolocation; \n";
        $map .= "function traceRoute() { \n";
        $map .= "directionsDisplay = new google.maps.DirectionsRenderer(); \n";
        $map .= "var mapOptions = { \n";
        $map .= "zoom:12, \n";
        $map .= "center: new google.maps.LatLng(" . $lat . "," . $long . ")\n";

        $map .= "}; \n";
        $map .= "calcRoute(); ";

        $map .= "var icon = '" . $icon_url . "';\n";
        $map .= "mapDirection = new google.maps.Map(document.getElementById('map-canvas'), mapOptions); \n";
        $map .= "directionsDisplay.setMap(mapDirection); \n";
        $map .= "directionsDisplay.setPanel(document.getElementById('directions-panel')); \n";
        $map .= "           } \n";
        $map .= "           function calcRoute() { \n";
        if($option['bmd_direction_start_point']==2){
            $map .= "            var start = '".$start_position_adresse."'; \n";
            $map .= "             var end = '".$lat.", ".$long."'; \n";
            $map .= "              var request = { \n";
            $map .= "                  origin:start, \n";
            $map .= "                  destination:end, \n";
            $map .= "                 travelMode: google.maps.TravelMode.".$transport_mode." \n";
            $map .= "              };\n";
            $map .= "              directionsService.route(request, function(response, status) { \n";
            $map .= "               if (status == google.maps.DirectionsStatus.OK) { \n";
            $map .= "                  directionsDisplay.setDirections(response); \n";
            $map .= "                 } \n";
            $map .= "              }); \n";
        }
        else {
            $map .= "             var end = '(" . $lat . ", " . $long . ")'; \n";
            $map .= "            var start = new GeoFindMe(start,end); \n";
        }
        $map .= "           } \n";
        $map .= "           function getLocation(position) { \n";
        $map .= "               var latitude = position.coords.latitude; \n";
        $map .= "               var longitude = position.coords.longitude; \n";
        $map .= "               showMap(latitude, longitude, " . $lat . ", " . $long . "); \n";
        $map .= "               addMarker(latitude, longitude); \n";
        $map .= "               addMarker2(" . $lat . ", " . $long . "); \n";
        $map .= "           } \n";
    }
    $map .= "// ]]></script>\n";
    $map .= "<div id='map-canvas'  style='width:97%;height:400px;'></div>";
    if($option['bmd_enable_automatic_map_direction']== 1) {
        $map .= '<div id="geo-panel">
                 <input type="button" id="submit_direction"  value="' . __('Find my position', 'brozzme-map-direction') . '">
           <div id="out"></div>
           </div>';
        $map .= "        \n";
        $map .= "<div id='directions-panel' style='width:98%;'></div>";
    }
    return $map;
}
add_shortcode('googlemap', 'google_simple_marker');

function google_simple_direction($params = array()) {
    $option = get_option('b_map_direction_settings');
    $latitude = $option['bmd_origine_adresse']['latitude'];
    $longitude = $option['bmd_origine_adresse']['longitude'];
    $title = $option['bmd_origine_adresse'];

    $icon_url = plugin_dir_url( BMDPATH ).'images/markers/reperes-activ.png';
    $zoom = '12';

    extract(shortcode_atts(array(

        'lat' => $latitude,
        'long' => $longitude,
        'marker' => $icon_url,
        'zoom' => $zoom,
        'title' => ''
    ), $params));


    $title = $option['bmd_adresse_title'];
    $start_position_adresse = $option['bmd_origine_adresse']['completed_adresse'];
    $transport_mode = $option['bmd_transport_mode'];
    $map = "<script type='text/javascript'>// <![CDATA[ \n";

    $map .= "var directionsDisplay; \n";
    $map .= "var directionsService = new google.maps.DirectionsService(); \n";
    $map .= "var mapDirection; \n";
    $map .= "var laroute; \n";
    $map .= "function initialize() { \n";
    $map .= "directionsDisplay = new google.maps.DirectionsRenderer(); \n";
    $map .= "var mapOptions = { \n";
    $map .= "zoom:12, \n";
    $map .= "center: new google.maps.LatLng(".$lat.",".$long.")\n";

    $map .= "}; \n";
    $map .= "calcRoute(); ";

    $map .= "var icon = '".$icon_url."';\n";
    $map .= "mapDirection = new google.maps.Map(document.getElementById('map-direction-canvas'), mapOptions); \n";
    $map .= "directionsDisplay.setMap(mapDirection); \n";
    $map .= "directionsDisplay.setPanel(document.getElementById('directions-panel')); \n";
    $map .= "           } \n";
    $map .= "           function calcRoute() { \n";
    $map .= "            var start = '".$start_position_adresse."'; \n";
    $map .= "             var end = '".$lat.", ".$long."'; \n";
    $map .= "              var request = { \n";
    $map .= "                  origin:start, \n";
    $map .= "                  destination:end, \n";
    $map .= "                 travelMode: google.maps.TravelMode.".$transport_mode." \n";
    $map .= "              };\n";
    $map .= "              directionsService.route(request, function(response, status) { \n";
    $map .= "               if (status == google.maps.DirectionsStatus.OK) { \n";
    $map .= "                  directionsDisplay.setDirections(response); \n";
    $map .= "                 } \n";
    $map .= "              }); \n";
    $map .= "           } \n";

    $map .= "           function calcRouteChx() { \n";

    $map .= "            var start = '".$start_position_adresse."'; \n";
    $map .= "            var end = '".$lat.", ".$long."'; \n";
    $map .= "           var Transportmode = document.getElementById('Transportmode').value; \n";
    $map .= "               if(Transportmode == 'BICYCLING'){
                                var Transportmode = google.maps.TravelMode.BICYCLING; }\n";
    $map .= "               if(Transportmode == 'DRIVING'){
                                var Transportmode = google.maps.TravelMode.DRIVING; }\n";
    $map .= "               if(Transportmode == 'WALKING'){
                                var Transportmode = google.maps.TravelMode.WALKING; }\n";
    $map .= "               else{
                                var Transportmode = google.maps.TravelMode.DRIVING; }\n";
    $map .= "              var request = { \n";
    $map .= "                  origin:start, \n";
    $map .= "                  destination:end, \n";
    $map .= "                  travelMode: Transportmode \n";
    //$map .= "                  travelMode: google.maps.TravelMode.Transportmode \n";
    $map .= "              };\n";
    $map .= "              directionsService.route(request, function(response, status) { \n";
    $map .= "               if (status == google.maps.DirectionsStatus.OK) { \n";
    $map .= "                  directionsDisplay.setDirections(response); \n";
    $map .= "                 } \n";
    $map .= "              }); \n";
    $map .= "           } \n";

    $map .= "           function getLocation(position) { \n";
    $map .= "               var latitude = position.coords.latitude; \n";
    $map .= "               var longitude = position.coords.longitude; \n";

    $map .= "               showMap(latitude, longitude, ".$lat.", ".$long."); \n";
    $map .= "               addMarker(latitude, longitude); \n";
    $map .= "               addMarker2(".$lat.", ".$long."); \n";
    $map .= "           } \n";

    // $map .= "               google.maps.event.trigger(map,'resize');\n";
    $map .= "           google.maps.event.addDomListener(window, 'resize', initialize);\n";
    $map .= "           google.maps.event.addDomListener(window, 'load', initialize); \n";

    $map .= "// ]]></script>\n";

    $map .= "<div id='map-direction-canvas' style='width:98%;height:500px;margin: 0px auto;'></div>";
    $map .= '<div id="geo-panel">
                 <input type="button" id="submit" value="'.__('Find my position', 'brozzme-map-direction').'">
            </div>';
    $map .= '<div id="panel">
    <b>'.__('Travel mode :', 'brozzme-map-direction').'</b>
    <select id="Transportmode" onchange="calcRouteChx();">
      <option value="DRIVING" selected>'.__('Choose your travel mode', 'brozzme-map-direction').'</option>
      <option value="BICYCLING">Vélo / Bicycling</option>
      <option value="DRIVING">Voiture / Driving</option>
      <option value="WALKING">A pied / Walking</option>

    </select>
    </div>';
    $map .= "<div id='directions-panel' style='width:98%;'></div>";

    return $map;
}
add_shortcode('googlemap-direction', 'google_simple_direction');

function google_geo_direction($params = array())
{
    $option = get_option('b_map_direction_settings');

    $map_datas = retrieve_map_data_post_type();
    // var_dump($map_datas);
    if ($map_datas == null) {
        return;
    }
    $latitude = $map_datas['latitude'];
    $longitude = $map_datas['longitude'];
    $marker_url= $map_datas['marker_url'];
    $marker_url = unserialize($marker_url);
    foreach ($marker_url as $key=>$url) {
        $icon_url = $url;
    }
    // marker url
    if($icon_url==null){
        $icon_url = plugin_dir_url(BMDPATH) . 'images/markers/reperes-activ.png';
    }
    else{
        $icon_url = $icon_url;
    }
    // zoom
    if($map_datas['zoom']==null){
        $zoom = '12';
    }
    else{
        $zoom = $map_datas['zoom'];
    }
    extract(shortcode_atts(array(

        'lat' => $latitude,
        'long' => $longitude,
        'marker' => $icon_url,
        'zoom' => $zoom,
        'title' => ''
    ), $params));
    // if(retrieve_map_data_post_type()== false){return;}

    $map = "<script type='text/javascript'>// <![CDATA[ \n";
    $map .= "function initialize() { \n";
    $map .= "var mapOptions = { \n";
    $map .= "zoom: " . $zoom . ",\n";
    $map .= "center: new google.maps.LatLng(" . $lat . "," . $long . ")\n";
    $map .= "} \n";
    $map .= "var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions); \n";
    $map .= "var myLatLng = new google.maps.LatLng(" . $lat . ", " . $long . ");\n";
    $map .= "var icon = '" . $icon_url . "';\n";
    $map .= "setMarkers_simple(map, myLatLng,icon)";
    $map .= "} \n";
    $map .= "google.maps.event.addDomListener(window, 'resize', initialize);\n";
    $map .= "google.maps.event.addDomListener(window, 'load', initialize);\n";

    if($option['bmd_enable_automatic_map_direction']== 1){
        $title = $option['bmd_adresse_title'];
        $start_position_adresse = $option['bmd_origine_adresse']['completed_adresse'];
        $transport_mode = $option['bmd_transport_mode'];

        $map .= "var directionsDisplay; \n";
        $map .= "var directionsService = new google.maps.DirectionsService(); \n";
        $map .= "var mapDirection; \n";
        $map .= "var start = '".$start_position_adresse."' ; \n";
        $map .= "var geolocation; \n";
        $map .= "function traceRoute() { \n";
        $map .= "directionsDisplay = new google.maps.DirectionsRenderer(); \n";
        $map .= "var mapOptions = { \n";
        $map .= "zoom:12, \n";
        $map .= "center: new google.maps.LatLng(" . $lat . "," . $long . ")\n";

        $map .= "}; \n";
        $map .= "calcRoute(); ";

        $map .= "var icon = '" . $icon_url . "';\n";
        $map .= "mapDirection = new google.maps.Map(document.getElementById('map-canvas'), mapOptions); \n";
        $map .= "directionsDisplay.setMap(mapDirection); \n";
        $map .= "directionsDisplay.setPanel(document.getElementById('directions-panel')); \n";
        $map .= "           } \n";
        $map .= "           function calcRoute() { \n";
        if($option['bmd_direction_start_point']==2){
            $map .= "            var start = '".$start_position_adresse."'; \n";
            $map .= "             var end = '".$lat.", ".$long."'; \n";
            $map .= "              var request = { \n";
            $map .= "                  origin:start, \n";
            $map .= "                  destination:end, \n";
            $map .= "                 travelMode: google.maps.TravelMode.".$transport_mode." \n";
            $map .= "              };\n";
            $map .= "              directionsService.route(request, function(response, status) { \n";
            $map .= "               if (status == google.maps.DirectionsStatus.OK) { \n";
            $map .= "                  directionsDisplay.setDirections(response); \n";
            $map .= "                 } \n";
            $map .= "              }); \n";
        }
        else {
            $map .= "             var end = '(" . $lat . ", " . $long . ")'; \n";
            $map .= "            var start = new GeoFindMe(start,end); \n";
        }
        $map .= "           } \n";
        $map .= "           function getLocation(position) { \n";
        $map .= "               var latitude = position.coords.latitude; \n";
        $map .= "               var longitude = position.coords.longitude; \n";
        $map .= "               showMap(latitude, longitude, " . $lat . ", " . $long . "); \n";
        $map .= "               addMarker(latitude, longitude); \n";
        $map .= "               addMarker2(" . $lat . ", " . $long . "); \n";
        $map .= "           } \n";
    }
    $map .= "// ]]></script>\n";
    $map .= "<div id='map-canvas'  style='width:97%;height:400px;'></div>";
    if($option['bmd_enable_automatic_map_direction']== 1) {
        $map .= '<div id="geo-panel">
                 <input type="button" id="submit_direction"  value="' . __('Find my position', 'brozzme-map-direction') . '">
           <div id="out"></div>
           </div>';
        $map .= "        \n";
        $map .= "<div id='directions-panel' style='width:98%;'></div>";
    }
    return $map;
}
add_shortcode('googlemap-my-direction', 'google_geo_direction');
// get taxonomies terms links
function custom_taxonomies_terms_links2(){
    // get post by post id
    $post = get_post( $post->ID );

    // get post type by post
    $post_type = $post->post_type;

    // get post type taxonomies
    $taxonomies = get_object_taxonomies( $post_type, 'objects' );

    $out = array();
    foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){

        // get the terms related to post
        $terms = get_the_terms( $post->ID, $taxonomy_slug );

        if ( !empty( $terms ) ) {

            foreach ( $terms as $term ) {
                $out[] = $term->name ;
            }

        }
    }

    return implode('', $out );
}
function google_maps_archives($params = array()){
    $option = get_option('b_map_direction_settings');
    $base_longitude = $option['bmd_origine_adresse']['longitude'];
    $base_latitude = $option['bmd_origine_adresse']['latitude'];
    global $post;

    if($option['bmd_archive_zoom']!=''){
        $zoom = $option['bmd_archive_zoom'];
    }
    else{
        $zoom ='12';
    }

    extract(shortcode_atts(array(

        'lat' => $base_latitude,
        'long' => $base_longitude,
        'zoom' => $zoom,
        'title' => ''
    ), $params));

    // retrieve marker category
    // $marker_cat = wp_get_post_terms( $the_id, 'reperes', array("fields" => "all") );
    // $marker_cat_slug = $marker_cat[0]->slug;
    // $marker_title = get_the_title();
    //var_dump($marker_cat);
    $args_cat = array(
        'type'                     => 'post',
        //'child_of'                 => 0,
        //'parent'                   => '',
        'orderby'                  => 'name',
        'order'                    => 'ASC',
        'hide_empty'               => 1,
        'hierarchical'             => 1,
        'exclude'                  => '',
        //'include'                  => '',
        //'number'                   => '',
        'taxonomy'                 => 'details',
        'pad_counts'               => false

    );
    $the_cats = get_categories( $args_cat );
    // var_dump($the_cats);
    $cats_array = array();
    $ids_array = array();
    $cat_id = '';
    foreach($the_cats as $cats){
        $cats_array[$cats->term_id] = $cats->slug;
        $cat_id = $cats->term_id;

        $args = array(
            'posts_per_page'   => -1,
            //'category'         => $cat_id,
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'post_type'        => 'stores',
            'post_status'      => 'publish',
            'suppress_filters' => true );

        $posts_in_category_array = get_posts( $args );
      //var_dump($posts_in_category_array);
        $icon_url='';
        foreach($posts_in_category_array as $post){
            $latitude = get_post_meta(  $post->ID, 'store_adresse_latitude', true );
            $longitude = get_post_meta(  $post->ID, 'store_adresse_longitude', true );
            $icon_marker = get_post_meta(  $post->ID, 'store_url_marker', true );
            foreach($icon_marker as $value){
                $icon_file_url =  $value ;
            }
            if($icon_file_url=='' || $icon_marker==null){
                $icon_url = plugin_dir_url( BMDPATH ).'images/markers/reperes-activ.png';
            }
            else{
                $icon_url = $icon_file_url;
            }
            $store_category = custom_taxonomies_terms_links2();
            //var_dump($store_category);
            //$ids_array[] = array($category_name, $latitude, $longitude, $icon_url, $post->post_title, get_permalink($post->ID));
            if($latitude == '' || $longitude == ''){
                $ids_array[] = array($store_category, $base_latitude, $base_longitude, $icon_url, $post->post_title, get_permalink($post->ID));
            }
            else{
                $ids_array[] = array($store_category, $latitude, $longitude, $icon_url, $post->post_title, get_permalink($post->ID));
            }

        }
    }
    $ids_array[] = array('', $base_latitude, $base_longitude, plugin_dir_url( BMDPATH ).'images/markers/reperes-hom.png', 'La Villa Flore', 'http://www.happyflore.com/gite-la-villa-flore/');
    //  var_dump($cats_array);
    $ids_array = json_encode($ids_array);

    // retrieve post for this category slug

    $map = "<script type='text/javascript'>// <![CDATA[ \n";
    $map .= "function initialize() { \n";
    $map .= "var mapOptions = { \n";
    $map .= "zoom: ". $zoom . ",\n";
    $map .= "center: new google.maps.LatLng(".$base_latitude.",".$base_longitude.")\n";
    //$map .= "center: new google.maps.LatLng(0, 0),\n";
    // $map .= "center: new google.maps.LatLng(0, 0),\n";
    $map .= "} \n";
    $map .= "var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions); \n";
    // $map .= "bounds  = new google.maps.LatLngBounds();\n";
    $map .= "setMarkers_info(map, lieux);\n";
    $map .= "} \n";
    $map .= "var lieux = ".$ids_array.";\n";
    $map .= "google.maps.event.addDomListener(window, 'resize', initialize);\n";
    $map .= "google.maps.event.addDomListener(window, 'load', initialize);\n";
    $map .= "// ]]></script>\n";
    $map .= "<div id='map-canvas' style='width:97%;height:500px;margin:0px auto;'></div>";
    // $map .= '<div id="select-reperes"><a href="http://www.parisianeast.com/category/actueast/#lmap" class="color-actu">actu<span class="east">east</span></a><a href="http://www.parisianeast.com/category/homeeast/#lmap" class="color-hom">hom<span class="east">east</span></a><a href="http://www.parisianeast.com/category/arteast/#lmap" class="color-art">art<span class="east">east</span></a><a href="http://www.parisianeast.com/category/loveast/" class="color-lov">lov<span class="east">east</span></a><a href="http://www.parisianeast.com/category/activit/#lmap" class="color-activ">activ<span class="east">east</span></a><a href="http://www.parisianeast.com/category/genteast/#lmap" class="color-gent">gent<span class="east">east</span></a><a href="http://www.parisianeast.com/category/urbaneast/#lmap" class="color-urban">urban<span class="east">east</span></a><a href="http://www.parisianeast.com/category/localeast/#lmap" class="color-local">local<span class="east">east</span></a></div>';
    return $map;

}

add_shortcode('googlemap-archives', 'google_maps_archives');

