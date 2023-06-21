<?php

//Directions Service (POST)
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.openrouteservice.org/v2/directions/driving-car");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, '{"coordinates":[[-60.222827,-33.329607],[-60.227879,-33.331952],[-60.226850,-33.333042],[-60.232418,-33.329754],[-60.228531,-33.335857],[-60.236950,-33.334706]]}');

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Accept: application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8",
  "Authorization: 5b3ce3597851110001cf6248eaf57b0b75014607bb3d53ba0fc558e5",
  "Content-Type: application/json; charset=utf-8"
));

$response = curl_exec($ch);
curl_close($ch);

/* var_dump($response); */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="polyline.js"></script>


    <!--  recursos para generar ruta entre coordenadas
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" / -->>
    <title>Document</title>
</head>
<body>
<div id="map" style="height: 400px;"></div>


<script>
// Crea un objeto de mapa en un contenedor HTML específico
const map = L.map('map').setView([-33.38151239916761,-60.216151025578654], 13); 

// Agrega un proveedor de mapas (por ejemplo, OpenStreetMap)
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);


 var json = JSON.parse('<?php echo $response;  ?>');
// Obtiene las coordenadas de la ruta del JSON de ORS
var coordinates = json.routes[0].geometry;



// Decodifica las coordenadas utilizando la biblioteca polyline.js
var decodedCoordinates = polyline.decode(coordinates);


//obtiene los waypoints originales
var puntos = decodedCoordinates;
var waypoints = json.routes[0].way_points;

var orderedCoordinates = [];


for (var i = 0; i < waypoints.length; i++) {
  var index = waypoints[i];
  var coordinate = puntos[index];
  orderedCoordinates.push(coordinate);
}


 orderedCoordinates.forEach(element => {
    L.marker(element).addTo(map);
});


/* generar ruta entre coordenadas

L.Routing.control({
    waypoints: orderedCoordinates
}).addTo(map); */

// Crea una polilínea en el mapa utilizando las coordenadas decodificadas
var polyline = L.polyline(decodedCoordinates).addTo(map);


// Ajusta el mapa para que se muestre la ruta completa
map.fitBounds(polyline.getBounds());


</script>
</body>
</html>

