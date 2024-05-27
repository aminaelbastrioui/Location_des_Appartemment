<?php
include_once('db_connection.php');
session_start();
if(isset($_POST['owner'])){
    header("Location:login_owner.php");
}

if(isset($_POST['tenant'])){
    header("Location:login_tenant.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create1"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tele = $_POST["tele"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $table_name = "proprietaire";

    $sql = $conn->prepare("INSERT INTO $table_name (nom, prenom, tele, email, password) VALUES (?, ?, ?, ?, ?)");

    $sql->bind_param("sssss", $nom, $prenom, $tele, $email, $hashedPassword);

    if ($sql->execute()) {
        $success_message = "Account successfully created";
        header("Location: login_owner.php?success_message=" . urlencode($success_message));
        exit();
    } else {
        $error_message = "Error creating account" . $sql->error;
        header("Location: login_owner.php?error_message=" . urlencode($error_message));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create2"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tele = $_POST["tele"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $table_name = "client";

    $sql = $conn->prepare("INSERT INTO $table_name (nom, prenom, tele, email, password) VALUES (?, ?, ?, ?, ?)");

    $sql->bind_param("sssss", $nom, $prenom, $tele, $email, $hashedPassword);

    if ($sql->execute()) {
        $success_message = "Account successfully created";
        header("Location: login_tenant.php?success_message=" . urlencode($success_message));
        exit();
    } else {
        $error_message = "Error creating account" . $sql->error;
        header("Location: login_tenant.php?error_message=" . urlencode($error_message));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Connexion"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Avoid SQL Injection using a prepared statement
    $stmt = $conn->prepare("SELECT id, password FROM proprietaire WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // Verify the password using password_verify for accuracy and security
        if (password_verify($password, $user_data['password'])) {
            // Successful login
            session_start();

            $_SESSION['owner_id'] = $user_data['id'];
            // $_SESSION['owner_id'] = $user_data['owner_id'];
            $_SESSION['user_type'] = 'proprietaire';

            // Redirect to a secure page (insert_appartement_form.php)
            header("Location: home_owner.php");
            exit();
        }
    }

    // If the email or password is incorrect
    $error_message = "The email and password are incorrect. Please create a new account if you do not have one, or try again.";

    // Consider logging the error instead of exposing it in the URL
    error_log("Login failed for email: $email");

    // Redirect to the login page with an error message
    header("Location: login_owner.php?errmsg=" . urlencode($error_message));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Connexion2"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // يتم تجنب SQL Injection باستخدام استعلام محضر
    $stmt = $conn->prepare("SELECT * FROM client WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // يتم التحقق من كلمة المرور باستخدام password_verify لضمان الدقة والأمان
        if (password_verify($password, $user_data['password'])) {
            // بيانات صحيحة
            session_start();
            // يمكنك استخدام الجلسات هنا
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['user_type'] = 'client';
            header("Location: house.php");
            exit();
        }
    }

    // إذا كان البريد الإلكتروني أو كلمة المرور غير صحيحة
    $error_message = "The email and password are incorrect. Please create a new account if you do not have one, or try again";
    header("Location: login_tenant.php?errmsg=" . urlencode($error_message));
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Update the paths to PHPMailer library
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Your HTML code...

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Connexion3'])) {
   
    // Retrieve data from the form
    $email = $_POST['email'];

    // Check if the email exists in the database
    $checkEmailQuery = "SELECT * FROM proprietaire WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows > 0) {
        // Email exists in the database

        // Example code using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rentalhouse.alhoceima24@gmail.com';
            $mail->Password   = 'azey gcyv cwqu tmol'; // Use your generated App Password if 2-step verification is enabled
            $mail->SMTPSecure = 'tls'; // or 'ssl'
            $mail->Port       = 587; // or 465

            // Recipients
            $mail->setFrom('el4649296@gmail.com', 'Rental House');
            $mail->addAddress($email, 'Recipient Name');

            // Generate a random 6-digit password
            $generatedPassword = mt_rand(100000, 999999);

            // Store the generated password in the database using a prepared statement
            $insertQuery = "INSERT INTO verification (email, code) VALUES (?, ?)";
            
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ss", $email, $generatedPassword);
            
            // Execute the query
            if ($stmt->execute()) {
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Verification Code';
                $mail->Body    = "Your verification code is: $generatedPassword. Use this code to verify your email address.";
                
                // Send the email using PHPMailer
                $mail->send();

                header("Location: send_code_owner.php");
                exit();
            } else {
                // Handle database error
                echo 'Error inserting data: ' . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        } catch (Exception $e) {
            // Handle email sending error
            $error_message = "Email incorrect";
            header("Location: oublie_owner.php?errmsg=" . urlencode($error_message));
            exit();
        }
    } else {
        // Email does not exist in the database
        // Provide feedback to the user or redirect to an error page
        $error_message = "Email does not exist in the database.";
            header("Location: oublie_owner.php?errmsg=" . urlencode($error_message));
            exit();
    }

    // Close the database connection
    $conn->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Connexion4'])) {
   
    // Retrieve data from the form
    $email = $_POST['email'];

    // Check if the email exists in the database
    $checkEmailQuery = "SELECT * FROM client WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows > 0) {
        // Email exists in the database

        // Example code using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rentalhouse.alhoceima24@gmail.com';
            $mail->Password   = 'azey gcyv cwqu tmol'; // Use your generated App Password if 2-step verification is enabled
            $mail->SMTPSecure = 'tls'; // or 'ssl'
            $mail->Port       = 587; // or 465

            // Recipients
            $mail->setFrom('el4649296@gmail.com', 'Rental House');
            $mail->addAddress($email, 'Recipient Name');

            // Generate a random 6-digit password
            $generatedPassword = mt_rand(100000, 999999);

            // Store the generated password in the database using a prepared statement
            $insertQuery = "INSERT INTO verification (email, code) VALUES (?, ?)";
            
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ss", $email, $generatedPassword);
            
            // Execute the query
            if ($stmt->execute()) {
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Verification Code';
                $mail->Body    = "Your verification code is: $generatedPassword. Use this code to verify your email address.";
                
                // Send the email using PHPMailer
                $mail->send();

                header("Location: send_code_tenant.php");
                exit();
            } else {
                // Handle database error
                echo 'Error inserting data: ' . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        } catch (Exception $e) {
            // Handle email sending error
            $error_message = "Email incorrect";
            header("Location: oublie_tenant.php?errmsg=" . urlencode($error_message));
            exit();
        }
    } else {

        $error_message = "Email does not exist in the database.";
            header("Location: oublie_tenant.php?errmsg=" . urlencode($error_message));
            exit();
    }

    // Close the database connection
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Connexion5'])) {
    // إنشاء اتصال قاعدة البيانات (قم بتعويضه بمعلومات اتصال قاعدة البيانات الفعلية الخاصة بك)

    // استرجاع البيانات من النموذج
    $code = $conn->real_escape_string($_POST['code']);
    $newPassword1 = $conn->real_escape_string($_POST['newpassword1']);
    $newPassword2 = $conn->real_escape_string($_POST['newpassword2']);

    // التحقق من صحة ومعالجة بيانات النموذج
    // التحقق مما إذا كانت كلمات المرور الجديدة تتطابق
    if ($newPassword1 !== $newPassword2) {
        echo 'كلمات المرور الجديدة غير متطابقة.';
        $conn->close();
        exit();
    }

    // المزيد من التحقق والمعالجة
    // (يمكنك إضافة مزيد من التحقق ومعالجة الأخطاء)

    // التحقق مما إذا كان رمز التحقق صحيحًا واسترجاع البريد الإلكتروني المرتبط
    $checkCodeQuery = "SELECT email FROM verification WHERE code = '$code'";
    $checkCodeResult = $conn->query($checkCodeQuery);

    if ($checkCodeResult->num_rows > 0) {
        // رمز التحقق صحيح

        // استرجاع البريد الإلكتروني المرتبط برمز التحقق
        $row = $checkCodeResult->fetch_assoc();
        $email = $row['email'];

        // تحديث كلمة المرور القديمة في جدول العملاء
        $hashedNewPassword = password_hash($newPassword1, PASSWORD_DEFAULT);
        $updatePasswordQuery = "UPDATE proprietaire SET password = '$hashedNewPassword' WHERE email = '$email'";
        $updatePasswordResult = $conn->query($updatePasswordQuery);

        if ($updatePasswordResult) {
            // تم تحديث كلمة المرور بنجاح

            // حذف رمز التحقق من جدول التحقق
            $deleteCodeQuery = "DELETE FROM verification WHERE code = '$code'";
            $conn->query($deleteCodeQuery);
            $error_message = "Le mot de passe a été mis à jour avec succès.";
            header("Location: login_owner.php?errmsg=" . urlencode($error_message));
            exit();
        } else {
            // التعامل مع خطأ تحديث كلمة المرور
            echo 'خطأ في تحديث كلمة المرور: ' . $conn->error;
        }
    } else {
        // رمز التحقق غير صحيح
        echo 'رمز التحقق غير صحيح.';
    }

    // إغلاق اتصال قاعدة البيانات
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Connexion6'])) {
    // إنشاء اتصال قاعدة البيانات (قم بتعويضه بمعلومات اتصال قاعدة البيانات الفعلية الخاصة بك)

    // استرجاع البيانات من النموذج
    $code = $conn->real_escape_string($_POST['code']);
    $newPassword1 = $conn->real_escape_string($_POST['newpassword1']);
    $newPassword2 = $conn->real_escape_string($_POST['newpassword2']);

    // التحقق من صحة ومعالجة بيانات النموذج
    // التحقق مما إذا كانت كلمات المرور الجديدة تتطابق
    if ($newPassword1 !== $newPassword2) {
        echo 'كلمات المرور الجديدة غير متطابقة.';
        $conn->close();
        exit();
    }

    // المزيد من التحقق والمعالجة
    // (يمكنك إضافة مزيد من التحقق ومعالجة الأخطاء)

    // التحقق مما إذا كان رمز التحقق صحيحًا واسترجاع البريد الإلكتروني المرتبط
    $checkCodeQuery = "SELECT email FROM verification WHERE code = '$code'";
    $checkCodeResult = $conn->query($checkCodeQuery);

    if ($checkCodeResult->num_rows > 0) {
        // رمز التحقق صحيح

        // استرجاع البريد الإلكتروني المرتبط برمز التحقق
        $row = $checkCodeResult->fetch_assoc();
        $email = $row['email'];

        // تحديث كلمة المرور القديمة في جدول العملاء
        $hashedNewPassword = password_hash($newPassword1, PASSWORD_DEFAULT);
        $updatePasswordQuery = "UPDATE client SET password = '$hashedNewPassword' WHERE email = '$email'";
        $updatePasswordResult = $conn->query($updatePasswordQuery);

        if ($updatePasswordResult) {
            // تم تحديث كلمة المرور بنجاح

            // حذف رمز التحقق من جدول التحقق
            $deleteCodeQuery = "DELETE FROM verification WHERE code = '$code'";
            $conn->query($deleteCodeQuery);

            echo 'تم تحديث كلمة المرور بنجاح.';
        } else {
            // التعامل مع خطأ تحديث كلمة المرور
            echo 'خطأ في تحديث كلمة المرور: ' . $conn->error;
        }
    } else {
        // رمز التحقق غير صحيح
        echo 'رمز التحقق غير صحيح.';
    }

    // إغلاق اتصال قاعدة البيانات
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Connexion7"])) {
    if (!isset($_SESSION['owner_id'])) {
    // Redirect to the login page or handle accordingly
    header("Location: login_owner.php");
    exit();
}

// Now you can use $_SESSION['owner_id'] as the owner_id for the new apartment
    $owner_id = $_SESSION['owner_id'];
    // Get form data
    $nom_app = $_POST['nom_app'];
    $type = $_POST['type'];
    $chambre_coucher = intval($_POST['chambre_coucher']);
    $location = $_POST['location'];
    $prix = floatval($_POST['prix']);
    $id_proprietaire = $owner_id;  // Use the owner_id from the session

    // Handle image upload
    $targetDirectory = "images/"; // Change this to your desired directory
    $targetFile = $targetDirectory . uniqid() . "_" . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        // Image uploaded successfully, proceed with the database insertion
        $imagePath = mysqli_real_escape_string($conn, $targetFile);

        // Insert data into the appartement table using prepared statement
        $sql = "INSERT INTO appartement (nom_app, type, chambre_coucher, location, img, prix, id_proprietaire)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssisssi", $nom_app, $type, $chambre_coucher, $location, $imagePath, $prix, $id_proprietaire);

            // Execute the statement
            if ($stmt->execute()) {
                $success_message = "Appartement inserted successfully.";
                header("Location: insert_appartement_form.php?success_message=" . urlencode($success_message));
                exit();
            } else {
                $error_message = "Error inserting appartement: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            // Handle prepare error
            $error_message = "Error preparing statement: " . $conn->error;
        }
    } else {
        // Handle file upload error
        $error_message = "Error uploading image.";
    }

    // Redirect with error message if there was an issue
    header("Location: insert_appartement_form.php?error_message=" . urlencode($error_message));
    exit();
}

?>