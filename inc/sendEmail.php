<?php

// Replace this with your own email address
$siteOwnersEmail = 'coderishi9@gmail.com';  // Make sure this is the correct email
$error = [];  // Array to store errors

// Check if the form is being submitted via POST
if ($_POST) {
    // Sanitize and collect form data
    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    // Log the received data for debugging
    error_log("Received form data: Name - $name, Email - $email, Subject - $subject");

    // Validate Name
    if (strlen($name) < 2) {
        $error['name'] = "Please enter your name.";
        error_log("Name validation failed.");
    }

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Please enter a valid email address.";
        error_log("Email validation failed.");
    }

    // Validate Message
    if (strlen($contact_message) < 15) {
        $error['message'] = "Please enter a message with at least 15 characters.";
        error_log("Message validation failed.");
    }

    // Set subject if not provided
    if (empty($subject)) {
        $subject = "Contact Form Submission";
    }

    // Prepare the email message body
    $message = "Email from: " . $name . "<br />";
    $message .= "Email address: " . $email . "<br />";
    $message .= "Message: <br />";
    $message .= nl2br(htmlspecialchars($contact_message));  // Convert newlines to <br />
    $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

    // Set email From header
    $from = htmlspecialchars($name) . " <" . htmlspecialchars($email) . ">";

    // Set email headers
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // Log the email headers for debugging
    error_log("Email headers: $headers");

    // If there are no errors, send the email
    if (empty($error)) {
        // Send the email using PHP's mail() function
        ini_set("sendmail_from", $siteOwnersEmail);  // For Windows servers
        $mail = mail($siteOwnersEmail, $subject, $message, $headers);

        // Check if the mail was sent successfully
        if ($mail) {
            error_log("Email sent successfully.");
            echo "OK";  // Send "OK" response to indicate success
        } else {
            error_log("Email failed to send.");
            echo "Something went wrong. Please try again.";  // Error response
        }
    } else {
        // Display validation errors
        $response = '';
        if (isset($error['name'])) $response .= $error['name'] . "<br /> \n";
        if (isset($error['email'])) $response .= $error['email'] . "<br /> \n";
        if (isset($error['message'])) $response .= $error['message'] . "<br />";
        error_log("Form validation failed: $response");  // Log the validation errors
        echo $response;  // Output the error messages
    }
}
?>
