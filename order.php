<?php
include 'includes/header.php';
include 'classes/Database.php';

$errors = [];

if (isset($_POST['order_btn'])) {
    $description = $_POST['description'];
    $dimensions = $_POST['dimensions'];
    $address = $_POST['address']; 
    $price = $dimensions * 1.5;

    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('gltf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            $fileDestination = 'uploads/' . uniqid('', true) . "." . $fileActualExt;
            move_uploaded_file($fileTmpName, $fileDestination);
            
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, description, dimensions, price, address, file_path) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$_SESSION['id'], $description, $dimensions, $price, $address, $fileDestination])) {
                header('Location: order_success.php');
            } else {
                $errors[] = "Something went wrong while saving the order.";
            }
        } else {
            $errors[] = "There was an error uploading your file.";
        }
    } else {
        $errors[] = "You cannot upload files of this type!";
    }
}
?>

<style>
.form-section {
    padding-top: 100px; 

}

.form-section {
    padding-top: 100px;  
    margin-left: 20%;   
    background-color: #DBEAFF;
}
body {
    background-color: #DBEAFF;
}
</style>


<section class="form-section h-screen flex flex-col justify-start">
    <div class="h-96"></div>

    <div class="max-w-xl mx-auto">
        <div class="p-8 bg-white rounded-lg shadow-md">
        <h2 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-700 md:text-4xl dark:text-white">Create Order</h2>
            <?php if(count($errors)): ?>
                <div class="bg-red-300 p-2 rounded mb-4">
                    <?php foreach($errors as $error): ?>
                        <p class="m-0 text-red-600"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                   
                    <input type="text" id="description" name="description" required class="input input-bordered w-full" placeholder="Enter description">
                </div>
                <div class="mb-4">
                    <input type="number" id="dimensions" name="dimensions" required class="input input-bordered w-full" placeholder="Enter dimensions">
                </div>
                <div class="mb-4">
                    <span id="price" class="text-gray-700"></span>
                </div>
                <div class="mb-4">
                    
                    <input type="text" id="address" name="address" required class="input input-bordered w-full" placeholder="Enter address">
                </div>
                <div class="mb-4">
                 
                    <input type="file" id="file3D" name="file" required class="input input-bordered w-full" onchange="previewModel(event)">
                </div>
                <div id="model-container" class="mb-4" style="width: 400px; height: 400px;"></div>
              
                
                <button type="submit" name="order_btn" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">Create Order</button>


            </form>
        </div>
    </div>
</section>




<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<script>
    let scene, camera, renderer, controls, model;

    function initThreeJS() {
        scene = new THREE.Scene();
        camera = new THREE.PerspectiveCamera(75, 400 / 400, 0.1, 1000);
        renderer = new THREE.WebGLRenderer();
        scene.background = new THREE.Color(0xffffff); // Set the background color to white

        renderer.setSize(400, 400);
        document.getElementById("model-container").appendChild(renderer.domElement);
        
        const light = new THREE.PointLight(0xffffff);
        light.position.set(10, 10, 10);
        scene.add(light);
        controls = new THREE.OrbitControls(camera, renderer.domElement);
        camera.position.z = 2;
    }

    function previewModel(event) {
        const file = event.target.files[0];
        if (file) {
            const objectURL = URL.createObjectURL(file);
            const loader = new THREE.GLTFLoader();
            loader.load(objectURL, (gltf) => {
                if (model) {
                    scene.remove(model);
                }
                model = gltf.scene;
                scene.add(model);

                const box = new THREE.Box3().setFromObject(model);
                const size = box.getSize(new THREE.Vector3());
                const dimensions = size.x + size.y + size.z;
                document.getElementById('dimensions').value = dimensions.toFixed(2);
                
                const price = dimensions * 1.5;
                document.getElementById('price').textContent = 'Price: ' + price.toFixed(2) + ' EUR';
            });
        }
    }

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }

    initThreeJS();
    animate();
</script>

<script>
    const dimensionsInput = document.getElementById('dimensions');
    const priceDisplay = document.getElementById('price');

    dimensionsInput.addEventListener('input', function() {
        let dimensions = parseFloat(dimensionsInput.value);
        let price = dimensions * 1.5;
        priceDisplay.textContent = 'Price: ' + price + ' EUR';
    });
</script>

<?php include 'includes/footer.php'; ?>