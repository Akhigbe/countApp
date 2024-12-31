<?php
$servername = "localhost";
$username = "tfnznviu_attendance";
$password = "vC;ima@((7qR";
$dbname = "tfnznviu_attendance";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $record_date = date('Y-m-d'); // Assign today's date

    // Validate POST data
    if (!isset($_POST['total_seats'], $_POST['males'], $_POST['kids'])) {
        echo json_encode(["status" => "error", "message" => "Invalid form submission."]);
        exit();
    }

    try {
        // Loop through each section and insert/update data
        foreach ($_POST['total_seats'] as $section => $total_seats) {
            $males = $_POST['males'][$section];
            $kids = $_POST['kids'][$section];
            $females = $total_seats - ($males + $kids);

            // Insert data into the database
            $sql = "INSERT INTO sections (section, total_seats, males, kids, females, record_date)
                    VALUES ('$section', $total_seats, $males, $kids, $females, '$record_date')
                    ON DUPLICATE KEY UPDATE 
                        total_seats = VALUES(total_seats), 
                        males = VALUES(males), 
                        kids = VALUES(kids), 
                        females = VALUES(females),
                        record_date = VALUES(record_date)";
            if (!$conn->query($sql)) {
                throw new Exception("Database error: " . $conn->error);
            }
        }

        echo json_encode(["status" => "success", "message" => "Attendance updated successfully!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit();
}
