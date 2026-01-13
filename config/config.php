<?php
return [
  'db' => [
    'host' => '127.0.0.1',
    'dbname' => 'pelayanan_db',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
  ],
  'base_url' => 'http://localhost',
  'remember_cookie_name' => 'pl_rem',
  'remember_expire_days' => 14,
  'upload_dir' => __DIR__ . '/../public/uploads',
  'upload_url' => 'uploads',
  'jwt_secret' => 'SECRET_KEY_GANTI_INI_YA'
];
