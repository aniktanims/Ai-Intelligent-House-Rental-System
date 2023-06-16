<?php
 
 $lat = 51.505;
 $lon = -0.09;
?>

<script>
var mymap = L.map('map').setView([<?php echo $lat ?>, <?php echo $lon ?>], 13);
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

  });
}
// MODIFY THE CODE FROM HERE
navigator.geolocation.getCurrentPosition(function(position) {
  var userLat = position.coords.latitude;
  var userLon = position.coords.longitude;

  // Define the house locations
  var houses = [    {lat: 23.814246, lon: 90.427393}, {lat: 23.819539, lon: 90.432359},   {lat: 23.821187, lon: 90.432901},    {lat: 23.824328, lon: 90.441919},    {lat: 23.821973, lon: 90.432300}  ];

  // Find the nearest house to the user's location
  var nearestDistance = Infinity;
  var nearestHouse = null;
  houses.forEach(function(house) {
    var distance = (L.latLng(userLat, userLon)).distanceTo(L.latLng(house.lat, house.lon));
    if (distance < nearestDistance) {
      nearestDistance = distance;
      nearestHouse = house;
    }
  });

  // Create a green circle marker for the nearest house with a "this house is near" popup


  L.circleMarker([nearestHouse.lat, nearestHouse.lon], {
    color: 'green',
    fillColor: '#green',
    fillOpacity: 1,
    radius: 8
  }).addTo(mymap).bindPopup("This house is near");

  // Create a red circle marker for all other houses with a popup showing the distance
  houses.forEach(function(house) {
    if (house !== nearestHouse) {
      var distance = (L.latLng(userLat, userLon)).distanceTo(L.latLng(house.lat, house.lon));
      distance = (distance / 1000).toFixed(2); // Convert to kilometers and round to 2 decimal places
      L.circleMarker([house.lat, house.lon], {
        color: 'red',
        fillColor: '#red',
        fillOpacity: 1,
        radius: 8
      }).addTo(mymap).bindPopup("Distance: " + distance + " km");
    }
  });

  // Get the route from user location to the nearest house
  L.Routing.control({
    waypoints: [
      L.latLng(userLat, userLon),
      L.latLng(nearestHouse.lat, nearestHouse.lon)
    ]
  }).addTo(mymap);

  // Set the map view to the nearest house
  mymap.setView([nearestHouse.lat, nearestHouse.lon], 13);
});

</script>