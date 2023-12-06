<?php
include 'includes/header.php';
?>

<style>
.custom-paragraph {
    margin-top: 130px;
    margin-bottom: 10px;
    margin-left: 230px;
    margin-right: 20px;
}
</style>

<section class="my-5 flex justify-end items-center bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern.svg')] dark:bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern-dark.svg')]">
    <div class="container">
        <div class="row">
            <div class="col-6 offset-1 d-flex align-items-center">
                <div class="card w-75 mx-auto bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                    <div class="card-body py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 z-10 relative">
                        <div class="custom-paragraph">
                        <h5 class="card-title font-bold text-gray-900 dark:text-white md:text-4xl lg:text-5xl">Order Created Successfully</h5>
                        <div class="card-text mb-8 text-lg font-normal lg:text-xl">
                            <p>Your order has been created. You can view it in your profile.</p>
                            <a href="profile.php" class="btn btn-sm btn-outline-primary font-semibold text-gray-900 underline dark:text-white decoration-indigo-500">Go to Profile</a>
                            
                        </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-b from-blue-50 to-transparent dark:from-blue-900 w-full h-full absolute top-0 left-0 z-0"></div>
</section>


<?php include 'includes/footer.php'; ?>
