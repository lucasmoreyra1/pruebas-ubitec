<?php
include './polylineEncoder.php';
// Parámetros de la solicitud
$origin = 'mitre 215, san nicolas de los arroyos, buenos aires, argentina';
$destination = 'mitre 215, san nicolas de los arroyos, buenos aires, argentina';
$waypoints = array('mitre 280, san nicolas de los arroyos, buenos aires, argentina','nacion 345, san nicolas de los arroyos, buenos aires, argentina', 'falcon 560, san nicolas de los arroyos, buenos aires, argentina'  );
$api_key = 'AIzaSyDGc0UBAR_Y30fX31EvaU65KATMx0c0ItI'; // Reemplaza con tu propia clave de API de Google Maps

// Construir la URL de la solicitud
$url = 'https://maps.googleapis.com/maps/api/directions/json?';
$url .= 'origin=' . urlencode($origin);
$url .= '&destination=' . urlencode($destination);
$url .= '&waypoints=optimize:true|' . urlencode(implode('|', $waypoints));
$url .= '&key=' . urlencode($api_key);



// Realizar la solicitud HTTP
$response = file_get_contents($url);

// Decodificar la respuesta JSON
$data = json_decode($response);

$coords = $data->routes[0]->overview_polyline->points;
echo $coords;
$resultado = decodePolylineToArray($coords);

// Procesar la respuesta
if ($data->status == 'OK') {
    // Obtener las ciudades en orden
    $legs = $data->routes[0]->legs;
    $cities = array();
    foreach ($legs as $leg) {
        $cities[] = $leg->start_address;
    }
    $cities[] = end($legs)->end_address;

    // Obtener las coordenadas de las ciudades
    $coordinates = array();
    foreach ($legs as $leg) {
        $coordinates[] = array(
            'lat' => $leg->start_location->lat,
            'lng' => $leg->start_location->lng
        );
    }
    $coordinates[] = array(
        'lat' => end($legs)->end_location->lat,
        'lng' => end($legs)->end_location->lng
    );

    // Imprimir las ciudades y sus coordenadas
    for ($i = 0; $i < count($cities); $i++) {
        echo 'Ciudad: ' . $cities[$i] . '<br>';
        echo 'Latitud: ' . $coordinates[$i]['lat'] . '<br>';
        echo 'Longitud: ' . $coordinates[$i]['lng'] . '<br><br>';
    }
} else {
    echo 'Error al obtener las direcciones: ' . $data->status;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="polyline.js"></script>
    <title>Document</title>
</head>
<body>
<div id="map" style="height: 400px;"></div>

    <script>

const map = L.map('map').setView([-33.38151239916761,-60.216151025578654], 13); 

// Agrega un proveedor de mapas (por ejemplo, OpenStreetMap)
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

    var decodedCoordinates = <?php echo json_encode($resultado);?>;;
    console.log(decodedCoordinates);

    var polyline = L.polyline(decodedCoordinates).addTo(map);


// Ajusta el mapa para que se muestre la ruta completa
    map.fitBounds(polyline.getBounds());
    </script>
</body>
</html>





