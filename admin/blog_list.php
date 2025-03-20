<?php
include 'db.php';

$sql = "SELECT blog_id, title, date, image_url FROM blogs ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .blog-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
        }
    </style>
</head>
<body>
<?php
include "header.html";
?>
<div class="container">
    <h2 class="text-center">Blog List</h2>
    <a href="create_blog.php" class="btn btn-primary mb-3">Create New Blog</a>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Published Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['image_url'])): ?>
                            <img src="<?= $row['image_url'] ?>" class="blog-img" alt="Blog Image">
                        <?php else: ?>
                            <p>No Image</p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="blog_detail.php?id=<?= $row['blog_id'] ?>" class="text-decoration-none"><?= $row['title'] ?></a>
                    </td>
                    <td><?= $row['date'] ?></td>
                    <td>
                        <a href="edit_blog.php?id=<?= $row['blog_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['blog_id'] ?>">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this blog post?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="confirmDelete" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function(){
        $(".delete-btn").click(function(){
            let blogId = $(this).data("id");
            $("#confirmDelete").attr("href", "delete_blog.php?id=" + blogId);
            $("#deleteModal").modal("show");
        });
    });
</script>

</body>
</html>
