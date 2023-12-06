<?php
include 'includes/header.php'; 
include 'classes/Database.php';

$order_id = $_GET['id']; 


$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>

.order-card {
    max-width: 400px;
    margin: 50px auto; 
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    background-color: white;
}

.order-card h5 {
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
}

.order-card p {
    margin-bottom: 1rem;
    font-size: 1rem;
    color: #4b5563;
}

.order-card p strong {
    font-weight: 600;
}


</style>

<h2>Order Details</h2>
<div class="order-card">
    <h5><?php echo $order['description']; ?></h5>
    <p><strong>Dimensions:</strong> <?php echo $order['dimensions']; ?></p>
    <p><strong>Price:</strong> <?php echo $order['price']; ?></p>
    <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
    <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
    <div id="model-container-<?php echo $order['id']; ?>" style="width: 320px; height: 320px;" class="border rounded-md"></div>


<?php if($order['status'] === 'Delivered'): ?>
    <h5>Leave a Review</h5>
    <form action="submit_review.php" method="POST">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <textarea name="comment" rows="2" cols="42"></textarea><br>
        <button type="submit">Submit</button>
    </form>
    <?php endif; ?>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

<script>
initThreeJS('model-container-<?php echo $order["id"]; ?>', '<?php echo $order["file_path"]; ?>');

function initThreeJS(containerId, filePath) {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, 320 / 320, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    scene.background = new THREE.Color(0xffffff); 

    renderer.setSize(320, 320);
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
</script>

<?php include 'includes/footer.php'; ?>





