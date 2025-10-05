<?php
session_start();
include "db.php";

if($_POST){
    $isim = $_POST['isim'];
    $sifre = md5($_POST['sifre']); // md5 ile kontrol

    $sql = "SELECT * FROM kullanicilar WHERE isim='$isim' AND sifre='$sifre'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['isim'] = $row['isim'];
        $_SESSION['rol']  = $row['rol'];
        header("Location: welcome.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Login</title></head>

<style>

.video-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1; /* container'ın arkasında kalır */
}

#bgVideo {
    width: 100%;
    height: 100%;
    object-fit: cover; /* video ekrana sığsın ve kırpılmasın */
    filter: brightness(0.6); /* softlaştırır, göz yormaz */
}

body{
    z-index: 1;
     background-color: rgba(255,255,255,0.85);
font-family: Arial, sans-serif;
    background-color: #FAF8F1;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 2vh;
     box-sizing: border-box;
}
h2 {
    text-align: center;
    margin-bottom: 3vh;
    color: #333;
    font-weight: 700;        
    font-size: 3vh;          
}
.container{
background-color: #96A78D;
    padding:10vh;
    margin-bottom:2vh;
    border-radius:1.2vh;
    box-shadow: 0 0 8vh rgba(0,0,0,0.2);
    width: 90vw;
    max-width:45vh;
    box-sizing:border-box;
    font-size:2vh;
    border: 0.3vh solid #0ff; /* parlak neon renk */
    box-shadow: 0 0 10px #0ff, 0 0 20px #0ff, 0 0 30px #0ff;
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

input[type="text"]::placeholder,
input[type="password"]::placeholder{
    color: #888;
}

input[type="text"]:hover,
input[type="password"]:hover {
    border-color: #0ff; /* hoverda border cyan neon */
    box-shadow: 0 0 0.5vh #0ff; /* hafif glow */
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
    border-color: #ccc; /* valid değilse bile border aynı kalsın */
    box-shadow: none;   /* glow efekt kaymasın */
}

input:focus:invalid {
    border-color: #f44336; /* kırmızı border isteğe bağlı */
    box-shadow: 0 0 1vh rgba(244,67,54,0.5);
}
</style>

<body>

<div class="video-bg">
    <video autoplay muted loop id="bgVideo">
        <source src="video.mp4" type="video/mp4">
        Tarayıcınız video etiketini desteklemiyor.
    </video>
</div>

    <div class="container">
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
