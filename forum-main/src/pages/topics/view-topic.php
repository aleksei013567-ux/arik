<?php
session_start();
require '../../databases/db.php';

if (!isset($_GET['id'])) {
    die("ID темы не указан");
}
$topic_id = $_GET['id'];


$stmt = $pdo->prepare("
    SELECT topics.*, users.first_name, users.last_name 
    FROM topics 
    JOIN users ON topics.user_id = users.id 
    WHERE topics.id = ?
");
$stmt->execute([$topic_id]);
$topic = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$topic) die("Тема не найдена");


$stmt = $pdo->prepare("SELECT replies.*, users.first_name, users.last_name 
                       FROM replies 
                       JOIN users ON replies.user_id = users.id
                       WHERE topic_id = ?
                       ORDER BY replies.created_at DESC");
$stmt->execute([$topic_id]);
$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO replies (topic_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$topic_id, $_SESSION['user_id'], $content]);
        header("Location: view-topic.php?id=$topic_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($topic['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../assets/styles/style.css">
    <link rel="icon" href="../../../assets/images/i.png">
    <style>
        body {
            margin-left: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <h2><?= htmlspecialchars($topic['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($topic['content'])) ?></p>
    <small>Автор: <?= htmlspecialchars($topic['first_name'] . ' ' . $topic['last_name']) ?> | Дата: <?= date("Y-m-d H:i", strtotime($topic['created_at'])) ?></small>

    <hr>
    <h3>Ответы</h3>

    <?php foreach ($replies as $reply): ?>
        <div style="border:1px solid #ddd;margin-bottom:10px;padding:10px;">
            <p><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
            <small><?= htmlspecialchars($reply['first_name'] . ' ' . $reply['last_name']) ?> | <?= date("d.m.Y H:i", strtotime($reply['created_at'])) ?></small>
        </div>
    <?php endforeach; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h3>Оставить ответ</h3>
        <form method="post">
            <textarea name="content" style="width:100%;height:100px;" required></textarea><br><br>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    <?php endif; ?>

    <br><br>
    <a href="../index.php">← Назад к списку тем</a>
</body>
</html>