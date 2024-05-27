<?php
session_start();
include_once('db_connection.php');

$sql = "SELECT * FROM appartement";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>عرض المنازل</title>
</head>
<body>
    <div>
        <?php
        if (isset($_SESSION['user_type'])) {
            echo "مرحبًا بك، {$_SESSION['user_type']}!";
            echo "<a href='logout.php'>تسجيل الخروج</a>";
        }
        ?>
    </div>

    <h2>المنازل المتاحة</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='house-container'>";
            echo "<h3>{$row['nom_app']}</h3>";
            echo "<p>النوع: {$row['type']}</p>";
            echo "<p>عدد غرف النوم: {$row['chambre_coucher']}</p>";
            echo "<p>الموقع: {$row['location']}</p>";
            echo "<img src='{$row['img']}' alt='صورة المنزل'>";
            echo "<p>السعر: {$row['prix']}</p>";
            echo "<a href='reservation.php?id={$row['id']}'>احجز الآن</a>";
            echo "</div>";
        }
    } else {
        echo "لا توجد منازل متاحة حاليًا.";
    }
    ?>

    <div>
        <a href="reservation.php">انتقل إلى صفحة الحجز</a>
    </div>
</body>
</html>
