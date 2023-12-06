<?php
include 'includes/header.php';
include 'classes/Database.php';

$user_id = $_SESSION['id']; 
$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$user_id]);
$userInfo = $query->fetch(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];

    $updateStmt = $pdo->prepare("UPDATE users SET name = ?, surname = ?, email = ? WHERE id = ?");
    $updateStmt->execute([$name, $surname, $email, $user_id]);

    header("Location: profile.php");
    exit();
}

?>

<style>
  .custom-card {
    margin-left: 500px;  
    margin-top: 100px; 

}

</style>


<div class="profile-section max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 custom-card">
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Your Profile</h2>

    <form action="profile.php" method="post" class="space-y-4">
        <div class="flex flex-col space-y-2 mb-4">
            <label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-400">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $userInfo['name']; ?>" class="border p-2 rounded-md">
        </div>

        <div class="flex flex-col space-y-2 mb-4">
            <label for="surname" class="text-sm font-medium text-gray-700 dark:text-gray-400">Surname:</label>
            <input type="text" name="surname" id="surname" value="<?php echo $userInfo['surname']; ?>" class="border p-2 rounded-md">
        </div>

        <div class="flex flex-col space-y-2 mb-4">
            <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-400">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo $userInfo['email']; ?>" class="border p-2 rounded-md">
        </div>

        <input type="submit" value="Update" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md cursor-pointer">
    </form>
</div>


<?php include 'includes/footer.php'; ?>
