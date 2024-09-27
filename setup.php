<?php
// Define variables for the form with default values
$host = '';
$dbname = '';
$user = '';
$password = '';
$error = '';
$createDatabase = isset($_POST['create_database']) ? true : false;

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $host = $_POST['host'];
    $dbname = $_POST['dbname'];
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($host) || empty($dbname) || empty($user)) {
        $error = "Please fill in all required fields.";
    } else {
        // Attempt to connect to MySQL using the provided credentials without selecting a database
        $conn = @new mysqli($host, $user, $password);

        // Check if the connection was successful
        if ($conn->connect_error) {
            $error = "Invalid SQL credentials. Please check your inputs.";
        } else {
            // If the "Create Database" option was selected
            if ($createDatabase) {
                // Create the database if it doesn't exist
                $sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
                if (!$conn->query($sql)) {
                    $error = "Error creating database: " . $conn->error;
                } else {
                    // Select the newly created database
                    $conn->select_db($dbname);

                    // Import the database schema from database.sql
                    if (file_exists('database.sql')) {
                        $sqlScript = file_get_contents('database.sql');
                        if ($conn->multi_query($sqlScript)) {
                            do {
                                // Store first result set
                                if ($result = $conn->store_result()) {
                                    $result->free();
                                }
                            } while ($conn->more_results() && $conn->next_result());
                        } else {
                            $error = "Error importing database: " . $conn->error;
                        }
                    } else {
                        $error = "SQL file not found.";
                    }
                }
            }

            // If no errors occurred, create config.php file with the provided details
            if (empty($error)) {
                $configContent = "<?php\n";
                $configContent .= "\$host = '$host';\n";
                $configContent .= "\$dbname = '$dbname';\n";
                $configContent .= "\$user = '$user';\n";
                $configContent .= "\$password = '$password';\n";
                $configContent .= "\$envirnment = 'local';\n";

                file_put_contents('config.php', $configContent);

                // Redirect to login.php
                header('Location: login.php');
                exit();
            }
        }
        // Close the connection
        $conn->close();
    }
}

// Display the form to enter configuration details
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Configuration</title>
</head>
<body>
    <h2>Database Configuration</h2>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="host">Host:</label>
        <input type="text" name="host" value="<?= htmlspecialchars($host); ?>" required><br>

        <label for="dbname">Database Name:</label>
        <input type="text" name="dbname" value="<?= htmlspecialchars($dbname); ?>" required><br>

        <label for="user">Username:</label>
        <input type="text" name="user" value="<?= htmlspecialchars($user); ?>" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" value="<?= htmlspecialchars($password); ?>"><br>

        <label>
            <input type="checkbox" name="create_database" <?= $createDatabase ? 'checked' : ''; ?>> Create Database
        </label><br>

        <button type="submit">Save Configuration</button>
    </form>
</body>
</html>
