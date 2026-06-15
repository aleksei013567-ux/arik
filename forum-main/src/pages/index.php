<?php
    session_start();
    require '../databases/db.php';

    $stmt = $pdo->query("SELECT topics.*, COUNT(replies.id) AS replies_count 
                      FROM topics 
                      LEFT JOIN replies ON topics.id = replies.topic_id 
                      GROUP BY topics.id 
                      ORDER BY topics.created_at DESC");
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user_info = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_info = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head><title>Форум</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="icon" href="../../assets/images/i.png">
</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
    <center><p class="c">Привет, <?= htmlspecialchars($user_info['first_name']) ?> | <a href="../components/logout.php">Выход</a> | <a href="profile.php">Мои темы</a></p>
<?php else: ?>
    <p><a href="login.php">Войти</a> | <a href="register.php">Регистрация</a></p>
<?php endif; ?>

<h1>Темы форума</h1>

<?php foreach ($topics as $topic): ?>
    <div style="margin-bottom:20px;border:1px solid #ccc;padding:10px;">
        <h3><a href="topics/view-topic.php?id=<?= $topic['id'] ?>"><?= htmlspecialchars($topic['title']) ?></a></h3>
        <p><?= nl2br(htmlspecialchars(substr($topic['content'], 0, 100))) ?>...</p>
        <small>Ответов: <?= $topic['replies_count'] ?></small>
    </div>
<?php endforeach; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <a href="topics/add-topic.php" class="d">+ Создать новую тему</a><br><br><a href="../components/logout.php" class="d "><button class="btn btn-danger">Выйти из аккаунта</button></a></center>
<?php endif; ?>
</body>
</html>