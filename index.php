<?php 
$servername = "localhost";
$username = "tfnznviu_attendance";
$password = "vC;ima@((7qR";
$dbname = "tfnznviu_attendance";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch sections from the database
$sql = "SELECT * FROM sections";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .mobile-app {
            max-width: 400px;
            margin: auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #8706ad;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .section-header {
            font-size: 1.2rem;
            font-weight: bold;
            padding: 10px;
            color: #6c757d;
        }
        .card {
            border-radius: 10px;
        }
        @media (max-width: 576px) {
            .modal-fullscreen-sm-down {
                max-height: 90vh;
                overflow-y: auto;
            }
        }
        .modal-success {
            color: #fff;
            background: #28a745;
        }
        .modal-success .modal-title {
            font-size: 1.5rem;
        }
        .modal-success .modal-body svg {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="mobile-app">
        <div class="header">
            <h1>Attendance Tracker</h1>
        </div>
        <form id="attendanceForm" class="p-4">
            <div class="section-header">Current Attendance</div>
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3 p-3">
                <div class="row mb-2">
                    <div class="col-6">Section: <?= htmlspecialchars($row['section']) ?></div>
                </div>
                <div class="mb-2">
                    <label>Total Seats</label>
                    <input type="number" class="form-control" id="total_seats_<?= htmlspecialchars($row['section']) ?>" name="total_seats[<?= htmlspecialchars($row['section']) ?>]" oninput="updateFemales('<?= htmlspecialchars($row['section']) ?>')" required>
                </div>
                <div class="mb-2">
                    <label>Males</label>
                    <input type="number" class="form-control" id="males_<?= htmlspecialchars($row['section']) ?>" name="males[<?= htmlspecialchars($row['section']) ?>]" oninput="updateFemales('<?= htmlspecialchars($row['section']) ?>')" required>
                </div>
                <div class="mb-2">
                    <label>Kids</label>
                    <input type="number" class="form-control" id="kids_<?= htmlspecialchars($row['section']) ?>" name="kids[<?= htmlspecialchars($row['section']) ?>]" oninput="updateFemales('<?= htmlspecialchars($row['section']) ?>')" required>
                </div>
                <div>
                    <strong>Females:</strong> <span id="females_<?= htmlspecialchars($row['section']) ?>">0</span>
                </div>
            </div>
            <?php endwhile; ?>
            <button type="submit" class="btn btn-primary w-100 mb-3">Submit</button>
            <button type="button" class="btn btn-secondary w-100" onclick="document.getElementById('attendanceForm').reset()">Reset</button>
            <button type="button" class="btn btn-success w-100 mt-3" data-bs-toggle="modal" data-bs-target="#historyModal" onclick="loadHistory()">View History</button>
        </form>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-sm-down modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="historyModalLabel">Attendance History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="historyContent" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-success">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" fill="currentColor" class="bi bi-check-circle mb-4" viewBox="0 0 16 16">
                        <path d="M15.854 4.146a.5.5 0 0 1 0 .708L7.707 13l-4.353-4.354a.5.5 0 1 1 .708-.708L8 11.293l7.146-7.147a.5.5 0 0 1 .708 0z"/>
                        <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                    </svg>
                    <h4>Attendance Recorded</h4>
                    <p>Your submission was successful.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFemales(section) {
            const totalSeats = parseInt(document.getElementById(`total_seats_${section}`).value) || 0;
            const males = parseInt(document.getElementById(`males_${section}`).value) || 0;
            const kids = parseInt(document.getElementById(`kids_${section}`).value) || 0;
            const females = Math.max(totalSeats - (males + kids), 0); // Calculate females
            document.getElementById(`females_${section}`).textContent = females;
        }

        document.getElementById("attendanceForm").addEventListener("submit", async function (event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('submit.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.status === "success") {
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                } else {
                    alert("Error: " + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert("An error occurred while submitting the form.");
            }
        });

        async function loadHistory() {
            const historyContent = document.getElementById("historyContent");
            historyContent.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;

            try {
                const response = await fetch('history.php');
                if (response.ok) {
                    const historyHTML = await response.text();
                    historyContent.innerHTML = historyHTML;
                } else {
                    historyContent.innerHTML = `<p class="text-danger">Error loading history. Please try again later.</p>`;
                }
            } catch (error) {
                console.error('Error fetching history:', error);
                historyContent.innerHTML = `<p class="text-danger">An error occurred while loading history.</p>`;
            }
        }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', () => {
            // Remove any lingering modal backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.parentNode.removeChild(backdrop);
            }
            // Restore the body class
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
        });
    });
});
</script>
</body>
</html>

<?php $conn->close(); ?>
