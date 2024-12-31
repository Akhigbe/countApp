<?php 

// Database connection code...
$servername = "localhost";
$username = "tfnznviu_attendance";
$password = "vC;ima@((7qR";
$dbname = "tfnznviu_attendance";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch attendance history
$sql = "SELECT section, total_seats, males, kids, record_date FROM sections"; // Adjust this SQL query as per your table structure
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Initialize totals
$totalMales = 0;
$totalKids = 0;
$totalFemales = 0;

// Prepare history content
$historyContent = '<table class="table"><thead><tr><th>Section</th><th>Total Seats</th><th>Males</th><th>Kids</th><th>Females</th><th>Record Date</th></tr></thead><tbody>';

while ($row = $result->fetch_assoc()) {
    $females = $row['total_seats'] - $row['males'] - $row['kids'];
    $historyContent .= '<tr><td>' . htmlspecialchars($row['section']) . '</td>
                            <td>' . htmlspecialchars($row['total_seats']) . '</td>
                            <td>' . htmlspecialchars($row['males']) . '</td>
                            <td>' . htmlspecialchars($row['kids']) . '</td>
                            <td>' . ($females >= 0 ? $females : 0) . '</td>
                            <td>' . htmlspecialchars($row['record_date']) . '</td></tr>'; // Added record_date

    // Accumulate totals
    $totalMales += $row['males'];
    $totalKids += $row['kids'];
    $totalFemales += ($females >= 0 ? $females : 0); // Avoid negative females
}

$historyContent .= '</tbody></table>';
$historyContent .= '<div class="alert alert-info">Total Males: ' . $totalMales . ' | Total Kids: ' . $totalKids . ' | Total Females: ' . $totalFemales . '</div>';

echo $historyContent; // Output the history content
$conn->close();
?>
