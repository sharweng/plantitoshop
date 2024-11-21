<?php
    session_start();
    include('includes/config.php');

    if(!isset($_SESSION['roleDesc'])){
        $_SESSION['roleDesc'] = "";
    }
    include('includes/headerBS.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <?php include('includes/styles/style.css') ?>
        form{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <h1 class="text-center p-2 fw-bold">About Page</h1>
    <div class="container-sm outer-box p-3 mb-3 shadow-lg  border border-success border-2 rounded">
        <div class="row top-header pb-3 justify-content-between">
            <div class="col-4 d-flex align-items-center justify-content-start">
                <a href="/plantitoshop/" class="btn btn-success" name="add_user">BACK</a>
            </div>
            <div class="col-8 d-flex align-items-center justify-content-end gap-1">
            </div>
        </div>
        <div class="container inner-box border border-success border-2 py-3 px-4">
        <h2 class="fw-bold text-center">Welcome to Plantito’s Shop</h2>
        <p style="text-align: justify; text-indent: 50px;">At Plantito’s Shop, we believe in the transformative power of plants to bring life, tranquility, and beauty into every home. Founded with a passion for greenery and a mission to promote sustainable living, our online store is dedicated to serving fellow plantitos and plantitas, whether you're a seasoned gardener or just starting your plant journey.</p>
        <p style="text-align: justify; text-indent: 50px;">Our carefully curated collection features an extensive variety of plants, from herbs and shrubs to creepers and climbers, each hand-selected for exceptional quality and vibrancy. Beyond plants, we offer an array of gardening essentials, including premium pots, fertilizers, and tools, making us a one-stop shop for all your gardening needs.</p>
        <p style="text-align: justify; text-indent: 50px;"> We’re more than just a plant store—we’re a community. At Plantito’s Shop, we aim to inspire a love for nature, encourage sustainable practices, and help cultivate thriving green spaces in homes, offices, and neighborhoods. Whether you’re looking for a statement piece for your living room, a functional herb garden for your kitchen, or the perfect gift for a fellow plant lover, you’ll find it here.</p> 
        <h5 class="fw-bold text-center">Why Choose Plantito’s Shop?</h5>        
        <h5 class="fw-bold">Quality You Can Trust:</h5> <p style="text-align: justify;">Every plant is nurtured and inspected to ensure it arrives at your doorstep healthy and ready to flourish.</p>
        <h5 class="fw-bold">Wide Variety:</h5> <p style="text-align: justify;">From rare finds to beginner-friendly options, our selection caters to every type of plant enthusiast.</p>
        <h5 class="fw-bold">Sustainability Focused:</h5> <p style="text-align: justify;">We prioritize eco-friendly practices, from our packaging to the sourcing of our products.</p>
        <h5 class="fw-bold">Expert Guidance:</h5> <p style="text-align: justify;">Access tips and advice from our team of plant enthusiasts to help you grow and maintain your green companions.</p>
        <p class="fst-italic">Join us in celebrating the beauty and benefits of nature. Together, we can grow greener, happier spaces!</p>

        </div>
    </div>
</body>
</html>
<?php
    include('includes/footer.php');
?>