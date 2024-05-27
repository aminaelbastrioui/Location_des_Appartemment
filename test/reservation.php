<?php
session_start();
include_once('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من وجود الجلسة هنا
    // if (!isset($_SESSION["user_id"])) {
    //     // إذا لم يكن المستخدم قد سجل دخوله، يمكنك إعادته إلى صفحة تسجيل الدخول أو عرض رسالة خطأ
    //     header("Location: login.php");
    //     exit();
    // }

    $id_client = $_SESSION["user_id"]; // افتراضي أن العميل قد سجل دخوله
    $id_appartement = $_POST["id_appartement"];
    $date_debut = date('Y-m-d', strtotime($_POST["date_debut"])); // تنسيق التاريخ
    $date_fin = date('Y-m-d', strtotime($_POST["date_fin"])); // تنسيق التاريخ

    // استخدام استعلام معلمة لتفادي هجمات SQL Injection
    $sql = $conn->prepare("INSERT INTO reservation (id_client, id_appartement, date_debut, date_fin) VALUES (?, ?, ?, ?)");
    $sql->bind_param("iiss", $id_client, $id_appartement, $date_debut, $date_fin);

    if ($sql->execute()) {
        echo "تم إجراء الحجز بنجاح.";
    } else {
        echo "حدث خطأ أثناء إجراء الحجز: " . $conn->error;
    }

    $sql->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>صفحة الحجز</title>
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

    <h2>احجز المنزل الآن</h2>

    <?php
    if (isset($_GET['id'])) {
        $appartement_id = $_GET['id'];
        echo "<form action='reservation.php' method='post'>";
        echo "<input type='hidden' name='id_appartement' value='$appartement_id'>";
        echo "<input type='hidden' name='id_client' value='1'>"; // افتراض أنه لديك عميل مسجل بالفعل
        echo "<input type='hidden' name='date_debut' value='2024-02-01'>";
        echo "<input type='hidden' name='date_fin' value='2024-02-10'>";
        echo "<button type='submit'>احجز الآن</button>";
        echo "</form>";
    } else {
        echo "خطأ: لا يوجد معرف للمنزل.";
    }
    ?>

    <div>
        <a href="houses.php">العودة إلى صفحة المنازل</a>
    </div>
</body>
</html>