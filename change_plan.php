<?php
session_start();
include 'connection.php';
include 'header.php';

$plan_id = $_SESSION['plan_id'];

// fetch plans
$sql = "SELECT id, name, max_entries FROM plans";
$plans_result = $conn->query($sql);

// Store plans in an array
$plans = [];
if ($plans_result->num_rows > 0) {
  while ($row = $plans_result->fetch_assoc()) {
    $plans[] = $row; // Add each row to the plans array
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $plan_id = $_POST['plan_id'];
  $user_id = $_SESSION['user_id'];

  // Update user plan
  $stmt = $conn->prepare("UPDATE users SET plan_id = ? WHERE id = ?");
  $stmt->bind_param("ii", $plan_id, $user_id);
  $stmt->execute();

  $_SESSION['plan_id'] = $plan_id;
  header('Location: dashboard.php');
  exit();
}
?>

<form method="POST" action="change_plan.php">
  <label for="plan_id">Change Plan:</label><br>

  <?php if (!empty($plans)): ?>
    <?php foreach ($plans as $plan): ?>
      <input type="radio" name="plan_id" value="<?php echo $plan['id']; ?>" <?php echo ($plan['id'] == $plan_id) ? 'checked' : ''; ?> required>
      <?php echo htmlspecialchars($plan['name']) . " (Max Entries: " . ($plan['max_entries'] > 0 ? htmlspecialchars($plan['max_entries']) : 'Unlimited') . ")"; ?><br>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No plans available at the moment.</p>
  <?php endif; ?>
  <button type="submit">Change Plan</button>
</form>
