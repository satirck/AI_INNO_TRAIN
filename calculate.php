<?php
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['expenses'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

$expenses = $data['expenses'];

// Calculate total amount
$totalAmount = array_reduce($expenses, function($sum, $expense) {
    return $sum + $expense['amount'];
}, 0);

// Calculate average daily expense (assuming 30 days)
$averageDaily = $totalAmount / 30;

// Get top 3 largest expenses
usort($expenses, function($a, $b) {
    return $b['amount'] - $a['amount'];
});
$topExpenses = array_slice($expenses, 0, 3);

// Prepare response
$response = [
    'totalAmount' => $totalAmount,
    'averageDaily' => $averageDaily,
    'topExpenses' => $topExpenses
];

echo json_encode($response);
?> 