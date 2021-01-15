<?php
//Ta bort session för att kunna loga ut med hjälp av session_destroy()
session_start();
if(isset($_GET['logout'])){
session_destroy();
header("Location: ../");
}
?>