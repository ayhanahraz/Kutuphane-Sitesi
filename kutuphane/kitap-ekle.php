<?php
session_start();
if($_SESSION['rol']!="yonetici"){ header("Location: kitaplar.php"); exit; }
include "db.php";

if($_POST){
  $isim = $_POST['isim'];
  $yazar = $_POST['yazar_id'];
  $tur = $_POST['tur_id'];
  $yayinevi = $_POST['yayinevi'];
  $yil = $_POST['yil'];

  mysqli_query($conn, "INSERT INTO kitaplar (isim,yazar_id,tur_id,yayinevi,basim_yili)
                       VALUES ('$isim','$yazar','$tur','$yayinevi','$yil')");
  header("Location: kitaplar.php");
}

$yazarlar = mysqli_query($conn, "SELECT * FROM yazarlar");
$turler = mysqli_query($conn, "SELECT * FROM turler");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Kitap Ekle</title></head>
<body>
<h2>Kitap Ekle</h2>
<form method="post">
  İsim: <input type="text" name="isim"><br>
  Yazar: <select name="yazar_id">
    <?php while($y = mysqli_fetch_assoc($yazarlar)): ?>
      <option value="<?= $y['id'] ?>"><?= $y['yazar_adi'] ?></option>
    <?php endwhile; ?>
  </select><br>
  Tür: <select name="tur_id">
    <?php while($t = mysqli_fetch_assoc($turler)): ?>
      <option value="<?= $t['id'] ?>"><?= $t['tur_adi'] ?></option>
    <?php endwhile; ?>
  </select><br>
  Yayınevi: <input type="text" name="yayinevi"><br>
  Basım Yılı: <input type="number" name="yil"><br>
  <input type="submit" value="Ekle">
</form>
</body>
</html>
