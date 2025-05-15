<?php
session_start();
include 'config/db.php';

// Membersihkan input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $val) {
        $_POST[$key] = mysqli_real_escape_string($conn, htmlspecialchars(stripslashes(trim($val))));
    }
}

// Menentukan halaman saat ini
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-nav">
        <div class="header">
            <div class="container">
                <h1>Sistem Informasi Akademik</h1>
            </div>
        </div>
        
        <div class="navbar">
            <div class="container">
                <ul>
                    <li><a href="index.php?page=dashboard" <?php echo $page == 'dashboard' ? 'class="active"' : ''; ?>>Dashboard</a></li>
                    <li><a href="index.php?page=mahasiswa" <?php echo $page == 'mahasiswa' ? 'class="active"' : ''; ?>>Mahasiswa</a></li>
                    <li><a href="index.php?page=matakuliah" <?php echo $page == 'matakuliah' ? 'class="active"' : ''; ?>>Mata Kuliah</a></li>
                    <li><a href="index.php?page=krs" <?php echo $page == 'krs' ? 'class="active"' : ''; ?>>KRS</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php
        if (isset($_SESSION['alert'])) {
            echo "<div class='alert alert-success'>{$_SESSION['alert']}</div>";
            unset($_SESSION['alert']);
        }

        switch ($page) {
            case 'dashboard':
                include 'pages/dashboard.php';
                break;
            case 'mahasiswa':
                include 'pages/mahasiswa.php';
                break;
            case 'matakuliah':
                include 'pages/matakuliah.php';
                break;
            case 'krs':
                include 'pages/krs.php';
                break;
            default:
                include 'pages/dashboard.php';
        }
        ?>
    </div>
    
    <div class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistem Informasi Akademik</p>
        </div>
    </div>
</body>
</html>
