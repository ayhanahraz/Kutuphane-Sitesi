<?php
session_start();
if($_SESSION['rol']!="yonetici"){ header("Location: kitaplar.php"); exit; }
include "db.php";

if($_POST){
  $yazar = $_POST['yazar'];
  mysqli_query($conn, "INSERT INTO yazarlar (yazar_adi) VALUES ('$yazar')");
}

$result = mysqli_query($conn, "SELECT * FROM yazarlar");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Yazarlar</title></head>
<body>
<h2>Yazarlar</h2>
<form method="post">
  Yazar Adı: <input type="text" name="yazar">
  <input type="submit" value="Ekle">
</form>
<ul>
<?php while($row = mysqli_fetch_assoc($result)): ?>
  <li><?= $row['yazar_adi'] ?></li>
<?php endwhile; ?>
</ul>
</body>
</html>
