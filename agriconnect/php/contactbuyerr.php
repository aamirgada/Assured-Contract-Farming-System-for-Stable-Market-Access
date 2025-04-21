<?php
session_start();

// Include Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $mobile = trim(preg_replace("/[^0-9]/", "", $_POST['mobile'] ?? '')); // only digits
    $subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));
    $created_at = date('Y-m-d H:i:s');

    $errors = [];

    if (empty($name)) $errors[] = "Name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (strlen($mobile) < 10) $errors[] = "Valid mobile number is required";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";

    if (empty($errors)) {
        try {
            // Check and create table if not exists
            $tableCheck = $conn->query("SHOW TABLES LIKE 'contact_messages'");
            if ($tableCheck->rowCount() == 0) {
                $createTableSQL = "CREATE TABLE contact_messages (
                    id INT(11) AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    mobile VARCHAR(15) NOT NULL,
                    subject VARCHAR(255) NOT NULL,
                    message TEXT NOT NULL,
                    created_at DATETIME NOT NULL
                )";
                $conn->exec($createTableSQL);
            }

            // Insert into DB
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, mobile, subject, message, created_at) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$name, $email, $mobile, $subject, $message, $created_at]);

            // If DB insert successful, send email
            if ($result) {
                $mail = new PHPMailer(true);

                try {
                    // SMTP Settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'mridul7876800396@gmail.com'; // your gmail
                    $mail->Password = 'fqvc actj spyj yyaz';     // App password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('mridul7876800396@gmail.com', 'Agriconnect Contact Form');
                    $mail->addAddress('mridulsharma1712006@gmail.com');

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'New Contact Form Query';
                    $mail->Body    = "
                        <h2>New Query Received</h2>
                        <p><strong>Name:</strong> $name</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Mobile:</strong> $mobile</p>
                        <p><strong>Subject:</strong> $subject</p>
                        <p><strong>Message:</strong><br>$message</p>
                        <p><strong>Received at:</strong> $created_at</p>
                    ";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Mailer Error: " . $mail->ErrorInfo);
                }

                echo "<script>
                    alert('Thank you for your message. We will get back to you soon!');
                    window.location.href = '../indexbuyer.php';
                </script>";
                exit();
            } else {
                throw new Exception("Failed to save your message");
            }
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());

            // Fallback: Save to file
            $fallbackDir = __DIR__ . '/../contact_messages';
            if (!file_exists($fallbackDir)) mkdir($fallbackDir, 0755, true);
            $filename = $fallbackDir . '/message_' . time() . '_' . md5($email) . '.txt';
            $messageData = "Name: $name\nEmail: $email\nMobile: $mobile\nSubject: $subject\nDate: $created_at\nMessage:\n$message\n";
            file_put_contents($filename, $messageData);

            echo "<script>
                alert('Message saved. We will contact you soon!');
                window.location.href = '../index.php';
            </script>";
            exit();
        }
    } else {
        $errorMsg = implode("\\n", $errors);
        echo "<script>
            alert('Please fix the following errors:\\n$errorMsg');
            window.location.href = '../contact_us.html';
        </script>";
        exit();
    }
} else {
    header("Location: ../contact_us.html");
    exit();
}
