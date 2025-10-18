<?php
session_start();
include "db.php"; // Veritabanı bağlantısı

if ($_POST) {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isim = $_POST["isim"] ?? '';
    $sifre = $_POST["sifre"] ?? '';
}
    // Kullanıcı adı ve şifreyi kontrol et
    $sql = "SELECT * FROM kullanicilar WHERE isim = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $isim);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['sifre']; // Veritabanındaki şifre

        // Şifreyi kontrol et (password_verify ile)
        if (password_verify($sifre, $stored_password)) {
            // Giriş başarılıysa, session'a kullanıcı bilgilerini kaydet
            $_SESSION['isim'] = $row['isim'];
            $_SESSION['rol']  = $row['rol'];

            // Giriş sonrası yönlendirme
            header("Location: anasayfa.php");
            exit;
        } else {
            $error = "Hatalı şifre!";
        }
    } else {
        $error = "Kullanıcı adı bulunamadı!";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>A Kütüphane | Giriş</title></head>

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
input:invalid {
    border-color: #aaa; /* valid değilse bile border aynı kalsın */
    box-shadow: none;   /* glow efekt kaymasın */
}

input:focus:invalid {
    border-color: #f44336; /* kırmızı border isteğe bağlı */
    box-shadow: 0 0 1vh rgba(244,67,54,0.5);
}
</style>

<body>
    
    <div class="container">
        <h1>A Kütüphane</h1>
        <img src="logo.jpg" alt="" class="logo">
        <h2>Giriş Yap</h2>
        <div class="panel">
<form method="post">

<input type="text" name="isim" placeholder="İsim" required><br>
<input type="password" name="sifre" placeholder="Şifre" required><br>

  
 <div class="submit">
    <input type="submit" value="Giriş">
</div> 
</form>
<div class="no_account"><p>Hesabın yok mu? <a href="register.php">Kayıt Ol</a></p></div>
</div>    
</div>

</body>
</html>
