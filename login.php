<?php 
ob_start(); // start output buffering
include 'includes/header.php'; 
include 'classes/Database.php'; 

if(isset($_SESSION['isloggedin']) && $_SESSION['isloggedin'] == true) {
    header('Location: index.php?id='.$_SESSION['id']);
}

$errors = [];

if(isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = 'Email is not valid!';
    }

    if(empty($password) || strlen($password) < 6) {
        $errors[] = "Please enter password (at least 6 characters)!";
    }

    if(count($errors) == 0) {
        $stm = $pdo->prepare('SELECT * FROM  `users` WHERE `email` = ?');
        $stm->execute([$email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if($user == false) {
            $errors[] = 'User does not exist!';
        } else {
            if(password_verify($password, $user['password'])) {
                $id = $user['id'];
                $_SESSION['id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $user['role']; 
                $_SESSION['isloggedin'] = true;
                header('Location: index.php?id='.$id);
            } else {
                $errors[] = 'Password is incorrect!';
            }
        }
    }
}
?>


<style>
  .section-right {
    margin-left: 240px;
    max-width: 80%;  
  }
  .p-5.bg-white.rounded-lg.shadow-md {
  padding: 1.25rem;
  width: 400px;  
  height: 350px;  
  margin-top: 50px;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

</style>


<section class="h-screen flex items-center justify-center section-right bg-gray-100">
    <div class="max-w-md mx-auto">
        <div class="p-5 bg-white rounded-lg shadow-md">
            <h5 class="text-xl font-semibold mb-4">Login</h5>
            <?php if(count($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach($errors as $error): ?>
                        <p class="m-0"><?= $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                    <input type="email" name="email" id="email" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter email">
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                    <input type="password" name="password" id="password" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter password">
                </div>
                <button type="submit" name="login_btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>