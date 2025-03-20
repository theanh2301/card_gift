<?php
include 'db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT blog_id, title, content, date, image_url FROM blogs ORDER BY date DESC";
if (!empty($search)) {
    $sql = "SELECT blog_id, title, content, date, image_url FROM blogs 
            WHERE title LIKE '%$search%' OR content LIKE '%$search%' 
            ORDER BY date DESC";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1100px;
            margin-top: 20px;
        }
        .news-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .news-card {
            transition: transform 0.2s;
        }
        .news-card:hover {
            transform: scale(1.03);
        }
        .news-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .news-excerpt {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .news-date {
            font-size: 0.9em;
            color: gray;
        }
    </style>
</head>
<body>
<?php
include "header.html";
?>
<div class="container">
    <h1 class="news-header">Latest News</h1>

    <form method="GET" class="d-flex mb-4">
        <input type="text" name="search" class="form-control me-2" placeholder="Search news..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card news-card">
                <img src="<?= !empty($row['image_url']) ? $row['image_url'] : 'default-image.jpg' ?>" class="news-img card-img-top" alt="News Image">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['title'] ?></h5>
                    <p class="news-date"><?= date("F j, Y", strtotime($row['date'])) ?></p>
                    <p class="card-text news-excerpt"><?= substr($row['content'], 0, 120) ?>...</p>
                    <a href="blog_detail.php?id=<?= $row['blog_id'] ?>" class="btn btn-primary btn-sm">Read More</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
