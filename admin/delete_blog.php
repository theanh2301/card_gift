<?
include 'db.php';

$blog_id = $_GET['id'] ?? null;
if (!$blog_id) {
    die("Blog post not found!");
}

// Delete image if exists
$sql = "SELECT image_url FROM blogs WHERE blog_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!empty($blog['image_url']) && file_exists($blog['image_url'])) {
    unlink($blog['image_url']);
}

// Delete blog post
$sql = "DELETE FROM blogs WHERE blog_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);

if ($stmt->execute()) {
    header("Location: blog_list.php");
    exit();
} else {
    echo "Error deleting blog: " . $stmt->error;
}
?>
