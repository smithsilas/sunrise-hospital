<?php
/**
 * process_appointment.php
 * Task 4: Back-end processing of the appointment booking form.
 * Task 5: Stores the appointment in the MySQL "appointments" table.
 *
 * Steps:
 *  1. Receive the submitted form fields.
 *  2. Validate them again on the server (never trust the browser alone).
 *  3. Insert the record into the database using a prepared statement.
 *  4. Show a simple success or error message.
 */

require "db_connect.php";

$errors = [];

// ---- Step 1: Receive submitted details ----
$patientName    = trim($_POST["patientName"] ?? "");
$nationalId     = trim($_POST["nationalId"] ?? "");
$gender         = trim($_POST["gender"] ?? "");
$phoneNumber    = trim($_POST["phoneNumber"] ?? "");
$email          = trim($_POST["email"] ?? "");
$department     = trim($_POST["department"] ?? "");
$appointmentDate = trim($_POST["appointmentDate"] ?? "");

// ---- Step 2: Server-side validation (mirrors the JavaScript checks) ----
if ($patientName === "") {
    $errors[] = "Patient name is required.";
}

if (!preg_match("/^[0-9]{6,10}$/", $nationalId)) {
    $errors[] = "National ID must be 6 to 10 digits.";
}

if (!in_array($gender, ["Female", "Male", "Other"])) {
    $errors[] = "Please select a valid gender.";
}

if (!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
    $errors[] = "Phone number must be exactly 10 digits.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please provide a valid email address.";
}

if ($department === "") {
    $errors[] = "Please choose a department.";
}

$today = date("Y-m-d");
if ($appointmentDate === "" || $appointmentDate < $today) {
    $errors[] = "Appointment date must not be in the past.";
}

// ---- Step 3: Stop here and show errors if validation failed ----
if (!empty($errors)) {
    echo "<h2>We could not process your appointment</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul><p><a href='../appointments.html'>Go back and try again</a></p>";
    exit;
}

// ---- Step 4: Insert into the database using a prepared statement ----
// Prepared statements protect us from SQL injection.
$sql = "INSERT INTO appointments
        (patient_name, national_id, gender, phone_number, email, department, appointment_date)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssss",
    $patientName,
    $nationalId,
    $gender,
    $phoneNumber,
    $email,
    $department,
    $appointmentDate
);

if ($stmt->execute()) {
    // Success! Send the patient back to the booking page with a flag
    header("Location: ../appointments.html?success=1");
    exit;
} else {
    echo "<h2>Something went wrong</h2>";
    echo "<p>" . htmlspecialchars($stmt->error) . "</p>";
}

$stmt->close();
$conn->close();
?>
