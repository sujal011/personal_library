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

$message = '';
$book = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch();

    if (!$book) {
        $message = 'Book not found.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE books SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $id])) {
        $message = 'Book status updated successfully!';
        // Refresh book data
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch();
    } else {
        $message = 'Error updating book status.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details - Personal Library Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Copy styles from index.php and add these: */
        .book-details {
            max-width: 600px;
            margin: 0 auto;
            padding: 1rem;
            border: 1px solid #ddd;
        }
        .book-details img {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto 1rem;
        }
        .update-form {
            margin-top: 1rem;
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
        <h2>Book Details</h2>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <?php if ($book): ?>
            <div class="book-details">
                <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?> cover">
                <h3><?= htmlspecialchars($book['title']) ?></h3>
                <p>Author: <?= htmlspecialchars($book['author']) ?></p>
                <p>ISBN: <?= htmlspecialchars($book['isbn']) ?></p>
                <p>Status: <?= ucfirst(htmlspecialchars($book['status'])) ?></p>
                <p>Added on: <?= htmlspecialchars($book['added_date']) ?></p>
                
                <form class="update-form" method="POST">
                    <input type="hidden" name="id" value="<?= $book['id'] ?>">
                    <label for="status">Update Status:</label>
                    <select id="status" name="status">
                        <option value="unread" <?= $book['status'] === 'unread' ? 'selected' : '' ?>>Unread</option>
                        <option value="read" <?= $book['status'] === 'read' ? 'selected' : '' ?>>Read</option>
                    </select>
                    <button type="submit" name="update_status">Update Status</button>
                </form>
            </div>
        <?php else: ?>
            <p>No book details available.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Personal Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>