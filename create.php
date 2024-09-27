<?php
session_start();
include 'connection.php';
include 'header.php';

// Get current user and plan
$user_id = $_SESSION['user_id'];
$plan_id = $_SESSION['plan_id'];

// Check entry count for the user
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM entries WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$entry_count = $stmt->get_result()->fetch_assoc()['total'];

// Get the user's plan details
$stmt = $conn->prepare("SELECT * FROM plans WHERE id = ?");
$stmt->bind_param("i", $plan_id);
$stmt->execute();
$plan = $stmt->get_result()->fetch_assoc();

// Store max entries from the plan
$maxEntries = $plan['max_entries'];
// die($entry_count . ' ' . $maxEntries);

if ($maxEntries != 0 && $entry_count >= $maxEntries) {
  $_SESSION['error'] = "You have reached the maximum number of entries for your plan!";
  header("Location: dashboard.php");
  exit();
} else {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insert entry
    $stmt = $conn->prepare("INSERT INTO entries (user_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $description);
    $stmt->execute();

    header('Location: view.php');
    exit();
  }
}
?>

<form method="POST" action="create.php">
  <label for="title">Title:</label><br>
  <input type="text" name="title" required><br><br>
  <label for="description">Description:</label><br>
  <textarea name="description" required></textarea><br><br>
  <button type="submit">Create</button>
</form>