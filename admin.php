<?php
include 'includes/header.php'; 
include 'classes/Database.php';


if (!isset($_SESSION['isloggedin']) || $_SESSION['isloggedin'] == false || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = $pdo->prepare("SELECT * FROM orders WHERE id LIKE ? OR description LIKE ?");
    $query->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%']);
} else {
    $query = $pdo->prepare("SELECT * FROM orders");
    $query->execute();
}
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
    $orderId = $_GET['delete'];
    $deleteQuery = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $deleteQuery->execute([$orderId]);
    header("Location: admin.php");
}

?>

<style>
  .custom-table {
    margin-left: 20%; 
    margin-top: -20px;
  }
  .custom-table th,
  .custom-table td {
    padding: 8px;  
  }
  .custom-form {
    margin-top: 50px; 
    margin-left: 260px; 
  }
</style>


<section class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1>Admin Page</h1>
    <form action="admin.php" method="GET" class="mb-4 custom-form">
    <input type="text" name="search" placeholder="Search by ID or description" value="<?= $searchTerm; ?>" class="border rounded">
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Search</button>
</form>

    <h2>All Orders</h2>
    <table class="custom-table w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Order ID</th>
                <th scope="col" class="px-6 py-3">User ID</th>
                <th scope="col" class="px-6 py-3">Description</th>
                <th scope="col" class="px-6 py-3">Created At</th>
                <th scope="col" class="px-6 py-3">Dimensions</th>
                <th scope="col" class="px-6 py-3">Price</th>
                <th scope="col" class="px-6 py-3">Address</th>
                <th scope="col" class="px-6 py-3">Status</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?= $order['id']; ?></th>
                    <td class="px-6 py-4"><?= $order['user_id']; ?></td>
                    <td class="px-6 py-4"><?= $order['description']; ?></td>
                    <td class="px-6 py-4"><?= $order['created_at']; ?></td>
                    <td class="px-6 py-4"><?= $order['dimensions']; ?></td>
                    <td class="px-6 py-4"><?= $order['price']; ?></td>
                    <td class="px-6 py-4"><?= $order['address']; ?></td>
                    <td class="px-6 py-4">
                        <select name="status" onchange="updateStatus(<?= $order['id']; ?>, this.value)" class="rounded">
                            <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <a href="admin.php?delete=<?= $order['id']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script>
function updateStatus(orderId, newStatus) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            alert("Status updated");
        }
    };
    xhr.send("id=" + orderId + "&status=" + newStatus);
}
</script>

<?php include 'includes/footer.php'; ?>
