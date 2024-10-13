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

// Fetch books
$stmt = $pdo->query("SELECT * FROM books ORDER BY added_date DESC");
$books = $stmt->fetchAll();

// Fetch statistics
$totalBooks = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$readBooks = $pdo->query("SELECT COUNT(*) FROM books WHERE status = 'read'")->fetchColumn();
$unreadBooks = $totalBooks - $readBooks;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Library Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #f4f4f4;
            padding: 1rem;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }
        nav a {
            color: #333;
            padding: 0.5rem 1rem;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #ddd;
        }
        .dashboard {
            display: flex;
            justify-content: space-around;
            background-color: #e9e9e9;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .stat {
            text-align: center;
        }
        .book-list {
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
        footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            background-color: #f4f4f4;
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
        <section class="dashboard">
            <div class="stat">
                <h3>Total Books</h3>
                <p><?= $totalBooks ?></p>
            </div>
            <div class="stat">
                <h3>Books Read</h3>
                <p><?= $readBooks ?></p>
            </div>
            <div class="stat">
                <h3>Books Unread</h3>
                <p><?= $unreadBooks ?></p>
            </div>
        </section>
        <h2>Your Book Collection</h2>
        <div class="book-list">
            <?php foreach ($books as $book): ?>
                <div class="book">
                    <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?> cover">
                    <h3><?= htmlspecialchars($book['title']) ?></h3>
                    <p>By <?= htmlspecialchars($book['author']) ?></p>
                    <p>Status: <?= ucfirst(htmlspecialchars($book['status'])) ?></p>
                    <a href="book_details.php?id=<?= $book['id'] ?>">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Personal Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>