<?php
session_start();
require '../../databases/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title)) $errors[] = "Название темы обязательно";
    if (empty($content)) $errors[] = "Содержание темы обязательно";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO topics (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $title, $content]);
        header("Location: ../profile.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Создать тему</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../assets/styles/style.css">
    <link rel="stylesheet" href="../../../assets/styles/add.css">
    <link rel="icon" href="../../../assets/images/i.png"></head>
<body>
<center><h2>Создать новую тему</h2><br>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <input type="text" name="title" placeholder="Название темы" style="width:100%;"><br><br>
    <textarea name="content" placeholder="Текст темы" style="width:100%;height:200px;"></textarea><br><br>
    <button type="submit" class="btn btn-primary">Создать тему</button>
</form>
        </center>
</body>
</html>