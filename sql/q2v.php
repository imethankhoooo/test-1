<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/page.css">

    </head>
    <body>
        <h3> View Message</h3>
        <form action="q2v.php" method="post">
            <?php 
            if(isset($_SESSION['msg'])){
                echo "<ul>";
                foreach($_SESSION['msg'] as $value){
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }else
            echo "No message";
            ?>
            <input name='submit' type='submit' value='Delete All'>
            <input name = 'button' type='button' value='Add' onclick="location='q2.php'">
</form>
</body>

</html>