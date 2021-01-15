<?php
require 'partials/connect.php';
require 'partials/session.php';
require 'views/header.php';

?>
    <?php
    date_default_timezone_set("Europe/Stockholm"); // sätta Svensk time

    $titleErr = "";
    $contentErr = "";

    if (isset($_SESSION['loggedIn']) && isset($_SESSION['userID'])) {
        require 'views/jounal.php'; 
  
        // Lagra journal i DB
        if (isset($_GET['add']) && $_GET['add'] == 'journal') {

                // Om det är tomt i formlär då visas error message
                if (empty($_POST['title'])) {
                    $titleErr = "Write your title";
                }
                if (empty($_POST['content'])) {
                    $contentErr = "Write your content";
                }

                if (!empty($_POST['title']) && !empty($_POST['content'])) {
                    $statement = $pdo->prepare(
                        "INSERT INTO entries (title,content,createdAt,userID)
                   VALUES (:title, :content, :createdAt, :userID)"
                    );
                    $statement->execute([
                        ":title" => $_POST['title'],
                        ":createdAt" => date("Y/m/d H:i"),
                        ":content" => $_POST['content'],
                        ":userID" => $_SESSION['userID']
                    ]);
                    // Undvika att skicka data till DB när man reload sidan. För att inte ska spara dubbla inlägg i DB
                    header('Location: index.php');
                }
        } // end of Lagra journal i DB
        ?>
         
                <div class="form-group row">
                    <label for="title">Title</label>
                    <div><font color="#ff0000"><?php echo $titleErr ?></font></div> <!--Error message-->
                    <!--Skapat value="<php if( !empty($_POST['title']) ){echo $_POST['title']; } ?>" för att kunna visa värdet i formulär-->
                    <input type="text" name="title" id="title" class="form-control" value="<?php if( !empty($_POST['title']) ){echo $_POST['title']; } ?>">
                </div>
                <div class="form-group row">
                    <label for="content">Content</label>
                    <div><font color="#ff0000"><?php echo $contentErr ?></font></div> <!--Error message-->
                    <textarea type="text" name="content" rows="10" id="content" ><?php if( !empty($_POST['content']) ){echo $_POST['content']; } ?></textarea>
                </div>
                
                <div class="mx-auto">
                    <input type="submit" value="Add Journal" rolr="button" class="btn btn-success add px-4">
                </div>
            </div>
           
         </div>
        </form>
    </div>

    <h3 class="text-center mt-5 text-capitalize">All your journal</h3>
    
    <?php
        // Ta bort en rad
        if(isset($_GET['entryID'])){
            $statement = $pdo->prepare("DELETE FROM entries WHERE entryID = ?");
            $statement->execute([
                $_GET['entryID']
            ]);
        }

        // Visa tidigare inlägg
        $statement = $pdo->prepare(
            "SELECT * FROM entries WHERE userID = {$_SESSION['userID']} ORDER BY createdAt DESC"
        );
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="container w-90">

            <table class="table mx-auto">
                <thead>
                    <tr class="text-uppercase">
                        <th scope="col">Date</th>
                        <th scope="col">Title</th>
                        <th scope="col">Content</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Iterera över alla innehåll
                    foreach ($data as $journal) {
                        ?>
                        <tr>
                            <td><?= $journal['createdAt'] ?></td>
                            <td><?= $journal['title'] ?></td>
                            <td><?= $journal['content'] ?></td>
                            <td><a href="?entryID=<?= $journal['entryID'] ?>" role="button" class="btn btn-danger">Delete</a></td>
                        </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    <?php
} else {
    require "views/login.php";
    require "views/register.php";
}
?>
</body>

</html>