<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/auth.php';

$pdo = DB::get();
Auth::check();
$user = Auth::user();

$counts = $pdo->query("SELECT status, COUNT(*) as c FROM reports GROUP BY status")->fetchAll();
$summary = ['new'=>0,'in_progress'=>0,'resolved'=>0,'rejected'=>0];
foreach($counts as $r) $summary[$r['status']] = (int)$r['c'];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard Admin</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Inter,Arial}
body{
  background:#f4f6fb;
  opacity:0;
  animation:loginFade .9s ease forwards;
}
@keyframes loginFade{
  from{opacity:0;transform:translateY(20px)}
  to{opacity:1;transform:translateY(0)}
}

/* HEADER */
header{
  background:#fff;
  padding:18px 30px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  box-shadow:0 4px 16px rgba(0,0,0,.06);
}
header h1{font-size:20px}
nav span{margin-right:14px;font-weight:500}
.btn-logout{
  background:#ff4d4f;
  color:#fff;
  padding:8px 14px;
  border-radius:8px;
  text-decoration:none;
}

/* MAIN */
main{padding:30px}

/* CARDS */
.cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
  gap:20px;
  margin-bottom:30px;
}
.card{
  background:#fff;
  padding:22px;
  border-radius:16px;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  text-align:center;
  transition:.3s;
}
.card:hover{transform:translateY(-6px)}
.card strong{
  display:block;
  margin-top:10px;
  font-size:34px;
}

/* CHART */
.chart-card{
  background:#fff;
  padding:24px;
  border-radius:18px;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  max-width:420px;
  margin-bottom:30px;
}
.chart-card h2{margin-bottom:14px}

/* BUTTON */
.action-center{
  display:flex;
  gap:14px;
}
.btn-primary,.btn-outline{
  padding:12px 20px;
  border-radius:12px;
  font-weight:600;
  text-decoration:none;
}
.btn-primary{background:#4e91ff;color:#fff}
.btn-outline{
  border:2px solid #4e91ff;
  color:#4e91ff;
}
</style>
</head>

<body>

<header>
  <h1>📊 Dashboard Admin</h1>
  <nav>
    <span>Halo, <?= e($user['name']) ?></span>
    <a href="./logout.php" class="btn-logout">Logout</a>
  </nav>
</header>

<main>

<section class="cards">
  <div class="card">🆕 Baru<strong><?= $summary['new'] ?></strong></div>
  <div class="card">⏳ Proses<strong><?= $summary['in_progress'] ?></strong></div>
  <div class="card">✅ Selesai<strong><?= $summary['resolved'] ?></strong></div>
  <div class="card">❌ Ditolak<strong><?= $summary['rejected'] ?></strong></div>
</section>

<section class="chart-card">
  <h2>Distribusi Status</h2>
  <canvas id="pieChart" width="360" height="260"></canvas>
</section>

<section class="action-center">
  <a class="btn-primary" href="./report_create.php">+ Buat Laporan</a>
  <a class="btn-outline" href="./report_list.php">📋 Daftar Laporan</a>
</section>

</main>

<script>
const data = <?= json_encode(array_values($summary)) ?>;
const colors = ['#4e91ff','#ffc107','#28a745','#dc3545'];
const labels = ['Baru','Proses','Selesai','Ditolak'];

const canvas = document.getElementById('pieChart');
const ctx = canvas.getContext('2d');

const total = data.reduce((a,b)=>a+b,0) || 1;
let start = -0.5 * Math.PI;

data.forEach((v,i)=>{
  const angle = (v/total)*Math.PI*2;
  ctx.beginPath();
  ctx.moveTo(180,130);
  ctx.arc(180,130,100,start,start+angle);
  ctx.fillStyle = colors[i];
  ctx.fill();
  start += angle;
});

/* Legend */
labels.forEach((l,i)=>{
  ctx.fillStyle = colors[i];
  ctx.fillRect(20,20+i*20,12,12);
  ctx.fillStyle="#333";
  ctx.fillText(l,40,30+i*20);
});
</script>

</body>
</html>
