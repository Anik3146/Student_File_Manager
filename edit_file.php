<?php
session_start();

if (!isset($_SESSION['student'])) {
    header("location: student_profile.php");
    exit;
}

require_once 'admin/conn.php';

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['store_id'])) {
    // Fetch file details for editing
    $store_id = $_GET['store_id'];
    $query = mysqli_query($conn, "SELECT * FROM `storage` WHERE `store_id` = '$store_id'") or die(mysqli_error($conn));
    $fetch = mysqli_fetch_array($query);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['store_id']) && isset($_POST['new_filename'])) {
    // Handle form submission for file editing
    $store_id = $_POST['store_id'];
    $newFilename = mysqli_real_escape_string($conn, $_POST['new_filename']);

    // Update the filename in the database
    $updateQuery = mysqli_query($conn, "UPDATE `storage` SET `filename` = '$newFilename' WHERE `store_id` = '$store_id'") or die(mysqli_error($conn));

    if ($updateQuery) {
        header("location: student_profile.php"); // Redirect to the previous page after editing
        exit;
    } else {
        $errorMessage = "Failed to update filename. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Edit File</title>
    <link rel="stylesheet" type="text/css" href="admin/css/bootstrap.css" />
</head>
<body>
    <div class="container">
        <h1>Edit File</h1>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="edit_file.php">
            <input type="hidden" name="store_id" value="<?php echo $store_id; ?>">
            <div class="form-group">
                <label for="new_filename">New Filename:</label>
                <input type="text" name="new_filename" id="new_filename" class="form-control" value="<?php echo isset($fetch['filename']) ? $fetch['filename'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="student_profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
