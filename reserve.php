<?php
session_start();
include_once('db_connection.php');

// Assuming $conn is your database connection
$logged_in_owner_id = $_SESSION['owner_id'];

$sql_apartments = "SELECT id FROM appartement WHERE id_proprietaire = $logged_in_owner_id";
$result_apartments = $conn->query($sql_apartments);

$reservations = array();

if ($result_apartments->num_rows > 0) {
    while($row_apartment = $result_apartments->fetch_assoc()) {
        $apartment_id = $row_apartment['id'];
        $sql_reservations = "SELECT reservation.*, appartement.nom_app, appartement.img
                             FROM reservation
                             INNER JOIN appartement ON reservation.id_appartement = appartement.id
                             WHERE id_appartement = $apartment_id";
        $result_reservations = $conn->query($sql_reservations);

        if ($result_reservations->num_rows > 0) {
            while($row_reservation = $result_reservations->fetch_assoc()) {
                $reservations[] = $row_reservation;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations for Property Owner</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style/house.css">
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
            <a href="home_owner.php">Home</a>
            <a href="insert_appartement_form.php">Ajouter appartement</a>
            <a href="delete_appartement_owner.php">Delete appartement</a>
            <a href="reserve.php">Reservation</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

<div class="main">
    <?php if (!empty($reservations)): ?>
        <?php foreach ($reservations as $reservation): ?>
            <div class="card">
                <div class="image">
                    <div class="apartment">
                        <img class="img1" src="<?php echo $reservation['img']; ?>" alt="Apartment Image">
                        <div class="title">
                            <h1><?= $reservation["nom_app"] ?></h1>
                        </div>
                        <div class="des">
                            <p>ID: <?php echo $reservation['id']; ?></p>
                            <p>Client ID: <?php echo $reservation['id_client']; ?></p>
                            <p>Apartment ID: <?php echo $reservation['id_appartement']; ?></p>
                            <p>Start Date: <?php echo $reservation['date_debut']; ?></p>
                            <p>End Date: <?php echo $reservation['date_fin']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: {
        $error_message = "<span>No reservations found for your apartments.</span>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '{$error_message}',
                        icon: 'info',
                        confirmButtonText: 'Ok'
                    });
                });
              </script>";
    } endif; ?>
</div>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
