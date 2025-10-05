<?php
session_start();
if(!isset($_SESSION['isim'])){ header("Location: index.php"); exit; }
include "db.php";

$sql = "SELECT k.isim, y.yazar_adi, t.tur_adi, k.yayinevi, k.basim_yili
        FROM kitaplar k
        LEFT JOIN yazarlar y ON k.yazar_id=y.id
        LEFT JOIN turler t ON k.tur_id=t.id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Kitaplar</title></head>
<body>
<h2>Kitaplar</h2>
<a href="logout.php">Çıkış</a>
<?php if($_SESSION['rol']=="yonetici"): ?>
 | <a href="kitap_ekle.php">Kitap Ekle</a>
 | <a href="yazarlar.php">Yazarlar</a>
 | <a href="turler.php">Türler</a>
<?php endif; ?>
<table border="1">
<tr><th>İsim</th><th>Yazar</th><th>Tür</th><th>Yayınevi</th><th>Basım Yılı</th></tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
  <td><?= $row['isim'] ?></td>
  <td><?= $row['yazar_adi'] ?></td>
  <td><?= $row['tur_adi'] ?></td>
  <td><?= $row['yayinevi'] ?></td>
  <td><?= $row['basim_yili'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
