<?php 
include 'includes/header.php'; 
include 'classes/Database.php'; 

if(isset($_SESSION['isloggedin']) && $_SESSION['isloggedin'] == true) {
    header('Location: profile.php?id='.$_SESSION['id']);
}

$errors = [];

if(isset($_POST['register_btn'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if(!empty($fullname) && strstr(trim($fullname), " ")) {
        $name = explode(" ", $fullname)[0];
        $surname = explode(" ", $fullname)[1];
    } else {
        $name = filter_var($email, FILTER_VALIDATE_EMAIL) ? explode("@", $email)[0] : '';
        $surname = '';
    }

    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = 'Email is not valid!';
    }

    if(empty($password) || strlen($password) < 6) {
        $errors[] = "Please enter password (at least 6 characters)!";
    }

    if(count($errors) == 0) {
        $stm = $pdo->prepare('INSERT INTO `users` (`name`, `surname`, `email`, `password`) VALUES (?, ?, ?, ?)');
        
        if($stm->execute([$name, $surname, $email, password_hash($password, PASSWORD_BCRYPT )])) {
            $id = $pdo->lastInsertId();
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'user';  
            $_SESSION['isloggedin'] = true;
            header('Location: index.php?id='.$id);
        } else {
            $errors[] = 'Something went wrong - please try once again!';
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
      height: 400px;
      margin-top: 50px;
      background-color: white;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body>

<section class="h-screen flex items-center justify-center section-right bg-gray-100">
  <div class="max-w-md mx-auto">
    <div class="p-5 bg-white rounded-lg shadow-md">
      <h5 class="text-xl font-semibold mb-4">Register</h5>
      <?php if(count($errors)): ?>
        <div class="bg-red-300 p-2 rounded mb-4">
          <?php foreach($errors as $error): ?>
            <p class="m-0 text-red-600"><?= $error ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <form action="#" method="post">
        <div class="mb-4">
        <label for="text" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fullname</label>
          <input type="text" name="fullname" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Enter fullname">
        </div>
        <div class="mb-4">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
          <input type="email" name="email" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Enter email">
        </div>
        <div class="mb-4">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
          <input type="password" name="password" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Enter password">
        </div>
        <button name="register_btn" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Register</button>
      </form>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
