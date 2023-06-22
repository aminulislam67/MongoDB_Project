<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client('mongodb://localhost:27017');
$database = $mongoClient->selectDatabase('Boshundhara');
$collection = $database->selectCollection('News');

// Delete news data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_news'])) {
    $newsId = $_POST['delete_news'];
    $deleteResult = $collection->deleteOne(['news_id' => $newsId]);

    // if ($deleteResult->getDeletedCount() > 0) {
    //     echo "Data deleted successfully!";
    // } else {
    //     echo "Failed to delete data!";
    // }
}

// Fetch all news data
$newsData = $collection->find();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Display News</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .action-buttons {
            display: flex;
        }
        

        .update-button {
            background-color: #008000;
            border-color: #008000;
            color: #fff;
        }

        .delete-button {
            background-color: #800000;
            border-color: #800000;
            color: #fff;
        }
        .container{
            
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">News App</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>News Data</h1>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>News ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Image ID</th>
                    <th>Newscol</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsData as $news): ?>
                    <tr>
                        <td><?php echo $news['news_id']; ?></td>
                        <td><?php echo $news['title']; ?></td>
                        <td><?php echo $news['content']; ?></td>
                        <td><?php echo $news['image_id']; ?></td>
                        <td><?php echo $news['newscol']; ?></td>
                        <td class="action-buttons">
                            <a href="update.php?news_id=<?php echo $news['news_id']; ?>" class="btn btn-primary update-button">Update</a>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display: inline;">
                                <input type="hidden" name="delete_news" value="<?php echo $news['news_id']; ?>">
                                <button type="submit" class="btn btn-danger delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
