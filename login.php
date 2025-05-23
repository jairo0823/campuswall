<?php
session_start();
require_once 'pdo_config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: login.php'); // Change to dashboard or home page if available
            exit();
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Fredoka One', cursive;
    }
  </style>
</head>
<body class="bg-white m-0 p-0 min-h-screen flex flex-col">
  <header class="border-b border-black pl-4 py-2">
    <h1 class="text-black text-2xl">Login</h1>
  </header>
  <main class="flex-grow flex flex-col items-center justify-center px-4">
    <div class="w-48 h-16 bg-gray-800 flex items-center justify-center rounded-md mb-10">
      <span class="text-white text-xl font-semibold">Campus Wall</span>
    </div>
    <?php if (!empty($errors)): ?>
      <div class="mb-4 text-red-600 font-semibold w-full max-w-sm">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form method="POST" class="flex flex-col space-y-6 w-full max-w-sm">
      <div>
        <label class="block mb-1 text-gray-600 font-semibold">Email</label>
        <input name="email" type="email" class="border border-black rounded-md h-12 w-full px-3 focus:outline-none focus:ring-2 focus:ring-gray-700" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>
      <div>
        <label class="block mb-1 text-gray-600 font-semibold">Password</label>
        <input name="password" type="password" class="border border-black rounded-md h-12 w-full px-3 focus:outline-none focus:ring-2 focus:ring-gray-700" />
      </div>
      <div class="flex justify-between items-center">
        <button type="submit" class="border border-black rounded-md w-28 h-12 font-semibold hover:bg-gray-800 hover:text-white transition">Login</button>
        <a href="forgotpass.php" class="text-gray-600 underline hover:text-gray-900 font-semibold flex items-center h-12">Forgot Password</a>
      </div>
      </form>
      <p class="mt-4 text-center text-gray-700 font-semibold">
        Don't have an account? 
        <a href="register.php" class="text-gray-600 underline hover:text-gray-900">Register</a>
      </p>
  </main>
</body>
</html>
