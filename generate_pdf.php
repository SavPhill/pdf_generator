<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Function to determine pricing based on room count
function getPricing($rooms) {
    $pricing = [
        [1, 15, 3500, 38500],
        [16, 25, 4000, 44000],
        [26, 35, 4500, 49500],
        [36, 50, 5000, 55000],
        [51, 70, 6000, 66000],
        [71, 90, 7000, 77000],
        [91, 9999, 8000, 88000], // For hotels with more than 90 rooms
    ];

    foreach ($pricing as $range) {
        if ($rooms >= $range[0] && $rooms <= $range[1]) {
            return ['monthly' => $range[2], 'yearly' => $range[3]];
        }
    }
    return ['monthly' => 0, 'yearly' => 0]; // Default case
}

// Get form data with security measures
$hotel_name = htmlspecialchars($_POST['hotel_name'] ?? '');
$hotel_owner_name = htmlspecialchars($_POST['hotel_owner_name'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$rooms = intval($_POST['rooms'] ?? 0);
$discount_percent = floatval($_POST['discount_percent'] ?? 0);
$discount_month = floatval($_POST['discount_month'] ?? 0);
$discount_year = floatval($_POST['discount_year'] ?? 0);

$pricing = getPricing($rooms);
$monthlyPrice = (float) $pricing['monthly'];
$yearlyPrice = (float) $pricing['yearly'];

// Calculate percentage discount if applied
$monthlyDiscount = ($discount_percent > 0) ? $monthlyPrice * ($discount_percent / 100) : 0;
$yearlyDiscount = ($discount_percent > 0) ? $yearlyPrice * ($discount_percent / 100) : 0;

// Apply fixed discounts (discount_month & discount_year)
$monthlyTotal = max(0, $monthlyPrice - $monthlyDiscount - $discount_month);
$yearlyTotal = max(0, $yearlyPrice - $yearlyDiscount - $discount_year);

// Get current date
$creationDate = date("F d, Y");

// Calculate expiration date (creation date + 2 weeks)
$expirationDate = date("F d, Y", strtotime("+2 weeks"));

// PDF options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Ensure the logo loads correctly
$logoPath = __DIR__ . "/logo.jpg";
$logoBase64 = "data:image/jpg;base64," . base64_encode(file_get_contents($logoPath));

// Discount section logic
$discountSection = "";

if ($discount_percent > 0) {
    $discountSection .= "<tr>
        <td colspan='2' class='highlight'>Discount Applied: $discount_percent%</td>
        <td>- " . number_format($monthlyDiscount, 2) . " THB</td>
        <td>- " . number_format($yearlyDiscount, 2) . " THB</td>
    </tr>";
}

if ($discount_month > 0) {
    $discountSection .= "<tr>
        <td colspan='2' class='highlight'>Monthly Discount (Fixed)</td>
        <td>- " . number_format($discount_month, 2) . " THB</td>
        <td></td>
    </tr>";
}

if ($discount_year > 0) {
    $discountSection .= "<tr>
        <td colspan='2' class='highlight'>Yearly Discount (Fixed)</td>
        <td></td>
        <td>- " . number_format($discount_year, 2) . " THB</td>
    </tr>";
}

// HTML content
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Proposal</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 11px; }
        .table th { background-color: #0BA59B; color: white; font-weight: bold; text-transform: uppercase; }
        .header, .footer { width: 100%; text-align: center; font-size: 12px; color: #0BA59B; font-weight: bold; padding: 5px 0; }
        .footer { border-top: 2px solid #0BA59B; margin-top: 20px; font-size: 10px; }
        .highlight { font-weight: bold; font-size: 12px; color: #0BA59B; }
    </style>
</head>
<body>
    <div class='header'>
        <img src='$logoBase64' alt='Company Logo' width='150'><br>
        <span>Proposal Date: $creationDate</span>
    </div>

    <u><span>Proposal For:</span></u>
    <h3>$hotel_owner_name<br>
    $hotel_name<br>
    $phone<br>
    $email</h3>
    <p>We are pleased to present this tailored proposal for $hotel_name. This proposal is active until $expirationDate.</p>

    <h2 class='highlight' style='margin-top: 30px;'>Proposed Package</h2>

    <table class='table'>
        <tr>
            <th>Services</th>
            <th>Rooms</th>
            <th>Monthly Fee</th>
            <th>Yearly Fee</th>
        </tr>
        <tr>
            <td>
                <span class='highlight'>All-In-One Hotel System</span><br>
                - Property Management System<br>
                - Channel Manager<br>
                - Booking Engine<br>
                - Website Template<br>
            </td>
            <td class='highlight'>{$rooms} Rooms</td>
            <td>
    <b>" . number_format($monthlyTotal, 2) . " THB</b><br>
    " . (($discount_percent > 0 || $discount_month > 0) ? "<small>Original: " . number_format($pricing['monthly'], 2) . " THB</small>" : "") . "
</td>
<td>
    <b>" . number_format($yearlyTotal, 2) . " THB</b><br>
    " . (($discount_percent > 0 || $discount_year > 0) ? "<small>Original: " . number_format($pricing['yearly'], 2) . " THB</small>" : "") . "
</td>

        </tr>
        $discountSection
    </table>

    <p class='highlight' style='margin-top: 10px;'>** Prices exclude VAT 7%</p>

    <div class='footer'>
        Ace Marketing Solutions Co., Ltd. <br>
        Website: www.acemsthailand.com | Email: hotelsupport@acemsthailand.com <br>
        Phone: 063-7925666
    </div>
</body>
</html>
";

// Load content into Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Proposal.pdf", ["Attachment" => false]);
?>
