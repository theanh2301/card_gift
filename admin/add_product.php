<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = $_POST['catagory_id']; // Đảm bảo category_id đúng
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $material = $_POST['material'];
    $description = $_POST['description'];

    // Kiểm tra danh mục có tồn tại không
    $check_category = $conn->prepare("SELECT category_id FROM categories WHERE category_id = ?");
    $check_category->bind_param("i", $category_id);
    $check_category->execute();
    $check_category->store_result();

    if ($check_category->num_rows == 0) {
        die("Lỗi: Danh mục không tồn tại. Vui lòng chọn danh mục hợp lệ.");
    }

    // Thêm sản phẩm vào bảng products
    $sql = "INSERT INTO products (name, catagory_id, stock, price, material, description) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siidss", $name, $category_id, $stock, $price, $material, $description);
    
    if ($stmt->execute()) {
        $product_id = $stmt->insert_id; // Lấy ID sản phẩm vừa thêm

        // Xử lý tải lên hình ảnh (3 hình)
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $file_name = time() . "_" . basename($_FILES['images']['name'][$key]);
                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $sql_image = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
                    $stmt_image = $conn->prepare($sql_image);
                    $stmt_image->bind_param("is", $product_id, $target_file);
                    $stmt_image->execute();
                }
            }
        }

        echo "Sản phẩm đã thêm thành công!";
    } else {
        echo "Lỗi khi thêm sản phẩm: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
