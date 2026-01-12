<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];


$query = mysqli_query($conn, "
    SELECT * FROM riwayat 
    WHERE username='$username' 
    ORDER BY tanggal DESC
");

$riwayat_data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $riwayat_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | Valorant FPS Analyzer</title>
    <link rel="stylesheet" href="CSS/base.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/profil.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-section">
                <img src="Assets/Logo_valo.png" alt="Valorant Logo" class="logo">
                <span class="app-name">Valorant FPS Analyzer</span>
            </div>
            <div class="hamburger-menu" onclick="toggleMenu()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <nav class="nav-menu" id="navMenu">
                <a href="index.php">
                    <i class="fas fa-home"></i>
                    Beranda
                </a>
                <a href="diagnosa.php">
                    <i class="fas fa-stethoscope"></i>
                    Diagnosa
                </a>
                <a href="profil.php" class="active">
                    <i class="fas fa-user"></i>
                    Profil
                </a>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Keluar
                </a>
            </nav>
        </div>
    </header>

    <main class="profile-main">
        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="avatar-section">
                    <div class="avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h2 id="profileName"><?php echo htmlspecialchars($username); ?></h2>
                        <p class="player-rank">
                            <i class="fas fa-trophy"></i>
                            <span id="playerRank">Memuat...</span>
                        </p>
                        <p class="member-since">
                            <i class="fas fa-calendar-alt"></i>
                            Member sejak: <span id="memberSince">-</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="profile-stats">
                <h3><i class="fas fa-chart-bar"></i> Statistik Performa</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <span class="stat-number" id="totalDiagnosa">0</span>
                        <span class="stat-label">Total Diagnosa</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="stat-number" id="lastActivity">-</span>
                        <span class="stat-label">Aktivitas Terakhir</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-trending-up"></i>
                        </div>
                        <span class="stat-number" id="improvementScore">-</span>
                        <span class="stat-label">Improvement Score</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <span class="stat-number" id="completionRate">0%</span>
                        <span class="stat-label">Completion Rate</span>
                    </div>
                </div>
            </div>

            <!-- History Section -->
            <div class="history-section">
                <div class="section-header">
                    <h3><i class="fas fa-history"></i> Riwayat Diagnosa</h3>
                </div>
                <div class="history-list" id="historyList">
                    <?php if(empty($riwayat_data)): ?>
                        <div class="empty-history">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Belum ada riwayat diagnosa</p>
                            <small>Mulai diagnosa pertama Anda untuk melihat riwayat di sini</small>
                        </div>
                    <?php else: ?>
                        <?php foreach($riwayat_data as $index => $riwayat): ?>
                        <div class="history-item">
                            <div class="history-content">
                                <div class="history-message">
                                    <?php echo htmlspecialchars($riwayat['nama_hasil']); ?>
                                </div>
                                <div class="history-date">
                                    <i class="far fa-clock"></i>
                                    <?php echo date('d F Y H:i', strtotime($riwayat['tanggal'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="profile-actions">
                <button class="action-btn primary" onclick="window.location.href='diagnosa.php'">
                    <i class="fas fa-plus"></i>
                    Diagnosa Baru
                </button>
                <button class="logout-btn-large" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i>
                    Keluar Akun
                </button>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Player Support</h3>
                <p>Butuh bantuan dengan analisis skill-mu?</p>
                <a href="mailto:support@valorantanalyzer.com" class="support-link">
                    <i class="fas fa-headset"></i>
                    support@valorantanalyzer.com
                </a>
            </div>
            <div class="footer-section">
                <h3>Join Community</h3>
                <p>Diskusi dengan player lain</p>
                <div class="social-links">
                    <a href="#" class="social-link">
                        <i class="fab fa-discord"></i>
                        Discord
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-reddit"></i>
                        Reddit
                    </a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Resources</h3>
                <a href="#" class="resource-link">
                    <i class="fas fa-book"></i>
                    Tips & Panduan
                </a>
                <a href="#" class="resource-link">
                    <i class="fas fa-video"></i>
                    Video Tutorial
                </a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Valorant FPS Analyzer | By Fauzan Azis</p>
        </div>
    </footer>

    <script>
    const riwayatFromDB = <?php echo json_encode($riwayat_data); ?>;
    const currentUsername = "<?php echo $username; ?>";
    </script>
    <script src="JS/element.js"></script>
    <script src="JS/profil.js"></script>
</body>
</html>