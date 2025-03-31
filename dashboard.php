<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-lg text-center">
        <h1 class="text-2xl font-bold text-gray-800">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p class="text-gray-600 mb-4">Your role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>

        <!-- Admin-only actions -->
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="add_user.php" class="block w-full bg-blue-500 text-white py-3 rounded-md hover:bg-blue-600 transition mb-4">
                Add User
            </a>
        <?php endif; ?>

        <!-- Navigation -->
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Proposal Generator</h2>
        <nav class="space-y-4">
            <a href="hotel.php" class="block w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition">
                Hotel System Proposal
            </a>
            <a href="channel.php" class="block w-full bg-green-600 text-white py-3 rounded-md hover:bg-green-700 transition">
                Channel Manager Proposal
            </a>
            <a href="logout.php" class="block w-full bg-red-600 text-white py-3 rounded-md hover:bg-red-700 transition">
                Logout
            </a>
        </nav>
    </div>

</body>
</html>
