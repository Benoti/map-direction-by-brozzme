/**
 * Created by benoti on 08/09/2014.
 */


function setMarkers_simple(map, myLatLng,icon) {
    // Add one marker to the map

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: icon,
            animation: google.maps.Animation.DROP,
            animation: google.maps.Animation.BOUNCE

        });

}

function setMarkers_link(map, locations) {
    // Add markers to the map

    // Marker sizes are expressed as a Size of X,Y
    // where the origin of the image (0,0) is located
    // in the top left of the image.

    // Origins, anchor positions and coordinates of the marker
    // increase in the X direction to the right and in
    // the Y direction down.

    for (var i = 0; i < locations.length; i++) {
        var lieux = locations[i];
        var myLatLng = new google.maps.LatLng(lieux[1], lieux[2]);
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: lieux[3],
            title: lieux[0]+' : '+lieux[4],
            url: lieux[5],
            tooltip: lieux[0]+' : '+lieux[4]
        });
       google.maps.event.addListener(marker, 'click', function() {
           window.location.href = this.url;
            });


    }
}
function setMarkers_info(map, locations) {
    // Add markers to the map

    // Marker sizes are expressed as a Size of X,Y
    // where the origin of the image (0,0) is located
    // in the top left of the image.

    // Origins, anchor positions and coordinates of the marker
    // increase in the X direction to the right and in
    // the Y direction down.
    var infowindow =  new google.maps.InfoWindow({
        content: ""
    });
    for (var i = 0; i < locations.length; i++) {
        var lieux = locations[i];

        var myLatLng = new google.maps.LatLng(lieux[1], lieux[2]);
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: lieux[3],
            title: lieux[0]+' : '+lieux[4],
            url: lieux[5],
            tooltip: lieux[0]+' : '+lieux[4]
        });
      //  google.maps.event.addListener(marker, 'click', function() {
      //      window.location.href = this.url;
      //  });
    var strdescription = getInfoWindowDetails(lieux);
        bindInfoWindow(marker, map, infowindow, strdescription);

    }
}
function setMarkers_test(map, locations) {
    // Add markers to the map

    // Marker sizes are expressed as a Size of X,Y
    // where the origin of the image (0,0) is located
    // in the top left of the image.

    // Origins, anchor positions and coordinates of the marker
    // increase in the X direction to the right and in
    // the Y direction down.
    var infowindow =  new google.maps.InfoWindow({
        content: ""
    });

    var marker, i;
    var markers = [];
    for (var i = 0; i < locations.length; i++) {
        var lieux = locations[i];

        var myLatLng = new google.maps.LatLng(lieux[1], lieux[2]);
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: lieux[3],
            title: lieux[0]+' : '+lieux[4],
            url: lieux[5],
            tooltip: lieux[0]+' : '+lieux[4]
        });
        var strdescription = getInfoWindowDetails(lieux);

     bindInfoWindowtest(marker,map, infowindow, strdescription);

    }

}

function bindInfoWindow(marker, map, infowindow, strDescription) {

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(strDescription);
        infowindow.open(map, marker);

    });

}
function bindInfoWindowtest(marker, map, infowindow, strDescription) {

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(strDescription);
        infowindow.open(map, marker);

    });

}
function getInfoWindowDetails(location){
    var contentString = '<div id="content" style="width:270px;height:100px">' +
        '<h2 id="firstHeading" class="firstHeading"><img src="'+ location[3] + '" valign="bottom">   ' + location[0] + '</h2>'+
        '<div id="bodyContent">'+
        '<div style="float:left;width:100%"> '+ location[4] + '<br/><br/><a href="'+ location[5] + '">Cliquez pour découvrir</a></div>'+
        '</div>'+
        '</div>';
    return contentString;
}

function toggleBounce() {

    if (marker.getAnimation() != null) {
        marker.setAnimation(null);
    } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
    }
}

function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = new google.maps.LatLng(
                position.coords.latitude, position.coords.longitude);

        });
    }
}

window.onload = init;

var map = null;


function init() {

    var submit_direction = document.getElementById("submit_direction");
    var submit = document.getElementById("submit");

    submit_direction.onclick = getThought;
    submit_direction.onclick = traceRoute;
    submit.onclick = getThought;

}

function getThought() {

    // get our location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getLocation, locationError);

    }
    else {
        console.log("Sorry, no Geolocation support!");
        return;
    }


}


function getLocationorig(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;


        showMap(latitude, longitude);
        addMarker(latitude, longitude);
}

function showMap(lat, long, endlat, endlong) {
    var googleLatLong = new google.maps.LatLng(lat, long);
    var endpointLatLong = new google.maps.LatLng(endlat, endlong);
    var mapOptions = {
        zoom: 12,
        center: googleLatLong,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var mapDiv = document.getElementById("map-direction-canvas");

    var directionsService = new google.maps.DirectionsService();
    var request = {
        origin:googleLatLong,
        destination:endpointLatLong,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
        else{alert('Aie pas posssible, désolé');}
    });
  //  mapDirection = new google.maps.Map(mapDiv, mapOptions);


    map = new google.maps.Map(mapDiv, mapOptions);
    map.panTo(googleLatLong);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById('directions-panel'));




}

function locationError(error) {
    var errorTypes = {
        0: "Unknown error",
        1: "Permission denied by user",
        2: "Position not available",
        3: "Request timed out"
    };
    var errorMessage = errorTypes[error.code];
    if (error.code == 0 || error.code == 2) {
        errorMessage += " " + error.message;
    }
    console.log(errorMessage);
    alert(errorMessage);
}
function addMarker(lat, long) {
    var googleLatLong = new google.maps.LatLng(lat, long);
    var markerOptions = {
        position: googleLatLong,
        map: map,
        title: "Ma position actuelle"
    }
    var marker = new google.maps.Marker(markerOptions);
}
function addMarker2(lat, long) {
    var googleLatLong = new google.maps.LatLng(lat, long);
    var markerOptions = {
        position: googleLatLong,
        map: map,
        title: "Destination"
    }
    var marker = new google.maps.Marker(markerOptions);
}

// add 16/02
function GeoFindMe(start, end) {
    var output = document.getElementById("out");
    var geolocation='';
    if (!navigator.geolocation){
        output.innerHTML = "<p>Geolocation is not supported by your browser</p>";
        return;
    }

    function success(position) {

        var start_latitude  = position.coords.latitude;
        var start_longitude = position.coords.longitude;

        output.innerHTML = '<p>Latitude is ' + start_latitude + '° <br>Longitude is ' + start_longitude + '°</p>';

        var geolocation = (start_latitude +" , " + start_longitude);

        var request = {
                          origin:geolocation,
                          destination:end,
                          travelMode: google.maps.TravelMode.DRIVING
                      };
        directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
             directionsDisplay.setDirections(response);
           }
        });


     return geolocation;
    };

    function error() {
        output.innerHTML = "Unable to retrieve your location";
    };

    output.innerHTML = "<p><i class='fa fa-2x fa-spinner fa-spin'></i> Locating...<br/> waiting for autorisation to proceed.</p>";

    navigator.geolocation.getCurrentPosition(success, error);

}

