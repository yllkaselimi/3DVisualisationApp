<?php 
include 'includes/header.php'; 
include 'classes/Database.php'; 


$stmt = $pdo->prepare("SELECT * FROM orders ORDER BY id DESC LIMIT 5");
$stmt->execute();
$latest_orders = $stmt->fetchAll();

?>

<style>
    .custom-content {
  margin-left: 20%;  
  margin-top: 10%; 
  font-size: 0.9em; 
}

.svg {
  margin-top: -620px;  
}


</style>

<section class="bg-white dark:bg-gray-900">
    <div class="custom-content py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Click <mark class="px-2 text-white bg-blue-300 rounded dark:bg-blue-500">Print</mark> Done</h1>
        <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">Known for our user-friendly software and robust printers, we offer solutions for all levels of 3D printing needs.</p>
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
            <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                Get started
                <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
            <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                Learn more
            </a>  
        </div>
    </div>
</section>


<section class="svg">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="visual" viewBox="0 0 900 600" width="100%" height="100%" version="1.1"><path d="M0 533L21.5 531.2C43 529.3 86 525.7 128.8 522C171.7 518.3 214.3 514.7 257.2 517.8C300 521 343 531 385.8 531.5C428.7 532 471.3 523 514.2 522C557 521 600 528 642.8 528.7C685.7 529.3 728.3 523.7 771.2 522.3C814 521 857 524 878.5 525.5L900 527L900 601L878.5 601C857 601 814 601 771.2 601C728.3 601 685.7 601 642.8 601C600 601 557 601 514.2 601C471.3 601 428.7 601 385.8 601C343 601 300 601 257.2 601C214.3 601 171.7 601 128.8 601C86 601 43 601 21.5 601L0 601Z" fill="#a4cafe"/><path d="M0 548L21.5 551C43 554 86 560 128.8 559.5C171.7 559 214.3 552 257.2 549.5C300 547 343 549 385.8 553.8C428.7 558.7 471.3 566.3 514.2 570.2C557 574 600 574 642.8 569C685.7 564 728.3 554 771.2 549C814 544 857 544 878.5 544L900 544L900 601L878.5 601C857 601 814 601 771.2 601C728.3 601 685.7 601 642.8 601C600 601 557 601 514.2 601C471.3 601 428.7 601 385.8 601C343 601 300 601 257.2 601C214.3 601 171.7 601 128.8 601C86 601 43 601 21.5 601L0 601Z" fill="#1e429f"/></svg>
</section>




<?php if (isset($_SESSION['isloggedin']) && $_SESSION['isloggedin']): ?>

<div id="notificationArea" class="fixed inset-0 flex items-center justify-center w-full h-full z-50" style="display:none; top: -300px; right: 500px;">
    <div class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
  
        </div>
        <div id="notificationText" class="ml-3 text-sm font-normal">
       
        </div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="$('#notificationArea').hide()">
            <span class="sr-only">Close</span>
   
        </button>
    </div>
</div>

<script>
setInterval(function() {
    // E qon requestin ne ajax mr kqyr statusi a ka ndryshu
    $.ajax({
        url: 'check_status_change.php',
        type: 'POST',
        data: { user_id: <?php echo $_SESSION['id']; ?> },
        dataType: 'json',
        success: function(data) {
            if (data.status_changed) {
                $('#notificationText').html('Your order with ID: ' + data.order_id + ' has changed to ' + data.new_status);
                $('#notificationArea').show();
            }
        }
    });
}, 5000);
</script>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
