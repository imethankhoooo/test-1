<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>
    <body>
        <h3>My To-do List</h3>
        <?php 
        if(isset($_COOKIE['tasks'])){
            $task = explode('|',$_COOKIE['tasks']);
        }else{
            $task = array();
        }

        if(isset($_POST['submit'])){
            if($_POST['submit']=='Add')
            if(!empty($_POST['task'])){
                $task[]=$_POST['task'];

            }
            if($_POST['submit']=='X'){
                $k=$_POST['k'];
                unset($task[$k]);
                $task = array_values($task);
            }
        }
        foreach($task as $key => $value){
            echo "<form action = 'p1.php' method='post'>";
            echo " <input name='k' value=$key type='hidden'>";
            echo "<p><input name='submit' value='x' type='submit'>".
            ($key+1).". $value</p>";
            echo " </form>";
        }
        $tasks= implode('|',$task);
        setcookie('tasks',$tasks);
        ?>
        <form action="p1.php" method="post">
            <input name="task">
            <input name="submit" value="Add" type="submit">
    </form>
</body>

</html>