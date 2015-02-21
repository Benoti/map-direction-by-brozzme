(function ($) {

 	$('.cmb-type-pw_map').each(function() {
		var searchInput = $('#bmd_adresse', this).get(0);
		var mapCanvas = $('.map', this).get(0);
		var latitude = $('.latitude', this);
		var longitude = $('.longitude', this);
        var rtzoom = $('.rtzoom', this);
        var completed_adresse = $('.completed_adresse', this);
		var origine_lat = bmdOptions.bmd_origine_adresse_latitude;
		var origine_long = bmdOptions.bmd_origine_adresse_longitude;
		var latLng = new google.maps.LatLng(origine_lat,origine_long);
		var zoom = 13;
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'

        };

		// Map
		if(latitude.val().length > 0 && longitude.val().length > 0) {
			latLng = new google.maps.LatLng(latitude.val(), longitude.val());
			zoom = 17;
		}

		var mapOptions = {
			center: latLng,
			zoom: zoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(mapCanvas, mapOptions);

		// Marker
		var markerOptions = {
			map: map,
			draggable: true,
			title: 'Déplacer le marqueur pour définir la position exacte'
		};
		var marker = new google.maps.Marker(markerOptions);

		if(latitude.val().length > 0 && longitude.val().length > 0) {
			marker.setPosition(latLng);
		}

		google.maps.event.addListener(marker, 'drag', function() {
			latitude.val(marker.getPosition().lat());
			longitude.val(marker.getPosition().lng());
		});
        google.maps.event.addListener(map, "zoom_changed", function() {
            rtzoom.val(map.getZoom());
        });
		// Search
		var autocomplete = new google.maps.places.Autocomplete(searchInput);
		autocomplete.bindTo('bounds', map);

		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);

			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);

			}

			marker.setPosition(place.geometry.location);

			latitude.val(place.geometry.location.lat());
			longitude.val(place.geometry.location.lng());
            console.log(place.componentForm);

            var addressType_street_number = place.address_components[0].short_name;
            var addressType_route = place.address_components[1].long_name;
            var addressType_locality = place.address_components[2].long_name;
            var addressType_postal_code = place.address_components[6].short_name;

            document.getElementById('bmd_origine_adresse[completed_adresse]').value = addressType_street_number + ", " + addressType_route+ "  "+ addressType_postal_code+ "  "+addressType_locality;

        });

		$(searchInput).keypress(function(e) {
			if(e.keyCode == 13) {
				e.preventDefault();
			}
		});
    });

}(jQuery));

