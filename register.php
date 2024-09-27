<?php
include 'connection.php';

// Fetch plans
$sql = "SELECT id, name, max_entries FROM plans";
$result = $conn->query($sql);

// Store plans in an array
$plans = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plans[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $plan_id = $_POST['plan_id'];

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, plan_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $password, $plan_id);
    $stmt->execute();

    header('Location: login.php');
    exit();
}
?>

<form method="POST" action="register.php">
    <label for="username">Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label for="plan_id">Plan:</label><br>
    <!-- <select name="plan_id">
        <option value="1">Basic Plan</option>
        <option value="2">Premium Plan</option>
    </select><br> -->

    <?php if (!empty($plans)): ?>
        <?php foreach ($plans as $plan): ?>
            <input type="radio" name="plan_id" value="<?php echo $plan['id']; ?>" required>
            <?php echo htmlspecialchars($plan['name']) . " (Max Entries: " . ($plan['max_entries'] > 0 ? htmlspecialchars($plan['max_entries']) : 'Unlimited') . ")"; ?><br>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No plans available at the moment.</p>
    <?php endif; ?><br><br>

    <button type="submit">Register</button><br>
    <a href="login.php"> Login now</a>
</form>
