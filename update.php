<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client('mongodb://localhost:27017');
$database = $mongoClient->selectDatabase('Boshundhara');
$collection = $database->selectCollection('News');

// Check if news ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['news_id'])) {
    $newsId = $_GET['news_id'];
    $newsData = $collection->findOne(['news_id' => $newsId]);

    if (!$newsData) {
        echo "News not found!";
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newsId = $_POST['news_id'];
    $data = [
        'news_id' => $_POST['news_id'],
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'image_id' => $_POST['image_id'],
        'newscol' => $_POST['newscol'],
    ];

    $updateResult = $collection->updateOne(['news_id' => $newsId], ['$set' => $data]);

    if ($updateResult->getModifiedCount() > 0) {
        echo "Data updated successfully!";
        header("Location: display.php"); // Redirect to display.php after form submission
        exit();
    } else {
        echo "Failed to update data!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update News</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">News App</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="display.php">Display</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Update News</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="news_id">News ID:</label>
                <input type="text" name="news_id" id="news_id" class="form-control" value="<?php echo $newsData['news_id']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo $newsData['title']; ?>">
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <input type="text" name="content" id="content" class="form-control" value="<?php echo $newsData['content']; ?>">
            </div>
            <div class="form-group">
                <label for="image_id">Image ID:</label>
                <input type="text" name="image_id" id="image_id" class="form-control" value="<?php echo $newsData['image_id']; ?>">
            </div>
            <div class="form-group">
                <label for="newscol">Newscol:</label>
                <input type="text" name="newscol" id="newscol" class="form-control" value="<?php echo $newsData['newscol']; ?>">
            </div>
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
