<?php
include 'classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE orders SET status = ?, status_changed = 1 WHERE id = ?");
        $stmt->execute([$status, $id]);
    }
}
?>
