<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client('mongodb://localhost:27017');
$database = $mongoClient->selectDatabase('Boshundhara');
$collection = $database->selectCollection('News');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'news_id' => $_POST['news_id'],
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'image_id' => $_POST['image_id'],
        'newscol' => $_POST['newscol'],
    ];

    $collection->insertOne($data);
    echo "Data inserted successfully!";
    header("Location: display.php"); // Redirect to display.php after form submission
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert News into MongoDB</title>
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
        <h1>Insert News into MongoDB</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="news_id">News ID:</label>
                <input type="text" name="news_id" id="news_id" class="form-control">
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <input type="text" name="content" id="content" class="form-control">
            </div>
            <div class="form-group">
                <label for="image_id">Image ID:</label>
                <input type="text" name="image_id" id="image_id" class="form-control">
            </div>
            <div class="form-group">
                <label for="newscol">Newscol:</label>
                <input type="text" name="newscol" id="newscol" class="form-control">
            </div>
            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
