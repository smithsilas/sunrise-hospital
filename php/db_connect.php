<?php
/**
 * db_connect.php
 * Task 6: "Connect the application to the database and store appointment
 * records." This one file creates the MySQL connection that every other
 * PHP script includes, so we only write the connection details once.
 *
 * Beginner note: change these four values to match your own local MySQL
 * setup (XAMPP/WAMP default username is usually "root" with no password).
 */

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "hospital_management";

// mysqli is used here because it is the simplest MySQL extension to learn
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Stop the script immediately if the connection failed
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
