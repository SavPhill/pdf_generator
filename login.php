<?php
session_start();
require 'databaseconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-700">Login</h2>
        
        <?php if (!empty($error)): ?>
            <p class="text-red-500 text-center"><?= $error ?></p>
        <?php endif; ?>
        
        <form method="post" class="mt-4">
            <input type="text" name="username" placeholder="Username" required
                class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-200">
            <input type="password" name="password" placeholder="Password" required
                class="w-full mt-3 px-4 py-2 border rounded-md focus:ring focus:ring-indigo-200">
            <button type="submit"
                class="w-full mt-4 bg-indigo-500 hover:bg-indigo-600 text-white py-2 rounded-md">
                Login
            </button>
        </form>
    </div>
</body>
</html>
