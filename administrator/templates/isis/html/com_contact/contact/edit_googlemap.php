<?php
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query
        ->select('*')
        ->from('#__contact_details')
        ->where('id = 1');
    $db->setQuery($query);
    $result = $db->loadObject();
    //get information maps
    $con_lat = $result->con_lat;
    $con_lng = $result->con_lng; 
    $show_maps = $result->show_maps;
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    var geocoder = new google.maps.Geocoder();

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function (responses) {
            if (responses && responses.length > 0) {
                updateMarkerAddress(responses[0].formatted_address);
            } else {
                updateMarkerAddress('Cannot determine address at this location.');
            }
        });
    }


    function updateMarkerPositionLat(latLng) {
        document.getElementById('lat').value = [
            latLng.lat()
        ];
    }

    function updateMarkerPositionLng(latLng) {
        document.getElementById('lng').value = [
            latLng.lng()
        ];
    }

    function initialize() {
        var latLng = new google.maps.LatLng(<?php echo $con_lat; ?>, <?php echo $con_lng; ?>);
        var map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 8,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var marker = new google.maps.Marker({
            position: latLng,
            title: 'Baselli',
            map: map,
            draggable: true
        });

        // Update current position info.
        updateMarkerPositionLat(latLng);
        updateMarkerPositionLng(latLng);
        geocodePosition(latLng);

        // Add dragging event listeners.
        google.maps.event.addListener(marker, 'dragstart', function () {
            updateMarkerAddress('');
        });

        google.maps.event.addListener(marker, 'drag', function () {
            updateMarkerPositionLat(marker.getPosition());
            updateMarkerPositionLng(marker.getPosition());
        });

        google.maps.event.addListener(marker, 'dragend', function () {
            geocodePosition(marker.getPosition());
        });
    }

// Onload handler to fire off the app.
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<style>
    #mapCanvas {width: 100%; height: 400px;}

</style>

<div id="infoPanel" style="margin-bottom: 20px">
    <input type="text" name="con_lat" value="" id="lat" />
    <input type="text" name="con_lng" value="" id="lng" />
    <select id="show_maps" name="show_maps">
        <option value="1" <?php if($show_maps == 1): ?> selected="selected"<?php endif; ?>> Show maps </option>
        <option value="0" <?php if($show_maps == 0): ?> selected="selected"<?php endif; ?>> Disiable maps </option>
    </select>
</div>
<div id="mapCanvas"></div>
