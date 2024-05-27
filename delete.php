<?php
session_start();
include_once('db_connection.php');

if (!isset($_SESSION['owner_id'])) {
    // Redirect to the login page or handle accordingly
    header("Location: login_owner.php");
    exit();
}

$owner_id = $_SESSION['owner_id'];

// Check if the property ID is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $property_id = htmlspecialchars($_GET['id']);

    // Perform the deletion query for reservations first
    $sql_reservations = "DELETE FROM reservation WHERE id_appartement = $property_id";

    if ($conn->query($sql_reservations) === TRUE) {
        // Reservation records deleted successfully, now delete the additional images
        $sql_additional_images = "DELETE FROM additional_images WHERE apartment_id = $property_id";

        if ($conn->query($sql_additional_images) === TRUE) {
            // Additional images deleted successfully, now delete the apartment
            $sql_apartment = "DELETE FROM appartement WHERE id = $property_id";

            if ($conn->query($sql_apartment) === TRUE) {
                header("Location: delete_appartement_owner.php");
            } else {
                echo "Error deleting apartment: " . $conn->error;
            }
        } else {
            echo "Error deleting additional images: " . $conn->error;
        }
    } else {
        echo "Error deleting reservations: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Apartment ID not provided";
}
?>
