<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/auth.php';

$pdo = DB::get();
// Fetch only reports with coordinates
$stmt = $pdo->query("SELECT r.*, u.name as reporter, c.name as category FROM reports r LEFT JOIN users u ON u.id = r.user_id LEFT JOIN categories c ON c.id = r.category_id WHERE r.latitude IS NOT NULL AND r.longitude IS NOT NULL");
$reports = $stmt->fetchAll();

$reportsJson = json_encode(array_map(function($r) {
    return [
        'id' => $r['id'],
        'title' => e($r['title']),
        'desc' => e($r['description']),
        'lat' => (float)$r['latitude'],
        'lng' => (float)$r['longitude'],
        'category' => e($r['category']),
        'status' => e($r['status']),
        'image' => !empty($r['image_path']) ? e($r['image_path']) : null
    ];
}, $reports));
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Peta Laporan</title>
    <link rel="stylesheet" href="./css/report.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map { height: 80vh; width: 100%; border: 1px solid #ccc; margin-top: 10px; }
        .popup-img { max-width: 100%; height: auto; margin-top: 5px; border-radius: 4px; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <a class="back-btn" href="report_list.php">← Kembali ke Daftar</a>
        <h1>Peta Sebaran Laporan</h1>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([-6.200000, 106.816666], 12);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var reports = <?= $reportsJson ?>;
        var group = L.featureGroup();

        reports.forEach(function(r) {
            var popupContent = '<b>' + r.title + '</b><br>' +
                               '<small>' + r.category + ' - ' + r.status + '</small><br>' +
                               r.desc;
            if (r.image) {
                popupContent += '<br><img src="' + r.image + '" class="popup-img">';
            }
            
            var marker = L.marker([r.lat, r.lng])
                .bindPopup(popupContent)
                .addTo(group);
        });

        if (reports.length > 0) {
            group.addTo(map);
            map.fitBounds(group.getBounds(), {padding: [50, 50]});
        }
    </script>
</body>
</html>
