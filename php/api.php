<?php
/**
 * api.php
 * Task 7 (Option B): A simple PHP API endpoint that returns appointment
 * records in JSON format, so other applications could read our data.
 *
 * Try it by visiting: php/api.php
 * Or for one record only: php/api.php?id=1
 */

require "db_connect.php";

header("Content-Type: application/json");

if (isset($_GET["id"])) {
    // Return a single appointment by ID
    $id = (int) $_GET["id"];
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode(["success" => true, "appointment" => $data]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Appointment not found."]);
    }
} else {
    // Return every appointment
    $result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date DESC");
    $appointments = [];

    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    echo json_encode(["success" => true, "count" => count($appointments), "appointments" => $appointments]);
}

$conn->close();
?>
