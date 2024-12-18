<?php
require "../helper/connection.php";
session_start(); // Start the session to access session variables

// Add CORS headers
header("Access-Control-Allow-Origin: *"); // Allows all origins, adjust '*' to a specific domain if necessary
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow GET, POST, and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow Content-Type header

// Ensure the request is a GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the user is logged in by checking the session
    if (!isset($_SESSION['login']['id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User is not logged in.'
        ]);
        exit;
    }

    // Get the user ID from the session
    $userId = $_SESSION['login']['id'];

    // Query the database to get the linked_id for the logged-in user
    if ($connection) {
        $stmt = $connection->prepare("SELECT linked_id FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($linked_id);
        $stmt->fetch();
        $stmt->close();

        // If linked_id is found, proceed to fetch data from datastream
        if ($linked_id !== null) {
            // Fetch data from datastream where product_id matches linked_id with a limit of 20 rows
            $stmt = $connection->prepare("SELECT * FROM datastream WHERE product_id = ? ORDER BY id DESC LIMIT 20");
            $stmt->bind_param("s", $linked_id); // Bind linked_id as string
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $stmt->close();

            // Return the matching data as JSON
            echo json_encode($data);

        } else {
            // If no linked_id is found, return an error message
            echo json_encode([
                'status' => 'error',
                'message' => 'No linked_id found for this user.'
            ]);
        }
    } else {
        // Return an error if the database connection fails
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed.'
        ]);
    }
} else {
    // Handle unsupported request methods
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}

$connection->close(); // Close the connection
