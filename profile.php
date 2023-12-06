<?php
include 'includes/header.php';
include 'classes/Database.php';

$user_id = $_SESSION['id']; 

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


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

.profile-section {
    border: 1px solid #e5e7eb;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
.order-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
}

.order-card {
    max-width: 100%;
    padding: 1.5rem;
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.order-card h5 {
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
    font-weight: bold;
    color: #1f2937;
}

.order-card p {
    margin-bottom: 1rem;
    font-size: 1rem;
    color: #4b5563;
}


</style>
<div class="flex space-x-8">

<div class="profile-section w-1/4">
    <h2 class="text-xl font-semibold mb-4">Your Profile</h2>

    <form action="profile.php" method="post" class="space-y-4 ">
        <div class="flex flex-col space-y-2 mb-4">
            <label for="name" class="text-sm font-medium">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $userInfo['name']; ?>" class="border p-2 rounded-md">
        </div>

        <div class="flex flex-col space-y-2 mb-4">
            <label for="surname" class="text-sm font-medium">Surname:</label>
            <input type="text" name="surname" id="surname" value="<?php echo $userInfo['surname']; ?>" class="border p-2 rounded-md">
        </div>

        <div class="flex flex-col space-y-2 mb-4">
            <label for="email" class="text-sm font-medium">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo $userInfo['email']; ?>" class="border p-2 rounded-md">
        </div>

        <input type="submit" value="Update" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md cursor-pointer">
    </form>
</div>

<div class="w-3/4">
    <h2 class="text-xl font-semibold mb-4 mt-8">Your Orders</h2>
    <div class="order-list">
    <?php
    foreach ($orders as $order) {
        echo '<div class="order-card">';
        echo '<h5> ' . $order['description'] . '</h5>';
        echo '<p><strong>Dimensions:</strong> ' . $order['dimensions'] . '</p>';
        echo '<p><strong>Price:</strong> ' . $order['price'] . '</p>';
        echo '<p><strong>Address:</strong> ' . $order['address'] . '</p>';
        echo '<p><strong>Status:</strong> ' . $order['status'] . '</p>';
        echo '<div id="model-container-' . $order['id'] . '" style="width: 250px; height: 250px;" class="border rounded-md"></div>';
        echo '<a href="order_details.php?id=' . $order['id'] . '" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-md cursor-pointer">Details</a>';
        echo '</div>';
    }

    ?>
    </div>
</div>


</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

<script>
function initThreeJS(containerId, filePath) {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, 250 / 250, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    scene.background = new THREE.Color(0xffffff); 

    renderer.setSize(250, 250);
    document.getElementById(containerId).appendChild(renderer.domElement);

    const loader = new THREE.GLTFLoader();
    let model;

    loader.load(filePath, (gltf) => {
        model = gltf.scene;
        model.rotation.x += 0.01;
        model.rotation.y += 0.01;
        scene.add(model);
    });

    camera.position.z = 7;

    function animate() {
        requestAnimationFrame(animate);
        if (model) {
            model.rotation.x += 0.01;
            model.rotation.y += 0.01;
        }
        renderer.render(scene, camera);
    }

    animate();
}


<?php foreach ($orders as $order): ?>
    initThreeJS('model-container-<?php echo $order["id"]; ?>', '<?php echo $order["file_path"]; ?>');
<?php endforeach; ?>
</script>

<?php include 'includes/footer.php'; ?>
