<?php
require '../databases/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($first_name)) $errors[] = "Имя обязательно";
    if (empty($last_name)) $errors[] = "Фамилия обязательна";
    if (empty($email)) $errors[] = "Email обязателен";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email";
    if (empty($password)) $errors[] = "Пароль обязателен";
    if ($password !== $confirm_password) $errors[] = "Пароли не совпадают";

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount()) {
            $errors[] = "Этот email уже используется";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $email, $hashed_password]);
            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Регистрация</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="icon" href="../../assets/images/i.png"></head>
<body class="body">
<center><h2 >Регистрация</h2><br>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <input type="text" name="first_name" placeholder="Имя"><br><br>
    <input type="text" name="last_name" placeholder="Фамилия"><br><br>
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Пароль"><br><br>
    <input type="password" name="confirm_password" placeholder="Подтвердите пароль"><br><br>
    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
</form>

<br><p>Уже есть аккаунт? <a href="login.php" class="b">Войдите</a></p>
</center>
</body>
</html>