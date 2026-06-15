<?php
session_start();
require '../databases/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM topics WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Мои темы</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="icon" href="../../assets/images/i.png">
  </head>
<body>
<h2>Мои темы</h2>

<?php if (!empty($topics)): ?>
    <ul>
        <?php foreach ($topics as $topic): ?>
            <li>
                <strong><?= htmlspecialchars($topic['title']) ?></strong><br>
                <?= nl2br(htmlspecialchars($topic['content'])) ?><br>
                <small><?= date("d-m-Y H:i:s", strtotime($topic['created_at'])) ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Тем пока нет.</p>
<?php endif; ?>

<a href="topics/add-topic.php">+ Создать новую тему</a><br>
<a href="index.php">На главную</a>
</body>
</html>