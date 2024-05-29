<?php
include 'db.php';

// Query untuk mendapatkan data GeoJSON
$sql = "SELECT id, nama, geojson, nilai_smart FROM your_table";  // Pastikan tabel dan kolom sesuai
$result = $conn->query($sql);
$geojsonData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $geojson = json_decode($row['geojson'], true);
        $geojson['properties']['nama'] = $row['nama'];
        $geojson['properties']['nilai_smart'] = (float)$row['nilai_smart'];
        $geojsonData[] = $geojson;
    }
}

header('Content-Type: application/json');
echo json_encode($geojsonData);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Choropleth Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            width: 100%;
            height: 600px;
        }
        .legend {
            line-height: 18px;
            color: #555;
            background: white;
            padding: 6px 8px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-8.836956, 116.3525879], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Function to get color based on value
        function getColor(d) {
            return d > 0.8 ? '#800026' :
                   d > 0.6 ? '#BD0026' :
                   d > 0.4 ? '#E31A1C' :
                   d > 0.2 ? '#FC4E2A' :
                   d > 0.1 ? '#FD8D3C' :
                             '#FEB24C';
        }

        // Function to style each feature
        function style(feature) {
            return {
                fillColor: getColor(feature.properties.nilai_smart),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            };
        }

        // Load GeoJSON data
        fetch('path/to/your_geojson_endpoint.php')
            .then(response => response.json())
            .then(data => {
                L.geoJson(data, { style: style, onEachFeature: onEachFeature }).addTo(map);
            });

        // Function to handle feature actions
        function onEachFeature(feature, layer) {
            layer.bindPopup('<b>' + feature.properties.nama + '</b><br>Zona Level: ' + feature.properties.nilai_smart);
        }

        // Adding legend
        var legend = L.control({ position: 'bottomright' });

        legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'legend'),
                grades = [0, 0.1, 0.2, 0.4, 0.6, 0.8],
                labels = [],
                from, to;

            for (var i = 0; i < grades.length; i++) {
                from = grades[i];
                to = grades[i + 1];

                labels.push(
                    '<i style="background:' + getColor(from + 0.1) + '"></i> ' +
                    from + (to ? '&ndash;' + to : '+'));
            }

            div.innerHTML = labels.join('<br>');
            return div;
        };

        legend.addTo(map);
    </script>
</body>

</html>
