<?php
session_start();
include_once('db_connection.php');

if (isset($_GET['success_message'])) {
    $success_message = urldecode($_GET['success_message']);
    if (!empty($success_message)) {
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
}

if (isset($_GET['error_message'])) {
    $error_message = urldecode($_GET['error_message']);
    if (!empty($error_message)) {
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
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Responsive Registration Form</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style/insert_appartement_form.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
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
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>Ajouter un Appartement</h2>
        <form action="tr.php" method="post" enctype="multipart/form-data">
            <div class="main-user-info">
                <div class="user-input-box">
                    <label for="nom_app">Appartment Name:</label>
                    <input class="p1" type="text" name="nom_app" id="nom_app" required>
                </div>
                <br>
                <div class="user-input-box">
                    <label for="type">Appartment Type:</label>
                    <select class="p1" id="type" name="type" required>
                    <option value="maison">Maison</option>
                    <option value="appartement">Appartement</option>
                    <option value="garage">Garage</option>
                    <option value="hotel">Hôtel</option>
                    <option value="riad">Riad</option>
                    </select>
                    <!-- <input class="p1" type="text" name="type" id="type" required> -->
                </div>
                <br>
                <div class="user-input-box">
                    <label for="chambre_coucher">Number of Bedrooms:</label>
                    <input class="p1" type="number" name="chambre_coucher" id="chambre_coucher" required>
                </div>
                <br>
                <div class="user-input-box">
                    <label for="location">Location:</label>
                    <input class="p1" type="text" name="location" id="location" required>
                </div>
                <br>
                <div class="user-input-box">
                    <label for="prix">Price:</label>
                    <input class="p1" type="number" name="prix" id="prix" step="0.01" required>
                </div>
                <br>
                <!-- <div class="user-input-box">
                    <label for="id_proprietaire">Owner ID:</label>
                    <input class="p1" type="number" name="id_proprietaire" id="id_proprietaire" required>
                </div> -->
                <br>
                <div class="bb user-input-box">
                    <label for="image">Choose Image:</label>
                    <input class="p3" type="file" name="image" id="image" accept="image/*" required>
                </div>
                <br>
            </div>
            <div class="form-submit-btn">
                <input class="p2" type="submit" value="Upload Image and Appartement Data" name="Connexion7">
            </div>
        </form>
    </div>
</body>
</html>