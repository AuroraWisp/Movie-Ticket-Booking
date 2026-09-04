<?php
require_once "db.php";
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: movies.php");
    exit();
}

$error = "";
$success = "";

if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'] ?? $user['email'];

            header("Location: movies.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVerse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-card {
            background-color: #1e293b;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            max-width: 420px;
            width: 90%;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #334155;
            background-color: #0f172a;
            color: #f8fafc;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #38bdf8;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #e11d48;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .submit-btn:hover {
            background-color: #be123c;
        }
        .switch-auth {
            margin-top: 20px;
            color: #94a3b8;
            font-size: 0.9rem;
            text-align: center;
        }
        .switch-auth a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 600;
        }
        .switch-auth a:hover {
            text-decoration: underline;
        }
        .success-msg {
            color: #4ade80;
            background-color: rgba(74, 222, 128, 0.1);
            border: 1px solid rgba(74, 222, 128, 0.2);
            border-radius: 8px;
            padding: 10px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <h2 style="margin-bottom: 20px;">Welcome!!!</h2>

    <?php if ($success): ?>
        <p class="success-msg"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="submit-btn">Login</button>
    </form>

    <div class="switch-auth">
        Don't have an account? <a href="register.php">Sign up here</a>
    </div>
</div>

</body>
</html>
