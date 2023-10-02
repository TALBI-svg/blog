<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';

if(isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    $username=getUsername($connection,$id);
    
    if($_SERVER['REQUEST_METHOD']==='GET'){
        $search_id=$_GET['search_id'];

        // check if id is null
        if($search_id!=null){
            // check id exist in db
            $sql="SELECT COUNT(*) AS isSearchIdExist FROM search_history WHERE id='$search_id'";
            $statement=$connection->query($sql);
            if(!$statement){
                die("invalid query!").$connection->getMessage();
            }
            while($res=$statement->fetch()){
                $isSearchIdExist=$res['isSearchIdExist'];
                if($isSearchIdExist>0){
                    echo "ok";
                    $sql="DELETE FROM search_history WHERE id='$search_id'";
                    $statement=$connection->query($sql);
                    header("location: search.php");

                }else{
                    ?>
                    <div class="container ps-3">
                        <p class="fw-normal ps-2">Result not found plz try again!</p>
                    </div>
                    <?php
                }
            }
        }else{
            ?>
            <div class="container ps-3">
                <p class="fw-normal ps-2">Invalid or Null search_id!</p>
            </div>
            <?php
        }
    }
}else{
    header("location: ../login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>