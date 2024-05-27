
<?php
session_start();
include_once('db_connection.php');

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    header("Location: login_tenant.php");
    exit();
}

// Validate reservation ID in the URL
$reservation_id = isset($_POST['id_appartement']) ? $_POST['id_appartement'] : null;

if (!$reservation_id || !is_numeric($reservation_id)) {
    // إعادة توجيه إذا كانت الهوية غير صالحة
    header("Location: reservation.php");
    exit();
}

// Retrieve apartment information including owner details
$sqlApartment = "SELECT a.*, p.*, i.img_path 
                FROM appartement a
                INNER JOIN proprietaire p ON a.id_proprietaire = p.id
                LEFT JOIN additional_images i ON a.id = i.apartment_id
                WHERE a.id = ?";

$stmtApartment = $conn->prepare($sqlApartment);

// Check if the statement was prepared successfully
if (!$stmtApartment) {
    error_log("Error preparing statement: " . $conn->error);
    exit();
}

$stmtApartment->bind_param("i", $reservation_id);
$stmtApartment->execute();
$resultApartment = $stmtApartment->get_result();

// Check if apartment data is found
if ($resultApartment->num_rows > 0) {
    $apartment = $resultApartment->fetch_assoc();
} else {
    // Redirect if no apartment is found
    header("Location: reservation.php");
    exit();
}

$stmtApartment->close();

// Retrieve additional images for the apartment
$sqlImages = "SELECT * FROM additional_images WHERE apartment_id = ?";
$stmtImages = $conn->prepare($sqlImages);

// Check if the statement was prepared successfully
if (!$stmtImages) {
    error_log("Error preparing statement: " . $conn->error);
    exit();
}

$stmtImages->bind_param("i", $reservation_id);
$stmtImages->execute();
$resultImages = $stmtImages->get_result();

// Handle reservation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form data
    $id_appartement = $_POST['id_appartement'];
    $id_client = $_POST['id_client'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Check if the client exists before inserting the reservation
    $checkClientQuery = "SELECT id FROM client WHERE id = ?";
    $stmtCheckClient = $conn->prepare($checkClientQuery);

    if ($stmtCheckClient) {
        $stmtCheckClient->bind_param("i", $id_client);
        $stmtCheckClient->execute();
        $resultCheckClient = $stmtCheckClient->get_result();

        if ($resultCheckClient->num_rows > 0) {
            // Client exists, proceed with inserting the reservation
            $insertReservationQuery = "INSERT INTO reservation (id_appartement, id_client, date_debut, date_fin) VALUES (?, ?, ?, ?)";
            $stmtInsertReservation = $conn->prepare($insertReservationQuery);

            if ($stmtInsertReservation) {
                $stmtInsertReservation->bind_param("iiss", $id_appartement, $id_client, $date_debut, $date_fin);

                if ($stmtInsertReservation->execute()) {
                    $success_message = "Reservation inserted successfully!";
                    header("Location: reservation.php?success_message=" . urlencode($success_message)."&id=$id_appartement");
                    exit();
                } else {
                    $error_message = "Error inserting reservation: " ;
                    header("Location: reservation.php?errmsg=" . urlencode($error_message));
                    exit();
                }

                $stmtInsertReservation->close();
            } else {
                // Handle prepare error
                $error_message = "Error preparing statement for reservation: " . $conn->error;
                header("Location: reservation.php?errmsg=" . urlencode($error_message));
                exit();
            }
        } else {
            // Client does not exist, handle accordingly
            $error_message = "Error: Client with ID $id_client does not exist.";
            header("Location: reservation.php?errmsg=" . urlencode($error_message));
            exit();
        }

        $stmtCheckClient->close();
    } else {
        // Handle prepare error
        $error_message = "Error preparing statement for client check: " . $conn->error;
        header("Location: reservation.php?errmsg=" . urlencode($error_message));
        exit();
    }
}


// Close database connection
$conn->close();
?>
