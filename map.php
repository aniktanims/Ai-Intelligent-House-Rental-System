<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Leaflet in PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.js"></script>
    <style>
      #map {
        height: 400px;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <h1>Leaflet in PHP</h1>
    <div id="map"></div>
    <?php
      // Your PHP code here to generate coordinates
      $lat = 51.505;
      $lon = -0.09;
    ?>
    <script>
      var mymap = L.map('map').setView([<?php echo $lat ?>, <?php echo $lon ?>], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        maxZoom: 18,
      }).addTo(mymap);
      L.marker([<?php echo $lat ?>, <?php echo $lon ?>]).addTo(mymap);
    </script>
  </body>
</html>
