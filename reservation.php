<?php
session_start();
include_once('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login_tenant.php");
    exit();
}

// التحقق من وجود معرف الحجز في رابط الصفحة


$reservation_id = $_GET['id'];

// Retrieve apartment information including owner details
$sqlApartment = "SELECT a.*, p.*, i.img_path 
                FROM appartement a
                INNER JOIN proprietaire p ON a.id_proprietaire = p.id
                LEFT JOIN additional_images i ON a.id = i.apartment_id
                WHERE a.id = ?";

$stmtApartment = $conn->prepare($sqlApartment);

if ($stmtApartment) {
    $stmtApartment->bind_param("i", $reservation_id);
    $stmtApartment->execute();
    $resultApartment = $stmtApartment->get_result();

    if ($resultApartment->num_rows > 0) {
        $apartment = $resultApartment->fetch_assoc();
    } else {
        // Handle the case where no apartment is found with the given ID
        header("Location: reservation.php");
        exit();
    }
} else {
    // Handle prepare error
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Retrieve additional images for the apartment
$sqlImages = "SELECT * FROM additional_images WHERE apartment_id = ?";
$stmtImages = $conn->prepare($sqlImages);

if ($stmtImages) {
    $stmtImages->bind_param("i", $reservation_id); // Change to $reservation_id
    $stmtImages->execute();
    $resultImages = $stmtImages->get_result();
} else {
    // Handle prepare error
    echo "Error preparing statement: " . $conn->error;
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Apartment Information</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url(images/1.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 80vh;
        z-index: 1;
    }

        .container {
            max-width: 95%;
            margin: 95px auto;
            padding: 10px 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            height: 85vh;
        }

    .text-info {
        flex: 1;
        margin-right: 20px;
    }

    h2 {
        color: #333;
    }

    .apartment-info p {
        margin: 5px 0;
        color: #555;
    }

    .images-container {
        flex: 1;
        display: flex;
        flex-wrap: wrap;
        overflow-y: auto;
        max-height: 80%;
        margin: 20px 0;
    }

    .image {
        width: calc(33.33% - 10px);
        margin: 5px;
    }

    .image img {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    input[type="file"] {
        margin-bottom: 10px;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    .img_info {
        width: auto;
        height: 300px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }

    .welcome {
        margin-bottom: 20px;
    }

    .reservation-form {
        margin-top: 100px;
        padding-left: 60px;
        padding-right: 30px;
    }

    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }



    .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        padding: 20px 100px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        backdrop-filter: blur(10px);
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        z-index: 100;
    }

    .header::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent);
        transition: 0.5s;
    }

    .header:hover::before {
        left: 100%;
    }

    .logo {
        color: #fff;
        font-size: 25px;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: center;
    }

    .navbar a {
        color: #fff;
        font-size: 18px;
        text-decoration: none;
        margin-left: 35px;
        transition: 0.3s;
    }

    .navbar a:hover {
        color: #f00;
    }

    #menu-icon {
        font-size: 36px;
        color: #fff;
        display: none;
    }

    /* BREAKPOINTS */
    @media (max-width: 992px) {
        .header {
            padding: 1.25rem 4%;
        }
    }

    @media (max-width: 768px) {
        #menu-icon {
            display: block;
        }

        .navbar {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            padding: 0.5rem 4%;
            display: none;
        }

        .navbar.active {
            display: block;
        }

        .navbar a {
            display: block;
            margin: 1.5rem 0;
        }
    }

    .img {
        position: absolute;
        width: 40px;
        margin-left: -50px;
    }

    p.p1,
    p.p2 {
        color: #000000;
        display: inline;
        font-size: 1.5rem;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    p.p2 {
        color: #2798D0;
        margin-left: 5px;
    }



    *,
    *:after,
    *:before {
        box-sizing: border-box;
    }
    #date_debut{
        margin: 10px 0px 0px 0px;
    }
    #date_fin{
        margin: 10px 0px 0px 0px;
    }
    </style>
</head>

<body>

    <header class="header">
        <a href="#" class="logo">
            <p class="p1">LOCATION</p>
            <p class="p2">DE MAISON</p>
        </a>
        <img class="img" src="images/333.png" alt="images" class="logo">
        <nav class="navbar">
            <a href="house.php">Home</a>
            <a href="maison.php">Maison</a>
            <a href="appartement.php">Appartement</a>
            <a href="garage.php">Garage</a>
            <a href="hotel.php">Hotel</a>
            <a href="riad.php">Raid</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <div class="text-info">
            <!-- Apartment Information -->
            <div class="apartment-info">
                <h2>Informations sur la maison</h2>
                <p>Propriétaire : <?= $apartment['nom'] ?> <?= $apartment['prenom'] ?></p>
                <p>Contact : <?= $apartment['tele'] ?></p>
                <p>Email : <?= $apartment['email'] ?></p>
                <h2><?= $apartment['nom_app'] ?></h2>
                <p>Type : <?= $apartment['type'] ?></p>
                <p>Chambres à coucher : <?= $apartment['chambre_coucher'] ?></p>
                <p>Emplacement : <?= $apartment['location'] ?></p>
                <p>Prix : <?= $apartment['prix'] ?> DH</p>
                <img class="img_info" src="<?= $apartment['img'] ?>" alt="Image de la maison">
            </div>

        </div>

        <!-- Display existing images -->
        <div class="images-container">
            <?php while ($rowImage = $resultImages->fetch_assoc()) : ?>
            <div class="image">
                <img src="<?= $rowImage['img_path'] ?>" alt="صورة إضافية">

            </div>
            <?php endwhile; ?>
        </div>
        <div class="reservation-form">
            <h2>Reserve maintenant</h2>

            <?php
    
    if (isset($_GET['id'])) {
        $appartement_id = $_GET['id'];
        $id_client = $_SESSION['user_id'];
        echo "<form action='test.php' method='POST' class='form_img'>";
        echo "<input type='hidden' name='id_appartement' value='$appartement_id'>";
        echo "<input type='hidden' name='id_client' value='$id_client'>"; // افتراض أنه لديك عميل مسجل بالفعل
        echo "<label id='date_debut' for='date_debut'>Date début :</label>";
        echo "<input id='date_debut' type='date' name='date_debut' value=''>";
        echo "<label id='date_fin' for='date_fin'>Date fin :</label>";
        echo "<input id='date_fin' type='date' name='date_fin' value=''><br><br>";
        echo "<button type='submit'>Reserve maintenant</button>";
        echo "</form>";
    } else {
        echo "خطأ: لا يوجد معرف للمنزل.";
    }
    ?>
        </div>
    </div>
    <?php
if(isset($_GET['errmsg'])){
    $error_message = urldecode($_GET['errmsg']);
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '{$error_message}',
                    icon: 'info',
                    confirmButtonText: 'Ok'
                });
            });
          </script>";
}

if(isset($_GET['success_message'])){
    $success_message = urldecode($_GET['success_message']);
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '{$success_message}',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });
            });
          </script>";
}

if(isset($_GET['error_message'])){
    $error_message = urldecode($_GET['error_message']);
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '{$error_message}',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            });
          </script>";
}
?>
</body>

</html>