<!doctype html>
<html>
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daftar</title>
  <link rel="stylesheet" href="./css/auth.css">
</head>
<body>
<div class="auth-box">
  <h2>Daftar Akun</h2>
  <div id="alert" class="alert error" style="display:none;"></div>
  
  <form id="registerForm">
    <label>Nama Lengkap <input type="text" name="name" required></label>
    <label>Email <input type="email" name="email" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit">Daftar</button>
  </form>

  <p>Sudah punya akun? <a href="./login.php">Login</a></p>
  <p><a href="./index.php">Ke Beranda</a></p>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const alertBox = document.getElementById('alert');
  const btn = e.target.querySelector('button');

  alertBox.style.display = 'none';
  btn.disabled = true;
  btn.innerText = 'Loading...';

  const formData = new FormData(this);
  const data = Object.fromEntries(formData.entries());

  try {
    const res = await fetch('api/auth/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await res.json();
    
    if (res.ok) {
        localStorage.setItem('token', result.token);
        localStorage.setItem('user', JSON.stringify(result.user));
        alert('Pendaftaran berhasil!');
        window.location.href = 'index.php'; 
    } else {
        alertBox.innerText = result.error || 'Pendaftaran gagal';
        alertBox.style.display = 'block';
    }
  } catch (err) {
      alertBox.innerText = 'Terjadi kesalahan koneksi';
      alertBox.style.display = 'block';
  } finally {
      btn.disabled = false;
      btn.innerText = 'Daftar';
  }
});
</script>
</body>
</html>
