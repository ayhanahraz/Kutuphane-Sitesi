<?php
session_start();
if(!isset($_SESSION['isim'])){
    header("Location: index.php");
    exit;
}

include "db.php";

// Seçilen filtreleri al
$selected_yazar = isset($_GET['yazar']) ? $_GET['yazar'] : [];
$selected_tur   = isset($_GET['tur']) ? $_GET['tur'] : [];
$search_term    = isset($_GET['search']) ? $_GET['search'] : '';

// SQL sorgusu
$sql = "SELECT * FROM kitaplar WHERE 1=1";

// Filtreleme
if(!empty($selected_yazar)){
    $yazar_list = array_map(function($v){
        return "'".mysqli_real_escape_string($GLOBALS['conn'],$v)."'";
    }, $selected_yazar);
    $sql .= " AND yazar IN (".implode(",",$yazar_list).")";
}

if(!empty($selected_tur)){
    $tur_list = array_map(function($v){
        return "'".mysqli_real_escape_string($GLOBALS['conn'],$v)."'";
    }, $selected_tur);
    $sql .= " AND tur IN (".implode(",",$tur_list).")";
}

// Arama
if(!empty($search_term)){
    $search_term_escaped = mysqli_real_escape_string($conn, $search_term);
    $sql .= " AND isim LIKE '%$search_term_escaped%'";
}

$sql .= " ORDER BY kitap_id DESC";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Kütüphane</title>
<style>
    
body {
    margin:0; padding:0; font-family:Arial,sans-serif; background:#f4f4f4;
}
/* Header */
header {
    background:#00c3ffff; color:white; height:8vh;
    display:flex; justify-content:space-between; align-items:center;
    padding:0 2vw; box-sizing:border-box;
}
header h1 { margin:0; font-size:2.5vh; }
.profile-box { display:flex; align-items:center; gap:1vw; }
.profile-img { width:6vh; height:6vh; border-radius:50%; object-fit:cover; }
/* Layout */
.container { display:flex; width:100%; box-sizing:border-box; }
/* Sol panel */
aside { width:20vw; background:#e0e0e0; padding:2vh 1vw; box-sizing:border-box; }
aside h3 { margin-top:0; }
aside form label { display:block; margin-bottom:0.5vh; }
/* Ana içerik */
main { flex:1; padding:2vh 2vw; box-sizing:border-box; }
.kitap-container { display:block; }
.kitap {
    background:white; padding:2vh 2vw; border-radius:1vh;
    box-shadow:0 0.5vh 1vh rgba(0,0,0,0.2);
    width:100%; box-sizing:border-box; font-size:2vh; margin-bottom:1vh;
}
</style>
</head>
<body>

<header>
    <h1>KÜTÜPHANE</h1>

    <!-- Arama -->
    <form method="GET" action="" style="flex:1; display:flex; justify-content:center; margin:0 2vw;">
        <input type="text" name="search" value="<?= htmlspecialchars($search_term) ?>" placeholder="Kitap ara..." style="width:50%; padding:0.5vh; border-radius:0.5vh; border:1px solid #ccc;">
        <button type="submit" style="padding:0.5vh 1vh; margin-left:0.5vw; border:none; background:#007bff; color:white; border-radius:0.5vh;">Ara</button>
    </form>

    <div class="profile-box">
        <form action="log_in.php" method="post">
            <input type="submit" name="quit_button" value="Çıkış Yap">
        </form>
        <span style="font-size:2vh;"><?= $_SESSION['isim'] ?></span>

        <!-- Sadece admin görsün -->
        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
            <a href="admin_panel.php" style="margin-left:1vw; padding:0.5vh 1vh; background:#ff9800; color:white; border-radius:0.5vh; text-decoration:none; font-size:1.8vh;">Düzenle</a>
        <?php endif; ?>

        <a href="profile.php"><img src="profile.png" alt="Profil" class="profile-img"></a>
    </div>
</header>



<div class="container">
    <!-- Sol panel -->
    <aside>
        <h3>Filtrele</h3>
        <form method="GET" action="">
            <strong>Yazar</strong><br>
            <?php
            $yazarsql = mysqli_query($conn,"SELECT * FROM yazarlar ORDER BY yazar_adi");
            while($y = mysqli_fetch_assoc($yazarsql)){
                $checked = in_array($y['yazar_adi'],$selected_yazar)?'checked':'';
                echo '<label><input type="checkbox" name="yazar[]" value="'.$y['yazar_adi'].'" '.$checked.'> '.$y['yazar_adi'].'</label>';
            }
            ?>
            <br><strong>Tür</strong><br>
            <?php
            $tursql = mysqli_query($conn,"SELECT * FROM turler ORDER BY tur_adi");
            while($t = mysqli_fetch_assoc($tursql)){
                $checked = in_array($t['tur_adi'],$selected_tur)?'checked':'';
                echo '<label><input type="checkbox" name="tur[]" value="'.$t['tur_adi'].'" '.$checked.'> '.$t['tur_adi'].'</label>';
            }
            ?>
            <br><button type="submit">Filtrele</button>
        </form>
    </aside>

    <!-- Ana içerik -->
    <main>
        <h2>Kitaplar</h2>
        <div class="kitap-container">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($kitap = mysqli_fetch_assoc($result)): ?>
                    <div class="kitap">
                        <p><strong>Kitap:</strong> <?= $kitap['isim'] ?></p>
                        <p><strong>Yazar:</strong> <?= $kitap['yazar'] ?></p>
                        <p><strong>Tür:</strong> <?= $kitap['tur'] ?></p>
                        <p><strong>Yayınevi:</strong> <?= $kitap['yayinevi'] ?></p>
                        <p><strong>Basım Yılı:</strong> <?= $kitap['basim_yili'] ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Henüz kitap bulunamadı.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>