<?php
// Get user plan
$plan_id = $_SESSION['plan_id'];
$username = $_SESSION['username'];

// Fetch the plan details
$stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
$stmt->bind_param("i", $plan_id);
$stmt->execute();
$plan = $stmt->get_result()->fetch_assoc();

echo "Welcome " . $username . ",<br>";
echo "Your plan: " . $plan['name'] . "<br>";
echo "Max entries allowed: " . ($plan['max_entries'] > 0 ? htmlspecialchars($plan['max_entries']) : 'Unlimited') . "<br><br>";
?>
|
<a href="dashboard.php">View Entries</a> |
<a href="change_plan.php">Change Plan</a> |
<a href="logout.php">Logout</a> |
<hr>

<?php
// Check for the error message
if (isset($_SESSION['error'])) {
  echo "<div style='color: red;'>" . $_SESSION['error'] . "</div>"; // Display the error message
  unset($_SESSION['error']); // Clear the error message
}
?>