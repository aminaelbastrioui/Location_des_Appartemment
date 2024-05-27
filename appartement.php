<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Glassmorphism Navbar HTML CSS JS | Codehal</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
    .card {
        background-color: #fff; /* لون افتراضي للبطاقة */
    }

    .reserved {
        background-color: lightcoral /* لون مختلف للبطاقة إذا كانت محجوزة */
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
    <div class="main">
        <?php
    include_once('db_connection.php');
    $sql = "SELECT * FROM appartement where type = 'appartement'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    
        $apartment_id = $row['id'];
                    $reservation_query = "SELECT * FROM reservation WHERE id_appartement = $apartment_id";
                    $reservation_result = $conn->query($reservation_query);
                    $is_reserved = $reservation_result->num_rows > 0;
                    $card_class = $is_reserved ? 'reserved' : '';
        ?>
        <div class="card <?= $card_class ?>">
            <div class="image">
                <div class="apartment">
                    <img class="img1" src="<?= $row['img'] ?>" alt="Apartment Image">
                    <div class="title">
                        <h1><?= $row["nom_app"] ?></h1>
                    </div>
                    <div class="des">
                        <p>Type: <?= $row['type'] ?></p>
                        <p>Bedrooms: <?= $row['chambre_coucher'] ?></p>
                        <p>Location: <?= $row['location'] ?></p>
                        <p>Price: <?= $row['prix'] ?> DH</p>
                        <a class="button" href="reservation.php?id=<?= $row['id'] ?>">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
    } else {
        $error_message = "<p>No available Appartements <br> <br> at the moment.</p>";
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
    ?>
    </div>
    <script>
    const cards = document.querySelectorAll('.card');
    function transition() {
        if (this.classList.contains('active')) {
            this.classList.remove('active')
        } else {
            this.classList.add('active');
        }
    }
    cards.forEach(card => card.addEventListener('click', transition));
    </script>
</body>
</html>