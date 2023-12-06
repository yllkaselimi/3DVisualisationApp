<?php
include 'includes/header.php'; 
include 'classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];  
    
    if (!empty($user_id)) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, order_id, comment, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $order_id, $comment]);
    }

    header("Location: order_details.php?id=$order_id"); 
    exit;
}
