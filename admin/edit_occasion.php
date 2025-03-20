<?php
include 'db.php';


if (!isset($_GET['id'])) {
    die("Occasion ID is required.");
}
$occasion_id = $_GET['id'];


$result = $conn->query("SELECT * FROM occasions WHERE occasions_id = $occasion_id");
$occasion = $result->fetch_assoc();

if (!$occasion) {
    die("Occasion not found.");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "UPDATE occasions SET name='$name', description='$description' WHERE occasions_id=$occasion_id";
    if ($conn->query($sql)) {
        header("Location: manage_occasions.php"); 
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Occasion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Edit Occasion</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Occasion Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $occasion['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="<?php echo $occasion['description']; ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="manage_occasions.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
<?php
$conn->close();
?>
