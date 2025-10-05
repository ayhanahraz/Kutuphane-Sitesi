<?php
session_start();
include "db.php";

if($_POST){
    $isim = $_POST['isim'];
    $sifre = $_POST['sifre'];
    $rol = 'user';

    $check = mysqli_query($conn, "SELECT * FROM kullanicilar WHERE= isim ='$isim'");
    if(mysqli_num_rows($check)> 0){
        echo "Bu kullanıcı adı zaten mevcut!";
    }
    else{
        $sql = "INSERT INTO kullanicilar (isim, sifre, rol) VALUES('$isim', '$sifre','$rol')";  
    
        if(mysqli_query($conn,$sql)){
            echo "Kayıt Başarılı! <br> <a href='log_in.php'>Giriş Sayfası</a>";
        }
        else{
            echo "Hata: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Kayıt Ol</h2>

    <form action="post">

İsim: <input type="text" name="isim" required><br>
Şifre: <input type="password" name="sifre" required><br>
<input type="submit" value="Kayıt Ol">
<p>Zaten hesabın var mı?</p><a href="log_in.php">giriş yap</a>
    </form>
</body>
</html>