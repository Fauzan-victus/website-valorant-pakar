<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_SESSION['username'];
    $answers  = json_decode($_POST['answers'], true);

    $hasil = analyzeDiagnosisRuleBased($answers, $conn);

    $kode_hasil = $hasil['kode'];
    $nama_hasil = $hasil['nama'];

    mysqli_query($conn, "
        INSERT INTO riwayat (username, kode_hasil, nama_hasil)
        VALUES ('$username', '$kode_hasil', '$nama_hasil')
    ");

    header("Location: hasil.php");
    exit;
}

function analyzeDiagnosisRuleBased($answers, $conn) {

    $map = [];
    $q = mysqli_query($conn, "SELECT id, kode FROM gejala");

    while ($g = mysqli_fetch_assoc($q)) {
        $map[$g['id']] = $g['kode'];
    }


    $jawaban_kode = [];

    foreach ($answers as $id_gejala => $jawaban) {
        if (isset($map[$id_gejala])) {
            $jawaban_kode[$map[$id_gejala]] = $jawaban;
        }
    }

    $aturan = mysqli_query($conn, "
        SELECT * FROM aturan
        WHERE kode_hasil != 'H006'
        ORDER BY id ASC
    ");

    while ($rule = mysqli_fetch_assoc($aturan)) {

        $gejala_rule = array_map(
            'trim',
            explode(',', $rule['kode_gejala'])
        );

        $match = true;

        foreach ($gejala_rule as $g) {
            if (
                !isset($jawaban_kode[$g]) ||
                $jawaban_kode[$g] !== 'ya'
            ) {
                $match = false;
                break;
            }
        }


        if ($match) {
            return mysqli_fetch_assoc(
                mysqli_query(
                    $conn,
                    "SELECT * FROM hasil WHERE kode='{$rule['kode_hasil']}' LIMIT 1"
                )
            );
        }
    }

    return mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM hasil WHERE kode='H006' LIMIT 1")
    );
}
?>
