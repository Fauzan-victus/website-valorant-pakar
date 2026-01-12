<?php

session_start();
require_once "config.php";


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}


if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}


$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username !== '') {
    
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = $username;

        header("Location: index.php");
        exit;
    } else {
        $error = "Nama pemain wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Valorant FPS Analyzer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="CSS/base.css">
    <link rel="stylesheet" href="CSS/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="login-body">

<div class="login-container">
    <div class="login-card">

        <div class="logo-header">
            <img src="Assets/Logo_valo.png" class="login-logo" alt="Valorant Logo">
            <h1>Valorant FPS Analyzer</h1>
        </div>

        <p class="login-subtitle">Masukkan nama untuk mulai diagnosa</p>

        <form method="POST">

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input 
                    type="text" 
                    name="username" 
                    placeholder="Nama Pemain" 
                    required
                >
            </div>

            <?php if ($error): ?>
                <div class="error-message show">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>
                Mulai Analisis
            </button>

        </form>

        <div class="login-features">
            <div class="feature">
                <i class="fas fa-bolt"></i>
                <span>Analisis Cepat</span>
            </div>
            <div class="feature">
                <i class="fas fa-chart-line"></i>
                <span>Laporan Detail</span>
            </div>
            <div class="feature">
                <i class="fas fa-history"></i>
                <span>Riwayat Analisis</span>
            </div>
        </div>

    </div>
</div>

</body>
</html>
