<?php
session_start();
require_once 'pdo_config.php';

$errors = [];
$success_message = '';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($current_password)) {
        $errors[] = 'Current password is required.';
    }
    if (empty($new_password)) {
        $errors[] = 'New password is required.';
    }
    if ($new_password !== $confirm_password) {
        $errors[] = 'New password and confirm password do not match.';
    }

    if (empty($errors)) {
        // Fetch current password hash from database
        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_password, $user['password'])) {
            // Update password
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
            $update_stmt->execute(['password' => $new_password_hash, 'id' => $_SESSION['user_id']]);

            $success_message = 'Password changed successfully. Redirecting to login...';
            // Destroy session to force login again
            session_destroy();
            // Redirect after 3 seconds
            header("refresh:3;url=login.php");
        } else {
            $errors[] = 'Current password is incorrect.';
        }
    }
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forgot Password</title>
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
    <h1 class="text-black text-2xl">Forgot Password</h1>
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
    <?php if ($success_message): ?>
      <div class="mb-4 text-green-600 font-semibold w-full max-w-sm">
        <?= htmlspecialchars($success_message) ?>
      </div>
    <?php endif; ?>
    <?php if (!$success_message): ?>
    <form method="POST" class="flex flex-col space-y-6 w-full max-w-sm">
      <div>
        <label class="block mb-1 text-gray-600 font-semibold">Enter Current Password</label>
        <input name="current_password" type="password" class="border border-black rounded-md h-12 w-full px-3 focus:outline-none focus:ring-2 focus:ring-gray-700" />
      </div>
      <div>
        <label class="block mb-1 text-gray-600 font-semibold">Enter New Password</label>
        <input name="new_password" type="password" class="border border-black rounded-md h-12 w-full px-3 focus:outline-none focus:ring-2 focus:ring-gray-700" />
      </div>
      <div>
        <label class="block mb-1 text-gray-600 font-semibold">Confirm Password</label>
        <input name="confirm_password" type="password" class="border border-black rounded-md h-12 w-full px-3 focus:outline-none focus:ring-2 focus:ring-gray-700" />
      </div>
      <button type="submit" class="border border-black rounded-md w-full h-12 font-semibold hover:bg-gray-800 hover:text-white transition">Proceed</button>
    </form>
    <?php endif; ?>
  </main>
</body>
</html>
