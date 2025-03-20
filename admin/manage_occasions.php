<?php
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_occasion'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "INSERT INTO occasions (name, description) VALUES ('$name', '$description')";
    $conn->query($sql);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_occasion'])) {
    $id = $_POST['occasions_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "UPDATE occasions SET name='$name', description='$description' WHERE occasions_id=$id";
    $conn->query($sql);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM occasions WHERE occasions_id=$id");
}


$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM occasions WHERE name LIKE '%$search%' OR description LIKE '%$search%'");
} else {
    $result = $conn->query("SELECT * FROM occasions");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Occasions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function resetSearch() {
            let searchInput = document.getElementById('search');
            if (searchInput.value === "") {
                window.location.href = "manage_occasions.php";
            }
        }
    </script>
</head>
<body class="container mt-5">
    <?php
        include "header.html";
    ?>
    <h2 class="mb-4">Add New Occasion</h2>
    <form method="post" class="mb-4">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Occasion Name" required>
        </div>
        <div class="mb-3">
            <input type="text" name="description" class="form-control" placeholder="Description" required>
        </div>
        <button type="submit" name="add_occasion" class="btn btn-primary">Add</button>
    </form>

    <h2 class="mb-4">Occasions List</h2>
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" id="search" name="search" class="form-control" placeholder="Search occasions..." value="<?php echo $search; ?>" onkeyup="resetSearch()">
            <button type="submit" class="btn btn-secondary">Search</button>
        </div>
    </form>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['occasions_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
                <a href="?delete=<?php echo $row['occasions_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                <a href="edit_occasion.php?id=<?php echo $row['occasions_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</body>
</html>
<?php
$conn->close();
?>
