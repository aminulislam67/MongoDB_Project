<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client('mongodb://localhost:27017');
$database = $mongoClient->selectDatabase('Boshundhara');

// Generate and download CSV file
if (isset($_GET['download']) && $_GET['download'] === 'csv') {
    $collectionNames = $database->listCollectionNames();
    $csvData = '';
    
    foreach ($collectionNames as $collectionName) {
        $collection = $database->selectCollection($collectionName);
        $cursor = $collection->find();
        $data = iterator_to_array($cursor);

        if (!empty($data)) {
            $csvData .= implode(',', array_keys((array)$data[0])) . "\n";
            foreach ($data as $document) {
                $csvData .= implode(',', (array)$document) . "\n";
            }
        }
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="data.csv"');
    echo $csvData;
    exit();
}

// Generate and download JSON file
if (isset($_GET['download']) && $_GET['download'] === 'json') {
    $collectionNames = $database->listCollectionNames();
    $jsonData = [];
    
    foreach ($collectionNames as $collectionName) {
        $collection = $database->selectCollection($collectionName);
        $cursor = $collection->find();
        $data = iterator_to_array($cursor);

        if (!empty($data)) {
            $jsonData = array_merge($jsonData, $data);
        }
    }

    $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);

    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="data.json"');
    echo $jsonString;
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collectionName = $_POST['collection_name'];
    $collection = $database->selectCollection($collectionName);

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
        
        <!-- Download buttons -->
       
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="collection_name">Collection Name:</label>
                <input type="text" name="collection_name" id="collection_name" class="form-control">
            </div>
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
            <input type="submit" value="Submit" class="btn btn-info">
            <a href="<?php echo $_SERVER['PHP_SELF'] . '?download=csv'; ?>" class="btn btn-info">Download CSV</a>
        <a href="<?php echo $_SERVER['PHP_SELF'] . '?download=json'; ?>" class="btn btn-info">Download JSON</a>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
