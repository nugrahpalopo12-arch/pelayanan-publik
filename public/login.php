<!doctype html>
<html>
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="./css/auth.css">
</head>
<body>
<div class="auth-box">
  <h2>Login</h2>
  <div id="alert" class="alert error" style="display:none;"></div>
  
  <form id="loginForm">
    <label>Email <input type="email" name="email" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit">Masuk</button>
  </form>
  
  <p>Belum punya akun? <a href="./register.php">Daftar</a></p>
  <p><a href="./index.php">Ke Beranda</a></p>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const alertBox = document.getElementById('alert');
  const btn = e.target.querySelector('button');
  
  alertBox.style.display = 'none';
  btn.disabled = true;
  btn.innerText = 'Loading...';

  const formData = new FormData(this);
  const data = Object.fromEntries(formData.entries());

  try {
    const res = await fetch('api/auth/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await res.json();
    
    if (res.ok) {
        localStorage.setItem('token', result.token);
        localStorage.setItem('user', JSON.stringify(result.user));
        window.location.href = 'index.php'; 
    } else {
        alertBox.innerText = result.error || 'Login gagal';
        alertBox.style.display = 'block';
    }
  } catch (err) {
      alertBox.innerText = 'Terjadi kesalahan koneksi';
      alertBox.style.display = 'block';
  } finally {
      btn.disabled = false;
      btn.innerText = 'Masuk';
  }
});
</script>
</body>
</html>
