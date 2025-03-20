<?php
include 'db.php';
$blog_id = $_GET['id'] ?? null;
if (!$blog_id) {
    die("Blog post not found!");
}

$sql = "SELECT * FROM blogs WHERE blog_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date("Y-m-d H:i:s");

    $image_url = $blog['image_url'];

    if (isset($_POST['delete_image']) && !empty($blog['image_url'])) {
        if (file_exists($blog['image_url'])) {
            unlink($blog['image_url']);
        }
        $image_url = null;
    }

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            if (!empty($blog['image_url']) && file_exists($blog['image_url'])) {
                unlink($blog['image_url']);
            }
            $image_url = $target_file;
        }
    }

    $sql = "UPDATE blogs SET title = ?, content = ?, date = ?, image_url = ? WHERE blog_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $content, $date, $image_url, $blog_id);

    if ($stmt->execute()) {
        header("Location: blog_list.php");
        exit();
    } else {
        echo "Error updating blog: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
include "header.html";
?>
<div class="container mt-4">
    <h2 class="text-center">Edit Blog Post</h2>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($blog['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Content:</label>
            <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($blog['content']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image:</label><br>
            <?php if (!empty($blog['image_url'])): ?>
                <img src="<?= htmlspecialchars($blog['image_url']) ?>" width="200" class="mb-2"><br>
                <input type="checkbox" name="delete_image"> Delete current image<br>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload New Image:</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success w-100">Update Blog</button>
        <a href="blog_list.php" class="btn btn-secondary w-100 mt-2">Back to List</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
