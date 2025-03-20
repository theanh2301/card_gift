<?php
include 'db.php';

// Kiểm tra nếu có dữ liệu ảnh được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"])) {
    $product_id = $_POST['product_id'] ?? null;
    if (!$product_id) {
        die("Lỗi: Không xác định sản phẩm!");
    }

    $upload_dir = "uploads/"; // Thư mục lưu ảnh
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    // Tạo thư mục nếu chưa có
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Kiểm tra số lượng ảnh hiện tại của sản phẩm
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM product_images WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $current_image_count = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    if ($current_image_count >= 3) {
        echo "Sản phẩm này đã có đủ 3 ảnh. Không thể tải lên thêm.";
        header("Refresh: 2; URL=show.php");
        exit();
    }

    // Xử lý từng ảnh
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($current_image_count >= 3) break; // Giới hạn 3 ảnh

        $file_name = $_FILES["images"]["name"][$key];
        $file_tmp = $_FILES["images"]["tmp_name"][$key];
        $file_size = $_FILES["images"]["size"][$key];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Kiểm tra định dạng hợp lệ
        if (!in_array($file_ext, $allowed_types)) {
            echo "Chỉ chấp nhận JPG, JPEG, PNG & GIF.";
            continue;
        }

        // Kiểm tra kích thước
        if ($file_size > $max_size) {
            echo "Ảnh $file_name quá lớn. Chỉ chấp nhận ảnh dưới 2MB.";
            continue;
        }

        // Tạo tên file duy nhất để tránh trùng lặp
        $new_file_name = $upload_dir . uniqid() . "." . $file_ext;

        // Di chuyển file vào thư mục
        if (move_uploaded_file($file_tmp, $new_file_name)) {
            $current_image_count++;

            // Lưu đường dẫn ảnh vào database
            $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
            $stmt->bind_param("is", $product_id, $new_file_name);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Sau khi hoàn tất, quay lại `show.php`
    header("Location: show.php");
    exit();
}
?>
