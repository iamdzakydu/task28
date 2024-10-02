<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Kode task27 di bawah ini
$conn = new mysqli('localhost', 'root', '', 'task27');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$sql = "SELECT * FROM employees ORDER BY $sort_column $order LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) FROM employees";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);

$toggle_order = $order == 'ASC' ? 'DESC' : 'ASC';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Employee Table</title>
</head>
<body>
    <h1>Welcome, <?= $_SESSION['username']; ?>!</h1>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=id&order=<?= $toggle_order ?>&page=<?= $page ?>">ID</a></th>
                <th><a href="?sort=nik&order=<?= $toggle_order ?>&page=<?= $page ?>">NIK</a></th>
                <th><a href="?sort=name&order=<?= $toggle_order ?>&page=<?= $page ?>">Name</a></th>
                <th><a href="?sort=department&order=<?= $toggle_order ?>&page=<?= $page ?>">Department</a></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nik'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['department'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&sort=<?= $sort_column ?>&order=<?= $order ?>">Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&sort=<?= $sort_column ?>&order=<?= $order ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>&sort=<?= $sort_column ?>&order=<?= $order ?>">Next</a>
        <?php endif; ?>
    </div>

    <a href="logout.php">Logout</a>
</body>
</html>

<?php $conn->close(); ?>
