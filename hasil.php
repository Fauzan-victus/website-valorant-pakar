<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];


$query = mysqli_query($conn, "
    SELECT r.*, h.* 
    FROM riwayat r
    LEFT JOIN hasil h ON r.kode_hasil = h.kode
    WHERE r.username = '$username'
    ORDER BY r.tanggal DESC
    LIMIT 1
");

if (mysqli_num_rows($query) > 0) {
    $hasil_detail = mysqli_fetch_assoc($query);
    $kode_hasil = $hasil_detail['kode'];
    $nama_hasil = $hasil_detail['nama'];
} else {
   
    $kode_hasil = isset($_GET['kode']) ? $_GET['kode'] : 'H006';
    $nama_hasil = isset($_GET['nama']) ? $_GET['nama'] : 'Skill Seimbang';
    
    
    $query = mysqli_query($conn, "SELECT * FROM hasil WHERE kode = '$kode_hasil' LIMIT 1");
    $hasil_detail = mysqli_fetch_assoc($query);
}

if (!$hasil_detail) {
    
    $hasil_detail = [
        'kode' => 'H006',
        'nama' => 'Skill Seimbang',
        'deskripsi' => 'Fundamental sudah baik, perlu tingkatkan skill lebih lanjut.',
        'solusi' => '1. Master agent baru\r\n2. Latih advanced techniques\r\n3. Main scrim dengan tim\r\n4. Analisis VOD pro player'
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa | Valorant FPS Analyzer</title>
    <link rel="stylesheet" href="CSS/base.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/modal.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .result-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--bg) 0%, var(--secondary) 100%);
        }
        
        .result-card {
            background: var(--card-bg);
            border-radius: 20px;
            border: 2px solid var(--primary);
            padding: 50px;
            max-width: 700px;
            width: 100%;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        
        .result-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .result-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }
        
        .result-message {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .tips-section {
            background: rgba(255, 70, 85, 0.05);
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid var(--primary);
            margin-bottom: 30px;
        }
        
        .tips-section h4 {
            color: var(--primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .result-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .result-btn {
            flex: 1;
            min-width: 150px;
            padding: 15px 25px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-home {
            background: var(--primary);
            color: white;
        }
        
        .btn-diagnosa {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border);
            color: var(--text);
        }
        
        .result-btn:hover {
            transform: translateY(-3px);
        }
        
        .btn-home:hover {
            background: var(--primary-dark);
            box-shadow: 0 8px 20px rgba(255, 70, 85, 0.3);
        }
        
        .btn-diagnosa:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="result-page">
        <div class="result-card">
            <div class="result-header">
                <div class="result-icon">🎯</div>
                <h2><?php echo htmlspecialchars($hasil_detail['nama']); ?></h2>
            </div>
            
            <div class="result-message">
                <p><?php echo nl2br(htmlspecialchars($hasil_detail['deskripsi'])); ?></p>
            </div>
            
            <div class="tips-section">
                <h4><i class="fas fa-lightbulb"></i> Tips Improvement:</h4>
                <?php 
                // Perbaiki pemisah string - di database menggunakan \r\n
                $solusi_lines = preg_split('/\r\n|\n|\r/', $hasil_detail['solusi']);
                foreach ($solusi_lines as $line):
                    if (!empty(trim($line))):
                ?>
                    <p>• <?php echo htmlspecialchars(trim($line)); ?></p>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            
            <div class="result-actions">
                <button class="result-btn btn-home" onclick="window.location.href='index.php'">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </button>
                <button class="result-btn btn-diagnosa" onclick="window.location.href='diagnosa.php'">
                    <i class="fas fa-redo"></i>
                    Ulangi Diagnosa
                </button>
                <button class="result-btn btn-diagnosa" onclick="window.location.href='profil.php'">
                    <i class="fas fa-history"></i>
                    Lihat Riwayat
                </button>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border); text-align: center;">
                <p style="color: var(--text-secondary); font-size: 0.9em;">
                    <i class="fas fa-info-circle"></i>
                    Hasil ini telah disimpan ke riwayat diagnosa Anda
                </p>
            </div>
        </div>
    </div>
    
    <script src="JS/element.js"></script>
</body>
</html>