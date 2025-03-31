<?php
session_start();
require 'databaseconnect.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        $message = "User added successfully!";
    } else {
        $message = "Error adding user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-gray-100 flex flex-col items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <h1 class="text-2xl font-bold">Add New User</h1>

        <?php if (!empty($message)): ?>
            <p class="text-green-500"><?= $message ?></p>
        <?php endif; ?>

        <form method="post" class="mt-4">
            <input type="text" name="username" placeholder="Username" required
                class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-200">
            <input type="password" name="password" placeholder="Password" required
                class="w-full mt-3 px-4 py-2 border rounded-md focus:ring focus:ring-indigo-200">
            <select name="role" class="w-full mt-3 px-4 py-2 border rounded-md">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit"
                class="w-full mt-4 bg-green-500 hover:bg-green-600 text-white py-2 rounded-md">
                Add User
            </button>
        </form>
        <a href="dashboard.php" class="mt-4 block text-blue-500 hover:underline">Back to Dashboard</a>
    </div>
</body>
</html>
