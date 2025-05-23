<?php
session_start();
require_once 'pdo_config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';

    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Password is required and must be at least 6 characters.';
    }
    if (empty($birthdate)) {
        $errors[] = 'Birthdate is required.';
    }

    if (empty($errors)) {
        // Check if email or username already exists
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email OR username = :username');
        $stmt->execute(['email' => $email, 'username' => $username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errors[] = 'Username or email already exists.';
        } else {
            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, birthdate) VALUES (:username, :email, :password, :birthdate)');
            $result = $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'birthdate' => $birthdate,
            ]);

            if ($result) {
                // Redirect to login page after successful registration
                header('Location: login.php');
                exit();
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex flex-col">
  <header class="border border-black border-b-0">
    <h1 class="text-3xl font-extrabold px-6 py-3">Register</h1>
  </header>
  <main class="flex-grow flex flex-col items-center justify-center px-4">
    <img 
      src="https://placehold.co/192x64?text=Campuswall" 
      alt="Campus wall text on a black rectangular background" 
      class="w-48 h-16 rounded mb-10"
    />
    <?php if (!empty($errors)): ?>
      <div class="mb-4 text-red-600 font-semibold">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="mb-4 text-green-600 font-semibold">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>
    <form method="POST" class="w-full max-w-sm space-y-6">
      <div>
        <label class="block mb-1 text-gray-600 font-semibold" for="username">username</label>
        <input name="username" id="username" type="text" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <div>
        <label class="block mb-1 text-gray-600 font-semibold" for="email">email</label>
        <input name="email" id="email" type="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <div>
        <label class="block mb-1 text-gray-600 font-semibold" for="password">password</label>
        <input name="password" id="password" type="password" class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <div>
        <label class="block mb-1 text-gray-600 font-semibold" for="birthdate">birthdate</label>
        <input name="birthdate" id="birthdate" type="date" class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <button type="submit" class="border border-black rounded-md w-32 h-12 shadow-[4px_4px_0_0_black] hover:bg-gray-100 transition">
        Register
      </button>
    </form>
  </main>
</body>
</html>
