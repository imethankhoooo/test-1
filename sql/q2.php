<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>
    <body>
        <h3>Add Message</h3>
        <?php 
        if(isset($_POST['submit'])){
            if(!empty($_POST['msg'])){
                $_SESSION['msg'][]=$_POST['msg'];
                echo "Message added to session";
            }
        }
        ?>
        <form action="q2.php" method="post">
            <input name="msg">
            <input name="submit" value="Add" type="submit">
            <input name="button" type= "button" value="View" onclick="location='q2v.php'">
            </form>
</body>

</html>