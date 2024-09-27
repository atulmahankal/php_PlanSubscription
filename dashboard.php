<?php
session_start();
include 'connection.php';
include 'header.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM entries WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$index = 1;
?>

<p><a href="create.php">Create New Entry</a></p>

<table border=1 width=100%>
  <tr>
    <th>#</th>
    <th>Title</th>
    <th>Description</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $index++; ?></td>
      <td><?= $row['title'] ?></td>
      <td><?= $row['description'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>