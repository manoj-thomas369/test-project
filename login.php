<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and Password are required.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $salt = $user['salt'];  // row_salt
            $hashed = md5(md5($password) . $salt); // md5(md5(form_password).row_salt)

            if ($hashed === $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                header('Location: projects.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Test 2</title>
</head>

<body>
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label>Username:
            <input type="text" name="username" required>
        </label><br><br>
        <label>Password:
            <input type="password" name="password" required>
        </label><br><br>
        <button type="submit">Login</button>
    </form>
</body>

</html>