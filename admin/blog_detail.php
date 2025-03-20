<?php
include 'db.php';

$blog_id = $_GET['id'] ?? null;
if (!$blog_id) {
    die("Article not found!");
}

$sql = "SELECT * FROM blogs WHERE blog_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();

$sql_related = "SELECT blog_id, title, date, image_url FROM blogs WHERE blog_id != ? ORDER BY date DESC LIMIT 3";
$stmt_related = $conn->prepare($sql_related);
$stmt_related->bind_param("i", $blog_id);
$stmt_related->execute();
$related_articles = $stmt_related->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blog['title']) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 900px;
            margin-top: 20px;
        }
        .article-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .article-date {
            color: gray;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .article-img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .article-content {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .related-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 30px;
        }
        .related-article img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        .related-article h5 {
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php
include "header.html";
?>
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="news.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($blog['title']) ?></li>
        </ol>
    </nav>

    <h1 class="article-title"><?= htmlspecialchars($blog['title']) ?></h1>
    <p class="article-date">Published on: <?= date("F j, Y", strtotime($blog['date'])) ?></p>

    <?php if (!empty($blog['image_url'])): ?>
        <img src="<?= htmlspecialchars($blog['image_url']) ?>" class="article-img" alt="Article Image">
    <?php endif; ?>

    <p class="article-content"><?= nl2br(htmlspecialchars($blog['content'])) ?></p>

    <h2 class="related-title">Related Articles</h2>
    <div class="row">
        <?php while ($row = $related_articles->fetch_assoc()): ?>
        <div class="col-md-4">
            <div class="related-article">
                <a href="blog_detail.php?id=<?= $row['blog_id'] ?>" class="text-decoration-none">
                    <img src="<?= !empty($row['image_url']) ? $row['image_url'] : 'default-image.jpg' ?>" alt="Related Article">
                    <h5><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="text-muted"><?= date("F j, Y", strtotime($row['date'])) ?></p>
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <a href="news.php" class="btn btn-secondary mt-4">Back to News</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
