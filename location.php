
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.3.4/leaflet-routing-machine.js"></script>
    <script>
        // Initialize the Leaflet map
        var map = L.map('map').setView([0, 0], 13);

        // Create a tile layer (you can choose a different tile provider if desired)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Locate the user's current position
        map.locate({ setView: true, maxZoom: 16 });

        // Handle the location found event
        function onLocationFound(e) {
            var userMarker = L.marker([e.latitude, e.longitude], {
                icon: L.icon({
                    iconUrl: './images/marker.png',
                    iconSize: [60, 85]
                })
            }).addTo(map);
            userMarker.bindPopup("You are here").openPopup();

            <?php
            $id = $_REQUEST['pid']; 
            $query = mysqli_query($con, "SELECT property.*, user.*, property.longitude, property.latitude FROM `property`, `user` WHERE property.uid = user.uid AND pid = '$id'");
            while ($row = mysqli_fetch_array($query)) {
                $longitude = $row['longitude'];
                $latitude = $row['latitude'];
            ?>
                // Add a marker for the property location
                var propertyMarker = L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map);
                propertyMarker.bindPopup("Property Location").openPopup();

                // Create a routing control
                L.Routing.control({
                    waypoints: [
                        L.latLng(e.latitude, e.longitude), // user's current location
                        L.latLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>) // property location
                    ],
                    routeWhileDragging: true
                }).addTo(map);
            <?php
            }
            ?>
        }

        // Handle the location error event
        function onLocationError(e) {
            alert(e.message);
        }

        // Attach the event listeners
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
    </script>