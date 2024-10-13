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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $isbn = $_POST['isbn'] ?? '';
    $status = $_POST['status'] ?? 'unread';
    
    // Simple validation
    if (empty($title) || empty($author)) {
        $message = 'Title and Author are required.';
    } else {
        $cover_image = 'https://via.placeholder.com/100x150.png?text=No+Cover';
        
        $stmt = $pdo->prepare("INSERT INTO books (title, author, isbn, status, cover_image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $author, $isbn, $status, $cover_image])) {
            $message = 'Book added successfully!';
        } else {
            $message = 'Error adding book.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - Personal Library Management System</title>
    <style>
        /* Copy styles from index.php and add these: */
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
        form {
            max-width: 500px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 1rem;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }
        button {
            display: block;
            width: 100%;
            padding: 0.5rem;
            margin-top: 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-top: 1rem;
            padding: 0.5rem;
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
        <h2>Add New Book</h2>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
            
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn">
            
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="unread">Unread</option>
                <option value="read">Read</option>
            </select>
            
            <button type="submit">Add Book</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Personal Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>