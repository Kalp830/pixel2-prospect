<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your reCAPTCHA secret key
    $secretKey = "YOUR_SECRET_KEY";
    $captcha = $_POST['g-recaptcha-response'];

    // Verify CAPTCHA
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
    $responseKeys = json_decode($verifyResponse, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo "Captcha verification failed. Please try again.";
        exit;
    }

    // Collect form data
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $phone   = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Target email
    $to = "targetemail@example.com";  // <-- replace with your email
    $headers = "From: {$name} <{$email}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $emailBody = "
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";

    if (mail($to, "New Message from Website: {$subject}", $emailBody, $headers)) {
        echo "Success! Your message has been sent.";
    } else {
        echo "Failed to send message. Please try again later.";
    }
}
?>
