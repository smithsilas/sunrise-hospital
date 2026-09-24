<?php
/**
 * admin.php
 * Task 6: Appointment Records Management.
 * Lets an administrator VIEW all appointments, SEARCH them by name or
 * department, and DELETE a record. Kept on one simple page for a
 * beginner-level project.
 */

require "db_connect.php";

// ---- Handle delete requests (?delete=ID in the URL) ----
if (isset($_GET["delete"])) {
    $deleteId = (int) $_GET["delete"]; // cast to int, this alone prevents SQL injection here
    $deleteStmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id = ?");
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();
    header("Location: admin.php?deleted=1");
    exit;
}

// ---- Handle search (?q=search term in the URL) ----
$searchTerm = isset($_GET["q"]) ? trim($_GET["q"]) : "";

if ($searchTerm !== "") {
    // Search by patient name OR department
    $stmt = $conn->prepare(
        "SELECT * FROM appointments
         WHERE patient_name LIKE ? OR department LIKE ?
         ORDER BY appointment_date DESC"
    );
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // No search term - show everything
    $result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Manage Appointments</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>

  <header>
    <div class="header-inner">
      <div class="logo">Sunrise <span>Hospital</span> Admin</div>
    </div>
  </header>

  <div class="page-banner">
    <h1>Manage Appointments</h1>
  </div>

  <main>
    <?php if (isset($_GET["deleted"])): ?>
      <div class="success-box" style="display:block;">Appointment record deleted.</div>
    <?php endif; ?>

    <!-- Search form -->
    <form class="search-bar" method="GET" action="admin.php">
      <input
        type="text"
        name="q"
        placeholder="Search by patient name or department"
        value="<?php echo htmlspecialchars($searchTerm); ?>"
      />
      <button type="submit" class="btn">Search</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Patient Name</th>
          <th>National ID</th>
          <th>Gender</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Department</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row["appointment_id"]; ?></td>
              <td><?php echo htmlspecialchars($row["patient_name"]); ?></td>
              <td><?php echo htmlspecialchars($row["national_id"]); ?></td>
              <td><?php echo htmlspecialchars($row["gender"]); ?></td>
              <td><?php echo htmlspecialchars($row["phone_number"]); ?></td>
              <td><?php echo htmlspecialchars($row["email"]); ?></td>
              <td><?php echo htmlspecialchars($row["department"]); ?></td>
              <td><?php echo htmlspecialchars($row["appointment_date"]); ?></td>
              <td>
                <a
                  class="delete-link"
                  href="admin.php?delete=<?php echo $row['appointment_id']; ?>"
                  onclick="return confirm('Delete this appointment?');"
                >Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="9">No appointments found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <footer>
    <p>&copy; 2026 Sunrise Community Hospital. All rights reserved.</p>
  </footer>
</body>
</html>
<?php $conn->close(); ?>
