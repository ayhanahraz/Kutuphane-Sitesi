<?php
session_start();
include "db.php"; // $conn

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isim = trim($_POST['isim'] ?? '');
    $sifre = $_POST['sifre'] ?? '';
    $sifre_tekrar = $_POST['sifre_tekrar'] ?? '';

    // Basit validasyon: İsim ve şifre gerekli
    if ($isim == '' || $sifre == '') {
        $error_message = "İsim ve şifre gerekli.";
    }

    // Şifrelerin eşleşip eşleşmediğini kontrol et
    if ($sifre != $sifre_tekrar) {
        $error_message = "Şifreler eşleşmiyor!";
    }

    // Kullanıcı adı zaten var mı? (prepared statement)
    if ($error_message === '') {
        $checkStmt = mysqli_prepare($conn, "SELECT kullanici_id FROM kullanicilar WHERE isim = ?");
        if ($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, "s", $isim);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                $error_message = "Bu kullanıcı adı zaten mevcut!";
            }
            mysqli_stmt_close($checkStmt);
        } else {
            $error_message = "Sorgu hazırlanamadı: " . mysqli_error($conn);
        }
    }

    // Eğer hata yoksa, kullanıcıyı veritabanına ekleyelim
    if ($error_message === '') {
        $rol = 'kullanici';
        $hashed_sifre = password_hash($sifre, PASSWORD_DEFAULT);
        $rol_esc = mysqli_real_escape_string($conn, $rol);

        $insertSql = "INSERT INTO kullanicilar (isim, sifre, rol) VALUES (?, ?, '{$rol_esc}')";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        if ($insertStmt) {
            mysqli_stmt_bind_param($insertStmt, "ss", $isim, $hashed_sifre);
            if (mysqli_stmt_execute($insertStmt)) {
                $success_message = "Kayıt Başarılı! <a href='log_in.php'>Giriş Yap</a>";
            } else {
                $error_message = "Hata: " . mysqli_stmt_error($insertStmt);
            }
            mysqli_stmt_close($insertStmt);
        } else {
            $error_message = "Kayıt sorgusu hazırlanamadı: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Kütüphane | Kayıt Ol</title>

    <style>
        body{
            background-color: rgba(255,255,255,0.85);
            font-family: Arial, sans-serif;
            background-image: url('background_login.jpg');
            background-repeat: no-repeat;
            background-size:cover;  
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 2vh;
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            margin-bottom: 3vh;
            color: #333;
            font-weight: 700;        
            font-size: 3vh;          
        }

        h2 {
            text-align: center;
            margin-bottom: 3vh;
            color: #333;
            font-weight: 700;        
            font-size: 3vh;          
        }

        .container{ 
            background-color: #ECEEDF;
            padding:10vh;
            margin-bottom:2vh; 
            border-radius:1.2vh; 
            box-shadow: 0 0 8vh rgba(0,0,0,0.2);
            width: 90vw;
            max-width:70vh;
            box-sizing:border-box;
            font-size:2vh;
            border: 0.3vh solid #BBDCE5;
            box-shadow: 0 0 10px #BBDCE5, 0 0 20px #BBDCE5, 0 0 30px #BBDCE5; 
        }

        .logo {
            display: block;
            margin: 0 auto 2vh auto; 
            width: 15vh;
            height: 15vh;
            object-fit: cover;
            border-radius: 50%; 
            box-shadow: 0 0 10px #BBDCE5, 0 0 20px #BBDCE5;
        }

        input[type="text"],
        input[type="password"]{
            width: 100%;
            padding: 2vh;
            margin-bottom: 2vh;
            border-radius:0.8vh;
            border: 0.2vh solid #ccc;
            box-sizing:border-box;
            font-size:2vh;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:hover,
        input[type="password"]:hover {
            border-color: #0ff; 
            box-shadow: 0 0 0.5vh #0ff;
        }

        input[type="submit"]{
            width: 100%;
            padding: 2vh;
            border-radius:0.8vh;
            border:none;
            background-color: #4CAF50;
            color:white;
            font-size:2vh;
            cursor:pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover{
            background-color: #348a37ff;
        }

        .no_account{
            text-align:center;
            margin-top:2vh;
        }
        .no_account a{
            color: #0066ffff;
            text-decoration:none;
        }
        .no_account a:hover{
            text-decoration:underline;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>A Kütüphane</h1>
        <img src="logo.jpg" alt="" class="logo">
        <h2>Kayıt Ol</h2>

        <!-- Hata Mesajı -->
        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Başarı Mesajı -->
        <?php if ($success_message): ?>
            <div class="success"><?= $success_message ?></div>
        <?php endif; ?>

        <div class="panel">
            <form method="post">
                <input type="text" name="isim" placeholder="İsim" required><br>
                <input type="password" name="sifre" placeholder="Şifre" required><br>
                <input type="password" name="sifre_tekrar" placeholder="Şifreyi Tekrar Gir" required><br>

                <div class="submit">
                    <input type="submit" value="Kayıt Ol">
                </div> 
            </form>
            <div class="yes_account"><p>Hesabın zaten var mı? <a href="log_in.php">Giriş Yap</a></p></div>
        </div>    
    </div>

</body>
</html>
