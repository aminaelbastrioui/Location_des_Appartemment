<?php
session_start();
include_once('db_connection.php');

if (!isset($_SESSION['owner_id'])) {
    // Redirect to the login page or handle accordingly
    header("Location: login_owner.php");
    exit();
}

$owner_id = $_SESSION['owner_id'];

// Select data from the proprietaire table based on the owner_id
$sql = "SELECT a.*, p.nom, p.prenom, p.tele, p.email
        FROM appartement a
        INNER JOIN proprietaire p ON a.id_proprietaire = p.id
        WHERE a.id_proprietaire = ?";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Handle prepare error
    $error_message = "Error preparing statement: " . $conn->error;
    echo $error_message;
}

// Supprimer les enregistrements des appartements et leurs images supplémentaires associées où la date d'enregistrement plus 30 jours est antérieure à la date actuelle
// $date_limite = date('Y-m-d', strtotime('-30 days'));

// // Sélectionner les maisons qui ont été enregistrées il y a plus de 30 jours et qui n'ont pas été réservées
// $sql = "SELECT a.id, a.img, ai.img_path
//         FROM appartement a
//         LEFT JOIN reservation r ON a.id = r.id_appartement
//         LEFT JOIN additional_images ai ON a.id = ai.apartment_id
//         WHERE a.registration_date < ? 
//         AND (r.id IS NULL OR r.date_fin < CURDATE())";

// $stmt = $conn->prepare($sql);

// if ($stmt) {
//     $stmt->bind_param("s", $date_limite);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     // Parcourir les résultats
//     while ($row = $result->fetch_assoc()) {
//         // Supprimer les images supplémentaires
//         if (!empty($row['img_path'])) {
//             unlink($row['img_path']); // Supprimer le fichier d'image
//         }

//         // Supprimer l'enregistrement de la maison
//         $delete_apartment_query = "DELETE FROM appartement WHERE id = ?";
//         $stmt_delete = $conn->prepare($delete_apartment_query);
//         if ($stmt_delete) {
//             $stmt_delete->bind_param("i", $row['id']);
//             $stmt_delete->execute();
//         }
//     }
// } else {
//     // Gérer les erreurs si nécessaire
//     $error_message = "Error preparing statement: " . $conn->error;
//     echo $error_message;
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appartements</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
      * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: sans-serif;
    }

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
        display: flex;
        height: 100vh;
        justify-content: center;
        align-items: center;
    }

    .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 100px;
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

.appartements {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px;
    margin-top: 70px; /* Adjusted margin-top */
}

.appartement-card, .card {
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    margin: 10px;
    width: 300px;
}

.appartement-card:hover, .card:hover {
    box-shadow: 2px 2px 20px white;
}

footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    position: fixed;
    bottom: 0;
    width: 100%;
}

/* section {
    padding-top: 70px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    padding-bottom: 50px;
}

.card {
    width: 300px;
    border-radius: 5px;
    margin: 2%;
    height: 450px;
    background-color: white;
} */
.card:hover {
    box-shadow: 2px 2px 20px white;
}

.image img {
    width: 100%;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    height: 200px;

}

.title {

    text-align: center;
    padding: 10px;

}

h1 {
    font-size: 20px;
}

.des {
    padding: 3px;
    text-align: center;

    padding-top: 10px;
    border-bottom-right-radius: 5px;
    border-bottom-left-radius: 5px;
}

button {
    margin-top: 40px;
    margin-bottom: 10px;
    background-color: white;
    border: 1px solid black;
    border-radius: 5px;
    padding: 10px;
}

button:hover {
    background-color: black;
    color: white;
    transition: .5s;
    cursor: pointer;
}
/* @media (max-width: 768px) {
          .card {
              width: 100%;
              margin: 2% 0;
          }
      }

      img {
          width: 100%;
          border-top-right-radius: 5px;
          border-top-left-radius: 5px;
          height: 200px;
      } */
      .card {
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    margin: 10px;
    width: 300px;
}

.reserved {
    background-color: lightcoral; /* أو أي لون آخر تفضله */
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

    <section class="appartements">
    <?php
    while ($row = $result->fetch_assoc()) {
        // استعلام SQL للتحقق مما إذا كانت الشقة محجوزة أم لا
        $appartment_id = $row['id']; // افترض أن 'id' هو معرف الشقة في جدول الشقق
        $reservation_query = "SELECT * FROM reservation WHERE id_appartement = $appartment_id";
        $reservation_result = $conn->query($reservation_query);
        $is_reserved = $reservation_result->num_rows > 0;

        // تحديد اللون بناءً على حالة الحجز
        $card_class = ($is_reserved) ? 'card reserved' : 'card';

        // إظهار البيانات بناءً على الحالة
    ?>
        <div class="<?= $card_class ?>">
            <div class="image">
                <img src="<?= $row['img'] ?>" alt="<?= $row['nom_app'] ?> Image">
                <div class="apartment">
                    <div class="title">
                        <h1><?= $row["nom_app"] ?></h1>
                    </div>
                    <div class="des">
                        <p>Type: <?= $row['type'] ?></p>
                        <p>Bedrooms: <?= $row['chambre_coucher'] ?></p>
                        <p>Location: <?= $row['location'] ?></p>
                        <p>Price: <?= $row['prix'] ?> DH</p>
                        <p>Owner: <?= $row['nom'] ?> <?= $row['prenom'] ?></p>
                        <p>Contact: <?= $row['tele'] ?></p>
                        <p>Email: <?= $row['email'] ?></p>
                        <a class="button" href="info_appartement.php?id=<?= $row['id'] ?>">Info</a>
                    </div>
                </div>
            </div>
        </div>
    <?php

    }
    
    ?>
</section>


    <!-- <footer>
         Add any footer content if needed 
    </footer> -->
</body>
</html>
