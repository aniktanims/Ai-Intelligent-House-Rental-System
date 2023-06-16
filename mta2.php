<?php
$con = mysqli_connect("localhost", "root", "", "baribhara");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = "SELECT pid, title, price, location, latitude, longitude FROM property";
$result = mysqli_query($con, $query);
$houses = array();
while ($row = mysqli_fetch_assoc($result)) {
    $houses[] = $row;
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.css" />

<script>
var mymap = L.map('map').setView([51.505, -0.09], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
  maxZoom: 18,
}).addTo(mymap);

// Get user's current location
if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var userLat = position.coords.latitude;
    var userLon = position.coords.longitude;
    mymap.setView([userLat, userLon], 13);
    L.marker([userLat, userLon], {icon: L.icon({iconUrl: './images/marker.png', iconSize: [50, 82]})}).addTo(mymap);

    // MODIFY THE CODE FROM HERE
    var location = <?php echo json_encode($houses); ?>;

    var nearestDistance = Infinity;
    var nearestHouse = null;

    location.forEach(function(house) {
      var houseLat = house.latitude;
      var houseLon = house.longitude;

      var distance = (L.latLng(userLat, userLon)).distanceTo(L.latLng(houseLat, houseLon));
      if (distance < nearestDistance) {
        nearestDistance = distance;
        nearestHouse = house;
      }
    });

    var greenIcon = L.icon({
      iconUrl: './images/green-marker.png',
      iconSize: [50, 62],
      iconAnchor: [16, 30]
    });

    L.marker([nearestHouse.latitude, nearestHouse.longitude], { icon: greenIcon })
      .addTo(mymap)
      .bindPopup("<b>" + nearestHouse.title + "</b><br>Price: ৳" + nearestHouse.price + "<br>Location: " + nearestHouse.location + "<br><a href='http://localhost/baribhara/propertydetail.php?pid=" + nearestHouse.pid + "' target='_blank'>View Details</a>")
      .openPopup();

    location.forEach(function(house) {
      var houseLat = house.latitude;
      var houseLon = house.longitude;

      if (house !== nearestHouse) {
        var distance = (L.latLng(userLat, userLon)).distanceTo(L.latLng(houseLat, houseLon));
        distance = (distance / 1000).toFixed(2); // Convert to kilometers and round to 2 decimal places

        var redIcon = L.icon({
          iconUrl: './images/red-marker.png',
          iconSize: [50, 62],
          iconAnchor: [16, 32]
        });

        L.marker([houseLat, houseLon], { icon: redIcon })
          .addTo(mymap)
          .bindPopup("<b>" + house.title + "</b><br>Price: ৳" + house.price + "<br>Location: " + house.location + "<br>Distance: " + distance + " km<br><a href='http://localhost/baribhara/propertydetail.php?pid=" + house.pid + "' target='_blank'>View Details</a>");
      }
    });

    L.Routing.control({
      waypoints: [
        L.latLng(userLat, userLon),
        L.latLng(nearestHouse.latitude, nearestHouse.longitude)
      ]
    }).addTo(mymap);
  });
}
</script>
