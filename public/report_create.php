<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/auth.php';

if (!Auth::check()) {
  header('Location: ./login.php'); exit;
}
$pdo = DB::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_check($_POST['_csrf'] ?? '')) {
    flash_set('error','Invalid CSRF token');
    header('Location: /report_create.php'); exit;
  }
  $title = trim($_POST['title'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $cat = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;

  if (!$title || !$desc) {
    flash_set('error','Judul dan deskripsi wajib.');
    header('Location: /report_create.php'); exit;
  }

  $imgPath = null;
  if (!empty($_FILES['image']['name'])) {
    $cfg = require __DIR__ . '/../config/config.php';
    $allowed = ['image/jpeg','image/png'];
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      flash_set('error','Upload error.');
      header('Location: /report_create.php'); exit;
    }
    if ($_FILES['image']['size'] > 2*1024*1024) {
      flash_set('error','File terlalu besar (max 2MB).');
      header('Location: /report_create.php'); exit;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $m = finfo_file($finfo, $_FILES['image']['tmp_name']);
    finfo_close($finfo);
    if (!in_array($m, $allowed)) {
      flash_set('error','Tipe file tidak diizinkan.');
      header('Location: /report_create.php'); exit;
    }
    $ext = $m === 'image/png' ? '.png' : '.jpg';
    $fname = bin2hex(random_bytes(12)) . $ext;
    $cfg = require __DIR__ . '/../config/config.php';
    $dst = rtrim($cfg['upload_dir'], '/\\') . DIRECTORY_SEPARATOR . $fname;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dst)) {
      flash_set('error','Gagal menyimpan file.');
      header('Location: /report_create.php'); exit;
    }
    $imgPath = rtrim($cfg['upload_url'], '/\\') . '/' . $fname;
  }

  $imgDesc = null; 
  $lat = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
  $lng = !empty($_POST['longitude']) ? $_POST['longitude'] : null;

  $stmt = $pdo->prepare('INSERT INTO reports (user_id, category_id, title, description, image_path, latitude, longitude, sla_deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
  $sla = date('Y-m-d H:i:s', strtotime('+3 days'));
  $stmt->execute([ $_SESSION['user']['id'], $cat, $title, $desc, $imgPath, $lat, $lng, $sla ]);
  flash_set('success','Laporan terkirim.');
  log_action($_SESSION['user']['id'], 'create_report', json_encode(['title'=>$title]));
  header('Location: report_list.php'); exit;
}

$cats = $pdo->query('SELECT * FROM categories')->fetchAll();
$token = csrf_token();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Buat Laporan</title>
<link rel="stylesheet" href="./css/report_create.css">
<body>
<a class="back-btn" href="index.php">← Kembali</a>
<?php if ($m = flash_get('error')): ?><div class="alert error"><?= e($m) ?></div><?php endif; ?>
<form method="post" action="report_create.php" enctype="multipart/form-data">
  <input type="hidden" name="_csrf" value="<?= e($token) ?>">
  <label>Judul <input type="text" name="title" required></label>
  <label>Kategori
    <select name="category_id">
      <option value="">Pilih</option>
      <?php foreach($cats as $c): ?>
        <option value="<?= e($c['id']) ?>"><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Deskripsi <textarea name="description" required></textarea></label>
  <label>Lokasi Kejadian (Klik pada peta)
    <div id="map" style="height: 300px; margin-bottom: 10px; border: 1px solid #ddd;"></div>
    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lng">
  </label>
  <label>Gambar (opsional) <input type="file" name="image" accept="image/png,image/jpeg"></label>
  <button type="submit">Kirim</button>
</form>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
  // Initialize map
  var map = L.map('map').setView([-6.200000, 106.816666], 13); // Default Jakarta

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  var marker;

  function onMapClick(e) {
      if (marker) {
        map.removeLayer(marker);
      }
      marker = L.marker(e.latlng).addTo(map);
      document.getElementById('lat').value = e.latlng.lat;
      document.getElementById('lng').value = e.latlng.lng;
  }

  map.on('click', onMapClick);

  // Try to get user location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var lat = position.coords.latitude;
      var lng = position.coords.longitude;
      map.setView([lat, lng], 15);
    });
  }
</script>
</body></html>
