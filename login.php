<?php
session_start();


spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . '.php';
    $file = __DIR__ . '/' . $path;
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Model\AdminModel;

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Masukkan username dan password.';
    } else {
        $adminModel = new AdminModel();
        $result = $adminModel->verifyLogin($username, $password);
        
        if ($result['success']) {
            $_SESSION['user'] = $result['data']['username'];
            $_SESSION['user_role'] = $result['data']['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Username atau password salah.';
        }
    }
}

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login - Absensi</title>
    <?php include 'favicon.php'; ?>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/mainStyles.css">
    <style>
        body{font-family: Arial, sans-serif;background:#f3f4f6;display:flex;align-items:center;justify-content:center;height:100vh;margin:0; gap: 20px;}
        .card{background:#fff;padding:24px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,.06);width:360px}
        .card h2{margin-top:0;margin-bottom:12px}
        .form-row{margin-bottom:10px}
        label{display:block;font-weight:600;margin-bottom:6px}
        input[type=text],input[type=password]{width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px}
        .btn{background:#2563eb;color:#fff;padding:8px 12px;border-radius:4px;border:0;cursor:pointer}
        .error{background:#fee2e2;color:#991b1b;padding:8px;border-radius:4px;margin-bottom:10px}
    </style>
</head>
<body>
<div class="card"> 
    <img src="app_logo/logo.png" alt="" style="width: 70%; display: block; margin-left: auto; margin-right: auto; margin-bottom: 20px;">
    <h2 style="text-align: center;">Login</h2>
    <?php if (!empty($errors)): ?>
        <div class="error"><?php echo implode('<br>', $errors); ?></div>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div class="form-row">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" autocomplete="username" required autofocus>
        </div>
        <div class="form-row">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
        </div>
        <div style="display:flex;gap:8px;align-items:center;justify-content:space-between">
            <button class="btn" type="submit">Masuk</button>
            <a href="index.php" style="color:#374151;text-decoration:none;font-size:14px">Kembali</a>
        </div>
    </form>
</div>
</body>
</html>
