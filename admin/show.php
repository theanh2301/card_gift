<?php
include 'db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT p.product_id, p.name, c.name AS category_name, p.stock, p.price, p.material, p.description,
        (SELECT image_url FROM product_images WHERE product_id = p.product_id LIMIT 1) AS image
        FROM products p 
        LEFT JOIN categories c ON p.catagory_id = c.category_id";

if (!empty($search)) {
    $sql .= " WHERE p.name LIKE '%$search%' OR c.name LIKE '%$search%' OR p.price LIKE '%$search%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .product-img {
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
    <h2 class="text-center mt-4">Product List</h2>

    <form method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name, category, price..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Material</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="<?= $row['image'] ? $row['image'] : 'default-image.jpg' ?>" class="product-img" alt="Product Image">
                    </td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['category_name'] ?? 'No category' ?></td>
                    <td><?= number_format($row['price'], 0, ',', '.') ?> ₫</td>
                    <td><?= $row['material'] ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-warning">Add Images</a>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['product_id'] ?>">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
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
            let productId = $(this).data("id");
            $("#confirmDelete").attr("href", "delete_product.php?id=" + productId);
            $("#deleteModal").modal("show");
        });
    });
</script>

</body>
</html>
