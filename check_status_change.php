<?php
include 'classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status_changed = 1");
        $stmt->execute([$user_id]);
        
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($orders) > 0) {
            $resetStmt = $pdo->prepare("UPDATE orders SET status_changed = 0 WHERE user_id = ?");
            $resetStmt->execute([$user_id]);

            echo json_encode(['status_changed' => true, 'order_id' => $orders[0]['id'], 'new_status' => $orders[0]['status']]);
            exit;
        } else {
            echo json_encode(['status_changed' => false]);
            exit;
        }
    }
}
