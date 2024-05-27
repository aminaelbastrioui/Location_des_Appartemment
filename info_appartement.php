


<?php
session_start();
include_once('db_connection.php');

if (!isset($_SESSION['owner_id'])) {
    // Redirect to the login page or handle accordingly
    header("Location: login_owner.php");
    exit();
}

if (!isset($_GET['id'])) {
    // Handle the case where 'id' parameter is missing
    header("Location: home_appartement.php");
    exit();
}

$apartment_id = $_GET['id'];

// Retrieve apartment information including owner details
$sqlApartment = "SELECT a.*, p.*, i.img_path 
                FROM appartement a
                INNER JOIN proprietaire p ON a.id_proprietaire = p.id
                LEFT JOIN additional_images i ON a.id = i.apartment_id
                WHERE a.id = ?";

$stmtApartment = $conn->prepare($sqlApartment);

if ($stmtApartment) {
    $stmtApartment->bind_param("i", $apartment_id);
    $stmtApartment->execute();
    $resultApartment = $stmtApartment->get_result();

    if ($resultApartment->num_rows > 0) {
        $apartment = $resultApartment->fetch_assoc();
    } else {
        // Handle the case where no apartment is found with the given ID
        header("Location: home_appartement.php");
        exit();
    }
} else {
    // Handle prepare error
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Handle image upload
if (isset($_POST['uploadImages'])) {

    $uploadDirectory = "uploads/";

if (!is_dir($uploadDirectory)) {
    // قم بإنشاء الدليل إذا لم يكن موجودًا
    mkdir($uploadDirectory, 0777, true);

    // تحقق مما إذا كان إنشاء الدليل ناجحًا
    if (!is_dir($uploadDirectory)) {
        die("فشل في إنشاء دليل 'uploads'");
    }
}
    $uploadDirectory = "uploads/";  // Change this to your desired directory
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    foreach ($_FILES['image']['name'] as $key => $name) {
        $tempName = $_FILES['image']['tmp_name'][$key];
        $fileType = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $targetFile = $uploadDirectory . uniqid() . '.' . $fileType;

        if (in_array($fileType, $allowedExtensions)) {
            move_uploaded_file($tempName, $targetFile);

            // Insert image path into additional_images table
            $sqlInsertImage = "INSERT INTO additional_images (img_path, apartment_id) VALUES (?, ?)";
            $stmtInsertImage = $conn->prepare($sqlInsertImage);

            if ($stmtInsertImage) {
                $stmtInsertImage->bind_param("si", $targetFile, $apartment_id);
                $stmtInsertImage->execute();
                $stmtInsertImage->close();
            } else {
                // Handle prepare error
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            // Handle invalid file type
            echo "Invalid file type!";
        }
    }
}

// Retrieve additional images for the apartment
$sqlImages = "SELECT * FROM additional_images WHERE apartment_id = ?";
$stmtImages = $conn->prepare($sqlImages);

if ($stmtImages) {
    $stmtImages->bind_param("i", $apartment_id);
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
            max-height: 80%; /* ارتفاع العناصر القابلة للتمرير */
            margin: 50px;
        }

        .image {
            width: calc(33.33% - 10px); /* 3 عمود بنسبة 33.33% مع هامش بينهما */
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

        .form_img{
            margin-top: 100px;
            padding-left: 60px;
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
        <div class="text-info">
            <div class="apartment-info">

                <h2>Owner Information</h2>
                <p>Owner: <?= $apartment['nom'] ?> <?= $apartment['prenom'] ?></p>
                <p>Contact: <?= $apartment['tele'] ?></p>
                <p>Email: <?= $apartment['email'] ?></p>
                <h2><?= $apartment['nom_app'] ?></h2>
                <p>Type: <?= $apartment['type'] ?></p>
                <p>Bedrooms: <?= $apartment['chambre_coucher'] ?></p>
                <p>Location: <?= $apartment['location'] ?></p>
                <p>Price: <?= $apartment['prix'] ?> DH</p>
                <!-- Display Apartment Image -->
                <img class="img_info" src="<?= $apartment['img'] ?>" alt="Apartment Image" style="width: auto; height: 300px;">
            </div>
        </div>

        <!-- Display existing images -->
        <div class="images-container">
            <?php while ($rowImage = $resultImages->fetch_assoc()) : ?>
                <div class="image">
                    <img src="<?= $rowImage['img_path'] ?>" alt="Additional Image">
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Form for uploading additional images -->
        <form action="info_appartement.php?id=<?= $apartment_id ?>" method="post" enctype="multipart/form-data" class="form_img">
            <label for="image"><h2>Upload More Images:</h2></label>
            <input type="file" name="image[]" id="image" accept="image/*" multiple required><br><br>
            <button type="submit" name="uploadImages">Upload Images</button>
        </form>
    </div>

</body>
</html>

