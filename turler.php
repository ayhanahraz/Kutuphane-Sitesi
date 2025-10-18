<?php
session_start();
if($_SESSION['rol']!="yonetici"){ header("Location: kitaplar.php"); exit; }
include "db.php";

if($_POST){
  $tur = $_POST['tur'];
  mysqli_query($conn, "INSERT INTO turler (tur_adi) VALUES ('$tur')");
}

$result = mysqli_query($conn, "SELECT * FROM turler");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Türler</title></head>
<body>
<h2>Türler</h2>
<form method="post">
  Tür Adı: <input type="text" name="tur">
  <input type="submit" value="Ekle">
</form>
<ul>
<?php while($row = mysqli_fetch_assoc($result)): ?>
  <li><?= $row['tur_adi'] ?></li>
<?php endwhile; ?>
</ul>
</body>
</html>
