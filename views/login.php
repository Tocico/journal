<?php
require 'partials/connect.php';
require 'partials/session.php';
require 'header.php';
?>

  <style>
    body{
        background-image: url(./img/venice.jpg);
        background-position: center center;
		background-repeat:  no-repeat;
		background-attachment: fixed;
		background-size:  cover;
    }
  </style>

<body>
    <div class="container-back">
    <?php
    // Error message
    $nameErr = "";
    $passErr = "";

    if (isset($_GET['action']) && $_GET['action'] == 'login') {

        // Om användare matar ingenting då visas error message
        if (empty($_POST['username'])) {
            $nameErr = "Write your name";
        }
        if (empty($_POST['password'])) {
            $passErr = "Write your password";
        }

        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $statement->execute([
                ":username" => $_POST['username']
            ]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if ($_POST['username'] == $user['username']) {
                if (password_verify($_POST['password'], $user['password'])) {
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['userID'] = $user['userID'];  // använd session för att kunna använda userID på journal sidan
                    $_SESSION['username'] = $user['username'];  // använd session för att kunna visa username på journal sidan 
                    header('Location: index.php');
                } else {
                    // Om password inte stämmer visas message
                    $passErr = "Wrong password";
                }
            } else {
                // Om username inte existera i DB visas message
                $nameErr = "We can not find your name";
            }
        }
    }

    ?>
    <h1 class="text-center my-5">Journal</h1>


    <div class="container">
        <div class="row justify-content-around mx-auto">
            <div class="box mt-5">
              <div class="col-md-4 column">
                <h2 class="text-center">Sign in</h2>
                <form action="?action=login" method="POST" class="form-group">

                    <div class="form-group">
                        <label for="username">Username</label>
                        <!--visas username som användare har matat in i formulär-->
                        <input class="form-control first-page" type="text" name="username" id="username" value="<?php if (!empty($_POST['username'])) {echo $_POST['username'];} ?>">
                        <div>
                            <font color="#ff0000"><?php echo $nameErr ?></font>
                        </div>
                        <!--Error message-->
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control first-page" type="password" name="password" id="password">
                        <div>
                            <font color="#ff0000"><?php echo $passErr ?></font>
                        </div>
                        <!--Error message-->
                    </div>
                    <input type="submit" value="LOGIN" class="btn btn-primary">
                </form>
            </div>
            </div>
     