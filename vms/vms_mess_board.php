<?php
include "pages/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    // $location = $_POST['location'];
    // $isActive = $_POST['isActive'];

    // Call the function to send the message to the VMS board
    // $response = sendToBoard($message, $location, $isActive);
    $response = sendToBoard($message);

    echo $response;
}

function sendToBoard($message) {
    $host = '127.0.0.1'; // IP address of your local server
    $port = 5500;        // Port number

    $message = json_encode([
        'message' => $message
        // 'location' => $location,
        // 'active' => $isActive
    ]);

    // Create a socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        return "socket_create() failed: reason: " . socket_strerror(socket_last_error());
    }

    // Connect to the server
    $result = socket_connect($socket, $host, $port);
    if ($result === false) {
        return "socket_connect() failed: reason: " . socket_strerror(socket_last_error($socket));
    }

    // Send the message to the server
    socket_write($socket, $message, strlen($message));

    // Read the server's response
    $response = socket_read($socket, 2048);

    // Close the socket
    socket_close($socket);

    return $response;
}
?>
