<?php
// 1. Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    exit;
}

// 2. Collect & sanitize inputs
//    - trim whitespace
//    - strip tags to prevent HTML injection
$name    = strip_tags(trim($_POST['name']    ?? ''));
$email   = filter_var(trim($_POST['email']  ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = strip_tags(trim($_POST['number']  ?? ''));
$company = strip_tags(trim($_POST['company'] ?? ''));
$message = strip_tags(trim($_POST['message'] ?? ''));

// 3. Basic validation
$errors = [];
if (empty($name))    $errors[] = 'Name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required.';
}
if (empty($message)) $errors[] = 'Message cannot be empty.';

if (count($errors) > 0) {
    // Return errors as JSON for AJAX
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// 4. Build the email
$to      = 'oscarscott2411@gmail.com';        // Admin's primary email
$subject = "New contact from $name";
$body    = "
  <h2>New Message from Contact Form</h2>
  <p><strong>Name:</strong>    {$name}</p>
  <p><strong>Email:</strong>   {$email}</p>
  <p><strong>Phone:</strong>   {$phone}</p>
  <p><strong>Company:</strong> {$company}</p>
  <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
";

// 5. Set the headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
// From visitor address (some servers require a from on your own domain)
$headers .= "From: {$name} <no-reply@glovetradelitex.org>\r\n";
$headers .= "Reply-To: {$email}\r\n";

// 6. Send the email
$sent = mail($to, $subject, $body, $headers);

// 7. Return a JSON response
header('Content-Type: application/json');
if ($sent) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Thank you for your message. We will get back to you shortly.'
    ]);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Oops! Something went wrong, please try again later.'
    ]);
}
