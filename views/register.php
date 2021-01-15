<?php
require 'partials/connect.php';
require 'partials/session.php';
require 'header.php';
?>


<body>
    <?php
    // Error message
    $nameErr = "";
    $passErr = "";
    $msg = "";
    $hasRegistered = false; // För att kunna veta om användare har registretat.

    if (isset($_GET['action']) && $_GET['action'] == 'register') {

        // Om användare matar ingenting då visas error message
    if (empty($_POST['username2'])) {
        $nameErr = "Write your name";
    }
    if (empty($_POST['password'])) {
        $passErr = "Write your password";
    }

    if (!empty($_POST['username2']) && !empty($_POST['password'])) { 
        // Kollar först om användare har redan registrerat eller inte
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $statement->execute([
            ":username" => $_POST['username2']
        ]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (password_verify($_POST['password'], $user['password']) && $_POST['username2'] == $user['username']){
           $msg = "You have already registered.";
           $hasRegistered = true;
        }
        // Annars de information lagras i DB.
        else{
            $statement = $pdo->prepare(
                "INSERT INTO users (username, password)
            VALUES (:username, :password)"
            );
            $statement->execute([
                ":username" => $_POST['username2'],
                ":password" => password_hash($_POST['password'], PASSWORD_BCRYPT),
            ]);
            // Undvika att skicka data till DB när man reload sidan. Så att det blir inte dubbla data i DB.
            header('Location: index.php');
        }
    }
}

?>
               <div class="box mt-5">
                <div class="column col-4">
                    <h2 class="text-center">Create account</h2>
                    <div><font color="#ff0000"><?php echo $msg ?></font></div> <!--Error message-->
                    <form action="?action=register" method="POST" class="form-group">
                       <div class="form-group">
                           <label  for="username2">Username</label>
                           <!-- Om användare matar in username och password som har redan registrerat då visas inte username som användare har matat in. Annars visas username som användare har matat in i formulär-->
                           <input class="form-control first-page" type="text" name="username2" id="username2"  value="<?php if($hasRegistered == true){echo "";} if($hasRegistered == false && !empty($_POST['username2']) ){echo $_POST['username2'];} ?>">
                           <div><font color="#ff0000"><?php echo $nameErr ?></font></div> <!--Error message-->
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control first-page" type="password" name="password" id="password" >
                            <div><font color="#ff0000"><?php echo $passErr ?></font></div> <!--Error message-->
                        </div>
                        <input type="submit" value="SIGN UP" class="btn btn-primary">
                    </form>
                </div><!--column col-4-->
               </div>
            </div><!--row justify-content-around mt-5-->
    </div><!--container-->
  </div><!--container-back-->
</body>

</html>