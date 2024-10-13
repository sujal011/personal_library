<?php
// Database connection
$host = 'localhost';
$db   = 'personal_library';
$user = 'root';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$search_results = [];
$search_performed = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ?");
    $stmt->execute([$search_term, $search_term, $search_term]);
    $search_results = $stmt->fetchAll();
    $search_performed = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - Personal Library Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Copy styles from index.php and add these: */
        .search-form {
            max-width: 500px;
            margin: 0 auto 2rem;
        }
        .search-form input[type="text"] {
            width: 70%;
            padding: 0.5rem;
        }
        .search-form button {
            width: 25%;
            padding: 0.5rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        .book {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .book img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>Personal Library Management System</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="add_book.php">Add Book</a>
        <a href="search.php">Search</a>
    </nav>
    <main>
        <h2>Search Books</h2>
        <form class="search-form" method="GET">
            <input type="text" name="search" placeholder="Search by title, author, or ISBN" required>
            <button type="submit">Search</button>
        </form>
        
        <?php if ($search_performed): ?>
            <h3>Search Results</h3>
            <?php if (count($search_results) > 0): ?>
                <div class="search-results">
                    <?php foreach ($search_results as $book): ?>
                        <div class="book">
                            <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?> cover">
                            <h3><?= htmlspecialchars($book['title']) ?></h3>
                            <p>By <?= htmlspecialchars($book['author']) ?></p>
                            <p>Status: <?= ucfirst(htmlspecialchars($book['status'])) ?></p>
                            <a href="book_details.php?id=<?= $book['id'] ?>">View Details</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No books found matching your search criteria.</p>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Personal Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>