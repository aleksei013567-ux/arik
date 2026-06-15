<?php
session_start();
require '../databases/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверный email или пароль";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Вход</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="icon" href="../../assets/images/i.png">
</head>
<body>
<center><h2>Вход</h2><br>
<?php if ($error): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="post">
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Пароль"><br><br>
    <button type="submit" class="btn btn-primary">Войти</button><br>
</form>

<br><p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p></center>
</body>
</html>