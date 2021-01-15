<?php
require 'partials/session.php';
require 'header.php';

?>


<body>
    <nav class="navbar sticky-top bg-white">
        <div class="container">
            <h2 class="text-center mx-auto welcome">Welcome to journal  <span class="name"><?php echo $_SESSION['username'] ?></span></h2>
            <a href="partials/logout.php?logout" role="button" class="btn btn-warning logOut">Log out</a>
        </div>
    </nav>

    <h3 class="text-center my-5 text-capitalize">Write your journal</h3>

    <div class="container">

        <form action="?add=journal" method="POST">
            <div class="row justify-content-around">
            <div class="col-6">
                <div class="bg-color">
                    <img src="img/flower.jpg" alt="" >
                </div>
            </div>
            <div class="col-6">
                

    
</body>

</html>