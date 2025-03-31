<?php 
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Proposal</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CSS -->
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Hotel System Proposal</h1>

        <form action="generate_pdf.php" method="POST" class="space-y-4">
            <input type="text" name="hotel_name" placeholder="Hotel Name" required class="w-full p-3 border rounded-md">
            <input type="text" name="hotel_owner_name" placeholder="Owner Name" required class="w-full p-3 border rounded-md">
            <input type="tel" name="phone" placeholder="Phone Number" required class="w-full p-3 border rounded-md">
            <input type="email" name="email" placeholder="Email" required class="w-full p-3 border rounded-md">

            <select name="rooms" required class="w-full p-3 border rounded-md">
    <option value="15">1-15 Rooms (3,500 THB)</option>
    <option value="25">16-25 Rooms (4,000 THB)</option>
    <option value="50">31-50 Rooms (5,500 THB)</option>
    <option value="70">51-70 Rooms (6,000 THB)</option>
    <option value="90">71-90 Rooms (7,000 THB)</option>
    <option value="91">91+ Rooms (8,000 THB)</option>
</select>




        <!-- Discount Fields -->
        <input type="number" name="discount_percent" step="0.01" min="0" max="25" placeholder="Discount % (Optional)" class="w-full p-3 border rounded-md">
        <input type="number" name="discount_month" step="0.01" min="0" max="100" placeholder="Discount Fixed Month (Optional)" class="w-full p-3 border rounded-md">
        <input type="number" name="discount_year" step="0.01" min="0" max="5000" placeholder="Discount Fixed Year (Optional)" class="w-full p-3 border rounded-md">
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-md hover:bg-green-700 transition">
                Generate PDF
            </button>
        </form>
    </div>

</body>
</html>
